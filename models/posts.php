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
      $query = "SELECT * FROM `$this->table` WHERE `user_key` = :user_key";

      $stmt = $this->conn->prepare($query);

      $stmt->execute([
        "user_key" => $this->user_key
      ]);

      return $stmt;
    }

    // Get Single Post
    public function read_single() {
      $query = "SELECT * FROM `$this->table` WHERE `id` = :id AND `user_key` = :user_key LIMIT 1";

      $stmt = $this->conn->prepare($query);

      $stmt->execute([
        "id" => $this->id,
        "user_key" => $this->user_key,
      ]);

      return $stmt;
    }

    // Write a post
    public function create() {
      $query = "INSERT INTO `$this->table` (title, content, author, user_key) VALUES (:title, :content, :author, :user_key)";

      $stmt = $this->conn->prepare($query);

      if($stmt->execute([
        "title" => $this->title,
        "content" => $this->content,
        "author" => $this->author,
        "user_key" => $this->user_key,
      ])) {
        return $this->conn->lastInsertId();
      }

      return false;
    }

    // Update a post
    public function update() {
      $query = "UPDATE `$this->table` SET title = :title, author = :author, content = :content WHERE id = :id AND user_key = :user_key LIMIT 1";

      $stmt = $this->conn->prepare($query);

      if($stmt->execute([
        "title" => $this->title,
        "author" => $this->author,
        "content" => $this->content,
        "id" => $this->id,
        "user_key" => $this->user_key,
      ])) {
        return true;
      }

      return false;
    }

    // Delete a post
    public function delete() {
      $query = "DELETE FROM `$this->table` WHERE id = :id AND user_key = :user_key";

      $stmt = $this->conn->prepare($query);

      if($stmt->execute([
        "id" => $this->id,
        "user_key" => $this->user_key
      ])) {
        return true;
      }

      return false;
    }

  }
