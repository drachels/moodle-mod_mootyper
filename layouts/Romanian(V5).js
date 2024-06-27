/**
 * @fileOverview Romanian(V5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 02/15/2019
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
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[”!QAZ@WSX#EDĐC$RFV%TGB]/)) {
            this.shiftright = true;
 //       } else if (ltr.match(/[^ZHN&UJM*IK;«(OL:»)PȘ:?]/)) {
        } else if (ltr.match(/[ZHN^YHN&UJM*IK;«(OL:»)PȘĂ{Î}ÂȚ":?_–+±|]/)) {
            this.shiftleft = true;
        }
    }
    if (ltr.match(/[`~~ˇ€şđĐ©˘°˛`·<«´łŁ>»˝§¨–\[{¸ţ'"¸±\]}]/)) {
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
            if (this.chr.match(/[asdfjklș]/i)) {
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
    } else if (tCrka.match(/[\n„”`~1!~qa\\z0)˝pșş/?ă\[{}\]țţî\-_¨–=+¸±â'"|]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@ˇwsßx9(´olł>».:]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#e€dđc©8*·ik,;<«]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$˘rfv5%°tgb6^˛yhn7&`ujm]/i)) {
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
    } else if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
        return "jkeyenter";
    } else if (tCrka === '„' || tCrka === '”' || tCrka === '~' || tCrka === '`') {
        return "jkeytilde";
    } else if (tCrka === '!' || tCrka === '~') {
        return "jkey1";
    } else if (tCrka === '@' || tCrka === 'ˇ') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$' || tCrka === '˘') {
        return "jkey4";
    } else if (tCrka === '%' || tCrka === '°') {
        return "jkey5";
    } else if (tCrka === '^' || tCrka === '˛') {
        return "jkey6";
    } else if (tCrka === '&' || tCrka === '`') {
        return "jkey7";
    } else if (tCrka === '*' || tCrka === '·') {
        return "jkey8";
    } else if (tCrka === '(' || tCrka === '´') {
        return "jkey9";
    } else if (tCrka === ')' || tCrka === '˝') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_' || tCrka === '–' || tCrka === '¨') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '±' || tCrka === '¸') {
        return "jkeyequal";
    } else if (tCrka === '€') {
        return "jkeye";
    } else if (tCrka === '§') {
        return "jkeyp";
    } else if (tCrka === '[' || tCrka === '{') {
        return "jkeyă";
    } else if (tCrka === ']' || tCrka === '}') {
        return "jkeyî";
    } else if (tCrka === 'â' || tCrka === '\\' || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === 'ß' || tCrka === 'ş') {
        return "jkeys";
    } else if (tCrka === 'đ' || tCrka === 'Đ') {
        return "jkeyd";
    } else if (tCrka === '©') {
        return "jkeyc";
    } else if (tCrka === 'ţ') {
        return "jkeyt";
    } else if (tCrka === 'ł' || tCrka ==='Ł') {
        return "jkeyl";
    } else if (tCrka === ',' || tCrka === ';' || tCrka === '<' || tCrka === '«') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':' || tCrka === '>' || tCrka === '»') {
        return "jkeyperiod";
    } else if (tCrka === '§') {
        return "jkeyp";
    } else if (tCrka === 'ș' ||tCrka === ';' || tCrka === ':') {
        return "jkeyș";
    } else if (tCrka === '/' || tCrka === '?') {
        return "jkey/";
    } else if (tCrka === '\'' || tCrka === '"') {
        return "jkeyț";
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
