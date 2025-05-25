<?php
require_once "../../includes/parts/connection.php";
require_once "../../models/Teacher.php";
require_once "log_error.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $query = "SELECT * FROM Teachers WHERE 1=1";

    // Фильтр по логину
    if (!empty($_GET['login'])) {
        $login = mysqli_real_escape_string($db_connection, $_GET['login']);
        $query .= " AND login LIKE '%$login%'";
    }

    $result = mysqli_query($db_connection, $query);
    $items = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $items[] = new Teacher((object)$row);
    }
    echo json_encode($items);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "log_error.php";
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

http_response_code(400);
echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
logError("teachers.php: Неверный метод запроса");
exit();
?>