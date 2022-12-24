<?php


class User
{
    static function find_all_users()
    {
        global $database;
        $result_set = $database->query("SELECT * FROM users");

        return $result_set;
    }

    static function find_user_by_id($id)
    {
        global $database;
        // LIMIT 1 - tutor said that is good practice hmm is it really?
        $result_set = $database->query("SELECT * FROM users WHERE id = $id LIMIT 1");

        $found_user = mysqli_fetch_array($result_set);

        return $found_user;
    }
}
