@api
@api_search_post

Feature: As an authenticated user i must search post

  Scenario:[Fail] Try to search post while not being authenticated
    When I send a "GET" request to "/api/posts"
    Then the response should be in JSON
    And the response status code should be 401

  Scenario:[Fail] As an authenticated user i try to search post with invalid key fields
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[pos]=id,title"
    Then the response should be in JSON
    And the response status code should be 400
    And the JSON node "errors.message" should be equal to "Not found index post"

  Scenario:[Fail] As an authenticated user i try to search post with invalid param page
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[pos]=id,title&page=3"
    Then the response should be in JSON
    And the response status code should be 404
    And the JSON node "errors.message" should be equal to "Not found page 3"

  Scenario:[Success] As an authenticated user i try to search post with id and title
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[post]=id,title"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "items" should have 2 elements
    And the JSON node "items[0].id" should be equal to "post1"
    And the JSON node "items[0].title" should be equal to "titre du post1"
    And the JSON node "items[1].id" should be equal to "post2"
    And the JSON node "items[1].title" should be equal to "titre du post2"

  Scenario:[Success] As an authenticated user i try to search post include user
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[post]=id,title&includes=user.id"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "items" should have 2 elements
    And the JSON node "items[0].id" should be equal to "post1"
    And the JSON node "items[0].title" should be equal to "titre du post1"
    And the JSON node "items[0].user.id" should be equal to "user1"
    And the JSON node "items[1].id" should be equal to "post2"
    And the JSON node "items[1].title" should be equal to "titre du post2"
    And the JSON node "items[1].user.id" should be equal to "user2"

  Scenario:[Success] As an authenticated user i try to search post with one post by page
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[post]=id,title&items=1"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "items" should have 1 elements
    And the JSON node "items[0].id" should be equal to "post1"
    And the JSON node "items[0].title" should be equal to "titre du post1"
    And the JSON node "nbItems" should exist
    And the JSON node "nbItems" should be equal to "2"
    And the JSON node "nbPages" should exist
    And the JSON node "nbPages" should be equal to "2"
    And the JSON node "links" should exist
    And the JSON node "links" should have 2 elements
    And the JSON node "links.current" should be equal to "http://localhost/api/posts?fields%5Bpost%5D=id,title&items=1&page=1"
    And the JSON node "links.next" should be equal to "http://localhost/api/posts?fields%5Bpost%5D=id,title&items=1&page=2"

  Scenario:[Success] As an authenticated user i try to search post with one post by page and page 2
    When I load the fixture "searchPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[post]=id,title&items=1&page=2"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "items" should have 1 elements
    And the JSON node "items[0].id" should be equal to "post2"
    And the JSON node "items[0].title" should be equal to "titre du post2"
    And the JSON node "nbItems" should exist
    And the JSON node "nbItems" should be equal to "2"
    And the JSON node "nbPages" should exist
    And the JSON node "nbPages" should be equal to "2"
    And the JSON node "links" should have 2 elements
    And the JSON node "links.previous" should be equal to "http://localhost/api/posts?fields%5Bpost%5D=id,title&items=1&page=1"
    And the JSON node "links.current" should be equal to "http://localhost/api/posts?fields%5Bpost%5D=id,title&items=1&page=2"

  Scenario:[Success] As an authenticated user i try to search post without post in database
    When I load the fixture "searchPostWithoutPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "GET" request to "/api/posts?fields[post]=id,title&items=1"
    Then the response should be in JSON
    And the response status code should be 200
    And the JSON node "items" should exist
    And the JSON node "nbItems" should be equal to "0"
    And the JSON node "nbPages" should be equal to "1"
    And the JSON node "links.current" should exist
    And the JSON node "links.current" should be equal to "http://localhost/api/posts?fields%5Bpost%5D=id,title&items=1&page=1"