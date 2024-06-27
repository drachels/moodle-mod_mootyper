/**
 * @fileOverview Tamil(V5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20220727
 */

/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
    //window.window.alert("In the isCombined(chr) function.");

    return false;
}

/**
 * Process keyup for combined character.
 * @param {string} e The combined character.
 * @returns {bolean} The result.
 */
function keyupCombined(e) {
    //window.window.alert("In the keyupCombined(e) function.");

    return false;
}

/**
 * Process keyupFirst.
 * @param {string} event Type of event.
 * @returns {bolean} The event.
 */
function keyupFirst(event) {
    //window.window.alert("In the keyupFirst(event) function.");

    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    //window.alert("In the keyboardElement(ltr) function printing keyboardElement(ltr): "+keyboardElement(ltr));

    this.chr = ltr.toUpperCase();
    this.alt = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
    //window.alert("In the keyboardElement(ltr) function printing ltr: " + ltr);

        // phpcs:ignore
        if (ltr.match(/[~!@#$%ஸஷஜஹக்ஷ௹௺௸ஃஎ௳௴௵௶௷]/)) {
            this.shiftright = true;
        // phpcs:ignore
        } else if (ltr.match(/[^ஶ்ரீகௐ&ஶப/*"<([:>)];?_{'+}|]/)) {
            this.shiftleft = true;
        }
    }
    //window.alert("just finished the check for isLetter(ltr): " + isLetter(ltr));

    this.turnOn = function() {
    //window.alert("In the this.turnOn function printing this.chr: ".this.chr);

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
    };
    this.turnOff = function() {
    //window.alert("In the this.turnOff function printing this.chr: ".this.chr);

        if (isLetter(this.chr)) {
            // phpcs:ignore
                if (this.chr.match(/[அஇஉ்பமதந]/i)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
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
//window.alert("In the thenFinger(tCrka) function.");

    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // phpcs:ignore
    // } else if (tCrka.match(/[`~1!ஆஸஅ௹ஔ௳0)ண\]ந;ழ?-_ச{ய'=+ஞ}\\|]/i)) {
    } else if (tCrka.match(/[`~1!ஆஸஅ௹ஔ௳0)ண\]ந;ழ?-_ச{ய'=+ஞ}\\|]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2@ஈஷி௺ஓ௴9(ட\[த:.>]/)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3#ஊஜஉ௸ஒ௵8*னம",<]/)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4$ஐஹஃ்வ௶5%ஏக்ஷெஎங௷6^ளஶ்ரீக்கலௐ7&றஶப்பர/]/i)) {
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
//window.alert("In the getKeyID(tCrka) function printing tCrka: ".tCrka);

    if (tCrka === ' ') {
        return "jkeyspace";
    } else if (tCrka === '`' || tCrka === '~') {
        return "jkeytilde";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if (tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '#') {
        return "jkey3";
    } else if (tCrka === '$') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '^') {
        return "jkey6";
    } else if (tCrka === '&') {
        return "jkey7";
    } else if (tCrka === '*') {
        return "jkey8";
    } else if (tCrka === '(') {
        return "jkey9";
    } else if (tCrka === ')') {
        return "jkey0";
    } else if (tCrka === '_') {
        return "jkey-";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkey=";
    } else if (tCrka === 'ஆ' || tCrka === 'ஸ') {
        return "jkeyஆ";
    } else if (tCrka === 'ஷ') {
        return "jkeyw";
    } else if (tCrka === 'ஜ') {
        return "jkeye";
    } else if (tCrka === 'ஹ') {
        return "jkeyr";
    } else if (tCrka === 'க்ஷ') {
        return "jkeyt";
    } else if (tCrka === 'ஶ்ரீ') {
        return "jkeyy";
    } else if (tCrka === 'ஶ') {
        return "jkeyu";
    } else if (tCrka === 'ன') {
        return "jkeyi";
    } else if (tCrka === '[') {
        return "jkeyட";
    } else if (tCrka === ']') {
        return "jkeyண";


    } else if (tCrka === 'அ' || tCrka === '௹') {
        return "jkeya";
    } else if (tCrka === 'இ' || tCrka === '௺') {
        return "jkeys";
    } else if (tCrka === 'உ' || tCrka === '௸') {
        return "jkeyd";
    } else if (tCrka === '்' || tCrka === 'ஃ') {
        return "jkeyf";
    } else if (tCrka === 'எ' || tCrka === 'எ') {
        return "jkeyg";

    } else if (tCrka === 'க' || tCrka === 'க') {
        return "jkeyh";
    } else if (tCrka === 'ப' || tCrka === 'ப') {
        return "jkeyj";
    } else if (tCrka === 'ம' || tCrka === '"') {
        return "jkeyk";
    } else if (tCrka === 'த' || tCrka === ':') {
        return "jkeyl";
    } else if (tCrka === 'ந' || tCrka === ';') {
        return "jkeyந";
    } else if (tCrka === 'ய' || tCrka === '\'') {
        return "jkeyய";
    } else if (tCrka === '?' || tCrka === '\'') {
        return "jkeyapostrophe";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";


    } else if (tCrka === 'ஔ' || tCrka === '௳') {
        return "jkeyz";
    } else if (tCrka === 'ஓ' || tCrka === '௴') {
        return "jkeyx";
    } else if (tCrka === 'ஒ' || tCrka === '௵') {
        return "jkeyc";
    } else if (tCrka === 'வ' || tCrka === '௶') {
        return "jkeyv";
    } else if (tCrka === 'ங' || tCrka === '௷') {
        return "jkeyb";
    } else if (tCrka === 'ல' || tCrka === 'ௐ') {
        return "jkeyn";
    } else if (tCrka === 'ர' || tCrka === '/') {
        return "jkeym";

    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";

    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === 'ழ' || tCrka === '?') {
        return "ழ";
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
//window.alert("In the isLetter function printing str: "+str);

    //return str.length === 1 && str.match(/[!-ﻼ]/i);
    return str.length === 1 && str.match(/[`~1ஆஅஔ]/i);
}
