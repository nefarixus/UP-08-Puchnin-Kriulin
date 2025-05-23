<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Consultation.php";

    require_once "../log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Consultation::Get();
        echo json_encode($items);
        exit();
    } else {
        logError("Invalid request method: " . $_SERVER['REQUEST_METHOD']);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $item = new Consultation();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $item = new Consultation();
                    $item->consultation_id = $data['consultation_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $item = new Consultation();
                    $item->consultation_id = $data['consultation_id'];
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