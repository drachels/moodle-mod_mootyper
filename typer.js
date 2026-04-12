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
    endSaveUrl = '',
    lastKeyChar = null,
    lastKeyTimeMs = 0,
    suppressPhantomUntilMs = 0;

/**
 * Detect keyboard auto-repeat so held keys do not inflate progress/hits.
 *
 * @param {Event} e
 * @param {string} keychar
 * @returns {boolean}
 */
function isAutoRepeatKey(e, keychar) {
    if (e && e.repeat === true) {
        return true;
    }
    var now = Date.now();
    var isrepeat = false;
    if (keychar && lastKeyChar === keychar && (now - lastKeyTimeMs) < 40) {
        isrepeat = true;
    }
    lastKeyChar = keychar;
    lastKeyTimeMs = now;
    return isrepeat;
}

/**
 * If not the end of fullText, move cursor to next character.
 * Color the previous character according to result.
 *
 * @param {number} nextPos Next cursor position.
 */
function moveCursor(nextPos) {
    var startPos = currentPos;
    var endPos = Math.min(nextPos - 1, fullText.length - 1);

    if (nextPos > 0 && nextPos <= fullText.length && endPos >= startPos) {
        for (var p = startPos; p <= endPos; p++) {
            $('#crka' + p).removeClass('txtBlue');
            if (keyResult) {
                $('#crka' + p)
                .removeClass('txtBlack')
                .removeClass('txtRed')
                .addClass('txtGreen');
            } else {
                $('#crka' + p)
                .removeClass('txtBlack')
                .removeClass('txtGreen')
                .addClass('txtRed');
            }
        }
        if (!keyResult && !(countMistakes)) {
            // Even with multiple keystrokes on the wrong key, only one mistake is counted.
            mistakes++;
            mistakestring += currentChar; // Keep a copy of the wrong letter.
        }
    }

    var visiblePos = nextPos;
    while (visiblePos < fullText.length && $('#crka' + visiblePos).css('display') === 'none') {
        visiblePos++;
    }
    if (visiblePos < fullText.length) {
        $('#crka' + visiblePos).addClass('txtBlue');
    }
    keyResult = true;
    scrollToNextLine($('#crka' + visiblePos));
}

/**
 * Snap an index forward to the next visible text span.
 * Hidden spans are placeholders for multi-codepoint display clusters.
 *
 * @param {number} pos Candidate index.
 * @returns {number}
 */
function nextVisiblePos(pos) {
    var p = pos;
    while (p < fullText.length && $('#crka' + p).css('display') === 'none') {
        p++;
    }
    return p;
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
    removeEventListener('compositionend', keyPressed);
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
    // Full hits should reflect what was actually typed, not total exercise length.
    $('input[name="rpFullHits"]').val((currentPos + mistakes));
    $('input[name="rpTimeInput"]').val(samoSekunde);
    $('input[name="rpMistakesInput"]').val(mistakes);
    var speed = calculateSpeed(samoSekunde);
    $('input[name="rpAccInput"]').val(calculateAccuracy(fullText, mistakes).toFixed(2));
    $('input[name="rpSpeedInput"]').val(speed);
    var gwpm = (speed / 5);
    var wpm = Math.max(0, (speed / 5) - (mistakes / (samoSekunde / 60)));
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

    // addEventListener('keydown', (event) => {
    // Th.log('keydown We may have typed a Korean character here '+event.keyCode);
    // console.log('keydown We need to get the data from compositionupdate '+event.compositionupdate);
    // });
    // addEventListener('compositionupdate', (event) => {

    if (e && typeof e.data === 'string' && e.data.length > 0) {
        // console.log('compositionupdate We typed a Korean character here '+event.data);
        keychar = e.data;
        if (keychar.length > 1) {
            // If a multi-character token exactly matches the upcoming target sequence,
            // keep it intact (for example Tamil99 ligatures like க்ஷ / ஶ்ரீ).
            // Otherwise keep legacy behavior used by some IMEs (for example VIE TL)
            // that emit cumulative composition text and use only the latest character.
            var upcoming = '';
            if (typeof fullText === 'string' && typeof currentPos === 'number') {
                upcoming = fullText.substring(currentPos, currentPos + keychar.length);
            }
            if (keychar !== upcoming) {
                // Some IMEs emit cumulative payloads. Prefer the longest suffix
                // that still matches the upcoming target text before falling back.
                var bestsuffix = '';
                var maxsuffix = Math.min(keychar.length, fullText.length - currentPos);
                for (var slen = maxsuffix; slen >= 1; slen--) {
                    var suffix = keychar.substring(keychar.length - slen);
                    if (fullText.substring(currentPos, currentPos + slen) === suffix) {
                        bestsuffix = suffix;
                        break;
                    }
                }
                if (bestsuffix) {
                    keychar = bestsuffix;
                } else {
                    keychar = keychar.charAt(keychar.length - 1);
                }
            }
        }
        // console.log('keychar We transferred event.data to keychar and it is '+keychar);

        if (keychar) {
            //console.log('if (keychar) was tested and it is '+keychar);
            return keychar;
        }
    }

    // Modern browsers provide the final character in e.key, including accented letters.
    if (e && typeof e.key === 'string') {
        if (e.key === 'Enter') {
            return '\n';
        }
        if (e.key.length === 1) {
            return e.key;
        }
        if (e.key.length > 1 && typeof fullText === 'string' && typeof currentPos === 'number' &&
                fullText.substring(currentPos, currentPos + e.key.length) === e.key) {
            return e.key;
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
    } else if (!keynum || keynum === 160 || keynum === 161) {
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
            var thisEl = new keyboardElement(fullText[0], 0);
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

    // Combined-character layouts handle composed letters in keyupCombined.
    // Ignore compositionupdate here for combined targets to avoid double-processing.
    if (e && e.type === 'compositionupdate' && typeof isCombined === 'function' && isCombined(currentChar)) {
        return false;
    }

    var requiredAdvance = 1;
    if (typeof getDisplayClusterAt === 'function' && typeof fullText === 'string' &&
            typeof currentPos === 'number') {
        var cinfo = getDisplayClusterAt(fullText, currentPos);
        if (cinfo && cinfo.skip > 0) {
            requiredAdvance = cinfo.skip + 1;
        }
    }

    // Something was typed so go figure out what character it was. and return for further processing.
    var keychar = getPressedChar(e);

    // IME fallback: when key events don't carry the committed glyphs reliably,
    // derive the latest committed input directly from the textarea tail.
    if (typeof fullText === 'string' && typeof currentPos === 'number') {
        var tbtext = $('#tb1').val();
        if (typeof tbtext === 'string' && tbtext.length > currentPos) {
            var tail = tbtext.substring(currentPos);
            var maxlen = Math.min(tail.length, fullText.length - currentPos);
            var bestmatch = '';
            for (var m = maxlen; m >= 1; m--) {
                var candidate = tail.substring(0, m);
                if (fullText.substring(currentPos, currentPos + m) === candidate) {
                    bestmatch = candidate;
                    break;
                }
            }
            if (bestmatch && (typeof keychar !== 'string' || keychar === '[not_yet_defined]' ||
                    keychar.length === 0 || (keychar.length === 1 && bestmatch.length > 1))) {
                keychar = bestmatch;
            }
        }
    }

    // After accepting some IME/multi-codepoint inputs, browsers can emit trailing
    // key events that should not count as new input for the next character.
    // During this short window, only allow input that matches the upcoming target text.
    if (Date.now() < suppressPhantomUntilMs) {
        var upcomingTarget = fullText.substring(currentPos);
        var hasmatch = (typeof keychar === 'string' && keychar.length > 0 &&
            upcomingTarget.indexOf(keychar) === 0);
        if (!hasmatch) {
            return false;
        }
    }

    // In some IME/browser combinations (for example Chrome + Vietnamese IME),
    // compositionupdate can fire with carry-over text before the actual keypress.
    // Ignore those pre-events unless they match the current target sequence.
    if (e && (e.type === 'compositionupdate' || e.type === 'compositionend')) {
        var compMatch = (keychar === currentChar);
        if (!compMatch && typeof keychar === 'string' && keychar.length > 1 &&
                fullText.substring(currentPos, currentPos + keychar.length) === keychar) {
            compMatch = true;
        }
        if (!compMatch) {
            return false;
        }
    }

    if (isAutoRepeatKey(e, keychar)) {
        if (e && typeof e.preventDefault === 'function') {
            e.preventDefault();
        }
        return false;
    }

    // Some IME/browser paths emit placeholder key codes before commit.
    // Treat them as no-op so they do not taint next valid cursor advance.
    if (keychar === '[not_yet_defined]') {
        return false;
    }

    // For multi-codepoint display clusters (for example Tamil99 க்ஷ/ஶ்ரீ),
    // ignore partial prefix commits and wait for the full cluster commit.
    if (requiredAdvance > 1 && typeof keychar === 'string' && keychar.length > 0 &&
            keychar.length < requiredAdvance &&
            fullText.substring(currentPos, currentPos + keychar.length) === keychar) {
        return false;
    }
   // var keychar = addEventListener('keydown', getPressedChar);
   // var keychar = addEventListener('compositionstart', getPressedChar);

    var multiMatch = false;
    var clusterTarget = '';
    if (requiredAdvance > 1) {
        clusterTarget = fullText.substring(currentPos, currentPos + requiredAdvance);
    }

    if (requiredAdvance > 1 && typeof keychar === 'string' && keychar.length > requiredAdvance &&
            keychar.substring(keychar.length - requiredAdvance) === clusterTarget) {
        keychar = clusterTarget;
    }

    if (typeof keychar === 'string' && keychar.length > 1 &&
            keychar.length >= requiredAdvance &&
            fullText.substring(currentPos, currentPos + keychar.length) === keychar) {
        multiMatch = true;
    }

    if (requiredAdvance > 1 && !multiMatch && keychar === currentChar) {
        // Some IME/keymap paths emit only the first codepoint for a combined keypress.
        // Treat that as the full cluster commit for this target position.
        keychar = clusterTarget;
        multiMatch = true;
    }

    if (requiredAdvance > 1 && !multiMatch && e && typeof e.code === 'string' &&
            typeof getKeyID === 'function' && typeof keyboardElement === 'function') {
        var expectedKeyId = getKeyID(clusterTarget);
        var expectedCode = '';
        if (expectedKeyId === 'jkeyt') {
            expectedCode = 'KeyT';
        } else if (expectedKeyId === 'jkeyy') {
            expectedCode = 'KeyY';
        }

        if (expectedCode && e.code === expectedCode) {
            var expectedElem = new keyboardElement(currentChar, currentPos);
            var needsShift = !!(expectedElem.shiftleft || expectedElem.shiftright);
            if (!needsShift || e.shiftKey) {
                // Fallback path for cases where browser events report physical key only.
                keychar = clusterTarget;
                multiMatch = true;
            }
        }
    }

    // For multi-codepoint cluster targets, only a full cluster commit should be scored.
    // Ignore intermediate/noisy events so they do not count as wrong keypresses.
    if (requiredAdvance > 1 && !multiMatch) {
        return false;
    }

    var advance = multiMatch ? keychar.length : 1;

    if (((keychar === currentChar) && requiredAdvance === 1) || multiMatch || ((currentChar === '\n' || currentChar === '\r\n' ||
        currentChar === '\n\r' || currentChar === '\r') && (keychar === ' '))) {
        var nextPos = nextVisiblePos(currentPos + advance);
        moveCursor(nextPos);
        suppressPhantomUntilMs = Date.now() + 120;

        // Student is at the end of the exercise or has ran out of time.
        if ((nextPos >= fullText.length) || (rpTimeLimit3 < 0)) {

            $('#tb1').val($('#tb1').val() + currentChar);
            var elemOff = new keyboardElement(currentChar, currentPos);
            elemOff.turnOff();
            currentPos = nextPos;
            currentChar = fullText[currentPos];
            doTheEnd();
            return true;
        }
        // Student still has more to type.
        if (nextPos < fullText.length) {
            var nextChar = fullText[nextPos];
            if (showKeyboard) {
                var thisE = new keyboardElement(currentChar, currentPos);
                thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow ||
                    thisE.uppercase_umlaut || thisE.accent)) {
                    combinedCharWait = true;
                }
                var nextE = new keyboardElement(nextChar, nextPos);
                nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        currentPos = nextPos;
        currentChar = fullText[currentPos];
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
        // If not set for continuous typing, wait for correct letter.
        if ((!continuousType && !countMistypedSpaces) || (!continuousType && countMistypedSpaces)) {
            return false;
        // If continuous typing, show wrong letter and move on.
        } else if (currentPos < fullText.length - 1) {
                // For continuous typing, mark this position as incorrect and advance.
                keyResult = false;
                var nextPos = nextVisiblePos(currentPos + 1);
                var nextChar = fullText[nextPos];
            if (showKeyboard) {
                var thisE = new keyboardElement(currentChar, currentPos);
                    thisE.turnOff();
                if (isCombined(nextChar) && (thisE.shift || thisE.alt || thisE.pow || thisE.uppercase_umlaut || thisE.accent)) {
                        combinedCharWait = true;
                }
                var nextE = new keyboardElement(nextChar, nextPos);
                    nextE.turnOn();
            }
            if (isCombined(nextChar)) {
                $("#form1").off("keypress", "#tb1", keyPressed);
                $("#form1").on("keyup", "#tb1", keyupFirst);
            }
        }
        var nextPos = nextVisiblePos(currentPos + 1);
        moveCursor(nextPos);
        suppressPhantomUntilMs = Date.now() + 120;
        // Student is at the end of the exercise or ran out of time.
        if ((nextPos >= fullText.length) || (rpTimeLimit3 < 0)) {

            $('#tb1').val($('#tb1').val() + currentChar);
            var elemOff = new keyboardElement(currentChar, currentPos);
            elemOff.turnOff();
            currentPos = nextPos;
            currentChar = fullText[currentPos];
            doTheEnd();
            return true;
        }
        currentPos = nextPos;
        currentChar = fullText[currentPos];
        return true;
    }
}

/**
 * Group known Tamil99 multi-codepoint key outputs into one display token
 * for the scrolling text area while keeping underlying index positions.
 * @param {string} text Full source text.
 * @param {number} pos Current index.
 * @returns {{display: string, skip: number}}
 */
function getDisplayClusterAt(text, pos) {
    if (text[pos] === 'க' && text[pos + 1] === '்' && text[pos + 2] === 'ஷ') {
        return {display: 'க்ஷ', skip: 2};
    }
    if (text[pos] === 'ஶ' && text[pos + 1] === '்' && text[pos + 2] === 'ர' && text[pos + 3] === 'ீ') {
        return {display: 'ஶ்ரீ', skip: 3};
    }
    return {display: text[pos], skip: 0};
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
    // Keep compositionupdate fallback for IME environments where keypress is limited.
    // keyPressed() filters this while combined-char handlers are active.
    addEventListener('compositionupdate', keyPressed);
    addEventListener('compositionend', keyPressed);
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
        currentPos = nextVisiblePos(currentPos);
        currentChar = fullText[currentPos]; // Current character (trenutni = current).
        if(showKeyboard) {
            var nextE = new keyboardElement(currentChar, currentPos);
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
            var info = getDisplayClusterAt(ttext, i);
            var tChar = info.display;
            if (tChar === '\n') {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + i + "' class='txtGreen'>" + tChar + "</span>";
            }
            if (info.skip > 0) {
                for (var hs = 1; hs <= info.skip; hs++) {
                    tempStr += "<span id='crka" + (i + hs) + "' class='txtGreen' style='display:none'></span>";
                }
                i += info.skip;
            }
        }
        var cinfo = getDisplayClusterAt(ttext, currentPos);
        tempStr += "<span id='crka" + currentPos + "' class='txtBlue'>" + cinfo.display + "</span>";
        if (cinfo.skip > 0) {
            for (var chs = 1; chs <= cinfo.skip; chs++) {
                tempStr += "<span id='crka" + (currentPos + chs) + "' class='txtBlue' style='display:none'></span>";
            }
        }
        for (var j = currentPos + 1; j < ttext.length; j++) {
            var ninfo = getDisplayClusterAt(ttext, j);
            var tChar = ninfo.display;
            if (tChar === '\n') {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>&darr;</span><br>";
            } else {
                tempStr += "<span id='crka" + j + "' class='txtBlack'>" + tChar + "</span>";
            }
            if (ninfo.skip > 0) {
                for (var nhs = 1; nhs <= ninfo.skip; nhs++) {
                    tempStr += "<span id='crka" + (j + nhs) + "' class='txtBlack' style='display:none'></span>";
                }
                j += ninfo.skip;
            }
        }
    } else {
        for (var i = 0; i < ttext.length; i++) {
            var info = getDisplayClusterAt(ttext, i);
            var tChar = info.display;
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
            if (info.skip > 0) {
                var hiddenClass = (i === 0) ? 'txtBlue' : 'txtBlack';
                for (var hs = 1; hs <= info.skip; hs++) {
                    tempStr += "<span id='crka" + (i + hs) + "' class='" + hiddenClass + "' style='display:none'></span>";
                }
                i += info.skip;
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
    // Accuracy is correct hits divided by total keystrokes affecting score.
    return ((currentPos * 100) / (currentPos + mistakes));
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
    var nwpm = Math.max(0, ((calculateSpeed(secs) / 5) - (mistakes / (secs / 60))));
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
