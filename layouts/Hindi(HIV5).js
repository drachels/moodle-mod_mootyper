/**
 * @fileOverview Hindi(HIV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.1
 * @since 20220128
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
    //if (isLetter(ltr)) { // Verify this is a unicode letter.
        // phpcs:ignore
        //if (ltr.match(/[्ॅऍ र्ज्ञत्रक्षश्र()ऋः]/)) {
        //    this.shiftright = true; // Set specified shift key for right.
        //} else if (ltr.match(/[]/)) {
        //    this.shiftleft = true; // Set specified shift key for left.
        //}
    //}

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
        //if (this.shiftleft) {
        //    document.getElementById('jkeyshiftl').className = "next4";
        //}
        //if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // phpcs:ignore
            if (this.chr.match(/[ोे्िरकतच]/i)) {
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
        //if (this.shiftleft) {
        //    document.getElementById('jkeyshiftl').className = "normal";
        //}
        //if (this.shiftright) {
        //    document.getElementById('jkeyshiftr').className = "normal";
        //}
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
    //} else if (tCrka.match(/[ृ़1ऍ0)जझचछयय़-ःडढटठृऋञॉऑ]/i)) {
    } else if (tCrka.match(/[-़ौऍःृ1)0ऋडोॉयय़]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    //} else if (tCrka.match(/[ॅेंँ2ैऐए(9दधतथ.।]/i)) {
    } else if (tCrka.match(/[ंैॅ2े(9दधढथ।.ँ]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    //} else if (tCrka.match(/[्ा्3आअमण8श्रगघकख,ष]/i)) {
    } else if (tCrka.match(/[्ा्3आअमण8श्रगघकख,ष]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    //} else if (tCrka.match(/[िूु4र्ीईइन5ज्ञऊउव6त्रबभपफलळ7क्षहङरऱसश]/i)) {
    } else if (tCrka.match(/[िूु4र्ीईइन5ज्ञऊउव6त्रबभपफलळ7क्षहङरऱसश]/i)) {
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
    } else if (tCrka === '' || tCrka === '') {
        return "jkeybackquote";
    } else if (tCrka === '1' || tCrka === 'ऍ') {
        return "jkey1";
    } else if (tCrka === '2' || tCrka === 'ॅ') {
        return "jkey2";
    } else if (tCrka === '3' || tCrka === '्') {
        return "jkey3";
    } else if (tCrka === '4' || tCrka === 'र्') {
        return "jkey4";
    } else if (tCrka === '5' || tCrka === 'ज्ञ') {
        return "jkey5";
    } else if (tCrka === '6' || tCrka === 'त्र') {
        return "jkey6";
    } else if (tCrka === '7' || tCrka === 'क्ष') {
        return "jkey7";
    } else if (tCrka === '8' || tCrka === 'श्र') {
        return "jkey8";
    } else if (tCrka === '9' || tCrka === '(') {
        return "jkey9";
    } else if (tCrka === '0' || tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === 'ः') {
        return "jkeyminus";
    } else if (tCrka === 'ृ' || tCrka === 'ऋ') {
        return "jkeyequals";
    } else if (tCrka === 'ौ' || tCrka === 'औ') {
        return "jkeyq";
    } else if (tCrka === 'ै' || tCrka === 'ऐ') {
        return "jkeyw";
    } else if (tCrka === 'ा' || tCrka === 'आ') {
        return "jkeye";
    } else if (tCrka === 'ी' || tCrka === 'ई') {
        return "jkeyr";
    } else if (tCrka === 'ू' || tCrka === 'ऊ') {
        return "jkeyt";
    } else if (tCrka === 'ब' || tCrka === 'भ') {
        return "jkeyy";
    } else if (tCrka === 'ह' || tCrka === 'ङ') {
        return "jkeyu";
    } else if (tCrka === 'ग' || tCrka === 'घ') {
        return "jkeyi";
    } else if (tCrka === 'द' || tCrka === 'ध') {
        return "jkeyo";
    } else if (tCrka === 'ज' || tCrka === 'झ') {
        return "jkeyp";
    } else if (tCrka === 'ड' || tCrka === 'ढ') {
        return "jkeybracketl";
    } else if (tCrka === '़' || tCrka === 'ञ') {
        return "jkeybracketr";
    } else if (tCrka === 'ो' || tCrka === 'ओ') {
        return "jkeya";
    } else if (tCrka === 'े' || tCrka === 'ए') {
        return "jkeys";
    } else if (tCrka === '्' || tCrka === 'अ') {
        return "jkeyd";
    } else if (tCrka === 'ि' || tCrka === 'इ') {
        return "jkeyf";
    } else if (tCrka === 'ु' || tCrka === 'उ') {
        return "jkeyg";
    } else if (tCrka === 'प' || tCrka === 'फ') {
        return "jkeyh";
    } else if (tCrka === 'र' || tCrka === 'ऱ') {
        return "jkeyj";
    } else if (tCrka === 'क' || tCrka === 'ख') {
        return "jkeyk";
    } else if (tCrka === 'त' || tCrka === 'थ') {
        return "jkeyl";
    } else if (tCrka === 'च' || tCrka === 'छ') {
        return "jkeysemicolon";
    } else if (tCrka === 'ट' || tCrka === 'ठ') {
        return "jkeyapostrophe";
    } else if (tCrka === 'ॉ' || tCrka === 'ऑ') {
        return "jkeybackslash";
    } else if (tCrka === '' || tCrka === '') {
        return "jkeyz";
    } else if (tCrka === 'ं' || tCrka === 'ँ') {
        return "jkeyx";
    } else if (tCrka === 'म' || tCrka === 'ण') {
        return "jkeyc";
    } else if (tCrka === 'न') {
        return "jkeyv";
    } else if (tCrka === 'व') {
        return "jkeyb";
    } else if (tCrka === 'ल' || tCrka === 'ळ') {
        return "jkeyn";
    } else if (tCrka === 'स' || tCrka === 'श') {
        return "jkeym";
    } else if (tCrka === ',' || tCrka === 'ष') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '।') {
        return "jkeyperiod";
    } else if (tCrka === 'य' || tCrka === 'य़') {
        return "jkeyslash";
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
