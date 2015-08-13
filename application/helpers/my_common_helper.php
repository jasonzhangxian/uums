<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * 打印数据 用于调试代码
 * 
 * @access   public
 * @param    array     数据数组
 * @die      boolean   是否终止执行
 */
function print_d($datas, $die = TRUE) 
{
    echo '<pre>';
    print_r($datas);
    echo '</pre>';
    $die ? die() : '';
}

/**
 * 获取IP地址
 * 
 * @access   public
 * @return   string   IP地址
 */
function get_ip() 
{
    $ip = '';
    if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_CLIENT_IP']))
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    else
        $ip = $_SERVER['REMOTE_ADDR'];
    return $ip;
}


/**
 * 解析xml
 *
 * @access   public
 * @param    string     xml字符串
 * @return   array   结果数组
 */
function get_xml_data ($strXml) 
{
    $pos = strpos($strXml, 'xml');
    if ($pos) {
        $xmlCode=simplexml_load_string($strXml,'SimpleXMLElement');
        $arrayCode=get_object_vars_final($xmlCode);
        return $arrayCode ;
    } else {
        return '';
    }
}

function get_object_vars_final($obj)
{
    if(is_object($obj)){
        $obj=get_object_vars($obj);
    }
    if(is_array($obj)){
        if(empty($obj))
            $obj = '';
        else{
            foreach ($obj as $key=>$value){
                $obj[$key]=get_object_vars_final($value);
            }
        }
    }
    return $obj;
}

/*
 * 过滤全局GPC的空格
 * @param  $arr  
 * @return  $arr
 */

function trim_gpc($arr) {
    if (is_array($arr)) {
        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                trim_gpc($arr[$key]);
            } else {
                $arr[$key] = trim($val);
            }
        }
    } else {
        $arr = trim($arr);
    }
    return $arr;
}

/*
 * @ 密码双md5加密
 * @ param $str
 * @ return $str
 */

function sas_md5($str) {
    return md5(md5($str));
}

//公共分页
function page_common($page = null, $url = null) {
    $page_config['perpage'] = $page; //每页条数
    $page_config['part'] = 2; //当前页前后链接数量
    $page_config['url'] = $url; //url
    $page_config['seg'] = 3; //参数取 index.php之后的段数，默认为3，即index.php/control/function/18 这种形式
    return $page_config;
}

//创建文件夹
function mkdirs($dir, $mode = 0777, $recursive = true) {
    if (is_null($dir) || $dir === "") {
        return FALSE;
    }
    if (is_dir($dir) || $dir === "/") {
        return TRUE;
    }
    if (mkdirs(dirname($dir), $mode, $recursive)) {
        return mkdir($dir, $mode);
    }
    return FALSE;
}

//复制图片到文件下
function file_copy($filename, $dir) {
    $dir_realpath = str_replace("system", 'wx_face', BASEPATH);
    $dir_path = explode('/', $dir);
    $dsc_name = $dir_path['1'];
    $dir_path = $dir_realpath . $dir_path['0'];
    mkdirs($dir_path);
    $f = file_get_contents($filename);
    if (!$f) {
        return false;
    } else {
        $bytes = file_put_contents($dir_path . '/' . $dsc_name, $f);
        return $bytes;
    }
}

//curl 获取图片
function getImg($url = "", $dir = "") {
    $dir_realpath = str_replace("system", 'wx_face', BASEPATH);
    $dir_path = explode('/', $dir);
    $dsc_name = $dir_path['1'];
    $dir_path = $dir_realpath . $dir_path['0'];
    mkdirs($dir_path);
    $filename = $dir_path . '/' . $dsc_name;
    //去除URL连接上面可能的引号
    //$url = preg_replace( '/(?:^['"]+|['"/]+$)/', '', $url );
    $refer = "https://mp.weixin.qq.com/";
    $hander = curl_init();
    $fp = fopen($filename, 'wb');
    curl_setopt($hander, CURLOPT_REFERER, $refer);
    curl_setopt($hander, CURLOPT_URL, $url);
    curl_setopt($hander, CURLOPT_FILE, $fp);
    curl_setopt($hander, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
    curl_setopt($hander, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
    curl_setopt($hander, CURLOPT_SSLVERSION, 3);
    curl_setopt($hander, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
    curl_setopt($hander, CURLOPT_REFERER, $refer);
    curl_exec($hander);
    curl_close($hander);
    fclose($fp);
    return true;
}

//生成随机数
function generateRandStr($length) {
    $randstr = "";
    for ($i = 0; $i < $length; $i++) {
        $randnum = mt_rand(0, 61);
        if ($randnum < 10) {
            $randstr .= chr($randnum + 48);
        } else if ($randnum < 36) {
            $randstr .= chr($randnum + 55);
        } else {
            $randstr .= chr($randnum + 61);
        }
    }
    return $randstr;
}

//生成纯数字随机数
function rand_str($length = 32, $chars = '1234567890') {
    // Length of character list
    $chars_length = (strlen($chars) - 1);
    // Start our string
    $string = $chars{rand(0, $chars_length)};
    // Generate random string
    for ($i = 1; $i < $length; $i = strlen($string)) {
        // Grab a random character from our list
        $r = $chars{rand(0, $chars_length)};
        // Make sure the same two characters don't appear next to each other
        if ($r != $string{$i - 1})
            $string .= $r;
    }
    // Return the string
    return $string;
}

//加密
function encrypt($data, $key) {
    $key = md5($key);
    $x = 0;
    $len = strlen($data);
    $l = strlen($key);
    $char = '';
    $str = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= $key{$x};
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
    }
    return base64_encode($str);
}

//解密
function decrypt($data, $key) {
    $key = md5($key);
    $x = 0;
    $data = base64_decode($data);
    $len = strlen($data);
    $l = strlen($key);
    $str = '';
    $char = '';
    for ($i = 0; $i < $len; $i++) {
        if ($x == $l) {
            $x = 0;
        }
        $char .= substr($key, $x, 1);
        $x++;
    }
    for ($i = 0; $i < $len; $i++) {
        if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
            $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
        } else {
            $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
        }
    }
    return $str;
}

//用引用分类的方法
function generateTree($items) {
    foreach ($items as $item)
        $items[$item['pid']]['son'][$item['id']] = &$items[$item['id']];
    return isset($items[0]['son']) ? $items[0]['son'] : array();
}

//数据入库转义
function check_input($content) {
    return (!get_magic_quotes_gpc()) ? addslashes($content) : $content;
}

//模拟登录
function request_post($url = '', $post_data = array(), $pass_method = 'post') {
    if (empty($url) || empty($post_data)) {
        return false;
    }
    $o = "";
    foreach ($post_data as $k => $v) {
        $o.= "$k=" . urlencode($v) . "&";
    }
    $post_data = substr($o, 0, -1);
    $postUrl = $url;
    $curlPost = $post_data;
    $ch = curl_init(); //初始化curl
    curl_setopt($ch, CURLOPT_URL, $postUrl); //抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0); //设置header
    if ($pass_method == 'post') {
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
    } else {
        curl_setopt($ch, CURLOPT_POST, 0); //get提交
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    $data = curl_exec($ch); //运行curl
    curl_close($ch);
    return $data;
}

//生成订单号
function order_sn() {
    $year_code = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j');
    $order_sn = $year_code[intval(date('Y')) - 2010] .
            strtoupper(dechex(date('m'))) . date('d') .
            substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
    return 'SKY' . $order_sn;
}

//生成不复复的ID()
function uniq_id() {
    return md5(time() . mt_rand(1, 1000000));
}

//数组转换为对象
function arrayToObject($e) {
    if (gettype($e) != 'array')
        return;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'array' || getType($v) == 'object')
            $e[$k] = (object) arrayToObject($v);
    }
    return (object) $e;
}

//对象转换为级数
function objectToArray($e) {
    $e = (array) $e;
    foreach ($e as $k => $v) {
        if (gettype($v) == 'resource')
            return;
        if (gettype($v) == 'object' || gettype($v) == 'array')
            $e[$k] = (array) objectToArray($v);
    }
    return $e;
}

/* End of file my_common_helper.php */
/* Location: ./application/helpers/my_common_helper.php */