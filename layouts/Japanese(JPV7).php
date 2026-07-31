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
 * This file defines the Japanese(JPV7) keyboard layout.
 *
 * V1 scaffold based on English(USV5) while Japanese IME behavior is handled
 * in the standalone typer(JPV7).js logic.
 *
 * @package    mod_mootyper
 * @copyright  2019 AL Rachels (drachels@drachels.com)
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
<div id="keyboard" class="keyboardback">Japanese(JPV7) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal">~<br>` ろ</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1 ぬ</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2 ふ</div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3 あ</div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4 う</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5 え</div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6 お</div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7 や</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8 ゆ</div>
            <div id="jkey9" class="normal" style='text-align:left;'>(<br>9 よ</div>
            <div id="jkey0" class="normal" style='text-align:left;'>)<br>0 わ</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>- ほ</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>= へ</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal" style='text-align:left;'>Q<br>た</div>
            <div id="jkeyw" class="normal" style='text-align:left;'>W<br>て</div>
            <div id="jkeye" class="normal" style='text-align:left;'>E<br>い</div>
            <div id="jkeyr" class="normal" style='text-align:left;'>R<br>す</div>
            <div id="jkeyt" class="normal" style='text-align:left;'>T<br>か</div>
            <div id="jkeyy" class="normal" style='text-align:left;'>Y<br>ん</div>
            <div id="jkeyu" class="normal" style='text-align:left;'>U<br>な</div>
            <div id="jkeyi" class="normal" style='text-align:left;'>I<br>に</div>
            <div id="jkeyo" class="normal" style='text-align:left;'>O<br>ら</div>
            <div id="jkeyp" class="normal" style='text-align:left;'>P<br>せ</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;'>{<br>[ ゛</div>
            <div id="jkeybracketr" class="normal" style='text-align:left;'>}<br>] ゜</div>
            <div id="jkeybackslash" class="normal" style='width: 75px; text-align:left;'>|<br>\ む</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeya" class="finger4" style='text-align:left;'>A<br>ち</div>
            <div id="jkeys" class="finger3" style='text-align:left;'>S<br>と</div>
            <div id="jkeyd" class="finger2" style='text-align:left;'>D<br>し</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>F<br>は</div>
            <div id="jkeyg" class="normal" style='text-align:left;'>G<br>き</div>
            <div id="jkeyh" class="normal" style='text-align:left;'>H<br>く</div>
            <div id="jkeyj" class="finger1" style='text-align:left;'>J<br>ま</div>
            <div id="jkeyk" class="finger2" style='text-align:left;'>K<br>の</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>L<br>り</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:left;'>:<br>; れ</div>
            <div id="jkeycrtica" class="normal" style='text-align:left;'>"<br>' け</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal" style='text-align:left;'>Z<br>つ</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X<br>さ</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C<br>そ</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V<br>ひ</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B<br>こ</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N<br>み</div>
            <div id="jkeym" class="normal" style='text-align:left;'>M<br>も</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>&lt;<br>, ね</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>&gt;<br>. る</div>
            <div id="jkeyslash" class="normal" style='text-align:left;'>?<br>/ め</div>
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
