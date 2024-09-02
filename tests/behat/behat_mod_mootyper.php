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
 * Steps definitions related with the mootyper activity.
 *
 * @package mod_mootyper
 * @category test
 * @copyright 2015 The Open University
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// NOTE: no MOODLE_INTERNAL test here, this file may be required by behat before including /config.php.

require_once(__DIR__ . '/../../../../lib/behat/behat_base.php');

use Behat\Behat\Context\Step\Given as Given,
Behat\Gherkin\Node\TableNode as TableNode;
use Behat\Behat\Context\Step\Then;
use Behat\Mink\Exception\ElementNotFoundException;

/**
 * mootyper-related steps definitions.
 *
 * @package    mod_mootyper
 * @category   test
 * @copyright  2015 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class behat_mod_mootyper extends behat_base {

    /**
     * To assert a select value.
     * @Then /^"([^"]*)" from "([^"]*)" is selected$/
     *
     * Shamelessly inspired by: https://stackoverflow.com/a/33223002/1038565
     * @param string $optionvalue
     * @param string $select
     */
    public function theoptionfromselectisselected($optionvalue, $select) {
        $selectfield = $this->getSession()->getPage()->findField($select);

        if (null === $selectfield) {
            throw new \Exception(sprintf('The select "%s" was not found in the page %s',
                $select, $this->getSession()->getCurrentUrl()));
        }

        $optionfield = $selectfield->find('xpath', "//option[@selected]");
        if (null === $optionfield) {
            throw new \Exception(sprintf('No option is selected in the %s select in the page %s',
                $select, $this->getSession()->getCurrentUrl()));
        }

        if ($optionfield->getValue() != $optionvalue) {
            throw new \Exception(sprintf('The option "%s" was not selected in the page %s, %s was selected',
                $optionvalue, $this->getSession()->getCurrentUrl(), $optionfield->getValue()));
        }
    }
}
