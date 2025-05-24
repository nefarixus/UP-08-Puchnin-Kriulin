<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Grade.php";

    require_once "log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Grade::Get();
        echo json_encode($items);
        exit();
    } else {
        logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        require_once "log_error.php";
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
    logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
?>