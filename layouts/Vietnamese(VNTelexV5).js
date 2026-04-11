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
	return /[ăâđêôơưáàảãạắằẳẵặấầẩẫậéèẻẽẹếềểễệíìỉĩịóòỏõọốồổỗộớờởỡợúùủũụứừửữựýỳỷỹỵ]/i.test(chr);
}

var combinedChar = false;
var telexProgress = "";

/**
 * Return Telex key sequence for a Vietnamese target character.
 * @param {string} chr The target character.
 * @returns {string} Telex sequence.
 */
function telexSequenceForChar(chr) {
    var c = (chr || "").toLowerCase();
    switch (c) {
        case 'á': return 'as';
        case 'à': return 'af';
        case 'ả': return 'ar';
        case 'ã': return 'ax';
        case 'ạ': return 'aj';
        case 'ă': return 'aw';
        case 'ắ': return 'aws';
        case 'ằ': return 'awf';
        case 'ẳ': return 'awr';
        case 'ẵ': return 'awx';
        case 'ặ': return 'awj';
        case 'â': return 'aa';
        case 'ấ': return 'aas';
        case 'ầ': return 'aaf';
        case 'ẩ': return 'aar';
        case 'ẫ': return 'aax';
        case 'ậ': return 'aaj';

        case 'é': return 'es';
        case 'è': return 'ef';
        case 'ẻ': return 'er';
        case 'ẽ': return 'ex';
        case 'ẹ': return 'ej';
        case 'ê': return 'ee';
        case 'ế': return 'ees';
        case 'ề': return 'eef';
        case 'ể': return 'eer';
        case 'ễ': return 'eex';
        case 'ệ': return 'eej';

        case 'í': return 'is';
        case 'ì': return 'if';
        case 'ỉ': return 'ir';
        case 'ĩ': return 'ix';
        case 'ị': return 'ij';

        case 'ó': return 'os';
        case 'ò': return 'of';
        case 'ỏ': return 'or';
        case 'õ': return 'ox';
        case 'ọ': return 'oj';
        case 'ô': return 'oo';
        case 'ố': return 'oos';
        case 'ồ': return 'oof';
        case 'ổ': return 'oor';
        case 'ỗ': return 'oox';
        case 'ộ': return 'ooj';
        case 'ơ': return 'ow';
        case 'ớ': return 'ows';
        case 'ờ': return 'owf';
        case 'ở': return 'owr';
        case 'ỡ': return 'owx';
        case 'ợ': return 'owj';

        case 'ú': return 'us';
        case 'ù': return 'uf';
        case 'ủ': return 'ur';
        case 'ũ': return 'ux';
        case 'ụ': return 'uj';
        case 'ư': return 'uw';
        case 'ứ': return 'uws';
        case 'ừ': return 'uwf';
        case 'ử': return 'uwr';
        case 'ữ': return 'uwx';
        case 'ự': return 'uwj';

        case 'ý': return 'ys';
        case 'ỳ': return 'yf';
        case 'ỷ': return 'yr';
        case 'ỹ': return 'yx';
        case 'ỵ': return 'yj';

        case 'đ': return 'dd';
        default: return c;
    }
}

/**
 * Normalize Vietnamese combined letters to their physical Telex base key.
 * @param {string} chr The current character.
 * @returns {string} Base key character.
 */
function normalizeVietnameseBaseChar(chr) {
    if (!chr || typeof chr !== 'string') {
        return chr;
    }
    var c = chr.toLowerCase();
    if (/[ăâáàảãạắằẳẵặấầẩẫậ]/.test(c)) {
        return 'a';
    }
    if (/[đ]/.test(c)) {
        return 'd';
    }
    if (/[êéèẻẽẹếềểễệ]/.test(c)) {
        return 'e';
    }
    if (/[íìỉĩị]/.test(c)) {
        return 'i';
    }
    if (/[ôơóòỏõọốồổỗộớờởỡợ]/.test(c)) {
        return 'o';
    }
    if (/[ưúùủũụứừửữự]/.test(c)) {
        return 'u';
    }
    if (/[ýỳỷỹỵ]/.test(c)) {
        return 'y';
    }
    return c;
}

/**
 * Normalize IME-altered keyup input to a Telex-friendly base key.
 * @param {string} chr The latest typed key character.
 * @returns {string} Normalized key character.
 */
function normalizeTelexInputKey(chr) {
    if (!chr || typeof chr !== 'string') {
        return chr;
    }
    var c = chr.toLowerCase();
    if (c.length !== 1) {
        return c;
    }
    if (c === ' ') {
        return c;
    }
    if (typeof c.normalize === 'function') {
        c = c.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }
    return normalizeVietnameseBaseChar(c);
}

/**
 * Determine whether input is still composing the same Vietnamese base letter.
 * @param {string} typedChar The latest textarea character.
 * @param {string} targetChar The target character at current position.
 * @returns {boolean} True when composition is still in progress.
 */
function isCompositionInProgress(typedChar, targetChar) {
    if (!typedChar || !targetChar) {
        return false;
    }
    if (typedChar === targetChar) {
        return false;
    }
    return normalizeVietnameseBaseChar(typedChar) === normalizeVietnameseBaseChar(targetChar);
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
    var rawkeychar = getPressedChar(e);
    if (typeof rawkeychar === 'string' && rawkeychar.length === 1) {
        rawkeychar = rawkeychar.toLowerCase();
    }
    var keychar = normalizeTelexInputKey(rawkeychar);
    var targetchar = (currentChar || '').toLowerCase();
    if (rawkeychar === targetchar) {
        telexProgress = "";
        if (showKeyboard) {
            var thisE1 = new keyboardElement(currentChar);
            thisE1.turnOff();
        }
        if (currentPos == fullText.length - 1) {
            doTheEnd();
            return true;
        }
        if (currentPos < fullText.length - 1) {
            var nextChar1 = fullText[currentPos + 1];
            if (showKeyboard) {
                var nextE1 = new keyboardElement(nextChar1);
                nextE1.turnOn();
            }
            if (!isCombined(nextChar1)) {
                $("#form1").off("keyup", "#tb1");
                $("#form1").on("keypress", "#tb1", keyPressed);
            }
        }
        moveCursor(currentPos + 1);
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    }
    var expected = telexSequenceForChar(currentChar);
    if (typeof expected === 'string' && expected.length > 1) {
        if (keychar === expected.charAt(telexProgress.length)) {
            telexProgress += keychar;
            if (telexProgress.length < expected.length) {
                return true;
            }
            telexProgress = "";
            if (showKeyboard) {
                var thisE2 = new keyboardElement(currentChar);
                thisE2.turnOff();
            }
            if (currentPos == fullText.length - 1) {
                doTheEnd();
                return true;
            }
            if (currentPos < fullText.length - 1) {
                var nextChar2 = fullText[currentPos + 1];
                if (showKeyboard) {
                    var nextE2 = new keyboardElement(nextChar2);
                    nextE2.turnOn();
                }
                if (!isCombined(nextChar2)) {
                    $("#form1").off("keyup", "#tb1");
                    $("#form1").on("keypress", "#tb1", keyPressed);
                }
            }
            moveCursor(currentPos + 1);
            currentChar = fullText[currentPos + 1];
            currentPos++;
            return true;
        }
        telexProgress = "";
    }
    if(rawkeychar == '[not_yet_defined]') {
		combinedChar = true;
		return true;
	}
	if(combinedCharWait) {
		combinedCharWait = false;
		return true;
	}
	var currentText = $('#tb1').val();
    if (currentText.length <= currentPos) {
        // IME is still composing; no new character committed yet.
        return true;
    }
	var lastChar = currentText.substring(currentText.length-1);
    if (isCompositionInProgress(lastChar, currentChar)) {
        return true;
    }
    if(lastChar == currentChar)
	// && ((currentChar.toUpperCase() == currentChar && e.shiftKey) || (currentChar.toUpperCase() != currentChar))) 
	{
        if (showKeyboard) {
			var thisE = new keyboardElement(currentChar);
			thisE.turnOff();
		}
		if(currentPos == fullText.length-1) { // END. 
            doTheEnd();
			return true;
		}
		if(currentPos < fullText.length-1){
			var nextChar = fullText[currentPos+1];
            if (showKeyboard) {
				var nextE = new keyboardElement(nextChar);
				nextE.turnOn();
			}
			if(!isCombined(nextChar)) { // If next char is not combined char.
				$("#form1").off("keyup", "#tb1");
				$("#form1").on("keypress", "#tb1", keyPressed);
			}
		}
        combinedChar = false;
        telexProgress = "";
		moveCursor(currentPos+1);
		currentChar = fullText[currentPos+1];
		currentPos++;
		return true;
	}
	else
	{
        combinedChar = false;
        telexProgress = "";
        mistakes++;
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
    this.shiftleft = false;
    this.shiftright = false;
    this.alt = false;
    this.accent = false;
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
    tCrka = normalizeVietnameseBaseChar(tCrka);
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
    tCrka = normalizeVietnameseBaseChar(tCrka);
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
