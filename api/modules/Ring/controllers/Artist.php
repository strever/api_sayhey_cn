<?php
/************************************
 * Project:      yaf
 * FileName:     Artist.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/21 11:07
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;

class ArtistController extends BaseRingController {

    public function getHotAction() {
        $rowCount = $this->getRequest()->getParam('rowCount',15);
        $artists = $this->model->getHot($rowCount);
        Response::show($artists);
    }

    public function getAction() {
        $region = $this->getRequest()->getParam('region',1);
        $category = $this->getRequest()->getParam('cate',1);
        $artists = $this->model->get($region,$category);
        Response::show($artists);
    }
}