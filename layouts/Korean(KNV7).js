/**
 * @fileOverview Korean(KNV7.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 7.0
 * @since 20260308
 */

var THE_LAYOUT,
    ended = false,
    started = false,
    doStart,
    getPressedChar,
    combinedChar,
    combinedCharWait,
    $,
    currentChar,
    show_keyboard,
    currentPos,
    fullText,
    doKonec,
    moveCursor,
    napake,
    keyupFirst,
    event;

/**
 * Check for combined character.
 * @param {char} chr.
 * @returns {char}.
 */
function isCombined(chr) {
    return false;
}

/**
 * Process keyup for combined character.
 * @param {char} e.
 * @returns {bolean}.
 */
function keyupCombined(e) {
    return false;
}

/**
 * Process keyupFirst.
 * @param {char} event.
 * @returns {bolean}.
 */
function keyupFirst(event) {
    return false;
}

THE_LAYOUT = 'Korean(KNV7)';

/**
 * Decompose a Hangul syllable (U+AC00–U+D7A3) into the individual jamo keystrokes
 * needed to type it on a Korean 2-set keyboard.  The returned array is in typing order:
 * [onset, vowel_component(s), coda_component(s)].  Non-syllable characters are returned
 * as a single-element array unchanged.
 * @param {string} chr  A single Unicode character.
 * @returns {Array.<string>}
 */
function knv7ToKeystrokes(chr) {
    if (!chr || typeof chr !== 'string' || chr.length !== 1) {
        return [chr];
    }
    var code = chr.charCodeAt(0);
    if (code < 0xAC00 || code > 0xD7A3) {
        return [chr];
    }

    // 19 choseong (onset consonants).
    var cho = ['ㄱ','ㄲ','ㄴ','ㄷ','ㄸ','ㄹ','ㅁ','ㅂ','ㅃ','ㅅ','ㅆ','ㅇ','ㅈ','ㅉ','ㅊ','ㅋ','ㅌ','ㅍ','ㅎ'];
    // 21 jungseong (vowels); compound vowels are arrays of their two components.
    var jung = [
        'ㅏ','ㅐ','ㅑ','ㅒ','ㅓ','ㅔ','ㅕ','ㅖ','ㅗ',
        ['ㅗ','ㅏ'],['ㅗ','ㅐ'],['ㅗ','ㅣ'],
        'ㅛ','ㅜ',
        ['ㅜ','ㅓ'],['ㅜ','ㅔ'],['ㅜ','ㅣ'],
        'ㅠ','ㅡ',['ㅡ','ㅣ'],'ㅣ'
    ];
    // 28 jongseong slots (0 = none); compound codas are arrays of two consonants.
    var jong = [
        null,'ㄱ','ㄲ',['ㄱ','ㅅ'],
        'ㄴ',['ㄴ','ㅈ'],['ㄴ','ㅎ'],
        'ㄷ','ㄹ',
        ['ㄹ','ㄱ'],['ㄹ','ㅁ'],['ㄹ','ㅂ'],['ㄹ','ㅅ'],['ㄹ','ㅌ'],['ㄹ','ㅍ'],['ㄹ','ㅎ'],
        'ㅁ','ㅂ',['ㅂ','ㅅ'],
        'ㅅ','ㅆ','ㅇ','ㅈ','ㅊ','ㅋ','ㅌ','ㅍ','ㅎ'
    ];

    var idx = code - 0xAC00;
    var ci = Math.floor(idx / 588);
    var vi = Math.floor((idx % 588) / 28);
    var ti = idx % 28;

    var keys = [cho[ci]];

    var vowel = jung[vi];
    if (Array.isArray(vowel)) {
        keys = keys.concat(vowel);
    } else {
        keys.push(vowel);
    }

    if (ti > 0 && jong[ti]) {
        var coda = jong[ti];
        if (Array.isArray(coda)) {
            keys = keys.concat(coda);
        } else {
            keys.push(coda);
        }
    }

    return keys;
}

/**
 * Return shift-key metadata for a single jamo or character.
 * @param {string} j  A single jamo or character.
 * @returns {{chr:string, shift:boolean, shiftleft:boolean, shiftright:boolean}}
 */
function knv7KeyInfo(j) {
    var info = {chr: j, shift: false, shiftleft: false, shiftright: false};
    if (!j || typeof j !== 'string') {
        return info;
    }
    // @codingStandardsIgnoreLine
    if (j.match(/[~!@#$%^&*()_+{}|:"<>?]/)) {
        info.shift = true;
        if (j.match(/[~!@#$%]/)) {
            info.shiftright = true;
        }
        if (j.match(/[\^&*()_+{}|:"<>?]/)) {
            info.shiftleft = true;
        }
    }
    // Won/backslash variants are unshifted; only pipe uses shift.
    if (j === '\u20a9' || j === '\uffe6' || j === '\\') {
        info.shift = false; info.shiftleft = false; info.shiftright = false;
    } else if (j === '|') {
        info.shift = true; info.shiftleft = true; info.shiftright = false;
    }
    // Korean doubled consonants require right shift (left-hand keys).
    if (j.match(/[ㅃㅉㄸㄲㅆ]/)) {
        info.shift = true; info.shiftright = true;
    }
    // Korean doubled vowels require left shift (right-hand keys).
    if (j.match(/[ㅒㅖ]/)) {
        info.shift = true; info.shiftleft = true;
    }
    // Latin uppercase.
    if (j.length === 1 && j !== j.toLowerCase && j !== j.toLowerCase()) {
        info.shift = true;
        if (j.match(/[QWERTASDFGZXCVB]/)) {
            info.shiftright = true;
        } else if (j.match(/[YUIOPHJKLNM]/)) {
            info.shiftleft = true;
        }
    }
    return info;
}

/**
 * Check for character typed so flags can be set.
 * For Korean syllables, ALL keystroke jamo are highlighted simultaneously so
 * the student can see every key needed to compose the syllable at a glance.
 * @param {char} ltr.
 */
function keyboardElement(ltr) {
    var code = ltr ? ltr.charCodeAt(0) : 0;
    var isSyllable = (code >= 0xAC00 && code <= 0xD7A3);

    // Decompose syllable into individual keystroke jamo; non-syllable chars stay as-is.
    var jamoList = isSyllable ? knv7ToKeystrokes(ltr) : [ltr];

    // Build per-keystroke metadata.
    this.keys = jamoList.map(function(j) { return knv7KeyInfo(j); });

    // Legacy single-char interface expected by the rest of the codebase.
    this.chr = this.keys[0].chr;
    this.shift = this.keys[0].shift;
    this.shiftleft = this.keys[0].shiftleft;
    this.shiftright = this.keys[0].shiftright;
    this.alt = false;

    this.turnOn = function() {
        for (var i = 0; i < this.keys.length; i++) {
            var ki = this.keys[i];
            var target = document.getElementById(getKeyID(ki.chr));
            var lc = (ki.chr && typeof ki.chr.toLowerCase === 'function') ? ki.chr.toLowerCase() : ki.chr;
            var finger = thenFinger(lc);
            if (finger < 1 || finger > 4) {
                finger = 4;
            }
            if (target) {
                if (ki.chr === ' ') {
                    target.className = 'nextSpace';
                } else {
                    target.className = 'next' + finger;
                }
            }
            if (ki.chr === '\n' || ki.chr === '\r\n' || ki.chr === '\n\r' || ki.chr === '\r') {
                document.getElementById('jkeyenter').className = 'next4';
            }
            if (ki.shiftleft) {
                document.getElementById('jkeyshiftl').className = 'next4';
            }
            if (ki.shiftright) {
                document.getElementById('jkeyshiftr').className = 'next4';
            }
        }
    };

    this.turnOff = function() {
        for (var i = 0; i < this.keys.length; i++) {
            var ki = this.keys[i];
            var target = document.getElementById(getKeyID(ki.chr));
            if (target) {
                // @codingStandardsIgnoreLine
                if (isLetter(ki.chr) && ki.chr.match(/[asdfjkl;]/i)) {
                    target.className = 'finger' + thenFinger(ki.chr.toLowerCase());
                } else {
                    target.className = 'normal';
                }
            }
            if (ki.chr === '\n' || ki.chr === '\r\n' || ki.chr === '\n\r' || ki.chr === '\r') {
                document.getElementById('jkeyenter').className = 'normal';
            }
            if (ki.shiftleft) {
                document.getElementById('jkeyshiftl').className = 'normal';
            }
            if (ki.shiftright) {
                document.getElementById('jkeyshiftr').className = 'normal';
            }
        }
    };
}

/**
 * Set color flag based on current character.
 * @param {char} tCrka.
 * @returns {number}.
 */
function thenFinger(tCrka) {
//console.log("In the thenFinger function and tCrka is: "+ tCrka);
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[`~1!qㅂㅃaㅁzㅋ0)pㅔㅖ;:'/?\-_[{'"=+\]}\\|]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2@wㅈㅉsㄴxㅌ9(oㅐㅒlㅣ.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3#eㄷㄸdㅇcㅊ8*iㅑkㅏ,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$rㄱㄲfㄹvㅍ5%tㅅㅆgㅎbㅠ6^yㅛhㅗnㅜ7&uㅕjㅓmㅡ]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 4; // Fall back to an existing highlight class.
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {char} tCrka.
 * @returns {char}.
 */
function getKeyID(tCrka) {
//console.log("In the getKeyID function and tCrka is: "+ tCrka);

    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === ',') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '`') {
        return "jkeybackquote";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '^') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeybracketr";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === "'" || tCrka === '"') {
        return "jkeycrtica";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === '₩' || tCrka === '￦') {
        return "jkeybackslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else if (tCrka === '~' || tCrka === '`') {
        return "jkeybackquote";
    } else if (tCrka === 'ㅂ' || tCrka === 'ㅃ') {
        return "jkeyㅂ";
    } else if (tCrka === 'ㅈ' || tCrka === 'ㅉ') {
        return "jkeyㅈ";
    } else if (tCrka === 'ㄷ' || tCrka === 'ㄸ') {
        return "jkeyㄷ";
    } else if (tCrka === 'ㄱ' || tCrka === 'ㄲ') {
        return "jkeyㄱ";
    } else if (tCrka === 'ㅅ' || tCrka === 'ㅆ') {
        return "jkeyㅅ";
    } else if (tCrka === 'ㅛ') {
        return "jkeyㅛ";
    } else if (tCrka === 'ㅕ') {
        return "jkeyㅕ";
    } else if (tCrka === 'ㅑ') {
        return "jkeyㅑ";
    } else if (tCrka === 'ㅐ' || tCrka === 'ㅒ') {
        return "jkeyㅐ";
    } else if (tCrka === 'ㅔ' || tCrka === 'ㅖ') {
        return "jkeyㅔ";
    } else if (tCrka === 'ㅁ') {
        return "jkeya";
    } else if (tCrka === 'ㄴ') {
        return "jkeys";
    } else if (tCrka === 'ㅇ') {
        return "jkeyd";
    } else if (tCrka === 'ㄹ') {
        return "jkeyf";
    } else if (tCrka === 'ㅎ') {
        return "jkeyg";
    } else if (tCrka === 'ㅗ') {
        return "jkeyh";
    } else if (tCrka === 'ㅓ') {
        return "jkeyj";
    } else if (tCrka === 'ㅏ') {
        return "jkeyk";
    } else if (tCrka === 'ㅣ') {
        return "jkeyl";
    } else if (tCrka === 'ㅋ') {
        return "jkeyz";
    } else if (tCrka === 'ㅌ') {
        return "jkeyx";
    } else if (tCrka === 'ㅊ') {
        return "jkeyc";
    } else if (tCrka === 'ㅍ') {
        return "jkeyv";
    } else if (tCrka === 'ㅠ') {
        return "jkeyb";
    } else if (tCrka === 'ㅜ') {
        return "jkeyn";
    } else if (tCrka === 'ㅡ') {
        return "jkeym";
    } else {
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {char} str.
 * @returns {(int|Array)}.
 */
function isLetter(str) {
//console.log("In the isLetter function and str is: "+ str);

    return str.length === 1 && str.match(/[a-zㄱ-ㅣ]/i);
}
