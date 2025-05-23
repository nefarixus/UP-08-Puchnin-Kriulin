<?php
    function logError($message) {
        $logFile = __DIR__ . '/../../logs/error_log.txt';
        $timestamp = date("Y-m-d H:i:s");
        $logEntry = "[$timestamp] $message\n";

        file_put_contents($logFile, $logEntry, FILE_APPEND);
    }

    $data = json_decode(file_get_contents('php://input'), true);
    if (isset($data['message'])) {
        logError($data['message']);
    }
?>