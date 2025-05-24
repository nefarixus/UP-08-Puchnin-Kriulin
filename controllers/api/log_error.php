<?php
    function logError($message) {
        $logFile = __DIR__ . '/../../logs/error_log.txt';
        $timestamp = date("Y-m-d H:i:s");

        // Если $message — массив, преобразуем его в строку
        if (is_array($message)) {
            $message = implode(', ', $message);
        }

        $logEntry = "[$timestamp] $message\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
?>