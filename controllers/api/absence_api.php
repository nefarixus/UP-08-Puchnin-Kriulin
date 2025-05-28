<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Absence.php";
    require_once "log_error.php";

    header("Content-Type: application/json");

    // --- POST запросы ---
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $absence = new Absence();
                    foreach ($data as $key => $value) {
                        if (property_exists($absence, $key)) {
                            $absence->{$key} = $value;
                        }
                    }

                    $errors = $absence->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("absences.php: ". implode('; ', $errors));
                        exit();
                    }

                    // Проверка наличия студента
                    $studentQuery = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Students WHERE student_id = {$absence->student_id}");
                    $studentExists = mysqli_fetch_assoc($studentQuery)['count'] > 0;

                    if (!$studentExists) {
                        http_response_code(400);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Студент с таким ID не найден'
                        ]);
                        exit();
                    }

                    // Проверка наличия занятия
                    $lessonQuery = mysqli_query($db_connection, "SELECT COUNT(*) AS count FROM Lessons WHERE lesson_id = {$absence->lesson_id}");
                    $lessonExists = mysqli_fetch_assoc($lessonQuery)['count'] > 0;

                    if (!$lessonExists && !empty($absence->lesson_id)) {
                        http_response_code(400);
                        echo json_encode([
                            'status' => 'error',
                            'message' => 'Занятие с таким ID не найдено'
                        ]);
                        exit();
                    }

                    if ($absence->Insert()) {
                        echo json_encode(['status' => 'success', 'data' => Absence::Get()]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении пропуска']);
                    }
                    break;

                case 'update':
                    $absence = new Absence();
                    $absence->absence_id = $data['absence_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($absence, $key)) {
                            $absence->{$key} = $value;
                        }
                    }

                    $errors = $absence->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("absences.php: ". implode('; ', $errors));
                        exit();
                    }

                    if ($absence->Update()) {
                        echo json_encode(['status' => 'success', 'data' => Absence::Get()]);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении']);
                    }
                    break;

                case 'delete':
                    $absence = new Absence();
                    $absence->absence_id = $data['absence_id'];

                    if ($absence->Delete()) {
                        echo json_encode(['status' => 'success', 'message' => 'Пропуск успешно удален']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'message' => 'Нельзя удалить пропуск — есть связанные данные']);
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

        // Если action не указан → можно использовать для фильтрации
        if (!empty($data['search']) || !empty($data['student_id']) || !empty($data['group_id'])) {
            $query = "
                SELECT 
                    a.absence_id,
                    a.lesson_id,
                    a.student_id,
                    a.minutes_missed,
                    a.explanatory_note_path,
                    s.last_name,
                    g.group_name
                FROM Absences a
                LEFT JOIN Students s ON a.student_id = s.student_id
                LEFT JOIN StudentGroups g ON s.group_id = g.group_id
                WHERE 1=1
            ";

            if (!empty($data['search'])) {
                $search = mysqli_real_escape_string($db_connection, $data['search']);
                $query .= " AND (
                    s.last_name LIKE '%$search%' OR
                    g.group_name LIKE '%$search%'
                )";
            }

            if (!empty($data['student_id'])) {
                $studentId = mysqli_real_escape_string($db_connection, $data['student_id']);
                $query .= " AND a.student_id = '$studentId'";
            }

            if (!empty($data['group_id'])) {
                $groupId = mysqli_real_escape_string($db_connection, $data['group_id']);
                $query .= " AND g.group_id = '$groupId'";
            }

            $result = mysqli_query($db_connection, $query);
            $items = [];

            while ($row = mysqli_fetch_assoc($result)) {
                $items[] = new Absence((object)$row);
            }

            echo json_encode($items);
            exit();
        }

        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Поле "action" обязательно']);
        exit();
    }

    // --- GET запросы ---
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $query = "
            SELECT 
                a.absence_id,
                a.lesson_id,
                a.student_id,
                s.last_name,
                g.group_name,
                a.minutes_missed,
                a.explanatory_note_path
            FROM Absences a
            LEFT JOIN Students s ON a.student_id = s.student_id
            LEFT JOIN StudentGroups g ON s.group_id = g.group_id
            WHERE 1=1
        ";

        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($db_connection, $_GET['search']);
            $query .= " AND (
                s.last_name LIKE '%$search%' OR
                g.group_name LIKE '%$search%'
            )";
        }

        if (!empty($_GET['student_id'])) {
            $studentId = mysqli_real_escape_string($db_connection, $_GET['student_id']);
            $query .= " AND a.student_id = '$studentId'";
        }

        if (!empty($_GET['group_id'])) {
            $groupId = mysqli_real_escape_string($db_connection, $_GET['group_id']);
            $query .= " AND g.group_id = '$groupId'";
        }

        $result = mysqli_query($db_connection, $query);
        $items = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $items[] = new Absence((object)$row);
        }

        echo json_encode($items);
        exit();
    }

    logError("absences.php: Неверный метод запроса");
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    exit();
?>