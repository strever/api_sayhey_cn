<?php
/************************************
 * Project:      yaf
 * FileName:     Request.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/5 17:46
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

class Strever_API_Request {
    private static $_instance;
    protected $access_token;

    private function __construct() {

    }

    private function __clone() {

    }

    public static function getInstance() {
        if(!self::$_instance instanceof self) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


}