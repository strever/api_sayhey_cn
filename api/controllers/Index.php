<?php
/************************************
 * Project:      yaf
 * FileName:     Index.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/17 14:58
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

//use Yaf\Dispatcher;
class IndexController extends BaseAPIController {
    public function indexAction() {
        \Strever\API\Response::error(489,'你要干啥！？');
        /*
        if(isset($_POST['lang'])) {
            $this->changeLang(trim($_POST['lang']));
            exit;
        }
        $this->initlang();
        $language  = parent::$language;
        $valid_lang_names = $this->getValidLangName();
        $this->_view->assign('lang',$language);
        $this->_view->assign('valid_langs',$valid_lang_names);
        $this->render('index.html');
        */
        //fang_p($language);
        //fang_p($valid_lang_names);
        //echo Yaf_Dispatcher::getInstance()->getRequest()->getLanguage();
        //return false;
    }

    public function phpinfoAction() {
        phpinfo();
    }


}