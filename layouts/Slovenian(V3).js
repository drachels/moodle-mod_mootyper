/**
 * Check for combined character.
 *
 */
function isCombined(chr) {
    return false;
}

/**
 * Process keyup for combined character.
 *
 */
function keyupCombined(e) {
    return false;
}

/**
 * Process keyupFirst.
 *
 */
function keyupFirst(event) {
    return false;
}
THE_LAYOUT = 'Slovenian(V3)';

/**
 * Check for character typed so flags can be set.
 *
 */
function keyboardElement(ltr) {
    if (ltr !== 'Ł') {
        this.chr = ltr.toLowerCase();
    } else {
        this.chr = ltr.toUpperCase();
    }
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else if (ltr.match(/[ĐĆČŠŽ]/)) {
        this.shift = true;
    } else if (ltr.match(/[\\|€÷×\[\]łŁß¤@{}§]/)) {
        this.shift = false;
        this.alt = true;
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[¨!"#$%&/()=?*>;:_]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    this.turnOn = function () {
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
    };
    this.turnOff = function () {
        // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklč]/i)) {
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
    };
}

/**
 * Set color flag based on current character.
 *
 */
function thenFinger(t_Crka) {
    if (t_Crka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[\n¸¨1!~q\\a><0=˝pč.:\'?š÷ćß\-_+*¸đ×ž¤]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[2"ˇw|sy9)´olŁ,;]/)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[3#^e€dx8(˙ikłm§]/)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[4$˘rf\[c5%°tg\]v@6&˛zhb{7/`ujn}]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

/**
 * Get ID of key to highlight based on current character.
 *
 */
function getKeyID(t_Crka) {
    if (t_Crka === ' ') {
        return "jkeyspace";
    } else if (t_Crka === ',' || t_Crka === ';') {
        return "jkeycomma";
    } else if (t_Crka === '\n') {
        return "jkeyenter";
    } else if (t_Crka === '¸' || t_Crka === '¨') {
        return "jkeytildo";
    } else if (t_Crka === '.' || t_Crka === ':') {
        return "jkeyperiod";
    } else if (t_Crka === '-' || t_Crka === '_') {
        return "jkeyminus";
    } else if (t_Crka === '!') {
        return "jkey1";
    } else if (t_Crka === '"') {
        return "jkey2";
    } else if (t_Crka === '#') {
        return "jkey3";
    } else if (t_Crka === '$') {
        return "jkey4";
    } else if (t_Crka === '%') {
        return "jkey5";
    } else if (t_Crka === '&') {
        return "jkey6";
    } else if (t_Crka === '/') {
        return "jkey7";
    } else if (t_Crka === '(') {
        return "jkey8";
    } else if (t_Crka === ')') {
        return "jkey9";
    } else if (t_Crka === '=') {
        return "jkey0";
    } else if (t_Crka === '\\') {
        return "jkeyq";
    } else if (t_Crka === '|') {
        return "jkeyw";
    } else if (t_Crka === '€') {
        return "jkeye";
    } else if (t_Crka === '÷') {
        return "jkeyš";
    } else if (t_Crka === '×') {
        return "jkeyđ";
    } else if (t_Crka === '[') {
        return "jkeyf";
    } else if (t_Crka === ']') {
        return "jkeyg";
    } else if (t_Crka.match(/ł/)) {
        return "jkeyk";
    } else if (t_Crka.match(/Ł/)) {
        return "jkeyl";
    } else if (t_Crka === 'ß') {
        return "jkeyć";
    } else if (t_Crka === '¤') {
        return "jkeyž";
    } else if (t_Crka === '@') {
        return "jkeyv";
    } else if (t_Crka === '{') {
        return "jkeyb";
    } else if (t_Crka === '}') {
        return "jkeyn";
    } else if (t_Crka === '§') {
        return "jkeym";
    } else if (t_Crka === '?' || t_Crka === '\'') {
        return "jkeyapostrophe";
    } else if (t_Crka === '*' || t_Crka === '+') {
        return "jkeyplus";
    } else if (t_Crka === '<' || t_Crka === '>') {
        return "jkeyckck";
    } else if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
        return "jkeyenter";
    } else {
        return "jkey" + t_Crka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 *
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[a-zčšžđć]/i);
}
