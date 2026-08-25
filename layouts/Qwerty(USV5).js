/**
 * @fileOverview Qwerty(USV5) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 5.0
 * @since 03/04/2019
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
 * Check for character typed so flags can be set.
 * @param {string} ltr The current letter.
 */
function keyboardElement(ltr) {
    this.chr = ltr.toLowerCase();
    this.alt = false;
    if (isLetter(ltr)) { // Specified shift left, right shift.
        if (ltr.match(/[QWERTASDFGZXCVB~!@#$%]/i)) {
            this.shiftright = true;
        } else if (ltr.match(/[YUIOPHJKLNM^&*()_+{}|:"<>?]/i)) {
            this.shiftleft = true;
        }
    }
    this.turnOn = function () {
        if (isLetterNotNumber(this.chr)) {
            document.getElementById(dobiTipkoId(this.chr)).className = "next" + dobiFinger(this.chr.toLowerCase()) + " font18";
            if (dobiLeftHand(this.chr.toLowerCase())) {
                document.getElementById('lefthand').src = "images/left" + dobiFinger(this.chr.toLowerCase()) + ".png";
                document.getElementById('righthand').src = "images/righthand.png";
            } else {
                document.getElementById('righthand').src = "images/right" + dobiFinger(this.chr.toLowerCase()) + ".png";
                document.getElementById('lefthand').src = "images/lefthand.png";
            }
        } else if (this.chr == ' ') {
            document.getElementById(dobiTipkoId(this.chr)).className = "nextSpace";
            document.getElementById('lefthand').src = "images/left5.png";
            document.getElementById('righthand').src = "images/right5.png";
        } else {
            document.getElementById(dobiTipkoId(this.chr)).className = "next" + dobiFinger(this.chr.toLowerCase());
            if (dobiLeftHand(this.chr.toLowerCase())) {
                document.getElementById('lefthand').src = "images/left" + dobiFinger(this.chr.toLowerCase()) + ".png";
                    document.getElementById('righthand').src = "images/righthand.png";
            } else {
                document.getElementById('righthand').src = "images/right" + dobiFinger(this.chr.toLowerCase()) + ".png";
                document.getElementById('lefthand').src = "images/lefthand.png";
            }
        }
        if (this.chr == '\n' || this.chr == '\r\n' || this.chr == '\n\r' || this.chr == '\r') {
            document.getElementById('jkeyenter').className = "nextEnter";
            document.getElementById('righthand').src = "images/right4.png";
            document.getElementById('lefthand').src = "images/lefthand.png";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "nextShift left";
            document.getElementById('lefthand').src = "images/left4.png";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftd').className = "nextShift";
            document.getElementById('righthand').src = "images/right4.png";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "nextSpace";
        }
    };
    this.turnOff = function () {
        if (isLetter(this.chr)) {
            document.getElementById(dobiTipkoId(this.chr)).className = "key single";
        } else if (this.chr == ' ') {
            document.getElementById(dobiTipkoId(this.chr)).className = "key wide_5";
        } else if (this.chr == '\n' || this.chr == '\r\n' || this.chr == '\n\r' || this.chr == '\r') {
            document.getElementById('jkeyenter').className = "key wide_3";
        } else {
            document.getElementById(dobiTipkoId(this.chr)).className = "key";
        }
        if (this.chr == '\n' || this.chr == '\r\n' || this.chr == '\n\r' || this.chr == '\r') {
            document.getElementById('jkeyenter').classname = "key wide_3";
        }
        if (this.shiftleft) {
            document.getElementById('jkeyshiftl').className = "key wide_4";
        }
        if (this.shiftright) {
            document.getElementById('jkeyshiftd').className = "key wide_4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "key wide_5";
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
    } else if (tCrka.match(/[`~1!qaz0)p;:/?\-_[{'"=+\]}\\|]/i)) {
        return 4; // Highlight the correct key above in red.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[2@wsx9(ol.>]/i)) {
        return 3; // Highlight the correct key above in green.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[3#edc8*ik,<]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // @codingStandardsIgnoreLine
    } else if (tCrka.match(/[4$rfv5%tgb6^yhn7&ujm]/i)) {
        return 1; // Highlight the correct key above in blue.
    } else {
        return 6; // Do not change any highlight.
    }
}

function dobiLeftHand(t_crka) { // Check the key is hit by the left hand?
    if (t_crka == '~' || t_crka == '!' || t_crka == '@' || t_crka == '#' || t_crka == '$' || t_crka == '%' ||
        t_crka == '`' || t_crka == '1' ||  t_crka == '2' || t_crka == '3' || t_crka == '4' || t_crka == '5' ||
        t_crka == 'q' || t_crka == 'w' || t_crka == 'e' || t_crka == 'r' || t_crka == 't' ||
        t_crka == 'a' || t_crka == 's' || t_crka == 'd' || t_crka == 'f' || t_crka == 'g' ||
         t_crka == 'z' || t_crka == 'x' || t_crka == 'c' || t_crka == 'v' || t_crka == 'b') {
         return true;
    } else {
        return false;
    }
}

/**
 * Get ID of key to highlight based on current character.
 * @param {string} tCrka The current character.
 * @returns {string}.
 */
function getKeyID(tCrka) {
    if (t_crka == ' ') {
        return "jkeyspace";
    } else if (t_crka == ',') {
        return "jkeyvejica";
    } else if (t_crka == '\n') {
        return "jkeyenter";
    } else if (t_crka == '.') {
        return "jkeypika";
    } else if (t_crka == '-' || t_crka == '_') {
        return "jkeypomislaj";
    } else if (t_crka == '!') {
        return "jkey1";
    } else if (t_crka == '@') {
        return "jkey2";
    } else if (t_crka == '#') {
        return "jkey3";
    } else if (t_crka == '$') {
        return "jkey4";
    } else if (t_crka == '%') {
        return "jkey5";
    } else if (t_crka == '^') {
        return "jkey6";
    } else if (t_crka == '&') {
        return "jkey7";
    } else if (t_crka == '*') {
        return "jkey8";
    } else if (t_crka == '(') {
        return "jkey9";
    } else if (t_crka == ')') {
        return "jkey0";
    } else if (t_crka ==  '-' || t_crka == '_') {
        return "jkeypomislaj";
    } else if (t_crka == '[' || t_crka == '{') {
        return "jkeyoglokl";
    } else if (t_crka == ']' || t_crka == '}') {
        return "jkeyoglzak";
    } else if (t_crka == ';' || t_crka == ':') {
        return "jkeypodpicje";
    } else if (t_crka == "'" || t_crka == '"') {
        return "jkeycrtica";
    } else if (t_crka == "\\" || t_crka == '|') {
        return "jkeybackslash";
    } else if (t_crka == ',') {
        return "jkeyvejica";
    } else if (t_crka == '.') {
        return "jkeypika";
    } else if (t_crka == '=' || t_crka == '+') {
        return "jkeyequals";
    } else if (t_crka == '?' || t_crka == '/') {
        return "jkeyslash";
    } else if (t_crka == '<' || t_crka == '>') {
        return "jkeyckck";
    } else if (t_crka == '`' || t_crka == '~') { // Viet: mới thêm vào 13/02/2013.
        return "jkeytildo";
    } else {
        return "jkey" + t_crka;
}

function isLetter(str) {
    return str.length === 1 && str.match(/[a-z]/i);
}
function isLetterNotNumber(str) {
    return str.length === 1 && str.match(/[a-z]/);
}
