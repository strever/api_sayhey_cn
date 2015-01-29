<?php
/************************************
 * Project:      get_appvv_com
 * FileName:     DlRecord.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/29 17:51
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Cache;
use Strever\Db\Mysql\Mysql;

class DlRecordModel extends Mysql {
    protected $_table = "ring_dlrecord";
    protected static $_fields = 'ring_id,dl_time';

    public function modifySchema() {
        //建表
        //$sql = "CREATE TABLE `ring_dlrecord`( `ring_id` int(10) unsigned NOT NULL, `dltime` int(10) unsigned NOT NULL,PRIMARY KEY (`ring_id`))ENGINE=MyISAM DEFAULT CHARSET=utf8";


        //$dbh->query($sql);

        $dbh = Mysql::$dbh;
        //准备数据
        $stmt = $dbh->query("SELECT ring_id,download_num FROM ring WHERE download_num>100");
        $rings = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($rings as $ring) {
            foreach(range(1,$ring['download_num']) as $i) {
                $hour = rand(8,24);$minute = rand(0,59);$second =rand(0,59);$day = rand(1,29);$month = array_rand(array('2014-11 ','2014-12 ','2015-01 '));
                echo "$month$day $hour:$minute$second: " . strtotime("$month$day $hour:$minute$second") . "<br>";
            }
        }




    }
}
 