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
 * This file defines the Italian(ITV5.1)keyboard layout.
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
<?php // phpcs:ignore
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
    <div id="masterlayoutfinger" class="masterlayoutfinger">
        <section>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 0.9em; !important;'>
                <div id="jkeyxxx" class="finger3" style="width: 120px; font-size: 0.9em;">mignolo</div>
                <div id="jkeyxxx" class="finger2" style="text-align:left; font-size: 0.9em;">anulare</div>
                <div id="jkeyxxx" class="finger4" style="text-align:left; font-size: 0.9em;">medio</div>
                <div id="jkeyxxx" class="finger1" style="width: 160px;">indice</div>
                <div id="jkeyxxx" class="finger4" style="text-align:left; font-size: 0.9em;">medio</div>
                <div id="jkeyxxx" class="finger2" style="text-align:left; font-size: 0.9em;">anulare</div>
                <div id="jkeyxxx" class="finger3" style="width: 180px; font-size: 0.9em;">mignolo</div>
            </div>
        </section>
    </div><br>
    <div id="keyboard" class="keyboardback">Italian(ITV5.2) Keyboard Layout<br>
        <section>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeybackslash" class="finger3" style='text-align:left;'>|<br>\</div>
                <div id="jkey1" class="finger3" style='text-align:left;'>!<br>1</div>
                <div id="jkey2" class="finger3" style='text-align:left;'>"<br>2</div>
                <div id="jkey3" class="finger2" style='text-align:left;'>£<br>3</div>
                <div id="jkey4" class="finger4" style='text-align:left;'>$<br>4</div>
                <div id="jkey5" class="finger1" style='text-align:left;'>%<br>5<span style="color:blue">&nbsp;&nbsp;€</span></div>
                <div id="jkey6" class="finger1" style='text-align:left;'><b>&amp;<br>6</b></div>
                <div id="jkey7" class="finger1" style='text-align:left;'><b>/<br>7</b></div>
                <div id="jkey8" class="finger1" style='text-align:left;'><b>(<br>8</b></div>
                <div id="jkey9" class="finger4" style='text-align:left;'><b>)<br>9</b></div>
                <div id="jkey0" class="finger2" style='text-align:left;'><b>=<br>0</b></div>
                <div id="jkeyapostrophe" class="finger3" style='text-align:left;'>?<br>'</div>
                <div id="jkeyì" class="finger3" style='text-align:left;'>^<br>ì</div>
                <div id="jkeybackspace" class="finger3" style="width: 95px;">&#9003;</div>
            </div>
            <div style="float: left;">
                <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                    <div id="jkeytab" class="finger3" style="width: 60px;">&#8633;</div>
                    <div id="jkeyq" class="finger3" style='text-align:left;'>Q<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyw" class="finger2" style='text-align:left;'>W<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeye" class="finger4" style='text-align:left;'>E<br>&nbsp;&nbsp;&nbsp;
                        <span style="color:blue">&nbsp;&nbsp;€</span></div>
                    <div id="jkeyr" class="finger1" style='text-align:left;'>R<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyt" class="finger1" style='text-align:left;'>T<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyy" class="finger1" style='text-align:left;'>Y<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyu" class="finger1" style='text-align:left;'>U<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyi" class="finger4" style='text-align:left;'>I<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyo" class="finger2" style='text-align:left;'>O<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyp" class="finger3" style='text-align:left;'>P<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyè" class="finger3" style='text-align:left;'>é&nbsp;&nbsp;&nbsp;
                              <span style="color:red">{</span><br>è&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:blue">[</span></div>
                    <div id="jkeyplus" class="finger3" style='text-align:left;'>*&nbsp;&nbsp;&nbsp;&nbsp;
                        <span style="color:red">}</span><br>+&nbsp;&nbsp;&nbsp;&nbsp;<span style="color:blue">]</span></div>
                </div>
                <span id="jkeyenter" class="finger3" style="width: 50px; margin-right:5px; float: right; height: 85px;">Invio</span>
                <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                    <div id="jkeycaps" class="finger3" style="width: 80px;  font-size: 12px !important;">&#8681;</div>
                    <div id="jkeya" class="finger3" style='text-align:left;'>A<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeys" class="finger2" style='text-align:left;'>S<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyd" class="finger4" style='text-align:left;'>D<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyf" class="finger1" style='text-align:left;'>F<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyg" class="finger1" style='text-align:left;'>G<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyh" class="finger1" style='text-align:left;'>H<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyj" class="finger1" style='text-align:left;'>J<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyk" class="finger4" style='text-align:left;'>K<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyl" class="finger2" style='text-align:left;'>L<br>&nbsp;&nbsp;&nbsp;</div>
                    <div id="jkeyò" class="finger3" style='text-align:left;'>ç<br>ò&nbsp;
                        <span style="color:blue">@</span></div>
                    <div id="jkeyà" class="finger3" style='text-align:left;'>°<br>à&nbsp;&nbsp;&nbsp;
                        <span style="color:blue">#</span></div>
                    <div id="jkeyù" class="finger3" style='text-align:left;'>§<br>ù</div>
                </div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeyshiftl" class="finger3" style="width: 60px;">&#8679;</div>
                <div id="jkeylessthan" class="finger3" style='text-align:left;'>&gt;<br>&lt;</div>
                <div id="jkeyz" class="finger2" style='text-align:left;'>Z<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeyx" class="finger4" style='text-align:left;'>X<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeyc" class="finger1" style='text-align:left;'>C<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeyv" class="finger1" style='text-align:left;'>V<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeyb" class="finger1" style='text-align:left;'>B<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeyn" class="finger1" style='text-align:left;'>N<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeym" class="finger4" style='text-align:left;'>M<br>&nbsp;&nbsp;&nbsp;</div>
                <div id="jkeycomma" class="finger2" style='text-align:left;'>;<br>,</div>
                <div id="jkeyperiod" class="finger3" style='text-align:left;'>:<br>.</div>
                <div id="jkeyminus" class="finger3" style='text-align:left;'>_<br>-</div>
                <div id="jkeyshiftr" class="finger3" style="width: 115px;">&#8679;</div>
            </div>
            <div class="mtrow" style='float: left; margin-left:5px;'>
                <div id="jkeyctrll" class="finger3" style="width: 60px;">Ctrl</div>
                <div id="jkeyfn" class="finger3" style="width: 50px;">Fn</div>
                <div id="jkeyalt" class="finger2" style="width: 50px;">Alt</div>
                <div id="jkeyspace" class="fingerSpace" style="width: 290px;">Space</div>
                <div id="jkeyaltgr" class="finger3" style="width: 55px;"><span style="color:blue">Alt Gr</span></div>
                <div id="jkeyfn" class="finger3" style="width: 50px;">Fn</div>
                <div id="jkeyctrlr" class="finger3" style="width: 60px;">Ctrl</div>
            </div>
    </section>
    </div><br>
    <div id="masterlayoutfinger2" class="masterlayoutfinger">
        <div id="jkeyxxx" class="finger3" style="width: 165px;">mignolo</div>
        <div id="jkeyxxx" class="fingerSpace" style="width: 295px;">pollice</div>
        <div id="jkeyxxx" class="finger3" style="width: 165px;">mignolo</div>
    </div>
</div>
