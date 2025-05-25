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

    public $group_name;
    public $discipline_name;
    public $teacher_name;
    public $last_name;
    public $first_name;
    public $middle_name;



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
    public static function Get($filters = []) {
    global $db_connection;
    $items = [];

    $whereClauses = [];

    if (!empty($filters['search'])) {
        $search = mysqli_real_escape_string($db_connection, $filters['search']);
        $whereClauses[] = "(t.last_name LIKE '%$search%' OR d.discipline_name LIKE '%$search%')";
    }

    if (!empty($filters['group_id'])) {
        $groupId = intval($filters['group_id']); // Защита от SQL-инъекций
        if ($groupId > 0) {
            $whereClauses[] = "w.group_id = '$groupId'";
        }
    }

    $where = !empty($whereClauses) ? "WHERE " . implode(" AND ", $whereClauses) : "";

    $query = "
        SELECT 
            w.*,
            t.last_name,
            t.first_name,
            t.middle_name,
            sg.group_name,
            d.discipline_name
        FROM Teacher_Workload w
        LEFT JOIN Teachers t ON w.teacher_id = t.teacher_id
        LEFT JOIN StudentGroups sg ON w.group_id = sg.group_id
        LEFT JOIN Disciplines d ON w.discipline_id = d.discipline_id
        $where
    ";

    $result = mysqli_query($db_connection, $query);

    if (!$result) {
        die('Ошибка SQL: ' . mysqli_error($db_connection)); // ← Логирование ошибок
    }

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