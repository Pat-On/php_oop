<?php

class Db_object
{
    protected static $db_table = "";

    static function find_all()
    {
        // https://www.geeksforgeeks.org/what-is-late-static-bindings-in-php/
        // https://www.php.net/manual/en/language.oop5.late-static-bindings.php
        // one way to fix bugs
        // $this->find_this_query.....
        // but then you can not use static method




        return static::find_this_query("SELECT * FROM " . static::$db_table . " ");
    }

    static function find_by_id($id)
    {
        global $database;
        $the_result_array = static::find_this_query("SELECT * FROM " . static::$db_table . " WHERE id = $id LIMIT 1");

        // ternary version in php
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
    }
    public static function find_this_query($sql)
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
}
