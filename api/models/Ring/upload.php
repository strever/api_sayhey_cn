<?php
/************************************
 * Project:      get_appvv_com
 * FileName:     upload.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/2/12 16:05
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Db\Mysql\Mysql;

class UploadModel extends Mysql {
    protected $_table = "ring_upload";
    protected $_primary = "ring_id";
    protected static $_fields = 'ring_id,user_id,nickname,uploaded_at';




}