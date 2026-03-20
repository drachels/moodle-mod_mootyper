/**
 * @fileOverview Vietnamese(VNTelexV5.0) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 20241026
 */

/**
 * Check for combined character.
 * @param {string} chr The combined character.
 * @returns {string} The character.
 */
function isCombined(chr) {
 	//return false;
	return (chr == 'ă' || chr == 'â' || chr == 'ê' || chr == 'ô');
	//return (chr == '´' || chr == '`');
}

/**
 * Process keyup for combined character.
 * @param {string} e The combined character.
 * @returns {bolean} The result.
 */
function keyupCombined(e) {
	if(ended)
		return false;
	if(!started)
		doStart();
	var keychar = getPressedChar(e);
	if(keychar == '[not_yet_defined]') {
		combinedChar = true;
		return true;
	}
	if(combinedCharWait) {
		combinedCharWait = false;
		return true;
	}
	var currentText = $('#tb1').val();
	var lastChar = currentText.substring(currentText.length-1);
	if(combinedChar && lastChar==currentChar) 
	// && ((currentChar.toUpperCase() == currentChar && e.shiftKey) || (currentChar.toUpperCase() != currentChar))) 
	{
		if(show_keyboard){
			var thisE = new keyboardElement(currentChar);
			thisE.turnOff();
		}
		if(currentPos == fullText.length-1) { // END. 
			doKonec();
			return true;
		}
		if(currentPos < fullText.length-1){
			var nextChar = fullText[currentPos+1];
			if(show_keyboard){
				var nextE = new keyboardElement(nextChar);
				nextE.turnOn();
			}
			if(!isCombined(nextChar)) { // If next char is not combined char.
				$("#form1").off("keyup", "#tb1");
				$("#form1").on("keypress", "#tb1", keyPressed);
			}
		}
		combinedChar = false;
		moveCursor(currentPos+1);
		currentChar = fullText[currentPos+1];
		currentPos++;
		return true;
	}
	else
	{
		combinedChar = false;
		napake++;
		var tbval = $('#tb1').val();
		$('#tb1').val(tbval.substring(0, currentPos));
		return false;
	}	
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
    // phpcs:ignore
    if (isLetter(ltr)) { // Set specified shift key for right or left.
        if (ltr.match(/[ĂÂÊÔĐQWERTASDFGZXCVB]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[ƯƠYUIOP|HJKL:_+"NM<>?]/)) {
            this.shiftleft = true;
        } else if (ltr.match(/[~!@#$%]/)) {
            this.shiftright = true;
        } else if (ltr.match(/[\^&*()_+{}|:"<>?]/)) {
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
            if (this.chr.match(/[asdfjkl;]/i)) {
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
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftr').className = "next4";
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
        // phpcs:ignore
    } else if (tCrka.match(/[`~1!'"qaăâ;:đ0)zp\[{/?\-_\]}₫ươ=+\\|]/i)) {
        return 4; // Highlight the correct key above in red.
        // phpcs:ignore
    } else if (tCrka.match(/[2@slwx.>oq̣9(]/i)) {
        return 3; // Highlight the correct key above in green.
        // phpcs:ignore
    } else if (tCrka.match(/[ê3#,<ediḱ8*c]/i)) {
        return 2; // Highlight the correct key above in yellow.
        // phpcs:ignore
    } else if (tCrka.match(/[ô4$vrjnuk5̀%ỷ6^fb̃7&tghm]/i)) {
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
    } else if (tCrka === ',') {
        return "jkeycomma";
    } else if (tCrka === '\n') {
        return "jkeyenter";
    } else if (tCrka === '.') {
        return "jkeyperiod";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '`') {
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
    } else if (tCrka === ')' || tCrka === 'đ') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === '_') {
        return "jkeyminus";
    } else if (tCrka === '[' || tCrka === '{' || tCrka === 'ư') {
        return "jkeybracketl";
    } else if (tCrka === ']' || tCrka === '}' || tCrka === 'ơ') {
        return "jkeybracketr";
    } else if (tCrka === ';' || tCrka === ':') {
        return "jkeysemicolon";
    } else if (tCrka === "'" || tCrka === '"') {
        return "jkeycrtica";
    } else if (tCrka === "\\" || tCrka === '|') {
        return "jkeybackslash";
    } else if (tCrka === ',' || tCrka === '<') {
        return "jkeycomma";
    } else if (tCrka === '.' || tCrka === '>') {
        return "jkeyperiod";
    } else if (tCrka === '=' || tCrka === '+' || tCrka === '₫') {
        return "jkeyequals";
    } else if (tCrka === '?' || tCrka === '/') {
        return "jkeyslash";
    } else if (tCrka === '~' || tCrka === '`') {
        return "jkeybackquote";
    } else if (tCrka === 'ă' || tCrka === 'â' || tCrka === 'á' || tCrka === 'à' || tCrka === 'ả' || tCrka === 'ã' || tCrka === 'ạ') {
        return "jkeya";
    } else if (tCrka === 'ê') {
        return "jkeye";
    } else if (tCrka === 'ô') {
        return "jkeyo";
    } else if (tCrka === 'ư') {
        return "jkeyu";
    } else if (tCrka === 'đ') {
        return "jkeyd";

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
