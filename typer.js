var startTime,
    endTime,
    mistakes,
    mistakestring = "",
    currentPos,
    keyResult,
    started = false,
    ended = false,
    currentChar,
    fullText,
    intervalID = -1,
    interval2ID = -1,
    appUrl,
    showKeyboard,
    THE_LAYOUT,
    continuousType,
    countMistypedSpaces,
    countMistakes,
    keyupCombined,
    keyupFirst,
    $,
    differenceT,
    keyboardElement,
    combinedCharWait,
    isCombined,
    newCas,
    tDifference,
    secs,
    rpTimeLimit2,
    rpTimeLimit3,
    continueSubmitting = false,
    endSaveUrl = '';

/**
 * If not the end of fullText, move cursor to next character.
 * Color the previous character according to result.
 *
 * @param {number} nextPos Next cursor position.
 */
function moveCursor(nextPos) {
    if (nextPos > 0 && nextPos <= fullText.length) {
        $('#crka' + (nextPos - 1)).removeClass('txtBlue');
        if (keyResult) {
            $('#crka' + (nextPos - 1))
            .removeClass('txtBlack')
            .removeClass('txtRed')
            .addClass('txtGreen');
        } else {
            if (!(countMistakes)) {
                // Even with multiple keystrokes on the wrong key, only one mistake is counted.
                mistakes++;
                mistakestring += currentChar; // Keep a copy of the wrong letter.
            }
            $('#crka' + (nextPos - 1))
            .removeClass('txtBlack')
            .removeClass('txtGreen')
            .addClass('txtRed');
        }
    }
    if (nextPos < fullText.length) {
        $('#crka' + nextPos).addClass('txtBlue');
    }
    keyResult = true;
    scrollToNextLine($('#crka' + nextPos));
}

/**
 * Scroll to object.
 *
 * @param {DOM object} obj
 */
function scrollToNextLine(obj) {
    var scrollBox = $('#texttoenter');
    if ($(obj).length > 0) {
        scrollBox.animate({
            scrollTop: $(obj).offset().top - scrollBox.offset().top + scrollBox.scrollTop()
        }, 10);
    }
}

// Initialize scrolling text when document is ready.
$(document).ready(function() {
    $('#keyboard textarea:last').css({
        "height": "16px",
        "font-size": "10pt",
        "opacity": "0.0"
    });
    $("html, body").keyup(function(e) {
        scrollToNextLine($('#crka' + currentPos));
    })
    .mouseup(function(e) {
        if (ended || (e && e.target && e.target.id === 'btnContinue')) {
            return;
        }
        $('#keyboard textarea:last').focus();
    });
    $('#keyboard textarea:last').focus();
    scrollToNextLine($("#keyboard"));

    window.mootyperContinueClickHandler = function(e) {
        if (!ended) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
        if (continueSubmitting) {
            e.preventDefault();
            return false;
        }
        continueSubmitting = true;
        var form = document.getElementById('form1');
        var submitNow = function() {
            continueSubmitting = false;
            if (form) {
                if (typeof form.submit === 'function') {
                    form.submit();
                } else {
                    HTMLFormElement.prototype.submit.call(form);
                }
            }
        };

        var finalizeAndSubmit = function(attid) {
            if (attid) {
                var finishUrl = appUrl + "/mod/mootyper/atchk.php?status=3&attemptid=" + attid;
                $.ajax({
                    url: finishUrl,
                    method: 'GET',
                    timeout: 3000
                }).always(submitNow);
            } else {
                submitNow();
            }
        };

        var rpAttId = $('input[name="rpAttId"]').val();
        if (rpAttId) {
            finalizeAndSubmit(rpAttId);
            return false;
        }

        // Last-resort for very short attempts: create attempt id before final submit.
        var rpMootyperId = $('input[name="rpSityperId"]').val();
        var rpUser = $('input[name="rpUser"]').val();
        var stime = startTime ? (startTime.getTime() / 1000) : (new Date().getTime() / 1000);
        var startUri = appUrl + "/mod/mootyper/atchk.php?status=1&mootyperid=" + rpMootyperId +
            "&userid=" + rpUser + "&time=" + stime;
        $.get(startUri, function(data) {
            if (data) {
                $('input[name="rpAttId"]').val(data);
            }
        }).always(function() {
            finalizeAndSubmit($('input[name="rpAttId"]').val());
        });

        return false;
    };

    // Continue should advance only on pointer click, never by keyboard activation.
    $('#btnContinue')
        .on('keydown keyup keypress', function(e) {
            if (!e) {
                return true;
            }
            if (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32) {
                e.preventDefault();
                return false;
            }
            return true;
        })
        .on('click', window.mootyperContinueClickHandler);

    // After exercise end, ignore Enter/Space globally so keyboard cannot advance.
    $(document).on('keydown keypress keyup', function(e) {
        if (!ended) {
            return true;
        }
        if (e && (e.key === 'Enter' || e.key === ' ' || e.keyCode === 13 || e.keyCode === 32)) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
        return true;
    });
});

/**
 * End of typing.
 *
 */
function doTheEnd() {
    if (ended) {
        return;
    }
    ended = true;
    removeEventListener('compositionupdate', keyPressed);
    $("#form1").off("keypress", "#tb1", keyPressed);
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").off("keyup", "#tb1", keyupCombined);
    clearInterval(intervalID);
    clearInterval(interval2ID);
    endTime = new Date();
    // Update status bar with final data.
    var updateAll = updTimeSpeed();
    differenceT = timeDifference(startTime, endTime);
    var hours = differenceT.getHours();
    var mins = differenceT.getMinutes();
    var secs = differenceT.getSeconds();
    var samoSekunde = converToSeconds(hours, mins, secs);
    if (rpTimeLimit2 > 0) {
        samoSekunde = Math.min(samoSekunde, rpTimeLimit2);
    }
    $('input[name="rpFullHits"]').val((fullText.length + mistakes));
    $('input[name="rpTimeInput"]').val(samoSekunde);
    $('input[name="rpMistakesInput"]').val(mistakes);
    var speed = calculateSpeed(samoSekunde);
    $('input[name="rpAccInput"]').val(calculateAccuracy(fullText, mistakes).toFixed(2));
    $('input[name="rpSpeedInput"]').val(speed);
    var gwpm = (speed / 5);
    var wpm = (speed / 5) - (mistakes / (samoSekunde / 60));
    $('#jsWpm2').html((gwpm.toFixed(1)) + " | " + (wpm.toFixed(1)));
    $('input[name="rpWpmInput"]').val(wpm);
    $('input[name="rpMistakeDetailsInput"]').val(countChars(mistakestring));
    $('#tb1').attr('disabled', 'disabled');
    $('#tb1').blur();
    $('#btnContinue').prop('disabled', false);
    $('#btnContinue').css('visibility', 'visible');
    var finalizeAfterAttemptReady = function(retriesLeft, canCreateAttempt) {
        var rpAttId = $('input[name="rpAttId"]').val();
        if (!rpAttId) {
            if (retriesLeft > 0) {
                setTimeout(function() {
                    finalizeAfterAttemptReady(retriesLeft - 1, canCreateAttempt);
                }, 120);
                return;
            }
            if (!canCreateAttempt) {
                endSaveUrl = '';
                return;
            }
            // Last resort: create a new attempt id so completion can be finalized and graded.
            var rpMootyperId = $('input[name="rpSityperId"]').val();
            var rpUser = $('input[name="rpUser"]').val();
            var stime = startTime ? (startTime.getTime() / 1000) : (new Date().getTime() / 1000);
            var startUri = appUrl + "/mod/mootyper/atchk.php?status=1&mootyperid=" + rpMootyperId +
                "&userid=" + rpUser + "&time=" + stime;
            $.get(startUri, function(data) {
                if (data) {
                    $('input[name="rpAttId"]').val(data);
                }
            }).always(function() {
                finalizeAfterAttemptReady(0, false);
            });
            return;
        }

        endSaveUrl = appUrl + "/mod/mootyper/atchk.php?status=3&attemptid=" + rpAttId;

        $.ajax({
            url: endSaveUrl,
            method: 'GET',
            timeout: 3000
        }).always(function() {
            // Keep end-save best-effort only; click path will finalize again before submit.
        });
    };

    finalizeAfterAttemptReady(20, true);
    // At the end, add a scroll bar so student can see all the text and their mistakes.
    $('#texttoenter').css({"overflow-y":"scroll"});
    scrollToNextLine($("#reportDiv input:last"));
}

/**
 * Get the character for the pressed key depending on current keyboard driver.
 *
 * @param {char} e.
 * @returns {keychar}.
 */
function getPressedChar(e) {
    var keynum;
    var keychar;
    var numcheck;

    // addEventListener('keydown', (event) => {
    // Th.log('keydown We may have typed a Korean character here '+event.keyCode);
    // console.log('keydown We need to get the data from compositionupdate '+event.compositionupdate);
    // });
    // addEventListener('compositionupdate', (event) => {

    if (event.data) {
        // console.log('compositionupdate We typed a Korean character here '+event.data);
        keychar = event.data;
        // console.log('keychar We transferred event.data to keychar and it is '+keychar);

        if (keychar) {
            //console.log('if (keychar) was tested and it is '+keychar);
            return keychar;
        }
    }
    // });

    if (window.event) { // IE.
        keynum = e.keyCode;
    } else if (e.which) { // Netscape/Firefox/Opera.
        keynum = e.which;
    }
    if (keynum === 13) {
        keychar = '\n';
        // This hack is needed for Spanish keyboard, which uses 161 for some character.
    } else if ((!keynum || keynum === 160 || keynum === 161) && (keynum !== 161 && THE_LAYOUT !== 'Spanish')) {
        keychar = '[not_yet_defined]';
    } else {
        keychar = String.fromCharCode(keynum);
    }
    // console.log('We are returning keychar and it is '+keychar);

    return keychar;
}

/**
 * Set the focus.
 *
 * @param {char} e.
 * @returns {bolean}.
 */
function focusSet(e) {
    if(!started) {
        $('#tb1').val('');
        if (showKeyboard){
            var thisEl = new keyboardElement(fullText[0]);
            thisEl.turnOn();
        }
        return true;
    } else {
        $('#tb1').val(fullText.substring(0, currentPos));
        return true;
    }
}

/**
 * Do checks.
 *
 */
function doCheck() {
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var rpAttId = $('input[name="rpAttId"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=2&attemptid=" + rpAttId +
        "&mistakes=" + mistakes + "&hits=" + (currentPos + mistakes);
    $.get(juri, function( data ) { });
}

/**
 * Start exercise and reset data variables.
 *
 */
function doStart() {
    startTime = new Date();
    mistakes = 0;
    mistakestring = "";
    currentPos = 0;
    started = true;
    keyResult = true;
    currentChar = fullText[currentPos];
    intervalID = setInterval(updTimeSpeed, 1000);
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=1&mootyperid=" + rpMootyperId +
        "&userid=" + rpUser + "&time=" + (startTime.getTime() / 1000);
    $.get(juri, function(data) {
        $('input[name="rpAttId"]').val(data);
    });
    interval2ID = setInterval(doCheck, 4000);
    rpTimeLimit2 = $('input[name="rpTimeLimit"]').val() * 60;

}

/**
 * Process current key press and proceed based on typing mode.
 *
 * @param {char} e.
 * @returns {bolean}.
 */
function keyPressed(e) {
    // If reached the end of the lesson, don't let the student continue to type.
    if (ended) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        if (e && typeof e.stopImmediatePropagation === 'function') {
            e.stopImmediatePropagation();
        }
        return false;
    }
    // If this is the first typed letter, initialize the status bar data.
    if (!started) {
        doStart();
    }

    // Something was typed so go figure out what character it was. and return for further processing.
    var keychar = getPressedChar(e);
   // var keychar = addEventListener('keydown', getPressedChar);
   // var keychar = addEventListener('compositionstart', getPressedChar);

    if (keychar === currentChar || ((currentChar === '\n' || currentChar === '\r\n' ||
        currentChar === '\n\r' || currentChar === '\r') && (keychar === ' '))) {
        moveCursor(currentPos + 1);

        // Student is at the end of the exercise or has ran out of time.
        if ((currentPos === fullText.length - 1) || (rpTimeLimit3 < 0)) {

            $('#tb1').val($('#tb1').val() + currentChar);
            var elemOff = new keyboardElement(currentChar);
            elemOff.turnOff();
            currentChar = fullText[currentPos + 1];
            currentPos++;
            doTheEnd();
            return true;
        }
        // Student still has more to type.
        if (currentPos < fullText.length - 1) {
            var nextChar = fullText[currentPos + 1];
            if (showKeyboard) {
                var thisE = new keyboardElement(currentChar);
                thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow ||
                    thisE.uppercase_umlaut || thisE.accent)) {
                    combinedCharWait = true;
                }
                var nextE = new keyboardElement(nextChar);
                nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    // Ignore mistyped extra spaces unless set to count them.
    } else if (keychar === ' ' && !countMistypedSpaces) {
        return false;
    } else {
        if (countMistakes) {
            // With multiple keystrokes on the wrong key, each wrong keystroke is counted.
            // Typed the wrong letter so increment mistake count.
            mistakes++;
            // Keep a copy of the wrong letter.
            mistakestring += currentChar;
        }
        // Mistake count increased after correct key is typed if disabled and line 37 enabled.
        keyResult = false;
        // If not set for continuous typing, wait for correct letter.
        if ((!continuousType && !countMistypedSpaces) || (!continuousType && countMistypedSpaces)) {
            return false;
        // If continuous typing, show wrong letter and move on.
        } else if (currentPos < fullText.length - 1) {
                var nextChar = fullText[currentPos + 1];
            if (showKeyboard) {
                    var thisE = new keyboardElement(currentChar);
                    thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow || thisE.uppercase_umlaut || thisE.accent)) {
                        combinedCharWait = true;
                }
                    var nextE = new keyboardElement(nextChar);
                    nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        moveCursor(currentPos + 1);
        // Student is at the end of the exercise or ran out of time.
        if ((currentPos === fullText.length - 1) || (rpTimeLimit3 < 0)) {

            $('#tb1').val($('#tb1').val() + currentChar);
            var elemOff = new keyboardElement(currentChar);
            elemOff.turnOff();
            currentChar = fullText[currentPos + 1];
            currentPos++;
            doTheEnd();
            return true;
        }
        currentChar = fullText[currentPos + 1];
        currentPos++;
        return true;
    }
}

/**
 * Calculate time to seconds.
 *
 * @param {number} hrs.
 * @param {number} mins.
 * @param {number} seccs.
 * @returns {seconds}.
 */
function converToSeconds(hrs, mins, seccs) {
    if (hrs > 0) {
        mins = (hrs * 60) + mins;
    }
    if (mins === 0) {
        return seccs;
    } else {
        return (mins * 60) + seccs;
    }
}

/**
 * Calculate date difference.
 *
 * @param {number} t1.
 * @param {number} t2.
 * @returns {date}.
 */
function timeDifference(t1, t2) {
    var yrs = t1.getFullYear();
    var mnth = t1.getMonth();
    var dys = t1.getDate();
    var h1 = t1.getHours();
    var m1 = t1.getMinutes();
    var s1 = t1.getSeconds();
    var h2 = t2.getHours();
    var m2 = t2.getMinutes();
    var s2 = t2.getSeconds();
    var ure = h2 - h1;
    var minute = m2 - m1;
    var secunde = s2 - s1;
    return new Date(yrs, mnth, dys, ure, minute, secunde, 0);
}

/**
 * Initialize variables and text to enter.
 *
 * @param {varchar} ttext.
 * @param {number} tinprogress.
 * @param {number} tmistakes.
 * @param {number} thits.
 * @param {number} tstarttime.
 * @param {number} tattemptid.
 * @param {varchar} turl.
 * @param {boolean} tshowkeyboard.
 * @param {boolean} tcontinuoustype.
 * @param {boolean} tcountmistypedspaces.
 */
function inittexttoenter(ttext, tinprogress, tmistakes, thits, tstarttime, tattemptid, turl,
    tshowkeyboard, tcontinuoustype, tcountmistypedspaces, tcountmistakes) {
    // Needed for IME (input method editor) when using Korean keyboard layout.
    addEventListener('compositionupdate', keyPressed);
    $("#form1").on("keypress", "#tb1", keyPressed);
    showKeyboard = tshowkeyboard;
    continuousType = tcontinuoustype;
    countMistypedSpaces = tcountmistypedspaces;
    countMistakes = tcountmistakes;
    fullText = ttext;
    appUrl = turl;
    var tempStr = "";
    if (tinprogress) {
        $('input[name="rpAttId"]').val(tattemptid);
        startTime = new Date(tstarttime * 1000);
        mistakes = tmistakes;
        currentPos = (thits - tmistakes);
        currentChar = fullText[currentPos]; // Current character (trenutni = current).
        if(showKeyboard) {
            var nextE = new keyboardElement(currentChar);
            nextE.turnOn();
            if (isCombined(currentChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupCombined);
            }
        }
        started = true;
        intervalID = setInterval(updTimeSpeed, 1000);
        interval2ID = setInterval(doCheck, 3000);
        for (var i = 0; i < currentPos; i++) {
            var tChar = ttext[i];
            if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>" + tChar + "</span>";
            }
        }
        tempStr += "<span id='crka" + currentPos + "' class='txtBlue'>" + currentChar + "</span>";
        for (var j = currentPos + 1; j < ttext.length; j++) {
            var tChar = ttext[j];
            if (tChar === '\n') {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>" + tChar + "</span>";
            }
        }
    } else {
        for (var i = 0; i < ttext.length; i++) {
            var tChar = ttext[i];
            if (i === 0) {
                tempStr += "<span id='crka" + i + "' class='txtBlue'>" + tChar + "</span>";
                if (isCombined(tChar)) {
                    $("#form1").off("keypress", "#tb1", keyPressed);
                    $("#form1").on("keyup", "#tb1", keyupCombined);
                }
            } else if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtBlack'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtBlack'>" + tChar + "</span>";
            }
        }
    }
    $('#texttoenter').html(tempStr);
}

/**
 * Calculate speed.
 *
 * @param {number} sc.
 * @returns {number}.
 */
function calculateSpeed(sc) {
    if ((!continuousType && !countMistypedSpaces) || (!continuousType && countMistypedSpaces)) {
        // Normally use this.
        return (((currentPos + mistakes) * 60) / sc);
    } else {
        // Use this when set to continuous type.
        return ((currentPos * 60) / sc);
    }
}

/**
 * Calculate accuracy.
 *
 * @param {number} currentPos.
 * @param {number} mistakes.
 * @returns {number}.
 */
function calculateAccuracy() {
    if (currentPos + mistakes === 0) {
        return 0;
    }
    // Only correctly typed count.
    return (((currentPos - mistakes) * 100) / currentPos);
}

/**
 * Update current time, progress, mistakes presicsion, hits per minute, and words per minute.
 *
 * @param {number} startTime.
 * @param {number} newCas.
 * @param {number} secs.
 * @param {number} tDifference.
 * @param {number} mistakes.
 * @param {number} currentPos.
 * @param {number} fullText.
 * @param {number} currentPos.
 * @param {varchar} mistakestring.
 */
function updTimeSpeed() {
    newCas = new Date();
    secs = Math.floor((newCas.getTime() - startTime.getTime()) / 1000);
    if (secs < 0) {
        secs = 0;
    }

    // If timelimit is set, subtract elapsed time from the timelimit and set a flag.
    if (rpTimeLimit2 !== 0) {
        rpTimeLimit3 = rpTimeLimit2 - secs;
        if (!ended && rpTimeLimit3 <= 0) {
            doTheEnd();
            return;
        }
    }

    tDifference = new Date(secs * 1000);

    // Each minute when seconds display is less than 10 seconds, add leading 0:0.
    if (tDifference.getSeconds() < 10) {
        $('#jsTime2').html(tDifference.getMinutes() + ':0' + tDifference.getSeconds());
    } else {
        // Each time the display is greater than 9 seconds drop the leading zero.
        $('#jsTime2').html(tDifference.getMinutes() + ':' + tDifference.getSeconds());
    }

    $('#jsProgress2').html(currentPos + "/" + fullText.length);

    $('#jsMistakes2').html(mistakes);

    $('#jsSpeed2').html(calculateSpeed(secs).toFixed(2));

    $('#jsAcc2').html(calculateAccuracy(fullText, mistakes).toFixed(2));

    var gwpm = (calculateSpeed(secs) / 5);
    var nwpm = ((calculateSpeed(secs) / 5) - (mistakes / (secs / 60)));
    $('#jsWpm2').html((gwpm.toFixed(1)) + " | " + (nwpm.toFixed(1)));
    if (mistakestring) {
        $('#jsDetailMistake').html(countChars(mistakestring));
    }
}

/**
 * Count the number of characters.
 * Separating characters = separateChars
 * @param {int} dem. Number of mistakes for the current character
 * @result {varchar} result.
 */
function countChars(str) {
    var arr = separateChars(str);
    arr.sort();
    var result = "" ;
    //alert(arr);
    for ( var j = 0 ; j<arr.length ; j++) {
        var dem = 0 ;
        for (var i = 0; i < str.length; i++) {
            if (str[i] === arr[j]) dem++;
        }
        result += "'" + arr[j] + "'=" + dem  + ", " ;
    }
    return result;
}

// Separation of characters = separateChars
function separateChars(str) {
//console.log('In the separateChars function and str is '+str);

    var array = [];
    var k = 1 ;
    array[0] = str[0];

    for (var i = 1; i<str.length; i++) {
        for (var j = 0; j<=array.length; j++) {
            if (j === array.length) {
                array[k] = str[i];
                k++;
            }
            if (str[i] === array[j]) break;
        }
    }
    return array;
}
