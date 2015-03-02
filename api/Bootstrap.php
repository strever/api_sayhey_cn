<?php
/************************************
 * Project:      yaf
 * FileName:     Bootstrap.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/17 15:18
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Yaf\Application;
use Yaf\Bootstrap_Abstract;
use Yaf\Registry;
use Yaf\Dispatcher;
use Yaf\Request;

class Bootstrap extends Bootstrap_Abstract {
    public function _initConfig() {
        $config = Application::app()->getConfig();
        Registry::set('config',$config);
    }

    public function _initFunctionCore() {
        require_once __ROOT__ . '/lib/strever_function.php';
    }

    /*public function _initRoute(Dispatcher $dispatcher) {
        $route = $dispatcher->getRouter();
        $route->addConfig(Registry::get('config')->routes);
    }*/

    public function _initView(Dispatcher $dispatcher) {
        //Dispatcher::getInstance()->disableView();
        Dispatcher::getInstance()->autoRender(false);
    }

}