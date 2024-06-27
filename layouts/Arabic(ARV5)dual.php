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
 * This file defines the Arabic(V5.0) (20231029) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar
 * @copyright  2016 AL Rachels
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
// First section is for right-to-left (Arabic). Second section is for left-to-right.
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php

echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
if ($directionality === 'rtl') {
?>
">
    <div id="keyboard" class="keyboardback">Arabic(V5.0) Keyboard Layout<br>
        <section>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
                <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>=</div>
                <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
                <div id="jkey0" class="normal" style='text-align:left;'>(<br>0</div>
                <div id="jkey9" class="normal" style='text-align:left;'>)<br>9</div>
                <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>
                <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7</div>
                <div id="jkey6" class="normal" style='text-align:left;'>^<br>6</div>
                <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>
                <div id="jkey4" class="normal" style='text-align:left;'>$<br>4</div>
                <div id="jkey3" class="normal" style='text-align:left;'>#<br>3</div>
                <div id="jkey2" class="normal" style='text-align:left;'>@<br>2</div>
                <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
                <div id="jkeyr10" class="normal" style='text-align:left;'>ّ<br>ذ&nbsp;</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\</div>
                <div id="jkeyد" class="normal" style='text-align:left;'>&gt;<br>د</div>
                <div id="jkeyج" class="normal" style='text-align:left;'>&lt;<br>ج</div>
                <div id="jkeyP" class="normal" style='text-align:left;'>؛<br>&nbsp;&nbsp;&nbsp;ح</div>
                <div id="jkeyO" class="normal" style='text-align:left;'>×<br>&nbsp;&nbsp;&nbsp;خ</div>
                <div id="jkeyI" class="normal" style='text-align:left;'>÷<br>&nbsp;&nbsp;&nbsp;ه</div>
                <div id="jkeyU" class="normal" style='text-align:left;'>‘<br>&nbsp;&nbsp;&nbsp;ع</div>
                <div id="jkeyY" class="normal" style='text-align:left;'>إ<br>&nbsp;&nbsp;&nbsp;غ</div>
                <div id="jkeyT" class="normal" style='text-align:left;'>لإ<br>&nbsp;&nbsp;&nbsp;ف</div>
                <div id="jkeyR" class="normal" style='text-align:left;'>ٌ <br>&nbsp;&nbsp;&nbsp;ق</div>
                <div id="jkeyE" class="normal" style='text-align:left;'>ُ <br>&nbsp;&nbsp;&nbsp;ث</div>
                <div id="jkeyW" class="normal" style='text-align:left;'>ً <br>&nbsp;&nbsp;&nbsp;ص</div>
                <div id="jkeyQ" class="normal" style='text-align:left;'>َ <br>&nbsp;&nbsp;&nbsp;ض</div>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
                <div id="jkeyط" class="normal" style='text-align:left;'>"<br>ط</div>
                <div id="jkeyك" class="finger4" style='text-align:left;'>:<br>&nbsp;&nbsp;&nbsp;ك</div>
                <div id="jkeyL" class="finger3" style='text-align:left;'>/<br>&nbsp;&nbsp;&nbsp;م</div>
                <div id="jkeyK" class="finger2" style='text-align:left;'>،<br>&nbsp;&nbsp;&nbsp;ن</div>
                <div id="jkeyJ" class="finger1" style='text-align:left;'>ـ<br>&nbsp;&nbsp;&nbsp;ت</div>
                <div id="jkeyH" class="normal" style='text-align:left;'>أ<br>&nbsp;&nbsp;&nbsp;ا</div>
                <div id="jkeyG" class="normal" style='text-align:left;'>لأ<br>&nbsp;&nbsp;&nbsp;ل</div>
                <div id="jkeyF" class="finger1" style='text-align:left;'>[<br>&nbsp;&nbsp;&nbsp;ب</div>
                <div id="jkeyD" class="finger2" style='text-align:left;'>]<br>&nbsp;&nbsp;&nbsp;ي</div>
                <div id="jkeyS" class="finger3" style='text-align:left;'>ٍ<br>&nbsp;&nbsp;&nbsp;س</div>
                <div id="jkeyA" class="finger4" style='text-align:left;'>ِ <br>&nbsp;&nbsp;&nbsp;ش</div>
                <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
                <div id="jkeyظ" class="normal" style='text-align:left;'>؟<br>ظ</div>
                <div id="jkeyز" class="normal" style='text-align:left;'>.<br>&nbsp;&nbsp;&nbsp;ز</div>
                <div id="jkeyو" class="normal" style='text-align:left;'>,<br>&nbsp;&nbsp;&nbsp;و</div>
                <div id="jkeyM" class="normal" style='text-align:left;'>’<br>&nbsp;&nbsp;&nbsp;ة</div>
                <div id="jkeyN" class="normal" style='text-align:left;'>آ<br>&nbsp;&nbsp;&nbsp;ى</div>
                <div id="jkeyB" class="normal" style='text-align:left;'>لآ<br>&nbsp;&nbsp;&nbsp;لا</div>
                <div id="jkeyV" class="normal" style='text-align:left;'>{<br>&nbsp;&nbsp;&nbsp;ر</div>
                <div id="jkeyC" class="normal" style='text-align:left;'>}<br>&nbsp;&nbsp;&nbsp;ؤ</div>
                <div id="jkeyX" class="normal" style='text-align:left;'>ْ <br>&nbsp;&nbsp;&nbsp;ء</div>
                <div id="jkeyZ" class="normal" style='text-align:left;'>~<br>&nbsp;&nbsp;&nbsp;ئ</div>
                <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px;'>
                <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
                <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
                <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
                <div id="jkeyspace" class="normal" style="width: 295px;">Space</div>
                <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
                <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
                <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            </div>
        </section>
    </div>
</div>
<?php
} else {
?>
">
<div id="keyboard" class="keyboardback">Arabic(V5.0) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyr10" class="normal" style='text-align:left;'>ّ<br>ذ&nbsp;</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3</div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>
            <div id="jkey9" class="normal" style='text-align:left;'>(<br>9</div>
            <div id="jkey0" class="normal" style='text-align:left;'>)<br>0</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyQ" class="normal" style='text-align:left;'>َ<br>ض&nbsp;</div>
            <div id="jkeyW" class="normal" style='text-align:left;'>ً<br>ص&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyE" class="normal" style='text-align:left;'>ُ<br>ث&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyR" class="normal" style='text-align:left;'>ٌ<br>ق&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyT" class="normal" style='text-align:left;'>لإ<br>ف&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyY" class="normal" style='text-align:left;'>إ<br>غ&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyU" class="normal" style='text-align:left;'>‘<br>ع&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyI" class="normal" style='text-align:left;'>÷<br>ه&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyO" class="normal" style='text-align:left;'>×<br>خ&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyP" class="normal" style='text-align:left;'>؛<br>ح&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyج" class="normal" style='text-align:left;'>&lt;<br>ج</div>
            <div id="jkeyد" class="normal" style='text-align:left;'>&gt;<br>د</div>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeyA" class="finger4" style='text-align:left;'>ِ<br>ش&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyS" class="finger3" style='text-align:left;'>ٍ<br>س&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyD" class="finger2" style='text-align:left;'>]<br>ي&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyF" class="finger1" style='text-align:left;'>[<br>ب&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyG" class="normal" style='text-align:left;'>لأ<br>ل&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyH" class="normal" style='text-align:left;'>أ<br>ا&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyJ" class="finger1" style='text-align:left;'>ـ<br>ت&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyK" class="finger2" style='text-align:left;'>،<br>ن&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyL" class="finger3" style='text-align:left;'>/<br>م&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyك" class="finger4" style='text-align:left;'>:<br>ك&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyط" class="normal" style='text-align:left;'>"<br>ط</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyZ" class="normal" style='text-align:left;'>~<br>ئ&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyX" class="normal" style='text-align:left;'>ْْ<br>ء&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyC" class="normal" style='text-align:left;'>}<br>ؤ&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyV" class="normal" style='text-align:left;'>{<br>ر&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyB" class="normal" style='text-align:left;'>لآ<br>لا&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyN" class="normal" style='text-align:left;'>آ<br>ى&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyM" class="normal" style='text-align:left;'>’<br>ة&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyو" class="normal" style='text-align:left;'>,<br>و&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyز" class="normal" style='text-align:left;'>.<br>ز&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyظ" class="normal" style='text-align:left;'>؟<br>ظ</div>
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
<?php
}
