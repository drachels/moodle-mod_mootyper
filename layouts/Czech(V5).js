/**
 * @fileOverview Czech(V5.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 06/24/2018
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
    if ((ltr !== 'Ł') && (ltr !== 'Đ')) {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        // phpcs:ignore
        if (ltr.match(/[°12345QWERTASDFGYXCVB]/)) {
            this.shiftright = true;
        // phpcs:ignore
        } else if (ltr.match(/[67890%ˇZUIOP/('HJKL"!NM?:_]/)) {
            this.shiftleft = true;
        }
    }
    if (ltr.match(/[~^˘˛`˙˝¨¸\\|€÷×đĐ\[\]łŁ$ß#&@{}<>*]/)) {
        this.alt = true;
    }
    this.turnOn = function() {
        if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
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
            if (this.chr.match(/[asdfjklů]/i)) {
            document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
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
    } else if (tCrka.match(/[\n;°1+~q\\ay0é˝pů"$\-_*=%¨ú/÷§!ß´ˇ)(×¨']/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2ěˇw|sđx#9í´olŁ.:>]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3š^e€dĐ[c&8á˙ikł,?<]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4č˘rf\[v@5ř°tg\]b{6ž˛zhn}7ý`ujm]/i)) {
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
    } else if (tCrka === '°' || tCrka === ';') {
        return "jkeyscolon";
    } else if (tCrka === '1' || tCrka === '+' || tCrka === '~') {
        return "jkey1";
    } else if (tCrka === '2' || tCrka === 'ě' || tCrka === 'ˇ') {
        return "jkey2";
    } else if (tCrka === '3' || tCrka === 'š' || tCrka === '^') {
        return "jkey3";
    } else if (tCrka === '4' || tCrka === 'č' || tCrka === '˘') {
        return "jkey4";
    } else if (tCrka === '5' || tCrka === 'ř' || tCrka === '°') {
        return "jkey5";
    } else if (tCrka === '6' || tCrka === 'ž' || tCrka === '˛') {
        return "jkey6";
    } else if (tCrka === '7' || tCrka === 'ý' || tCrka === '`') {
        return "jkey7";
    } else if (tCrka === '8' || tCrka === 'á' || tCrka === '˙') {
        return "jkey8";
    } else if (tCrka === '9' || tCrka === 'í') {
        return "jkey9";
    } else if (tCrka === '0' || tCrka === 'é' || tCrka === '˝') {
        return "jkey0";
    } else if (tCrka === '%' || tCrka === '=' || tCrka === '¨') {
        return "jkeyequal";
    } else if (tCrka === 'ˇ' || tCrka === '´') {
        return "jkeyaccent";
    } else if (tCrka === '\\') {
        return "jkeyq";
    } else if (tCrka === '|') {
        return "jkeyw";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === '/' || tCrka === 'ú' || tCrka === '÷') {
        return "jkeyú";
    } else if (tCrka === '(' || tCrka === ')' || tCrka === '×') {
        return "jkey)";
    } else if (tCrka === "'" || tCrka === '¨') {
        return "jkey¨";
    } else if (tCrka.match(/đ/)) {
        return "jkeys";
    } else if (tCrka.match(/Đ/)) {
        return "jkeyd";
    } else if (tCrka === '[') {
        return "jkeyf";
    } else if (tCrka === ']') {
        return "jkeyg";
    } else if (tCrka.match(/ł/)) {
        return "jkeyk";
    } else if (tCrka.match(/Ł/)) {
        return "jkeyl";
    } else if (tCrka === '"' || tCrka === 'ů' || tCrka === '$') {
        return "jkeyů";
    } else if (tCrka === '!' || tCrka === '§' || tCrka === 'ß') {
        return "jkey§";
    } else if (tCrka === '#') {
        return "jkeyx";
    } else if (tCrka === '&') {
        return "jkeyc";
    } else if (tCrka === '@') {
        return "jkeyv";
    } else if (tCrka === '{') {
        return "jkeyb";
    } else if (tCrka === '}') {
        return "jkeyn";
    } else if (tCrka === ',' || tCrka === '?' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '*') {
        return "jkeyminus";
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
