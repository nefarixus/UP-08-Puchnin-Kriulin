<?php

    class Discipline {
        public $discipline_id;
        public $discipline_name;
        public $group_id;
        public $group_name;
        public $lesson_count;
        public $total_hours;

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
            $result = mysqli_query($db_connection, "SELECT * FROM Disciplines");
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

            $programQuery = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Discipline_Programs WHERE discipline_id = $this->discipline_id");
            $programs = mysqli_fetch_assoc($programQuery)['count'];

            $workloadQuery = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Teacher_Workload WHERE discipline_id = $this->discipline_id");
            $workloads = mysqli_fetch_assoc($workloadQuery)['count'];

            if ($programs > 0 || $workloads > 0) {
                return false;
            }

            $query = "DELETE FROM Disciplines WHERE discipline_id = $this->discipline_id";
            return mysqli_query($db_connection, $query);
        }

        public function validate() {
            $errors = [];

            if (empty($this->discipline_name)) {
                $errors[] = 'Название дисциплины обязательно';
            } elseif (!preg_match("/^[а-яА-ЯёЁa-zA-Z0-9\- ]+$/u", $this->discipline_name)) {
                $errors[] = 'Название дисциплины должно содержать только буквы, цифры, дефис или пробел';
            }

            return $errors;
        }
    }
?>