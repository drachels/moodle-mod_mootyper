/**
 * @fileOverview Slovenian(V5.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 12/01/2017
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
    if (ltr !== 'Ł') {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        // phpcs:ignore
        if (ltr.match(/[¨!"#$%QWERTASDFG>YXCVB]/)) {
            this.shiftright = true;
        // phpcs:ignore
        } else if (ltr.match(/[&/()=?*ZUIOPŠĐŽHJKLČĆNM;:_]/)) {
            this.shiftleft = true;
        }
    }
    if (ltr.match(/[\\|€÷×\[\]łŁß¤@{}§]/)) {
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
            if (this.chr.match(/[asdfjklč]/i)) {
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
    } else if (tCrka.match(/[\n¸¨1!~q\\a><0=˝pč.:\'?š÷ćß\-_+*¸đ×ž¤]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2"ˇw|sy9)´olŁ,;]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#^e€dx8(˙ikłm§]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$˘rf\[c5%°tg\]v@6&˛zhb{7/`ujn}]/i)) {
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
    } else if (tCrka === '¸' || tCrka === '¨') {
        return "jkeytildo";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '"') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%') {
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
    } else if (tCrka === '\\') {
        return "jkeyq";
    } else if (tCrka === '|') {
        return "jkeyw";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === '÷') {
        return "jkeyš";
    } else if (tCrka === '×') {
        return "jkeyđ";
    } else if (tCrka === '[') {
        return "jkeyf";
    } else if (tCrka === ']') {
        return "jkeyg";
    } else if (tCrka.match(/ł/)) {
        return "jkeyk";
    } else if (tCrka.match(/Ł/)) {
        return "jkeyl";
    } else if (tCrka === 'ß') {
        return "jkeyć";
    } else if (tCrka === '¤') {
        return "jkeyž";
    } else if (tCrka === '@') {
        return "jkeyv";
    } else if (tCrka === '{') {
        return "jkeyb";
    } else if (tCrka === '}') {
        return "jkeyn";
    } else if (tCrka === '§') {
        return "jkeym";
    } else if (tCrka === '?' || tCrka === '\'') {
        return "jkeyapostrophe";
    } else if (tCrka === '*' || tCrka === '+') {
        return "jkeyplus";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
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
