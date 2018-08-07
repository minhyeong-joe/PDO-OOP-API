<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');
  header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
  header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

  include_once('../../config/Database.php');
  include_once('../../models/posts.php');

  // Fetch Request
  $METHOD = $_SERVER['REQUEST_METHOD'];
  $REQUEST_DOMAIN = $_SERVER['SERVER_NAME'];

  // Declare response array
  $response_arr = array();
  $response_arr['data'] = array(); // actual array with objects of data to return

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();

  // Instantiate Posts object
  $posts = new Post($db);

  // Check if request has KEY param
  if(!isset($_GET["key"])) {
    $response_arr = array(
      "success" => false,
      "message" => "Must provide a key value."
    );
    echo json_encode($response_arr);
    exit();
  } else {
    // KEY param for user-specific posts
    $posts->user_key = $_GET["key"];
  }

  // determine action based on request method
  switch($METHOD) {
    case "GET":
      // read posts
      if(!isset($_GET['id'])) {
        // fetch all posts
        $result = $posts->read();
        $num = $result->rowCount();

        if ($num > 0) {
          // Post array
          while($row = $result->fetch()) {
            extract($row);
            $post_item = array(
              "id" => $id,
              "title" => $title,
              "author" => $author,
              "content" => $content,
              "user_key" => $user_key,
              "timestamp" => $time_stamp
            );

            array_push($response_arr['data'], $post_item);
          }
          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "data" => $response_arr['data']
          );
        } else {
          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "No Data Entry."
          );
        }
      } else {
        // fetch single post
        $posts->id = (int)$_GET['id'];

        $result = $posts->read_single();
        $num = $result->rowCount();

        if ($num > 0) {
          // Post array
          $row = $result->fetch();
          extract($row);
          $post_item = array(
            "id" => $id,
            "title" => $title,
            "author" => $author,
            "content" => $content,
            "user_key" => $user_key,
            "timestamp" => $time_stamp
          );
          array_push($response_arr['data'], $post_item);

          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "data" => $response_arr['data']
          );
        } else {
          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "No Matching Data."
          );
        }
      }

      break;

    case "POST":
    // create post
      $data = json_decode(file_get_contents("php://input"));

      $posts->title = $data->title;
      $posts->content = $data->content;
      $posts->author = $data->author;

      if($newId = $posts->create()) {
        // fetch newly created data
        $posts->id = $newId;
        $result = $posts->read_single();
        $row = $result->fetch();
        extract($row);
        $post_item = array(
          "id" => $id,
          "title" => $title,
          "author" => $author,
          "content" => $content,
          "user_key" => $user_key,
          "timestamp" => $time_stamp
        );

        array_push($response_arr['data'], $post_item);

        $response_arr = array(
          "success" => true,
          "key" => $posts->user_key,
          "method" => $METHOD,
          "message" => "Post Created.",
          "data" => $response_arr['data'],
        );
      } else {
        $response_arr = array(
          "success" => false,
          "key" => $posts->user_key,
          "method" => $METHOD,
          "message" => "Post Not Created."
        );
      }

      break;
    case "PUT":
    // update post
      if(!isset($_GET['id'])) {
        // ID must be set, invalidation
        $response_arr = array(
          "success" => false,
          "key" => $posts->user_key,
          "method" => $METHOD,
          "message" => "Must Provide ID."
        );
      } else {
        $posts->id = $_GET['id'];

        // fetch old data
        $result = $posts->read_single();
        $row = $result->fetch();
        extract($row);
        $old_post_item = array(
          "id" => $id,
          "title" => $title,
          "author" => $author,
          "content" => $content,
          "user_key" => $user_key,
          "timestamp" => $time_stamp
        );

        $data = json_decode(file_get_contents("php://input"));

        $posts->title = $data->title;
        $posts->content = $data->content;
        $posts->author = $data->author;

        if($posts->update()) {
          // if update was successful
          // fetch updated post data
          $result = $posts->read_single();
          $row = $result->fetch();
          extract($row);
          $updated_post_item = array(
            "id" => $id,
            "title" => $title,
            "author" => $author,
            "content" => $content,
            "user_key" => $user_key,
            "timestamp" => $time_stamp
          );

          // populate response
          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "Update was successful.",
            "original" => $old_post_item,
            "updated" => $updated_post_item
          );

        } else {
          // update failed to execute
          $response_arr = array(
            "success" => false,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "Update failed."
          );
        }
      }
      break;
    case "DELETE":
    // delete post
      if(!isset($_GET['id'])) {
        // id must be provided
        $response_arr = array(
          "success" => false,
          "key" => $posts->user_key,
          "method" => $METHOD,
          "message" => "Must Provide ID."
        );
      } else {
        $posts->id = $_GET['id'];

        if($posts->delete()) {
          // delete successful
          $response_arr = array(
            "success" => true,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "Successfully removed the post."
          );
        } else {
          // delete failed
          $response_arr = array(
            "success" => false,
            "key" => $posts->user_key,
            "method" => $METHOD,
            "message" => "Failed to delete the post."
          );
        }
      }
      break;
    default:
      $response_arr = array(
        "success" => false,
        "message" => "Invalid Request Method."
      );
  }


  echo json_encode($response_arr);
