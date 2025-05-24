<?php
    function logError($message) {
        $logFile = __DIR__ . '/../../logs/error_log.txt';
        $timestamp = date("Y-m-d H:i:s");
        $logEntry = "[$timestamp] $message\n";

        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
?>