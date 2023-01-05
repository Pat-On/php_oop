<?php


// https://www.php.net/manual/en/language.oop5.late-static-bindings.php

// https://stackoverflow.com/questions/1912902/what-exactly-are-late-static-bindings-in-php
class Db_object
{
    // TODO: READ more
    // it is working because of the static:$db_table 
    // protected static $db_table = "";

    static function find_all()
    {
        // https://www.geeksforgeeks.org/what-is-late-static-bindings-in-php/
        // https://www.php.net/manual/en/language.oop5.late-static-bindings.php
        // one way to fix bugs
        // $this->find_by_query.....
        // but then you can not use static method




        return static::find_by_query("SELECT * FROM " . static::$db_table . " ");
    }

    static function find_by_id($id)
    {
        global $database;
        $the_result_array = static::find_by_query("SELECT * FROM " . static::$db_table . " WHERE id = $id LIMIT 1");

        // ternary version in php
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }
    public static function find_by_query($sql)
    {
        global $database;
        $result_set = $database->query($sql);
        $the_object_array = array();

        while ($row = mysqli_fetch_array($result_set)) {
            $the_object_array[] = static::instantiation($row);
        }

        return  $the_object_array;
    }



    public static function instantiation($the_record)
    {
        // child class instance https://www.php.net/manual/en/language.oop5.late-static-bindings.php
        $calling_class = get_called_class();

        // parentheses?
        $the_object = new $calling_class;

        foreach ($the_record as $attribute => $value) {
            if ($the_object->has_the_attribute($attribute)) {
                $the_object->$attribute = $value;
            }
        }


        return $the_object;
    }


    private function has_the_attribute($attribute)
    {
        $object_properties = get_object_vars($this);

        return array_key_exists($attribute, $object_properties);
    }

    protected function properties()
    {
        // return get_object_vars($this);

        $properties = array();

        foreach (static::$db_table_fields as $db_field) {

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
        $sql = "INSERT INTO " . static::$db_table .  "(" . implode(',', array_keys($properties)) . ")";
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

        $sql = "UPDATE " . static::$db_table .  " SET ";

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

        $sql = "DELETE FROM " . static::$db_table .  " ";
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
}
