/**
 * @fileOverview Icelandic(ISUKV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20210331
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
        // phpcs:ignore
        if (ltr.match(/[¨!"#$%>]/i)) {
            this.shiftright = true;
        } else if (ltr.match(/[&/()=_();:?*Þ]/)) {
            this.shiftleft = true;
        }
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[QWEÉËRTAÁÄÅSDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[ÖYÝUÚÜIÍÏOÓÖPÐHJKLÆNM]/)) {
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // phpcs:ignore
    if (ltr.match(/[¦€{[\]}\\|µ^~`]/)) {
        this.alt = true;
    //} else if (ltr.match(/[ÉëÁ]/)) {
    //    this.shiftright = true;
    //    this.alt = true;
    //} else if (ltr.match(/[ÚÍÓ]/)) {
    //    this.shiftleft = true;
    //    this.alt = true;
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
            if (this.chr.match(/[asdfjklæ]/i)) {
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
    } else if (tCrka.match(/[°¨1!qaáäå<>|z0ö\\)pðæ´+;:/?\-_\[@{'=\]Þ}~#]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2"wsx9(oól.]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3£eéëdc8*iíïk,;]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$€rfv5%tgb6^yýÿhn7&uúüjm:]/i)) {
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
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '°' || tCrka === '¨') {
        return "jkey°";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '€') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&') {
        return "jkey6";
    } else if (tCrka === '/') {
        return "jkey7";
    } else if (tCrka === '(') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '=') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeybracketr";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === '*' || tCrka === '`') {
        return "jkey+";
    } else if (tCrka === '\\') {
        return "jkeyö";
    } else if (tCrka === 'é' || tCrka === 'ë') {
        return "jkeye";
    } else if (tCrka === 'ý' || tCrka === 'ÿ') {
        return "jkeyy";
    } else if (tCrka === 'ú' || tCrka === 'ü') {
        return "jkeyu";
    } else if (tCrka === 'í' || tCrka === 'ï') {
        return "jkeyi";
    } else if (tCrka === 'ó') {
        return "jkeyo";
    } else if (tCrka === '\'' || tCrka === '?' || tCrka === '~') {
        return "jkeyapos";
    } else if (tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === '^') {
        return "jkey´";
    } else if (tCrka === '#' || tCrka === '~') {
        return "jkey#";
    } else if (tCrka === '>' || tCrka === '|') {
        return "jkey<";
    } else if (tCrka === 'þ' || tCrka === 'Þ') {
        return "jkeyþ";
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
