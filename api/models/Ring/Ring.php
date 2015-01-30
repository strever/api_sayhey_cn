<?php
/************************************
 * Project:      yaf
 * FileName:     Ring.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 16:09
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use \Strever\Db\Mysql\Mysql;
class RingModel extends Mysql {
    protected $_table = 'ring';
    protected static $fields = array('ring_id','singer_id','genre_id','title','length','addtime','download_num','hash');
    protected static $_host = 'http://ring.appvv.com';


    public function getBYTime($increase = false,$rowCount = 20) {
        if($increase) $increase = 'ASC'; else $increase = 'DESC';
        $order = 'addtime ' . $increase;
        return $rings = $this->fetchAll(null,null,$order,$rowCount);
    }

    public function getByDLNum($rowCount = 20) {
        $order = self::orderBy('DLNum');
        return $rings = $this->fetchAll(self::$fields,null,$order,$rowCount);
    }

    public function getByGenreId($genreId = 1,$currentPage = 1,$duration = 'TOTAL', $order='DL_NUM',$rowCount = 20){
        $where = array(
            'genre_id'       =>   $genreId,
        );
        $order = $this->orderBy($order);
        if(in_array($duration,array('WEEK','MONTH'))) {
            return $this->duration($duration,$genreId,$order);
        }
        return $this->paginator(self::$fields,$where,$order,$rowCount,$currentPage);
    }

    public function getRandomByGenreId($genre_id,$rowCount = 20) {
        $where = array('genre_id'=>$genre_id);
        $rings = $this->fetchAll(self::$fields,$where,self::orderBy('DL_NUM'),1000);
        $randKeys = array_rand($rings,$rowCount);
        $retVal = array();
        foreach($randKeys as $key) {
            $retVal[] = $rings[$key];
        }
        return $retVal;
    }

    public function getDlLink($hash,$types = 'mp3') {
        $retVal = array();
        foreach( (array)$types as $type ) {
            $retVal[$type] = self::$_host . "/{$hash}.{$type}";
        }
        return is_array( $types ) ? $retVal : $retVal[$types];
    }

    public function orderBy($sort) {
        switch($sort) {
            case 'DL_NUM':
                $order = 'download_num DESC';
                break;
            case 'ADD_TIME':
                $order = 'addtime DESC';
                break;
            case 'GENRE':
                $order = 'genre_sort DESC';
                break;
            case 'RECOMMEND':
                $order = 'recommend_sort DESC';
            default:
                $order = 'download_num DESC';
        }
        return $order;
    }

    public function duration($duration = 'WEEK',$genre_id = 1,$order) {
        switch($duration) {
            case 'WEEK':
                $sql_count = ("SELECT count(*) as count FROM ring_dlrecord WHERE dltime > (unix_timestamp() - (7*86400)) AND genre_id = $genre_id GROUP BY ring_id");
                $row = Mysql::fetch($sql_count);
                $total = $row['count'];
                $order = " ORDER BY " . $this->orderBy($order);
                $fields = join(', r.',self::$fields);
                $fields = 'd.' . $fields;
                $sql = "SELECT $fields FROM ring_dlrecord d,ring r WHERE d.dltime > (unix_timestamp() - (7*86400)) AND d.ring_id = r.ring_id AND d.genre_id = $genre_id GROUP BY d.ring_id $order";
                return self::page($sql,$total);
                break;
            case 'MONTH':
                $sql_count = ("SELECT count(*) as count FROM ring_dlrecord WHERE dltime > (unix_timestamp() - (30*86400)) AND genre_id = $genre_id GROUP BY ring_id");
                $row = Mysql::fetch($sql_count);
                $total = $row['count'];
                $order = " ORDER BY " . $this->orderBy($order);
                $fields = join(', r.',self::$fields);
                $fields = 'd.' . $fields;
                $sql = "SELECT $fields FROM ring_dlrecord d,ring r WHERE d.dltime > (unix_timestamp() - (30*86400)) AND d.ring_id = r.ring_id AND d.genre_id = $genre_id GROUP BY d.ring_id $order";
                return self::page($sql,$total);
                break;
            case 'Total':
                $where = array();
                break;
        }
        return $where;
    }

    public function page($sql,$totalRowCount, $perPageRowCount = 20, $currentPage = 1) {
        $totalPage = ceil ( $totalRowCount / $perPageRowCount );
        $prevPage = ($currentPage > 1)?($currentPage - 1):1;
        $nextPage = ($currentPage < $totalPage)?($currentPage + 1):$totalPage;
        $offset = ($currentPage - 1) * $perPageRowCount;
        $limit = " LIMIT $offset,$perPageRowCount";
        $sql .=  $limit;
        $currentPageRows = Mysql::query($sql);
        $currentPageRowsCount = count($currentPageRows);

        $retVal = array(
            'rowOffset'           =>   $offset,
            'totalRowCount'       =>   $totalRowCount,
            'perPageRowCount'     =>   $perPageRowCount,
            'totalPage'           =>   $totalPage,
            'prevPage'            =>   $prevPage,
            'currentPage'         =>   $currentPage,
            'nextPage'            =>   $nextPage,
            'currentPageRowsCount'=>   $currentPageRowsCount,
            'currentPageRows'     =>   $currentPageRows,
        );
        return $retVal;
    }

}