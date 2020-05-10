@api
@api_delete_post

Feature: As an authenticated user i must delete post

  Scenario:[Fail] Try to delete post while not being authenticated
    When I send a "DELETE" request to "/api/posts/1"
    Then the response should be in JSON
    And the response status code should be 401

  Scenario:[Fail] As an authenticated user i try to delete a not existing post
    When I load the fixture "deletePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "DELETE" request to "/api/posts/post9999"
    Then the response should be in JSON
    And the response status code should be 400
    And the JSON node "errors" should exist
    And the JSON node "errors.message" should be equal to "Post with id post9999 not found"

  Scenario:[Success] As an authenticated user i try to delete a not existing post
    When I load the fixture "deletePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "DELETE" request to "/api/posts/post1"
    Then the response should be in JSON
    And the response status code should be 200
    And post with id "post1" should not exist in database