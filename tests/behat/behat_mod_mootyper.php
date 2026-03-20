<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Steps definitions related with the mootyper activity.
 *
 * @package mod_mootyper
 * @category test
 * @copyright 2015 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given as Given,
Behat\Gherkin\Node\TableNode as TableNode;
use Behat\Behat\Context\Step\Then;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * mootyper-related steps definitions.
 *
 * @package    mod_mootyper
 * @category   test
 * @copyright  2015 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_mod_mootyper extends behat_base {

    /**
     * Assert Continue gate behavior directly in the browser:
     * Enter key on Continue must not submit, mouse click must submit.
     *
     * @Then /^mootyper Continue gate should block Enter and allow click$/
     */
    public function mootypercontinuegateshouldblockenterandallowclick() {
        $script = "
            window.__mootypergateresult = 'not_set';
            try {
                var btn = document.getElementById('btnContinue');
                var form = document.getElementById('form1');
                if (!btn) {
                    window.__mootypergateresult = 'missing_btn';
                } else if (!form) {
                    window.__mootypergateresult = 'missing_form';
                } else {
                    window.__mootypersubmitcount = 0;
                    var intercept = function(e) {
                        window.__mootypersubmitcount++;
                        if (e && typeof e.preventDefault === 'function') {
                            e.preventDefault();
                        }
                        return false;
                    };

                    form.addEventListener('submit', intercept, true);

                    window.ended = true;
                    btn.style.visibility = 'visible';
                    btn.disabled = false;
                    btn.focus();

                    ['keydown', 'keypress', 'keyup'].forEach(function(type) {
                        var ev = new KeyboardEvent(type, {
                            bubbles: true,
                            cancelable: true,
                            key: 'Enter',
                            code: 'Enter',
                            keyCode: 13,
                            which: 13
                        });
                        btn.dispatchEvent(ev);
                    });

                    var afterenter = window.__mootypersubmitcount;

                    var clickEvent = new MouseEvent('click', {
                        bubbles: true,
                        cancelable: true,
                        view: window
                    });
                    btn.dispatchEvent(clickEvent);

                    var afterclick = window.__mootypersubmitcount;
                    var clickaccepted = !!window.continueSubmitting;
                    form.removeEventListener('submit', intercept, true);

                    if (afterenter !== 0) {
                        window.__mootypergateresult = 'enter_submitted:' + afterenter;
                    } else if (afterclick < 1 && !clickaccepted) {
                        window.__mootypergateresult = 'click_not_accepted';
                    } else {
                        window.__mootypergateresult = 'ok';
                    }
                }
            } catch (e) {
                window.__mootypergateresult = 'js_error:' + (e && e.message ? e.message : String(e));
            }
        ";

        $this->getSession()->executeScript($script);
        $result = $this->getSession()->evaluateScript('window.__mootypergateresult || "empty_result";');
        if ($result !== 'ok') {
            throw new \Exception('Continue gate assertion failed: ' . $result);
        }
    }

    /**
     * Create a completed grade record for the current MooTyper exercise shown on page.
     *
     * @Given /^I create a completed mootyper grade for the current exercise$/
     */
    public function icreateacompletedmootypergradeforthecurrentexercise() {
        global $DB, $USER;

        $mootyperid = (int)$this->getSession()->evaluateScript(
            "parseInt((document.querySelector('input[name=\"rpSityperId\"]') || {}).value || '0', 10);"
        );
            $pageuserid = (int)$this->getSession()->evaluateScript(
                "parseInt((document.querySelector('input[name=\"rpUser\"]') || {}).value || '0', 10);"
            );
            if (empty($mootyperid) || empty($pageuserid)) {
                throw new \Exception('Unable to detect MooTyper id or page user id from current page context.');
        }

        $mootyper = $DB->get_record('mootyper', ['id' => $mootyperid], '*', MUST_EXIST);
        $mootyperupdate = new \stdClass();
        $mootyperupdate->id = $mootyper->id;
        $mootyperupdate->isexam = '0';
        $mootyperupdate->lesson = !empty($mootyper->lesson) ? $mootyper->lesson : 1;
        $DB->update_record('mootyper', $mootyperupdate);
        $mootyper = $DB->get_record('mootyper', ['id' => $mootyperid], '*', MUST_EXIST);
        $exercise = $DB->get_record('mootyper_exercises', [
            'lesson' => $mootyper->lesson,
            'snumber' => 1,
        ], '*', MUST_EXIST);
        $exerciseid = (int)$exercise->id;

        if (empty($exerciseid)) {
            throw new \Exception('Unable to resolve lesson exercise id for progression setup.');
        }

        $attempt = (object)[
            'mootyperid' => $mootyperid,
                'userid' => $pageuserid,
            'timetaken' => time() - 5,
            'inprogress' => 0,
            'suspicion' => 0,
        ];
        $attemptid = $DB->insert_record('mootyper_attempts', $attempt, true);

        $grade = (object)[
            'mootyper' => $mootyperid,
                'userid' => $pageuserid,
            'grade' => 100,
            'mistakes' => 0,
            'timeinseconds' => 1,
            'hitsperminute' => 60,
            'fullhits' => 1,
            'precisionfield' => 100,
            'timetaken' => time(),
            'exercise' => $exerciseid,
            'pass' => 1,
            'attemptid' => $attemptid,
            'wpm' => 12,
            'mistakedetails' => get_string('nomistakes', 'mootyper'),
        ];

        $DB->insert_record('mootyper_grades', $grade, false);
    }

    /**
     * Assert current page points to a specific exercise sequence number.
     *
     * @Then /^the current mootyper exercise snumber should be (\d+)$/
     * @param int $expected
     */
    public function thecurrentmootyperexercisesnumbershouldbe($expected) {
        global $DB;

        $exerciseid = (int)$this->getSession()->evaluateScript(
            "parseInt((document.querySelector('input[name=\"rpExercise\"]') || {}).value || '0', 10);"
        );

        if (empty($exerciseid)) {
            throw new \Exception('No current rpExercise id found on page.');
        }

        $exercise = $DB->get_record('mootyper_exercises', ['id' => $exerciseid], 'id,snumber', MUST_EXIST);
        if ((int)$exercise->snumber !== (int)$expected) {
            throw new \Exception('Expected exercise snumber ' . (int)$expected . ', got ' . (int)$exercise->snumber . '.');
        }
    }

    /**
     * Assert that the MooTyper Continue button is visible and enabled.
     *
     * @Then /^mootyper Continue button should be visible$/
     */
    public function mootypercontinuebuttonshouldbevisible() {
        $script = "
            (function() {
                var btn = document.getElementById('btnContinue');
                if (!btn) {
                    return 'missing';
                }
                var style = window.getComputedStyle(btn);
                var visible = style && style.visibility !== 'hidden' && style.display !== 'none';
                if (!visible) {
                    return 'hidden';
                }
                if (btn.disabled) {
                    return 'disabled';
                }
                return 'ok';
            })();
        ";
        $result = $this->getSession()->evaluateScript($script);
        if ($result !== 'ok') {
            throw new \Exception('Continue button is not ready: ' . $result);
        }
    }

    /**
     * Simulate Enter key activation on the MooTyper Continue button.
     *
     * @When /^I activate mootyper Continue with keyboard Enter$/
     */
    public function iactivatemootypercontinuewithkeyboardenter() {
        $script = "
            (function() {
                var btn = document.getElementById('btnContinue');
                if (!btn) {
                    return 'missing';
                }
                btn.focus();
                ['keydown', 'keypress', 'keyup'].forEach(function(type) {
                    var ev = new KeyboardEvent(type, {
                        bubbles: true,
                        cancelable: true,
                        key: 'Enter',
                        code: 'Enter',
                        keyCode: 13,
                        which: 13
                    });
                    btn.dispatchEvent(ev);
                });
                return 'ok';
            })();
        ";
        $result = $this->getSession()->evaluateScript($script);
        if ($result !== 'ok') {
            throw new \Exception('Failed to activate Continue via keyboard: ' . $result);
        }
    }

    /**
     * To assert a select value.
     * @Then /^"([^"]*)" from "([^"]*)" is selected$/
     *
     * Shamelessly inspired by: https://stackoverflow.com/a/33223002/1038565
     * @param string $optionvalue
     * @param string $select
     */
    public function theoptionfromselectisselected($optionvalue, $select) {
        $selectfield = $this->getSession()->getPage()->findField($select);

        if (null === $selectfield) {
            throw new \Exception(sprintf('The select "%s" was not found in the page %s',
                $select, $this->getSession()->getCurrentUrl()));
        }

        $optionfield = $selectfield->find('xpath', "//option[@selected]");
        if (null === $optionfield) {
            throw new \Exception(sprintf('No option is selected in the %s select in the page %s',
                $select, $this->getSession()->getCurrentUrl()));
        }

        if ($optionfield->getValue() != $optionvalue) {
            throw new \Exception(sprintf('The option "%s" was not selected in the page %s, %s was selected',
                $optionvalue, $this->getSession()->getCurrentUrl(), $optionfield->getValue()));
        }
    }

    /**
     * Assert that a specific keyboard key is currently highlighted as the next key.
     *
     * @Then /^the mootyper key "([^"]*)" should be highlighted as next$/
     * @param string $keyid
     */
    public function themootyperkeyshouldbehighlightedasnext($keyid) {
        $script = "
            (function() {
                var el = document.getElementById('" . addslashes($keyid) . "');
                if (!el) {
                    return 'missing';
                }
                return el.className;
            })();
        ";
        $classname = $this->getSession()->evaluateScript($script);
        if ($classname === 'missing') {
            throw new \Exception('Key element not found: ' . $keyid);
        }
        if (strpos($classname, 'next') !== 0) {
            throw new \Exception('Expected key ' . $keyid . ' to be highlighted as next, current class is: ' . $classname);
        }
    }

    /**
     * Type text into MooTyper using keyboard events.
     * Hangul syllables (AC00-D7A3) are entered via composition events.
     * Other characters are entered via keypress events.
     *
     * @When /^I type "([^"]*)" in mootyper using simulated input$/
     * @param string $text
     */
    public function itypeinmootyperusingsimulatedinput($text) {
        $jsontext = json_encode($text, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $script = "
            (function(text) {
                try {
                    var input = document.getElementById('tb1');
                    if (!input) {
                        return 'missing_tb1';
                    }

                    function dispatchComposition(type, data) {
                        var ev;
                        try {
                            ev = new CompositionEvent(type, {bubbles: true, cancelable: true, data: data});
                        } catch (e) {
                            ev = new Event(type, {bubbles: true, cancelable: true});
                            try {
                                Object.defineProperty(ev, 'data', {value: data});
                            } catch (e2) {
                                ev.data = data;
                            }
                        }
                        input.dispatchEvent(ev);
                    }

                    function dispatchViaKeyPressed(ch) {
                        if (typeof keyPressed !== 'function') {
                            return false;
                        }
                        var code = (ch === '\\n') ? 13 : ch.charCodeAt(0);
                        var key = (ch === '\\n') ? 'Enter' : ch;
                        var ev = {
                            type: 'keypress',
                            key: key,
                            which: code,
                            keyCode: code,
                            preventDefault: function() {},
                            stopImmediatePropagation: function() {}
                        };
                        // getPressedChar in typer.js references global event in some paths.
                        window.event = ev;
                        keyPressed(ev);
                        return true;
                    }

                    function dispatchKeypress(ch) {
                        var code = (ch === '\\n') ? 13 : ch.charCodeAt(0);
                        var key = (ch === '\\n') ? 'Enter' : ch;
                        var ev = new KeyboardEvent('keypress', {
                            bubbles: true,
                            cancelable: true,
                            key: key,
                            charCode: code,
                            keyCode: code,
                            which: code
                        });
                        input.dispatchEvent(ev);
                    }

                    input.focus();
                    var chars = Array.from(text);
                    var syllable = /[\\uAC00-\\uD7A3]/;

                    chars.forEach(function(ch) {
                        if (syllable.test(ch)) {
                            dispatchComposition('compositionstart', '');
                            dispatchComposition('compositionupdate', ch);
                            dispatchComposition('compositionend', ch);
                        } else if (!dispatchViaKeyPressed(ch)) {
                            dispatchKeypress(ch);
                        }
                    });

                    return 'ok';
                } catch (e) {
                    return 'js_error:' + (e && e.message ? e.message : String(e));
                }
            })(" . $jsontext . ");
        ";

        $result = $this->getSession()->evaluateScript($script);
        if ($result !== 'ok') {
            throw new \Exception('Failed to simulate MooTyper input: ' . $result);
        }
    }

    /**
     * Assert that MooTyper progress is complete (x/x).
     *
     * @Then /^mootyper progress should be complete$/
     */
    public function mootyperprogressshouldbecomplete() {
        $script = "
            (function() {
                var progress = document.getElementById('jsProgress2');
                if (!progress) {
                    return 'missing';
                }
                var txt = progress.textContent.trim();
                var parts = txt.split('/');
                if (parts.length !== 2) {
                    return 'bad:' + txt;
                }
                return (parts[0] === parts[1]) ? 'complete' : txt;
            })();
        ";
        $result = $this->getSession()->evaluateScript($script);
        if ($result !== 'complete') {
            throw new \Exception('MooTyper progress is not complete: ' . $result);
        }
    }
}
