@mod @mod_mootyper @javascript
Feature: Lesson progression persists after viewing grades
  In order to prevent restarting finished lesson exercises
  As a student
  I need progression to stay on the next exercise after results are posted

  Background:
    Given the following "courses" exist:
      | fullname | shortname | category | groupmode |
      | Course 1 | C1 | 0 | 1 |
    And the following "activities" exist:
      | activity | name | intro | course | section | idnumber | lesson | isexam | requiredgoal | continuoustype | countmistypedspaces | countmistakes | layout |
      | mootyper | MooTyper Progression Test | Progression behavior test | C1 | 1 | MTPRG | 1 | 0 | 100 | 1 | 1 | 1 | 1 |
    And the following "users" exist:
      | username | firstname | lastname | email |
      | student1 | Student | 1 | student1@example.com |
    And the following "course enrolments" exist:
      | user | course | role |
      | student1 | C1 | student |

  Scenario: Student remains on next lesson exercise after viewing grades
    Given I log in as "student1"
    And I am on the "MooTyper Progression Test" "mootyper activity" page
    And the current mootyper exercise snumber should be 1
    And I create a completed mootyper grade for the current exercise
    When I am on the "MooTyper Progression Test" "mootyper activity" page
    And the current mootyper exercise snumber should be 2
    And I follow "View my grades"
    And I should see "MooTyper Progression Test"
    When I am on the "MooTyper Progression Test" "mootyper activity" page
    Then I should see "Exercise = 2"
