/**
 * @fileOverview Hungarian(HUV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20210318
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
    if (ltr !== 'Ł' && ltr !== 'Đ') {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }
    // phpcs:ignore
    if (ltr.match(/[§'"+!%]/i)) {
        this.shiftright = true;
        this.alt = false;
    // phpcs:ignore
    } else if (ltr.match(/[/=()ÖÜÓ]/)) {
        this.shiftleft = true;
        this.alt = false;
    // phpcs:ignore
    } else if (ltr.match(/[0123456789öüó,.\-]/)) {
        this.shift = false;
        this.alt = false;
    // phpcs:ignore
    } else if (ltr.match(/[~ˇ^˘°˛`˙´˝¨¸\\|€÷×đĐ\[\]łŁ$ß¤<>#&@{};*]/)) {
        this.alt = true;
    } else if (isLetter(ltr)) {
        if (ltr.match(/[QWERTASDFGÍYXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[ZUIOPŐÚHJKLÉÁŰNM?:_]/)) {
            this.shiftleft = true;
        }
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
            if (this.chr.match(/[asdfjklé]/i)) {
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
    } else if (tCrka.match(/[\n0§í<1'~q\\ay>ö˝pé$\-_*ü¨ő÷áßó¸ú×ű¤]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2"ˇw|sđx#9)´olŁ.:]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3+^edĐc&8(˙ikł,?;]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4!˘rf\[v@5%°tg\]b{6/˛zhn}7=`u€jm]/i)) {
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
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '§') {
        return "jkey0";
    } else if (tCrka === '\'' || tCrka === '~') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === 'ˇ') {
        return "jkey2";
    } else if (tCrka === '+' || tCrka === '^') {
        return "jkey3";
    } else if (tCrka === '!' || tCrka === '˘') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '°') {
        return "jkey5";
    } else if (tCrka === '/' || tCrka === '˛') {
        return "jkey6";
    } else if (tCrka === '=' || tCrka === '`') {
        return "jkey7";
    } else if (tCrka === '(' || tCrka === '˙') {
        return "jkey8";
    } else if (tCrka === ')' || tCrka === '´') {
        return "jkey9";
    } else if (tCrka === '˝') {
        return "jkeyö";
    } else if (tCrka === '¨') {
        return "jkeyü";
    } else if (tCrka === '¸') {
        return "jkeyó";
    } else if (tCrka === '\\') {
        return "jkeyq";
    } else if (tCrka === '|') {
        return "jkeyw";
    } else if (tCrka === '€') {
        return "jkeyu";
    } else if (tCrka === '÷') {
        return "jkeyő";
    } else if (tCrka === '×') {
        return "jkeyú";
    } else if (tCrka === 'đ') {
        return "jkeys";
    } else if (tCrka === 'Đ') {
        return "jkeyd";
    } else if (tCrka === '[') {
        return "jkeyf";
    } else if (tCrka === ']') {
        return "jkeyg";
    } else if (tCrka.match(/ł/)) {
        return "jkeyk";
    } else if (tCrka.match(/Ł/)) {
        return "jkeyl";
    } else if (tCrka === '$') {
        return "jkeyé";
    } else if (tCrka === 'ß') {
        return "jkeyá";
    } else if (tCrka === '¤') {
        return "jkeyű";
    } else if (tCrka === '<') {
        return "jkeyí";
    } else if (tCrka === '>') {
        return "jkeyy";
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
    } else if (tCrka === '?' || tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '*') {
        return "jkeyminus";
    } else if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
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
