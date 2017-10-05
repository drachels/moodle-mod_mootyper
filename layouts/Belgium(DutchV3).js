/**
 * Check for combined character.
 *
 */
function isCombined(chr) {
    return (chr === '´' || chr === '`' || chr === '~');
}

THE_LAYOUT = 'Belgium(DutchV3)';

/**
 * Process keyup for combined character.
 *
 */
function keyupCombined(e) {
    if (ended) {
        return false;
    }
    if (!started) {
        doStart();
    }
    var keychar = getPressedChar(e);
    if (keychar === '[not_yet_defined]') {
        combinedChar = true;
        return true;
    }
    if (combinedCharWait) {
        combinedCharWait = false;
        return true;
    }
    var currentText = $('#tb1').val();
    var lastChar = currentText.substring(currentText.length - 1);
    if (combinedChar && lastChar === currentChar) {
        if (show_keyboard) {
            var thisE = new keyboardElement(currentChar);
            thisE.turnOff();
        }
        if (currentPos === fullText.length - 1) {   // END.
            doKonec();
            return true;
        }
        if (currentPos < fullText.length - 1) {
            var nextChar = fullText[currentPos + 1];
            if (show_keyboard){
                var nextE = new keyboardElement(nextChar);
                nextE.turnOn();
            }
            if (!isCombined(nextChar)) {            // If next char is not combined char.
                $("#form1").off("keyup", "#tb1");
                $("#form1").on("keypress", "#tb1", keyPressed);
            }
        }
        combinedChar = false;
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    } else {
        combinedChar = false;
        napake++;
        var tbval = $('#tb1').val();
        $('#tb1').val(tbval.substring(0, currentPos));
        return false;
    }
}

/**
 * Process keyupFirst.
 *
 */
function keyupFirst(event) {
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").on("keyup", "#tb1", keyupCombined);
    return false;
}

/**
 * Check for character typed so flags can be set.
 *
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    this.accent = false;
    if (isLetter(ltr)) {
        this.shift = ltr.toUpperCase() === ltr;
    } else {
        // @codingStandardsIgnoreLine
        if (ltr.match(/[³1234567890°_¨*%£<?./+]/i)) {
            this.shift = true;
        } else {
            this.shift = false;
        }
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[\\|@#€{}\[\]~´`ñ]/i)) {
        this.shift = false;
        this.alt = true;
        this.accent = false;
    }
    // @codingStandardsIgnoreLine
    if (ltr.match(/[ëïöü]/i)) {
        this.shift = true;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    if (ltr === 'ê') {
        this.shift = false;
        this.alt = false;
        this.accent = false;
        this.caret = true;
    }
    if (ltr === 'ó' || ltr === 'á') {
        this.shift = false;
        this.alt = true;
        this.accent = true;
    }
    if (ltr === 'ñ') {
        this.shift = false;
        this.alt = true;
        this.accent = false;
        this.tilde = true;
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
            document.getElementById('jkeyaltgr').className = "next2";
        }
        if (this.accent) {
            document.getElementById('jkeyù').className = "next4";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "next4";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "next4";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            // @codingStandardsIgnoreLine
            if (this.chr.match(/[qsdfjklm]/i)) {
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
        if (this.accent) {
            document.getElementById('jkeyù').className = "normal";
        }
        if (this.caret) {
            document.getElementById('jkeycaret').className = "normal";
        }
        if (this.tilde) {
            document.getElementById('jkeyequal').className = "normal";
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
    } else if (t_Crka.match(/[²³&1|aáqw<>\\à0}pm)°^¨\[ù%´=+~\-_$*\]µ£`]/i)) {
        return 4; // Highlight the correct key above in red.
        // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[é2@zsxç9{oóöl:/]/i)) {
        return 3; // Highlight the correct key above in green.
        // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/["3#eéë€êdc!8iíïk;.]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // @codingStandardsIgnoreLine
    } else if (t_Crka.match(/[\'4rf(5tgbv§6yhnñè7uúüj,?]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6;
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
    } else if (t_Crka === ',' || t_Crka === '?') {
        return "jkeycomma";
    } else if (t_Crka === ';' || t_Crka === '.') {
        return "jkeysemicolon";
    } else if (t_Crka === ':' || t_Crka === '/') {
        return "jkeycolon";
    } else if (t_Crka === '-' || t_Crka === '_') {
        return "jkeyminus";
    } else if (t_Crka === '=' || t_Crka === '+' || t_Crka === '~') {
        return "jkeyequal";
    } else if (t_Crka === '&' || t_Crka === '|') {
        return "jkey1";
    } else if (t_Crka === 'é' || t_Crka === '@') {
        return "jkey2";
    } else if (t_Crka === '"' || t_Crka === '#') {
        return "jkey3";
    } else if (t_Crka === '\'') {
        return "jkey4";
    } else if (t_Crka === '(') {
        return "jkey5";
    } else if (t_Crka === '§') {
        return "jkey6";
    } else if (t_Crka === 'è') {
        return "jkey7";
    } else if (t_Crka === '!') {
        return "jkey8";
    } else if (t_Crka === 'ç' || t_Crka === '{') {
        return "jkey9";
    } else if (t_Crka === 'à' || t_Crka === '}') {
        return "jkey0";
    } else if (t_Crka === '^' || t_Crka === '¨' || t_Crka === '[') {
        return "jkeycaret";
    } else if ( t_Crka === '$' || t_Crka === '*' || t_Crka === ']') {
        return "jkeydollar";
    } else if (t_Crka === '%' || t_Crka === '´') {
        return "jkeyù";
    } else if (t_Crka === '£' || t_Crka === '`') {
        return "jkeyµ";
    } else if (t_Crka === ')' || t_Crka === '°') {
        return "jkeyparenr";
    } else if (t_Crka === '<' || t_Crka === '>') {
        return "jkeyckck";
    } else if (t_Crka === '²' || t_Crka === '³') {
        return "jkeytildo";
    } else if (t_Crka === 'a' || t_Crka === 'á') {
        return "jkeya";
    } else if (t_Crka === '¨') {
        return "jkeycaret";
    } else if (t_Crka === '€' || t_Crka === 'é' || t_Crka === 'ë' || t_Crka === 'ê') {
        return "jkeye";
    } else if (t_Crka === 'i' || t_Crka === 'í' || t_Crka === 'ï') {
        return "jkeyi";
    } else if (t_Crka === 'ñ') {
        return "jkeyn";
    } else if (t_Crka === 'o' || t_Crka === 'ó' || t_Crka === 'ö') {
        return "jkeyo";
    } else if (t_Crka === 'u' || t_Crka === 'ú' || t_Crka === 'ü') {
        return "jkeyu";
    } else {
        return "jkey" + t_Crka;
    }
}

/**
 * Is the typed letter part of the current alphabet.
 *
 */
function isLetter(str) {
    return str.length === 1 && str.match(/[0-9a-z¡ñçáéíóúüùµ]/i);
}
