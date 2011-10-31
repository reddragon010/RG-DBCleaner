<?php

class Config {
    private static $content = array(
        
    );
    
    public static function load($content){
        self::$content = $content;
    }
    
    public static function get($value){
        if(isset(self::$content[$value])){
            return self::$content[$value];
        } else {
            throw new Exception("Config-Value '$value' not set!");
        }
    }
}
?>
