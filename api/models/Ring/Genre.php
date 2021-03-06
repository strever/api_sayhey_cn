<?php
/************************************
 * Project:      yaf
 * FileName:     GenreModel.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 15:18
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */
namespace Ring;
use Strever\Db\Mysql\Mysql;

class GenreModel extends Mysql {
    protected $_table = 'ring_genre';
    protected $_primary = 'genre_id';
    protected static $fields = "parent_id,name";


    public function getGenres() {
        return $this->fetchAll('genre_id,name,pingyin',null,'sort DESC',10);
    }

    public function getByGenreId() {

    }
}