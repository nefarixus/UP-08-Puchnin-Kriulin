<?php

    class Discipline {
        public $discipline_id;
        public $discipline_name;

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
            $result = mysqli_query($db_connection, "SELECT * FROM Disciplines ORDER BY discipline_id ASC");
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Discipline((object)$row);
            }
            return $items;
        }

        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Disciplines (discipline_name) VALUES ('$this->discipline_name')";
            return mysqli_query($db_connection, $query);
        }

        public function Update() {
            global $db_connection;
            $query = "UPDATE Disciplines SET discipline_name = '$this->discipline_name' WHERE discipline_id = $this->discipline_id";
            return mysqli_query($db_connection, $query);
        }

        public function Delete() {
            global $db_connection;

            $checkUsage = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Teacher_Workload WHERE discipline_id = $this->discipline_id");
            $used = mysqli_fetch_assoc($checkUsage)['count'] > 0;

            if ($used) {
                return false;
            }

            return mysqli_query($db_connection, "DELETE FROM Disciplines WHERE discipline_id = $this->discipline_id");
        }

        public function validate() {
            $errors = [];

            if (empty($this->discipline_name)) {
                $errors[] = 'Название дисциплины обязательно';
            }

            return $errors;
        }
    }
?>