// Korean(KNV7) addon for MooTyper.
// This file augments typer.js and should be loaded after typer.js.
(function() {
    // typer.js defines getPressedChar globally. If it is missing, do nothing.
    if (typeof getPressedChar !== 'function') {
        return;
    }

    var originalGetPressedChar = getPressedChar;
    var originalKeyPressed = (typeof keyPressed === 'function') ? keyPressed : null;
    var lastComposedText = '';
    var suppressNextKeypress = false;
    var lastPhysicalCode = '';
    var lastPhysicalShift = false;
    var lastPhysicalTime = 0;
    var skipNextKeypress = false;
    var inputViewerPrefix = 'Your current input: ';
    var lastCommitSyllable = '';
    var lastCommitPos = -1;
    var lastCommitTime = 0;

    function setInputViewer(value) {
        var viewer = document.getElementById('inputviewer');
        if (!viewer) {
            return;
        }
        viewer.textContent = inputViewerPrefix + (value || '');
    }

    var keyToJamo = {
        q: 'ㅂ',
        Q: 'ㅃ',
        w: 'ㅈ',
        W: 'ㅉ',
        e: 'ㄷ',
        E: 'ㄸ',
        r: 'ㄱ',
        R: 'ㄲ',
        t: 'ㅅ',
        T: 'ㅆ',
        y: 'ㅛ',
        u: 'ㅕ',
        i: 'ㅑ',
        o: 'ㅐ',
        O: 'ㅒ',
        p: 'ㅔ',
        P: 'ㅖ',
        a: 'ㅁ',
        s: 'ㄴ',
        d: 'ㅇ',
        f: 'ㄹ',
        g: 'ㅎ',
        h: 'ㅗ',
        j: 'ㅓ',
        k: 'ㅏ',
        l: 'ㅣ',
        z: 'ㅋ',
        x: 'ㅌ',
        c: 'ㅊ',
        v: 'ㅍ',
        b: 'ㅠ',
        n: 'ㅜ',
        m: 'ㅡ'
    };

    var codeToChar = {
        KeyQ: ['ㅂ', 'ㅃ'],
        KeyW: ['ㅈ', 'ㅉ'],
        KeyE: ['ㄷ', 'ㄸ'],
        KeyR: ['ㄱ', 'ㄲ'],
        KeyT: ['ㅅ', 'ㅆ'],
        KeyY: ['ㅛ', 'ㅛ'],
        KeyU: ['ㅕ', 'ㅕ'],
        KeyI: ['ㅑ', 'ㅑ'],
        KeyO: ['ㅐ', 'ㅒ'],
        KeyP: ['ㅔ', 'ㅖ'],
        KeyA: ['ㅁ', 'ㅁ'],
        KeyS: ['ㄴ', 'ㄴ'],
        KeyD: ['ㅇ', 'ㅇ'],
        KeyF: ['ㄹ', 'ㄹ'],
        KeyG: ['ㅎ', 'ㅎ'],
        KeyH: ['ㅗ', 'ㅗ'],
        KeyJ: ['ㅓ', 'ㅓ'],
        KeyK: ['ㅏ', 'ㅏ'],
        KeyL: ['ㅣ', 'ㅣ'],
        KeyZ: ['ㅋ', 'ㅋ'],
        KeyX: ['ㅌ', 'ㅌ'],
        KeyC: ['ㅊ', 'ㅊ'],
        KeyV: ['ㅍ', 'ㅍ'],
        KeyB: ['ㅠ', 'ㅠ'],
        KeyN: ['ㅜ', 'ㅜ'],
        KeyM: ['ㅡ', 'ㅡ'],
        Semicolon: [';', ':'],
        Quote: ["'", '"'],
        Comma: [',', '<'],
        Period: ['.', '>'],
        Slash: ['/', '?'],
        BracketLeft: ['[', '{'],
        BracketRight: [']', '}'],
        Backslash: ['\\', '|'],
        Minus: ['-', '_'],
        Equal: ['=', '+'],
        Backquote: ['`', '~']
    };

    function isHangulSyllable(chr) {
        return typeof chr === 'string' && chr.length === 1 && /[\uAC00-\uD7A3]/.test(chr);
    }

    function extractLastHangulSyllable(text) {
        if (typeof text !== 'string' || text.length === 0) {
            return '';
        }
        for (var i = text.length - 1; i >= 0; i--) {
            var ch = text.charAt(i);
            if (isHangulSyllable(ch)) {
                return ch;
            }
        }
        return '';
    }

    function getTypingFieldValue(e) {
        if (e && e.target && typeof e.target.value === 'string') {
            return e.target.value;
        }

        var tb1 = document.getElementById('tb1');
        if (tb1 && typeof tb1.value === 'string') {
            return tb1.value;
        }

        return '';
    }

    function isDuplicateCommit(syllable, pos) {
        var now = Date.now();
        if (syllable && lastCommitSyllable === syllable && lastCommitPos === pos && (now - lastCommitTime) < 300) {
            return true;
        }
        return false;
    }

    function markCommit(syllable, pos) {
        lastCommitSyllable = syllable || '';
        lastCommitPos = pos;
        lastCommitTime = Date.now();
    }

    function getComposedFromEvent(e, consume) {
        var composed = '';
        if (typeof consume === 'undefined') {
            consume = true;
        }

        if (lastComposedText) {
            composed = lastComposedText;
            if (consume) {
                lastComposedText = '';
            }
        }

        if (!composed && e && e.originalEvent && typeof e.originalEvent.data === 'string') {
            composed = e.originalEvent.data;
        }

        if (!composed && e && typeof e.data === 'string') {
            composed = e.data;
        }

        // Some IME/browser combinations emit empty commit payloads.
        // In that case, derive the committed syllable from the typing field.
        if (!composed && e && (e.type === 'compositionend' || e.type === 'input')) {
            var typed = getTypingFieldValue(e);
            var tailsyl = extractLastHangulSyllable(typed);
            if (tailsyl) {
                composed = tailsyl;
            }
        }

        return composed;
    }

    // Capture finalized IME text on composition end.
    document.addEventListener('compositionstart', function() {
        setInputViewer('');
    }, true);

    document.addEventListener('compositionupdate', function(ev) {
        if (ev && typeof ev.data === 'string') {
            setInputViewer(ev.data);
        }
    }, true);

    document.addEventListener('compositionend', function(ev) {
        if (ev) {
            var committed = (typeof ev.data === 'string') ? ev.data : '';
            if (!committed) {
                committed = extractLastHangulSyllable(getTypingFieldValue(ev));
            }

            lastComposedText = committed;
            suppressNextKeypress = !!committed;
            skipNextKeypress = !!committed;
            setInputViewer(committed);
        }
    }, true);

    // Keep a short-lived record of the physical key so keypress/IME events can reuse it.
    document.addEventListener('keydown', function(ev) {
        if (!ev) {
            return;
        }
        if (typeof ev.code === 'string' && ev.code) {
            lastPhysicalCode = ev.code;
            lastPhysicalShift = !!ev.shiftKey;
            lastPhysicalTime = Date.now();
        }
        // For plain non-composing keydown we should not leak stale composed text.
        if (!ev.isComposing && typeof ev.key === 'string' && ev.key.length === 1) {
            lastComposedText = '';
        }
    }, true);

    // Patch key extraction to prefer IME composition text for Korean input.
    getPressedChar = function(e) {
        var composed = '';
        var key = (e && typeof e.key === 'string') ? e.key : '';
        var code = (e && typeof e.code === 'string') ? e.code : '';
        var isShift = !!(e && e.shiftKey);
        var now = Date.now();
        var expected = (typeof currentChar === 'string') ? currentChar : '';
        var expectsSyllable = isHangulSyllable(expected);

        if (!code && lastPhysicalCode && (now - lastPhysicalTime) < 250) {
            code = lastPhysicalCode;
            isShift = isShift || lastPhysicalShift;
        }

        composed = getComposedFromEvent(e, true);

        // For syllable targets, only accept finalized syllable characters.
        if (expectsSyllable) {
            if (composed) {
                var finalsyl = extractLastHangulSyllable(composed);
                if (finalsyl) {
                    return finalsyl;
                }
            }

            if (key && isHangulSyllable(key)) {
                return key;
            }

            // Ignore partial jamo events while composing syllables.
            return '';
        }

        // Some Korean IME/browser paths provide the final Jamo directly via event.key.
        if (key && key.match(/[ㄱ-ㅎㅏ-ㅣㅃㅉㄸㄲㅆㅒㅖ]/)) {
            return key;
        }

        if (composed) {
            // If IME returns multiple characters, consume first and keep the rest for next key cycle.
            if (composed.length > 1) {
                lastComposedText = composed.substring(1);
                return composed.charAt(0);
            }
            return composed;
        }

        // Stable fallback across IME/browser differences: resolve by physical key code.
        if (code && Object.prototype.hasOwnProperty.call(codeToChar, code)) {
            return codeToChar[code][isShift ? 1 : 0];
        }

        // Fallback: map physical Korean 2-set keys to Jamo when IME composition text is not available.
        if (key && Object.prototype.hasOwnProperty.call(keyToJamo, key)) {
            return keyToJamo[key];
        }

        // On Korean keyboards this key can emit won-sign variants instead of backslash.
        if (key === '₩' || key === '￦' || key === '\\' || key === '|') {
            return '\\';
        }

        return originalGetPressedChar(e);
    };

    // Prevent duplicate processing when composition event is immediately followed by keypress.
    if (originalKeyPressed) {
        $(document).on('input', '#tb1', function(e) {
            var expected = (typeof currentChar === 'string') ? currentChar : '';
            if (!isHangulSyllable(expected)) {
                return true;
            }

            var typed = getTypingFieldValue(e);
            var finalsyl = extractLastHangulSyllable(typed);
            if (!finalsyl) {
                return true;
            }

            if (isDuplicateCommit(finalsyl, currentPos)) {
                return false;
            }

            // Normalize IME commit handling by routing committed syllable through keyPressed.
            lastComposedText = finalsyl;
            var synthetic = {
                type: 'input',
                data: finalsyl,
                key: finalsyl,
                target: this
            };

            var handled = keyPressed(synthetic);
            if (handled !== false) {
                markCommit(finalsyl, currentPos - 1);
            }
            return true;
        });

        $(document).on('compositionend', '#tb1', function(e) {
            var expected = (typeof currentChar === 'string') ? currentChar : '';
            if (isHangulSyllable(expected)) {
                var composed = getComposedFromEvent(e, false);
                var finalsyl = extractLastHangulSyllable(composed);
                if (!finalsyl && e && typeof e.data === 'string' && isHangulSyllable(e.data)) {
                    finalsyl = e.data;
                }
                if (!finalsyl) {
                    finalsyl = extractLastHangulSyllable(getTypingFieldValue(e));
                }
                // Keep compositionend passive; input event path handles scoring.
                if (finalsyl) {
                    lastComposedText = finalsyl;
                }
                $('#tb1').focus();
                return true;
            }
            return true;
        });

        $(document).on('keydown', '#tb1', function(e) {
            if (e && e.key === 'Enter') {
                var handled = keyPressed(e);
                if (handled !== false) {
                    e.preventDefault();
                }
                $('#tb1').focus();
                return false;
            }
            return true;
        });

        keyPressed = function(e) {
            var expected = (typeof currentChar === 'string') ? currentChar : '';

            if (e && e.type === 'keypress' && skipNextKeypress) {
                var keypressed = (e && typeof e.key === 'string') ? e.key : '';
                // Only suppress duplicate IME keypresses for Hangul/jamo-like follow-up events.
                if (isHangulSyllable(expected) || /[ㄱ-ㅎㅏ-ㅣ]/.test(keypressed)) {
                    skipNextKeypress = false;
                    return false;
                }
                skipNextKeypress = false;
            }

            if (isHangulSyllable(expected)) {
                // For syllable targets, score only final commit events.
                if (!e || e.type !== 'input') {
                    return false;
                }

                var composed = getComposedFromEvent(e, false);
                var finalsyl = extractLastHangulSyllable(composed);
                var key = (e && typeof e.key === 'string') ? e.key : '';
                var finalcompose = (e && e.type === 'compositionend' && typeof e.data === 'string' && isHangulSyllable(e.data));

                // For syllable targets, wait for final composed syllable events.
                if (!finalcompose && !finalsyl && !isHangulSyllable(key)) {
                    if (composed) {
                        setInputViewer(composed);
                    }
                    return false;
                }
            } else if (e && e.type === 'compositionend') {
                // Never score compositionend events when expected target is not a syllable.
                return false;
            }

            if (e && e.type === 'keypress' && suppressNextKeypress) {
                var composedPeek = getComposedFromEvent(e, false);
                var keyPeek = (e && typeof e.key === 'string') ? e.key : '';
                var isImeDuplicate =
                    isHangulSyllable(composedPeek) ||
                    isHangulSyllable(keyPeek) ||
                    /[ㄱ-ㅎㅏ-ㅣ]/.test(keyPeek) ||
                    isHangulSyllable(lastComposedText);

                // Suppress only duplicate IME follow-up events, never normal next keys like space.
                suppressNextKeypress = false;
                if (isImeDuplicate) {
                    return false;
                }
            }

            var result = originalKeyPressed(e);
            setInputViewer('');

            return result;
        };
    }

    $(document).ready(function() {
        setInputViewer('');
    });
})();
