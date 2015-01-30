<?php
/************************************
 * Project:      yaf
 * FileName:     Ring.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/19 17:35
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;
class RingController extends BaseRingController {

    /**
     * genre/getByTime/order/[asc|dec]
     * 最新铃声
     */
    public function getByTimeAction() {
        $order = $this->getRequest()->getParam('order','desc');
        $rowCount = $this->getRequest()->getParam('rowCount',20);
        if($order == "asc") $order = true;
        if($order == "desc") $order = false;
        $rings = $this->model->getByTime($order,$rowCount);
        Response::show($rings);
    }

    /**
     * 最热铃声
     */
    public function getByDLNumAction() {
        $rowCount = $this->getRequest()->getParam('rowCount',20);
        $rings = $this->model->getByDLNum($rowCount);
        Response::show($rings);
    }

    public function getByGenreIdAction() {
        $genre_id = $this->getRequest()->getParam('genreId');
        $currentPage = $this->getRequest()->getParam('pn');
        $genre_id = intval($genre_id)?$genre_id:1;
        $currentPage = intval($currentPage)?$currentPage:1;
        $rings = $this->model->getByGenreId($genre_id,$currentPage);
        Response::show($rings);
    }

    public function getAllGenreAction() {
        $genreModel = new \Ring\GenreModel();
        $genres = $genreModel->getGenres();
        $data = array();
        foreach($genres as $genre) {
            $rings = $this->model->getRandomByGenreId($genre['genre_id'],20);
            $genre['rings'] = $rings;
            $data[] = $genre;
        }
        Response::show($data);
    }


}