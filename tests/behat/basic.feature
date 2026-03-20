@mod @mod_mootyper1
Feature: Teacher can setup mootyper
  In order to complete mootyper lessons
  As a teacher
  I need to set up a mootyper activity

  # @javascript
  Scenario: A teacher creates a mootyper activity
    # Teacher 1 adds mootyper activity.
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
    And I log in as "teacher1"
    And I am on "Course 1" course homepage with editing mode on
    And I add a "MooTyper" to section "1" and I fill the form with:
      | Name | MooTyper Name |
      | Description | A mootyper for testing |
    And I follow "MooTyper Name"
    And I should see "Setup"
    And I follow "Setup"
    And I set the field "mode" to "Practice"
    And I set the field "lesson" to "Lesson 01"
    And I set the field "requiredgoal" to "95"
    And I set the field "textalign" to "left"
    And I set the field "continuoustype" to "1"
    And I set the field "countmistypedspaces" to "1"
    And I set the field "countmistakes" to "1"
    And I set the field "showkeyboard" to "1"
    And I set the field "layout" to "English(USV4)"
    And I press "Confirm"
    And I should see "MooTyper Name"
    And I should see "A mootyper for testing"
    And I should see "Practice Exercise 1 of 10"
    And I set the following fields to these values:
      | tb1 | f |
    Then I wait "3" seconds
    And I should see "100.00%"
    And I press "Continue"
    And I should see "Practice Exercise 2 of 10"
    Then I log out
