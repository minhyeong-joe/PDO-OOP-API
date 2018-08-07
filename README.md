### PHP PDO Object-Oriented Rest API

Backend API Test: [Postman](https://www.getpostman.com/)

Root URL: https://test-api-php.herokuapp.com/api/posts/post_api.php

Must provide `?key=[your_custom_key]`

All entries are associated with your own key.

## Requests

- [GET]
[Use `GET` request method]

  - Fetch all entries associated with key=123
```
https://test-api-php.herokuapp.com/api/posts/post_api.php?key=123
```

  - Fetch single entry with id 1
```
https://test-api-php.herokuapp.com/api/posts/post_api.php?key=123&id=1
```

- [POST]
[Use `POST` request method]

  - Create an entry under key=123
```
https://test-api-php.herokuapp.com/api/posts/post_api.php?key=123

POST Params:
{
  "title" : "your title",
  "author" : "your name",
  "content" : "body content"
}
```

- [PUT]
[Use `PUT` request method]

  - Update an entry with id = 1
```
https://test-api-php.herokuapp.com/api/posts/post_api.php?key=123&id=1

PUT Params:
{
  "title" : "your title",
  "author" : "your name",
  "content" : "body content"
}
```

- [DELETE]
[Use `DELETE` request method]

  - Delete an entry with id = 1
```
https://test-api-php.herokuapp.com/api/posts/post_api.php?key=123&id=1
```
