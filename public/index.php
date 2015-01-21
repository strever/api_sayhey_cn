<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/12/12
 * Time: 16:26
 */

use Strever\API\Response;
define("__ROOT__",realpath(dirname(__FILE__) . '/../'));
define("__LIB__",__ROOT__ .'/lib');
define("__PUBLIC__",__ROOT__ . '/public');
define("__DATA__",__ROOT__ . '/data');
define("__APP__",__ROOT__ . '/api');
define("CACHE_PATH",__DATA__ . '/cache');


$app = new Yaf\Application(__ROOT__ . "/conf/application.ini");
try{
    $app->bootstrap()
        ->run();
}catch(Yaf\Exception $e) {
    $retVal = "Fatal Error";
    $code = $e->getCode();
    $msg = $e->getMessage();
    Response::error($retVal,$code,$msg);
}
