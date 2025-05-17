<?php

    class Lesson {
        public $lesson_id;
        public $program_id;
        public $group_id;
        public $teacher_id;
        public $lesson_date;
        public $duration_minutes;

        public function __construct($params = null) {
            if ($params !== null) {
                foreach ($params as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }

        // Получить все занятия
        public static function Get() {
            global $db_connection;
            $items = [];
            $result = mysqli_query($db_connection, "SELECT * FROM Lessons");
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Lesson((object)$row);
            }
            return $items;
        }

        // Добавить запись
        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Lessons (
                        program_id, group_id, teacher_id, lesson_date, duration_minutes
                    ) VALUES (
                        '$this->program_id', '$this->group_id',
                        '$this->teacher_id', '$this->lesson_date',
                        '$this->duration_minutes'
                    )";
            return mysqli_query($db_connection, $query);
        }

        // Обновить запись
        public function Update() {
            global $db_connection;
            $query = "UPDATE Lessons SET
                        program_id = '$this->program_id',
                        group_id = '$this->group_id',
                        teacher_id = '$this->teacher_id',
                        lesson_date = '$this->lesson_date',
                        duration_minutes = '$this->duration_minutes'
                    WHERE lesson_id = $this->lesson_id";
            return mysqli_query($db_connection, $query);
        }

        // Удалить запись
        public function Delete() {
            global $db_connection;
            $query = "DELETE FROM Lessons WHERE lesson_id = $this->lesson_id";
            return mysqli_query($db_connection, $query);
        }
    }
?>
