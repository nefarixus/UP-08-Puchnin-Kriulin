<?php

    class Absence {
        public $absence_id;
        public $lesson_id;
        public $student_id;
        public $minutes_missed;
        public $explanatory_note_path;
        public $last_name;
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
                SELECT 
                    a.absence_id,
                    a.lesson_id,
                    a.student_id,
                    a.minutes_missed,
                    a.explanatory_note_path,
                    s.last_name,
                    g.group_name
                FROM Absences a
                LEFT JOIN Students s ON a.student_id = s.student_id
                LEFT JOIN StudentGroups g ON s.group_id = g.group_id
                WHERE 1=1
            ";

            $result = mysqli_query($db_connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Absence((object)$row);
            }

            return $items;
        }

        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Absences (
                        lesson_id, student_id, minutes_missed, explanatory_note_path
                    ) VALUES (
                        '$this->lesson_id',
                        '$this->student_id',
                        " . ($this->minutes_missed ? "'$this->minutes_missed'" : 'NULL') . ",
                        " . ($this->explanatory_note_path ? "'$this->explanatory_note_path'" : 'NULL') . "
                    )";

            return mysqli_query($db_connection, $query);
        }

        public function Update() {
            global $db_connection;
            $query = "UPDATE Absences SET
                        lesson_id = " . ($this->lesson_id ? "'$this->lesson_id'" : 'NULL') . ",
                        student_id = " . ($this->student_id ? "'$this->student_id'" : 'NULL') . ",
                        minutes_missed = " . ($this->minutes_missed ? "'$this->minutes_missed'" : 'NULL') . ",
                        explanatory_note_path = " . ($this->explanatory_note_path ? "'$this->explanatory_note_path'" : 'NULL') . "
                    WHERE absence_id = $this->absence_id";

            return mysqli_query($db_connection, $query);
        }

        public function Delete() {
            global $db_connection;

            // Проверка на связи
            $checkQuery = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Grades WHERE absence_id = $this->absence_id");
            $used = mysqli_fetch_assoc($checkQuery)['count'] > 0;

            if ($used) {
                return false;
            }

            return mysqli_query($db_connection, "DELETE FROM Absences WHERE absence_id = $this->absence_id");
        }

        public function validate() {
            $errors = [];

            if (!is_numeric($this->student_id)) {
                $errors[] = 'ID студента обязателен и должен быть числом';
            }

            if (!is_numeric($this->lesson_id)) {
                $errors[] = 'ID занятия обязателен и должен быть числом';
            }

            if ($this->minutes_missed !== null && !is_numeric($this->minutes_missed)) {
                $errors[] = 'Минуты должны быть числом или пустыми';
            }

            if ($this->explanatory_note_path !== null && !is_string($this->explanatory_note_path)) {
                $errors[] = 'Путь к объяснительной записке должен быть строкой';
            }

            return $errors;
        }
    }
?>