/**
 * @fileOverview EnglishInternational(INTLV6.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 6.0
 * @since 20240517
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
        if (ltr.match(/[QWERTASDFGZXCVB¬!"£$%|]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUIOPHJKLNM\^&*()_+{}:@~<>?]/)) {
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // @codingStandardsIgnoreLine
    if (ltr.match(/[¦€áéúíó]/)) {
        this.alt = true;
    } else if (ltr.match(/[ÉÁ]/)) {
        this.shiftright = true;
        this.alt = true;
    } else if (ltr.match(/[ÚÍÓ]/)) {
        this.shiftleft = true;
        this.alt = true;
    }
    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
            // If this.chr is in the keypad, highlight it. Asterisk is a special case.
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[0123456789./+-]/i)) {
                document.getElementById(getKeyID(this.chr) + 'p').className = "next" + thenPadFinger(this.chr.toLowerCase());
            }
            if (this.chr === '*') {
                document.getElementById('jkey*p').className = "next" + thenPadFinger(this.chr.toLowerCase());
            }
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "next4";
            document.getElementById('jkeyenterp').className = "next4";
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
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjkl;]/i)) {
                // Turns off highlight of normal home row keys.
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
                // Turns off highlight of keypad home row keys.
            } else if (this.chr.match(/[456+]/i)) {
                // This turns off normal 456+ highlights from top row.
                document.getElementById(getKeyID(this.chr)).className = "normal";
                // This turns off 456+ highlights in the keypad home row.
                document.getElementById(getKeyID(this.chr) + 'p').className = "finger" + thenPadFinger(this.chr.toLowerCase());
            } else {
                // Turns off keyboard highlight for all keys but home row and enter.
                document.getElementById(getKeyID(this.chr)).className = "normal";
                // Turns off keypad highlights, except its homerow.
                // @codingStandardsIgnoreLine
                if (this.chr.match(/[0123789./-]/i)) {
                    document.getElementById(getKeyID(this.chr) + 'p').className = "normal";
                }
                // Needed to turn off keypad * highlight.
                if (this.chr === '*') {
                    document.getElementById('jkey*p').className = "normal";
                }
            }
        } else {
            // I think this turns off the spacebar highlight.      
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        // Turns off highlight for Enter keys.   
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
            document.getElementById(getKeyID(this.chr) + 'p').className = "normal";
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
    } else if (tCrka.match(/[`¬¦1!qaáz0)p;:/?\-_[@{'=+\]}\\|~#]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"wsx9(oól.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3£eédc8*iík,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$€rfv5%tgb6^yhn7&uújm]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Set color flag based on current character.
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenPadFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[-+]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[*963.]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[//852]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[7410]/i)) {
        return 1; // Highlight the correct key above in blue.
    // @codingStandardsIgnoreLine
    } else {
        return 6; // Do not change any highlight.
    // @codingStandardsIgnoreLine
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
    } else if (tCrka === '`' || tCrka === '¬' || tCrka === '¦') {
        return "jkeybackquote";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '£') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '€') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '^') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeybracketr";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === "'" || tCrka === '@') {
        return "jkeycrtica";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else if (tCrka === 'é') {
        return "jkeye";
    } else if (tCrka === 'ú') {
        return "jkeyu";
    } else if (tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'ó') {
        return "jkeyo";
    } else if (tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === '#' || tCrka === '~') {
        return "jkey#";
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
