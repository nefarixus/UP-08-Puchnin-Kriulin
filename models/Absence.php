<?php
    class Absence {
        public $absence_id;
        public $lesson_id;
        public $student_id;
        public $last_name;
        public $group_name;
        public $minutes_missed;
        public $explanatory_note_path;

        public function __construct($params = null) {
            if ($params !== null) {
                foreach ($params as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }

        public static function Get($filters = []) {
            global $db_connection;
            $items = [];

            // Поддержка фильтрации по student_id или group_id
            $where = '';
            if (!empty($filters['student_id'])) {
                $where .= " AND a.student_id = '" . mysqli_real_escape_string($db_connection, $filters['student_id']) . "'";
            }
            if (!empty($filters['group_id'])) {
                $where .= " AND s.group_id = '" . mysqli_real_escape_string($db_connection, $filters['group_id']) . "'";
            }

            $query = "
                SELECT 
                    a.absence_id,
                    a.lesson_id,
                    a.student_id,
                    a.minutes_missed,
                    a.explanatory_note_path,
                    s.last_name AS student_last_name,
                    g.group_name
                FROM Absences a
                LEFT JOIN Students s ON a.student_id = s.student_id
                LEFT JOIN StudentGroups g ON s.group_id = g.group_id
                WHERE 1=1
                $where
                ORDER BY a.absence_id ASC";

            $result = mysqli_query($db_connection, $query);
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = (object)$row;
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
                        '$this->minutes_missed', 
                        '$this->explanatory_note_path'
                    )";
            return mysqli_query($db_connection, $query);
        }

        public function Update() {
            global $db_connection;
            $query = "UPDATE Absences SET
                        lesson_id = '$this->lesson_id',
                        student_id = '$this->student_id',
                        minutes_missed = '$this->minutes_missed',
                        explanatory_note_path = '$this->explanatory_note_path'
                    WHERE absence_id = $this->absence_id";
            return mysqli_query($db_connection, $query);
        }

        public function Delete() {
            global $db_connection;
            $query = "DELETE FROM Absences WHERE absence_id = $this->absence_id";
            return mysqli_query($db_connection, $query);
        }

        public function validate() {
            $errors = [];

            if (empty($this->student_id)) {
                $errors[] = 'ID студента обязательно';
            }

            if (empty($this->lesson_id)) {
                $errors[] = 'ID занятия обязательно';
            }

            if (empty($this->minutes_missed) || !is_numeric($this->minutes_missed)) {
                $errors[] = 'Количество минут должно быть числом';
            }

            return $errors;
        }
    }
?>