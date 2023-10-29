/**
 * @fileOverview German(SwissV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1 (20231028)
 * @since 20201104
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
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").on("keyup", "#tb1", keyupCombined);
    return false;
}

/**
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    if (ltr.match(/[ÄÖÜ]/)) {
        this.chr = ltr.toUpperCase();
    } else {
        this.chr = ltr.toLowerCase();
    }
    this.alt = false;
    this.accent = false;
    this.pow = false;
    this.umlaut = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[°+"*ç%QWEÊRTAÂSDFG>YXCVB]/)) {
            this.shiftright = true;
        // @codingStandardsIgnoreLine
        } else if (ltr.match(/[&/()=?`è!£éà£;:_ZUIOÔPÜ*HJKLÖNM;:_]/)) {
            this.shiftleft = true;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ã¦@#¬|¢´~€\[\]{}\\]/i)) {
        this.alt = true;
    }
    if (ltr.match(/[ëï]/)) {
        this.umlaut = true;
    }
    if (ltr.match(/[ÄË]/)) {
        this.shiftright = true;
        this.umlaut = true;
    } else if (ltr.match(/[ÏÖÜ]/)) {
        this.shiftleft = true;
        this.umlaut = true;
    }
    if (ltr.match(/[âêîôû]/)) {
        this.pow = true;
    }
    if (ltr.match(/[ÂÊ]/)) {
        this.shiftright = true;
        this.pow = true;
    } else if (ltr.match(/[ÎÔÛ]/)) {
        this.shiftleft = true;
        this.pow = true;
    }
    if (ltr === 'ó' || ltr === 'á') {
        this.alt = true;
        this.accent = true;
    }
    if (ltr === 'ñ'|| ltr === 'ã') {
        this.alt = true;
        this.tilde = true;
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
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.pow) {
            document.getElementById('jkeypow').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
        if (this.umlaut) {
            document.getElementById('jkeyumlaut').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklö]/i)) {
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
        if (this.accent) {
            document.getElementById('jkeyù').className = "normal";
        }
        if (this.pow) {
            document.getElementById('jkeypow').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "normal";
        }
        if (this.umlaut) {
            document.getElementById('jkeyumlaut').className = "normal";
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
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[\n§°1+¦qaâãy<>0=pöé.:'?´üè\[äà{\-_^`~¨!\]$£}\\]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"@wsx9)oôöl.:]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3*#eëê€dc8(¢iïîk,;]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4çrfv5%tgb6&¬zhn7|uûjm/]/i)) {
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
    } else if (tCrka === '§' || tCrka === '°') {
        return "jkeysection";
    } else if (tCrka === '+' || tCrka === '¦') {
        return "jkey1";
    } else if (tCrka === '"' || tCrka === '@') {
        return "jkey2";
    } else if (tCrka === '*' || tCrka === '#') {
        return "jkey3";
    } else if (tCrka === 'ç') {
        return "jkey4";
    } else if (tCrka === '%') {
        return "jkey5";
    } else if (tCrka === '&' || tCrka === '¬') {
        return "jkey6";
    } else if (tCrka === '/' || tCrka === '|') {
        return "jkey7";
    } else if (tCrka === '(' || tCrka === '¢') {
        return "jkey8";
    } else if (tCrka === ')') {
        return "jkey9";
    } else if (tCrka === '=') {
        return "jkey0";
    } else if (tCrka === '\'' || tCrka === '?' || tCrka === '´') {
        return "jkeyapostrophe";
    } else if (tCrka === '^' || tCrka === '~' || tCrka === '`') {
        return "jkeypow";
    } else if (tCrka.match(/[€ëËê]/)) {
        return "jkeye";
    } else if (tCrka.match(/[uûÜ]/)) {
        return "jkeyu";
    } else if (tCrka === 'i' || tCrka === 'î' || tCrka === 'í' || tCrka === 'ï') {
        return "jkeyi";
    } else if (tCrka.match(/[oôÖó]/)) {
        return "jkeyo";
    } else if (tCrka === 'ü' || tCrka === 'è' || tCrka === '[') {
        return "jkeyü";
    } else if (tCrka === '¨' || tCrka === '!' || tCrka === ']') {
        return "jkeyumlaut";
    } else if (tCrka === 'ö' || tCrka === 'é') {
        return "jkeyö";
    } else if (tCrka.match(/[Äâã]/)) {
        return "jkeya";
    } else if (tCrka.match(/[äà{]/)) {
        return "jkeyumlauta";
    } else if ( tCrka === '$' || tCrka === '£' || tCrka === '}') {
        return "jkeydollar";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '~') {
        return "jkeyequal";
    } else if (tCrka === '<' || tCrka === '>' || tCrka === '\\') {
        return "jkeyckck";
    } else if (tCrka === 'ñ') {
        return "jkeyn";
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
