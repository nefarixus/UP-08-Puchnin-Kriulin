<?php

    class Student {
        public $student_id;
        public $last_name;
        public $first_name;
        public $middle_name;
        public $group_id;
        public $dismissal_date;
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

            $query = "
                SELECT s.*, g.group_name 
                FROM Students s
                LEFT JOIN StudentGroups g ON s.group_id = g.group_id
            ";

            $result = mysqli_query($db_connection, $query);
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
                        '$this->last_name', '$this->first_name',
                        '$this->middle_name', '$this->group_id',
                        " . ($this->dismissal_date ? "'$this->dismissal_date'" : "NULL") . "
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
                        dismissal_date = " . ($this->dismissal_date ? "'$this->dismissal_date'" : "NULL") . "
                    WHERE student_id = $this->student_id";

            return mysqli_query($db_connection, $query);
        }

        public function Delete() {
            global $db_connection;

            // Проверяем связи
            $hasAbsences = mysqli_query($db_connection, "SELECT * FROM Absences WHERE student_id = $this->student_id");
            $hasGrades = mysqli_query($db_connection, "SELECT * FROM Grades WHERE student_id = $this->student_id");

            if (mysqli_num_rows($hasAbsences) > 0 || mysqli_num_rows($hasGrades) > 0) {
                return false;
            }

            $query = "DELETE FROM Students WHERE student_id = $this->student_id";
            return mysqli_query($db_connection, $query);
        }

        public function validate() {
            $errors = [];

            if (empty($this->last_name)) {
                $errors[] = 'Фамилия обязательна';
            } elseif (!preg_match("/^[а-яА-ЯёЁa-zA-Z\- ]+$/u", $this->last_name)) {
                $errors[] = 'Фамилия должна содержать только буквы';
            }

            if (empty($this->first_name)) {
                $errors[] = 'Имя обязательно';
            } elseif (!preg_match("/^[а-яА-ЯёЁa-zA-Z\- ]+$/u", $this->first_name)) {
                $errors[] = 'Имя должно содержать только буквы';
            }

            if (!empty($this->middle_name) && !preg_match("/^[а-яА-ЯёЁa-zA-Z\- ]+$/u", $this->middle_name)) {
                $errors[] = 'Отчество должно содержать только буквы';
            }

            if (!empty($this->dismissal_date) && !preg_match("/^\d{4}-\d{2}-\d{2}$/", $this->dismissal_date)) {
                $errors[] = 'Дата отчисления должна быть в формате YYYY-MM-DD';
            }

            return $errors;
        }
    }
?>