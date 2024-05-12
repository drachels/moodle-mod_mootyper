/**
 * @fileOverview Italian(ITV5.2) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.2
 * @since 20240326
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
    if (isLetter(ltr)) {
        if (ltr.match(/[QWERTASDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUIOPHJKLNM]/)) {
            this.shiftleft = true;
        }
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[|!"£$%]/i)) {
            this.shiftright = true;
        } else if (ltr.match(/[&/()=?^é*ç°§;:_]/i)) {
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // @codingStandardsIgnoreLine
    if (ltr.match(/[€[\]@#]/)) {
        this.shift = false;
        this.alt = true;
    } else if (ltr.match(/[{}]/)) {
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
            document.getElementById('jkeyenter').className = "next3";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "next3";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next3";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklò]/i)) {
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
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[\\|<>1!qaz0=pòç@\-_'?èé[{à°#ì^+*\]}ù§]/i)) {
        //return 4; // Highlight the correct key above in red.
        return 3; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"wsx9)ol.:]/i)) {
        //return 3; // Highlight the correct key above in green.
        return 2; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3£edc8(ik,;]/i)) {
        //return 2; // Highlight the correct key above in yellow.
        return 4; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$rfv5%€tgb6&yhn7/ujm]/i)) {
        //return 1; // Highlight the correct key above in blue.
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
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '£') {
        return "jkey3";
    } else if (tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '€') {
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
    } else if (tCrka === "'" || tCrka === '?') {
        return "jkeyapostrophe";
    } else if (tCrka === '^' || tCrka === 'ì') {
        return "jkeyì";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'é' || tCrka === '[' || tCrka === '{') {
        return "jkeyè";
    } else if (tCrka === '+' || tCrka === '*' || tCrka === ']' || tCrka === '}') {
        return "jkeyplus";
    } else if (tCrka === 'ç' || tCrka === '@') {
        return "jkeyò";
    } else if (tCrka === '°' || tCrka === '#') {
        return "jkeyà";
    } else if (tCrka === 'ù' || tCrka === '§') {
        return "jkeyù";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeylessthan";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
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
    return str.length === 1 && str.match(/[a-z]/i);
}
