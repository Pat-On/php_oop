<?php


class User extends Db_object
{
    protected static $db_table = "users";
    protected static $db_table_fields = array('username', 'password', 'firstname', 'lastname');


    public $id;
    public $username;
    public $password;
    public $firstname;
    public $lastname;







    public static function verify_user($username, $password)
    {
        global $database;

        $username = $database->escape_string($username);
        $password = $database->escape_string($password);

        $sql = "SELECT * FROM " . self::$db_table . " WHERE ";
        $sql .= "username = '{$username}' ";
        // hashing?
        $sql .= "AND password = '{$password}' ";
        // ???
        $sql .= "LIMIT 1";

        $the_result_array = self::find_this_query($sql);

        // ternary version in php
        // return !empty($the_result_array) ? array_shift($the_result_array) : false;
        // long syntax in php
        if (!empty($the_result_array)) {
            $first_item = array_shift($the_result_array);
            return $first_item;
        } else {
            return false;
        }
    }

    protected function properties()
    {
        // return get_object_vars($this);

        $properties = array();

        foreach (self::$db_table_fields as $db_field) {

            if (property_exists($this, $db_field)) {

                $properties[$db_field] = $this->$db_field;
            }
        }

        return $properties;
    }

    protected function clean_properties()
    {
        global $database;

        $clean_properties = array();

        foreach ($this->properties() as $key => $value) {
            $clean_properties[$key] = $database->escape_string($value);
        }

        return $clean_properties;
    }


    public function save()
    {
        return isset($this->id) ? $this->update() : $this->create();
    }

    public function create()
    {
        global $database;

        $properties = $this->clean_properties();

        // https://www.php.net/manual/en/function.implode.php
        // https://www.w3schools.com/php/func_string_implode.asp 
        $sql = "INSERT INTO " . self::$db_table .  "(" . implode(',', array_keys($properties)) . ")";
        $sql .= "VALUES('" .  implode("','", array_values($properties))   . "')";

        // echo $sql;
        // users(id,username,password,firstname,lastname)VALUES('','static_user','static','first_name','last_name')
        // after upgrade
        // users(username,password,firstname,lastname)VALUES('static_user','static','first_name','last_name')

        $query_status =  $database->query($sql);
        if ($query_status) {

            $this->id = $database->the_insert_id();

            return true;
        } else {
            return false;
        }
    } // create method


    public function update()
    {
        global $database;

        $properties = $this->clean_properties();

        $properties_pairs = array();

        foreach ($properties as $key => $value) {
            $properties_pairs[] = "{$key}='{$value}'";
        }

        $sql = "UPDATE " . self::$db_table .  " SET ";

        $sql .= implode(", ", $properties_pairs);


        // $sql .= "username= '" . $database->escape_string(($this->username)) . "', ";
        // $sql .= "password= '" . $database->escape_string(($this->password)) . "', ";
        // $sql .= "firstname= '" . $database->escape_string(($this->firstname))  . "', ";
        // $sql .= "lastname= '" . $database->escape_string(($this->lastname))  . "' ";
        $sql .= " WHERE id= " .   $database->escape_string(($this->id));

        // echo $sql;
        // UPDATE users SET username='Whatever 2', password='static', firstname='first_name', lastname='last_name' WHERE id= 14
        $query_status =  $database->query($sql);

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }



    public function delete()
    {
        global $database;

        $sql = "DELETE FROM " . self::$db_table .  " ";
        $sql .= "WHERE id= " .  $database->escape_string(($this->id)) . " ";
        $sql .= "LIMIT 1";

        $query_status =  $database->query($sql);
        // if ($query_status) {

        //     return true;
        // } else {
        //     return false;
        // }

        return (mysqli_affected_rows($database->connection) == 1) ? true : false;
    }
} // end of the user class
