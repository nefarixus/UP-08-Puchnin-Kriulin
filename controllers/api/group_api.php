<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/StudentGroup.php";

    require_once "../log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo json_encode(StudentGroup::Get());
        exit();
    } else {
        logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $item = new StudentGroup();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $item = new StudentGroup();
                    $item->group_id = $data['group_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $item = new StudentGroup();
                    $item->group_id = $data['group_id'];
                    $item->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
            exit();
        } else {
            logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
        }
    }
?>