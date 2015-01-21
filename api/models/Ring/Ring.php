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
    protected static $fields = array('ring_id','singer_id','title','length','addtime','download_num');


    public function getBYTime($increase = false,$rowCount = 20) {
        if($increase) $increase = 'ASC'; else $increase = 'DESC';
        $order = 'addtime ' . $increase;
        return $rings = $this->fetchAll(null,null,$order,$rowCount);
    }

    public function getByDLNum($rowCount = 20) {
        $order = self::orderBy('DLNum');
        return $rings = $this->fetchAll(self::$fields,null,$order,$rowCount);
    }

    public function getByGenreId($genreId = 1,$order='DL_NUM',$rowCount = 20){
        $where = array(
            'genre_id'       =>   $genreId,
        );
        $order = $this->orderBy($order);
        return $this->fetchAll(self::$fields,$where,$order,$rowCount);
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

}