<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/DisciplineProgram.php";
    require_once "log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "
            SELECT 
                dp.*,
                d.discipline_name
            FROM Discipline_Programs dp
            LEFT JOIN Disciplines d ON dp.discipline_id = d.discipline_id
            WHERE 1=1
        ";

        if (!empty($_GET['discipline_id'])) {
            $disciplineId = mysqli_real_escape_string($db_connection, $_GET['discipline_id']);
            $query .= " AND dp.discipline_id = '$disciplineId'";
        }

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = $row;
        }

        echo json_encode(['status' => 'success', 'data' => $items]);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $item = new DisciplineProgram();
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }

                    $errors = $item->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("programs.php: ". implode('; ', $errors));
                        exit();
                    }

                    if ($item->Insert()) {
                        echo json_encode(['status' => 'success', 'data' => DisciplineProgram::Get()]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении программы']);
                    }
                    break;

                case 'update':
                    $item = new DisciplineProgram();
                    $item->program_id = $data['program_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($item, $key)) {
                            $item->{$key} = $value;
                        }
                    }

                    $errors = $item->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("programs.php: ". implode('; ', $errors));
                        exit();
                    }

                    if ($item->Update()) {
                        echo json_encode(['status' => 'success', 'data' => DisciplineProgram::Get()]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении программы']);
                    }
                    break;

                case 'delete':
                    $item = new DisciplineProgram();
                    $item->program_id = $data['program_id'];

                    // Проверяем, используется ли программа в других таблицах
                    $checkUsage = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Lessons WHERE program_id = {$item->program_id}");
                    $used = mysqli_fetch_assoc($checkUsage)['count'] > 0;

                    if ($used) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'message' => 'Нельзя удалить программу — она связана с занятиями']);
                        exit();
                    }

                    if ($item->Delete()) {
                        echo json_encode(['status' => 'success', 'data' => DisciplineProgram::Get()]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при удалении программы']);
                    }
                    break;

                default:
                    logError("Неизвестное действие: " . $data['action']);
                    http_response_code(400);
                    echo json_encode(['status' => 'error', 'message' => 'Неизвестное действие']);
                    exit();
            }
            exit();
        }
    }

    logError("programs.php: Неверный метод запроса");
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
?>