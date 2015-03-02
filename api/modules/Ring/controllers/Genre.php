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

    public function getByPinyinAction() {
        $pinyin = $this->getRequest()->getParam('genre',"gaoxiao");
        $pn = $this->getRequest()->getParam('pn',1);
        $order = $this->getRequest()->getParam('order','DL_NUM');
        $duration = $this->getRequest()->getParam('duration','TOTAL');
        $genre =  $this->model->getByPingyin($pinyin);
        $ringModel = new RingModel();
        $rings = $ringModel->getByGenreId($genre[0]['genre_id'],intval($pn),strtoupper($duration),$order);
        Response::show($rings);
    }

    public function getNameAction() {
        $pinyin = $this->getRequest()->getParam('genre',"gaoxiao");
        $name = $this->model->getNameByPingyin($pinyin);
        Response::show($name);
    }




}