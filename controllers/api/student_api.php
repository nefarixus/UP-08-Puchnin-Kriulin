<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Student.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Student::Get();
        echo json_encode($items);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $student = new Student();
                    foreach ($data as $key => $value) {
                        if (property_exists($student, $key)) {
                            $student->{$key} = $value;
                        }
                    }
                    $student->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $student = new Student();
                    $student->student_id = $data['student_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($student, $key)) {
                            $student->{$key} = $value;
                        }
                    }
                    $student->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $student = new Student();
                    $student->student_id = $data['student_id'];
                    $student->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
            exit();
        }
    }
?>