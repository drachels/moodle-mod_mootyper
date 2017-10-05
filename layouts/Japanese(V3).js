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

THE_LAYOUT = 'Japanese(V3)';

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
        if (ltr === '!' || ltr === '"' || ltr === '#' || ltr === '$' || ltr === '%' || ltr === '&' ||
            ltr === '\'' || ltr === '(' || ltr === ')' || ltr === '' || ltr === '=' || ltr === '~' || ltr === '|' ||
            ltr === '`' || ltr === '{' || ltr === '+' || ltr === '*' || ltr === '}' ||
            ltr === '<' || ltr === '>' || ltr === '?' || ltr === '_') {
            this.shift = true;
        } else {
            this.shift = false;
        }
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
            document.getElementById('jkeyshiftr').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            if (this.chr === 'a' || this.chr === 's' || this.chr === 'd' || this.chr === 'f' ||
               this.chr === 'j' || this.chr === 'k' || this.chr === 'l') {
                document.getElementById(getKeyID(this.chr)).className = "finger" + thenFinger(this.chr.toLowerCase());
            } else {
                document.getElementById(getKeyID(this.chr)).className = "normal";
            }
        } else if (this.chr === ':' || this.chr === ';') {    // English specific ; and :.
            document.getElementById(getKeyID(this.chr)).className = "finger4";
        } else {
            document.getElementById(getKeyID(this.chr)).className = "normal";
        }
        if (this.chr === '\n' || this.chr === '\r\n' || this.chr === '\n\r' || this.chr === '\r') {
            document.getElementById('jkeyenter').classname = "normal";
        }
        if (this.shift) {
            document.getElementById('jkeyshiftr').className = "normal";
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
    } else if (t_Crka === '1' || t_Crka === 'q' || t_Crka === 'a' || t_Crka === 'z' ||
            t_Crka === '!' ||
            t_Crka === '0' || t_Crka === 'p' || t_Crka === ';' || t_Crka === '/' ||
            t_Crka === '+' || t_Crka === '?' ||
            t_Crka === '-' || t_Crka === '@' || t_Crka === ':' || t_Crka === '\\' ||
            t_Crka === '=' || t_Crka === '`' || t_Crka === '*' || t_Crka === '_' ||
            t_Crka === '^' || t_Crka === '[' || t_Crka === ']' ||
            t_Crka === '~' || t_Crka === '{' || t_Crka === '}' ||
            t_Crka === '\\' || t_Crka === '|') {
        return 4; // Highlight the correct key above in red.
    } else if (t_Crka === '2' || t_Crka === 'w' || t_Crka === 's' || t_Crka === 'x' ||
            t_Crka === '"' ||
            t_Crka === '9' || t_Crka === 'o' || t_Crka === 'l' || t_Crka === '.' ||
            t_Crka === ')' || t_Crka === '>') {
        return 3; // Highlight the correct key above in green.
    } else if (t_Crka === '3' || t_Crka === 'e' || t_Crka === 'd' || t_Crka === 'c' ||
          t_Crka === '#' ||
          t_Crka === '8' || t_Crka === 'i' || t_Crka === 'k' || t_Crka === ',' ||
          t_Crka === '(' || t_Crka === '<' ) {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_Crka === '4' || t_Crka === 'r' || t_Crka === 'f' || t_Crka === 'v' ||
          t_Crka === '$' ||
          t_Crka === '5' || t_Crka === 't' || t_Crka === 'g' || t_Crka === 'b' ||
          t_Crka === '%' ||
          t_Crka === '6' || t_Crka === 'y' || t_Crka === 'h' || t_Crka === 'n' ||
          t_Crka === '&' ||
          t_Crka === '7' || t_Crka === 'u' || t_Crka === 'j' || t_Crka === 'm' ||
          t_Crka === '\'') {
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
    } else if (t_Crka === '\'') {
        return "jkey7";
    } else if (t_Crka === '(') {
        return "jkey8";
    } else if (t_Crka === ')') {
        return "jkey9";
    } else if (t_Crka === '-') {
        return "jkeyminus";
    } else if (t_Crka === '^' || t_Crka === '~') {
        return "jkeycaret";
    } else if (t_Crka === '|') {
        return "jkeyyen";
    } else if (t_Crka === '@' || t_Crka === '`') {
        return "jkeyat";
    } else if (t_Crka === '[' || t_Crka === '{') {
        return "jkeybracketopen";
    } else if (t_Crka === ';' || t_Crka === '+') {
        return "jkeysemicolon";
    } else if (t_Crka === ':' || t_Crka === '*') {
        return "jkeypodpicje";
    } else if (t_Crka === ']' || t_Crka === '}') {
        return "jkeybracketclose";
    } else if (t_Crka === ',' || t_Crka === '<') {
        return "jkeycomma";
    } else if (t_Crka === '.' || t_Crka === '>') {
        return "jkeyperiod";
    } else if (t_Crka === '/' || t_Crka === '?') {
        return "jkeyslash";
    } else if (t_Crka === '\\' || t_Crka === '_') {
        return "jkeybackslash";
    } else if (t_Crka === '\n') {
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
    return str.length === 1 && str.match(/[a-z]/i);
}
