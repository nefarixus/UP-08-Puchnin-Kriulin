<?php

    class Workload {
        public $workload_id;
        public $teacher_id;
        public $discipline_id;
        public $group_id;
        public $lecture_hours;
        public $practice_hours;
        public $consultation_hours;
        public $course_project_hours;
        public $exam_hours;

        public function __construct($params = null) {
            if ($params !== null) {
                foreach ($params as $key => $value) {
                    if (property_exists($this, $key)) {
                        $this->{$key} = $value;
                    }
                }
            }
        }

        // Получить всю нагрузку
        public static function Get() {
            global $db_connection;
            $items = [];
            $result = mysqli_query($db_connection, "SELECT * FROM Teacher_Workload");
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Workload((object)$row);
            }
            return $items;
        }

        // Добавить нагрузку
        public function Insert() {
            global $db_connection;
            $query = "INSERT INTO Teacher_Workload (
                        teacher_id, discipline_id, group_id,
                        lecture_hours, practice_hours, consultation_hours,
                        course_project_hours, exam_hours
                    ) VALUES (
                        '$this->teacher_id', '$this->discipline_id', '$this->group_id',
                        '$this->lecture_hours', '$this->practice_hours', '$this->consultation_hours',
                        '$this->course_project_hours', '$this->exam_hours'
                    )";
            return mysqli_query($db_connection, $query);
        }

        // Обновить нагрузку
        public function Update() {
            global $db_connection;
            $query = "UPDATE Teacher_Workload SET
                        teacher_id = '$this->teacher_id',
                        discipline_id = '$this->discipline_id',
                        group_id = '$this->group_id',
                        lecture_hours = '$this->lecture_hours',
                        practice_hours = '$this->practice_hours',
                        consultation_hours = '$this->consultation_hours',
                        course_project_hours = '$this->course_project_hours',
                        exam_hours = '$this->exam_hours'
                    WHERE workload_id = $this->workload_id";
            return mysqli_query($db_connection, $query);
        }

        // Удалить нагрузку
        public function Delete() {
            global $db_connection;
            $query = "DELETE FROM Teacher_Workload WHERE workload_id = $this->workload_id";
            return mysqli_query($db_connection, $query);
        }
    }
?>
