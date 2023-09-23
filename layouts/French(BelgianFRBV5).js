/**
 * @fileOverview French(BelgianFRBV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20230813
 */


/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
    return (chr === '´' || chr === '`' || chr === '~');
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
    this.accent = false;

    if (isLetter(ltr)) { // Set specified shift key for right or left.
        // @codingStandardsIgnoreLine
        if (ltr.match(/[AZERTQSDFGWXCVB³12345>]/)) {
            this.shiftright = true;
        // @codingStandardsIgnoreLine
        } else if (ltr.match(/[YUIOPHJKLMN67890°_¨*M%£?./+]/)) {
            this.shiftleft = true;
        }
    }

    // @codingStandardsIgnoreLine
    if (ltr.match(/[\\|@#€{}[\]~´`ñ]/i)) {
        this.alt = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ëïöü]/i)) {
        this.shiftleft = true;
        this.caret = true;
    }
    if (ltr === 'ê') {
        this.caret = true;
    }
    if (ltr === 'ó' || ltr === 'á') {
        this.alt = true;
        this.accent = true;
    }
    if (ltr === 'ñ') {
        this.alt = true;
        this.tilde = true;
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
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[azeruiop]/i)) {
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
            document.getElementById('jkeyù').className = "normal";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "normal";
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
    //} else if (tCrka.match(/[²³&1|aáqw<>\\à0}pm)°^¨[ù%´=+~\-_$*\]µ£`]/i)) {
    } else if (tCrka.match(/[²³&1|aáqw<>\\à0}pm)°^¨[ù%´=+~\-_$*\]µ£`]/i)) {
        return 4; // Highlight the correct key above in red.
        // @codingStandardsIgnoreLine
    //} else if (tCrka.match(/[é2@zsxç9{oóöl:/]/i)) {
    } else if (tCrka.match(/[é2@zsxç9{oóöl:/]/i)) {
        return 1; // Highlight the correct key above in green.
        // @codingStandardsIgnoreLine
    //} else if (tCrka.match(/["3#eéë€êdc!8iíïk;.]/i)) {
    } else if (tCrka.match(/["3#eéë€êdc!8iíïk;.]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // @codingStandardsIgnoreLine
    //} else if (tCrka.match(/[\'4rf(5tgbv§6yhnñè7uúüj,?]/i)) {
    } else if (tCrka.match(/[\'4rf(5tgbv§6yhnñè7uúüj,?]/i)) {
        return 3; // Highlight the correct key above in blue.
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
    } else if (tCrka === ',' || tCrka === '?') {
        return "jkeycomma";
    } else if (tCrka === ';' || tCrka === '.') {
        return "jkeysemicolon";
    } else if (tCrka === ':' || tCrka === '/') {
        return "jkeycolon";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '~') {
        return "jkeyequal";
    } else if (tCrka === '²' || tCrka === '³') {
        return "jkey²";
    } else if (tCrka === '&' || tCrka === '|') {
        return "jkey1";
    } else if (tCrka === 'é' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '"' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '\'') {
        return "jkey4";
    } else if (tCrka === '(') {
        return "jkey5";
    } else if (tCrka === '§') {
        return "jkey6";
    } else if (tCrka === 'è') {
        return "jkey7";
    } else if (tCrka === '!') {
        return "jkey8";
    } else if (tCrka === 'ç' || tCrka === '{') {
        return "jkey9";
    } else if (tCrka === 'à' || tCrka === '}') {
        return "jkey0";
    } else if (tCrka === '^' || tCrka === '¨' || tCrka === '[') {
        return "jkeycaret";
    } else if (tCrka === '$' || tCrka === '*' || tCrka === ']') {
        return "jkeydollar";
    } else if (tCrka === '%' || tCrka === '´') {
        return "jkeyù";
    } else if (tCrka === '£' || tCrka === '`') {
        return "jkeyµ";
    } else if (tCrka === ')' || tCrka === '°') {
        return "jkeyparenr";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === 'a' || tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === '¨') {
        return "jkeycaret";
    } else if (tCrka === '€' || tCrka === 'é' || tCrka === 'ë' || tCrka === 'ê') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í' || tCrka === 'ï') {
        return "jkeyi";
    } else if (tCrka === 'ñ') {
        return "jkeyn";
    } else if (tCrka === 'o' || tCrka === 'ó' || tCrka === 'ö') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú' || tCrka === 'ü') {
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
    return str.length === 1 && str.match(/[!-ﻼ]/);
}
