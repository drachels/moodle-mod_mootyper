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
 * This file defines the Russian(RUV5.0) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php // phpcs:ignore
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Russian(RUV5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyё" class="normal" style='text-align:left;'>Ё<br>&nbsp;</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>"<br>2</div>
            <div id="jkey3" class="normal" style='text-align:left;'>№<br>3</div>
            <div id="jkey4" class="normal" style='text-align:left;'>;<br>4</div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>
            <div id="jkey6" class="normal" style='text-align:left;'>:<br>6</div>
            <div id="jkey7" class="normal" style='text-align:left;'>?<br>7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>
            <div id="jkey9" class="normal" style='text-align:left;'>(<br>9</div>
            <div id="jkey0" class="normal" style='text-align:left;'>)<br>0</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeyequals" class="normal" style='text-align:left;'>+<br>=</div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>

    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkeyй" class="normal" style='text-align:left;'>Й<br>&nbsp;</div>
            <div id="jkeyц" class="normal" style='text-align:left;'>Ц<br>&nbsp;</div>
            <div id="jkeyу" class="normal" style='text-align:left;'>У<br>&nbsp;</div>
            <div id="jkeyк" class="normal" style='text-align:left;'>К<br>&nbsp;</div>
            <div id="jkeyе" class="normal" style='text-align:left;'>Е<br>&nbsp;</div>
            <div id="jkeyн" class="normal" style='text-align:left;'>Н<br>&nbsp;</div>
            <div id="jkeyг" class="normal" style='text-align:left;'>Г<br>&nbsp;</div>
            <div id="jkeyш" class="normal" style='text-align:left;'>Ш<br>&nbsp;</div>
            <div id="jkeyщ" class="normal" style='text-align:left;'>Щ<br>&nbsp;</div>
            <div id="jkeyз" class="normal" style='text-align:left;'>З<br>&nbsp;</div>
            <div id="jkeyх" class="normal" style='text-align:left;'>Х<br>&nbsp;</div>
            <div id="jkeyъ" class="normal"  style='text-align:left;'>Ъ<br>&nbsp;</div>
        </div>
        <span id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</span>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;">C.Lock</div>
            <div id="jkeyф" class="finger4" style='text-align:left;'>Ф<br>&nbsp;</div>
            <div id="jkeyы" class="finger3" style='text-align:left;'>Ы<br>&nbsp;</div>
            <div id="jkeyв" class="finger2" style='text-align:left;'>В<br>&nbsp;</div>
            <div id="jkeyа" class="finger1" style='text-align:left;'>А<br>&nbsp;</div>
            <div id="jkeyп" class="normal" style='text-align:left;'>П<br>&nbsp;</div>
            <div id="jkeyр" class="normal" style='text-align:left;'>Р<br>&nbsp;</div>
            <div id="jkeyо" class="finger1" style='text-align:left;'>О<br>&nbsp;</div>
            <div id="jkeyл" class="finger2" style='text-align:left;'>Л<br>&nbsp;</div>
            <div id="jkeyд" class="finger3" style='text-align:left;'>Д<br>&nbsp;</div>
            <div id="jkeyж" class="finger4" style='text-align:left;'>Ж<br>&nbsp;</div>
            <div id="jkeyэ" class="normal" style='text-align:left;'>Э<br>&nbsp;</div>
            <div id="jkeybackslash" class="normal" style='text-align:left;'>/<br>\</div>
        </div>
    </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 70px;">Shift</div>
            <div id="jkeyslash" class="normal" style='text-align:left;'>|<br>\</div>
            <div id="jkeyя" class="normal" style='text-align:left;'>Я<br>&nbsp;</div>
            <div id="jkeyч" class="normal" style='text-align:left;'>Ч<br>&nbsp;</div>
            <div id="jkeyс" class="normal" style='text-align:left;'>С<br>&nbsp;</div>
            <div id="jkeyм" class="normal" style='text-align:left;'>М<br>&nbsp;</div>
            <div id="jkeyи" class="normal" style='text-align:left;'>И<br>&nbsp;</div>
            <div id="jkeyт" class="normal" style='text-align:left;'>Т<br>&nbsp;</div>
            <div id="jkeyь" class="normal" style='text-align:left;'>Ь<br>&nbsp;</div>
            <div id="jkeyб" class="normal" style='text-align:left;'>Б<br>&nbsp;</div>
            <div id="jkeyю" class="normal" style='text-align:left;'>Ю<br>&nbsp;</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'>,<br>.</div>
            <div id="jkeyshiftr" class="normal" style="width: 105px;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px;'>
            <div id="jkeyctrll" class="normal" style="width: 50px;">Ctrl</div>
            <div id="jempty" class="normal" style="width: 50px;">Meta</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 260px;">Пробел</div>
            <div id="jkeyaltgr" class="normal" style="width: 55px;">Alt Gr</div>
            <div id="jempty" class="normal" style="width: 50px;">Meta</div>
            <div id="jempty" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 50px; border-right-style: solid;">Ctrl</div>
        </div>
    </section>
</div>
</div>
