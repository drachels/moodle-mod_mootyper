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
<?php // phpcs:ignore
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
<div id="keyboard" class="keyboardback">Amharic (ETV7) Keyboard<br>                                                           
<section>                                      
    <div id="inputviewer">Your current input:</div>                                                                             
    <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>                     
        <div id="jkeycaret`" class="normal" style='text-align:left;'>`<br>~</div>                                               
        <div id="jkey1" class="normal" style='text-align:left;'>!<br>1</div>                                                    
        <div id="jkey2" class="normal" style='text-align:left;'>@<br>2</div>                                                    
        <div id="jkey3" class="normal" style='text-align:left;'>#<br>3</div>                                                    
        <div id="jkey4" class="normal" style='text-align:left;'>$<br>4</div>                                                    
        <div id="jkey5" class="normal" style='text-align:left;'>%<br>5</div>                                                    
        <div id="jkey6" class="normal" style='text-align:left;'>^<br>6</div>                                                    
        <div id="jkey7" class="normal" style='text-align:left;'>&<br>7</div>                                                    
        <div id="jkey8" class="normal" style='text-align:left;'>*<br>8</div>                                                    
        <div id="jkey9" class="normal" style='text-align:left;'>(<br>9</div>                                                    
        <div id="jkey0" class="normal" style='text-align:left;'>)<br>0</div>                                                    
        <div id="jkeyminus" class="normal" style='text-align:left;'>_<br>-</div>                                                
        <div id="jkey=" class="normal" style='text-align:left;'>+<br>=</div>                                                    
        <div id="jkeybackspace" class="normal"  style="width: 92px;">Backspace</div>                                        
    </div>   
        <div style="float: left;">                                                                                                  
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>                     
                <div id="jkeytab" class="normal" style="width: 65px;">Tab</div>                                                         
                <div id="jkeyq" class="normal" style='text-align:left;'>Q<br>ቅ</div>                                                    
                <div id="jkeyw" class="normal" style='text-align:left;'>W<br>ው</div>                                                    
                <div id="jkeye" class="normal" style='text-align:left;'>E<br>እ</div>                                                    
                <div id="jkeyr" class="normal" style='text-align:left;'>R<br>ር</div>                                                   
                <div id="jkeyt" class="normal" style='text-align:left;'>T<br>ት</div>                                                    
                <div id="jkeyy" class="normal" style='text-align:left;'>Y<br>ይ</div>                                                    
                <div id="jkeyu" class="normal" style='text-align:left;'>U<br>ኡ</div>                                                    
                <div id="jkeyi" class="normal" style='text-align:left;'>I<br>ኢ</div>                                                    
                <div id="jkeyo" class="normal" style='text-align:left;'>O<br>ኦ</div>                                                    
                <div id="jkeyp" class="normal" style='text-align:left;'>P<br>ፕ</div>                                                    
                <div id="jkey[" class="normal" style="text-align:left;">{<br>[</div>                                                    
                    <div id="jkey]" class="normal" style="text-align:left;">}<br>]</div>                                                
                </div>                                                                                                                  
                <span id="jkeyenter" class="normal" style="width: 52px; margin-right:5px; float: right; height: 85px;">Enter</span>
            <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
                <div id="jkeycaps" class="normal" style="width: 80px;">Caps Lock</div>
                <div id="jkeya" class="finger2" style='text-align:left;'>A<br>አ</div>
                <div id="jkeys" class="finger1" style='text-align:left;'>S<br>ስ</div>
                <div id="jkeyd" class="finger4" style='text-align:left;'>D<br>ድ</div>
                <div id="jkeyf" class="finger3" style='text-align:left;'>F<br>ፍ</div>
                <div id="jkeyg" class="normal" style='text-align:left;'>G<br>ግ</div>
                <div id="jkeyh" class="normal" style='text-align:left;'>H<br>ህ</div>
                <div id="jkeyj" class="finger3" style='text-align:left;'>J<br>ጅ</div>
                <div id="jkeyk" class="finger4" style='text-align:left;'>K<br>ክ</div>
                <div id="jkeyl" class="finger1" style='text-align:left;'>L<br>ል</div>
                <div id="jkey፤" class="finger2" style='text-align:left;'>፡<br>፤</div>
                <div id="jkey'" class="normal" style='text-align:left;'>"<br>'</div>
                <div id="jkey\" class="normal" style='text-align:left;'>|<br>\</div>
            </div>
        </div>
        <div class="mtrow" style='float: left; margin-left:5px; font-size: 15px !important; line-height: 15px'>
            <div id="jkeyshiftl" class="normal" style="width: 87px;">Shift</div>
            <div id="jkeyckck" class="normal" style='text-align:left;'>&gt;<br>&lt;</div>
            <div id="jkeyz" class="normal" style='text-align:left;'>Z<br>ዝ</div>
            <div id="jkeyx" class="normal" style='text-align:left;'>X<br>ሽ</div>
            <div id="jkeyc" class="normal" style='text-align:left;'>C<br>ች</div>
            <div id="jkeyv" class="normal" style='text-align:left;'>V<br>ቭ</div>
            <div id="jkeyb" class="normal" style='text-align:left;'>B<br>ብ</div>
            <div id="jkeyn" class="normal" style='text-align:left;'>N<br>ን</div>
            <div id="jkeym" class="normal" style='text-align:left;'>M<br>ም</div>
            <div id="jkeycomma" class="normal" style='text-align:left;'><br>፣</div>
            <div id="jkeyperiod" class="normal" style='text-align:left;'><br>።</div>
            <div id="jkey/" class="normal" style='text-align:left;'>?<br>/</div>
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
