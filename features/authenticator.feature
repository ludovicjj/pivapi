@api_authenticator
Feature: Test JWTAuthenticator guard

  Scenario: [FAIL] Send request to protected uri with Invalid format token
    When I add "Authorization" header equal to "Bearer invalidtoken"
    And I send a "POST" request to "/api/authenticator"
    Then the response status code should be 401
    And the JSON node "error" should be equal to "Invalid token"

  Scenario: [FAIL] Send request to protected uri with no token
    When I send a "POST" request to "/api/authenticator"
    Then the response status code should be 401
    And the JSON node "error" should be equal to "Authentication Required"

  Scenario: [FAIL] Send request to protected uri with expired token
    When I load the fixture "createUser" in "user" folder
    And I am connected with email "user1@contact.fr" and expired token
    And I send a "POST" request to "/api/authenticator"
    Then the response status code should be 401
    And the JSON node "error" should be equal to "Expired token"

  Scenario: [Success] Send request to protected uri with valid token
    When I load the fixture "createUser" in "user" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/authenticator"
    Then the response status code should be 200