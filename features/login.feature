@api
@api_login

Feature: i need to be able to log in to api and obtain token

  Scenario: [Success] Fail Login with invalid username
    When I load the fixture "createUser" in "user" folder
    And I send a "POST" request to "/api/login" with body:
    """
    {
      "username": "user999",
      "password": "123456"
    }
    """
    Then the response status code should be 403
    And the response should be in JSON
    And the JSON node "message" should exist

  Scenario: [Success] Fail Login with invalid password
    When I load the fixture "createUser" in "user" folder
    And I send a "POST" request to "/api/login" with body:
    """
    {
      "username": "user1",
      "password": "1234567"
    }
    """
    Then the response status code should be 403
    And the response should be in JSON
    And the JSON node "message" should exist

  Scenario: [Success] Login as admin
    When I load the fixture "createUser" in "user" folder
    And I send a "POST" request to "/api/login" with body:
    """
    {
      "username": "user2",
      "password": "123456"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "token" should exist
    And the JSON node "user" should have 5 elements
    And the JSON node "user.id" should be equal to "2"
    And the JSON node "user.firstname" should be equal to "john"
    And the JSON node "user.lastname" should be equal to "doe"
    And the JSON node "user.email" should be equal to "user2@contact.fr"
    And the JSON node "user.roles[0]" should be equal to "ROLE_ADMIN"
    And user with email "user2@contact.fr" should exist in database

  Scenario: [Success] Login as user
    When I load the fixture "createUser" in "user" folder
    And I send a "POST" request to "/api/login" with body:
    """
    {
      "username": "user1",
      "password": "123456"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node "token" should exist
    And the JSON node "user" should have 5 elements
    And the JSON node "user.roles[0]" should be equal to "ROLE_USER"
    And user with email "user1@contact.fr" should exist in database

  Scenario: [Success] Login as user with email
    When I load the fixture "createUser" in "user" folder
    And I send a "POST" request to "/api/login" with body:
    """
    {
      "username": "user1@contact.fr",
      "password": "123456"
    }
    """
    Then the response status code should be 200