/**
 * @fileOverview Portuguese(PortugalV5.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 12/02/2017
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
        if (ltr.match(/[|!"#$%QWEÉRTAÁÃSDFG>ZXCVB]/)) {
            this.shiftright = true;
        // phpcs:ignore
        } else if (ltr.match(/[&/()=?»YUÚIÍOÓÕP*`HJKLÇª^NM;:_]/)) {
            this.shiftleft = true;
        }
    }
    // phpcs:ignore
    if (ltr.match(/[áéíóú]/)) {
        this.accent = true;
    }
    // phpcs:ignore
    if (ltr.match(/[ÁÉÍÓÚ]/)) {
        this.accent = true;
    }
    if (ltr === 'à') {
        this.shiftleft = true;
        this.accent = true;
    }
    if (ltr === 'À') {
        this.shiftleft = true;
        this.accent = true;
    }
    // phpcs:ignore
    if (ltr.match(/[ãõ]/)) {
        this.tilde = true;
    }
    // phpcs:ignore
    if (ltr.match(/[ÃÕ]/)) {
        this.tilde = true;
    }
    // phpcs:ignore
    if (ltr.match(/[âê]/i)) {
        this.shiftright = true;
        this.tilde = true;
    } else if (ltr.match(/[ô]/)) {
        this.shiftleft = true;
        this.tilde = true;
    }
    // phpcs:ignore
    if (ltr.match(/[@£§¨{\[\]}]/i)) {
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
        if (this.accent) {
            document.getElementById('jkeyacuteaccent').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeytilde').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // phpcs:ignore
            if (this.chr.match(/[asdfjklç]/i)) {
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
        if (this.accent) {
            document.getElementById('jkeyacuteaccent').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeytilde').className = "normal";
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
    // phpcs:ignore
    } else if (tCrka.match(/[\\|1!qaãáàâz<>0=}pç\-_'?+*¨ºª«»´`~^]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2"@wsx9)\]oóôõl.:]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#£e€éêdc8(\[iík,;]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$§rfv5%tgb6&yhn7{uújm/]/i)) {
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
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '£') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '§') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&') {
        return "jkey6";
    } else if (tCrka === '/' || tCrka === '{') {
        return "jkey7";
    } else if (tCrka === '(' || tCrka === '[') {
        return "jkey8";
    } else if (tCrka === ')' || tCrka === ']') {
        return "jkey9";
    } else if (tCrka === '=' || tCrka === '}') {
        return "jkey0";
    } else if (tCrka === '\'' || tCrka === '?') {
        return "jkeyapostrophe";
    } else if (tCrka === '«' || tCrka === '»') {
        return "jkeyquote";
    } else if (tCrka === '+' || tCrka === '*' || tCrka === '¨') {
        return "jkeyplus";
    } else if (tCrka === '´' || tCrka === '`' || tCrka === ']') {
        return "jkeyacuteaccent";
    } else if (tCrka === 'ª') {
        return "jkeyº";
    } else if (tCrka === '~' || tCrka === '^') {
        return "jkeytilde";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeylessthan";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === 'ã' || tCrka === 'á' || tCrka === 'à' || tCrka === 'â') {
        return "jkeya";
    } else if (tCrka === 'é' || tCrka === 'ê' || tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'õ' || tCrka === 'ó' || tCrka === 'ô') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú') {
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
    return str.length === 1 && str.match(/[!-ﻼ]/i);
}
