@mod @mod_mootyper
Feature: Teacher can remove mootyper grades
  In order to remove mootyper grades
  As a teacher
  I need to set up a mootyper activity

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | teacher1 | Teacher | 1 | teacher1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | teacher1 | C1 | editingteacher |
    And I log in as "teacher1"
    And I follow "Course 1"
    And I turn editing mode on

  Scenario: A teacher creates a mootyper activity
    # Teacher 1 adds mootyper activity.
    Given I add a "mootyper" to section "1" and I fill the form with:
      | Name | mootyper name |
      | Description | A mootyper for testing |
    And I follow "mootyper name"
    And I should see "Setup"
    Then I log out

  Scenario: Non-latest grade delete is blocked in view all grades
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
    And I add a "mootyper" to section "1" and I fill the form with:
      | Name | mootyper guard test |
      | Description | Guard regression test |
    And I follow "mootyper guard test"
    And I should see "Setup"
    And I log out
    And I log in as "student1"
    And I am on the "mootyper guard test" "mootyper activity" page
    And I seed two completed mootyper grades for the current user
    And I log out
    And I log in as "teacher1"
    And I am on the "mootyper guard test" "mootyper activity" page
    When I request deletion of the older seeded mootyper grade in view-all mode
    Then I should see "Delete blocked. You may delete only the latest completed exercise result for that user in this lesson."
    And the seeded mootyper grades should both still exist
