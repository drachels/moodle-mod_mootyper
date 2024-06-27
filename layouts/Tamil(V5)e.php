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
 * This file defines the Tamil(V5) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2022 AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Tamil(V5) Keyboard Layout based on US English file<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style='text-align:center;'>~<br>`</div>
            <div id="jkey1" class="normal" style='text-align:center;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:center;'>@<br>2</div>
            <div id="jkey3" class="normal" style='text-align:center;'>#<br>3</div>
            <div id="jkey4" class="normal" style='text-align:center;'>$<br>4</div>
            <div id="jkey5" class="normal" style='text-align:center;'>%<br>5</div>
            <div id="jkey6" class="normal" style='text-align:center;'>^<br>6</div>
            <div id="jkey7" class="normal" style='text-align:center;'>&amp;<br>7</div>
            <div id="jkey8" class="normal" style='text-align:center;'>*<br>8</div>
            <div id="jkey9" class="normal" style='text-align:center;'>(<br>9</div>
            <div id="jkey0" class="normal" style='text-align:center;'>)<br>0</div>
            <div id="jkeyminus" class="normal" style='text-align:center;'>_<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:center;'>+<br>=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal" style='text-align:center;'>ஸ<br>ஆ</div>
            <div id="jkeyw" class="normal" style='text-align:center;'>ஷ<br>ஈ</div>
            <div id="jkeye" class="normal" style='text-align:center;'>ஜ<br>ஊ</div>
            <div id="jkeyr" class="normal" style='text-align:center;'>ஹ<br>ஐ</div>
            <div id="jkeyt" class="normal" style='text-align:center;'>க்ஷ<br>ஏ</div>
            <div id="jkeyy" class="normal" style='text-align:center;'>ஶ்ரீ<br>ள</div>
            <div id="jkeyu" class="normal" style='text-align:center;'>ஶ<br>ற</div>
            <div id="jkeyi" class="normal" style='text-align:center;'>&nbsp;<br>ன</div>
            <div id="jkeyட" class="normal" style='text-align:center;'>[<br>ட</div>
            <div id="jkeyண" class="normal" style='text-align:center;'>]<br>ண</div>
            <div id="jkeyச" class="normal" style='text-align:center;'>{<br>ச</div>
            <div id="jkeyஞ" class="normal" style='text-align:center;'>}<br>ஞ</div>
            <div id="jkey\" class="normal" style='text-align:center; width: 75px;'>|<br>\</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 85px;">Caps lock</div>
            <div id="jkeya" class="finger4" style='text-align:center;'>௹<br>அ</div>
            <div id="jkeys" class="finger3" style='text-align:center;'>௺<br>இ</div>
            <div id="jkeyd" class="finger2" style='text-align:center;'>௸<br>உ</div>
            <div id="jkeyf" class="finger1" style='text-align:center;'>ஃ<br>்</div>
            <div id="jkeyg" class="normal" style='text-align:center;'>&nbsp;<br>எ</div>
            <div id="jkeyh" class="normal" style='text-align:center;'>&nbsp;<br>க</div>
            <div id="jkeyj" class="finger1" style='text-align:center;'>&nbsp;<br>ப</div>
            <div id="jkeyk" class="finger2" style='text-align:center;'>&nbsp;<br>ம</div>
            <div id="jkeyl" class="finger3" style='text-align:center;'>&nbsp;<br>த</span></div>
            <div id="jkeyந" class="finger4" style='text-align:center;'>&nbsp;<br>ந</div>
            <div id="jkeyய" class="normal" style='text-align:center;'>&nbsp;<br>ய</div>
            <div id="jkeyenter" class="normal" style="width: 90px;">Enter</div>
        </div>
    </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 107px;">Shift</div>
            <div id="jkeyy" class="normal" style='text-align:center;'>௳<br>ஔ</div>
            <div id="jkeyx" class="normal" style='text-align:center;'>௴<br>ஓ</div>
            <div id="jkeyc" class="normal" style='text-align:center;'>௵<br>ஒ</div>
            <div id="jkeyv" class="normal" style='text-align:center;'>௶<br>௶</div>
            <div id="jkeyb" class="normal" style='text-align:center;'>௷<br>ங</div>
            <div id="jkeyn" class="normal" style='text-align:center;'>ௐ<br>ல</div>
            <div id="jkeym" class="normal" style='text-align:center;'>/<br>ர</div>
            <div id="jkeycomma" class="normal" style='text-align:center;'>&lt;<br>,</div>
            <div id="jkeyperiod" class="normal" style='text-align:center;'>&gt;<br>.</div>
            <div id="jkeyminus" class="normal" style='text-align:center;'>?<br>ழ</div>
            <div id="jkeyshiftr" class="normal" style="width: 107px; border-right-style: solid;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyctrll" class="normal" style="width: 80px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 55px;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 55px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 235px;">விண்வெளி</div>
            <div id="jkeyaltgr" class="normal" style="width: 55px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 55px;">Win</div>
            <div id="jkeyctrlr" class="normal" style="width: 80px; border-right-style: solid;">Ctrl</div><br>
        </div>
    </section>
</div>
</div>
