/**
 * @fileOverview English International(INTLV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
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
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[QWERTASDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUIOPHJKLNM]/)) {
            this.shiftleft = true;
        }
    } else {
        // phpcs:ignore
        if (ltr.match(/[~!@#$%|]/i)) {
            this.shiftright = true;
        } else if (ltr.match(/[\^&*()_+{}|:<>?]/)) {
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // phpcs:ignore
    if (ltr.match(/[¡áßðø¶´äåé®þæ©ñµç¿üúíóö«»¬]/)) {
        this.alt = true;
    } else if (ltr.match(/[¹ÄÅÉÞÁ§Ø°¨¦ÜÚÍÓÖÆ¢ÑÇ]/)) {
        this.shiftright = true;
        this.alt = true;
    } else if (ltr.match(/[]/)) {
        this.shiftleft = true;
        this.alt = true;
    }
    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
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
        if (isLetter(this.chr)) {
        // phpcs:ignore
            if (this.chr.match(/[asdfjkl;]/i)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
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
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // phpcs:ignore
    } else if (tCrka.match(/[`~1!¡¹qäaázæ0¥’)pö;:¶°/?¿\-_[{«»¬'"´¨=+×÷\]}\\|]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@²wåsß§x9(‘oólø.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#³eédðc©¢8*¾iík,<ç]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$¤£r®fv5%€tþgb6^¼yühnñ7&½uújmµ]/i)) {
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
    } else if (tCrka === ',' || tCrka === '<' || tCrka === 'ç') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '¥') {
        return "jkeyminus";
    } else if (tCrka === '`' || tCrka === '~') {
        return "jkeybackquote";
    } else if (tCrka === '!' || tCrka === '¡' || tCrka === '¹') {
        return "jkey1";
    } else if (tCrka === '@' || tCrka === '²') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '³') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '£' || tCrka === '¤') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '€') {
        return "jkey5";
    } else if (tCrka === '^' || tCrka === '¼') {
        return "jkey6";
    } else if (tCrka === '&' || tCrka === '½') {
        return "jkey7";
    } else if (tCrka === '*' || tCrka === '¾') {
        return "jkey8";
    } else if (tCrka === '(' || tCrka === '‘') {
        return "jkey9";
    } else if (tCrka === ')' || tCrka === '’') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '¥') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '×' || tCrka === '÷') {
        return "jkeyequal";
    } else if (tCrka === '[' || tCrka === '{' || tCrka === '«') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}' || tCrka === '»') {
        return "jkeybracketr";
    } else if (tCrka === "\\" || tCrka === '|' || tCrka === '¬' || tCrka === '¦') {
        return "jkeybackslash";
    } else if (tCrka === ';' || tCrka === ':' || tCrka === '¶' || tCrka === '°') {
        return "jkeysemicolon";
    } else if (tCrka === "'" || tCrka === '"' || tCrka === '´' || tCrka === '¨') {
        return "jkeycrtica";
    } else if (tCrka === ',' || tCrka === '<' || tCrka === 'ç') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequal";
    } else if (tCrka === '?' || tCrka === '/' || tCrka === '¿') {
        return "jkeyslash";
     } else if (tCrka === 'ä') {
        return "jkeyq";       
    } else if (tCrka === 'å') {
        return "jkeyw";
    } else if (tCrka === 'é') {
        return "jkeye";
    } else if (tCrka === '®') {
        return "jkeyr";
    } else if (tCrka === 'þ') {
        return "jkeyt";
    } else if (tCrka === 'ü') {
        return "jkeyy";
    } else if (tCrka === 'ú') {
        return "jkeyu";
    } else if (tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'ó') {
        return "jkeyo";
    } else if (tCrka === 'ö') {
        return "jkeyp";
    } else if (tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === 'ß' || tCrka === '§') {
        return "jkeys";
    } else if (tCrka === 'ð') {
        return "jkeyd";
    } else if (tCrka === 'ø') {
        return "jkeyl";
    } else if (tCrka === 'æ') {
        return "jkeyz";
    } else if (tCrka === '©' || tCrka === '¢') {
        return "jkeyc";
    } else if (tCrka === 'ñ') {
        return "jkeyn";
    } else if (tCrka === 'µ') {
        return "jkeym";
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
    return str.length === 1 && str.match(/[!-ﻼ]/i);
}
