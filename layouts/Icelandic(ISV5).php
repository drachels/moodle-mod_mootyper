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
 * This file defines the Icelandic(ISV5.0)keyboard layout.
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
<div id="keyboard" class="keyboardback">Icelandic(ISV5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey°" class="normal" style='text-align:left; color:green'><b>¨<br>
                <span style="color:#F0B400">°</span></b></div>
            <div id="jkey1" class="normal" style='text-align:left;'><b>!<br>1</b></div>
            <div id="jkey2" class="normal" style='text-align:left;'><b>"<br>2</b></div>
            <div id="jkey3" class="normal" style='text-align:left;'><b>#<br>3</b></div>
            <div id="jkey4" class="normal" style='text-align:left;'><b>$<br>4</b></div>
            <div id="jkey5" class="normal" style='text-align:left;'><b>%<br>5
                <span style="color:blue">&nbsp;&nbsp;€</b></span></div>
            <div id="jkey6" class="normal" style='text-align:left;'><b>&amp;<br>6</b></div>
            <div id="jkey7" class="normal" style='text-align:left;'><b>/<br>7
                <span style="color:blue">&nbsp;&nbsp;{</b></span></div>
            <div id="jkey8" class="normal" style='text-align:left;'><b>(<br>8
                <span style="color:blue">&nbsp;&nbsp;[</b></span></div>
            <div id="jkey9" class="normal" style='text-align:left;'><b>)<br>9
                <span style="color:blue">&nbsp;&nbsp;]</b></span></div>
            <div id="jkey0" class="normal" style='text-align:left;'><b>=<br>0
                <span style="color:blue">&nbsp;&nbsp;}</b></span></div>
            <div id="jkeyö" class="normal" style='text-align:left;'><b>Ö<br>
                <span style="color:blue">&nbsp;&nbsp;&nbsp;&nbsp;\</b></span></div>
            <div id="jkeyminus" class="normal" style='text-align:left;'><b>_<br>-</b></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div style="float: left;">
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
                <div id="jkeyq" class="normal" style='text-align:left;'>Q<br>&nbsp;</div>
                <div id="jkeyw" class="normal" style='text-align:left;'>W<br>&nbsp;</div>
                <div id="jkeye" class="normal" style='text-align:left;'>E
                    <span style="color:red">&nbsp;&nbsp;&nbsp;É</span>
                    <br><span style="color:green">Ë</span>&nbsp;&nbsp;&nbsp;
                    <span style="color:blue">€</span></div>
                <div id="jkeyr" class="normal" style='text-align:left;'>R<br>&nbsp;</div>
                <div id="jkeyt" class="normal" style='text-align:left;'>T<br>&nbsp;</div>
                <div id="jkeyy" class="normal" style='text-align:left;'>Y
                    <span style="color:red">&nbsp;&nbsp;&nbsp;Ý</span>
                    <br><span style="color:green">ÿ&nbsp;</span></div>
                <div id="jkeyu" class="normal" style='text-align:left;'>U
                    <span style="color:red">&nbsp;Ú</span>
                    <br><span style="color:green">Ü&nbsp;</span></div>
                <div id="jkeyi" class="normal" style='text-align:left;'>I
                    <span style="color:red">&nbsp;&nbsp;&nbsp;&nbsp;Í</span>
                    <br><span style="color:green">Ï&nbsp;</span></div>
                <div id="jkeyo" class="normal" style='text-align:left;'>O
                    <span style="color:red">&nbsp;&nbsp;Ó</span>
                    <br><span style="color:green">Ö&nbsp;</span></div>
                <div id="jkeyp" class="normal" style='text-align:left;'>P<br>&nbsp;</div>
                <div id="jkeyð" class="normal" style='text-align:left;'>Ð<br>&nbsp;</div>
                <div id="jkeyapos" class="normal" style='text-align:left;'>?
                    <br>'<span style="color:blue">&nbsp;&nbsp;&nbsp;~</span></div>
            </div>
            <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
                <div id="jkeya" class="finger4" style='text-align:left;'>A
                    <span style="color:red">&nbsp;&nbsp;Á</span>
                    <br><span style="color:green">Ä</span>&nbsp;&nbsp;
                    <b><span style="color:#F0B400">Å</span></b></div>
                <div id="jkeys" class="finger3" style='text-align:left;'>S<br>&nbsp;</div>
                <div id="jkeyd" class="finger2" style='text-align:left;'>D<br>&nbsp;</div>
                <div id="jkeyf" class="finger1" style='text-align:left;'>F<br>&nbsp;</div>
                <div id="jkeyg" class="normal" style='text-align:left;'>G<br>&nbsp;</div>
                <div id="jkeyh" class="normal" style='text-align:left;'>H<br>&nbsp;</div>
                <div id="jkeyj" class="finger1" style='text-align:left;'>J<br>&nbsp;</div>
                <div id="jkeyk" class="finger2" style='text-align:left;'>K<br>&nbsp;</div>
                <div id="jkeyl" class="finger3" style='text-align:left;'>L<br>&nbsp;</div>
                <div id="jkeyæ" class="finger4" style='text-align:left;'>Æ<br>&nbsp;</div>
                <div id="jkey´" class="normal" style='text-align:left;'><b>´<br></b>
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;&nbsp;<b>^</b></span></div>
                <div id="jkey+" class="normal" style='text-align:left;'>*<br>+
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;<b>`</b></span></div>

            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 60px;">Shift</div>
            <div id="jkey<" class="normal" style='text-align:left;'>&lt;<br>&gt;
                <span style="color:blue">&nbsp;&nbsp;&nbsp;|</span></div>
            <div id="jkeyz" class="normal" style='text-align:left;'>Z<br>&nbsp;</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X<br>&nbsp;</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C<br>&nbsp;</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V<br>&nbsp;</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B<br>&nbsp;</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N<br>&nbsp;</div>
            <div id="jkeym" class="normal" style='text-align:left;'>M<br>&nbsp;
                <span style="color:blue">&nbsp;&nbsp;&nbsp;µ</span></div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>;<br>,</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>:<br>.</div>
            <div id="jkeyþ" class="normal" style='text-align:left;'>Þ<br></div>
            <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 295px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="width: 50px; color: blue">Alt Gr</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Fn</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px;">Ctrl</div>
        </div>
</section>
</div>
</div>
