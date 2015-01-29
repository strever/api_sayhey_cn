<?php
/************************************
 * Project:      yaf
 * FileName:     Genre.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 15:27
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;
use Ring\RingModel;
class GenreController extends BaseRingController {

    public function getGenresAction() {
        fang_p($this->model->fetchAll());
        fang_p($this->model);
    }

    public function getByPinyinAction() {
        $pinyin = $this->getRequest()->getParam('genre');
        $pn = $this->getRequest()->getParam('pn');
        $pinyin = empty($pinyin)?"gaoxiao":addslashes($pinyin);
        $genre =  $this->model->getByPingyin($pinyin);
        $ringModel = new RingModel();
        $rings = $ringModel->getByGenreId($genre[0]['genre_id'],$pn);
        Response::show($rings);
    }


}