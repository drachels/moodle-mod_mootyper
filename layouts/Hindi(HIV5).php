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
 * This file defines the Hindi(HIV5.0)keyboard layout.
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
<div id="keyboard" class="keyboardback">Hindi(HIV5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style="text-align:left;">&nbsp;&nbsp;&nbsp;&nbsp;
                <br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey1" class="normal" style="text-align:left;">ऍ
                <br><span style='float: right;'>1</span></div>
            <div id="jkey2" class="normal" style="text-align:left;">ॅ
                <br><span style='float: right;'>2</span></div>
            <div id="jkey3" class="normal" style="text-align:left;">्
                <br><span style='float: right;'>3</span></div>
            <div id="jkey4" class="normal" style="text-align:left;">र्
                <br><span style='float: right;'>4</span></div>
            <div id="jkey5" class="normal" style="text-align:left;">ज्ञ
                <br><span style='float: right;'>5</span></div>
            <div id="jkey6" class="normal" style="text-align:left;">त्र
                <br><span style='float: right;'>6</span></div>
            <div id="jkey7" class="normal" style="text-align:left;">क्ष
                <br><span style='float: right;'>7</span></div>
            <div id="jkey8" class="normal" style="text-align:left;">श्र
                <br><span style='float: right;'>8</span></div>
            <div id="jkey9" class="normal" style="text-align:left;">(
                <br><span style='float: right;'>9</span></div>
            <div id="jkey0" class="normal" style="text-align:left;">)
                <br><span style='float: right;'>0</span></div>
            <div id="jkeyminus" class="normal" style="text-align:left;">ः
                <br><span style='float: right;'>-</span></div>
            <div id="jkeyequals" class="normal" style="text-align:left;">ऋ
                <br><span style='float: right;'>ृ</span></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div style="float: left;">
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
                <div id="jkeyq" class="normal" style='text-align:left;'>औ
                    <br><span style='float: right;'>ौ</span></div>
                <div id="jkeyw" class="normal" style='text-align:left;'>ऐ
                    <br><span style='float: right;'>ै</span></div>
                <div id="jkeye" class="normal" style='text-align:left;'>आ
                    <br><span style='float: right;'>ा</span></div>
                <div id="jkeyr" class="normal" style='text-align:left;'>ई
                    <br><span style='float: right;'>ी</span></div>
                <div id="jkeyt" class="normal" style='text-align:left;'>ऊ
                    <br><span style='float: right;'>ू</span></div>
                <div id="jkeyy" class="normal" style='text-align:left;'>भ
                    <br><span style='float: right;'>ब</span></div>
                <div id="jkeyu" class="normal" style='text-align:left;'>ङ
                    <br><span style='float: right;'>ह</span></div>
                <div id="jkeyi" class="normal" style='text-align:left;'>घ
                    <br><span style='float: right;'>ग</span></div>
                <div id="jkeyo" class="normal" style='text-align:left;'>ध
                    <br><span style='float: right;'>द</span></div>
                <div id="jkeyp" class="normal" style='text-align:left;'>झ
                    <br><span style='float: right;'>ज</span></div>
                <div id="jkeybracketl" class="normal" style='text-align:left;'>ढ
                    <br><span style='float: right;'>ड</span></div>
                <div id="jkeybracketr" class="normal" style='text-align:left;'>ञ
                    <br><span style='float: right;'>़</span></div>
            </div>
            <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>

            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px; font-size: 12px">Caps Lock</div>
                <div id="jkeya" class="finger4" style='text-align:left;'>ओ
                    <br><span style='float: right;'>ो</span></div>
                <div id="jkeys" class="finger3" style='text-align:left;'>ए
                    <br><span style='float: right;'>े</span></div>
                <div id="jkeyd" class="finger2" style='text-align:left;'>अ
                    <br><span style='float: right;'>्</span></div>
                <div id="jkeyf" class="finger1" style='text-align:left;'>इ
                    <br><span style='float: right;'>ि</span></div>
                <div id="jkeyg" class="normal" style='text-align:left;'>उ
                    <br><span style='float: right;'>ु</span></div>
                <div id="jkeyh" class="normal" style='text-align:left;'>फ
                    <br><span style='float: right;'>प</span></div>
                <div id="jkeyj" class="finger1" style='text-align:left;'>ऱ
                    <br><span style='float: right;'>र</span></div>
                <div id="jkeyk" class="finger2" style='text-align:left;'>ख
                    <br><span style='float: right;'>क</span></div>
                <div id="jkeyl" class="finger3" style='text-align:left;'>थ
                    <br><span style='float: right;'>त</span></div>
                <div id="jkeysemicolon" class="finger4" style='text-align:left;'>च
                    <br><span style='float: right;'>च</span></div>
                <div id="jkeyapostrophe" class="normal" style='text-align:left;'>ठ
                    <br><span style='float: right;'>ट</span></div>
                <div id="jkeybackslash" class="normal" style='text-align:left;'>ऑ
                    <br><span style='float: right;'>ॉ</span></div>
            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal" style='text-align:left;'>
                    <br><span style='float: right;'></span></div>
            <div id="jkeyx" class="normal" style='text-align:left;'>ँ
                    <br><span style='float: right;'>ं</span></div>
            <div id="jkeyc" class="normal" style='text-align:left;'>ण
                    <br><span style='float: right;'>म</span></div>
            <div id="jkeyv" class="normal" style='text-align:left;'>न
                    <br><span style='float: right;'>&nbsp;</span></div>
            <div id="jkeyb" class="normal" style='text-align:left;'>व
                    <br><span style='float: right;'>&nbsp;</span></div>
            <div id="jkeyn" class="normal" style='text-align:left;'>ळ
                    <br><span style='float: right;'>ल</span></div>
            <div id="jkeym" class="normal" style='text-align:left;'>श
                    <br><span style='float: right;'>स</span></div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>ष
                    <br><span style='float: right;'>,</span></div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>।
                    <br><span style='float: right;'>.</span></div>
            <div id="jkeyslash" class="normal" style='text-align:left;'>य़
                    <br><span style='float: right;'>य</span></div>
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
