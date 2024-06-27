/**
 * @fileOverview Vietnamese(V4.4) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.4
 * @since 06/16/2018
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
    // phpcs:ignore
    if (ltr.match(/[!@#$%^&*(){}]/i)) {
        this.shift = true;
        this.alt = true;
        // phpcs:ignore
    } else if (ltr.match(/[`ăâêộ̀̉̃́đ-₫\\;',./]/)) {
        this.shift = false;
        this.alt = false;
        this.accent = false;
        // phpcs:ignore
    } else if (ltr.match(/[ƯƠĂÂÊÔĐQWERTYUIOP|ASDFGHJKL:~_+"ZXCVBNM<>?]/)) {
        this.shift = true;
        this.alt = false;
        this.accent = false;
        // phpcs:ignore
    } else if (ltr.match(/[1234567890=[\]]/i)) {
        this.shift = false;
        this.alt = true;
        // phpcs:ignore
    } else if (ltr.match(/[!@#$%^&*()]/i)) {
        this.shift = true;
        this.alt = true;
        this.tilde = false;
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
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
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
    } else if (tCrka.match(/[`~ă1!'"qa;:đ0)zp\[{/?\-_\]}₫ươ=+\\|]/i)) {
        return 4; // Highlight the correct key above in red.
        // phpcs:ignore
    } else if (tCrka.match(/[â2@slwx.>oq̣9(]/i)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/[ê3#,<ediḱ8*c]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // phpcs:ignore
    } else if (tCrka.match(/[ô4$vrjnuk5̀%ỷ6^fb̃7&tghm]/i)) {
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
    } else if (tCrka === '!' || tCrka === 'ă') {
        return "jkey1";
    } else if (tCrka === '@' || tCrka === 'â') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === 'ê') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === 'ô') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '̀') {
        return "jkey5";
    } else if (tCrka === '^' || tCrka === '̉') {
        return "jkey6";
    } else if (tCrka === '&' || tCrka === '̃') {
        return "jkey7";
    } else if (tCrka === '*' || tCrka === '́') {
        return "jkey8";
    } else if (tCrka === '(' || tCrka === '̣') {
        return "jkey9";
    } else if (tCrka === ')' || tCrka === 'đ') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '[' || tCrka === '{' || tCrka === 'ư') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}' || tCrka === 'ơ') {
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
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '₫') {
        return "jkeyequals";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else if (tCrka === '~' || tCrka === '`') {
        return "jkeybackquote";
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
