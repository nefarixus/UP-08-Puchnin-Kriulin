<?php
require_once "../../includes/parts/connection.php";
require_once "../../models/Consultation.php";

require_once "log_error.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $items = Consultation::Get();
    echo json_encode($items);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action'])) {
        try {
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

                default:
                    throw new Exception("Неизвестное действие: {$data['action']}");
            }
        } catch (Exception $e) {
            logError($e->getMessage());
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }

    logError("Действие не указано");
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Действие не указано']);
    exit();
}

logError("Неверный метод запроса: " . $_SERVER['REQUEST_METHOD']);
http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Метод не поддерживается']);
exit();