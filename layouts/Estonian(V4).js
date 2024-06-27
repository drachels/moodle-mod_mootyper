/**
 * @fileOverview Estonian(V4.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.0
 * @since 03/11/2018
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
    if (ltr.match(/[šž]/)) {
        this.shift = false;
        this.alt = true;
    } else if (ltr.match(/[ŠŽ]/)) {
        this.shift = true;
        this.alt = true;
    }
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else if (ltr.match(/[ÜÕÖÄ]/)) {
        this.shift = true;
        this.alt = false;
    } else if (ltr.match(/[@£$€{\[\]}\\§^½]/)) {
        this.shift = false;
        this.alt = true;
    } else {
        // phpcs:ignore
        if (ltr.match(/[~!"#¤%&/()=\?`*>;:_]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    this.turnOn = function() {
        if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        // phpcs:ignore
            if (this.chr.match(/[asdfjklö]/i)) {
            document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
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
    } else if (tCrka.match(/[\n~ˇ1!qa><|zž0=}pö\-_+?\\üä^`´õ§*'½]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2"@wsšx9)\]ol.:]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#£e€dc8(\[ik,;]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4¤$rfv5%€tgb6&yhn7{ujm/]/i)) {
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
    } else if (tCrka === '~' || tCrka === 'ˇ') {
        return "jkeytilde";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '£') {
        return "jkey3";
    } else if (tCrka === '¤' || tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '€') {
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
    } else if (tCrka === '+' || tCrka === '?' || tCrka === '\\') {
        return "jkeyplus";
    } else if (tCrka === '´' || tCrka === '`') {
        return "jkeyaccent";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'ü') {
        return "jkeyü";
    } else if (tCrka === 'õ' || tCrka === '§') {
        return "jkeyõ";
    } else if (tCrka === 'š') {
        return "jkeys";
    } else if (tCrka === 'ö') {
        return "jkeyö";
    } else if (tCrka === 'ä' || tCrka === '^') {
        return "jkeyä";
    } else if (tCrka === '\'' || tCrka === '*' || tCrka === '½') {
        return "jkeyapostrophe";
    } else if (tCrka === '<' || tCrka === '>' || tCrka === '|') {
        return "jkeyckck";
    } else if (tCrka === 'ž') {
        return "jkeyz";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
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
    return str.length === 1 && str.match(/[a-züõöäšž]/i);
}
