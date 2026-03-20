// Korean(KNV7) standalone typer for MooTyper.
// Complete replacement for typer.js when Korean(KNV7) keyboard layout is in use.
// Handles Korean IME input via compositionend events (Chrome/Edge/Firefox/Opera).
// Based on typer.js; Korean-specific changes are marked KNV7.
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

// KNV7: set true when compositionend scores a character so the following
// Opera keypress for the same Korean character can be suppressed.
var knv7CompScored = false;

// KNV7: Physical key code -> [unshifted Jamo, shifted Jamo].
var knv7CodeMap = {
    KeyQ: ['ㅂ', 'ㅃ'], KeyW: ['ㅈ', 'ㅉ'], KeyE: ['ㄷ', 'ㄸ'], KeyR: ['ㄱ', 'ㄲ'],
    KeyT: ['ㅅ', 'ㅆ'], KeyY: ['ㅛ', 'ㅛ'], KeyU: ['ㅕ', 'ㅕ'], KeyI: ['ㅑ', 'ㅑ'],
    KeyO: ['ㅐ', 'ㅒ'], KeyP: ['ㅔ', 'ㅖ'], KeyA: ['ㅁ', 'ㅁ'], KeyS: ['ㄴ', 'ㄴ'],
    KeyD: ['ㅇ', 'ㅇ'], KeyF: ['ㄹ', 'ㄹ'], KeyG: ['ㅎ', 'ㅎ'], KeyH: ['ㅗ', 'ㅗ'],
    KeyJ: ['ㅓ', 'ㅓ'], KeyK: ['ㅏ', 'ㅏ'], KeyL: ['ㅣ', 'ㅣ'], KeyZ: ['ㅋ', 'ㅋ'],
    KeyX: ['ㅌ', 'ㅌ'], KeyC: ['ㅊ', 'ㅊ'], KeyV: ['ㅍ', 'ㅍ'], KeyB: ['ㅠ', 'ㅠ'],
    KeyN: ['ㅜ', 'ㅜ'], KeyM: ['ㅡ', 'ㅡ'],
    Semicolon: [';', ':'], Quote: ["'", '"'], Comma: [',', '<'], Period: ['.', '>'],
    Slash: ['/', '?'], BracketLeft: ['[', '{'], BracketRight: [']', '}'],
    Backslash: ['\\', '|'], Minus: ['-', '_'], Equal: ['=', '+'], Backquote: ['`', '~']
};

/**
 * KNV7: Return true if c is a Korean jamo or syllable character.
 */
function knv7IsHangul(c) {
    return typeof c === 'string' && c.length === 1 &&
        /[\u1100-\u11FF\u3131-\u3163\uAC00-\uD7A3]/.test(c);
}

/**
 * KNV7: Update the on-screen input viewer element.
 */
function knv7SetViewer(val) {
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
    // KNV7: unregister compositionend and compositionupdate scoring listeners.
    removeEventListener('compositionend', keyPressed);
    removeEventListener('compositionupdate', keyPressed);
    $("#form1").off("keypress", "#tb1", keyPressed);
    $("#form1").off("keyup", "#tb1", keyupFirst);
    $("#form1").off("keyup", "#tb1", keyupCombined);
    clearInterval(intervalID);
    clearInterval(interval2ID);
    endTime = new Date();
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
 * KNV7: Return the character the user typed for the given event.
 * Handles Korean IME compositionend data and physical key code mapping.
 *
 * @param {Event} e
 * @returns {string}
 */
function getPressedChar(e) {
    var etype = (e && e.type) ? e.type : '';
    var key = (e && typeof e.key === 'string') ? e.key : '';
    var code = (e && typeof e.code === 'string') ? e.code : '';
    var isShift = !!(e && e.shiftKey);

    // KNV7: compositionend delivers the final committed text in event.data.
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

    // KNV7: for keypress events, prefer direct Hangul key value.
    if (key && knv7IsHangul(key)) {
        return key;
    }

    // KNV7: map physical key code to Jamo.
    if (code && Object.prototype.hasOwnProperty.call(knv7CodeMap, code)) {
        return knv7CodeMap[code][isShift ? 1 : 0];
    }

    // KNV7: won-sign variants on Korean keyboards.
    if (key === '\u20a9' || key === '\uffe6') {
        return '\\';
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
function focusSet(e) {
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
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var rpAttId = $('input[name="rpAttId"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=2&attemptid=" + rpAttId +
        "&mistakes=" + mistakes + "&hits=" + (currentPos + mistakes);
    $.get(juri, function(data) {});
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
    intervalID = setInterval('updTimeSpeed()', 1000);
    var rpMootyperId = $('input[name="rpSityperId"]').val();
    var rpUser = $('input[name="rpUser"]').val();
    var juri = appUrl + "/mod/mootyper/atchk.php?status=1&mootyperid=" + rpMootyperId +
        "&userid=" + rpUser + "&time=" + (startTime.getTime() / 1000);
    $.get(juri, function(data) {
        $('input[name="rpAttId"]').val(data);
    });
    interval2ID = setInterval('doCheck()', 4000);
    rpTimeLimit2 = $('input[name="rpTimeLimit"]').val() * 60;
}

/**
 * KNV7: Process a keypress or compositionend event and score it.
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

    // KNV7: compositionupdate fires during syllable assembly; update viewer only, never score.
    if (etype === 'compositionupdate') {
        knv7SetViewer((e && e.data) ? e.data : '');
        return false;
    }

    // KNV7: suppress duplicate keypress that fires in Opera immediately after compositionend
    // scored a Korean character at this position.
    if (etype === 'keypress' && knv7CompScored) {
        var k = (e && typeof e.key === 'string') ? e.key : '';
        knv7CompScored = false;
        if (knv7IsHangul(k)) {
            return false; // suppress Opera duplicate
        }
        // Non-Korean key (e.g. space): fall through and score normally.
    }

    var keychar = getPressedChar(e);
    knv7SetViewer(keychar);

    if (keychar === currentChar || ((currentChar === '\n' || currentChar === '\r\n' ||
        currentChar === '\n\r' || currentChar === '\r') && (keychar === ' '))) {

        // KNV7: mark compositionend as scored to suppress Opera's follow-up keypress.
        if (etype === 'compositionend') {
            knv7CompScored = true;
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
        knv7SetViewer('');
        return true;

    } else if (keychar === ' ' && !countMistypedSpaces) {
        return false;

    } else {
        // KNV7: mark compositionend as scored even on mismatch.
        if (etype === 'compositionend') {
            knv7CompScored = true;
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
        knv7SetViewer('');
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
 * KNV7: Initialize variables and text to enter.
 * Registers compositionend (primary Korean scoring path) and keypress
 * (space, punctuation, and Opera Korean fallback).
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
    // KNV7: compositionend drives Korean character scoring in all browsers.
    // compositionupdate keeps the viewer updated during syllable assembly.
    // keypress handles space, punctuation, and Opera's Korean keypress path.
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
        intervalID = setInterval('updTimeSpeed()', 1000);
        interval2ID = setInterval('doCheck()', 3000);
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

    if (rpTimeLimit2 != 0) {
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
            if (str[i] == arr[j]) {
                dem++;
            }
        }
        result += "'" + arr[j] + "'=" + dem + ", ";
    }
    return result;
}

// Separation of characters.
function separateChars(str) {
    var array = new Array();
    var k = 1;
    array[0] = str[0];
    for (var i = 1; i < str.length; i++) {
        for (var j = 0; j <= array.length; j++) {
            if (j == array.length) {
                array[k] = str[i];
                k++;
            }
            if (str[i] == array[j]) {
                break;
            }
        }
    }
    return array;
}
