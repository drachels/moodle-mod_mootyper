/**
 * @fileOverview Telugu(V4.1) keyboard driver.
 * @author <a href="mailto:drachels@drachels.com">AL Rachels</a>
 * @version 4.1
 * @since 02/18/2018
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
    // phpcs:ignore
    if (ltr.match(/[ఒఔ()ఃఋఔఐఆఈఊభఙఘధఝఢఞఓఏఅఇఉఫఱఖథఛఠఎఁణళశష]/)) {
        this.shift = true;
    } else {
        this.shift = false;
    }
    if (ltr.match(/[౧౨౩౪౫౬౭౮౯౦ౄ]/)) {
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
        if (this.shift) {
            document.getElementById('jkeyshiftd').className = "next4";
            document.getElementById('jkeyshiftl').className = "next4";
        }
        if (this.alt) {
            document.getElementById('jkeyaltgr').className = "next2";
        }
    };
    this.turnOff = function() {
        if (isLetter(this.chr)) {
        // phpcs:ignore
            if (this.chr.match(/[ోే్ిరకతచ]/i)) {
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
 * @param {string} tCrka The current character.
 * @returns {number}.
 */
function thenFinger(tCrka) {
    if (tCrka === ' ') {
        return 5; // Highlight the spacebar.
    // phpcs:ignore
    //} else if (tCrka.match(/[ొఒ1౧ౌఔోఓెఎ0)౦-ఃృఋౄజఝౙచఛౘయ]/i)) {
    } else if (tCrka.match(/[ొఒ1౧ౌఔఓెోఎ0)౦జఝడఢఞ\-ఃృఋౄయచఛటఠ]/i)) {
        return 4; // Highlight the correct key above in red.
    // phpcs:ignore
    } else if (tCrka.match(/[2౨ైఐేఏంఁ9(౯దధతథ.]/i)) {
        return 3; // Highlight the correct key above in green.
    // phpcs:ignore
    } else if (tCrka.match(/[3౩ాఆ్అమణ8౮గఘకఖ,ష]/i)) {
        return 2; // Highlight the correct key above in yellow.
    // phpcs:ignore
    } else if (tCrka.match(/[4౪ీఈిఇనన5౫ూఊుఉవ6౬బభపఫలళ7౭హఙరఱసశ]/i)) {
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
    } else if (tCrka === 'ొ' || tCrka === 'ఒ') {
        return "jkeybackquote";
    } else if (tCrka === '1' || tCrka === '౧') {
        return "jkey1";
    } else if (tCrka === '2' || tCrka === '౨') {
        return "jkey2";
    } else if (tCrka === '3' || tCrka === '౩') {
        return "jkey3";
    } else if (tCrka === '4' || tCrka === '౪') {
        return "jkey4";
    } else if (tCrka === '5' || tCrka === '౫') {
        return "jkey5";
    } else if (tCrka === '6' || tCrka === '౬') {
        return "jkey6";
    } else if (tCrka === '7' || tCrka === '౭') {
        return "jkey7";
    } else if (tCrka === '8' || tCrka === '౮') {
        return "jkey8";
    } else if (tCrka === '9' || tCrka === '(' || tCrka === '౯') {
        return "jkey9";
    } else if (tCrka === '0' || tCrka === ')' || tCrka === '౦') {
        return "jkey0";
    } else if (tCrka === '-' || tCrka === 'ః') {
        return "jkeyminus";
    } else if (tCrka === 'ృ' || tCrka === 'ఋ' || tCrka === 'ౄ') {
        return "jkeyequals";
    } else if (tCrka === 'ౌ' || tCrka === 'ఔ') {
        return "jkeyq";
    } else if (tCrka === 'ై' || tCrka === 'ఐ') {
        return "jkeyw";
    } else if (tCrka === 'ా' || tCrka === 'ఆ') {
        return "jkeye";
    } else if (tCrka === 'ీ' || tCrka === 'ఈ') {
        return "jkeyr";
    } else if (tCrka === 'ూ' || tCrka === 'ఊ') {
        return "jkeyt";
    } else if (tCrka === 'బ' || tCrka === 'భ') {
        return "jkeyy";
    } else if (tCrka === 'హ' || tCrka === 'ఙ') {
        return "jkeyu";
    } else if (tCrka === 'గ' || tCrka === 'ఘ') {
        return "jkeyi";
    } else if (tCrka === 'ద' || tCrka === 'ధ') {
        return "jkeyo";
    } else if (tCrka === 'జ' || tCrka === 'ఝ') {
        return "jkeyp";
    } else if (tCrka === 'డ' || tCrka === 'ఢ') {
        return "jkeybracketl";
    } else if (tCrka === 'ఞ') {
        return "jkeybracketr";
    } else if (tCrka === 'ో' || tCrka === 'ఓ') {
        return "jkeya";
    } else if (tCrka === 'ే' || tCrka === 'ఏ') {
        return "jkeys";
    } else if (tCrka === '్' || tCrka === 'అ') {
        return "jkeyd";
    } else if (tCrka === 'ి' || tCrka === 'ఇ') {
        return "jkeyf";
    } else if (tCrka === 'ు' || tCrka === 'ఉ') {
        return "jkeyg";
    } else if (tCrka === 'ప' || tCrka === 'ఫ') {
        return "jkeyh";
    } else if (tCrka === 'ర' || tCrka === 'ఱ') {
        return "jkeyj";
    } else if (tCrka === 'క' || tCrka === 'ఖ') {
        return "jkeyk";
    } else if (tCrka === 'త' || tCrka === 'థ') {
        return "jkeyl";
    } else if (tCrka === 'చ' || tCrka === 'ఛ') {
        return "jkeysemicolon";
    } else if (tCrka === 'ట' || tCrka === 'ఠ') {
        return "jkeyapostrophe";
    } else if (tCrka === 'ె' || tCrka === 'ఎ') {
        return "jkeyz";
    } else if (tCrka === 'ం' || tCrka === 'ఁ') {
        return "jkeyx";
    } else if (tCrka === 'మ' || tCrka === 'ణ') {
        return "jkeyc";
    } else if (tCrka === 'న' || tCrka === 'న') {
        return "jkeyv";
    } else if (tCrka === 'వ') {
        return "jkeyb";
    } else if (tCrka === 'ల' || tCrka === 'ళ') {
        return "jkeyn";
    } else if (tCrka === 'స' || tCrka === 'శ') {
        return "jkeym";
    } else if (tCrka === ',' || tCrka === 'ష') {
        return "jkeycomma";
    } else if (tCrka === '.') {
        return "jkeyperiod";
    } else if (tCrka === 'య') {
        return "jkeyslash";
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
