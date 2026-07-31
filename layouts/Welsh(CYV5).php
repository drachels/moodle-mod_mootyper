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
 * This file defines the Welsh(CYV5.0)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2016 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php // phpcs:ignore
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Welsh(CYV5) Keyboard Layout<br>
    <section>
         <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style='text-align:right;'><b>¬<br><span style="color:blue">¦</span>
                &nbsp;&nbsp;`</b></div>
            <div id="jkey1" class="normal" style='text-align:right;'><b>!<br>1</b></div>
            <div id="jkey2" class="normal" style='text-align:right;'><b>"<br><span style="color:blue">¨</span>
                &nbsp;2</b></div>
            <div id="jkey3" class="normal" style='text-align:right;'><b>£<br>3</b></div>
            <div id="jkey4" class="normal" style='text-align:right;'><b>$<br><span style="color:blue">€</span>
                &nbsp;4</b></div>
            <div id="jkey5" class="normal" style='text-align:right;'><b>%<br>5</b></div>
            <div id="jkey6" class="normal" style='text-align:right;'><b>^<br><span style="color:blue">^</span>
                &nbsp;6</b></div>
            <div id="jkey7" class="normal" style='text-align:right;'><b>&amp;<br>7</b></div>
            <div id="jkey8" class="normal" style='text-align:right;'><b>*<br>8</b></div>
            <div id="jkey9" class="normal" style='text-align:right;'><b>(<br>9</b></div>
            <div id="jkey0" class="normal" style='text-align:right;'><b>)<br>0</b></div>
            <div id="jkeyminus" class="normal" style='text-align:right;'><b>_<br>-</b></div>
            <div id="jkeyequals" class="normal" style='text-align:right;'><b>+<br>=</b></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div style="float: left;">
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
                <div id="jkeyq" class="normal" style='text-align:right;'><br>Q</div>
                <div id="jkeyw" class="normal"><span style='text-align:right; color:blue'><br>Ẃ</span>&nbsp;&nbsp;W</div>
                <div id="jkeye" class="normal"><span style='text-align:left; color:blue'><br>É</span>&nbsp;&nbsp;E</div>
                <div id="jkeyr" class="normal" style='text-align:right;'><br>R</div>
                <div id="jkeyt" class="normal" style='text-align:right;'><br>T</div>
                <div id="jkeyy" class="normal"><span style='text-align:right; color:blue'><br>Ý</span>&nbsp;&nbsp;Y</div>
                <div id="jkeyu" class="normal"><span style='text-align:right; color:blue'><br>Ú</span>&nbsp;&nbsp;U</div>
                <div id="jkeyi" class="normal"><span style='text-align:right; color:blue'><br>Í</span>&nbsp;&nbsp;I</div>
                <div id="jkeyo" class="normal"><span style='text-align:right; color:blue'><br>Ó</span>&nbsp;&nbsp;O</div>
                <div id="jkeyp" class="normal" style='text-align:right;'><br>P</div>
                <div id="jkeybracketl" class="normal" style='text-align:right;'>{<br>[</div>
                <div id="jkeybracketr" class="normal" style='text-align:right;'>}<br>]</div>
                <div id="jkey#" class="normal" style='width: 75px; text-align:right;'>~<br>#</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
                <div id="jkeya" class="normal"><span style='text-align:right; color:blue'><br>Á</span>&nbsp;&nbsp;A</div>
                <div id="jkeys" class="finger3" style='text-align:right;'><br>S</div>
                <div id="jkeyd" class="finger2" style='text-align:right;'><br>D</div>
                <div id="jkeyf" class="finger1" style='text-align:right;'><br>F</div>
                <div id="jkeyg" class="normal" style='text-align:right;'><br>G</div>
                <div id="jkeyh" class="normal" style='text-align:right;'><br>H</div>
                <div id="jkeyj" class="finger1" style='text-align:right;'><br>J</div>
                <div id="jkeyk" class="finger2" style='text-align:right;'><br>K</div>
                <div id="jkeyl" class="finger3" style='text-align:right;'><br>L</div>
                <div id="jkeysemicolon" class="finger4" style='text-align:right;'><b>:<br>;</b></div>
                <div id="jkeycrtica" class="normal" style='text-align:right;'><b>@<br>'</b></div>
                <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 60px;">Shift</div>
            <div id="jkeybackslash" class="normal" style='text-align:right;'>|<br>\</div>
            <div id="jkeyz" class="normal" style='text-align:right;'><br>Z</div>
            <div id="jkeyx" class="normal" style='text-align:right;'><br>X</div>
            <div id="jkeyc" class="normal"><span style='text-align:right; color:blue'><br>Ç</span>&nbsp;&nbsp;C</div>
            <div id="jkeyv" class="normal" style='text-align:right;'><br>V</div>
            <div id="jkeyb" class="normal" style='text-align:right;'><br>B</div>
            <div id="jkeyn" class="normal" style='text-align:right;'><br>N</div>
            <div id="jkeym" class="normal" style='text-align:right;'><br>M</div>
            <div id="jkeycomma" class="normal" style='text-align:right;'>&lt;<br>,</div>
            <div id="jkeyperiod" class="normal" style='text-align:right;'>&gt;<br>.</div>
            <div id="jkeyslash" class="normal" style='text-align:right;'>?<br>/</div>
            <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
    </section>
</div>
</div>
