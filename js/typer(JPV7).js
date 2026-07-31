// Japanese(JPV7) standalone typer for MooTyper.
// Complete replacement for typer.js when Japanese(JPV7) keyboard layout is in use.
// Handles Japanese IME input via compositionend events.
// Based on typer.js; Japanese-specific changes are marked JPV7.
/* exported focusSet, inittexttoenter */

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
    continuousType,
    countMistypedSpaces,
    countMistakes,
    keyupCombined,
    keyupFirst,
    $,
    differenceT,
    keyboardElement,
    isCombined,
    newCas,
    tDifference,
    secs,
    rpTimeLimit2,
    rpTimeLimit3,
    continueSubmitting = false,
    endSaveUrl = '';

// JPV7: set true when compositionend scores a character so a duplicate
// follow-up keypress for the same IME character can be suppressed.
var jpv7CompScored = false;

/**
 * JPV7: Return true if c is a Japanese IME output character.
 */
function jpv7IsImeChar(c) {
    return typeof c === 'string' && c.length === 1 &&
        /[\u3040-\u309F\u30A0-\u30FF\u3400-\u4DBF\u4E00-\u9FFF\uFF66-\uFF9F]/.test(c);
}

/**
 * JPV7: Update the on-screen input viewer element.
 */
function jpv7SetViewer(val) {
    var el = document.getElementById('inputviewer');
    if (el) {
        el.textContent = 'Your current input: ' + (val || '');
    }
}

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
                mistakes++;
                mistakestring += currentChar;
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

$(document).ready(function() {
    $('#keyboard textarea:last').css({
        "height": "16px",
        "font-size": "10pt",
        "opacity": "0.0"
    });
    $("html, body").keyup(function() {
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
 */
function doTheEnd() {
    if (ended) {
        return;
    }
    ended = true;
    // JPV7: unregister compositionend and compositionupdate scoring listeners.
    removeEventListener('compositionend', keyPressed);
    removeEventListener('compositionupdate', keyPressed);
    $("#form1").off("keypress", "#tb1", keyPressed);
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").off("keyup", "#tb1", keyupCombined);
    clearInterval(intervalID);
    clearInterval(interval2ID);
    endTime = new Date();
    updTimeSpeed();
    differenceT = timeDifference(startTime, endTime);
    var hours = differenceT.getHours();
    var mins = differenceT.getMinutes();
    var secs = differenceT.getSeconds();
    var samoSekunde = converToSeconds(hours, mins, secs);
    if (rpTimeLimit2 > 0) {
        samoSekunde = Math.min(samoSekunde, rpTimeLimit2);
    }
    $('input[name="rpFullHits"]').val((currentPos + mistakes));
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
        }).always(function() {});
    };
    finalizeAfterAttemptReady(20, true);
    $('#texttoenter').css({"overflow-y": "scroll"});
    scrollToNextLine($("#reportDiv input:last"));
}

/**
 * JPV7: Return the character the user typed for the given event.
 * Uses compositionend data as the primary IME path.
 *
 * @param {Event} e
 * @returns {string}
 */
function getPressedChar(e) {
    var etype = (e && e.type) ? e.type : '';
    var key = (e && typeof e.key === 'string') ? e.key : '';

    // JPV7: compositionend delivers the final committed text in event.data.
    if (etype === 'compositionend') {
        var data = (e && typeof e.data === 'string') ? e.data : '';
        if (!data && e && e.originalEvent && typeof e.originalEvent.data === 'string') {
            data = e.originalEvent.data;
        }
        if (data) {
            return data;
        }
        // Fallback: last character in the input field.
        var tb1 = document.getElementById('tb1');
        if (tb1 && tb1.value) {
            return tb1.value.charAt(tb1.value.length - 1);
        }
        return '';
    }

    // For non-composition events, use key when it is a single typed character.
    if (key && key.length === 1) {
        return key;
    }

    if (key === 'Enter') {
        return '\n';
    }

    // Standard keyCode fallback for non-Korean keys (space, punctuation, Enter, etc.)
    var keynum = 0;
    if (e && e.keyCode) {
        keynum = e.keyCode;
    } else if (e && e.which) {
        keynum = e.which;
    }
    if (keynum === 13) {
        return '\n';
    }
    if (!keynum || keynum === 160 || keynum === 161) {
        return '[not_yet_defined]';
    }
    return String.fromCharCode(keynum);
}

/**
 * Set the focus.
 *
 * @param {char} e.
 * @returns {boolean}.
 */
function focusSet() {
    if (!started) {
        $('#tb1').val('');
        if (showKeyboard) {
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
 */
function doCheck() {
    var rpAttId = $('input[name="rpAttId"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=2&attemptid=" + rpAttId +
        "&mistakes=" + mistakes + "&hits=" + (currentPos + mistakes);
    $.get(juri, function() {});
}

/**
 * Start exercise and reset data variables.
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
 * JPV7: Process a keypress or compositionend event and score it.
 * compositionend is the primary scoring path for Chrome/Edge/Firefox.
 * keypress handles space, punctuation, and Opera's Korean keypress fallback.
 *
 * @param {Event} e
 * @returns {boolean}
 */
function keyPressed(e) {
    if (ended) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        if (e && typeof e.stopImmediatePropagation === 'function') {
            e.stopImmediatePropagation();
        }
        return false;
    }
    if (!started) {
        doStart();
    }

    var etype = (e && e.type) ? e.type : '';

    // JPV7: compositionupdate fires during conversion assembly; update viewer only, never score.
    if (etype === 'compositionupdate') {
        jpv7SetViewer((e && e.data) ? e.data : '');
        return false;
    }

    // JPV7: while an IME composition is active, ignore keypress events and wait
    // for compositionend to provide the committed character(s).
    if (etype === 'keypress') {
        var keynum = 0;
        if (e && e.keyCode) {
            keynum = e.keyCode;
        } else if (e && e.which) {
            keynum = e.which;
        }
        var composing = !!(e && e.isComposing);
        var keyname = (e && typeof e.key === 'string') ? e.key : '';
        if (composing || keyname === 'Process' || keynum === 229) {
            return false;
        }
    }

    var scoreTypedChar = function(keychar) {
        if (keychar === currentChar || ((currentChar === '\n' || currentChar === '\r\n' ||
            currentChar === '\n\r' || currentChar === '\r') && (keychar === ' '))) {

            // JPV7: mark compositionend as scored to suppress duplicate follow-up keypress.
            if (etype === 'compositionend') {
                jpv7CompScored = true;
            }

            moveCursor(currentPos + 1);

            if ((currentPos === fullText.length - 1) || (rpTimeLimit3 < 0)) {
                $('#tb1').val($('#tb1').val() + currentChar);
                var elemOff = new keyboardElement(currentChar);
                elemOff.turnOff();
                currentChar = fullText[currentPos + 1];
                currentPos++;
                doTheEnd();
                return true;
            }
            if (currentPos < fullText.length - 1) {
                var nextChar = fullText[currentPos + 1];
                if (showKeyboard) {
                    var thisE = new keyboardElement(currentChar);
                    thisE.turnOff();
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
            jpv7SetViewer('');
            return true;

        } else if (keychar === ' ' && !countMistypedSpaces) {
            return false;

        } else {
            // JPV7: mark compositionend as scored even on mismatch.
            if (etype === 'compositionend') {
                jpv7CompScored = true;
            }

            if (countMistakes) {
                mistakes++;
                mistakestring += currentChar;
            }
            keyResult = false;
            if ((!continuousType && !countMistypedSpaces) || (!continuousType && countMistypedSpaces)) {
                return false;
            } else if (currentPos < fullText.length - 1) {
                var nextChar = fullText[currentPos + 1];
                if (showKeyboard) {
                    var thisE = new keyboardElement(currentChar);
                    thisE.turnOff();
                    var nextE = new keyboardElement(nextChar);
                    nextE.turnOn();
                }
                if (isCombined(nextChar)) {
                    $("#form1").off("keypress", "#tb1", keyPressed);
                    $("#form1").on("keyup", "#tb1", keyupFirst);
                }
            }
            moveCursor(currentPos + 1);
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
            jpv7SetViewer('');
            return true;
        }
    };

    // JPV7: suppress duplicate keypress that fires immediately after compositionend
    // scored an IME character at this position.
    if (etype === 'keypress' && jpv7CompScored) {
        var k = (e && typeof e.key === 'string') ? e.key : '';
        jpv7CompScored = false;
        if (jpv7IsImeChar(k)) {
            return false;
        }
        // Non-IME key (e.g. space): fall through and score normally.
    }

    var keychar = getPressedChar(e);
    jpv7SetViewer(keychar);

    if (etype === 'compositionend' && keychar && keychar.length > 1) {
        var committedChars = Array.from(keychar);
        var scoredAny = false;
        for (var i = 0; i < committedChars.length; i++) {
            var scored = scoreTypedChar(committedChars[i]);
            if (!scored) {
                return scoredAny;
            }
            scoredAny = true;
            if (ended) {
                return true;
            }
        }
        return scoredAny;
    }
    return scoreTypedChar(keychar);
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
 * Escape a single exercise character for safe HTML display.
 *
 * @param {string} ch
 * @returns {string}
 */
function escapeExerciseChar(ch) {
    if (ch === '&') {
        return '&amp;';
    }
    if (ch === '<') {
        return '&lt;';
    }
    if (ch === '>') {
        return '&gt;';
    }
    if (ch === '"') {
        return '&quot;';
    }
    if (ch === "'") {
        return '&#39;';
    }
    return ch;
}

/**
 * JPV7: Initialize variables and text to enter.
 * Registers compositionend (primary IME scoring path) and keypress.
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
 * @param {boolean} tcountmistakes.
 */
function inittexttoenter(ttext, tinprogress, tmistakes, thits, tstarttime, tattemptid, turl,
    tshowkeyboard, tcontinuoustype, tcountmistypedspaces, tcountmistakes) {
    // JPV7: compositionend drives Japanese IME character scoring.
    // compositionupdate keeps the viewer updated during conversion assembly.
    // keypress handles space, punctuation, and non-IME input.
    addEventListener('compositionend', keyPressed);
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
        currentChar = fullText[currentPos];
        if (showKeyboard) {
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
                tempStr += "<span id='crka" + i + "' class='txtGreen'>" +
                    escapeExerciseChar(tChar) + "</span>";
            }
        }
        tempStr += "<span id='crka" + currentPos + "' class='txtBlue'>" +
            escapeExerciseChar(currentChar) + "</span>";
        for (var j = currentPos + 1; j < ttext.length; j++) {
            var tChar = ttext[j];
            if (tChar === '\n') {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>" +
                    escapeExerciseChar(tChar) + "</span>";
            }
        }
    } else {
        for (var i = 0; i < ttext.length; i++) {
            var tChar = ttext[i];
            if (i === 0) {
                tempStr += "<span id='crka" + i + "' class='txtBlue'>" +
                    escapeExerciseChar(tChar) + "</span>";
                if (isCombined(tChar)) {
                    $("#form1").off("keypress", "#tb1", keyPressed);
                    $("#form1").on("keyup", "#tb1", keyupCombined);
                }
            } else if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtBlack'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtBlack'>" +
                    escapeExerciseChar(tChar) + "</span>";
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
        return (((currentPos + mistakes) * 60) / sc);
    } else {
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
    return (((currentPos - mistakes) * 100) / currentPos);
}

/**
 * Update current time, progress, mistakes, precision, hits per minute, and words per minute.
 */
function updTimeSpeed() {
    newCas = new Date();
    secs = Math.floor((newCas.getTime() - startTime.getTime()) / 1000);
    if (secs < 0) {
        secs = 0;
    }

    if (rpTimeLimit2 !== 0) {
        rpTimeLimit3 = rpTimeLimit2 - secs;
        if (!ended && rpTimeLimit3 <= 0) {
            doTheEnd();
            return;
        }
    }

    tDifference = new Date(secs * 1000);

    if (tDifference.getSeconds() < 10) {
        $('#jsTime2').html(tDifference.getMinutes() + ':0' + tDifference.getSeconds());
    } else {
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
 * @param {string} str.
 * @result {varchar} result.
 */
function countChars(str) {
    var arr = separateChars(str);
    arr.sort();
    var result = "";
    for (var j = 0; j < arr.length; j++) {
        var dem = 0;
        for (var i = 0; i < str.length; i++) {
            if (str[i] === arr[j]) {
                dem++;
            }
        }
        result += "'" + arr[j] + "'=" + dem + ", ";
    }
    return result;
}

// Separation of characters.
function separateChars(str) {
    var array = [];
    var k = 1;
    array[0] = str[0];
    for (var i = 1; i < str.length; i++) {
        for (var j = 0; j <= array.length; j++) {
            if (j === array.length) {
                array[k] = str[i];
                k++;
            }
            if (str[i] === array[j]) {
                break;
            }
        }
    }
    return array;
}
