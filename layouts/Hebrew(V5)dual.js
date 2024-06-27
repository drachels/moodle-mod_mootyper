/**
 * @fileOverview Hebrew(V5.0)dual keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 04/03/2018
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
    this.chr = ltr.toUpperCase();
    this.alt = false;

    // phpcs:ignore
    if (ltr.match(/[QWERTASDFGZXCVB~!@#$%]/i)) {
        this.shiftright = true;
    } else if (ltr.match(/[YUIOPHJKLNM^&*()_+{}|:"<>?]/)) {
        this.shiftleft = true;
    }
    // Set flags for characters needing Alt Gr key.
    // phpcs:ignore
    if (ltr.match(/[€₪־װױײ]/i)) {
        this.alt = true;
    }

    this.turnOn = function() {
        if (isLetter(this.chr)) {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toUpperCase());
        } else if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toUpperCase());
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
            if (this.chr.match(/[ASDFJKLשדגכחלךף]/i)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toUpperCase());
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
    } else if (tCrka.match(/[;~1!QAZשזz0)פp;ף:/?\-_־[{"=+\]}\\|.]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[םW2@'wsץס9ךxד(ol>]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[ן€3E#קגבלתedc8*ik,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$ראט₪וrRTYfvכעיחהנ5נמצ%tgb6^yhn7&ujmװױײ]/i)) {
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
    } else if (tCrka === ';' || tCrka === '~') {
        return "jkeysemicolon";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '€') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '₪') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '^') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '(') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '־') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === 'Q' || tCrka === '/') {
        return "jkeyQ";
    } else if (tCrka === "'" || tCrka === 'W') {
        return "jkeyW";
    } else if (tCrka === 'ק' || tCrka === 'E') {
        return "jkeyE";
    } else if (tCrka === 'ר' || tCrka === 'R') {
        return "jkeyR";
    } else if (tCrka === 'א' || tCrka === 'T') {
        return "jkeyT";
    } else if (tCrka === 'ט' || tCrka === 'Y' || tCrka === 'װ') {
        return "jkeyY";
    } else if (tCrka === 'ו' || tCrka === 'U') {
        return "jkeyU";
    } else if (tCrka === 'ן' || tCrka === 'I') {
        return "jkeyI";
    } else if (tCrka === 'ם' || tCrka === 'O') {
        return "jkeyO";
    } else if (tCrka === 'פ' || tCrka === 'P') {
        return "jkeyP";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeybracketr";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeybracketl";
    } else if (tCrka === 'ש' || tCrka === 'A') {
        return "jkeyA";
    } else if (tCrka === 'ד' || tCrka === 'S') {
        return "jkeyS";
    } else if (tCrka === 'ג' || tCrka === 'D') {
        return "jkeyD";
    } else if (tCrka === 'כ' || tCrka === 'F') {
        return "jkeyF";
    } else if (tCrka === 'ע' || tCrka === 'G' || tCrka === 'ױ') {
        return "jkeyG";
    } else if (tCrka === 'י' || tCrka === 'H' || tCrka === 'ײ') {
        return "jkeyH";
    } else if (tCrka === 'ח' || tCrka === 'J') {
        return "jkeyJ";
    } else if (tCrka === 'ל' || tCrka === 'K') {
        return "jkeyK";
    } else if (tCrka === 'ך' || tCrka === 'L') {
        return "jkeyL";
    } else if (tCrka === 'ף' || tCrka === ':') {
        return "jkeycolon";
    } else if (tCrka === 'ז' || tCrka === 'Z') {
        return "jkeyZ";
    } else if (tCrka === 'ס' || tCrka === 'X') {
        return "jkeyX";
    } else if (tCrka === 'ב' || tCrka === 'C') {
        return "jkeyC";
    } else if (tCrka === 'ה' || tCrka === 'V') {
        return "jkeyV";
    } else if (tCrka === 'נ' || tCrka === 'B') {
        return "jkeyB";
    } else if (tCrka === 'מ' || tCrka === 'N') {
        return "jkeyN";
    } else if (tCrka === 'צ' || tCrka === 'M') {
        return "jkeyM";
    } else if (tCrka === 'ת' || tCrka === '>') {
        return "jkeyת";
    } else if (tCrka === 'ץ' || tCrka === '<') {
        return "jkeyץ";
    } else if (tCrka === '.' || tCrka === '?') {
        return "jkeyperiod";
    } else if (tCrka === ',' || tCrka === '"') {
        return "jkeycomma";
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
    return str.length === 1 && str.match(/[A-Z,א-ת]/i);
}
