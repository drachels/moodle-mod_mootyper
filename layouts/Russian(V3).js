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

THE_LAYOUT = 'Russian(V3)';
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
        if (ltr.match(/[!"№;%:?*()_+/,]/i)) {
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
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if ( this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[фываолдж]/i)) {
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
    } else if (t_Crka.match(/[ё1!йфя0\-=\\)_+/зхъжэ.,]/i)) {
        return 4; // Highlight the correct key above in red.
    } else if (t_Crka.match(/[2цыч"9щдю(]/i)) {
        return 3; // Highlight the correct key above in green.
    } else if (t_Crka.match(/[3увс№8шлб*]/i)) {
        return 2; // Highlight the correct key above in yellow.
    } else if (t_Crka.match(/[4кам5епи;%6нрт7гоь:?]/i)) {
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
    } else if (t_Crka === '!') {
        return "jkey1";
    } else if (t_Crka === '"') {
        return "jkey2";
    } else if (t_Crka === '№') {
        return "jkey3";
    } else if (t_Crka === ';') {
        return "jkey4";
    } else if (t_Crka === '%') {
        return "jkey5";
    } else if (t_Crka === ':') {
        return "jkey6";
    } else if (t_Crka === '?') {
        return "jkey7";
    } else if (t_Crka === '*') {
        return "jkey8";
    } else if (t_Crka === '(') {
        return "jkey9";
    } else if (t_Crka === ')') {
        return "jkey0";
    } else if (t_Crka === '-' || t_Crka === '_') {
        return "jkeyminus";
    } else if (t_Crka === '.' || t_Crka === ',') {
        return "jkeyperiod";
    } else if (t_Crka === '=' || t_Crka === '+') {
        return "jkeyequals";
    } else if (t_Crka === "\\" || t_Crka === '/') {
        return "jkeybackslash";
    } else {
        return "jkey" + t_Crka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 *
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[ёйцукенгшщзхъфывапролджэячсмитьбю]/i);
}
