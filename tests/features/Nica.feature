Feature: non-node-titles
  Test HTML titles on non-node pages.

  Scenario: Check the homepage.
    Given I am on the homepage
    Then I should get a "200" HTTP response
