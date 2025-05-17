<?php

class Student {
    public $student_id;
    public $last_name;
    public $first_name;
    public $middle_name;
    public $group_id;
    public $dismissal_date;

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
        $result = mysqli_query($db_connection, "SELECT * FROM Students");
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new Student((object)$row);
        }
        return $items;
    }

    public function Insert() {
        global $db_connection;
        $query = "INSERT INTO Students (
                    last_name, first_name, middle_name, group_id, dismissal_date
                  ) VALUES (
                    '$this->last_name', '$this->first_name', '$this->middle_name',
                    '$this->group_id', '$this->dismissal_date'
                  )";
        return mysqli_query($db_connection, $query);
    }

    public function Update() {
        global $db_connection;
        $query = "UPDATE Students SET
                    last_name = '$this->last_name',
                    first_name = '$this->first_name',
                    middle_name = '$this->middle_name',
                    group_id = '$this->group_id',
                    dismissal_date = '$this->dismissal_date'
                  WHERE student_id = $this->student_id";
        return mysqli_query($db_connection, $query);
    }

    public function Delete() {
        global $db_connection;
        $query = "DELETE FROM Students WHERE student_id = $this->student_id";
        return mysqli_query($db_connection, $query);
    }
}
?>