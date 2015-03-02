<?php
/************************************
 * Project:      yaf
 * FileName:     Tag.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/20 10:17
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Db\Mysql\Mysql;

class TagModel extends Mysql {
    protected $_table = 'ring_tag';
}