<?php
/************************************
 * Project:      yaf
 * FileName:     Base.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/18 17:46
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Yaf\Controller_Abstract;
use Strever\API\Response;
use Yaf\Registry;
use Yaf\Exception;
class BaseRingController extends Controller_Abstract {
    protected $format;
    protected $accessToken;
    protected $controllerName;
    protected $actionName;
    protected $params;
    protected $model;

    public function init() {
        //记录开始时间
        fang_record_duration('start');
        $this->format = isset($_REQUEST['format'])?$_REQUEST['format']:'json';
        if($this->format == 'jsonp') {
            Registry::set('jsonp_callback',isset($_REQUEST['callback'])?$_REQUEST['callback']:'callback');
        }
        //验证数据格式是否正确
        $this->verifyFormat();

        //验证接口权限
        $this->auth();

        $this->controllerName = $this->getRequest()->getControllerName();
        $this->actionName = $this->getRequest()->getActionName();
        $this->params = $this->getRequest()->getParams();

        $controller = $this->controllerName;
        $modelName = 'Ring\\' . $controller . 'Model';
        if(class_exists($modelName)) {
            $this->model = new $modelName;
        }
    }

    public function isValidAction() {
        //$actions = $this->getRequest()->();

    }

    public function auth() {
        if(Registry::get('config')->api->auth) {
            $access_token = $this->getAccessToken();
            if(Registry::get('config')->api->access_token != $access_token) {
                Response::error(444,null,$this->format);
            }
        }
    }

    public function verifyFormat() {
        if(!in_array($this->format,array('json','jsonp','xml'))) {
            Response::error(405,null,'json');
        }else {
            Registry::set('response_data_format',$this->format);
        }
    }

    public function getAccessToken() {
        if(isset($_REQUEST['access_token'])) {
            $this->accessToken = $_REQUEST['access_token'];
            return $this->accessToken;
        }else {
            return false;
        }
    }

    public function errorAction() {
        try{
            echo "ddvddf";
        }catch (Exception\LoadFailed\Action $e) {
            echo "not found";
        }

    }

}