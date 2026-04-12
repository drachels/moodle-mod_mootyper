/**
 * @fileOverview Tamil(V5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20220730
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
 * Normalize Tamil multi-character tokens so one physical key highlights correctly.
 * @param {string} ltr The current expected character.
 * @param {number} [pos] Optional text index for token lookahead.
 * @returns {string}
 */
function tamilDisplayToken(ltr, pos) {
    var basePos = (typeof pos === 'number') ? pos : currentPos;

    if (!ltr || typeof fullText === 'undefined' || typeof basePos === 'undefined') {
        return ltr;
    }

    // Shift+T in Tamil99 outputs க்ஷ (ka + virama + ssa).
    if (ltr === 'க' && fullText[basePos + 1] === '்' && fullText[basePos + 2] === 'ஷ') {
        return 'க்ஷ';
    }

    // Shift+Y in Tamil99 outputs ஶ்ரீ (sha + virama + ra + ii sign).
    if (ltr === 'ஶ' && fullText[basePos + 1] === '்' && fullText[basePos + 2] === 'ர' &&
            fullText[basePos + 3] === 'ீ') {
        return 'ஶ்ரீ';
    }

    return ltr;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr, pos) {
    var keytoken = tamilDisplayToken(ltr, pos);
    this.chr = keytoken.toLowerCase();
    this.alt = false;
    if (keytoken === 'க்ஷ') {
        this.shiftright = true; // Shift+T - left-hand key, right shift.
    } else if (keytoken === 'ஶ்ரீ') {
        this.shiftleft = true; // Shift+Y - right-index key, left shift.
    } else if (isLetter(keytoken)) { // Set specified shift key for right or left.
        // phpcs:ignore
        if (keytoken.match(/[~!@#$%ஸஷஜஹ௹௺௸ஃ௳௴௵௶௷]/)) {
            this.shiftright = true;
        // phpcs:ignore
        } else if (keytoken.match(/[\^&*()_+\[\]{}\|:"';?<>ஶௐ\/]/)) {
            this.shiftleft = true;
        }
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
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // phpcs:ignore
            if (this.chr.match(/[asdfjkl;அ௹இ௺உ௸்ஃபம"த:ந]/)) {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').className = "normal";
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
    } else if (tCrka === 'க்ஷ' || tCrka === 'ஶ்ரீ') {
        return 1; // T/Y ligatures - index finger.
    // phpcs:ignore
    } else if (tCrka.match(/[`~1!0)ஆஸண\]ச{ஞ}\\|அ௹ஔ௳ந;ய'?ழ_\-=+]/)) {
        return 4; // Highlight the correct key above in red (pinky).
    // phpcs:ignore
    } else if (tCrka.match(/[2@9(ஈஷட\[இ௺ஓ௴த:\.>]/)) {
        return 3; // Highlight the correct key above in green (ring).
    // phpcs:ignore
    } else if (tCrka.match(/[3#8*னஉ௸ஒ௵ம",<]/)) {
        return 2; // Highlight the correct key above in yellow (middle).
    // phpcs:ignore
    } else if (tCrka.match(/[4$5%6\^7&ஐஹ்ஃவ௶ஏஎங௷ளறஶகபலௐர\/]/)) {
        return 1; // Highlight the correct key above in blue (index).
    } else {
        return 4; // Safe fallback to avoid assigning undefined class next6.
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
        return "jkeybackquote";
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
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+') {
        return "jkeyequals";
    } else if (tCrka === 'ஆ' || tCrka === 'ஸ') {
        return "jkeyq";
    } else if (tCrka === 'ஈ' || tCrka === 'ஷ') {
        return "jkeyw";
    } else if (tCrka === 'ஊ' || tCrka === 'ஜ') {
        return "jkeye";
    } else if (tCrka === 'ஐ' || tCrka === 'ஹ') {
        return "jkeyr";
    } else if (tCrka === 'ஏ' || tCrka === 'க்ஷ') {
        return "jkeyt";
    } else if (tCrka === 'ள' || tCrka === 'ஶ்ரீ') {
        return "jkeyy";
    } else if (tCrka === 'ற' || tCrka === 'ஶ') {
        return "jkeyu";
    } else if (tCrka === 'ன') {
        return "jkeyi";
    } else if (tCrka === 'ட' || tCrka === '[') {
        return "jkeyo";
    } else if (tCrka === 'ண' || tCrka === ']') {
        return "jkeyp";
    } else if (tCrka === 'ச' || tCrka === '{') {
        return "jkeybracketl";
    } else if (tCrka === 'ஞ' || tCrka === '}') {
        return "jkeybracketr";
    } else if (tCrka === 'அ' || tCrka === '௹') {
        return "jkeya";
    } else if (tCrka === 'இ' || tCrka === '௺') {
        return "jkeys";
    } else if (tCrka === 'உ' || tCrka === '௸') {
        return "jkeyd";
    } else if (tCrka === '்' || tCrka === 'ஃ') {
        return "jkeyf";
    } else if (tCrka === 'எ') {
        return "jkeyg";
    } else if (tCrka === 'க') {
        return "jkeyh";
    } else if (tCrka === 'ப') {
        return "jkeyj";
    } else if (tCrka === 'ம' || tCrka === '"') {
        return "jkeyk";
    } else if (tCrka === 'த' || tCrka === ':') {
        return "jkeyl";
    } else if (tCrka === 'ந' || tCrka === ';') {
        return "jkeysemicolon";
    } else if (tCrka === 'ய' || tCrka === "'") {
        return "jkeyapostrophe";
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
    } else if (tCrka === 'ழ' || tCrka === '?') {
        return "jkeyslash";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
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
    // return str.length === 1 && str.match(/[a-z]/i);
    return str.length === 1 && str.match(/[!-ﻼ]/i);
    // return str.length === 1 && str.match(/[`~1234567890அஇஉ்எ]/i);
}
