/**
 * @fileOverview Japanese(JPV7) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 03/05/2019
 */

/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
    return false;
}

/**
 * Process keyup for combined character.
 * @param {string} e The combined character.
 * @returns {bolean} The result.
 */
function keyupCombined(e) {
    return false;
}

/**
 * Process keyupFirst.
 * @param {string} event Type of event.
 * @returns {bolean} The event.
 */
function keyupFirst(event) {
    return false;
}

/**
 * Map Japanese characters to representative roman keys for visual guidance.
 * Unmapped characters (for example most kanji) return empty string.
 *
 * @param {string} chr Typed character.
 * @returns {string}
 */
function mapJapaneseHighlightChar(chr) {
    if (!chr || chr.length !== 1) {
        return chr;
    }

    // Keep direct highlighting for plain ASCII keys (letters, numbers, punctuation).
    if (/^[\x20-\x7e]$/.test(chr)) {
        return chr;
    }

    var direct = {
        // Hiragana (JIS keytop mapping).
        '\u308d': '`', '\u306c': '1', '\u3075': '2', '\u3042': '3', '\u3046': '4', '\u3048': '5',
        '\u304a': '6', '\u3084': '7', '\u3086': '8', '\u3088': '9', '\u308f': '0', '\u3092': '0', '\u307b': '-',
        '\u3078': '=', '\u305f': 'q', '\u3066': 'w', '\u3044': 'e', '\u3059': 'r', '\u304b': 't',
        '\u3093': 'y', '\u306a': 'u', '\u306b': 'i', '\u3089': 'o', '\u305b': 'p', '\u3080': '\\',
        '\u3061': 'a', '\u3068': 's', '\u3057': 'd', '\u306f': 'f', '\u304d': 'g', '\u304f': 'h',
        '\u307e': 'j', '\u306e': 'k', '\u308a': 'l', '\u308c': ';', '\u3051': "'", '\u3064': 'z',
        '\u3055': 'x', '\u305d': 'c', '\u3072': 'v', '\u3053': 'b', '\u307f': 'n', '\u3082': 'm',
        '\u306d': ',', '\u308b': '.', '\u3081': '/',

        // Katakana counterparts.
        '\u30ed': '`', '\u30cc': '1', '\u30d5': '2', '\u30a2': '3', '\u30a6': '4', '\u30a8': '5',
        '\u30aa': '6', '\u30e4': '7', '\u30e6': '8', '\u30e8': '9', '\u30ef': '0', '\u30f2': '0', '\u30db': '-',
        '\u30d8': '=', '\u30bf': 'q', '\u30c6': 'w', '\u30a4': 'e', '\u30b9': 'r', '\u30ab': 't',
        '\u30f3': 'y', '\u30ca': 'u', '\u30cb': 'i', '\u30e9': 'o', '\u30bb': 'p', '\u30e0': '\\',
        '\u30c1': 'a', '\u30c8': 's', '\u30b7': 'd', '\u30cf': 'f', '\u30ad': 'g', '\u30af': 'h',
        '\u30de': 'j', '\u30ce': 'k', '\u30ea': 'l', '\u30ec': ';', '\u30b1': "'", '\u30c4': 'z',
        '\u30b5': 'x', '\u30bd': 'c', '\u30d2': 'v', '\u30b3': 'b', '\u30df': 'n', '\u30e2': 'm',
        '\u30cd': ',', '\u30eb': '.', '\u30e1': '/',

        // Dakuten/handakuten and punctuation commonly used in Japanese text.
        '\u309b': '[', '\u309c': ']', '\u3099': '[', '\u309a': ']',
        '\u3001': ',', '\u3002': '.', '\u30fb': '/', '\u30fc': '-', '\u3000': ' ',
        '\u300c': '[', '\u300d': ']', '\u300e': '{', '\u300f': '}',
        '\u3008': '<', '\u3009': '>', '\u300a': '<', '\u300b': '>', '\u3010': '[', '\u3011': ']',
        '\uff08': '(', '\uff09': ')', '\uff0c': ',', '\uff0e': '.', '\uff1a': ':', '\uff1b': ';',
        '\uff01': '!', '\uff1f': '?', '\uff1c': '<', '\uff1e': '>', '\uff3b': '[', '\uff3d': ']',
        '\uff5b': '{', '\uff5d': '}'
    };
    if (Object.prototype.hasOwnProperty.call(direct, chr)) {
        return direct[chr];
    }

    // Small kana and voiced/semivoiced kana fall back to the base kana key.
    var folding = {
        '\u3041': '\u3042', '\u3043': '\u3044', '\u3045': '\u3046', '\u3047': '\u3048', '\u3049': '\u304a',
        '\u30a1': '\u30a2', '\u30a3': '\u30a4', '\u30a5': '\u30a6', '\u30a7': '\u30a8', '\u30a9': '\u30aa',
        '\u3083': '\u3084', '\u3085': '\u3086', '\u3087': '\u3088', '\u3063': '\u3064', '\u308e': '\u308f',
        '\u30e3': '\u30e4', '\u30e5': '\u30e6', '\u30e7': '\u30e8', '\u30c3': '\u30c4', '\u30ee': '\u30ef',
        '\u30f5': '\u30ab', '\u30f6': '\u30b1', '\u3095': '\u304b', '\u3096': '\u3051',
        '\u304c': '\u304b', '\u304e': '\u304d', '\u3050': '\u304f', '\u3052': '\u3051', '\u3054': '\u3053',
        '\u3056': '\u3055', '\u3058': '\u3057', '\u305a': '\u3059', '\u305c': '\u305b', '\u305e': '\u305d',
        '\u3060': '\u305f', '\u3062': '\u3061', '\u3065': '\u3064', '\u3067': '\u3066', '\u3069': '\u3068',
        '\u3070': '\u306f', '\u3073': '\u3072', '\u3076': '\u3075', '\u3079': '\u3078', '\u307c': '\u307b',
        '\u3071': '\u306f', '\u3074': '\u3072', '\u3077': '\u3075', '\u307a': '\u3078', '\u307d': '\u307b',
        '\u3094': '\u3046', '\u309e': '\u309d', '\u30f7': '\u30ef', '\u30f8': '\u30a4', '\u30f9': '\u30a8',
        '\u30fa': '\u30f2', '\u30ac': '\u30ab', '\u30ae': '\u30ad', '\u30b0': '\u30af',
        '\u30b2': '\u30b1', '\u30b4': '\u30b3', '\u30b6': '\u30b5', '\u30b8': '\u30b7', '\u30ba': '\u30b9',
        '\u30bc': '\u30bb', '\u30be': '\u30bd', '\u30c0': '\u30bf', '\u30c2': '\u30c1', '\u30c5': '\u30c4',
        '\u30c7': '\u30c6', '\u30c9': '\u30c8', '\u30d0': '\u30cf', '\u30d3': '\u30d2', '\u30d6': '\u30d5',
        '\u30d9': '\u30d8', '\u30dc': '\u30db', '\u30d1': '\u30cf', '\u30d4': '\u30d2', '\u30d7': '\u30d5',
        '\u30da': '\u30d8', '\u30dd': '\u30db', '\u30f4': '\u30a6'
    };
    if (Object.prototype.hasOwnProperty.call(folding, chr)) {
        return mapJapaneseHighlightChar(folding[chr]);
    }

    // Kana iteration marks and prolonged marks fallback.
    if (chr === '\u309d' || chr === '\u30fd') {
        return '/';
    }

    return '';
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.rawchr = ltr;
    this.chr = ltr.toLowerCase();
    this.hlchr = mapJapaneseHighlightChar(this.rawchr);
    this.hlkeychr = isLetter(this.hlchr) ? this.hlchr.toLowerCase() : this.hlchr;
    this.alt = false;
    this.shiftleft = false;
    this.shiftright = false;
    if (isLetter(this.hlchr)) {
        // Shift should only be highlighted for uppercase letters.
        if (this.hlchr !== this.hlchr.toLowerCase()) {
            if (this.hlchr.match(/[QWERTASDFGZXCVB]/)) {
                this.shiftright = true;
            } else if (this.hlchr.match(/[YUIOPHJKLNM]/)) {
                this.shiftleft = true;
            }
        }
    } else {
        if (this.hlchr && this.hlchr.match(/[~!@#$%]/)) {
            this.shiftright = true;
        } else if (this.hlchr && this.hlchr.match(/[\^&*()_+{}|:"<>?]/)) {
            this.shiftleft = true;
        }
    }
    this.turnOn = function() {
        var keyid = getKeyID(this.hlkeychr);
        var keyel = keyid ? document.getElementById(keyid) : null;
        if (isLetter(this.hlkeychr)) {
            if (keyel) {
                keyel.className = "next" + thenFinger(this.hlkeychr.toLowerCase());
            }
        } else if (this.chr === ' ') {
            if (keyel) {
                keyel.className = "nextSpace";
            }
        } else {
            // V1.1 Japanese highlight: map kana to representative roman-key guidance.
            if (keyel) {
                keyel.className = "next" + thenFinger(this.hlkeychr.toLowerCase());
            }
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            keyel = document.getElementById('jkeyenter');
            if (keyel) {
                keyel.className = "next4";
            }
        }
        if (this.shiftleft) {
            keyel = document.getElementById('jkeyshiftl');
            if (keyel) {
                keyel.className = "next4";
            }
        }
        if (this.shiftright) {
            keyel = document.getElementById('jkeyshiftr');
            if (keyel) {
                keyel.className = "next4";
            }
        }
        if (this.alt) {
            keyel = document.getElementById('jkeyaltgr');
            if (keyel) {
                keyel.className = "nextSpace";
            }
        }
    };
    this.turnOff = function() {
        var keyid = getKeyID(this.hlkeychr);
        var keyel = keyid ? document.getElementById(keyid) : null;
        if (isLetter(this.hlkeychr)) {
        // Phpcs:ignore
            if (this.hlkeychr.match(/[asdfjkl;]/i)) {
                if (keyel) {
                    keyel.className = "finger" + thenFinger(this.hlkeychr.toLowerCase());
                }
            } else {
                if (keyel) {
                    keyel.className = "normal";
                }
            }
        } else {
            if (keyel) {
                keyel.className = "normal";
            }
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            keyel = document.getElementById('jkeyenter');
            if (keyel) {
                keyel.className = "normal";
            }
        }
        if (this.shiftleft) {
            keyel = document.getElementById('jkeyshiftl');
            if (keyel) {
                keyel.className = "normal";
            }
        }
        if (this.shiftright) {
            keyel = document.getElementById('jkeyshiftr');
            if (keyel) {
                keyel.className = "normal";
            }
        }
        if (this.alt) {
            keyel = document.getElementById('jkeyaltgr');
            if (keyel) {
                keyel.className = "normal";
            }
        }
    };
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // phpcs:ignore
    } else if (tCrka.match(/[`~1!qaz0)p;:/?\-_[{'"=+\]}\\|]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@wsx9(ol.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#edc8*ik,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$rfv5%tgb6^yhn7&ujm]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {string} tCrka The current character.
 * @returns {string}.
 */
function getKeyID(tCrka) {
    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '~' || tCrka === '`') {
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
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else {
        if (!tCrka || tCrka.length !== 1) {
            return "";
        }
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {string} str The current letter.
 * @returns {(number|Array)}.
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[a-z]/i);
}
