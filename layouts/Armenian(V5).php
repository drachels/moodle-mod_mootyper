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
 * This file defines the Armenian(V5)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">հայերեն(V5) Ստեղնաշարի դասավորությունը<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey՝" class="normal">՜<br>՝</div>
            <div id="jkey1" class="normal" style='text-align:left;'>1<br>է</div>
            <div id="jkey2" class="normal" style='text-align:left;'>2<br>թ</div>
            <div id="jkey3" class="normal" style='text-align:left;'>3<br>փ</div>
            <div id="jkey4" class="normal" style='text-align:left;'>4<br>ձ</div>
            <div id="jkey5" class="normal" style='text-align:left;'>5<br>ջ</div>
            <div id="jkey6" class="normal" style='text-align:left;'>6<br>ւ</div>
            <div id="jkey7" class="normal" style='text-align:left;'>7<br>և</div>
            <div id="jkey8" class="normal" style='text-align:left;'>8<br>ր</div>
            <div id="jkey9" class="normal" style='text-align:left;'>9<br>չ</div>
            <div id="jkey0" class="normal" style='text-align:left;'>0<br>ճ</div>
            <div id="jkey-" class="normal" style='text-align:left;'>—<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>=<br>ժ</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyք" class="normal" style='text-align:left;'>Q<br>ք&nbsp;</div>
            <div id="jkeyո" class="normal" style='text-align:left;'>W<br>ո&nbsp;</div>
            <div id="jkeyե" class="normal" style='text-align:left;'>E<br>ե&nbsp;</div>
            <div id="jkeyռ" class="normal" style='text-align:left;'>R<br>ռ&nbsp;</div>
            <div id="jkeyտ" class="normal" style='text-align:left;'>T<br>տ&nbsp;</div>
            <div id="jkeyը" class="normal" style='text-align:left;'>Y<br>ը&nbsp;</div>
            <div id="jkeyւ" class="normal" style='text-align:left;'>U<br>ւ&nbsp;</div>
            <div id="jkeyի" class="normal" style='text-align:left;'>I<br>ի&nbsp;</div>
            <div id="jkeyօ" class="normal" style='text-align:left;'>O<br>օ&nbsp;</div>
            <div id="jkeyպ" class="normal" style='text-align:left;'>P<br>պ&nbsp;</div>
            <div id="jkeyխ" class="normal" style='text-align:left;'>{<br>խ</div>
            <div id="jkeyծ" class="normal" style='text-align:left;'>}<br>ծ</div>
            <div id="jkeyշ" class="normal" style='width: 75px; text-align:left;'>|<br>շ</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;  font-size: 12px !important;">Caps Lock</div>
            <div id="jkeyա" class="finger4" style='text-align:left;'>A<br>ա&nbsp;</div>
            <div id="jkeyս" class="finger3" style='text-align:left;'>S<br>ս&nbsp;</div>
            <div id="jkeyդ" class="finger2" style='text-align:left;'>D<br>դ&nbsp;</div>
            <div id="jkeyֆ" class="finger1" style='text-align:left;'>F<br>ֆ&nbsp;</div>
            <div id="jkeyգ" class="normal" style='text-align:left;'>G<br>գ&nbsp;</div>
            <div id="jkeyհ" class="normal" style='text-align:left;'>H<br>հ&nbsp;</div>
            <div id="jkeyյ" class="finger1" style='text-align:left;'>J<br>յ&nbsp;</div>
            <div id="jkeyկ" class="finger2" style='text-align:left;'>K<br>կ&nbsp;</div>
            <div id="jkeyլ" class="finger3" style='text-align:left;'>L<br>լ&nbsp;</div>
            <div id="jkeysemicolon" class="finger4" style='text-align:left;'>:<br>;</div>
            <div id="jkey՛" class="normal" style='text-align:left;'>"<br>՛</div>
            <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
            <div id="jkeyզ" class="normal" style='text-align:left;'>Z<br>զ&nbsp;</div>
            <div id="jkeyղ" class="normal" style='text-align:left;'>X<br>ղ&nbsp;</div>
            <div id="jkeyց" class="normal" style='text-align:left;'>C<br>ց&nbsp;</div>
            <div id="jkeyվ" class="normal" style='text-align:left;'>V<br>վ&nbsp;</div>
            <div id="jkeyբ" class="normal" style='text-align:left;'>B<br>բ&nbsp;</div>
            <div id="jkeyն" class="normal" style='text-align:left;'>N<br>ն&nbsp;</div>
            <div id="jkeyմ" class="normal" style='text-align:left;'>M<br>մ&nbsp;</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>&lt;<br>,</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>&gt;<br>․</div>
            <div id="jkeyslash" class="normal" style='text-align:left;'>՞<br>/</div>
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
