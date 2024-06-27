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
 * This file defines the Thai(V4.1)keyboard layout.
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
<div id="keyboard" class="keyboardback">Thai(V4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style="text-align:left;">&nbsp;%<br>&nbsp; &nbsp;&nbsp;&nbsp;_</div>
            <div id="jkey1" class="normal" style="text-align:left;">&nbsp;+<br>&nbsp; &nbsp;&nbsp;&nbsp;ๅ</div>
            <div id="jkey2" class="normal" style="text-align:left;">&nbsp;๑<br>&nbsp; &nbsp;&nbsp;&nbsp;/</div>
            <div id="jkey3" class="normal" style="text-align:left;">&nbsp;๒<br>&nbsp; &nbsp; &nbsp;&nbsp;-</div>
            <div id="jkey4" class="normal" style="text-align:left;">&nbsp;๓<br>&nbsp; &nbsp;&nbsp;&nbsp;ภ</div>
            <div id="jkey5" class="normal" style="text-align:left;">&nbsp;๔<br>&nbsp; &nbsp;&nbsp;&nbsp;ถ</div>
            <div id="jkey6" class="normal" style="text-align:left;">&nbsp;ู<br>&nbsp; &nbsp; &nbsp;&nbsp;ุ</div>
            <div id="jkey7" class="normal" style="text-align:left;">&nbsp;฿<br><b>&nbsp;&nbsp; &nbsp; &nbsp;ึ</b></div>
            <div id="jkey8" class="normal" style="text-align:left;">&nbsp;๕<br>&nbsp; &nbsp;&nbsp;&nbsp;ค</div>
            <div id="jkey9" class="normal" style="text-align:left;">&nbsp;๖<br>&nbsp; &nbsp;&nbsp;&nbsp;ต</div>
            <div id="jkey0" class="normal" style="text-align:left;">&nbsp;๗<br>&nbsp; &nbsp;&nbsp;&nbsp;จ</div>
            <div id="jkeyminus" class="normal" style="text-align:left;">&nbsp;๘<br>&nbsp; &nbsp;&nbsp;&nbsp;ข</div>
            <div id="jkeyequals" class="normal" style="text-align:left;">&nbsp;๙<br>&nbsp; &nbsp;&nbsp;&nbsp;ช</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyq" class="normal" style='text-align:left;'>&nbsp;๐<br>&nbsp; &nbsp; &nbsp;ๆ</div>
            <div id="jkeyw" class="normal" style='text-align:left;'>&nbsp;"<br>&nbsp; &nbsp; &nbsp;ไ</div>
            <div id="jkeye" class="normal" style='text-align:left;'>&nbsp;ฎ<br>&nbsp; &nbsp; &nbsp;ำ</div>
            <div id="jkeyr" class="normal" style='text-align:left;'>&nbsp;ฑ<br>&nbsp; &nbsp; &nbsp;พ</div>
            <div id="jkeyt" class="normal" style='text-align:left;'>&nbsp;ธ<br>&nbsp; &nbsp; &nbsp;ะ</div>
            <div id="jkeyy" class="normal" style='text-align:left;'>&nbsp;&nbsp;ํ<br>&nbsp; &nbsp; &nbsp;ั</div>
            <div id="jkeyu" class="normal" style='text-align:left;'>&nbsp;&nbsp;๊<br>&nbsp; &nbsp; &nbsp;ี</div>
            <div id="jkeyi" class="normal" style='text-align:left;'>&nbsp;ณ<br>&nbsp; &nbsp; &nbsp;ร</div>
            <div id="jkeyo" class="normal" style='text-align:left;'>&nbsp;ฯ<br>&nbsp; &nbsp; &nbsp;น</div>
            <div id="jkeyp" class="normal" style='text-align:left;'>&nbsp;ญ<br>&nbsp; &nbsp; &nbsp;ย</div>
            <div id="jkeybracketl" class="normal" style='text-align:left;'><b>&nbsp;ฐ<br>&nbsp;&nbsp;&nbsp; บ</b></div>
            <div id="jkeybracketr" class="normal" style='text-align:left;'><b>&nbsp;,<br>&nbsp;&nbsp; &nbsp;ล</b></div>
            <div id="jkeybackslash" class="normal" style='width: 75px;text-align:left;'>&nbsp;ฅ<br>&nbsp;&nbsp; &nbsp;ฃ</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px">Caps Lock</div>
            <div id="jkeya" class="finger4" style='text-align:left;'>&nbsp;ฤ<br>&nbsp; &nbsp; &nbsp;ฟ</div>
            <div id="jkeys" class="finger3" style='text-align:left;'>&nbsp;ฆ<br>&nbsp; &nbsp; &nbsp;ห</div>
            <div id="jkeyd" class="finger2" style='text-align:left;'>&nbsp;ฏ<br>&nbsp; &nbsp; &nbsp;ก</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>&nbsp;โ<br>&nbsp; &nbsp; &nbsp;ด</div>
            <div id="jkeyg" class="normal" style='text-align:left;'>&nbsp; ฌ<br>&nbsp; &nbsp; &nbsp;เ</div>
            <div id="jkeyh" class="normal" style='text-align:left;'>&nbsp;็<br>&nbsp; &nbsp; &nbsp;้</div>
            <div id="jkeyj" class="finger1" style='text-align:left;'>&nbsp;๋<br>&nbsp; &nbsp; &nbsp;่</div>
            <div id="jkeyk" class="finger2" style='text-align:left;'>&nbsp;ษ<br>&nbsp; &nbsp; &nbsp;า</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>&nbsp;ศ<br>&nbsp; &nbsp; &nbsp;ส</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:left;'>&nbsp;ซ<br>&nbsp; &nbsp; &nbsp;ว</div>
            <div id="jkeyapostrophe" class="normal" style='text-align:left;'>&nbsp;.<br>&nbsp; &nbsp; &nbsp;ง</div>
            <div id="jkeyenter" class="normal" style="width: 85px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyz" class="normal" style='text-align:left;'>&nbsp;(<br>&nbsp; &nbsp; &nbsp;ผ</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>&nbsp;)<br>&nbsp; &nbsp; &nbsp;ป</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>&nbsp;ฉ<br>&nbsp; &nbsp; &nbsp;แ</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>&nbsp;ฮ<br>&nbsp; &nbsp; &nbsp;อ</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>&nbsp;ฺ<br>&nbsp; &nbsp; &nbsp; &nbsp;ิ</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>&nbsp;์<br>&nbsp; &nbsp; &nbsp; &nbsp;ื</div>
            <div id="jkeym" class="normal" style='text-align:left;'>&nbsp;?<br>&nbsp;&nbsp; &nbsp;ท</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>&nbsp;ฒ<br>&nbsp;&nbsp; &nbsp;ม</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>&nbsp;ฬ<br>&nbsp;&nbsp; &nbsp;ใ</div>
            <div id="jkeyslash" class="normal" style='text-align:left;'>&nbsp;ฦ<br>&nbsp;&nbsp; &nbsp;ฝ</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px;">Shift</div>
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
