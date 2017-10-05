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
 * This file defines the English(V3)keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 require_once(dirname(dirname(dirname(dirname(__FILE__)))).'/config.php');
 require_login($course, true, $cm);
?>
﻿<div id="innerKeyboard" style="margin: 0px auto;display: inline-block;
<?php
echo (isset($displaynone) && ($displaynone == true)) ? 'display:none;' : '';
?>
"><br>
<span id="keyboard" class="keyboardback">Dvorak(V3) Keyboard Layout<br><br>
<span id="jkeybackquote" class="normal"><span class="textup">~ </span><span class="textdown">  `</span></span>
<span id="jkey1" class="normal"><span class="textup">! </span> 1</span>
<span id="jkey2" class="normal"><span class="textup">@</span> 2</span>
<span id="jkey3" class="normal"><span class="textup">#</span> 3</span>
<span id="jkey4" class="normal"><span class="textup">$</span> 4</span>
<span id="jkey5" class="normal"><span class="textup">%</span> 5</span>
<span id="jkey6" class="normal"><span class="textup">^</span> 6</span>
<span id="jkey7" class="normal"><span class="textup">& </span> 7</span>
<span id="jkey8" class="normal"><span class="textup">*</span> 8</span>
<span id="jkey9" class="normal"><span class="textup">(</span> 9</span>
<span id="jkey0" class="normal"><span class="textup">)</span> 0</span>
<span id="jkeybracketl" class="normal"><span class="textup">{</span> [</span>
<span id="jkeybracketr" class="normal"><span class="textup">}</span> ]</span>
<span id="jkeybackspace" class="normal" style="border-right-style: solid;">Backspace</span>
<br>
<span id="jkeytab" class="normal" style="width: 55px;">Tab</span>
<span id="jkeycrtica" class="normal"><span class="textup">"</span><span class="textdown"> '</span></span>
<span id="jkeycomma" class="normal"><span class="textup">&lt;</span> ,</span>
<span id="jkeyperiod" class="normal"><span class="textup">&gt;</span> .</span>
<span id="jkeyp" class="normal">P</span>
<span id="jkeyy" class="normal">Y</span>
<span id="jkeyf" class="normal">F</span>
<span id="jkeyg" class="normal">G</span>
<span id="jkeyc" class="normal">C</span>
<span id="jkeyr" class="normal">R</span>
<span id="jkeyl" class="normal">L</span>
<span id="jkeyslash" class="normal"><span class="textup">?</span><span class="textdown"> /</span></span>
<span id="jkeyequals" class="normal"><span class="textup">+</span><span class="textdown"> =</span></span>
<span id="jkeybackslash" class="normal" style="width: 60px;"><span class="textup">|</span><span class="textdown"> \</span></span>
<br>
<span id="jkeycaps" class="normal" style="width: 70px;">C.lock</span>
<span id="jkeya" class="finger4">A</span>
<span id="jkeyo" class="finger3">O</span>
<span id="jkeye" class="finger2">E</span>
<span id="jkeyu" class="finger1">U</span>
<span id="jkeyi" class="normal">I</span>
<span id="jkeyd" class="normal">D</span>
<span id="jkeyh" class="finger1">H</span>
<span id="jkeyt" class="finger2">T</span>
<span id="jkeyn" class="finger3">N</span>
<span id="jkeys" class="finger4">S</span>
<span id="jkeyminus" class="normal"><span class="textup">_</span><span class="textdown"> -</span></span>
<span id="jkeyenter" class="normal" style="width: 85px;">Enter</span>
<br>
<span id="jkeyshiftl" class="normal" style="width: 92px;">Shift</span>
<span id="jkeysemicolon" class="normal"><span class="textup">:</span><span class="textdown"> ;</span></span>
<span id="jkeyq" class="normal">Q</span>
<span id="jkeyj" class="normal">J</span>
<span id="jkeyk" class="normal">K</span>
<span id="jkeyx" class="normal">X</span>
<span id="jkeyb" class="normal">B</span>
<span id="jkeym" class="normal">M</span>
<span id="jkeyw" class="normal">W</span>
<span id="jkeyv" class="normal">V</span>
<span id="jkeyz" class="normal">Z</span>
<span id="jkeyshiftd" class="normal" style="width: 102px;">Shift</span>
<br>
<span id="jkeyctrll" class="normal" style="width: 65px;">Ctrl</span>
<span id="jkeyfn" class="normal">Win</span>
<span id="jkeyalt" class="normal" style="width: 45px;">Alt</span>
<span id="jkeyspace" class="normal" style="width: 258px;">Space</span>
<span id="jkeyaltgr" class="normal" style="width: 45px;">Alt</span>
<span id="jkeyfn" class="normal">Win</span>
<span id="jkeyfn" class="normal">Menu</span>
<span id="jkeyctrlr" class="normal" style="width: 65px;">Ctrl</span>
<br>
</span>
</div>
