<?php
/************************************
 * Project:      yaf
 * FileName:     strever_function.php
 * Description:
 * Author:       Strever Fang
 * CreateTime:   2014/12/17 15:26
 * ModifiedTime:
 * Version:      0.0.9
 ************************************
 */


function fang_p($obj)
{
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
}

/**
 * 获取客户端IP地址 Copy from ThinkPHP
 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
 * @return mixed
 */
function fang_get_client_ip($type = 0, $adv = false)
{
    $type = $type ? 1 : 0;
    static $ip = NULL;
    if ($ip !== NULL) return $ip[$type];
    if ($adv) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos = array_search('unknown', $arr);
            if (false !== $pos) unset($arr[$pos]);
            $ip = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $long = sprintf("%u", ip2long($ip));
    $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

function fang_get_envron()
{

}

function fang_get_user_agent()
{
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $browsers = array(
            'Chrome' => '谷歌',
            'Opera' => 'Opera',
            'MSIE' => 'IE',
            'Internet Explorer' => 'IE',
            'Firefox' => '火狐',
            'Safari' => 'Safari'
        );
        $platforms = array(
            'windows nt 6.2' => 'Win8',
            'windows nt 6.1' => 'Win7',
            'windows nt 5.2' => 'Win2003',
            'windows nt 5.0' => 'Win2000',
            'windows nt 5.1' => 'WinXP',
            'os x' => 'MacOS X',
            'linux' => 'Linux',
            'openbsd' => 'OpenBSD'
        );
        $agent = trim($_SERVER['HTTP_USER_AGENT']);
        $arr = array();
        foreach ($browsers as $key => $val) {
            $pattern = "/" . preg_quote($key) . "\/([0-9\.]+)/i";    //1.考察正则表达式，答案自由
            if (preg_match($pattern, $agent, $match)) {
                $arr[$val]['version'] = $match[1];
                foreach ($platforms as $key_p => $val_p) {
                    $pattern = "/" . preg_quote($key_p) . "/i";
                    if (preg_match($pattern, $agent)) {
                        $arr[$val]['platform'] = $val_p;
                        break;
                    }
                }
                break;
            }
        }
        return json_encode($arr, JSON_UNESCAPED_UNICODE);                //2.考察编码
    }
}

function fang_get_locale($ip = '')
{
    $ip = empty($ip) ? fang_get_client_ip() : $ip;
    $long = ip2long($ip);
    //传参不为IP
    if ($long == -1 || $long === FALSE) {
        //传参不为有效hostname
        if (gethostbyname($ip) == $ip) {
            echo json_encode(array('error' => 'Invalid argument'));
            return;
        } else $ip = gethostbyname($ip);
        //sprintf("%u\n",$long);
    }

    Yaf_Loader::import('GeoIP/vendor/autoload.php');

    $reader = new GeoIp2\Database\Reader(__DATA__ . '/GeoIP/GeoLite2-City.mmdb');
    return $reader->city($ip)->jsonSerialize();
}

/**
 * need geoip when extension=php_geoip.so on
 * @param $str Mixed '223.220.12.125' or 'www.vshare.com'
 * @return Array (
 * [continent_code] => NA
 * [country_code] => US
 * [country_code3] => USA
 * [country_name] => United States
 * [region] => CA
 * [city] => Marina Del Rey
 * [postal_code] =>
 * [latitude] => 33.9776992798
 * [longitude] => -118.435096741
 * [dma_code] => 803
 * [area_code] => 310
 * )
 */
/*
function fang_get_locale($str = '') {
    $str = empty($str) ? fang_get_client_ip() : $str;
    $long = ip2long($str);
    if ($long != -1 && $long !== FALSE) {
        $str = $long;
        //sprintf("%u\n",$long);

    }
    return geoip_record_by_name(ip2long($str));
}
*/

function fang_get_country($ip = '')
{

    $ip = empty($ip) ? fang_get_client_ip() : $ip;
    $long = ip2long($ip);
    //传参不为IP
    if ($long == -1 || $long === FALSE) {
        //传参不为有效hostname
        if (gethostbyname($ip) == $ip) {
            echo json_encode(array('error' => 'Invalid argument'));
            return;
        } else $ip = gethostbyname($ip);
        //sprintf("%u\n",$long);
    }

    require_once __ROOT__ . '/lib/GeoIP/vendor/autoload.php';

    $reader = new GeoIp2\Database\Reader('D:/wwwroot/data/GeoIP/GeoLite2-Country.mmdb');
    return $reader->country($ip)->jsonSerialize();
}

function fang_max()
{
    $args = func_get_args();
    $arr = array();
    $str = "";
    foreach ($args as $arg) {
        $arr[] = $arg;
        $str .= $arg . ",";
    }
    //$str = substr($str,0,strlen($str)-1);
    $str = substr($str, 0, -1);
    $str .= "中的最大值是: ";
    return $str . max($arr) . PHP_EOL;
}

/**
 * @param string $path
 * @param $format 1-html展示，2-数组
 */
function fang_get_files($path = "./", $format = 1, &$files = array())
{
    $top_level = scandir($path);
    if ($format === 1) {
        echo "<ul>";
        foreach ($top_level as $file) {
            if (is_dir("$path/$file") && $file != "." AND $file != "..") {
                echo "<li>$file</li>";
                fang_get_files("$path/$file");
            } else echo "<li>$file</li>";
        }
        echo "</ul>";
    } elseif ($format === 2) {
        foreach ($top_level as $file) {
            if ($file != "." AND $file != "..") {
                if (is_dir("$path/$file")) {
                    $files[$file] = array();
                    fang_get_files("$path/$file", 1, $files[$file]);
                } else $files[] = $file;
            }
        }
        return $files;
    }

}

function fang_curl_post($url, $post_data = array(), $options_add = array())
{
    $options_default = array(
        CURLOPT_POST => 1,                                 //
        CURLOPT_URL => $url,                               //
        CURLOPT_HEADER => 1,                               //
        CURLOPT_NOBODY => 1,
        CURLOPT_FRESH_CONNECT => 1,                        //
        CURLOPT_RETURNTRANSFER => 1,                       //
        CURLOPT_USERAGENT => "Chrome/32.0.0.1",
        CURLOPT_COOKIEJAR => tempnam('./tmp', 'cookie_'),   //
        CURLOPT_POSTFIELDS => http_build_query($post_data)      //
    );
    $ch = curl_init();
    $options = empty($options_add) ? $options_default : ($options_default + $options_add);
    curl_setopt_array($ch, $options);
    if (!$result = curl_exec($ch)) {
        trigger_error(curl_error($ch));
    } else {
        print_r(curl_getinfo($ch));
        return $result;
    }

}

/**
 * XML编码
 * @param mixed $data 数据
 * @param string $root 根节点名
 * @param string $item 数字索引的子节点名
 * @param string $attr 根节点属性
 * @param string $id 数字索引子节点key转换的属性名
 * @param string $encoding 数据编码
 * @return string
 */
function xml_encode($data, $root = 'strever', $item = 'item', $attr = '', $id = 'id', $encoding = 'utf-8')
{
    if (is_array($attr)) {
        $_attr = array();
        foreach ($attr as $key => $value) {
            $_attr[] = "{$key}=\"{$value}\"";
        }
        $attr = implode(' ', $_attr);
    }
    $attr = trim($attr);
    $attr = empty($attr) ? '' : " {$attr}";
    $xml = "<?xml version=\"1.0\" encoding=\"{$encoding}\"?>";
    $xml .= "<{$root}{$attr}>";
    $xml .= data_to_xml($data, $item, $id);
    $xml .= "</{$root}>";
    return $xml;
}

/**
 * 数据XML编码
 * @param mixed $data 数据
 * @param string $item 数字索引时的节点名称
 * @param string $id 数字索引key转换为的属性名
 * @return string
 */
function data_to_xml($data, $item = 'item', $id = 'id')
{
    $xml = $attr = '';
    foreach ($data as $key => $val) {
        if (is_numeric($key)) {
            $id && $attr = " {$id}=\"{$key}\"";
            $key = $item;
        }
        $xml .= "<{$key}{$attr}>";
        $xml .= (is_array($val) || is_object($val)) ? data_to_xml($val, $item, $id) : htmlentities($val,ENT_XML1,'UTF-8');
        $xml .= "</{$key}>";
    }
    return $xml;
}

function fang_to_json($data)
{
    return json_encode($data, JSON_UNESCAPED_UNICODE);
    //return json_encode($data);
}

function xml_decode($xml, $root = 'so')
{
    $search = '/<(' . $root . ')>(.*)<\/\s*?\\1\s*?>/s';
    $array = array();
    if (preg_match($search, $xml, $matches)) {
        $array = xml_to_array($matches[2]);
    }
    return $array;
}

function xml_to_array($xml)
{
    $search = '/<(\w+)\s*?(?:[^\/>]*)\s*(?:\/>|>(.*?)<\/\s*?\\1\s*?>)/s';
    $array = array();
    if (preg_match_all($search, $xml, $matches)) {
        foreach ($matches[1] as $i => $key) {
            $value = $matches[2][$i];
            if (preg_match_all($search, $value, $_matches)) {
                $array[$key] = xml_to_array($value);
            } else {
                if ('ITEM' == strtoupper($key)) {
                    $array[] = html_entity_decode($value);
                } else {
                    $array[$key] = html_entity_decode($value);
                }
            }
        }
    }
    return $array;
}

/**
 * 记录和统计时间（微秒）使用情况
 * 使用方法:
 * <code>
 * G('begin'); // 记录开始标记位
 * // ... 区间运行代码
 * G('end'); // 记录结束标签位
 * echo G('begin','end',6); // 统计区间运行时间 精确到小数后6位
 * 如果end标记位没有定义，则会自动以当前作为标记位
 * </code>
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位或者m
 * @return mixed
 */
function fang_record_duration($start, $end = '', $dec = 4)
{
    static $_time_record = array();
    if (is_float($end)) { // 记录时间
        $_time_record[$start] = $end;
    } elseif (!empty($end)) { // 统计时间使用
        if (!isset($_time_record[$end])) $_time_record[$end] = microtime(TRUE);
        return number_format(($_time_record[$end] - $_time_record[$start]), $dec);
    } else { // 记录时间使用
        $_time_record[$start] = microtime(TRUE);
    }
    return null;
}

/**
 * 判断字符串中是否有数组中任一字符串
 * @param $string
 * @param array $needle
 * @return bool
 */
function fang_strpos($string, $needle)
{
    if (is_array($needle)) {
        foreach ($needle as $target) {
            if (mb_strpos($string, $target) !== false) {
                return true;
            }
        }
    }
    return false;
}

/**
 * 返回well-formed数组有多少维
 * @param $array
 * @param int $dimension
 * @return int
 */
function fang_get_array_dimension($array,&$dimension = 1) {
    $sub_arr = array_shift($array);
    if(is_array($sub_arr)) {
        $dimension++;
        get_array_dimension($sub_arr,$dimension);
    }
    return $dimension;
}

