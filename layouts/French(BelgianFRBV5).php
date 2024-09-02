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
 * This file defines the French(BelgianFRBV5.0) keyboard layout.
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
<div id="keyboard" class="keyboardback">French(BelgianFRBV5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkey²" class="normal" style='text-align:left;'>³<br>²</div>
            <div id="jkey1" class="normal" style='text-align:left;'>1<br>&amp;
                <span style="color:blue">&nbsp;|</span></div>
            <div id="jkey2" class="normal" style='text-align:left;'>2<br>é
                <span style="color:blue"> @</span></div>
            <div id="jkey3" class="normal" style='text-align:left;'>3<br>"
                <span style="color:blue">&nbsp;&nbsp;#</span></div>
            <div id="jkey4" class="normal" style='text-align:left;'>4<br>'</div>
            <div id="jkey5" class="normal" style='text-align:left;'>5<br>(</div>
            <div id="jkey6" class="normal" style='text-align:left;'>6<br>§
                <span style="color:green">&nbsp;&nbsp;^</span></div>
            <div id="jkey7" class="normal" style='text-align:left;'>7<br>è</div>
            <div id="jkey8" class="normal" style='text-align:left;'>8<br>!</div>
            <div id="jkey9" class="normal" style='text-align:left;'>9<br>ç
                <span style="color:blue">&nbsp;&nbsp;{</span></div>
            <div id="jkey0" class="normal" style='text-align:left;'>0<br>à
                <span style="color:blue">&nbsp;&nbsp;}</span></div>
            <div id="jkeyparenr" class="normal" style='text-align:left;'>°<br>)</div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>
            <div id="jkeybackspace" class="normal"  style="width: 92px;">Backspace</div>
        </div>
        <div style="float: left;">
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeytab" class="normal" style="width: 65px;">Tab</div>
                <div id="jkeya" class="finger2" style='text-align:left;'>A<br>&nbsp;</div>
                <div id="jkeyz" class="finger1" style='text-align:left;'>Z<br>&nbsp;</div>
                <div id="jkeye" class="finger4" style='text-align:left;'>E<br>
                    <span style="color:blue">&nbsp;&nbsp;&nbsp;&nbsp;€</span></div>
                <div id="jkeyr" class="finger3" style='text-align:left;'>R<br>&nbsp;</div>
                <div id="jkeyt" class="normal" style='text-align:left;'>T<br>&nbsp;</div>
                <div id="jkeyy" class="normal" style='text-align:left;'>Y<br>&nbsp;</div>
                <div id="jkeyu" class="finger3" style='text-align:left;'>U<br>&nbsp;</div>
                <div id="jkeyi" class="finger4" style='text-align:left;'>I<br>&nbsp;</div>
                <div id="jkeyo" class="finger1" style='text-align:left;'>O<br>&nbsp;</div>
                <div id="jkeyp" class="finger2" style='text-align:left;'>P<br>&nbsp;</div>
                <div id="jkeycaret" class="normal" style="text-align:left;">
                    <span style="color:green">¨<br>^</span><span style="color:blue">&nbsp;&nbsp;[</span></div>
                <div id="jkeydollar" class="normal" style="text-align:left;">*<br>$
                    <span style="color:blue">&nbsp;&nbsp;]</span></div>
            </div>
            <span id="jkeyenter" class="normal" style="width: 52px; margin-right:5px; float: right; height: 85px;">Enter</span>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px;">Caps Lock</div>
                <div id="jkeyq" class="normal" style='text-align:left;'>Q<br>&nbsp;</div>
                <div id="jkeys" class="normal" style='text-align:left;'>S<br>&nbsp;</div>
                <div id="jkeyd" class="normal" style='text-align:left;'>D<br>&nbsp;</div>
                <div id="jkeyf" class="normal" style='text-align:left;'>F<br>&nbsp;</div>
                <div id="jkeyg" class="normal" style='text-align:left;'>G<br>&nbsp;</div>
                <div id="jkeyh" class="normal" style='text-align:left;'>H<br>&nbsp;</div>
                <div id="jkeyj" class="normal" style='text-align:left;'>J<br>&nbsp;</div>
                <div id="jkeyk" class="normal" style='text-align:left;'>K<br>&nbsp;</div>
                <div id="jkeyl" class="normal" style='text-align:left;'>L<br>&nbsp;</div>
                <div id="jkeym" class="normal" style='text-align:left;'>M<br>&nbsp;</div>
                <div id="jkeyù" class="normal" style='text-align:left;'>%<br>ù
                    <span style="color:green">&nbsp;&nbsp;´</span></div>
                <div id="jkeyµ" class="normal" style='text-align:left;'>£<br>µ
                    <span style="color:green">&nbsp;&nbsp;`</span></div>
            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 87px;">Shift</div>
            <div id="jkeyckck" class="normal" style='text-align:left;'>&gt;<br>&lt;
                <span style="color:blue">&nbsp;&nbsp;\</span></div>
            <div id="jkeyw" class="normal" style='text-align:left;'>W<br>&nbsp;</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X<br>&nbsp;</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C<br>&nbsp;</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V<br>&nbsp;</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B<br>&nbsp;</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N<br>&nbsp;</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>?<br>,</div>
            <div id="jkeysemicolon" class="normal" style='text-align:left;'>.<br>;</div>
            <div id="jkeycolon" class="normal" style='text-align:left;'>/<br>:</div>
            <div id="jkeyequal" class="normal" style='text-align:left;'>+<br>=
                <span style="color:green">&nbsp;~</span></div>
            <div id="jkeyshiftr" class="normal" style="width: 87px; border-right-style: solid;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyctrll" class="normal" style="width: 50px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 265px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="color:blue; width: 50px;">Alt Gr</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
            <div id="jempty" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 50px;">Ctrl</div>
        </div>
    </section>
</div>
</div>
