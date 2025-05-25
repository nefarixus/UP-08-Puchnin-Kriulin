<?php
require_once "../../includes/parts/connection.php";
require_once "../../models/Workload.php";
require_once "log_error.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $filters = [];
    if (!empty($_GET['search'])) {
        $filters['search'] = $_GET['search'];
    }
    if (!empty($_GET['group_id'])) {
        $filters['group_id'] = $_GET['group_id'];
    }

    error_log("Filters: " . print_r($filters, true)); 

    $items = Workload::Get($filters);
    echo json_encode($items);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['action'])) {
        try {
            switch ($data['action']) {
                case 'create':
                    $item = new Workload();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Insert();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'update':
                    $item = new Workload();
                    $item->workload_id = $data['workload_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }
                    $item->Update();
                    echo json_encode(['status' => 'success']);
                    break;

                case 'delete':
                    $item = new Workload();
                    $item->workload_id = $data['workload_id'];
                    $item->Delete();
                    echo json_encode(['status' => 'success']);
                    break;
            }
        } catch (Exception $e) {
            logError("Ошибка: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        exit();
    }
}

logError("Неверный метод запроса: " . $_SERVER['REQUEST_METHOD']);
http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
exit();