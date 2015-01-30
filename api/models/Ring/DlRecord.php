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

    /*
    public function modifySchema() {
        set_time_limit(0);
        $dbh = Mysql::$dbh;
        //建表
        $sql = "CREATE TABLE IF NOT EXISTS ring_dlrecord( ring_id int(10) unsigned NOT NULL, dltime int(10) unsigned NOT NULL)ENGINE=MyISAM DEFAULT CHARSET=utf8";
        $dbh->query($sql);
        //准备数据
        $stmt = $dbh->query("SELECT ring_id,download_num FROM ring WHERE download_num>100");
        $rings = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        foreach($rings as $ring) {
            foreach(range(1,$ring['download_num']) as $i) {
                $hour = rand(8,24);$minute = rand(0,59);$second =rand(0,59);$day = rand(1,29);$months = array('2014-11','2014-12','2015-01');$month = $months[array_rand($months)];
                $dlTime = strtotime("$month-$day $hour:$minute:$second");
                $sql = "INSERT INTO ring_dlrecord(ring_id,dltime) VALUES($ring[ring_id],$dlTime)";
                $dbh->exec($sql) or die(print_r($dbh->errorInfo(), true));
            }
        }
    }
    */
}
 