<?php
/************************************
 * Project:      yaf
 * FileName:     error.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/17 15:21
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Strever\API\Response;
error_reporting(E_ERROR);

class ErrorController extends BaseAPIController {
    public function errorAction($exception) {
        Response::error($exception->getCode());
    }
}