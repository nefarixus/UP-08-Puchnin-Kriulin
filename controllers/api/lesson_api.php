<?php
    require_once "../../includes/parts/connection.php";
    require_once "../../models/Lesson.php";
    require_once "log_error.php";

    header("Content-Type: application/json");

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['action'])) {
            switch ($data['action']) {
                case 'create':
                    $lesson = new Lesson();
                    foreach ($data as $key => $value) {
                        if (property_exists($lesson, $key)) {
                            $lesson->{$key} = $value;
                        }
                    }

                    $errors = $lesson->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("lessons.php: ". implode('; ', $errors));
                        exit();
                    }

                    if ($lesson->Insert()) {
                        echo json_encode(['status' => 'success', 'message' => 'Запись успешно добавлена']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при добавлении записи']);
                        logError("lessons.php: Ошибка при добавлении");
                    }
                    break;

                case 'update':
                    $lesson = new Lesson();
                    $lesson->lesson_id = $data['lesson_id'];
                    foreach ($data as $key => $value) {
                        if (property_exists($lesson, $key)) {
                            $lesson->{$key} = $value;
                        }
                    }

                    $errors = $lesson->validate();
                    if (!empty($errors)) {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'errors' => $errors]);
                        logError("lessons.php: ". implode('; ', $errors));
                        exit();
                    }

                    if ($lesson->Update()) {
                        echo json_encode(['status' => 'success', 'message' => 'Запись успешно обновлена']);
                    } else {
                        http_response_code(500);
                        echo json_encode(['status' => 'error', 'message' => 'Ошибка при обновлении записи']);
                        logError("lessons.php: Ошибка при обновлении");
                    }
                    break;

                case 'delete':
                    $lesson = new Lesson();
                    $lesson->lesson_id = $data['lesson_id'];

                    if ($lesson->Delete()) {
                        echo json_encode(['status' => 'success', 'message' => 'Запись успешно удалена']);
                    } else {
                        http_response_code(400);
                        echo json_encode(['status' => 'error', 'message' => 'Нельзя удалить — есть связанные данные']);
                        logError("lessons.php: Нельзя удалить — есть связанные данные");
                    }
                    break;
            }
            exit();
        } else {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Не указано действие']);
            logError("lessons.php: Не указано действие");
            exit();
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $items = Lesson::Get();
        echo json_encode($items);
        exit();
    }

    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Неверный метод запроса']);
    logError("lessons.php: Неверный метод запроса");
    exit();
?>