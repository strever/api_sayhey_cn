<?php
/************************************
 * Project:      Sf_Spider
 * FileName:     Cache.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/31 10:31
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

namespace Strever;
class Cache {

    private static $_instance;
    
    protected static $_cachePath;
    private function __construct($cachePath = '') {
        if(empty($cachePath)) {
            self::$_cachePath = CACHE_PATH;
        }else self::$_cachePath = $cachePath;
        
    }

    private function __clone() {}

    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function set($key,$value) {
        $cache_file = self::$_cachePath . '/' . $key . '.txt';
        if($value !== '') {
            file_put_contents($cache_file,serialize($value));
        }
    }

    public static function get($key) {
        $cache_file = self::$_cachePath . '/' . $key . '.txt';
        if(file_exists($cache_file)) {
            return unserialize(file_get_contents($cache_file));
        }else return false;
    }
}