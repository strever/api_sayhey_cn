<?php
/************************************
 * Project:      yaf
 * FileName:     Paginator.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2015/1/23 11:03
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

namespace Strever;

class Paginator {

    public $prevPage = 1;          //上一页码
    public $nextPage;              //下一页码
    public $currentPage;           //当前页码
    public $totalPage;             //总页数
    public $showPages;             //要显示的页数
    public $perPageRowCount;       //每页要显示的行数
    public $naviStr;               //分页导航html


    public function __get($prop) {
        if(isset($this->$prop)) {
            return $this->$prop;
        }else return null;
    }
    public function __set($prop,$propVal) {
        $this->$prop = $propVal;
    }

    public function __construct() {

    }
}