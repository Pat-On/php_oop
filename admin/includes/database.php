<?php

require_once('new_config.php');

class Database
{
    public $connection;

    // automatic connection
    function __construct()
    {
        $this->open_db_connection();
    }

    public function open_db_connection()
    {

        // $this->connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        // OOP:
        // - https://www.php.net/manual/en/mysqli.construct.php
        // - https://www.w3schools.com/php/func_mysqli_connect.asp 
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);


        if ($this->connection->connect_errno) {
            die("Database connection failed badly " . $this->connection->connect_error);
        } else {
            // echo "Connection to db has been established";
        }
        // if (mysqli_connect_errno()) {
        //     die("Database connection failed badly " . mysqli_error($this->connection));
        // } else {
        //     echo "Connection to db has been established";
        // }
    }

    public function query($sql)
    {
        // $result = mysqli_query($this->connection, $sql);
        $result = $this->connection->query($sql);
        $this->confirm_query($result);
        return $result;
    }

    private function confirm_query($result)
    {
        if (!$result) {
            die("Query failed" . $this->connection->error);
        }
    }

    public function escape_string($string)
    {
        // $escaped_string = mysqli_real_escape_string($this->connection, $string);
        $escaped_string = $this->connection->real_escape_string($string);
        return $escaped_string;
    }


    public function the_insert_id()
    {
        // return $this->connection->insert_id;
        return mysqli_insert_id($this->connection);
    }
}

// instance of the class
$database = new Database();

// starting connection <- manual
// $database->open_db_connection();