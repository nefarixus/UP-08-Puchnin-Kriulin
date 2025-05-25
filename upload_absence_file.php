<?php
    $uploadDir = __DIR__ . '/absence_text/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
        $file = $_FILES['file'];
        
        if ($file['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Ошибка загрузки файла']);
            exit();
        }

        if ($file['type'] !== 'application/pdf') {
            http_response_code(400);
            echo json_encode(['status' => 'error', 'message' => 'Можно загружать только PDF-файлы']);
            exit();
        }

        $fileName = basename($file['name']);
        $filePath = $uploadDir . $fileName;

        if (file_exists($filePath)) {
            $fileName = uniqid() . '_' . $fileName;
            $filePath = $uploadDir . $fileName;
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            echo json_encode(['status' => 'success', 'path' => $fileName]);
        } else {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Не удалось переместить файл']);
        }
    } else {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Файл не выбран']);
    }
?>