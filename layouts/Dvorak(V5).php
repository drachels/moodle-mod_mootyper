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
 * This file defines the Dvorak(V5.4)keyboard layout.
 *
 * @package   mod_mootyper
 * @copyright 2019 AL Rachels (drachels@drachels.com)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_login($course, true, $cm);
?>
﻿
<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
">
    <div id="keyboard" class="keyboardback">
        Dvorak(V5) Keyboard Layout<br>
        <section>
            <div class="mtrow"
                style='float: left; margin-left: 5px; font-size: 12px !important; line-height: 15px'>
                <div id="jkeybackquote" class="normal">
                    <b>~<br>`
                    </b>
                </div>
                <div id="jkey1" class="normal">
                    <b>!<br>1
                    </b>
                </div>
                <div id="jkey2" class="normal">
                    <b>@<br>2
                    </b>
                </div>
                <div id="jkey3" class="normal">
                    <b>#<br>3
                    </b>
                </div>
                <div id="jkey4" class="normal">
                    <b>$<br>4
                    </b>
                </div>
                <div id="jkey5" class="normal">
                    <b>%<br>5
                    </b>
                </div>
                <div id="jkey6" class="normal">
                    <b>^<br>6
                    </b>
                </div>
                <div id="jkey7" class="normal">
                    <b>&amp;<br>7
                    </b>
                </div>
                <div id="jkey8" class="normal">
                    <b>*<br>8
                    </b>
                </div>
                <div id="jkey9" class="normal">
                    <b>(<br>9
                    </b>
                </div>
                <div id="jkey0" class="normal">
                    <b>)<br>0
                    </b>
                </div>
                <div id="jkeybracketl" class="normal">
                    {<br> [
                </div>
                <div id="jkeybracketr" class="normal">
                    }<br> ]
                </div>
                <div id="jkeybackspace" class="normal" style="width: 95px;">Backspace</div>
            </div>

            <div class="mtrow" style='float: left; margin-left: 5px;'>
                <div id="jkeytab" class="normal" style="width: 60px;">Tab</div>
                <div id="jkeycrtica" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    "<br>'
                </div>
                <div id="jkeycomma" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    &lt;<br>,
                </div>
                <div id="jkeyperiod" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    &gt;<br>.
                </div>
                <div id="jkeyp" class="normal">P</div>
                <div id="jkeyy" class="normal">Y</div>
                <div id="jkeyf" class="normal">F</div>
                <div id="jkeyg" class="normal">G</div>
                <div id="jkeyc" class="normal">C</div>
                <div id="jkeyr" class="normal">R</div>
                <div id="jkeyl" class="normal">L</div>
                <div id="jkeyslash" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    ?<br>/
                </div>
                <div id="jkeyequals" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    +<br>=
                </div>
                <div id="jkeybackslash" class="normal"
                    style='width: 75px; font-size: 12px !important; line-height: 15px'>
                    |<br>\
                </div>
            </div>

            <div class="mtrow" style='float: left; margin-left: 5px;'>
                <div id="jkeycaps" class="normal"
                    style="width: 80px; font-size: 12px !important;">Caps Lock</div>
                <div id="jkeya" class="finger4">A</div>
                <div id="jkeyo" class="finger3">O</div>
                <div id="jkeye" class="finger2">E</div>
                <div id="jkeyu" class="finger1">U</div>
                <div id="jkeyi" class="normal">I</div>
                <div id="jkeyd" class="normal">D</div>
                <div id="jkeyh" class="finger1">H</div>
                <div id="jkeyt" class="finger2">T</div>
                <div id="jkeyn" class="finger3">N</div>
                <div id="jkeys" class="finger4">S</div>
                <div id="jkeyminus" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    _<br>-
                </div>
                <div id="jkeyenter" class="normal" style="width: 95px;">Enter</div>
            </div>

            <div class="mtrow" style='float: left; margin-left: 5px;'>
                <div id="jkeyshiftl" class="normal" style="width: 100px;">Shift</div>
                <div id="jkeysemicolon" class="normal"
                    style='font-size: 12px !important; line-height: 15px'>
                    :<br>;
                </div>
                <div id="jkeyq" class="normal">Q</div>
                <div id="jkeyj" class="normal">J</div>
                <div id="jkeyk" class="normal">K</div>
                <div id="jkeyx" class="normal">X</div>
                <div id="jkeyb" class="normal">B</div>
                <div id="jkeym" class="normal">M</div>
                <div id="jkeyw" class="normal">W</div>
                <div id="jkeyv" class="normal">V</div>
                <div id="jkeyz" class="normal">Z</div>
                <div id="jkeyshiftr" class="normal" style="width: 115px;">Shift</div>
            </div>

            <div class="mtrow" style='float: left; margin-left: 5px;'>
                <div id="jkeyctrll" class="normal" style="width: 70px;">Ctrl</div>
                <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
                <div id="jkeyalt" class="normal" style="width: 50px;">Alt</div>
                <div id="jkeyspace" class="normal" style="width: 225px;">Space</div>
                <div id="jkeyaltgr" class="normal" style="width: 50px;">Alt</div>
                <div id="jkeyfn" class="normal" style="width: 50px;">Win</div>
                <div id="jkeyfn" class="normal" style="width: 50px;">Menu</div>
                <div id="jkeyctrlr" class="normal" style="width: 70px;">Ctrl</div>
            </div>
        </section>
    </div>
</div>
