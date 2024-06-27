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
 * This file defines the Telugu(V4.1)keyboard layout.
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
<div id="keyboard" class="keyboardback">Telugu(V4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style="text-align:left;">`&nbsp;&nbsp;&nbsp;&nbsp;ఒ
                <br>&nbsp; &nbsp; &nbsp;ొ</div>
            <div id="jkey1" class="normal" style="text-align:left;">1&nbsp;
                <br><span style="color:blue">౧</span>&nbsp;&nbsp;&nbsp;1</div>
            <div id="jkey2" class="normal" style="text-align:left;">2&nbsp;
                <br><span style="color:blue">౨</span>&nbsp;&nbsp;&nbsp;2</div>
            <div id="jkey3" class="normal" style="text-align:left;">3&nbsp;
                <br><span style="color:blue">౩</span>&nbsp;&nbsp;&nbsp;3</div>
            <div id="jkey4" class="normal" style="text-align:left;">4&nbsp;
                <br><span style="color:blue">౪</span>&nbsp;&nbsp;&nbsp;4</div>
            <div id="jkey5" class="normal" style="text-align:left;">5&nbsp;
                <br><span style="color:blue">౫</span>&nbsp;&nbsp;&nbsp;5</div>
            <div id="jkey6" class="normal" style="text-align:left;">6&nbsp;
                <br><span style="color:blue">౬</span>&nbsp;&nbsp;&nbsp;6</div>
            <div id="jkey7" class="normal" style="text-align:left;">7&nbsp;
                <br><span style="color:blue">౭</span>&nbsp;&nbsp;&nbsp;7</div>
            <div id="jkey8" class="normal" style="text-align:left;">8&nbsp;
                <br><span style="color:blue">౮</span>&nbsp;&nbsp;&nbsp;8</div>
            <div id="jkey9" class="normal" style="text-align:left;">9&nbsp;(
                <br><span style="color:blue">౯</span>&nbsp;&nbsp;&nbsp;9</div>
            <div id="jkey0" class="normal" style="text-align:left;">0&nbsp;)
                <br><span style="color:blue">౦</span>&nbsp;&nbsp;&nbsp;0</div>
            <div id="jkeyminus" class="normal" style="text-align:left;">-&nbsp;&nbsp;ః
                <br>&nbsp; &nbsp; &nbsp;-</div>
            <div id="jkeyequals" class="normal" style="font-size: 14px !important; text-align:left;">=&nbsp;ఋ<br>
                <span style="color:blue">ౄ</span><span style="text-align:right;"> ృ</span></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal" style='text-align:right;'>Q&nbsp;&nbsp;&nbsp;ఔ<br>&nbsp;&nbsp;&nbsp;ౌ</div>
            <div id="jkeyw" class="normal" style='text-align:right;'>W&nbsp;&nbsp;&nbsp;ఐ<br>&nbsp;&nbsp;&nbsp;ై</div>
            <div id="jkeye" class="normal" style='text-align:right;'>E&nbsp;&nbsp;&nbsp;ఆ<br>&nbsp;&nbsp;&nbsp;ా</div>
            <div id="jkeyr" class="normal" style='text-align:right;'>R&nbsp;&nbsp;&nbsp;ఈ<br>&nbsp;&nbsp;&nbsp;ీ</div>
            <div id="jkeyt" class="normal" style='text-align:right;'>T&nbsp;&nbsp;&nbsp;ఊ<br>&nbsp;&nbsp;&nbsp;ూ</div>
            <div id="jkeyy" class="normal" style='text-align:right;'>Y&nbsp;&nbsp;&nbsp;భ<br>&nbsp;&nbsp;&nbsp;బ</div>
            <div id="jkeyu" class="normal" style='text-align:right;'>U&nbsp;&nbsp;&nbsp;ఙ<br>&nbsp;&nbsp;&nbsp;హ</div>
            <div id="jkeyi" class="normal" style='text-align:right;'>I&nbsp;&nbsp;&nbsp;ఘ<br>&nbsp;&nbsp;&nbsp;గ</div>
            <div id="jkeyo" class="normal" style='text-align:right;'>O&nbsp;&nbsp;&nbsp;ధ<br>&nbsp;&nbsp;&nbsp;ద</div>
            <div id="jkeyp" class="normal" style='text-align:right;'>P&nbsp;&nbsp;&nbsp;ఝ<br>&nbsp;&nbsp;&nbsp;జ</div>
            <div id="jkeybracketl" class="normal" style='text-align:right;'>[&nbsp;&nbsp;&nbsp;ఢ<br>&nbsp;&nbsp;&nbsp; డ</div>
            <div id="jkeybracketr" class="normal" style='text-align:right;'>]&nbsp;&nbsp;&nbsp;ఞ<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeybackslash" class="normal" style='width: 75px;text-align:left;'>\<br>&nbsp;</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px; font-size: 12px">Caps Lock</div>
            <div id="jkeya" class="finger4" style='text-align:right;'>A&nbsp;&nbsp;&nbsp;ఓ<br>&nbsp;&nbsp;&nbsp;ో</div>
            <div id="jkeys" class="finger3" style='text-align:right;'>S&nbsp;&nbsp;&nbsp;ఏ<br>&nbsp;&nbsp;&nbsp;ే</div>
            <div id="jkeyd" class="finger2" style='text-align:right;'>D&nbsp;&nbsp;&nbsp;అ<br>&nbsp;&nbsp;&nbsp;్</div>
            <div id="jkeyf" class="finger1" style='text-align:right;'>F&nbsp;&nbsp;&nbsp;ఇ<br>&nbsp;&nbsp;&nbsp;ి</div>
            <div id="jkeyg" class="normal" style='text-align:right;'>G&nbsp;&nbsp;&nbsp;ఉ<br>&nbsp;&nbsp;&nbsp;ు</div>
            <div id="jkeyh" class="normal" style='text-align:right;'>H&nbsp;&nbsp;&nbsp;ఫ<br>&nbsp;&nbsp;&nbsp;ప</div>
            <div id="jkeyj" class="finger1" style='text-align:right;'>J&nbsp;&nbsp;&nbsp;ఱ<br>&nbsp;&nbsp;&nbsp;ర</div>
            <div id="jkeyk" class="finger2" style='text-align:right;'>K&nbsp;&nbsp;&nbsp;ఖ<br>&nbsp;&nbsp;&nbsp;క</div>
            <div id="jkeyl" class="finger3" style='text-align:right;'>L&nbsp;&nbsp;&nbsp;థ<br>&nbsp;&nbsp;&nbsp;త</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:right;'>;&nbsp;&nbsp;&nbsp;ఛ<br>&nbsp;&nbsp;&nbsp;చ</div>
            <div id="jkeyapostrophe" class="normal" style='text-align:right;'>'&nbsp;&nbsp;&nbsp;ఠ<br>&nbsp;&nbsp;&nbsp;ట</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal" style='text-align:right;'>Z&nbsp;&nbsp;&nbsp;ఎ<br>&nbsp;&nbsp;&nbsp;&nbsp;ె</div>
            <div id="jkeyx" class="normal" style='text-align:right;'>X&nbsp;&nbsp;&nbsp;ఁ<br>&nbsp;&nbsp;&nbsp;ం</div>
            <div id="jkeyc" class="normal" style='text-align:right;'>C&nbsp;&nbsp;&nbsp;ణ<br>&nbsp;&nbsp;&nbsp;మ</div>
            <div id="jkeyv" class="normal" style='text-align:right;'>V&nbsp;&nbsp;&nbsp;న<br>&nbsp;&nbsp;&nbsp;న</div>
            <div id="jkeyb" class="normal" style='text-align:right;'>B&nbsp;&nbsp;&nbsp;<br>&nbsp;&nbsp;&nbsp;వ</div>
            <div id="jkeyn" class="normal" style='text-align:right;'>N&nbsp;&nbsp;&nbsp;ళ<br>&nbsp;&nbsp;&nbsp;ల</div>
            <div id="jkeym" class="normal" style='text-align:right;'>M&nbsp;&nbsp;&nbsp;శ<br>&nbsp;&nbsp;&nbsp;స</div>
            <div id="jkeycomma" class="normal" style='text-align:right;'>&nbsp;ష<br>&nbsp;&nbsp;&nbsp;,</div>
            <div id="jkeyperiod" class="normal" style='text-align:right;'>&nbsp;<br>&nbsp;&nbsp;&nbsp;.</div>
            <div id="jkeyslash" class="normal" style='text-align:right;'>&nbsp;<br>&nbsp;&nbsp;&nbsp;య</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 290px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 55px; text-align:center;"><span style="color:blue">Alt Gr</span></div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
</div>
