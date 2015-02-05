<?php
/************************************
 * Project:      yaf
 * FileName:     Artist.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/20 10:16
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Cache;
use Strever\Db\Mysql\Mysql;

class ArtistModel extends Mysql {
    protected $_table = 'ring_singer';
    protected $_primary = 'singer_id';
    protected static $_fields = 'singer_id,region,name,index';

    public function getHot($rowCount = 15) {
        $_cache = Cache::getInstance();
        if(!$_cache::get('artist_gethot')){
            $db = Mysql::$dbh;
            $sql = "SELECT s.singer_id,s.name,count(*) as ring_num,sum(r.download_num) as dl_num FROM ring r, ring_singer s WHERE r.singer_id = s.singer_id  GROUP BY r.singer_id ORDER BY dl_num DESC LIMIT $rowCount";
            $stmt = $db->query($sql);
            $dlNums = $stmt->fetchAll(\PDO::FETCH_ASSOC);
            $_cache::set('artist_gethot',$dlNums);
            //$singer_ids = array_column($dlNums,'singer_id');
        }else {
            $dlNums = $_cache::get('artist_gethot');
        }

        return $dlNums;
        //return $this->fetchAll(self::$_fields,null,'');
    }

    public function get($region = 1,$category = 1,$status = 1,$rowCount = 20) {
        $where = array(
            'region' => $region,
            'type'   => $category,
            'status' => $status
        );
        return $this->fetchAll(self::$_fields,$where);

    }
}