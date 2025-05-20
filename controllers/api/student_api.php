<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Student.php";

    header("Content-Type: application/json");

    // --- Поддержка GET и SEARCH ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Student::Get();

        // Если есть параметр ?search
        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = mysqli_real_escape_string($db_connection, $_GET['search']);
            $query = "SELECT * FROM Students WHERE 
                last_name LIKE '%$search%' OR 
                first_name LIKE '%$search%' OR 
                group_id LIKE '%$search%'";
            $result = mysqli_query($db_connection, $query);
            $items = [];
            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Student((object)$row);
            }
        }

        echo json_encode($items);
        exit();
    }

    // --- POST: create / update / delete ---
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