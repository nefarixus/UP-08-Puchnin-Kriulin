<?php

class StudentGroup {
    public $group_id;
    public $group_name;

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
        $result = mysqli_query($db_connection, "SELECT * FROM StudentGroups");
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new StudentGroup((object)$row);
        }
        return $items;
    }

    public function Insert() {
        global $db_connection;
        $query = "INSERT INTO StudentGroups (group_name) VALUES ('$this->group_name')";
        return mysqli_query($db_connection, $query);
    }

    public function Update() {
        global $db_connection;
        $query = "UPDATE StudentGroups SET group_name = '$this->group_name'
                  WHERE group_id = $this->group_id";
        return mysqli_query($db_connection, $query);
    }

    public function Delete() {
        global $db_connection;
        $query = "DELETE FROM StudentGroups WHERE group_id = $this->group_id";
        return mysqli_query($db_connection, $query);
    }
}