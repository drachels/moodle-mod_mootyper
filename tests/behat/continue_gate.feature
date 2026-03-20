@mod @mod_mootyper @javascript
Feature: Continue gate behavior
  In order to prevent keyboard-only progression at exercise end
  As a student
  I need Enter key activation on Continue to be ignored while mouse click advances

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "activities" exist:
      | activity | name | intro | course | section | idnumber | lesson | isexam | requiredgoal | continuoustype | countmistypedspaces | countmistakes | layout |
      | mootyper | MooTyper Continue Gate Test | Continue gate behavior test | C1 | 1 | MTCGT | 1 | 0 | 95 | 1 | 1 | 1 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |

  Scenario: Keyboard activation on Continue is ignored, mouse click advances
    Given I log in as "student1"
    And I am on the "MooTyper Continue Gate Test" "mootyper activity" page
    Then mootyper Continue gate should block Enter and allow click
