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
 * This file defines the Qwerty(RV2) keyboard layout.
 *
 * @package    mod_mootyper
 * @copyright  2012 Jaka Luthar (jaka.luthar@gmail.com)
 * @copyright  2016 onwards AL Rachels (drachels@drachels.com)
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
// phpcs:disable Generic.WhiteSpace.DisallowTabIndent.TabsUsed

require_once(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.php');
require_login($course, true, $cm);
?>

<div id="keyboardContainerRow" style="float:left;">
		<div id="left_hand_container"  style="float: left;margin-right:20px" >
		<img id="lefthand" src='images/lefthand.png'>
		</div>
		<div class="keyboard"  style="float: left;" >
					<section>
						<div class="row">
							<div id="jkeytildo" class="key"><em>~</em><br><strong>`</strong></div>
							<div id="jkey1" class="key"><em>!</em><br><strong>1</strong></div>
							<div id="jkey2" class="key"><em>@</em><br><strong>2</strong></div>
							<div id="jkey3" class="key"><em>#</em><br><strong>3</strong></div>
							<div id="jkey4" class="key"><em>$</em><br><strong>4</strong></div>
							<div id="jkey5" class="key"><em>%</em><br><strong>5</strong></div>
							<div id="jkey6" class="key"><em>^</em><br><strong>6</strong></div>
							<div id="jkey7" class="key"><em>&</em><br><strong>7</strong></div>
							<div id="jkey8" class="key"><em>*</em><br><strong>8</strong></div>
							<div id="jkey9" class="key"><em>(</em><br><strong>9</strong></div>
							<div id="jkey0" class="key"><em>)</em><br><strong>0</strong></div>
							<div id="jkeypomislaj" class="key"><em>_</em><br><strong>-</strong></div>
							<div id="jkeyequals" class="key"><em>+</em><br><strong>=</strong></div>
							<div id="jkeybackspace" class="key wide_2"><span class="right"><strong>backspace</strong></span></div>
						</div>
						<div class="row">
							<div id="jkeytab" class="key wide_2"><span class="left"><strong>tab</strong></span></div>
							<div id="jkeyq" class="key single"><strong>Q</strong></div>
							<div id="jkeyw" class="key single"><strong>W</strong></div>
							<div id="jkeye" class="key single"><strong>E</strong></div>
							<div id="jkeyr" class="key single"><strong>R</strong></div>
							<div id="jkeyt" class="key single"><strong>T</strong></div>
							<div id="jkeyy" class="key single"><strong>Y</strong></div>
							<div id="jkeyu" class="key single"><strong>U</strong></div>
							<div id="jkeyi" class="key single"><strong>I</strong></div>
							<div id="jkeyo" class="key single"><strong>O</strong></div>
							<div id="jkeyp" class="key single"><strong>P</strong></div>
							<div id="jkeyoglokl" class="key"><em>{</em><br><strong>[</strong></div>
							<div id="jkeyoglzak" class="key"><em>}</em><br><strong>]</strong></div>
							<div id="jkeybackslash" class="key"><em>|</em><br><strong>\</strong></div>
						</div>
						<div class="row">
							<div id="jkeycaps" class="key wide_3"><span class="left"><strong>caps lock</strong></span></div>
							<div id="jkeya" class="key single"><strong>A</strong></div>
							
							<div id="jkeys" class="key single"><strong>S</strong></div>
							<div id="jkeyd" class="key single"><strong>D</strong></div>
							<div id="jkeyf" class="key single"><strong>F</strong></div>
							<div id="jkeyg" class="key single"><strong>G</strong></div>
							<div id="jkeyh" class="key single"><strong>H</strong></div>
							<div id="jkeyj" class="key single"><strong>J</strong></div>
							<div id="jkeyk" class="key single"><strong>K</strong></div>
							<div id="jkeyl" class="key single"><strong>L</strong></div>
							<div id="jkeypodpicje" class="key"><em>:</em><br><strong>;</strong></div>
							<div id="jkeycrtica" class="key"><em>"</em><br><strong>'</strong></div>
							<div id="jkeyenter" class="key wide_3"><span class="right"><strong>enter</strong></span></div>
						</div>
						<div class="row">
							<div id="jkeyshiftl" class="key wide_4"><span class="left"><strong>shift</strong></span></div>
							<div id="jkeyz" class="key single"><strong>Z</strong></div>
							<div id="jkeyx" class="key single"><strong>X</strong></div>
							<div id="jkeyc" class="key single"><strong>C</strong></div>
							<div id="jkeyv" class="key single"><strong>V</strong></div>
							<div id="jkeyb" class="key single"><strong>B</strong></div>
							<div id="jkeyn" class="key single"><strong>N</strong></div>
							<div id="jkeym" class="key single"><strong>M</strong></div>
							<div id="jkeyvejica" class="key"><em>&lt;</em><br><strong>,</strong></div>
							<div id="jkeypika" class="key"><em>&gt;</em><br><strong>.</strong></div>
							<div id="jkeyslash" class="key"><em>?</em><br><strong>/</strong></div>
							<div id="jkeyshiftd" class="key wide_4"><span class="right"><strong>shift</strong></span></div>
						</div>
						<div class="row">
							<div id="jkeyctrll" class="key wide_1"><span class="left"><strong>ctrl</strong></span></div>
							<div id="key_left_cmd" class="key wide_1"><span class="left"><strong>cmd</strong></span></div>
							<div id="jkeyalt" class="key wide_1"><span class="left"><strong>alt</strong></span></div>
							<div id="jkeyspace" class="key wide_5">Qwerty 2</div>
							<div id="jkeyaltgr" class="key wide_1"><span class="right"><strong>alt</strong></span></div>
							<div id="key_right_cmd" class="key wide_1"><span class="right"><strong>cmd</strong></span></div>
							<div id="jkeyctrlr" class="key wide_1">
								<span class="right"><strong>ctrl</strong></span>
							</div>
						</div>
						<div class="row" style="display:none">
						
							
							<div id="jkeyá" class="key single" style="display:none" >Á</div>
							<div id="jkeyà" class="key single" style="display:none" >À</div>
							<div id="jkeyả" class="key single" style="display:none" >Ả</div>
							<div id="jkeyã" class="key single" style="display:none" >Ã</div>
							<div id="jkeyạ" class="key single" style="display:none" >Ạ</div>
							
							<div id="jkeyă" class="key single" style="display:none" >Ă</div>
							
							<div id="jkeyắ" class="key single" style="display:none" >Ắ</div>
							<div id="jkeyằ" class="key single" style="display:none" >Ằ</div>
							<div id="jkeyẳ" class="key single" style="display:none" >Ẳ</div>
							<div id="jkeyẵ" class="key single" style="display:none" >Ẵ</div>
							<div id="jkeyặ" class="key single" style="display:none" >Ặ</div>
							
							<div id="jkeyâ" class="key single" style="display:none" >Â</div>
							
							<div id="jkeyấ" class="key single" style="display:none" >Ấ</div>
							<div id="jkeyầ" class="key single" style="display:none" >Ầ</div>
							<div id="jkeyẩ" class="key single" style="display:none" >Ẩ</div>
							<div id="jkeyẫ" class="key single" style="display:none" >Ẫ</div>
							<div id="jkeyậ" class="key single" style="display:none" >Ậ</div>
							
							<div id="jkeyđ" class="key single" style="display:none" >Đ</div>
							
							<div id="jkeyé" class="key single" style="display:none" >É</div>
							<div id="jkeyè" class="key single" style="display:none" >È</div>
							<div id="jkeyẻ" class="key single" style="display:none" >Ẻ</div>
							<div id="jkeyẽ" class="key single" style="display:none" >Ẽ</div>
							<div id="jkeyẹ" class="key single" style="display:none" >Ẹ</div>
							
							<div id="jkeyê" class="key single" style="display:none" >Ê</div>
							
							<div id="jkeyế" class="key single" style="display:none" >Ế</div>
							<div id="jkeyề" class="key single" style="display:none" >Ề</div>
							<div id="jkeyể" class="key single" style="display:none" >Ể</div>
							<div id="jkeyễ" class="key single" style="display:none" >Ễ</div>
							<div id="jkeyệ" class="key single" style="display:none" >Ệ</div>
							
							<div id="jkeyí" class="key single" style="display:none" >Í</div>
							<div id="jkeyì" class="key single" style="display:none" >Ì</div>
							<div id="jkeyỉ" class="key single" style="display:none" >Ỉ</div>
							<div id="jkeyĩ" class="key single" style="display:none" >Ĩ</div>
							<div id="jkeyị" class="key single" style="display:none" >Ị</div>
							
							<div id="jkeyó" class="key single" style="display:none" >Ó</div>
							<div id="jkeyò" class="key single" style="display:none" >Ò</div>
							<div id="jkeyỏ" class="key single" style="display:none" >Ỏ</div>
							<div id="jkeyõ" class="key single" style="display:none" >Õ</div>
							<div id="jkeyọ" class="key single" style="display:none" >Ọ</div>
							
							<div id="jkeyô" class="key single" style="display:none" >Ô</div>
							
							<div id="jkeyố" class="key single" style="display:none" >Ố</div>
							<div id="jkeyồ" class="key single" style="display:none" >Ồ</div>
							<div id="jkeyổ" class="key single" style="display:none" >Ổ</div>
							<div id="jkeyỗ" class="key single" style="display:none" >Ỗ</div>
							<div id="jkeyộ" class="key single" style="display:none" >Ộ</div>
							
							<div id="jkeyơ" class="key single" style="display:none" >Ơ</div>
							
							<div id="jkeyớ" class="key single" style="display:none" >Ớ</div>
							<div id="jkeyờ" class="key single" style="display:none" >Ờ</div>
							<div id="jkeyở" class="key single" style="display:none" >Ở</div>
							<div id="jkeyỡ" class="key single" style="display:none" >Ỡ</div>
							<div id="jkeyợ" class="key single" style="display:none" >Ợ</div>
							
							<div id="jkeyú" class="key single" style="display:none" >Ú</div>
							<div id="jkeyù" class="key single" style="display:none" >Ù</div>
							<div id="jkeyủ" class="key single" style="display:none" >Ủ</div>
							<div id="jkeyũ" class="key single" style="display:none" >Ũ</div>
							<div id="jkeyụ" class="key single" style="display:none" >Ụ</div>
							
							<div id="jkeyư" class="key single" style="display:none" >Ư</div>
							
							<div id="jkeyứ" class="key single" style="display:none" >Ứ</div>
							<div id="jkeyừ" class="key single" style="display:none" >Ừ</div>
							<div id="jkeyử" class="key single" style="display:none" >Ử</div>
							<div id="jkeyữ" class="key single" style="display:none" >Ữ</div>
							<div id="jkeyự" class="key single" style="display:none" >Ự</div>
							
							<div id="jkeyý" class="key single" style="display:none" >Ý</div>
							<div id="jkeyỳ" class="key single" style="display:none" >Ỳ</div>
							<div id="jkeyỷ" class="key single" style="display:none" >Ỷ</div>
							<div id="jkeyỹ" class="key single" style="display:none" >Ỹ</div>
							<div id="jkeyỵ" class="key single" style="display:none" >Ỵ</div>
							
						
							
							
						
							
						</div>
						<div><audio src="sounds/type.ogg" id="type" preload></div>
					</section>
					
					
		 </div>
		<div id="right_hand_container"  style="float: left;margin-left:20px" >
		<img id="righthand" src='images/righthand.png'>
		</div>
</div>
