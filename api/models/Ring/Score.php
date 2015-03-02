<?php
/************************************
 * Project:      yaf
 * FileName:     Score.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/20 10:14
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Db\Mysql\Mysql;

class ScoreModel extends Mysql {
    protected $_table = 'ring_score';
    protected $_primary = 'ring_id';
}