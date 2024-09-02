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
 * This file defines the Uygher(V4.1)Dual keyboard layout.
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
<?php // phpcs:ignore
// This upper part of the layout is used when using a RTL language.
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
if ($directionality === 'rtl') {
?>
">
<div id="keyboard" class="keyboardback">Uygher(V4)Dual Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
            <div id="jkey=" class="normal" style='text-align:left;'>+<br>=&nbsp; &nbsp;&nbsp;&nbsp;</div>
            <div id="jkey-" class="normal" style='text-align:left;'>_<br>-&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey0" class="normal" style='text-align:left;'>(<br>0&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey9" class="normal" style='text-align:left;'>)<br>9&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey`" class="normal" style='text-align:left;'>~<br>`&nbsp; &nbsp; &nbsp;</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey[" class="normal" style='text-align:left;'>«<br>[&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey]" class="normal" style='text-align:left;'>»<br>]&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyپ" class="normal" style='text-align:left;'>P<br>&nbsp;پ</div>
            <div id="jkeyو" class="normal" style='text-align:left;'>O<br>&nbsp;و</div>
            <div id="jkeyڭ" class="normal" style='text-align:left;'>I<br>&nbsp;ڭ</div>
            <div id="jkeyۇ" class="normal" style='text-align:left;'>U<br>&nbsp;ۇ</div>
            <div id="jkeyي" class="normal" style='text-align:left;'>Y<br>&nbsp;ي</div>
            <div id="jkeyت" class="normal" style='text-align:left;'>T<br>&nbsp;ت</div>
            <div id="jkeyر" class="normal" style='text-align:left;'>R<br>&nbsp;ر</div>
            <div id="jkeyې" class="normal" style='text-align:left;'>E<br>&nbsp;ې</div>
            <div id="jkeyۋ" class="normal" style='text-align:left;'>W<br>&nbsp;ۋ</div>
            <div id="jkeyچ" class="normal" style='text-align:left;'>Q<br>&nbsp;چ</div>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
            <div id="jkey'" class="normal" style='text-align:left;'>"<br>,&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey؛" class="finger4" style='text-align:left;'>:<br>؛&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>L<br>ل&nbsp;</div>
            <div id="jkeyك" class="finger2" style='text-align:left;'>K<br>ك&nbsp;</div>
            <div id="jkeyق" class="finger1" style='text-align:left;'>J<br>ق&nbsp;</div>
            <div id="jkeyى" class="normal" style='text-align:left;'>H<br>ى&nbsp;</div>
            <div id="jkeyە" class="normal" style='text-align:left;'>G<br>ە&nbsp;</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>F<br>ا&nbsp;</div>
            <div id="jkeyد" class="finger2" style='text-align:left;'>D<br>د&nbsp;</div>
            <div id="jkeyس" class="finger3" style='text-align:left;'>S<br>س&nbsp;</div>
            <div id="jkeyھ" class="finger4" style='text-align:left;'>A<br>ھ&nbsp;</div>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
            <div id="jkeyئ" class="normal" style='text-align:left;'>؟<br>ئ&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey." class="normal" style='text-align:left;'><b>&gt;<br>.&nbsp; &nbsp; &nbsp;</b></div>
            <div id="jkey،" class="normal" style='text-align:left;'><b>&lt;<br>،&nbsp; &nbsp; &nbsp;</b></div>
            <div id="jkeyم" class="normal" style='text-align:left;'>M<br>م&nbsp;</div>
            <div id="jkeyن" class="normal" style='text-align:left;'>N<br>ن&nbsp;</div>
            <div id="jkeyب" class="normal" style='text-align:left;'>B<br>ب&nbsp;</div>
            <div id="jkeyۈ" class="normal" style='text-align:left;'>V<br>ۈ&nbsp;</div>
            <div id="jkeyغ" class="normal" style='text-align:left;'>C<br>غ&nbsp;</div>
            <div id="jkeyش" class="normal" style='text-align:left;'>X<br>ش&nbsp;</div>
            <div id="jkeyز" class="normal" style='text-align:left;'>Z<br>ز&nbsp;</div>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space RTL</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
</div>
    <?php // phpcs:ignore
    // This part of the layout is used when using LTR language.
} else {
?>
">
<div id="keyboard" class="keyboardback">Uygher(V4)Dual Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey`" class="normal" style='text-align:left;'>~<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;`</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3</div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8</div>
            <div id="jkey9" class="normal" style='text-align:left;'>)<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;9</div>
            <div id="jkey0" class="normal" style='text-align:left;'>(<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;0</div>
            <div id="jkey-" class="normal" style='text-align:left;'>_<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-</div>
            <div id="jkey=" class="normal" style='text-align:left;'>+<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyچ" class="normal" style='text-align:left;'>Q<br>&nbsp; &nbsp; &nbsp;چ</div>
            <div id="jkeyۋ" class="normal" style='text-align:left;'>W<br>&nbsp; &nbsp; &nbsp;ۋ</div>
            <div id="jkeyې" class="normal" style='text-align:left;'>E<br>&nbsp; &nbsp; &nbsp;ې</div>
            <div id="jkeyر" class="normal" style='text-align:left;'>R<br>&nbsp; &nbsp; &nbsp;ر</div>
            <div id="jkeyت" class="normal" style='text-align:left;'>T<br>&nbsp;&nbsp; &nbsp;ت</div>
            <div id="jkeyي" class="normal" style='text-align:left;'>Y<br>&nbsp; &nbsp; &nbsp;ي</div>
            <div id="jkeyۇ" class="normal" style='text-align:left;'>U<br>&nbsp; &nbsp; &nbsp;ۇ</div>
            <div id="jkeyڭ" class="normal" style='text-align:left;'>I<br>&nbsp; &nbsp; &nbsp;ڭ</div>
            <div id="jkeyو" class="normal" style='text-align:left;'>O<br>&nbsp; &nbsp; &nbsp;و</div>
            <div id="jkeyپ" class="normal" style='text-align:left;'>P<br>&nbsp;&nbsp; &nbsp;پ</div>
            <div id="jkey]" class="normal" style='text-align:left;'>»<br>&nbsp; &nbsp; &nbsp;]</div>
            <div id="jkey[" class="normal" style='text-align:left;'>«<br>&nbsp; &nbsp; &nbsp;[</div>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>&nbsp; &nbsp; &nbsp;\</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeyھ" class="finger4" style='text-align:left;'>A<br>&nbsp; &nbsp; &nbsp;ھ</div>
            <div id="jkeyس" class="finger3" style='text-align:left;'>S<br>&nbsp;&nbsp;&nbsp;س</div>
            <div id="jkeyد" class="finger2" style='text-align:left;'>D ژ<br>&nbsp; &nbsp; &nbsp;د</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>F ف<br>&nbsp; &nbsp; &nbsp;ا</div>
            <div id="jkeyە" class="normal" style='text-align:left;'>G گ<br>&nbsp; &nbsp; &nbsp;ە</div>
            <div id="jkeyى" class="normal" style='text-align:left;'>H خ<br>&nbsp; &nbsp; &nbsp;ى</div>
            <div id="jkeyق" class="finger1" style='text-align:left;'>J ج<br>&nbsp; &nbsp; &nbsp;ق</div>
            <div id="jkeyك" class="finger2" style='text-align:left;'>K ۆ<br>&nbsp; &nbsp; &nbsp;ك</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>L لا<br>&nbsp; &nbsp; &nbsp;ل</div>
            <div id="jkey؛" class="finger4" style='text-align:left;'>:<br>&nbsp; &nbsp; &nbsp;؛</div>
            <div id="jkey'" class="normal" style='text-align:left;'>"<br>&nbsp; &nbsp; &nbsp;'</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyز" class="normal" style='text-align:left;'>Z<br>&nbsp;ز</div>
            <div id="jkeyش" class="normal" style='text-align:left;'>X<br>&nbsp;ش</div>
            <div id="jkeyغ" class="normal" style='text-align:left;'>C<br>&nbsp;غ</div>
            <div id="jkeyۈ" class="normal" style='text-align:left;'>V<br>&nbsp;ۈ</div>
            <div id="jkeyب" class="normal" style='text-align:left;'>B<br>&nbsp;ب</div>
            <div id="jkeyن" class="normal" style='text-align:left;'>N<br>&nbsp;ن</div>
            <div id="jkeyم" class="normal" style='text-align:left;'>M<br>&nbsp;م</div>
            <div id="jkey،" class="normal" style='text-align:left;'><b>&gt;<br>&nbsp; &nbsp; &nbsp;،</b></div>
            <div id="jkey." class="normal" style='text-align:left;'><b>&lt;<br>&nbsp; &nbsp; &nbsp;.</b></div>
            <div id="jkeyئ" class="normal" style='text-align:left;'>؟<br>&nbsp; &nbsp; &nbsp;ئ</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space LTR</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
    <?php // phpcs:ignore
}
