<?php

    class Absence {
        public $absence_id;
        public $lesson_id;
        public $student_id;
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

        public static function Get() {
            global $db_connection;
            $items = [];
            $result = mysqli_query($db_connection, "SELECT * FROM Absences");
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
                        '$this->lesson_id', '$this->student_id',
                        '$this->minutes_missed', '$this->explanatory_note_path'
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
    }
?>