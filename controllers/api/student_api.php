<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Discipline.php";
    require_once "log_error.php";

    header("Content-Type: application/json");

    // --- GET Запросы ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // Получаем информацию о нагрузке по группам для выбранной дисциплины
        if (!empty($_GET['discipline_id'])) {
            $disciplineId = mysqli_real_escape_string($db_connection, $_GET['discipline_id']);

            $query = "
                SELECT 
                    g.group_name,
                    COUNT(l.lesson_id) AS lesson_count,
                    SUM(dp.hours) AS total_hours
                FROM Teacher_Workload tw
                LEFT JOIN StudentGroups g ON tw.group_id = g.group_id
                LEFT JOIN Discipline_Programs dp ON tw.discipline_id = dp.discipline_id
                LEFT JOIN Lessons l ON dp.program_id = l.program_id
                WHERE tw.discipline_id = $disciplineId
                GROUP BY g.group_id
            ";

            $result = mysqli_query($db_connection, $query);
            $items = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Discipline((object)$row);
            }

            echo json_encode($items);
            exit();
        }

        // Поиск по названию дисциплины через ?search=...
        $query = "SELECT * FROM Disciplines WHERE 1=1";

        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($db_connection, $_GET['search']);
            $query .= " AND discipline_name LIKE '%$search%'";
        }

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new Discipline((object)$row);
        }

        echo json_encode($items);
        exit();
    }

    // --- POST Запросы (create, update, delete) ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['action'])) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Поле "action" обязательно']);
            exit();
        }

        switch ($data['action']) {
            case 'create':
                $item = new Discipline();
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
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении дисциплины']);
                }
                break;

            case 'update':
                $item = new Discipline();
                $item->discipline_id = $data['discipline_id'];
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
                    echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении дисциплины']);
                }
                break;

            case 'delete':
                $item = new Discipline();
                $item->discipline_id = $data['discipline_id'];

                if ($item->Delete()) {
                    echo json_encode(['status' => 'success']);
                } else {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Невозможно удалить дисциплину — она используется в других разделах'
                    ]);
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

    logError("disciplines.php: Неверный метод запроса");
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
?>