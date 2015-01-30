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
        $pinyin = $this->getRequest()->getParam('genre',"gaoxiao");
        $pn = $this->getRequest()->getParam('pn',1);
        echo $duration = $this->getRequest()->getParam('duration','TOTAL');
        $genre =  $this->model->getByPingyin($pinyin);
        $ringModel = new RingModel();
        $rings = $ringModel->getByGenreId($genre[0]['genre_id'],$pn,$duration);
        Response::show($rings);
    }


}