<?php
class Config {
    private static $configPath = 'config/app.json';

    public static function get() {
        if (!file_exists(self::$configPath)) {
            return [];
        }
        return json_decode(file_get_contents(self::$configPath), true);
    }

    public static function save($data) {
        $current = self::get();
        $new = array_merge($current, $data);
        return file_put_contents(self::$configPath, json_encode($new, JSON_PRETTY_PRINT));
    }
}
