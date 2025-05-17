<?php

    class Consultation {
        public $consultation_id;
        public $teacher_id;
        public $group_id;
        public $student_id;
        public $consultation_date;
        public $is_present;
        public $is_completed;

        public function __construct($params = null) {
            if ($params !== null) {
                foreach ($params as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }

        // Получить все консультации
        public static function Get() {
            global $db_connection;
            $items = [];
            $result = mysqli_query($db_connection, "SELECT * FROM Consultations");
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Consultation((object)$row);
            }
            return $items;
        }

        // Добавить запись
        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Consultations (
                        teacher_id, group_id, student_id, consultation_date, is_present, is_completed
                    ) VALUES (
                        '$this->teacher_id', '$this->group_id', '$this->student_id',
                        '$this->consultation_date', '$this->is_present', '$this->is_completed'
                    )";
            return mysqli_query($db_connection, $query);
        }

        // Обновить запись
        public function Update() {
            global $db_connection;
            $query = "UPDATE Consultations SET
                        teacher_id = '$this->teacher_id',
                        group_id = '$this->group_id',
                        student_id = '$this->student_id',
                        consultation_date = '$this->consultation_date',
                        is_present = '$this->is_present',
                        is_completed = '$this->is_completed'
                    WHERE consultation_id = $this->consultation_id";
            return mysqli_query($db_connection, $query);
        }

        // Удалить запись
        public function Delete() {
            global $db_connection;
            $query = "DELETE FROM Consultations WHERE consultation_id = $this->consultation_id";
            return mysqli_query($db_connection, $query);
        }
    }
?>
