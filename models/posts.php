<?php

  class Post {
    // DB
    private $conn;
    private $table = 'posts';

    // Post table items
    public $id;
    public $title;
    public $author;
    public $content;
    public $user_key;
    public $timestamp;

    // Constructor to connect
    public function __construct($db) {
      $this->conn = $db;
    }

    // Get Posts
    public function read() {
      $query = "SELECT * FROM $table";

      $stmt = $this->conn->prepare($query);

      $stmt->execute();

      return $stmt;
    }
  }
