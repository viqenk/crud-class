<?php

/**
 * This class provides basic CRUD
 * functionality for MySQL databases
 * easily implemented with static methods
 *
 * @author Victor H Cuenca E
 * @version 1.0, 20/09/2016
 */

class DB {

  // define server variables for connection
  protected static $server   = 'localhost';
  protected static $user     = 'root';
  protected static $password = 'root';
  protected static $dbname   = 'database';
  protected static $conn;

  /*
   * connects to the database taking
   * the protected properties previously defined
   */

  public static function connect() {
    self::$conn = new mysqli(self::$server, self::$user, self::$password, self::$dbname);
    if (self::$conn->connect_error) {
      die('Connection failed: ' . self::$conn->connect_error);
    }
    mysqli_set_charset(self::$conn,'utf8');
  }

  /*
   * @desc creates a new record into the desire database
   * @param string $database, string $columns, string $values
   * @return string message for success or failure
   */

  public static function create($database, $columns, $values) {
    $sql = "INSERT INTO $database ($columns) VALUES ($values)";

    if (self::$conn->query($sql) === TRUE) {
      $last_id = $conn->insert_id;
      echo "New record created: $database [ ID: $last_id ]";
    } else {
      echo "Error: " . $sql . "<br>" . self::$conn->error;
    }
  }

  /*
   * @desc reads data from the database based on the parameters passed
   * @param string $database, string $columns, string $condition
   * @return string message for failure or JSON formatted data for success
   */

  public static function read($database, $columns, $condition) {
    $sql = "SELECT $columns FROM $database $condition";
    $result = self::$conn->query($sql);

    $data = array();
    if ($result->num_rows > 0) {
      while($row = $result->fetch_assoc()) {
        $data[] = $row;
      }
      echo json_encode($data, JSON_PRETTY_PRINT);
    } else {
      echo "0 results";
    }
  }

  /*
   * @desc updates a record based on the condition passed as a parameter
   * @param string $database, string $data, string $condition
   * @return string message for success or failure
   */

  public static function update($database, $data, $condition) {
    $sql = "UPDATE $database SET $data WHERE $condition";

    if (self::$conn->query($sql) === TRUE) {
      echo "Record updated: $database";
    } else {
      echo "Error updating record: " . self::$conn->error;
    }
  }

  /*
   * @desc deletes a record based on the condition passed as a parameter
   * @param string $database, string $condition
   * @return string message for success or failure
   */

  public static function delete($database, $condition) {
    $sql = "DELETE FROM $database WHERE $condition";

    if (self::$conn->query($sql) === TRUE) {
      echo "Record deleted from $database";
    } else {
      echo "Error deleting record: " . self::$conn->error;
    }
  }

  // ends the connection
  public static function close() {
    self::$conn->close();
  }

  /*
   * @desc creates a new database
   * @param string $name
   * @return string message for success or failure
   */

  public static function createDatabase($database) {
    $sql = "CREATE DATABASE $database";
    if (self::$conn->query($sql) === TRUE) {
      echo "Database created: $database";
    } else {
      echo "Error creating database: " . self::$conn->error;
    }
  }

  /*
   * @desc creates a new table into the database
   * @param string $table, string $columns
   * @return string message for success or failure
   */

  public static function createTable($table, $columns) {
    $sql = "CREATE TABLE $table (id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, $columns, reg_date TIMESTAMP)";

    if (self::$conn->query($sql) === TRUE) {
      echo "Table created: $table";
    } else {
      echo "Error creating table: " . self::$conn->error;
    }
  }
}

DB::connect();

/*
 * CREATE   =>  DB::create( $database, $columns, $values );
 * READ     =>  DB::read( $database, $columns, $condition );
 * UPDATE   =>  DB::update( $database, $data, $condition );
 * DELETE   =>  DB::delete( $database, $condition );
 * CONNECT  =>  DB::connect();
 * KILL     =>  DB::close();
 * DATABASE =>  DB::createDatabase($database)
 * TABLE    =>  DB::createTable($table, $columns);
 */
?>
