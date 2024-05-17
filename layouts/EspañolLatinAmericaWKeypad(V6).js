/**
 * @fileOverview SpanishLatinAmericaWKeypad(V6.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 6.1
 * @since 20240516
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
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").on("keyup", "#tb1", keyupCombined);
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        this.shift = ltr.toUpperCase() === ltr && ltr != '¡' && ltr != '`';
        if (ltr.match(/[QWEÉRTAÁSDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUÚÜIÍOÓPHJKLÑNMÇ]/)) {
            this.shiftleft = true;
        }
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[ª>!"·$%]/i)) {
            this.shiftright = true;
            this.shiftleft = false;
        // @codingStandardsIgnoreLine
        } else if (ltr.match(/[&/()=?¿^*¨;:_]/i)) {
            this.shiftright = false;
            this.shiftleft = true;
        } else {
            this.shiftright = false;
            this.shiftleft = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[áéíóú]/)) {
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ÁÉÍÓÚ]/)) {
        this.accent = true;
    }
    if (ltr === 'ü') {
        this.accent = true;
        this.shiftleft = true;

    }
    if (ltr === 'Ü') {
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[\\|@#~€¬\[\]{}]/i)) {
        this.alt = true;
    }
    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
            // If this.chr is in the keypad, highlight it. Asterisk is a special case.
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[0123456789./+-]/i)) {
                document.getElementById(getKeyID(this.chr) + 'p').className = "next" + thenPadFinger(this.chr.toLowerCase());
            }
            if (this.chr === '*') {
                document.getElementById('jkey*p').className = "next" + thenPadFinger(this.chr.toLowerCase());
            }
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
            document.getElementById('jkeyenterp').className = "next4";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyrighttick').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklñ]/i)) {
                // Turns off highlight of normal home row keys.
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
                // Turns off highlight of keypad home row keys.
            } else if (this.chr.match(/[456+]/i)) {
                // This turns off normal 456+ highlights from top row.
                document.getElementById(getKeyID(this.chr)).className = "normal";
                // This turns off 456+ highlights in the keypad home row.
                document.getElementById(getKeyID(this.chr) + 'p').className = "finger" + thenPadFinger(this.chr.toLowerCase());
            } else {
                // Turns off keyboard highlight for all keys but home row and enter.
                document.getElementById(getKeyID(this.chr)).className = "normal";
                // Turns off keypad highlights, except its homerow.
                // @codingStandardsIgnoreLine
                if (this.chr.match(/[0123789./-]/i)) {
                    document.getElementById(getKeyID(this.chr) + 'p').className = "normal";
                }
                // Needed to turn off keypad * highlight.
                if (this.chr === '*') {
                    document.getElementById('jkey*p').className = "normal";
                }
            }
        } else {
            // I think this turns off the spacebar highlight.
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        // Turns off highlight for Enter keys.
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
            document.getElementById(getKeyID(this.chr) + 'p').className = "normal";
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
        if (this.accent) {
            document.getElementById('jkeyrighttick').className = "normal";
        }
    }
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[ºª\\1!|qaáz<>0=pñ\'?`^\[´¨{\-_¡¿+*\]ç}]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"@wsx9)oól.:]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3·#eé€dc8(iík,;]/i)) {
        return 2; // Highlight the correct key above in yellow.    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$~rf5%€tgv6&¬yhnb7/uúüjm]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenPadFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[-+]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[*963.]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[//852]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[7410]/i)) {
        return 1; // Highlight the correct key above in blue.
    // @codingStandardsIgnoreLine
    } else {
        return 6; // Do not change any highlight.
    // @codingStandardsIgnoreLine
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
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '!' || tCrka === '|') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '·' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$'|| tCrka === '~') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&' || tCrka === '¬') {
        return "jkey6";
    } else if (tCrka === '/') {
        return "jkey7";
    } else if (tCrka === '(') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '=') {
        return "jkey0";
    } else if (tCrka === '`' || tCrka === '^' || tCrka === '[') {
        return "jkeylefttick";
    } else if (tCrka === '´' || tCrka === '¨' || tCrka === '{') {
        return "jkeyrighttick";
    } else if (tCrka === 'ç' || tCrka === '}') {
        return "jkeyç";
    } else if (tCrka === "'" || tCrka === '?') {
        return "jkeyapostrophe";
    } else if (tCrka === '*' || tCrka === '+' || tCrka === ']') {
        return "jkeyplus";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === 'º' || tCrka === 'ª' || tCrka === '\\') {
        return "jkeytilde";
    } else if (tCrka === '¿') {
        return 'jkey¡';
    } else if (tCrka === 'a' || tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === 'e' || tCrka === 'é' || tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'o' || tCrka === 'ó') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú' || tCrka === 'ü') {
        return "jkeyu";
                                                                 
    } else {
        return "jkey" + tCrka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 * @param {string} str The current letter.
 * @returns {(number|Array)}.
 */
function isLetter(str) {
    // return str.length === 1 && str.match(/[!-ﻼ]/i);
    return str.length === 1 && str.match(/[a-z0123456789`¡ñçáéíóúü]/i);
    // When I try to use str.match(/[!-ﻼ]/i);, at least four lower case
    // keys start highlighting the Shift key, too. But they all
    // work fine using the old version.
}
