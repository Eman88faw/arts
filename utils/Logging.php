<?php

class Logging {
    private static $logFile = __DIR__ . '/logfile.log'; // Log file in the current directory

    public static function Log($message) {
        $date = date('Y-m-d H:i:s');
        $formattedMessage = "[$date] $message" . PHP_EOL;

        // Append to the log file
        file_put_contents(self::$logFile, $formattedMessage, FILE_APPEND);
    }

    public static function Error($message) {
        self::Log("ERROR: $message");
    }
}
?>