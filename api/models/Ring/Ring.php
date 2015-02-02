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
    protected $_primary = 'ring_id';
    protected static $fields = array('ring_id','singer_id','genre_id','title','length','addtime','download_num','hash');
    protected static $_host = 'http://ring.appvv.com';


    public function getById($ringId = 1) {
        $ring = $this->find($ringId,self::$fields);
        $ring['dl_link'] = $this->getDlLink($ring['hash']);

        //评分
        $scoreModel = new ScoreModel();
        try {
            $ring['score'] = $scoreModel->find($ringId);
        }catch (\Exception $e) {
            $ring['score'] = 0;
        }

        //歌手信息
        $artistModel = new ArtistModel();
        try {
            $ring['artist'] = $artistModel->find($ring['singer_id']);
        }catch (\Exception $e) {
            $ring['artist'] = null;
        }


        if($ring['genre_id'] == 0) $ring['genre_id'] = 1;

        return $ring;
    }

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
            return $this->duration($duration,$genreId,$currentPage);
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

    public function duration($duration = 'WEEK',$genre_id = 1,$currentPage,$perPageRowCount = 20) {
        switch($duration) {
            case 'WEEK':
                $duration_time = 7*86400;
                break;
            case 'MONTH':
                $duration_time = 30*86400;
                break;
            case 'Total':
                $where = array();
                break;
        }
        $sql_count = ("SELECT ring_id,count(*) as download_num FROM ring_dlrecord WHERE dltime > (unix_timestamp() - ($duration_time)) AND genre_id = $genre_id GROUP BY ring_id");
        Mysql::fetch($sql_count);
        $totalRowCount = Mysql::$rowCount;
        $fields = join(', r.',self::$fields);
        $fields = 'd.' . $fields . ', count(*) as download_num';
        $sql = "SELECT $fields FROM ring_dlrecord d,ring r WHERE d.dltime > (unix_timestamp() - ($duration_time)) AND d.ring_id = r.ring_id AND d.genre_id = $genre_id GROUP BY d.ring_id ORDER BY download_num DESC";
        return self::page($sql,$totalRowCount,$currentPage,$perPageRowCount);
    }

    public function page($sql,$totalRowCount, $currentPage = 1, $perPageRowCount = 20) {
        $totalPage = ceil ( $totalRowCount / $perPageRowCount );
        $currentPage = ($currentPage > $totalPage)?$totalPage:$currentPage;
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