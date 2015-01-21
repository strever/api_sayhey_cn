<?php

/************************************
 * Project:      yaf
 * FileName:     Response.php
 * Description:
 * Author:       Strever Fang
 * CreateTime:   2015/1/5 17:45
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

namespace Strever\API;
use Yaf\Registry;

class Response
{

    protected static $errcode = array(
        '200'                => 'SUCCESS',
        '400'                => 'BAD_REQUEST',
        '404'                => 'NOTFOUND',
        '405'                => 'Unknown Data Format Request',            //未知的请求数据格式
        //'444'              => 'Authenticate Failed',                    //认证失败
        '444'                => '认证失败',                    //认证失败

        '512'                =>    'ERR_STARTUP_FAILED',
        '513'                =>    'ERR_ROUTE_FAILED',
        '514'                =>    'ERR_DISPATCH_FAILED',
        '515'                =>    'ERR_NOTFOUND_MODULE',
        '516'                =>    'ERR_NOTFOUND_CONTROLLER',
        '517'                =>    'ERR_NOTFOUND_ACTION',
        '518'                =>    'ERR_NOTFOUND_VIEW',
        '519'                =>    'ERR_CALL_FAILED',
        '520'                =>    'ERR_AUTOLOAD_FAILED',
        '521'                =>    'ERR_TYPE_ERROR',
    );

    public static function show($data, $code = '888',$msg = "成功",$format = '')
    {
        fang_record_duration('end');
        $duration = fang_record_duration('start','end');
        if($data instanceof \MongoCursor ) {
            $data = iterator_to_array($data);
        }
        $show = array(
            'time'     =>  $duration,
            'code'     =>  $code,
            'msg'      =>  $msg,
            'dataCount'=>  count($data),
            'data'     =>  $data

        );
        $format = $format?$format:(Registry::get('response_data_format')?Registry::get('response_data_format'):'json');
        switch($format) {
            case 'json':
                header('Content-Type:application/json; charset=utf-8');
                echo fang_to_json($show);
                break;
            case 'xml':
                header("Content-Type:text/xml;charset='utf-8'");
                echo xml_encode($show);
                break;
            case 'jsonp':
                header('Content-Type:application/json; charset=utf-8');
                echo  Registry::get('jsonp_callback') . '(' . fang_to_json($show) . ')';
                break;
            default:
                header('Content-Type:application/json; charset=utf-8');
                echo fang_to_json($show);
                break;
        }
        exit;
    }

    public static function error($code,$msg = '',$format='') {
        $err_msg = $msg?$msg:(self::$errcode[$code]?self::$errcode[$code]:'Unknown Error');
        fang_record_duration('end');
        $duration = fang_record_duration('start','end');
        $error = array(
            'time'    =>   $duration,
            'code'        =>   $code,
            'msg'         =>   $err_msg,
        );
        $format = $format?$format:(Registry::get('response_data_format')?Registry::get('response_data_format'):'json');
        switch($format) {
            case 'json':
                header('Content-Type:application/json; charset=utf-8');
                echo fang_to_json($error);
                break;
            case 'xml':
                header("Content-Type:text/xml;charset='utf-8'");
                echo xml_encode($error);
                break;
            case 'jsonp':
                header('Content-Type:application/json; charset=utf-8');
                echo  Registry::get('jsonp_callback') . '(' . fang_to_json($error) . ')';
                break;
            default:
                header('Content-Type:application/json; charset=utf-8');
                echo fang_to_json($error);
                break;
        }
        exit;
    }

}
