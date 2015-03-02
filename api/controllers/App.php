<?php
/************************************
 * Project:      yaf
 * FileName:     App.php
 * Description:  http://api.dev/app/getRecommended/id/22?access_token=test&format=json
 * Author:       Strever Fang 
 * CreateTime:   2014/12/18 17:54
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;
use Strever\Db\Mongo\Mongo;

error_reporting(E_ERROR);

class AppController extends BaseAPIController {
    protected $res;
    protected static $col_appinfo;

    public function init() {
        parent::init();
        $db = Mongo::getInstance()->getMongoDb();
        self::$col_appinfo = $db->appinfo_cn;

    }

    public function indexAction() {
        echo "<br>app_index";
        echo $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        fang_p($this->getRequest()->getParams());
        //data_to_xml($this->language);
        /*
        $loader = Yaf_Loader::getInstance();
        $loader->setLibraryPath("Local");
        $loader->import("Local/Local.php");
        $my = new MyClass();
        $my->myFunc();
        */
        echo \Yaf\VERSION;
    }

    public function detailAction() {
        echo "App_detail<br>";
        return false;
    }

    public function getRecommendedAction() {
        $apps = self::$col_appinfo->find(array(),array('_id'=>0))->limit(5);
        //$apps = $mongoHelper->findOne();
        //$apps = $collection->find(array(),array("_id"=>0));
        Response::show($apps,888,'success',$this->format);
        //Response::show(array('id'=>'22','name'=>'hello','saying'=>'world'),888,'success',$this->format);
    }

}