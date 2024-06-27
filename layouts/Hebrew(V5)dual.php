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
 * This file defines the Hebrew(V5.0)dual keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
if ($directionality === 'rtl') {
?>
">
<div id="keyboard" class="keyboardback">עברית (V5) פריסת מקלדת<br>
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
            <div id="jkeysemicolon" class="normal" style='text-align:left;'>~<br>`&nbsp;&nbsp;&nbsp;;</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;'>{<br>[</div>
            <div id="jkeybracketr" class="normal" style='text-align:left;'>}<br>]</div>
            <div id="jkeyP" class="normal" style='text-align:left;'>P<br>&nbsp; &nbsp; &nbsp;פ</div>
            <div id="jkeyO" class="normal" style='text-align:left;'>O<br>&nbsp; &nbsp; &nbsp;ם</div>
            <div id="jkeyI" class="normal" style='text-align:left;'>I<br>&nbsp; &nbsp; &nbsp;ן</div>
            <div id="jkeyU" class="normal" style='text-align:left;'>U<br>&nbsp; &nbsp; &nbsp;ו</div>
            <div id="jkeyY" class="normal" style='text-align:left;'>Y<br>&nbsp; &nbsp; &nbsp;ט</div>
            <div id="jkeyT" class="normal" style='text-align:left;'>T<br>&nbsp; &nbsp; &nbsp;א</div>
            <div id="jkeyR" class="normal" style='text-align:left;'>R<br>&nbsp; &nbsp; &nbsp;ר</div>
            <div id="jkeyE" class="normal" style='text-align:left;'>E<br>&nbsp; &nbsp; &nbsp;ק</div>
            <div id="jkeyW" class="normal" style='text-align:left;'>W<br>&nbsp; &nbsp; &nbsp;'</div>
            <div id="jkeyQ" class="normal" style='text-align:left;'>Q<br>&nbsp; &nbsp; &nbsp;/</div>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>"<br>,</div>
            <div id="jkeycolon" class="finger4" style='text-align:left;'>:<br>&nbsp; &nbsp; &nbsp;ף</div>
            <div id="jkeyL" class="finger3" style='text-align:left;'>L<br>&nbsp; &nbsp; &nbsp;ך</div>
            <div id="jkeyK" class="finger2" style='text-align:left;'>K<br>&nbsp; &nbsp; &nbsp;ל</div>
            <div id="jkeyJ" class="finger1" style='text-align:left;'>J<br>&nbsp; &nbsp; &nbsp;ח</div>
            <div id="jkeyH" class="normal" style='text-align:left;'>H<br>&nbsp; &nbsp; &nbsp;י</div>
            <div id="jkeyG" class="normal" style='text-align:left;'>G<br>&nbsp; &nbsp; &nbsp;ע</div>
            <div id="jkeyF" class="finger1" style='text-align:left;'>F<br>&nbsp; &nbsp; &nbsp;כ</div>
            <div id="jkeyD" class="finger2" style='text-align:left;'>D<br>&nbsp; &nbsp; &nbsp;ג</div>
            <div id="jkeyS" class="finger3" style='text-align:left;'>S<br>&nbsp; &nbsp; &nbsp;ד</div>
            <div id="jkeyA" class="finger4" style='text-align:left;'>A<br>&nbsp; &nbsp; &nbsp;ש</div>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>?<br>.</div>
            <div id="jkeyץ" class="normal" style='text-align:left;'><b>&gt;<br>&nbsp; &nbsp;&nbsp;ץ</b></div>
            <div id="jkeyת" class="normal" style='text-align:left;'><b>&lt;<br>&nbsp; &nbsp;&nbsp;ת</b></div>
            <div id="jkeyM" class="normal" style='text-align:left;'>M<br>&nbsp; &nbsp; &nbsp;צ</div>
            <div id="jkeyN" class="normal" style='text-align:left;'>N<br>&nbsp; &nbsp; &nbsp;מ</div>
            <div id="jkeyB" class="normal" style='text-align:left;'>B<br>&nbsp; &nbsp; &nbsp;נ</div>
            <div id="jkeyV" class="normal" style='text-align:left;'>V<br>&nbsp; &nbsp; &nbsp;ה</div>
            <div id="jkeyC" class="normal" style='text-align:left;'>C<br>&nbsp; &nbsp; &nbsp;ב</div>
            <div id="jkeyX" class="normal" style='text-align:left;'>X<br>&nbsp; &nbsp; &nbsp;ס</div>
            <div id="jkeyZ" class="normal" style='text-align:left;'>Z<br>&nbsp; &nbsp; &nbsp;ז</div>
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
<div id="keyboard" class="keyboardback">עברית (V5) פריסת מקלדת<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeysemicolon" class="normal" style='text-align:left;'>~<br>`&nbsp;&nbsp;&nbsp;;</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3</div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>
            <div id="jkey9" class="normal" style='text-align:left;'>)<br>9</div>
            <div id="jkey0" class="normal" style='text-align:left;'>(<br>0</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyQ" class="normal" style='text-align:left;'>Q<br>&nbsp; &nbsp; &nbsp;/</div>
            <div id="jkeyW" class="normal" style='text-align:left;'>W<br>&nbsp; &nbsp; &nbsp;'</div>
            <div id="jkeyE" class="normal" style='text-align:left;'>E<br>&nbsp; &nbsp; &nbsp;ק</div>
            <div id="jkeyR" class="normal" style='text-align:left;'>R<br>&nbsp; &nbsp; &nbsp;ר</div>
            <div id="jkeyT" class="normal" style='text-align:left;'>T<br>&nbsp; &nbsp; &nbsp;א</div>
            <div id="jkeyY" class="normal" style='text-align:left;'>Y<br>&nbsp; &nbsp; &nbsp;ט</div>
            <div id="jkeyU" class="normal" style='text-align:left;'>U<br>&nbsp; &nbsp; &nbsp;ו</div>
            <div id="jkeyI" class="normal" style='text-align:left;'>I<br>&nbsp; &nbsp; &nbsp;ן</div>
            <div id="jkeyO" class="normal" style='text-align:left;'>O<br>&nbsp; &nbsp; &nbsp;ם</div>
            <div id="jkeyP" class="normal" style='text-align:left;'>P<br>&nbsp; &nbsp; &nbsp;פ</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;'>{<br>[</div>
            <div id="jkeybracketr" class="normal" style='text-align:left;'>}<br>]</div>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeyA" class="finger4" style='text-align:left;'>A<br>&nbsp; &nbsp; &nbsp;ש</div>
            <div id="jkeyS" class="finger3" style='text-align:left;'>S<br>&nbsp; &nbsp; &nbsp;ד</div>
            <div id="jkeyD" class="finger2" style='text-align:left;'>D<br>&nbsp; &nbsp; &nbsp;ג</div>
            <div id="jkeyF" class="finger1" style='text-align:left;'>F<br>&nbsp; &nbsp; &nbsp;כ</div>
            <div id="jkeyG" class="normal" style='text-align:left;'>G<br>&nbsp; &nbsp; &nbsp;ע</div>
            <div id="jkeyH" class="normal" style='text-align:left;'>H<br>&nbsp; &nbsp; &nbsp;י</div>
            <div id="jkeyJ" class="finger1" style='text-align:left;'>J<br>&nbsp; &nbsp; &nbsp;ח</div>
            <div id="jkeyK" class="finger2" style='text-align:left;'>K<br>&nbsp; &nbsp; &nbsp;ל</div>
            <div id="jkeyL" class="finger3" style='text-align:left;'>L<br>&nbsp; &nbsp; &nbsp;ך</div>
            <div id="jkeycolon" class="finger4" style='text-align:left;'>:<br>&nbsp; &nbsp; &nbsp;ף</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>"<br>,</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyZ" class="normal" style='text-align:left;'>Z<br>&nbsp; &nbsp; &nbsp;ז</div>
            <div id="jkeyX" class="normal" style='text-align:left;'>X<br>&nbsp; &nbsp; &nbsp;ס</div>
            <div id="jkeyC" class="normal" style='text-align:left;'>C<br>&nbsp; &nbsp; &nbsp;ב</div>
            <div id="jkeyV" class="normal" style='text-align:left;'>V<br>&nbsp; &nbsp; &nbsp;ה</div>
            <div id="jkeyB" class="normal" style='text-align:left;'>B<br>&nbsp; &nbsp; &nbsp;נ</div>
            <div id="jkeyN" class="normal" style='text-align:left;'>N<br>&nbsp; &nbsp; &nbsp;מ</div>
            <div id="jkeyM" class="normal" style='text-align:left;'>M<br>&nbsp; &nbsp; &nbsp;צ</div>
            <div id="jkeyת" class="normal" style='text-align:left;'><b>&lt;<br>&nbsp; &nbsp;&nbsp;ת</b></div>
            <div id="jkeyץ" class="normal" style='text-align:left;'><b>&gt;<br>&nbsp; &nbsp;&nbsp;ץ</b></div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>?<br>.</div>
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

