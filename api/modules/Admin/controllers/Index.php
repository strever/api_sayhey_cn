<?php
/************************************
 * Project:      yaf
 * FileName:     Index.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/22 11:10
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

class IndexController extends Yaf_Controller_Abstract {
    public function indexAction() {
        echo "hello,Admin_Index.index<br>";
        return false;
    }
}