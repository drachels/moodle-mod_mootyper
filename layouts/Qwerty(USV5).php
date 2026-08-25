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
 * This file defines the Qwerty(V5)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php // phpcs:ignore
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>php
">
<div id="keyboard" class="keyboardback">Qwerty(USV5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytildo" class="normal"><em>~</em><br><strong>`</strong></div>
            <div id="jkey1" class="normal"><em>!</em><br><strong>1</strong></div>
            <div id="jkey2" class="normal"><em>@</em><br><strong>2</strong></div>
            <div id="jkey3" class="normal"><em>#</em><br><strong>3</strong></div>
            <div id="jkey4" class="normal"><em>$</em><br><strong>4</strong></div>
            <div id="jkey5" class="normal"><em>%</em><br><strong>5</strong></div>
            <div id="jkey6" class="normal"><em>^</em><br><strong>6</strong></div>
            <div id="jkey7" class="normal"><em>&amp;</em><br><strong>7</strong></div>
            <div id="jkey8" class="normal"><em>*</em><br><strong>8</strong></div>
            <div id="jkey9" class="normal"><em>(</em><br><strong>9</strong></div>
            <div id="jkey0" class="normal"><em>)</em><br><strong>0</strong></div>
            <div id="jkeypomislaj" class="normal"><em>_</em><br><strong>-</strong></div>
            <div id="jkeyequals" class="normal"><em>+</em><br><strong>=</strong></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;"><span class="right"><strong>backspace</strong></span></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;"><span class="left"><strong>tab</strong></span></div>
            <div id="jkeyq" class="normal"><strong>Q</strong></div>
            <div id="jkeyw" class="normal"><strong>W</strong></div>
            <div id="jkeye" class="normal"><strong>E</strong></div>
            <div id="jkeyr" class="normal"><strong>R</strong></div>
            <div id="jkeyt" class="normal"><strong>T</strong></div>
            <div id="jkeyy" class="normal"><strong>Y</strong></div>
            <div id="jkeyu" class="normal"><strong>U</strong></div>
            <div id="jkeyi" class="normal"><strong>I</strong></div>
            <div id="jkeyo" class="normal"><strong>O</strong></div>
            <div id="jkeyp" class="normal"><strong>P</strong></div>
            <div id="jkeyoglokl" class="normal"><em>{</em><br><strong>[</strong></div>
            <div id="jkeyoglzak" class="normal"><em>}</em><br><strong>]</strong></div>
            <div id="jkeybackslash" class="normal" style="width: 75px;"><em>|</em><br><strong>\</strong></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;"><span class="left"><strong>caps lock</strong></span></div>
            <div id="jkeya" class="normal"><strong>A</strong></div>
            <div id="jkeys" class="normal"><strong>S</strong></div>
            <div id="jkeyd" class="normal"><strong>D</strong></div>
            <div id="jkeyf" class="normal"><strong>F</strong></div>
            <div id="jkeyg" class="normal"><strong>G</strong></div>
            <div id="jkeyh" class="normal"><strong>H</strong></div>
            <div id="jkeyj" class="normal"><strong>J</strong></div>
            <div id="jkeyk" class="normal"><strong>K</strong></div>
            <div id="jkeyl" class="normal"><strong>L</strong></div>
            <div id="jkeypodpicje" class="normal"><em>:</em><br><strong>;</strong></div>
            <div id="jkeycrtica" class="normal"><em>"</em><br><strong>'</strong></div>
            <div id="jkeyenter" class="normal" style="width: 95px;"><span class="right"><strong>enter</strong></span></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;"><span class="left"><strong>shift</strong></span></div>
            <div id="jkeyz" class="normal"><strong>Z</strong></div>
            <div id="jkeyx" class="normal"><strong>X</strong></div>
            <div id="jkeyc" class="normal"><strong>C</strong></div>
            <div id="jkeyv" class="normal"><strong>V</strong></div>
            <div id="jkeyb" class="normal"><strong>B</strong></div>
            <div id="jkeyn" class="normal"><strong>N</strong></div>
            <div id="jkeym" class="normal"><strong>M</strong></div>
            <div id="jkeyvejica" class="normal"><em>&lt;</em><br><strong>,</strong></div>
            <div id="jkeypika" class="normal"><em>&gt;</em><br><strong>.</strong></div>
            <div id="jkeyslash" class="normal"><em>?</em><br><strong>/</strong></div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;"><span class="right"><strong>shift</strong></span></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;"><span class="left"><strong>ctrl</strong></span></div>
            <div id="key_left_cmd" class="normal" style="width: 50px;"><span class="left"><strong>cmd</strong></span></div>
            <div id="jkeyalt" class="normal" style="width: 50px;"><span class="left"><strong>alt</strong></span></div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Qwerty</div>
            <div id="jkeyaltgr" class="normal" style="width: 95px;"><span class="right"><strong>alt</strong></span></div>
            <div id="key_right_cmd" class="normal" style="width: 50px;"><span class="right"><strong>cmd</strong></span></div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;"><span class="right"><strong>ctrl</strong></span></div>
        </div>
    </section>
</div>
</div>

