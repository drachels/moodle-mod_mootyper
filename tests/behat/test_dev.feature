@mod @mod_mootyper1
Feature: Basic mootyper use
  In order to complete mootyper entries
  As a teacher or student
  I need to make mootyper entries

  Background:
    Given the following "courses" exist:
      | fullname | shortname |
      | Course 1 | C1        |
    And the following "activities" exist:
      | activity  | name                 | intro                      | course | section | idnumber |
      | mootyper  | Mootyper for testing | This mootyper introduction. | C1     | 1       | D001     |
    And the following "users" exist:
      | username | firstname | lastname | email            |
      | teacher1 | Terri     | Teacher  | teacher1@asd.com |
      | student1 | Owen      | Money    | student1@asd.com |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | C1     | editingteacher |
      | student1 | C1     | student        |

  # @javascript
  Scenario: A teacher creates a mootyper activity
    # Teacher 1 adds mootyper activity.
    When I am on the "Mootyper for testing" "mootyper activity" page logged in as "teacher1"
    Then I should see "Mootyper for testing"
    And I should see "This mootyper introduction."
    And I wait 10 seconds

    And I should see "Setup"
    And I wait 5 seconds

    And I follow "Setup"
    And I wait 5 seconds

    And I set the field "mode" to "Exam"
    And I set the field "lesson" to "Lesson 01"
    And I set the field "timelimit" to "3"
    And I set the field "requiredgoal" to "95"
    And I set the field "requiredwpm" to "25"
    And I set the field "textalign" to "left"
    And I set the field "continuoustype" to "1"
    And I set the field "countmistypedspaces" to "1"
    And I set the field "countmistakes" to "1"
    And I set the field "showkeyboard" to "1"
    And I set the field "layout" to "English(USV5)"
    And I press "Confirm"
    # And I should see "MooTyper for testing"
    And I should see "This mootyper introduction"
    And I should see "Mode = Exam"
    And I should see "Lesson name = Lesson 01"
    And I should see "Exercise = 1 Home row workout of 10"
    And I should see "Time limit (3:00)"
    And I should see "Required precision (95%)"
    And I should see "Required WPM (20)"
    And I wait 5 seconds

    And I set the following fields to these values:
      | tb1 | fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj fjfj |
    And I wait 5 seconds
    And I should see "100.00%"
    And I press "Continue"
    And I should see "Practice Exercise 2 of 10"
    Then I log out

