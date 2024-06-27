/**
 * @fileOverview French(V5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20210315
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
    this.chr = ltr.toLowerCase();
    // Reset all flags.
    this.alt = false;
    this.accent = false;
    this.caret = false;
    this.shift = false;
    this.tilde = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
        if (ltr.match(/[AZERTQSDFGWXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUIOP¨£µHJKLM%N?./§]/)) {
            this.shiftleft = true;
        }
    } else {
        // Set flags for characters needing shift keys.
        // phpcs:ignore
        if (ltr.match(/[12345>]/i)) {
            this.alt = false;
            this.accent = false;
            this.caret = false;
            this.shiftright = true;
        } else if (ltr.match(/[67890°+¨£%µ?./§]/)) {
            this.alt = false;
            this.accent = false;
            this.caret = false;
            this.shiftleft = true;
        }
    }
    // Set flags for characters needing Alt Gr key.
    // phpcs:ignore
    if (ltr.match(/[~#{\[|`\\@\]}¤€]/i)) {
        this.shiftright = false;
        this.shiftleft = false;
        this.alt = true;
        this.accent = false;
    }
    // Set flags for characters needing shift and ¨ keys.
    // Ignore case as flags are same lower or upper case.
    // phpcs:ignore
    if (ltr.match(/[äëïöü]/i)) {
        this.shiftleft = true;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    // Set flags for lower case characters needing ^ key.
    // phpcs:ignore
    if (ltr.match(/[âêîôû]/)) {
        this.shiftleft = false;
        this.alt = false;
        this.accent = false;
        this.caret = true;
        // Set flags for upper characters needing ^ key.
        // phpcs:ignore
    } else if (ltr.match(/[ÂÊÎÔÛ]/)) {
        this.shiftleft = true;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    // Set flags for lower characters needing ~ key.
    // phpcs:ignore
    if (ltr.match(/[ãñõ]/)) {
        this.shiftright = false;
        this.alt = true;
        this.accent = false;
        this.tilde = true;
        // Set flags for upper characters needing ~ key.
        // phpcs:ignore
    } else if (ltr.match(/[ÃÑÕ]/)) {
        this.shiftright = true;
        this.shiftleft = true;
        this.alt = true;
        this.accent = false;
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
            document.getElementById('jkey2').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // phpcs:ignore
            if (this.chr.match(/[qsdfjklm]/i)) {
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
            document.getElementById('jkey2').className = "normal";
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
    } else if (tCrka.match(/[²&1aáqw<>àâäã0@pm§!)°\]^¨ù%=+}$£¤*µ]/i)) {
        return 4; // Highlight the correct key above in red.
        // phpcs:ignore
    } else if (tCrka.match(/[é2~zsxç9^oóöôõl:/]/i)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/["3#eéë€êdc_8\\iíîïk;.]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // phpcs:ignore
    } else if (tCrka.match(/[\'4{rfv(5\[tgbv\-6|yhnñè7`uúüûj,?]/i)) {
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
    } else if (tCrka === '²') {
        return "jkey²";
    } else if (tCrka === '&') {
        return "jkey1";
    } else if (tCrka === 'é' || tCrka === '~') {
        return "jkey2";
    } else if (tCrka === '"' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '\'' || tCrka === '{') {
        return "jkey4";
    } else if (tCrka === '(' || tCrka === '[') {
        return "jkey5";
    } else if (tCrka === '-' || tCrka === '|') {
        return "jkey6";
    } else if (tCrka === 'è' || tCrka === '`') {
        return "jkey7";
    } else if (tCrka === '_' || tCrka === '\\') {
        return "jkey8";
    } else if (tCrka === 'ç') {
        return "jkey9";
    } else if (tCrka === 'à' || tCrka === '@') {
        return "jkey0";
    } else if (tCrka === ')' || tCrka === '°' || tCrka === ']') {
        return "jkeyparenr";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '}') {
        return "jkeyequal";
    } else if (tCrka === '^' || tCrka === '¨') {
        return "jkeycaret";
    } else if (tCrka === '$' || tCrka === '£' || tCrka === '¤') {
        return "jkeydollar";
    } else if (tCrka === '%' || tCrka === '´') {
        return "jkeyù";
    } else if (tCrka === 'µ' || tCrka === '*') {
        return "jkey*";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === ',' || tCrka === '?') {
        return "jkeycomma";
    } else if (tCrka === ';' || tCrka === '.') {
        return "jkeysemicolon";
    } else if (tCrka === ':' || tCrka === '/') {
        return "jkeycolon";
    } else if (tCrka === '=' || tCrka === '!' || tCrka === '§') {
        return "jkeyexclam";
    } else if (tCrka === 'á' || tCrka === 'â' || tCrka === 'ä' || tCrka === 'ã') {
        return "jkeya";
    } else if (tCrka === '¨' || tCrka === '^') {
        return "jkeycaret";
    } else if (tCrka === '€' || tCrka === 'é' || tCrka === 'ë' || tCrka === 'ê') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í' || tCrka === 'î' || tCrka === 'ï') {
        return "jkeyi";
    } else if (tCrka === 'ñ') {
        return "jkeyn";
    } else if (tCrka === 'o' || tCrka === 'ó' || tCrka === 'ô' || tCrka === 'ö' || tCrka === 'õ') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú' || tCrka === 'û' || tCrka === 'ü') {
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
    return str.length === 1 && str.match(/[a-zç]/i);
}
