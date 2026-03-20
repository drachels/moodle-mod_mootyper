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
 * Map Hangul syllable to its initial consonant (choseong) for key highlighting.
 * @param {char} chr.
 * @returns {char}.
 */
function getLeadJamo(chr) {
    if (!chr || typeof chr !== 'string' || chr.length !== 1) {
        return chr;
    }

    var code = chr.charCodeAt(0);
    if (code < 0xAC00 || code > 0xD7A3) {
        return chr;
    }

    var choseong = [
        'ㄱ', 'ㄲ', 'ㄴ', 'ㄷ', 'ㄸ', 'ㄹ', 'ㅁ', 'ㅂ', 'ㅃ',
        'ㅅ', 'ㅆ', 'ㅇ', 'ㅈ', 'ㅉ', 'ㅊ', 'ㅋ', 'ㅌ', 'ㅍ', 'ㅎ'
    ];
    var syllableIndex = code - 0xAC00;
    var choseongIndex = Math.floor(syllableIndex / 588);

    return choseong[choseongIndex] || chr;
}

/**
 * Check for character typed so flags can be set.
 * @param {char} ltr.
 */
function keyboardElement(ltr) {
//    this.chr = ltr.toLowerCase();
    this.chr = getLeadJamo(ltr).toUpperCase();
    var shiftChar = this.chr;
    this.alt = false;
    this.shiftleft = false;
    this.shiftright = false;
//console.log("In the keyboardElement(ltr) function and this.chr is: "+ this.chr);
//    if (isLetter(ltr)) {
//        this.shift = ltr.toUpperCase() === ltr;
//    } else {
        // @codingStandardsIgnoreLine
        if (shiftChar.match(/[~!@#$%^&*()_+{}|:"<>?]/i)) {
            this.shift = true;
            // Symbols on the left side of the keyboard use the opposite (right) shift.
            if (shiftChar.match(/[~!@#$%]/)) {
                this.shiftright = true;
            }
            // Symbols on the right side use the opposite (left) shift.
            if (shiftChar.match(/[\^&*()_+{}|:"<>?]/)) {
                this.shiftleft = true;
            }
        } else {
            this.shift = false;
        }
        // Korean won/backslash key variants are unshifted; only pipe uses shift.
        if (shiftChar === '₩' || shiftChar === '￦' || shiftChar === '\\') {
            this.shift = false;
            this.shiftleft = false;
            this.shiftright = false;
        } else if (shiftChar === '|') {
            this.shift = true;
            this.shiftleft = true;
            this.shiftright = false;
        }
        // Uppercase left-hand letters and Korean doubles on left-hand keys use right shift.
        if (shiftChar.match(/[QWERTASDFGZXCVBㅃㅉㄸㄲㅆ]/)) {
            this.shiftright = true;
        }
        // Uppercase right-hand letters and Korean doubles on right-hand keys use left shift.
        if (shiftChar.match(/[YUIOPHJKLNMㅒㅖ]/)) {
            this.shiftleft = true;
        }
//    }
    this.turnOn = function() {
//console.log("In the this.turnOn function and this.chr is: "+ this.chr);
        var target = document.getElementById(getKeyID(this.chr));
        var finger = thenFinger(this.chr.toLowerCase());
        if (finger < 1 || finger > 4) {
            finger = 4;
        }
        if (isLetter(this.chr)) {
            if (target) {
                target.className = "next" + finger;
            }
        } else if (this.chr === ' ') {
            if (target) {
                target.className = "nextSpace";
            }
        } else {
            if (target) {
                target.className = "next" + finger;
            }
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
//console.log("In the this.turnOff function and this.chr is: "+ this.chr);
        var target = document.getElementById(getKeyID(this.chr));
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjkl;]/i)) {
                if (target) {
                    target.className = "finger" + thenFinger(this.chr.toLowerCase());
                }
            } else {
                if (target) {
                    target.className = "normal";
                }
            }
        } else {
            if (target) {
                target.className = "normal";
            }
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
              document.getElementById('jkeyenter').className = "normal";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
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
