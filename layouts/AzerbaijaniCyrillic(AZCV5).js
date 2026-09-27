/**
 * @fileOverview Azerbaijani Cyrillic(AZCV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20260925
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
    // Azerbaijani I/ı and İ/i need locale-aware lowercasing.
    this.chr = ltr.toLocaleLowerCase('az');
    // Reset all flags.
    this.alt = false;
    this.accent = false;
    this.caret = false;
    this.shift = false;
    this.shiftright = false;
    this.shiftleft = false;
    this.tilde = false;
    // Set flags for characters needing shift keys.
    // phpcs:ignore
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
        if (this.shift) {
            // phpcs:ignore
            if (ltr.match(/[ЈҮУКЕФЫВАПӘЧСМИ]/)) {
                this.shiftright = true;
            } else {
                this.shiftleft = true;
            }
        }
    } else {
        // Set flags for symbol characters needing shift keys.
        // phpcs:ignore
        if (ltr.match(/[~!"№;%|]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[:?*()_+\/,]/)) {
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // phpcs:ignore
    if (ltr.match(/[@₼]/)) {
        this.shiftright = false;
        this.shiftleft = false;
        this.alt = true;
    }

    this.turnOn = function() {
        if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr);
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
        // phpcs:ignore
        if (isLetter(this.chr) && this.chr.match(/[фываолдж]/)) {
            document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr);
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
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
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
        // phpcs:ignore
    } else if (tCrka.match(/[`~1!јфә0)\-_=+зхҹжҝ\\\/|.,]/)) {
        return 4; // Highlight the correct key above in red.
        // phpcs:ignore
    } else if (tCrka.match(/[2"@үыч9(һдө]/)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/[3№увс8*шлб]/)) {
        return 2; // Highlight the correct key above in yellow.
        // phpcs:ignore
    } else if (tCrka.match(/[4;₼5%6:7?кепамирнгоғт]/)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6;
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
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '№') {
        return "jkey3";
    } else if (tCrka === ';' || tCrka === '₼') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === ':') {
        return "jkey6";
    } else if (tCrka === '?') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequal";
    } else if (tCrka === '\\' || tCrka === '/') {
        return "jkeybackslash";
    } else if (tCrka === '|') {
        return "jkeyckck";
    } else if (tCrka === '.' || tCrka === ',') {
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
//function isLetter(str) {
//    return str.length === 1 && str.match(/[а-яјүһҹҝәғө]/i);
//}

/**
 * Is the typed letter part of the current alphabet.
 * @param {string} str The current letter.
 * @returns {(number|Array)}.
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[а-яјүһҹҝәғө]/i);
}