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
 * This file defines the Vietnamese(V4.4)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
﻿<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Vietnamese(V4) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeybackquote" class="normal" style='text-align:left;'><b>~<br>`</b></div>
            <div id="jkey1" class="normal" style='text-align:right;'>Ă<span style="color:blue">&nbsp;&nbsp;&nbsp;!
                <br>1</span></div>
            <div id="jkey2" class="normal" style='text-align:right;'>Â<span style="color:blue">&nbsp;&nbsp;@
                <br>2</span></div>
            <div id="jkey3" class="normal" style='text-align:right;'>Ê<span style="color:blue">&nbsp;&nbsp;&nbsp;#
                <br>3</span></div>
            <div id="jkey4" class="normal" style='text-align:right;'>Ô<span style="color:blue">&nbsp;&nbsp;&nbsp;$
                <br>4</span></div>
            <div id="jkey5" class="normal" style='text-align:right;'>̀ <span style="color:blue">&nbsp;&nbsp;&nbsp;%
                <br>5</span></div>
            <div id="jkey6" class="normal" style='text-align:right;'>̉ <span style="color:blue">&nbsp;&nbsp;&nbsp;^
                <br>6</span></div>
            <div id="jkey7" class="normal" style='text-align:right;'>̃ <span style="color:blue">&nbsp;&nbsp;&nbsp;&amp;
                <br>7</span></div>
            <div id="jkey8" class="normal" style='text-align:right;'>́ <span style="color:blue">&nbsp;&nbsp;&nbsp;*
                <br>8</span></div>
            <div id="jkey9" class="normal" style='text-align:right;'>̣ <span style="color:blue">&nbsp;&nbsp;&nbsp;(
                <br>9</span></div>
            <div id="jkey0" class="normal" style='text-align:right;'>Đ<span style="color:blue">&nbsp;&nbsp;&nbsp;)
                <br>0</span></div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+
                <br>₫<span style="color:blue">&nbsp;&nbsp;&nbsp;=</span></div>
            <div id="jkeybackspace" class="normal" style="width: 95px; text-align:left;">Backspace</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px; text-align:left;">Tab</div>
            <div id="jkeyq" class="normal" style='text-align:left;'>Q</div>
            <div id="jkeyw" class="normal" style='text-align:left;'>W</div>
            <div id="jkeye" class="normal" style='text-align:left;'>E</div>
            <div id="jkeyr" class="normal" style='text-align:left;'>R</div>
            <div id="jkeyt" class="normal" style='text-align:left;'>T</div>
            <div id="jkeyy" class="normal" style='text-align:left;'>Y</div>
            <div id="jkeyu" class="normal" style='text-align:left;'>U</div>
            <div id="jkeyi" class="normal" style='text-align:left;'>I</div>
            <div id="jkeyo" class="normal" style='text-align:left;'>O</div>
            <div id="jkeyp" class="normal" style='text-align:left;'>P</div>
            <div id="jkeybracketl" class="normal" style='text-align:right;'>Ư<span style="color:blue">&nbsp;&nbsp;&nbsp;{
                <br>[</span></div>
            <div id="jkeybracketr" class="normal" style='text-align:right;'>Ơ<span style="color:blue">&nbsp;&nbsp;&nbsp;}
                <br>]</span></div>
            <div id="jkeybackslash" class="normal" style="width: 70px; text-align:left;"> |<br> \</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px; text-align:left; font-size: 12px !important;">Caps Lock</div>
            <div id="jkeya" class="finger4" style='text-align:left;'>A</div>
            <div id="jkeys" class="finger3" style='text-align:left;'>S</div>
            <div id="jkeyd" class="finger2" style='text-align:left;'>D</div>
            <div id="jkeyf" class="finger1" style='text-align:left;'>F</div>
            <div id="jkeyg" class="normal" style='text-align:left;'>G</div>
            <div id="jkeyh" class="normal" style='text-align:left;'>H</div>
            <div id="jkeyj" class="finger1" style='text-align:left;'>J</div>
            <div id="jkeyk" class="finger2" style='text-align:left;'>K</div>
            <div id="jkeyl" class="finger3" style='text-align:left;'>L</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:left;'>:<br>;</div>
            <div id="jkeycrtica" class="normal" style='text-align:left;'>"<br>'</div>
            <div id="jkeyenter" class="normal" style="width: 95px; text-align:left;">Enter</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px; text-align:left;">Shift</div>
            <div id="jkeyz" class="normal">Z</div>
            <div id="jkeyx" class="normal">X</div>
            <div id="jkeyc" class="normal">C</div>
            <div id="jkeyv" class="normal">V</div>
            <div id="jkeyb" class="normal">B</div>
            <div id="jkeyn" class="normal">N</div>
            <div id="jkeym" class="normal">M</div>
            <div id="jkeycomma" class="normal">&lt;<br>,</div>
            <div id="jkeyperiod" class="normal">&gt;<br>.</div>
            <div id="jkeyslash" class="normal">?<br>/</div>
            <div id="jkeyshiftd" class="normal" style="width: 115px; text-align:left;">Shift</div>
        </div>

        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 70px; text-align:left;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px; text-align:left;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 50px; text-align:left;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 220px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 55px; text-align:left;"><span style="color:blue">Alt Gr</span></div>
            <div id="jkeyfn" class="normal" style="width: 50px; text-align:left;">Win</div>
            <div id="jkeyfn" class="normal" style="width: 50px; text-align:left;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 70px; text-align:left;">Ctrl</div>
        </div>
</section>
</div>
</div>
