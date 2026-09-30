@mod @mod_mootyper @javascript
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
    And I am on "Course 1" course homepage with editing mode on

  Scenario: A teacher creates a mootyper activity
    # Teacher 1 adds mootyper activity.
    Given I add a "mootyper" activity to course "Course 1" section "1" and I fill the form with:
      | Name | mootyper name |
      | Description | A mootyper for testing |
    And I am on the "mootyper name" "mootyper activity" page
    And I should see "mootyper name"
    Then I log out

  Scenario: Non-latest grade delete is blocked in view all grades
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
    And I add a "mootyper" activity to course "Course 1" section "1" and I fill the form with:
      | Name | mootyper guard test |
      | Description | Guard regression test |
    And I am on the "mootyper guard test" "mootyper activity" page
    And I should see "mootyper guard test"
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

  Scenario: Student cannot delete a peer grade by changing the return mode
    Given the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
      | student2 | Student | 2 | student2@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |
      | student2 | C1 | student |
    And I add a "mootyper" activity to course "Course 1" section "1" and I fill the form with:
      | Name | mootyper ownership test |
      | Description | Ownership regression test |
    And I am on the "mootyper ownership test" "mootyper activity" page
    And I should see "mootyper ownership test"
    And I log out
    And I log in as "student1"
    And I am on the "mootyper ownership test" "mootyper activity" page
    And I seed a completed mootyper grade for user "student2"
    When I request deletion of the older seeded mootyper grade in view-all mode
    Then the seeded peer mootyper grade should still exist
