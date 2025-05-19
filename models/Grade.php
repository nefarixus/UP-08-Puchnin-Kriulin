<?php

    class Grade {
        public $grade_id;
        public $lesson_id;
        public $student_id;
        public $grade_value;
        public $color;

        public function __construct($params = null) {
            if ($params !== null) {
                foreach ($params as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }

        // Получить все оценки
        public static function Get() {
            global $db_connection;
            $items = [];
            $result = mysqli_query($db_connection, "SELECT * FROM Grades");
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Grade((object)$row);
            }
            return $items;
        }

        // Добавить оценку
        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Grades (
                        lesson_id, student_id, grade_value
                    ) VALUES (
                        '$this->lesson_id', '$this->student_id',
                        '$this->grade_value'
                    )";
            return mysqli_query($db_connection, $query);
        }

        // Обновить оценку
        public function Update() {
            global $db_connection;
            $query = "UPDATE Grades SET
                        lesson_id = '$this->lesson_id',
                        student_id = '$this->student_id',
                        grade_value = '$this->grade_value'
                    WHERE grade_id = $this->grade_id";
            return mysqli_query($db_connection, $query);
        }

        // Удалить оценку
        public function Delete() {
            global $db_connection;
            $query = "DELETE FROM Grades WHERE grade_id = $this->grade_id";
            return mysqli_query($db_connection, $query);
        }
    }
?>
