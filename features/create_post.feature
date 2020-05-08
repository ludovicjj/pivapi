@api
@api_create_post
Feature: As an authenticated user i must create post

  Scenario:[Fail] Try to create post while not being authenticated
    When I send a "POST" request to "/api/posts" with body:
    """
    {
      "title": "title test",
      "abstract": "shot description",
      "content": "content of this post"
    }
    """
    Then the response should be in JSON
    And the response status code should be 401

  Scenario:[Fail] Try to create post while being authenticated and invalid payload
    When I load the fixture "createPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts" with body:
      """
      {
        "title": "",
        "abstract": "",
        "content": ""
      }
      """
    Then the response should be in JSON
    And the response status code should be 400
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 3 elements
    And the JSON node "errors.title[0]" should be equal to "Le champs titre est obligatoire"
    And the JSON node "errors.abstract[0]" should be equal to "Le champs extrait est obligatoire"
    And the JSON node "errors.content[0]" should be equal to "Le champs contenu est obligatoire"

  Scenario:[Fail] Try to create post while being authenticated and not available title
    When I load the fixture "createPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts" with body:
      """
      {
        "title": "un titre pour les tests",
        "abstract": "mon extrait",
        "content": "mon contenu"
      }
      """
    Then the response should be in JSON
    And the response status code should be 400
    And the JSON node "errors" should exist
    And the JSON node "errors" should have 1 element
    And the JSON node "errors.title[0]" should be equal to "Il existe déja un article avec ce titre"

  Scenario:[Success] Try to create post while being authenticated and valid payload
    When I load the fixture "createPost" in "post" folder
    And I am connected with email "user1@contact.fr"
    And I send a "POST" request to "/api/posts" with body:
      """
      {
        "title": "un autre titre pour les tests",
        "abstract": "mon super extrait",
        "content": "mon super contenu"
      }
      """
    Then the response should be in JSON
    And the response status code should be 201
    And the response should contain the id of a post existing in database
    And post with id into the response should have "title" equal to "un autre titre pour les tests"
    And post with id into the response should have "abstract" equal to "mon super extrait"
    And post with id into the response should have "content" equal to "mon super contenu"