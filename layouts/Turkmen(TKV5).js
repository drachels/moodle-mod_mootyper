/**
 * @fileOverview Turkmen(TKV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20210507
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
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[Ž!@#$%ÄWERTASDFGZÜÇÝB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[№&*()_+YUIOPŇÖŞHJKL:"NM<>?]/)) {
            this.shiftleft = true;
        }
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
    } else if (tCrka.match(/[ž1!äaz0)p;:/?\-_ň'"=+\öş]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@wsü9(ol.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#edç8*ik,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    //} else if (tCrka.match(/[4$€rfý5%tgb6№yhn7&uújm]/i)) {
    } else if (tCrka.match(/[4$rfý5%tgb6№yhn7&ujm]/i)) {
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
    } else if (tCrka === 'ž' || tCrka === 'Ž') {
        return "jkeyž";
    } else if (tCrka === '1' || tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '2' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '3' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '4' || tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '5' || tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '6' || tCrka === '№') {
        return "jkey6";
    } else if (tCrka === '7' || tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '8' || tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '9' || tCrka === '(') {
        return "jkey9";
    } else if (tCrka === '0' || tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyasterick";
    } else if (tCrka === 'ä' || tCrka === 'Ä') {
        return "jkeyä";
    } else if (tCrka === 'ň' || tCrka === 'Ň') {
        return "jkeyň";
    } else if (tCrka === 'ö' || tCrka === 'Ö') {
        return "jkeyö";
    } else if (tCrka === 'ş' || tCrka === 'Ş') {
        return "jkeyş";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === '\'' || tCrka === '"') {
        return "jkeycrtica";
    } else if (tCrka === 'ü' || tCrka === 'Ü') {
        return "jkeyü";
    } else if (tCrka === 'ç' || tCrka === 'Ç') {
        return "jkeyç";
    } else if (tCrka === 'ý' || tCrka === 'Ý') {
        return "jkeyý";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '/' || tCrka === '?') {
        return "jkeyslash";
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
