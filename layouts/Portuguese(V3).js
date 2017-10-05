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

THE_LAYOUT = 'Portuguese(V3)(Brazil)';

/**
 * Check for character typed so flags can be set.
 *
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
    // @codingStandardsIgnoreLine
        if (ltr.match(/["!@#$%¨&*()_+`{^}|<>:?]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[¹²³£¢¬§//°ªº₢]/i))
    {
        this.shift = false;
        this.alt = true;
    }
    this.turnOn = function () {
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
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[asdfjklç]/i)) {
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
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "normal";
            document.getElementById('jkeyshiftl').className = "normal";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "normal";
        }
    }
}

/**
 * Set color flag based on current character.
 *
 */
function thenFinger(t_Crka) {
    if (t_Crka === ' ') {
        return 5; // Highlight the spacebar.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/['"1!¹q/a\\|z0)pç;:\-_´`~^/=+§\[{ª\]}º]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[2@²w?sx9(ol.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[3#³e°dc₢8*ik,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[4$£rfv5%¢tgb6¨¬yhn7&ujm]/i)) {
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
    } else if (t_Crka === '\n') {
        return "jkeyenter";
    } else if (t_Crka === "\'" || t_Crka === '"') {
        return "jkeycrtica";
    } else if (t_Crka === '!' || t_Crka === '¹') {
        return "jkey1";
    } else if (t_Crka === '@' || t_Crka === '²') {
        return "jkey2";
    } else if (t_Crka === '#' || t_Crka === '³') {
        return "jkey3";
    } else if (t_Crka === '$' || t_Crka === '£') {
        return "jkey4";
    } else if (t_Crka === '%' || t_Crka === '¢') {
        return "jkey5";
    } else if (t_Crka === '¨' || t_Crka === '¬') {
        return "jkey6";
    } else if (t_Crka === '&') {
        return "jkey7";
    } else if (t_Crka === '*') {
        return "jkey8";
    } else if (t_Crka === '(') {
        return "jkey9";
    } else if (t_Crka === ')') {
        return "jkey0";
    } else if (t_Crka === '-' || t_Crka === '_') {
        return "jkeyminus";
    } else if (t_Crka === '=' || t_Crka === '+' || t_Crka === '§') {
        return "jkeyequals";
    } else if (t_Crka === '/') {
        return "jkeyq";
    } else if (t_Crka === '°') {
        return "jkeye";
    } else if (t_Crka === '´' || t_Crka === '`') {
        return "jkeyacuteaccent";
    } else if (t_Crka === '[' || t_Crka === '{' || t_Crka === 'ª') {
        return "jkeybracketl";
    } else if (t_Crka === '~' || t_Crka === '^') {
        return "jkeytilde";
    } else if (t_Crka === ']' || t_Crka === '}' || t_Crka === 'º') {
        return "jkeybracketr";
    } else if (t_Crka === ';' || t_Crka === ':') {
        return "jkeysemicolon";
    } else if (t_Crka === "\\" || t_Crka === '|') {
        return "jkeybackslash";
    } else if (t_Crka === ',' || t_Crka === '<') {
        return "jkeycomma";
    } else if (t_Crka === '.' || t_Crka === '>') {
        return "jkeyperiod";
    } else if (t_Crka === 'w' || t_Crka === '?') {
        return "jkeyw";
    } else if (t_Crka === '?' || t_Crka === '/') {
        return "jkeyslash";
    } else if (t_Crka === '₢') {
        return "jkeyc";
    } else {
        return "jkey" + t_Crka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 *
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[�a-zç]/i);
}
