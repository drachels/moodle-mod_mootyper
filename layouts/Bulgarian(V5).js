/**
 * @fileOverview Bulgarian(V5.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 6/20/2018
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
    if (ltr.match(/[)!?+"%ыУЕИШѝЯАОЖЮЙЪЭФ]/i)) {
        this.shiftright = true;
    // phpcs:ignore
    } else if (ltr.match(/[=:/–№$€ЩКСДЗЦ§“ГТНВМЧХПРЛБ]/)) {
        this.shiftleft = true;
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
        if ( this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // phpcs:ignore
            if (this.chr.match(/[ьяаотнвм]/i)) {
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
		// phpcs:ignore
    } else if (tCrka.match(/[()1!,ыьѝюЮ0№зЗмМбБ\-$цЦчЧ.€;§„“]/i)) {
        return 4; // Highlight the correct key above in red.
    } else if (tCrka.match(/[2?уУяЯйЙ9–дДвВлЛ]/i)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/[3+еЕаАъЪ8/сСнНрР]/i)) {
        return 2; // Highlight the correct key above in yellow.
    } else if (tCrka.match(/[4"иИоОэЭ5%шШжЖфФ6=щЩгГхХ7:кКтТпП]/i)) {
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
    } else if (tCrka === ')' || tCrka === '(') {
        return "jkey(";
    } else if (tCrka === '!' || tCrka === '1') {
        return "jkey1";
    } else if (tCrka === '?') {
        return "jkey2";
    } else if (tCrka === '+') {
        return "jkey3";
    } else if (tCrka === '"') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '=') {
        return "jkey6";
    } else if (tCrka === ':') {
        return "jkey7";
    } else if (tCrka === '/') {
        return "jkey8";
    } else if (tCrka === '–') {
        return "jkey9";
    } else if (tCrka === '№') {
        return "jkey0";
    } else if (tCrka === '$' || tCrka === '-') {
        return "jkeyminus";
    } else if (tCrka === '€' || tCrka === '.') {
        return "jkeyperiod";
    } else if (tCrka === 'ы' || tCrka === ',') {
        return "jkeycomma";
    } else if (tCrka === '§' || tCrka === ';') {
        return "jkey;";
    } else if (tCrka === '“' || tCrka === '„') {
        return "jkey„";
    } else if (tCrka === 'ѝ' || tCrka === 'ь') {
        return "jkeyь";
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
