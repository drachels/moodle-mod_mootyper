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
 * This file defines the Korean(KNV7)keyboard layout.
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
?>
">
    <div id="keyboard" class="keyboardback">Korean(KNV7) Keyboard Layout<br>
    <section>
        <div id="inputviewer">Your current input:</div>                                                                             
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style='text-align:left;'><b>~<br>`</b></div>
            <div id="jkey1" class="normal" style='text-align:left;'><b>!<br>1</b></div>
            <div id="jkey2" class="normal" style='text-align:left;'><b>@<br>2</b></div>
            <div id="jkey3" class="normal" style='text-align:left;'><b>#<br>3</b></div>
            <div id="jkey4" class="normal" style='text-align:left;'><b>$<br>4</b></div>
            <div id="jkey5" class="normal" style='text-align:left;'><b>%<br>5</b></div>
            <div id="jkey6" class="normal" style='text-align:left;'><b>^<br>6</b></div>
            <div id="jkey7" class="normal" style='text-align:left;'><b>&<br>7</b></div>
            <div id="jkey8" class="normal" style='text-align:left;'><b>*<br>8</b></div>
            <div id="jkey9" class="normal" style='text-align:left;'><b>(<br>9</b></div>
            <div id="jkey0" class="normal" style='text-align:left;'><b>)<br>0</b></div>
            <div id="jkeyminus" class="normal" style='text-align:left;'><b>_<br>-</b></div>
            <div id="jkeyequals" class="normal" style='text-align:left;'><b>+<br>=</b></div>
            <div id="jkeybackslash" class="normal" style='text-align:left;'><b>|<br>₩</b></div>
            <div id="jkeybackspace" class="normal" style="width: 55px;"><b>←</b></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyㅂ" class="normal" style='text-align:left;'>Q ㅃ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅂ</div>
            <div id="jkeyㅈ" class="normal" style='text-align:left;'>W ㅉ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅈ</div>
            <div id="jkeyㄷ" class="normal" style='text-align:left;'>E&nbsp;&nbsp;ㄸ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㄷ</div>
            <div id="jkeyㄱ" class="normal" style='text-align:left;'>R&nbsp;&nbsp;ㄲ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㄱ</div>
            <div id="jkeyㅅ" class="normal" style='text-align:left;'>T&nbsp;&nbsp;ㅆ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅅ</div>
            <div id="jkeyㅛ" class="normal" style='text-align:left;'>Y<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅛ</div>
            <div id="jkeyㅕ" class="normal" style='text-align:left;'>U<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅕ</div>
            <div id="jkeyㅑ" class="normal" style='text-align:left;'>I<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅑ</div>
            <div id="jkeyㅐ" class="normal" style='text-align:left;'>O&nbsp;&nbsp;ㅒ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅐ</div>
            <div id="jkeyㅔ" class="normal" style='text-align:left;'>P&nbsp;&nbsp;ㅖ<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅔ</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;'><b>{<br>[</b></div>
            <div id="jkeybracketr" class="normal" style='text-align:left;'><b>}<br>]</b></div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeya" class="finger4" style='text-align:left;'>A<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅁ</div>
            <div id="jkeys" class="finger3" style='text-align:left;'>S<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㄴ</div>
            <div id="jkeyd" class="finger2" style='text-align:left;'>D<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅇ</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>F<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㄹ</div>
            <div id="jkeyg" class="normal" style='text-align:left;'>G<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅎ</div>
            <div id="jkeyh" class="normal" style='text-align:left;'>H<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅗ</div>
            <div id="jkeyj" class="finger1" style='text-align:left;'>J<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅓ</div>
            <div id="jkeyk" class="finger2" style='text-align:left;'>K<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅏ</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>L<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅣ</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:left;'><b>:<br>;</b></div>
            <div id="jkeycrtica" class="normal" style='text-align:left;'><b>"<br>'</b></div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal" style='text-align:left;'>Z<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅋ</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅌ</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅊ</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅍ</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅠ</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅜ</div>
            <div id="jkeym" class="normal" style='text-align:left;'>M<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ㅡ</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'><b>&lt;<br>,</b></div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'><b>&gt;<br>.</b></div>
            <div id="jkeyslash" class="normal" style='text-align:left;'><b>?<br>/</b></div>
            <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyxxx" class="normal" style="width: 50px;">xxx</div>
            <div id="jkeyspace" class="normal" style="width: 195px;">Space</div>
            <div id="jkeyyyy" class="normal" style="width: 50px;">yyy</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
    </section>
    </div>
</div>
