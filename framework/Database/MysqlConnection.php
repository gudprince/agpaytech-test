<?php

namespace Framework\Database;

use PDO;

class MysqlConnection
{
     public function connect()
     {    
          $host = $_ENV['DB_HOST'];
          $userName = $_ENV['DB_USERNAME'];
          $dbName = $_ENV['DB_DATABASE'];
          $password = $_ENV['DB_PASSWORD'];
          $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8", $userName, $password);
          $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          return $pdo;
     }
}
