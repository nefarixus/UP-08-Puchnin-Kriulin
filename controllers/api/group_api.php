<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/StudentGroup.php";

    require_once "log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "SELECT * FROM StudentGroups WHERE 1=1";
        
        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($db_connection, $_GET['search']);
            $query .= " AND group_name LIKE '%$search%'";
        }

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new StudentGroup((object)$row);
        }

        echo json_encode($items);
        exit();
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

                $errors = $item->validate();
                if (!empty($errors)) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'errors' => $errors]);
                    exit();
                }

                if ($item->Insert()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении группы']);
                    logError("student_groups.php: Ошибка при добавлении группы");
                }
                break;

            case 'update':
                $item = new StudentGroup();
                $item->group_id = $data['group_id'];

                foreach ($data as $key => $value) {
                    if (property_exists($item, $key)) {
                        $item->{$key} = $value;
                    }
                }

                $errors = $item->validate();
                if (!empty($errors)) {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'errors' => $errors]);
                    exit();
                }

                if ($item->Update()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(500);
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении группы']);
                    logError("student_groups.php: Ошибка при обновлении группы");
                }
                break;

            case 'delete':
                $item = new StudentGroup();
                $item->group_id = $data['group_id'];
                
                if ($item->Delete()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Невозможно удалить группу — она используется в других разделах']);
                    logError("student_groups.php: попытка удалить группу — есть связи с другими таблицами");
                }
                break;

            default:
                logError("Неизвестное действие: " . $data['action']);
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
                logError("student_groups.php: Неизвестное действие");
                exit();
        }
        exit();
    }
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Поле "action" обязательно']);
        logError('student_groups.php: Поле "action" обязательно');
        exit();
    }
?>