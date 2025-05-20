<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Teacher.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode(Teacher::Get());
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $item = new Teacher();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $item = new Teacher();
                    $item->teacher_id = $data['teacher_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $item = new Teacher();
                    $item->teacher_id = $data['teacher_id'];
                    $item->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
            exit();
        }
    }
?>