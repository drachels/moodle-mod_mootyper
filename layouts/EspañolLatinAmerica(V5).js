/**
 * @fileOverview Spanish Latin America(V5.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.1
 * @since 20240516
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
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        this.shift = ltr.toUpperCase() === ltr && ltr != '¡' && ltr != '`';
        if (ltr.match(/[QWEÉRTAÁSDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUÚÜIÍOÓPHJKLÑNMÇ]/)) {
            this.shiftleft = true;
        }
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[°!"#$%]/i)) {
            this.shiftright = true;
            this.shiftleft = false;
        // @codingStandardsIgnoreLine
        } else if (ltr.match(/[&/()=?¡*¨\[\];:_]/i)) {
            this.shiftright = false;
            this.shiftleft = true;
        } else {
            this.shiftright = false;
            this.shiftleft = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[áéíóú]/)) {
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ÁÉÍÓÚ]/)) {
        this.accent = true;
    }
    if (ltr === 'ü') {
        this.accent = true;
    }
    if (ltr === 'Ü') {
        this.accent = true;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[¬\\@~^`]/i)) {
        this.alt = true;
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
            document.getElementById('jkeyrighttick').className = "next4";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklñ]/i)) {
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
            document.getElementById('jkeyrighttick').className = "normal";
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
    } else if (tCrka.match(/[º|¬1!qaáz<>0=pñ\'?\\`^\[´¨{\-_¡¿+*\]}]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2"@wsx9)oól.:]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3·#eé€dc8(iík,;]/i)) {
        return 2; // Highlight the correct key above in yellow.    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$~rf5%€tgv6&¬yhnb7/uúüjm]/i)) {
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
    } else if (tCrka === '°' || tCrka === '¬') {
        return "jkey|";
    } else if (tCrka === '!') {
        return "jkey1";
    } else if ( tCrka === '"') {
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
    } else if (tCrka === "'" || tCrka === '?' || tCrka === '\') {
        return "jkeyapostrophe";
    } else if (tCrka === '¿' || tCrka === '¡') {
        return "jkey¿";
    } else if (tCrka === 'q' || tCrka === '@') {
        return "jkeyq";
    } else if (tCrka === '´' || tCrka === '¨') {
        return "jkeyrighttick";
    } else if (tCrka === '*' || tCrka === '+' || tCrka === '~') {
        return "jkeyplus";
    } else if (tCrka === 'ñ') {
        return 'jkeyñ';
    } else if (tCrka === '{' || tCrka === '[' || tCrka === '^') {
        return "jkeyleftbrace";
    } else if (tCrka === '}' || tCrka === ']' || tCrka === '`') {
        return "jkeyrightbrace";
    } else if (tCrka === '<' || tCrka === '>') {
        return "jkeyckck";
    } else if (tCrka === 'a' || tCrka === 'á') {
        return "jkeya";
    } else if (tCrka === 'e' || tCrka === 'é' || tCrka === '€') {
        return "jkeye";
    } else if (tCrka === 'i' || tCrka === 'í') {
        return "jkeyi";
    } else if (tCrka === 'o' || tCrka === 'ó') {
        return "jkeyo";
    } else if (tCrka === 'u' || tCrka === 'ú' || tCrka === 'ü') {
        return "jkeyu";
    } else if (tCrka === ',' || tCrka === ';') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.' || tCrka === ':') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
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

    // return str.length === 1 && str.match(/[a-z`¡ñçáéíóúü]/i);
    // When I try to use str.match(/[!-ﻼ]/i);, at least four lower case
    // keys start highlighting the Shift key, too. But they all
    // work fine using the old version.
}
