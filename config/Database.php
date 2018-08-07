<?php

  class Database {
    // DB access info
    // private $host = 'localhost';
    // private $db_name = 'API_test';
    // private $username = 'root';
    // private $password = 'root';
    private $host = 'us-cdbr-iron-east-01.cleardb.net';
    private $db_name = 'heroku_755d278f70276a1';
    private $username = 'bc033c8ea297c6';
    private $password = '5c19c898';
    private $conn;

    // Database connect
    public function connect() {
      $this->conn = null;

      try {
        $this->conn = new PDO('mysql:host='.$this->host.';dbname='.$this->db_name, $this->username, $this->password);
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
      }
      catch(PDOException $e) {
        echo 'Connection Error: ' . $e->getMessage();
      }

      return $this->conn;
    }

  }
