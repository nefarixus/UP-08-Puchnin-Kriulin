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

            $query = "SELECT * FROM Lessons";
            $result = mysqli_query($db_connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new self((object)$row);
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

        // Удалить запись с проверкой связей
        public function Delete() {
            global $db_connection;

            // Проверяем наличие связанных пропусков
            $hasAbsences = mysqli_query($db_connection, "SELECT * FROM Absences WHERE lesson_id = $this->lesson_id");

            if (mysqli_num_rows($hasAbsences) > 0) {
                return false; // Нельзя удалить — есть связи
            }

            $query = "DELETE FROM Lessons WHERE lesson_id = $this->lesson_id";
            return mysqli_query($db_connection, $query);
        }

        // Валидация данных
        public function validate() {
            $errors = [];

            if (!is_numeric($this->program_id) || intval($this->program_id) <= 0) {
                $errors[] = 'ID программы должен быть положительным числом';
            }

            if (!is_numeric($this->group_id) || intval($this->group_id) <= 0) {
                $errors[] = 'ID группы должен быть положительным числом';
            }

            if (!is_numeric($this->teacher_id) || intval($this->teacher_id) <= 0) {
                $errors[] = 'ID преподавателя должен быть положительным числом';
            }

            if (!is_numeric($this->duration_minutes) || intval($this->duration_minutes) <= 0) {
                $errors[] = 'Длительность должна быть положительным числом';
            }

            return $errors;
        }
    }
?>