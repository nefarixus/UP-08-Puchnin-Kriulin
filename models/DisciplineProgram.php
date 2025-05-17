<?php

class DisciplineProgram {
    public $program_id;
    public $discipline_id;
    public $topic;
    public $lesson_type;
    public $hours;

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
        $result = mysqli_query($db_connection, "SELECT * FROM Discipline_Programs");
        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new DisciplineProgram((object)$row);
        }
        return $items;
    }

    public function Insert() {
        global $db_connection;
        $query = "INSERT INTO Discipline_Programs (discipline_id, topic, lesson_type, hours)
                  VALUES ('$this->discipline_id', '$this->topic', '$this->lesson_type', '$this->hours')";
        return mysqli_query($db_connection, $query);
    }

    public function Update() {
        global $db_connection;
        $query = "UPDATE Discipline_Programs SET
                    discipline_id = '$this->discipline_id',
                    topic = '$this->topic',
                    lesson_type = '$this->lesson_type',
                    hours = '$this->hours'
                  WHERE program_id = $this->program_id";
        return mysqli_query($db_connection, $query);
    }

    public function Delete() {
        global $db_connection;
        $query = "DELETE FROM Discipline_Programs WHERE program_id = $this->program_id";
        return mysqli_query($db_connection, $query);
    }
}