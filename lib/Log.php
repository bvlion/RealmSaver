<?php
class Log {
    // info log
    public static function info($text) {
        self::write($text, "INFO");
    }
 
    // warn log
    public static function warn($text) {
        self::write($text, "WARN");
    }

    // error log
    public static function error($text) {
        self::write($text, "ERROR");
    }
 
    // output to [/log/yyyyMMdd.log]
    private static function write($text, $log_type) {
        $datetime = date('Y-m-d H:i:s');
        $date = self::getDate();
        $file_name = __DIR__ . "/../log/{$date}.log";
        $text = "{$datetime} [{$log_type}] {$text}" . PHP_EOL;
        return error_log(print_r($text, TRUE), 3, $file_name);
    }
 
    // get date (for filename)
    private static function getDate() {
        return date('Ymd');
    }
}