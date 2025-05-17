<?php

class Teacher {
    public $teacher_id;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $login;
    public $password;

    public function __construct($params = null) {
        if ($params !== null) {
            foreach ($params as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->{$key} = $value;
                }
            }
        }
    }

    public static function Get() {
        global $db_connection;
        $items = [];
        $result = mysqli_query($db_connection, "SELECT * FROM Teachers");
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new Teacher((object)$row);
        }
        return $items;
    }

    public function Insert() {
        global $db_connection;
        $query = "INSERT INTO Teachers (
                    last_name, first_name, middle_name, login, password
                  ) VALUES (
                    '$this->last_name', '$this->first_name', '$this->middle_name',
                    '$this->login', '$this->password'
                  )";
        return mysqli_query($db_connection, $query);
    }

    public function Update() {
        global $db_connection;
        $query = "UPDATE Teachers SET
                    last_name = '$this->last_name',
                    first_name = '$this->first_name',
                    middle_name = '$this->middle_name',
                    login = '$this->login',
                    password = '$this->password'
                  WHERE teacher_id = $this->teacher_id";
        return mysqli_query($db_connection, $query);
    }

    public function Delete() {
        global $db_connection;
        $query = "DELETE FROM Teachers WHERE teacher_id = $this->teacher_id";
        return mysqli_query($db_connection, $query);
    }
}
?>