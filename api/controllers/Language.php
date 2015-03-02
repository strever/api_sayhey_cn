<?php
/************************************
 * Project:      yaf
 * FileName:     Language.php
 * Description: 
 * Author:       Strever Fang 
 * CreateTime:   2014/12/19 14:47
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */

use Yaf\Controller_Abstract;
use Yaf\Session;
use Yaf\Registry;
use Yaf\Config\Ini;
class LanguageController extends Controller_Abstract {
    protected static $language;
    protected $currentLang;

    public function init() {
        $yaf_sess = Session::getInstance()->start();
        $this->currentLang = $yaf_sess->get('lang');
    }

    public function initlang() {

        //检查session是否已有语言设置信息
        if(!$this->currentLang) {
            //使用IP获取语言
            //$locale = fang_get_country('81.51.233.4');  //法国IP
            //$locale = fang_get_country('2.159.255.222');  //意大利IP
            //$locale = fang_get_country('2.96.255.22');  //英国IP
            $locale = fang_get_country('119.137.33.41');  //深圳IP
            if(!$lang = strtolower($locale['country']['iso_code'])) {
                //使用配置信息获取语言
                if(!$lang = Registry::get('config')->language) {
                    $lang = 'cn';
                }
            }
            Session::getInstance()->set('lang',$lang);
        }else {
            $lang = $this->currentLang;
        }

        echo $lang;
        self::$language = $this->loadLangArray($lang);
    }

    public function getValidLangISOCode() {
        $lang_files = fang_get_files(__ROOT__ . '/data/lang/',2);
        $valid_langs = array();
        foreach($lang_files as $lang) {
            if(strpos($lang,'lang.ini')) {
                $valid_langs[] = substr($lang, 0, -9);
            }
        }
        return $valid_langs;
    }

    public function getValidLangName() {
        $lang_map_conf = new Ini(__ROOT__ . '/data/lang/lang.map.ini');
        $lang_map = $lang_map_conf->toArray();
        $valid_iso_code = $this->getValidLangISOCode();
        $valid_lang_names = array();
        foreach($valid_iso_code as $iso_code) {
            $valid_lang_names[$iso_code] = $lang_map[$iso_code];
        }
        return $valid_lang_names;
    }

    public function loadLangArray($lang = 'cn') {
        $valid_langs = $this->getValidLangISOCode();
        if(in_array($lang,$valid_langs)) {
            $lang_file = __ROOT__ . '/data/lang/' . $lang . '.lang.ini';
        }else $lang_file = __ROOT__ . '/data/lang/cn.lang.ini';
        $lang_conf = new Ini($lang_file);
        $lang_previous = $lang_conf->toArray();
        $lang_en_conf = new Ini(__ROOT__. '/data/lang/en.lang.ini');
        $lang_en = $lang_en_conf->toArray();
        return array_merge($lang_en,$lang_previous);
    }

    public function changeLang($iso_code) {
        $yaf_sess = Session::getInstance();
        $yaf_sess->set('lang',$iso_code);
        $this->currentLang = $iso_code;
    }

    public function __destruct() {

    }
}