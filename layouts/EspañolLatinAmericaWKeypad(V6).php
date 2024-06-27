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
 * This file defines the Spanish Latin America(V6.1) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2024 AL Rachels (drachels@drachels.com)
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
<div id="keyboard" class="keyboardpadback">Español Latin America con teclado(V6) Diseño del teclado<br>
<table>
    <tr>
        <td style="width:630px">
            <section>
                <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                    <div id="jkey|" class="normal" style='text-align:left; float: left;'>°<br>|</div>
                    <div id="jkey1" class="normal" style='text-align:left; float: left;'>!<br>1</div>
                    <div id="jkey2" class="normal" style='text-align:left; float: left;'>"<br>2
                        <span style="color:blue;">&nbsp;@</span></div>
                    <div id="jkey3" class="normal" style='text-align:left; float: left;'>#<br>3</div>
                    <div id="jkey4" class="normal" style='text-align:left; float: left;'>$<br>4</div>
                    <div id="jkey5" class="normal" style='text-align:left; float: left;'>%<br>5</div>
                    <div id="jkey6" class="normal" style='text-align:left; float: left;'>&amp;<br>6</div>
                    <div id="jkey7" class="normal" style='text-align:left; float: left;'>/<br>7</div>
                    <div id="jkey8" class="normal" style='text-align:left; float: left;'>(<br>8</div>
                    <div id="jkey9" class="normal" style='text-align:left; float: left;'>)<br>9</div>
                    <div id="jkey0" class="normal" style='text-align:left; float: left;'>=<br>0</div>
                    <div id="jkeyapostrophe" class="normal" style='text-align:left; float: left;'>?<br>'</div>
                    <div id="jkey¡" class="normal" style='text-align:left; float: left;'>¿<br>¡</div>
                    <div id="jkeybackspace" class="normal" style="width: 90px; float: left;">Retroceso</div>
                </div>
                <div style="float: left;">
                    <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                        <div id="jkeytab" class="normal" style="width: 60px; float: left;">Tab</div>
                        <div id="jkeyq" class="normal" style='text-align:left; float: left;'>Q<br>&nbsp;</div>
                        <div id="jkeyw" class="normal" style='text-align:left; float: left;'>W<br>&nbsp;</div>
                        <div id="jkeye" class="normal" style='text-align:left; float: left;'>E<br>&nbsp;</div>
                        <div id="jkeyr" class="normal" style='text-align:left; float: left;'>R<br>&nbsp;</div>
                        <div id="jkeyt" class="normal" style='text-align:left; float: left;'>T<br>&nbsp;</div>
                        <div id="jkeyy" class="normal" style='text-align:left; float: left;'>Y<br>&nbsp;</div>
                        <div id="jkeyu" class="normal" style='text-align:left; float: left;'>U<br>&nbsp;</div>
                        <div id="jkeyi" class="normal" style='text-align:left; float: left;'>I<br>&nbsp;</div>
                        <div id="jkeyo" class="normal" style='text-align:left; float: left;'>O<br>&nbsp;</div>
                        <div id="jkeyp" class="normal" style='text-align:left; float: left;'>P<br>&nbsp;</div>
                        <div id="jkeylefttick" class="normal" style='color:red; text-align:left; float: left;'>¨<br>´</div>
                        <div id="jkeyplus" class="normal" style='text-align:left; float: left;'>*<br>+</div>
                    </div>
                    <span id="jkeyenter" class="normal" style="width: 50px; float: right; height: 85px;">Entrar</span>
                    <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                        <div id="jkeycaps" class="normal" style="width: 80px;
                            font-size: 12px !important; float: left;">Bloq. mayús</div>
                        <div id="jkeya" class="finger4" style='text-align:left; float: left;'>A<br>&nbsp;</div>
                        <div id="jkeys" class="finger3" style='text-align:left; float: left;'>S<br>&nbsp;</div>
                        <div id="jkeyd" class="finger2" style='text-align:left; float: left;'>D<br>&nbsp;</div>
                        <div id="jkeyf" class="finger1" style='text-align:left; float: left;'>F<br>&nbsp;</div>
                        <div id="jkeyg" class="normal" style='text-align:left; float: left;'>G<br>&nbsp;</div>
                        <div id="jkeyh" class="normal" style='text-align:left; float: left;'>H<br>&nbsp;</div>
                        <div id="jkeyj" class="finger1" style='text-align:left; float: left;'>J<br>&nbsp;</div>
                        <div id="jkeyk" class="finger2" style='text-align:left; float: left;'>K<br>&nbsp;</div>
                        <div id="jkeyl" class="finger3" style='text-align:left; float: left;'>L<br>&nbsp;</div>
                        <div id="jkeyñ" class="finger4" style='text-align:left; float: left;'>Ñ<br>&nbsp;</div>
                        <div id="jkeyrighttick" class="normal" style='text-align:left; float: left;'>[<br>{</div>
                        <div id="jkeyç" class="normal" style='text-align:left; float: left;'>]<br>}</div>
                    </div>
                </div>
                <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                    <div id="jkeyshiftl" class="normal" style="width: 70px; font-size: 12px !important; float: left;">Mayús</div>
                    <div id="jkeyckck" class="normal" style='text-align:left; float: left;'>&gt;<br>&lt;</div>
                    <div id="jkeyz" class="normal" style='text-align:left; float: left;'>Z<br>&nbsp;</div>
                    <div id="jkeyx" class="normal" style='text-align:left; float: left;'>X<br>&nbsp;</div>
                    <div id="jkeyc" class="normal" style='text-align:left; float: left;'>C<br>&nbsp;</div>
                    <div id="jkeyv" class="normal" style='text-align:left; float: left;'>V<br>&nbsp;</div>
                    <div id="jkeyb" class="normal" style='text-align:left; float: left;'>B<br>&nbsp;</div>
                    <div id="jkeyn" class="normal" style='text-align:left; float: left;'>N<br>&nbsp;</div>
                    <div id="jkeym" class="normal" style='text-align:left; float: left;'>M<br>&nbsp;</div>
                    <div id="jkeycomma" class="normal" style='text-align:left; float: left;'>;<br>,</div>
                    <div id="jkeyperiod" class="normal" style='text-align:left; float: left;'>:<br>.</div>
                    <div id="jkeyminus" class="normal" style='text-align:left; float: left;'>_<br>-</div>
                    <div id="jkeyshiftr" class="normal" style="width: 100px; font-size: 12px !important; float: left;">Mayús</div>
                </div>
                <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                    <div id="jkeyctrll" class="normal" style="width: 60px; float: left;">Ctrl</div>
                    <div id="jkeyfn" class="normal" style="width: 50px; float: left;">Fn</div>
                    <div id="jkeyalt" class="normal" style="width: 50px; float: left;">Alt</div>
                    <div id="jkeyspace" class="normal" style="width: 235px; float: left;">Espacio</div>
                    <div id="jkeyaltgr" class="normal" style="color:blue; width: 55px; float: left;">Alt Gr</div>
                    <div id="jkeyfn" class="normal" style="width: 50px; float: left;">Fn</div>
                    <div id="jempty" class="normal" style="width: 50px; float: left;">Menu</div>
                    <div id="jkeyctrlr" class="normal" style="width: 60px; float: left;">Ctrl</div>
                </div>
            </section>
            </td>
            <td style="width:203px;">
                <div class="mtrow" style='line-height: 15px'>
                    <div id="jkeyxxx" class="normalblank" style='width: 30px; text-align:left; float: left;'></div>
                    <div id="jkeynumlockp" class="normal" style='text-align:left; float: left;'>Lock<br>&nbsp;</div>
                    <div id="jkeyslashp" class="normal" style='text-align:left; float: left;'>/<br>&nbsp;</div>
                    <div id="jkey*p" class="normal" style='text-align:left; float: left;'>*<br>&nbsp;</div>
                    <div id="jkeyminusp" class="normal" style='text-align:left; float: left;'>-<br>&nbsp;</div>
                </div>     
                <div class="mtrow" style='line-height: 15px'>
                    <div id="jkeyequalsp" class="finger4" style="width: 41px; float: right; height: 85px;">+</div>
                    <div id="jkeyxxx" class="normalblank" style='width: 30px; text-align:left; float: left;'></div>
                    <div id="jkey7p" class="normal" style='text-align:left; float: left;'>7<br>&nbsp;</div>
                    <div id="jkey8p" class="normal" style='text-align:left; float: left;'>8<br>&nbsp;</div>
                    <div id="jkey9p" class="normal" style='text-align:left; float: left;'>9<br>&nbsp;</div>
                </div> 
                <div class="mtrow" style='line-height: 15px'>
                    <div id="jkeyxxx" class="normalblank" style='width: 30px; text-align:left; float: left;'></div>
                    <div id="jkey4p" class="finger1" style='text-align:left; float: left;'>4<br>&nbsp;</div>
                    <div id="jkey5p" class="finger2" style='text-align:left; float: left;'>5<br>&nbsp;</div>
                    <div id="jkey6p" class="finger3" style='text-align:left; float: left;'>6<br>&nbsp;</div>
                </div> 
                <div class="mtrow" style='line-height: 15px'>
                    <div id="jkeyenterp" class="normal" style="font-size: 11px !important; width: 41px;
                        float: right; height: 85px;">Enter</div>
                    <div id="jkeyxxx" class="normalblank" style='width: 30px; text-align:left; float: left;'></div>
                    <div id="jkey1p" class="normal" style='text-align:left; float: left;'>1<br>&nbsp;</div>
                    <div id="jkey2p" class="normal" style='text-align:left; float: left;'>2<br>&nbsp;</div>
                    <div id="jkey3p" class="normal" style='text-align:left; float: left;'>3<br>&nbsp;</div>
                </div>            
                    <div class="mtrow" style='line-height: 15px'>
                    <div id="jkeyxxx" class="normalblank" style='width: 30px; text-align:left; float: left;'> <br> </div>
                    <div id="jkey0p" class="normal" style="width: 80px; float: left;">0<br>&nbsp;</div>
                    <div id="jkeyperiodp" class="normal" style='text-align:left; float: left;'>.<br>&nbsp;</div>
                </div> 
            </td>
        </tr>
</table>
</div>
</div>
