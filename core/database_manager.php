<?php

class DatabaseManager {
    private static $databases = array();
    
    public static function get($dbname){
        if(!isset(self::$databases[$dbname])){
            $db_configs = Config::get('dbconfig');
            if(isset($db_configs[$dbname])){
                self::$databases[$dbname] = new Database($db_configs[$dbname]);
            } else {
                throw new Exception("DB $dbname not found in config!");
            }
        }
        
        return self::$databases[$dbname];
    }
}
?>
