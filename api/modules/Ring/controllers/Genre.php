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

class GenreController extends BaseRingController {

    public function getGenresAction() {
        fang_p($this->model->fetchAll());
        fang_p($this->model);
    }

    public function getByPinyinAction() {
        $pinyin = $this->getRequest()->getParam('genre');
        echo $pinyin = empty($pinyin)?"gaoxiao":(mysql_real_escape_string($pinyin));
        return $this->model->getByPingyin($pinyin);
    }


}