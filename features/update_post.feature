@api
@api_update_post
Feature: As an authenticated user i must update post

  Scenario:[Fail] Try to update post while not being authenticated
    When I send a "POST" request to "/api/posts/1" with body:
    """
    {
      "title": "title test",
      "abstract": "shot description",
      "content": "content of this post"
    }
    """
    Then the response should be in JSON
    And the response status code should be 401
    And the JSON node "error" should be equal to "Authentication Required"

  Scenario:[Fail] As authenticated try to update not exist post
    When I load the fixture "updatePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts/post9999" with body:
      """
      {
        "title": "",
        "abstract": "",
        "content": ""
      }
      """
    Then the response should be in JSON
    And the response status code should be 404
    And the JSON node "errors" should exist
    And the JSON node "errors" should be equal to "Post with id post9999 not found"

  Scenario:[Fail] As authenticated try to update post with an existing title
    When I load the fixture "updatePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts/post1" with body:
      """
      {
        "title": "titre du post2",
        "abstract": "un extrait modifié",
        "content": "un contenu modifié"
      }
      """
    Then the response status code should be 400
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors.title[0]" should be equal to "Il existe déja un article avec ce titre"

  Scenario:[Fail] As authenticated try to update post with invalid payload
    When I load the fixture "updatePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts/post1" with body:
      """
      {
        "title": "",
        "abstract": "",
        "content": ""
      }
      """
    Then the response status code should be 400
    And the JSON node "errors" should exist
    And the JSON node "errors.title[0]" should be equal to "Le champs titre est obligatoire"
    And the JSON node "errors.abstract[0]" should be equal to "Le champs extrait est obligatoire"
    And the JSON node "errors.content[0]" should be equal to "Le champs contenu est obligatoire"

  Scenario:[Success] As authenticated try to update post with valid payload and new title
    When I load the fixture "updatePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts/post1" with body:
      """
      {
        "title": "mon nouveau titre",
        "abstract": "mon nouveau extrait",
        "content": "mon nouveau contenu"
      }
      """
    Then the response should be in JSON
    And the response status code should be 200
    And post with id "post1" should have "title" equal to "mon nouveau titre"
    And post with id "post1" should have "abstract" equal to "mon nouveau extrait"
    And post with id "post1" should have "content" equal to "mon nouveau contenu"

  Scenario:[Success] As authenticated try to update post with valid payload and same title
    When I load the fixture "updatePost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts/post1" with body:
      """
      {
        "title": "titre du post1",
        "abstract": "mon nouveau extrait",
        "content": "mon nouveau contenu"
      }
      """
    Then the response should be in JSON
    And the response status code should be 200
    And post with id "post1" should have "title" equal to "titre du post1"
    And post with id "post1" should have "abstract" equal to "mon nouveau extrait"
    And post with id "post1" should have "content" equal to "mon nouveau contenu"