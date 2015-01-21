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
        $topNum = $this->getRequest()->getParam('top_num')?$this->getRequest()->getParam('top_num'):15;
        $artists = $this->model->getHot($topNum);
        Response::show($artists);
    }
}