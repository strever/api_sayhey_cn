<?php
/************************************
 * Project:      yaf
 * FileName:     Crypt.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/5 20:11
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */


class Strever_Auth_Crypt {
    protected $access_token;

    private $secret;

    public function __construct() {
        $this->secret = Yaf_Registry::get('config')->secret;
    }

    public function encodeToken() {

    }

    public function decrypt($encrypted) {
        $cipher = base64_decode($encrypted);
        //$decrypt_str = mcrypt_decrypt($cipher,$this->secret)

    }
}