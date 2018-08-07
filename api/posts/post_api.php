<?php
  // Headers
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json');

  include_once('../../config/Database.php');
  include_once('../../models/posts.php');

  // Fetch Request
  $METHOD = $_SERVER['REQUEST_METHOD'];
  $REQUEST_DOMAIN = $_SERVER['HTTP_HOST'];

  // Declare response array
  $result_arr = array();
  $result_arr['data'] = array(); // actual array with objects of data to return
  $result_arr['response'] = array(); // response status, error message, etc.

  // Instantiate Database and connect
  $database = new Database();
  $db = $database->connect();


  $result_arr = array();
  $result_arr['data'] = array();
  $result_arr['response'] = array(
    "success" => true,
    "Error code" => 0,
    "method" => $METHOD,
    "Request Host" => $REQUEREQUEST_DOMAIN
  );

  echo json_encode($result_arr);
