@mod @mod_mootyper @javascript
Feature: MooTyper layout input harness
  In order to validate keyboard layouts for non-Latin languages
  As a teacher and student
  I need reusable Behat scenarios that verify key highlighting and typing progression

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
      | student1 | C1 | student |

  Scenario: Korean KNV7 key highlighting and simulated typing harness
    Given I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "MooTyper" to section "1" using the activity chooser
    And I set the following fields to these values:
      | Name | MooTyper KNV7 Harness |
      | Description | Behat harness for Korean layout typing |
    And I click on "Save and display" "button"
    And I follow "Setup"
    And I set the field "mode" to "Practice"
    And I set the field "lesson" to "Lesson 01"
    And I set the field "continuoustype" to "0"
    And I set the field "countmistypedspaces" to "0"
    And I set the field "countmistakes" to "0"
    And I set the field "showkeyboard" to "1"
    And I set the field "layout" to "Korean(KNV7)"
    And I press "Confirm"
    And I log out

    When I am on the "MooTyper KNV7 Harness" "mootyper activity" page logged in as "student1"
    And I simulate committing the first mootyper character
    Then at least one mootyper key should be highlighted as next

    # Replace this sample with a target sentence for the selected lesson/layout.
    When I type "ㅂㅈㄷㄱ ㅅ" in mootyper using simulated input
    Then mootyper progress should be complete
