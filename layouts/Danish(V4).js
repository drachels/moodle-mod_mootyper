/**
 * @fileOverview Danish(V4.4) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.4
 * @since 06/19/2018
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
    if (ltr.match(/[§!"#¤%&/¤()=?`^*>;:_]/i)) {
        this.shift = true;
        this.alt = false;
        // phpcs:ignore
    } else if (ltr.match(/[@£$€{[\]}\\~µ|]/i)) {
        this.shift = false;
        this.alt = true;
        // phpcs:ignore
    } else if (ltr.match(/[½1234567890+´¨'<,.-]/)) {
        this.shift = false;
        this.alt = false;
        this.accent = false;
    } else if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
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
    } else if (tCrka.match(/[<>|§½´`~1!qa0zpæÆøØåÅ¨^~?\-_}=+\\'*]/i)) {
        return 4; // Highlight the correct key above in red.
        // phpcs:ignore
    } else if (tCrka.match(/[2"@wsx9)\]ol.:]/i)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/[3#£edc8([ik,;]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // phpcs:ignore
    } else if (tCrka.match(/[4$vrjnuk¤5%€y6^fb7&{tghmµ/]/i)) {
        return 1; // Highlight the correct key above in blue.
        // phpcs:ignore
    } else {
        // phpcs:ignore
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
    } else if (tCrka === '§' || tCrka === '½') {
        return "jkey§";
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
    } else if (tCrka === "+" || tCrka === '?') {
        return "jkeyplus";
    } else if (tCrka === "´" || tCrka === '`' || tCrka === '|') {
        return "jkeycrtica";
    } else if (tCrka === 'Å' || tCrka === 'å') {
        return "jkeyå";
    } else if (tCrka === 'Æ' || tCrka === 'æ') {
        return "jkeyæ";
    } else if (tCrka === 'Ø' || tCrka === 'ø') {
        return "jkeyø";
    } else if (tCrka === 'M' || tCrka === 'm' || tCrka === 'µ') {
        return "jkeym";
    } else if (tCrka === '*' || tCrka === "'") {
        return "jkey'";
    } else if (tCrka === '¨' || tCrka === '^' || tCrka === '~') {
        return "jkey¨";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '<' || tCrka === '>' || tCrka === '\\') {
        return "jkeyckck";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '₫') {
        return "jkeyequals";
    } else if (tCrka === '\n') {
        return "jkeyenter";
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
