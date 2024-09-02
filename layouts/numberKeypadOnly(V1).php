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
 * This file defines the numberKeypadOnly(V1) layout.
 *
 * @package    mod_mootyper
 * @copyright  2019 AL Rachels (drachels@drachels.com)
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
<div id="keyboard" class="keypadback">NumberPad Only<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeynumlock" class="normal" style='text-align:left;'>Lock<br>&nbsp;</div>
            <div id="jkey/" class="normal" style='text-align:left;'>/<br>&nbsp;</div>
            <div id="jkey*" class="normal" style='text-align:left;'>*<br>&nbsp;</div>
            <div id="jkey-" class="normal" style='text-align:left;'>-<br>&nbsp;</div>
        </div>
        <div id="jkey+" class="finger4" style="width: 41px; margin-right:5px; float: right; height: 85px;">+</div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey7" class="normal" style='text-align:left;'>7<br>&nbsp;</div>
            <div id="jkey8" class="normal" style='text-align:left;'>8<br>&nbsp;</div>
            <div id="jkey9" class="normal" style='text-align:left;'>9<br>&nbsp;</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey4" class="finger1" style='text-align:left;'>4<br>&nbsp;</div>
            <div id="jkey5" class="finger2" style='text-align:left;'>5<br>&nbsp;</div>
            <div id="jkey6" class="finger3" style='text-align:left;'>6<br>&nbsp;</div>
        </div>
        <div id="jkeyenter" class="normal" style="margin-top:5px; width: 41px; margin-right:5px;
            float: right; height: 85px;">Enter</div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey1" class="normal" style='text-align:left;'>1<br>&nbsp;</div>
            <div id="jkey2" class="normal" style='text-align:left;'>2<br>&nbsp;</div>
            <div id="jkey3" class="normal" style='text-align:left;'>3<br>&nbsp;</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey0" class="normal" style="width: 80px;">0<br>&nbsp;</div>
            <div id="jkey." class="normal" style='text-align:left;'>.<br>&nbsp;</div>
        </div>
</section>
</div>
</div>
