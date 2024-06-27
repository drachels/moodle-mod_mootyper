/**
 * @fileOverview Turkish(V4.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.1
 * @since 06/27/2018
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
 * Check for next character to be typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    if (ltr !== 'İ') {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }

    // phpcs:ignore
    if (ltr.match(/[é!'^+%&/()=?:;_]/i)) {
        this.shift = true;
        this.alt = false;
    // phpcs:ignore
    } else if (ltr.match(/["1234567890*.,\-]/)) {
        this.shift = false;
        this.alt = false;
    // phpcs:ignore
    } else if (ltr.match(/[<>£#$½{[\]}\\|¨~`æß´]/)) {
        this.shift = false;
        this.alt = true;
    } else if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        this.shift = false;
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
            if (this.chr.match(/[asdfjklş]/i)) {
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
    } else if (tCrka.match(/[\n"é<1!>q@aæz0=}pş´.:*?\\ğ¨iİ\-_|ü~,;`]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2'£wsßx9)\]olç]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3^#e€dc8(\[ıIikö]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4+$rfv5%½tgb6&yhn7{ujm/]/i)) {
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
    } else if (tCrka === '"' || tCrka === 'é' || tCrka === '<') {
        return "jkeyquote";
    } else if (tCrka === '1' || tCrka === '!' || tCrka === '>') {
        return "jkey1";
    } else if (tCrka === '2' || tCrka === '\'' || tCrka === '£') {
        return "jkey2";
    } else if (tCrka === '3' || tCrka === '^' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '4' || tCrka === '+' || tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '5' || tCrka === '%' || tCrka === '½') {
        return "jkey5";
    } else if (tCrka === '6' || tCrka === '&') {
        return "jkey6";
    } else if (tCrka === '7' || tCrka === '/' || tCrka === '{') {
        return "jkey7";
    } else if (tCrka === '8' || tCrka === '(' || tCrka === '[') {
        return "jkey8";
    } else if (tCrka === '9' || tCrka === ')' || tCrka === ']') {
        return "jkey9";
    } else if (tCrka === '0' || tCrka === '=' || tCrka === '}') {
        return "jkey0";
    } else if (tCrka === '*' || tCrka === '?' || tCrka === '\\') {
        return "jkeyasterick";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '|') {
        return "jkeyminus";
    } else if (tCrka === '@') {
        return "jkeyq";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'ı' || tCrka === 'I') {
        return "jkeyı";
    } else if (tCrka === '¨') {
        return "jkeyğ";
    } else if (tCrka === '~') {
        return "jkeyü";
    } else if (tCrka === ',' || tCrka === ';' || tCrka === '`') {
        return "jkeycomma";
    } else if (tCrka === 'æ') {
        return "jkeya";
    } else if (tCrka === 'ß') {
        return "jkeys";
    } else if (tCrka === 'ş' || tCrka === '´') {
        return "jkeyş";
    } else if (tCrka === 'i' || tCrka === 'İ') {
        return "jkeyeye";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
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
