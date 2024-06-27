/**
 * @fileOverview Greek(V5) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 20200212
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

    // phpcs:ignore
    if (ltr.match(/[~!@#$%^&*()_+:{}¨"|<>?]/i)) {
        this.shift = true;
        this.alt = false;
        // phpcs:ignore
    } else if (ltr.match(/[;\\`1234567890\-=[\]΄',./]/)) {
        this.shift = false;
        this.alt = false;
        // phpcs:ignore
    } else if (ltr.match(/[²³£§¶¤¦°±½«»¬]/)) {
        this.shift = false;
        this.alt = true;
        // phpcs:ignore
    } else if (ltr.match(/[άέήίόύώ]/)) {
        this.shift = false;
        this.alt = false;
        this.accent = true;
       // phpcs:ignore
    } else if (ltr.match(/[ΆΈΉΊΌΎΏ]/)) {
        this.shift = true;
        this.alt = false;
        this.accent = true;
    } else if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        this.shift = false;
    }
    this.turnOn = function() {
        if (this.chr === ' ') {
            document.getElementById(getKeyID(this.chr)).className = "nextSpace";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "next" + thenFinger(this.chr.toLowerCase());
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
        if (this.accent) {
            document.getElementById('jkeyaccent').className = "next4";
        }
    };
    this.turnOff = function() {
        // phpcs:ignore
            if (this.chr.match(/[ασδφξκλ΄]/i)) {
            document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
        if (this.accent) {
            document.getElementById('jkeyaccent').className = "normal";
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
    } else if (tCrka.match(/[\n`~1!;:αάζ0)°π΄¨΅/\-_±?[{«'"=+½\]}»\\|¬]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@²ς΅σχ9(¦οόλ.>]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#³εέδψ8*¤ιίκ,<]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$£ρφωώ5%§τγβ6^¶υύηήν7&θξμ]/i)) {
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
    } else if (tCrka === '~' || tCrka === '`') {
        return "jkeytilde";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '@' || tCrka === '²') {
        return "jkey2";
    } else if (tCrka === '#' || tCrka === '³') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '£') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '§') {
        return "jkey5";
    } else if (tCrka === '^' || tCrka === '¶') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*' || tCrka === '¤') {
        return "jkey8";
    } else if (tCrka === '(' || tCrka === '¦') {
        return "jkey9";
    } else if (tCrka === ')' || tCrka === '°') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '±') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '½') {
        return "jkeyequal";
    } else if (tCrka === 'έ') {
        return "jkeyε";
    } else if (tCrka === 'ύ') {
        return "jkeyυ";
    } else if (tCrka === 'ό') {
        return "jkeyο";
    } else if (tCrka === 'ί') {
        return "jkeyι";
    } else if (tCrka === 'ά') {
        return "jkeyα";
    } else if (tCrka === 'ή') {
        return "jkeyη";
    } else if (tCrka === 'ώ') {
        return "jkeyω";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkey;";
    } else if (tCrka === 'ς' || tCrka === '΅') {
        return "jkeyς";
    } else if (tCrka === 'ε' || tCrka === 'Ε') {
        return "jkeyε";
    } else if (tCrka === '[' || tCrka === '{' || tCrka === '«') {
        return "jkey[";
    } else if (tCrka === ']' || tCrka === '}' || tCrka === '»') {
        return "jkey]";
    } else if (tCrka === '΄' || tCrka === '¨' || tCrka === '΅') {
        return "jkeyaccent";
    } else if (tCrka === '\'' || tCrka === '"') {
        return "jkeyapostrophe";
    } else if (tCrka === '\\' || tCrka === '|' || tCrka === '¬') {
        return "jkeybslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === '/' || tCrka === '?') {
        return "jkeyfslash";
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
