@api
@api_read_post

Feature: As an authenticated user i must get one post

  Scenario:[Fail] Try to search post while not being authenticated
    When I send a "GET" request to "/api/posts/post1"
    Then the response should be in JSON
    And the response status code should be 401

  Scenario:[Fail] As an authenticated user i try to get post with invalid id
    When I load the fixture "readPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts/9999999"
    Then the response should be in JSON
    And the response status code should be 404
    And the JSON node "errors.message" should be equal to "Not found post with id 9999999"

  Scenario:[Success] As an authenticated user i try to get one post without query params
    When I load the fixture "readPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts/post1"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "item" should exist
    And the JSON node "item" should have 2 elements
    And the JSON node "item.id" should be equal to "post1"
    And the JSON node "item._links" should exist

  Scenario:[Success] As an authenticated user i try to get one post with title and content
    When I load the fixture "readPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts/post1?fields[post]=title,content"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "item.id" should be equal to "post1"
    And the JSON node "item.title" should be equal to "titre du post1"
    And the JSON node "item.content" should be equal to "le contenu de l'article pour les tests"

  Scenario:[Success] As an authenticated user i try to get one post include user
    When I load the fixture "readPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts/post1?includes=user"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "item.id" should be equal to "post1"
    And the JSON node "item.user.id" should be equal to "user1"

  Scenario:[Success] As an authenticated user i try to get one post include user firstname, lastname and email
    When I load the fixture "readPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts/post1?includes=user.firstname,user.lastname,user.email"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "item.id" should be equal to "post1"
    And the JSON node "item.user.id" should be equal to "user1"
    And the JSON node "item.user.firstname" should be equal to "john"
    And the JSON node "item.user.lastname" should be equal to "doe"
    And the JSON node "item.user.email" should be equal to "user1@contact.fr"