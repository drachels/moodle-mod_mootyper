@mod @mod_mootyper
Feature: Teacher can setup mootyper
  In order to complete mootyper lessons
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