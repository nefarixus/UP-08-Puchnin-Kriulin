<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Grade.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Grade::Get();
        echo json_encode($items);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $grade = new Grade();
                    foreach ($data as $key => $value) {
                        if (property_exists($grade, $key)) {
                            $grade->{$key} = $value;
                        }
                    }
                    $grade->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $grade = new Grade();
                    $grade->grade_id = $data['grade_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($grade, $key)) {
                            $grade->{$key} = $value;
                        }
                    }
                    $grade->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $grade = new Grade();
                    $grade->grade_id = $data['grade_id'];
                    $grade->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
            exit();
        }
    }
?>