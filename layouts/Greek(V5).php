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
 * This file defines the Greek(V5.1) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2020 onwards AL Rachels (drachels@drachels.com)
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
<div id="keyboard" class="keyboardback">Greek (V5) Keyboard Layout<br>
    <section>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytilde" class="normal" style="text-align:left;">~<br>`</div>
            <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>
            <div id="jkey2" class="normal" style='text-align:left;'>@<br>2
                <span style="color:blue">&nbsp;²</span></div>
            <div id="jkey3" class="normal" style='text-align:left;'>#<br>3
                <span style="color:blue">&nbsp;&nbsp;&nbsp;³</span></div>
            <div id="jkey4" class="normal" style='text-align:left;'>$<br>4
                <span style="color:blue">&nbsp;&nbsp;£</span></div>
            <div id="jkey5" class="normal" style='text-align:left;'>%<br>5
                <span style="color:blue">&nbsp;&nbsp;&nbsp;§</span></div>
            <div id="jkey6" class="normal" style='text-align:left;'>^<br>6
                <span style="color:blue">&nbsp;&nbsp;&nbsp;¶</span></div>
            <div id="jkey7" class="normal" style='text-align:left;'>&amp;<br>7</div>
            <div id="jkey8" class="normal" style='text-align:left;'>*<br>8
                <span style="color:blue">&nbsp;&nbsp;&nbsp;¤</span></div>
            <div id="jkey9" class="normal" style='text-align:left;'>(<br>9
                <span style="color:blue">&nbsp;&nbsp;&nbsp;¦</span></div>
            <div id="jkey0" class="normal" style='text-align:left;'>)<br>0
                <span style="color:blue">&nbsp;&nbsp;&nbsp;°</span></div>
            <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-
                <span style="color:blue">&nbsp;&nbsp;&nbsp;±</span></div>
            <div id="jkeyequal" class="normal" style='text-align:left;'>+<br>=
                <span style="color:blue">&nbsp;½</span></div>
            <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
        </div>
    <div style="float: left;">
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
            <div id="jkey;" class="normal" style='text-align:left;'>:<br>;</div>
            <div id="jkeyς" class="normal" style='text-align:left;'>΅<br>ς</div>
            <div id="jkeyε" class="normal" style='text-align:left;'>Ε<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyρ" class="normal" style='text-align:left;'>Ρ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyτ" class="normal" style='text-align:left;'>Τ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyυ" class="normal" style='text-align:left;'>Y<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyθ" class="normal" style='text-align:left;'>Θ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyι" class="normal" style='text-align:left;'>I<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyο" class="normal" style='text-align:left;'>O<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyπ" class="normal" style='text-align:left;'>Π<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkey[" class="normal" style='text-align:left;'>{<br>[&nbsp; &nbsp;&nbsp;«</div>
            <div id="jkey]" class="normal" style='text-align:left;'>}<br>]
                <span style="color:blue">&nbsp;&nbsp;&nbsp;»</span></div>
        </div>
        <div id="jkeyenter" class="normal" style="width: 50px; margin-right:5px; float: right; height: 85px;">Enter</div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeycaps" class="normal" style="width: 80px;">C.lock</div>
            <div id="jkeyα" class="finger4" style='text-align:left;'>A<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyσ" class="finger3" style='text-align:left;'>Σ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyδ" class="finger2" style='text-align:left;'>Δ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyφ" class="finger1" style='text-align:left;'>Φ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyγ" class="normal" style='text-align:left;'>Γ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyη" class="normal" style='text-align:left;'>Η<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyξ" class="finger1" style='text-align:left;'>Ξ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyκ" class="finger2" style='text-align:left;'>Κ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyλ" class="finger3" style='text-align:left;'>Λ<br>&nbsp; &nbsp; &nbsp;</div>
            <div id="jkeyaccent" class="finger4" style='text-align:left; color:red'>¨<br>΄
                <span style="color:blue">&nbsp;&nbsp;&nbsp;΅</span></div>
            <div id="jkeyapostrophe" class="normal" style='text-align:left;'>"<br>'&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeybslash" class="normal" style='text-align:left;'>|<br>\
                <span style="color:blue">&nbsp;&nbsp;¬</span></div>
        </div>
    </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 70px;">Shift</div>
            <div id="jkeyckck" class="normal"  style='text-align:left;'>&gt;<br>&lt;</div>
            <div id="jkeyζ" class="normal" style='text-align:left;'>Z<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyχ" class="normal" style='text-align:left;'>X<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyψ" class="normal" style='text-align:left;'>Ψ<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyω" class="normal" style='text-align:left;'>Ω<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyβ" class="normal" style='text-align:left;'>B<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyν" class="normal" style='text-align:left;'>N<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeyμ" class="normal" style='text-align:left;'>M<br>&nbsp;&nbsp;&nbsp;</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'>&lt;<br>,</div>
            <div id="jkeyperiod" class="normal"  style='text-align:left;'>&gt;<br>.</div>
            <div id="jkeyfslash" class="normal"  style='text-align:left;'>?<br>/</div>
            <div id="jkeyshiftd" class="normal" style="width: 105px; border-right-style: solid;">Shift</div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyctrll" class="normal" style="width: 60px;">Ctrl</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
            <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
            <div id="jkeyspace" class="normal" style="width: 240px;">Space</div>
            <div id="jkeyaltgr" class="normal" style="color:blue; width: 55px;">Alt Gr</div>
            <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
            <div id="jempty" class="normal" style="width: 50px;">Menu</div>
            <div id="jkeyctrlr" class="normal" style="width: 60px; border-right-style: solid;">Ctrl</div><br>
        </div>
    </section>
</div>
</div>
