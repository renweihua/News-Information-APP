<?php

/**
 * hash加密
 * @param string $password
 * @return string
 */
function hash_make(string $password = '123456'): string
{
    return password_hash($password, PASSWORD_DEFAULT);
}

/**
 * hash 解密
 * @param string $password
 * @param string $hash
 * @return bool
 */
function hash_verify(string $password = '123456', string $hash): bool
{
    return password_verify($password, $hash);
}

function list_to_tree($list, $primary_key = 'menu_id', $pid = 'parent_id', $child = '_child', $root = 0): array
{
    $tree = array();
    if (is_array($list)) {
        $refer = array();
        foreach ($list as $key => $data) $refer[$data[$primary_key]] =& $list[$key];
        foreach ($list as $key => $data) {
            $parentId = $data[$pid];
            if ($root == $parentId) $tree[] =& $list[$key];
            else {
                if (isset($refer[$parentId])) {
                    $parent =& $refer[$parentId];
                    $parent[$child][] =& $list[$key];
                }
            }
        }
    }
    return $tree;
}

/**
 * [login_token]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:登录token值
 * @englishAnnotation:
 * @param              integer $length [description]
 * @return             [type]          [description]
 */
function login_token($length = 60): string
{
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '!',
        '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '_',
        '[', ']', '{', '}', '<', '>', '~', '`', '+', '=', ',',
        '.', ';', ':', '/', '?', '|');
    $array = array_rand($chars, $length);
    $rand = '';
    for ($i = 0; $i < $length; $i++) $rand .= $chars[$array[$i]];
    return $rand;
}

/**
 * Get the IP and Language from the client
 * 获取IP与浏览器信息、语言
 *
 * @return array
 */
if (!function_exists('get_client_info')) {
    function get_client_info(): array
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $XFF = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $client_pos = strpos($XFF, ', ');
            $client_ip = false !== $client_pos ? substr($XFF, 0, $client_pos) : $XFF;
            unset($XFF, $client_pos);
        } else $client_ip = $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? $_SERVER['LOCAL_ADDR'] ?? '0.0.0.0';
        $client_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 5) : '';
        $client_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return ['ip' => &$client_ip, 'lang' => &$client_lang, 'agent' => &$client_agent];
    }
}

if (!function_exists('get_ip')) {
    function get_ip(): string
    {
        $data = get_client_info();
        return $data['ip'] ?? '';
    }
}

if (!function_exists('get_this_url')) {
    function get_this_url()
    {
        return 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
    }
}

if (!function_exists('get_request_url')) {
    function get_request_url()
    {
        return $_SERVER['REQUEST_URI'];
    }
}

/**
 * [request_post 模拟post进行url请求]
 * @Author:cnpscy——<[2278757482@qq.com]>
 * @DateTime:2017-09-25
 * @chineseAnnotation:模拟post进行url请求
 * @englishAnnotation:Simulate post for URL requests
 * @param                                string $url [url地址]
 * @param                                array $post_data [提交的数据]
 * @param                                boolean $ispost [是否是post请求]
 * @param                                string $type [返回格式]
 * @return                               array              [description]
 */
function request_post(string $url = '', array $post_data = [], $ispost = true, $type = 'json')
{
    @header("Content-type: text/html; charset=utf-8");
    if (empty($url)) return false;
    $o = "";
    if (!empty($post_data)) {
        foreach ($post_data as $k => $v) $o .= "$k=" . urlencode($v) . "&";
        $post_data = substr($o, 0, -1);
        $key = md5(base64_encode($post_data));
    } else $key = 'key';
    if ($ispost) {
        $url = $url;
        $curlPost = $post_data;
    } else {
        $url = $url . '?' . implode(',', $post_data);
        $curlPost = 'key=' . $key;
    }
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    }
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    $object = json_decode($data);
    $return = object_to_array($object);
    return $return;
}

/**
 * [object_to_array 对象转为数组]
 * @Author:cnpscy——<[2278757482@qq.com]>
 * @DateTime:2017-09-26
 * @chineseAnnotation:对象转为数组
 * @englishAnnotation:The object is converted to an array
 * @param                                object $array [需要转换的对象]
 * @return                               array         [description]
 */
function object_to_array($array)
{
    if (is_object($array)) $array = (array)$array;
    if (is_array($array)) {
        foreach ($array as $key => $value) $array[$key] = object_to_array($value);
    }
    return $array;
}

/**
 * [config_array_analysis]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:配置多维数组的解析
 * @englishAnnotation:
 * @param              [type] $data [需要解析的数组]
 * @return             [type]       [description]
 */
function config_array_analysis($data)
{
    $value_extra = preg_split('/[,;\r\n]+/', trim($data, ",;\r\n"));
    if (strpos($data, ':')) {
        $array = array();
        foreach ($value_extra as $val) {
            list($k, $v) = explode(':', $val);
            $array[$k] = $v;
        }
    } else $array = $value_extra;
    return $array ?? [];
}

/**
 * [is_base64]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测是否为base64位编码
 * @englishAnnotation:
 * @param              [type]  $str [description]
 * @return             boolean      [description]
 */
function is_base64($str)
{
    //这里多了个纯字母和纯数字的正则判断
    if (@preg_match('/^[0-9]*$/', $str) || @preg_match('/^[a-zA-Z]*$/', $str)) return false;
    elseif (is_utf8(base64_decode($str)) && base64_decode($str) != '') return true;
    return false;
}

/**
 * [is_utf8]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:判断否为UTF-8编码
 * @englishAnnotation:
 * @param              [type]  $str [description]
 * @return             boolean      [description]
 */
function is_utf8($str)
{
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}


function getMillisecond()
{
    list($t1, $t2) = explode(' ', microtime());
    return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
}

/**
 * [check_url]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测URL地址格式
 * @englishAnnotation:
 * @version:1.0
 * @param              string $_url [description]
 * @return             [type]        [description]
 */
if (!function_exists('check_url')) {
    function check_url(string $_url): bool
    {
        $str = "/^http(s?):\/\/(?:[A-za-z0-9-]+\.)+[A-za-z]{2,4}(?:[\/\?#][\/=\?%\-&~`@[\]\':+!\.#\w]*)?$/";
        if (!preg_match($str, $_url)) return false;
        else return true;
    }
}

/**
 * [array_ksort_to_string]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:数组升序转成字符串
 * @englishAnnotation:
 * @version:1.0
 * @param              [type] $data [description]
 * @return             [type]       [description]
 */
function array_ksort_to_string($data)
{
    if (is_string($data)) return $data;
    ksort($data);
    $tmps = array();
    foreach ($data as $k => $v) $tmps[] = $k . $v;
    return implode('', $tmps);
}

/**
 * [mobile_web]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:是否为手机端访问
 * @englishAnnotation:
 * @version:1.0
 * @return             boolean [description]
 */
function mobile_web()
{
    $useragent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock = preg_match('|\(.*?\)|', $useragent, $matches) > 0 ? $matches[0] : '';

    $mobile_os_list = array('Google Wireless Transcoder', 'Windows CE', 'WindowsCE', 'Symbian', 'Android', 'armv6l', 'armv5', 'Mobile', 'CentOS', 'mowser', 'AvantGo', 'Opera Mobi', 'J2ME/MIDP', 'Smartphone', 'Go.Web', 'Palm', 'iPAQ');
    $mobile_token_list = array('Profile/MIDP', 'Configuration/CLDC-', '160×160', '176×220', '240×240', '240×320', '320×240', 'UP.Browser', 'UP.Link', 'SymbianOS', 'PalmOS', 'PocketPC', 'SonyEricsson', 'Nokia', 'BlackBerry', 'Vodafone', 'BenQ', 'Novarra-Vision', 'Iris', 'NetFront', 'HTC_', 'Xda_', 'SAMSUNG-SGH', 'Wapaka', 'DoCoMo', 'iPhone', 'iPod');

    $found_mobile = CheckSubstrs($mobile_os_list, $useragent_commentsblock) ||
        CheckSubstrs($mobile_token_list, $useragent);

    if ($found_mobile) return true;
    else return false;
}

function CheckSubstrs($substrs, $text)
{
    foreach ($substrs as $substr) {
        if (false !== strpos($text, $substr)) return true;
    }
    return false;
}

/**
 * [is_app]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测是否为App
 * @englishAnnotation:
 * @version:1.0
 * @return             boolean [description]
 */
function is_app()
{
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) return true;// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA'])) return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;// 找不到为flase,否则为true
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = ['nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'];
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) return true;
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) return true;
    }
    return false;
}

/**
 * [is_id_card]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:检测身份证号码格式是否正确
 * @englishAnnotation:
 * @version:1.0
 * @param              string $id [description]
 * @return             boolean     [description]
 */
function is_id_card(string $id): bool
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if (!preg_match($regx, $id)) return FALSE;
    if (15 == strlen($id)) { //检查15位
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) return FALSE;
        else return TRUE;
    } else {      //检查18位
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) return FALSE;//检查生日日期是否正确
        else {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int)$id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id, 17, 1)) return FALSE;
            else return TRUE;//phpfensi.com
        }
    }
}

/**
 * [round_down_decimal]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:保留几位小数，向下取，就是直接截断 3 为小数即可。
 * @englishAnnotation:
 * @version:1.0
 * @param              float|integer $money_num [description]
 * @param              int|integer $length [description]
 * @return             [type]                   [description]
 */
function round_down_decimal(float $money_num = 0, int $length = 2): float
{
    return substr($money_num, 0, strlen($money_num) - _getFloatLength($money_num) + $length);
}

/**
 * [_getFloatLength]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:计算小数部分的长度
 * @englishAnnotation:
 * @version:1.0
 * @param              [type] $num [description]
 * @return             [type]      [description]
 */
function _getFloatLength($num): int
{
    $count = 0;
    $temp = explode('.', $num);
    if (sizeof($temp) > 1) {
        $decimal = end($temp);
        $count = strlen($decimal);
    }
    return $count;
}

/**
 * [set_money_conversion]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:金额插入数据库的时候，单位转换为“分”【具体根据$length长度而定】
 * @englishAnnotation:
 * @version:1.0
 * @param              float|integer $money [description]
 * @param              int|integer $length [description]
 */
function set_money_conversion(float $money = 0, int $length = 2): float
{
    return floatval($money) * pow(10, $length);
}

/**
 * [get_money_conversion]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:金额取出的时候，由“分”转化为“元”【具体根据$length长度而定】
 * @englishAnnotation:
 * @version:1.0
 * @param              float|integer $money [description]
 * @param              int|integer $length [description]
 * @return             [type]                [description]
 */
function get_money_conversion(float $money = 0, int $length = 2): float
{
    return floatval($money) / pow(10, $length);
}

/**
 * [is_date]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:是否为日期格式
 * @englishAnnotation:
 * @version:1.0
 * @param              string $date [description]
 * @return             boolean       [description]
 */
function is_date(string $date): bool
{
    $ret = strtotime($date);
    return $ret !== FALSE && $ret != -1;
}

/**
 * [month_list]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:
 * @englishAnnotation:
 * @version:1.0
 * @param              int $start [description]
 * @param              int $end [description]
 * @return             [type]        [description]
 */
function month_list(int $start, int $end): array
{
    if (!is_numeric($start) || !is_numeric($end) || ($end <= $start)) return '';
    $start = date('Y-m', $start);
    $end = date('Y-m', $end);
    //转为时间戳
    $start = strtotime($start . '-01');
    $end = strtotime($end . '-01');
    $i = 0;
    $d = array();
    while ($start <= $end) {
        //这里累加每个月的的总秒数 计算公式：上一月1号的时间戳秒数减去当前月的时间戳秒数
        $d[$i] = trim(date('Y-m', $start), ' ');
        $start += strtotime('+1 month', $start) - $start;
        $i++;
    }
    return $d;
}

function get_errors_list(array $data = []): string
{
    $html = '';
    if (!empty($data)) {
        foreach ($data as $k => $v) $html .= $k + 1 . '.' . $v . PHP_EOL;
    }
    return $html;
}

//内存占用空间
function memory_usage()
{
    $memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage() / 1024 / 1024, 2) . 'MB';
    return $memory;
}

/**
 *  参数说明
 *  $string  欲截取的字符串
 *  $sublen  截取的长度
 *  $start   从第几个字节截取，默认为0
 *  $code    字符编码，默认UTF-8
 */
function cutStr(string $string, int $sublen = 100, int $start = 0, $code = 'UTF-8')
{
    if ($code == 'UTF-8') {
        $pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
        preg_match_all($pa, $string, $t_string);
        if (count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . ".....";
        return join('', array_slice($t_string[0], $start, $sublen));
    } else {
        $start = $start * 2;
        $sublen = $sublen * 2;
        $strlen = strlen($string);
        $tmpstr = '';
        for ($i = 0; $i < $strlen; $i++) {
            if ($i >= $start && $i < ($start + $sublen)) {
                if (ord(substr($string, $i, 1)) > 129) {
                    $tmpstr .= substr($string, $i, 2);
                } else {
                    $tmpstr .= substr($string, $i, 1);
                }
            }
            if (ord(substr($string, $i, 1)) > 129) $i++;
        }
        if (strlen($tmpstr) < $strlen) $tmpstr .= "";
        return $tmpstr;
    }
}

/**
 * [strToHex]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:字符串转16进制
 * @englishAnnotation:
 * @version:1.0
 * @param              [type] $string [description]
 * @return             [type]         [description]
 */
function strToHex($string)
{
    $this_i = $hex = "";
    for ($i = 0; $i < strlen($string); $i++) {
        $this_i = dechex(ord($string[$i]));
        if (strlen($this_i) == 0) $this_i = '00';
        else if (strlen($this_i) == 1) $this_i = '0' . $this_i;
        $hex .= $this_i;
    }
    $hex = strtoupper($hex);
    return $hex;
}

/**
 * [hexToStr]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:16进制转字符串
 * @englishAnnotation:
 * @version:1.0
 * @param              [type] $hex [description]
 * @return             [type]      [description]
 */
function hexToStr($hex)
{
    $sendStrArray = str_split(str_replace(' ', '', $hex), 2);  // 将16进制数据转换成两个一组的数组
    $send_info = '';
    for ($j = 0; $j < count($sendStrArray); $j++) {
        $send_info .= chr(hexdec($sendStrArray[$j]));
    }
    return $send_info;
}


function crc16($string, $start_reverse = 0)
{
    $string = pack('H*', $string);
    $crc = 0xFFFF;
    for ($x = 0; $x < strlen($string); $x++) {
        $crc = $crc ^ ord($string[$x]);
        for ($y = 0; $y < 8; $y++) {
            if (($crc & 0x0001) == 0x0001) {
                $crc = (($crc >> 1) ^ 0xA001);
            } else {
                $crc = $crc >> 1;
            }
        }
    }
    $more_data = strlen(dechex(floor($crc % 256))) < 2 ? '0' . dechex($crc % 256) : dechex($crc % 256);
    $less_data = strlen(dechex(floor($crc / 256))) < 2 ? '0' . dechex($crc / 256) : dechex($crc / 256);
    return strtoupper($start_reverse == 0 ? ($more_data . $less_data) : ($less_data . $more_data));
}


function gencrc16($string)
{
    $crc = 0xFFFF;
    for ($x = 0; $x < strlen($string); $x++) {
        $crc = $crc ^ ord($string[$x]);
        for ($y = 0; $y < 8; $y++) {
            if (($crc & 0x0001) == 0x0001) {
                $crc = (($crc >> 1) ^ 0xA001);
            } else {
                $crc = $crc >> 1;
            }
        }
    }
    return strtoupper($crc);
}

/**
 * @param $lat1
 * @param $lng1
 * @param $lat2
 * @param $lng2
 * @return int
 *
 * 经纬度计算两点之间的距离
 */
function getDistance($lat1, $lng1, $lat2, $lng2)
{

    // 将角度转为狐度  
    $radLat1 = deg2rad($lat1);// deg2rad()函数将角度转换为弧度
    $radLat2 = deg2rad($lat2);
    $radLng1 = deg2rad($lng1);
    $radLng2 = deg2rad($lng2);

    $a = $radLat1 - $radLat2;
    $b = $radLng1 - $radLng2;

    $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;

    return $s;

}

/**
 * @param $lat1
 * @param $lon1
 * @param $lat2
 * @param $lon2
 * @param float $radius 星球半径 KM
 * @return float
 *
 * 经纬度计算两点之间的距离
 */
function distance($lat1, $lon1, $lat2, $lon2, $radius = 6378.137)
{
    $rad = floatval(M_PI / 180.0);

    $lat1 = floatval($lat1) * $rad;
    $lon1 = floatval($lon1) * $rad;
    $lat2 = floatval($lat2) * $rad;
    $lon2 = floatval($lon2) * $rad;

    $theta = $lon2 - $lon1;

    $dist = acos(sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta));

    if ($dist < 0) {
        $dist += M_PI;
    }
    return $dist = $dist * $radius;
}

function request_function(string $url = '', array $post_data = [], $ispost = true, $json_conversion = 1)
{
    if (empty($url)) return false;
    $o = "";
    if (!empty($post_data)) {
        foreach ($post_data as $k => $v) $o .= "$k=" . urlencode($v) . "&";
        $post_data = substr($o, 0, -1);
        $key = md5(base64_encode($post_data));
    } else $key = 'key';
    if ($ispost) {
        $url = $url;
        $curlPost = $post_data;
    } else {
        $url = $url . '?' . $post_data;
        $curlPost = 'key=' . $key;
    }
    $ch = curl_init();//初始化curl
    curl_setopt($ch, CURLOPT_URL, $url);//抓取指定网页
    curl_setopt($ch, CURLOPT_HEADER, 0);//设置header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//要求结果为字符串且输出到屏幕上
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);    //禁止 cURL 验证对等证书
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);    //是否检测服务器的域名与证书上的是否一致
    if ($ispost) {
        curl_setopt($ch, CURLOPT_POST, 1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
    }
    $data = curl_exec($ch);//运行curl
    curl_close($ch);
    return $json_conversion ? json_decode($data, true) : $data;
}

/**
 * [set_field_filtering]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:数据类型过滤
 * @englishAnnotation:
 * @version:1.0
 * @param              string $field [数据]
 * @param              string $field_type [数据的类型]
 * @param              string $default_val [默认值]
 */
function set_field_filtering($field = '', $field_type = 'string', $default_val = '')
{
    if (isset($field) || $field == null) return $field;
    $field_type = strtolower(trim($field_type));
    if (in_array($field_type, ['str', 'string', 'varchar'])) return trim($field ?? $default_val);
    else if (in_array($field_type, ['int', 'intval', 'number'])) return intval($field ?? $default_val);
    else if (in_array($field_type, ['double', 'float', 'floatval'])) return floatval($field ?? $default_val);
}

function get_server_url(): string
{
    $pact = isset($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS'] ? 'https://' : 'http://';
    return env('APP_URL', $pact . $_SERVER['SERVER_NAME']);
}

function set_server_url($str): string
{
    return env('APP_URL', get_server_url()) . $str;
    // return get_server_url() . $str;
}

function remove_server_url(string $img): string
{
    return str_replace(get_server_url(), "", $img);
}

/**
 * [CheckMobile 手机号码格式校验]
 */
function check_mobile(string $mobile = ''): bool
{
    return (preg_match('/' . lang('common_regex_mobile') . '/', $mobile) == 1) ? true : false;
}

function check_email(string $email = ''): bool
{
    return (preg_match('/' . lang('common_regex_email') . '/', $email) == 1) ? true : false;
}


//生成随机数
function get_rand($sum = 6)
{
    $rand = '';
    for ($i = 1; $i <= $sum; $i++) $rand .= rand(0, 9);
    return $rand;
}

/**
 * 获取随机字符串
 * @param int $randLength 长度
 * @param int $create_time 是否加入当前时间戳
 * @param int $includenumber 是否包含数字
 * @return string
 */
if (!function_exists('rand_str')) {
    function rand_str($randLength = 6, $create_time = 1, $includenumber = 1)
    {
        if ($includenumber) {
            $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHJKLMNPQEST123456789';
        } else {
            $chars = 'abcdefghijklmnopqrstuvwxyz';
        }
        $len = strlen($chars);
        $randStr = '';
        for ($i = 0; $i < $randLength; $i++) {
            $randStr .= $chars[mt_rand(0, $len - 1)];
        }
        $tokenvalue = $randStr;
        if ($create_time) {
            $tokenvalue = $randStr . time();
        }
        return $tokenvalue;
    }
}

/**
 * 倒计时 转化成 天-时分秒 展示
 * @param $time
 * @return string
 */
function countdown_conversion_time($time)
{
    if (empty($time)) return '';
    $day = intval($time / (60 * 60 * 24));
    $hour = intval($time / (60 * 60) - $day * 24);
    $minute = intval($time / 60 - $day * 24 * 60 - $hour * 60);
    $second = intval($time - $day * 24 * 60 * 60 - $hour * 60 * 60 - $minute * 60);

    $day = (intval($day) <= 0) ? '' : $day . '天 ';
    if (intval($hour) <= 9) $hour = '0' . $hour;
    if (intval($minute) <= 9) $minute = '0' . $minute;
    if (intval($second) <= 9) $second = '0' . $second;
    return $day . $hour . '时' . $minute . '分' . $second . '秒';
}

function amount_conversion($money, $length = 2)
{
    return round(floatval($money), $length);
}

/**
 * 金额格式化
 * @param   [float]         $value     [金额]
 * @param   [int]           $decimals  [保留的位数]
 * @param   [string]        $dec_point [保留小数分隔符]
 */
function PriceNumberFormat($value, $decimals = 2, $dec_point = '.')
{
    return number_format($value, $decimals, $dec_point, '');
}

function getWxPayMoeny($moeny)
{
    return $moeny * 100;//以分为单位
}

/*
 * 检测银行卡
  16-19 位卡号校验位采用 Luhm 校验方法计算：
    1，将未带校验位的 15 位卡号从右依次编号 1 到 15，位于奇数位号上的数字乘以 2
    2，将奇位乘积的个十位全部相加，再加上所有偶数位上的数字
    3，将加法和加上校验位能被 10 整除。
*/
function check_bank_card($bankCardNo)
{
    $strlen = strlen($bankCardNo);
    if ($strlen < 15 || $strlen > 19) {
        return false;
    }
    if (!preg_match("/^\d{15}$/i", $bankCardNo) && !preg_match("/^\d{16}$/i", $bankCardNo)
        &&
        !preg_match("/^\d{17}$/i", $bankCardNo) && !preg_match("/^\d{18}$/i", $bankCardNo)
        &&
        !preg_match("/^\d{19}$/i", $bankCardNo)) {
        return false;
    }
    $arr_no = str_split($bankCardNo);
    $last_n = $arr_no[count($arr_no) - 1];
    krsort($arr_no);
    $i = 1;
    $total = 0;
    foreach ($arr_no as $n) {
        if ($i % 2 == 0) {
            $ix = $n * 2;
            if ($ix >= 10) {
                $nx = 1 + ($ix % 10);
                $total += $nx;
            } else {
                $total += $ix;
            }
        } else {
            $total += $n;
        }
        $i++;
    }
    $total -= $last_n;
    $x = 10 - ($total % 10);
    if ($x != $last_n) {
        return false;
    }
    return true;
}

/**
 * 检测身份证号码
 * @param string $id
 * @return bool
 */
function check_idcard(string $id): bool
{
    $id = strtoupper($id);
    $regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
    $arr_split = array();
    if (!preg_match($regx, $id)) return FALSE;
    if (15 == strlen($id)) { //检查15位
        $regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";
        @preg_match($regx, $id, $arr_split);
        //检查生日日期是否正确
        $dtm_birth = "19" . $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) return FALSE;
        else return TRUE;
    } else {      //检查18位
        $regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
        @preg_match($regx, $id, $arr_split);
        $dtm_birth = $arr_split[2] . '/' . $arr_split[3] . '/' . $arr_split[4];
        if (!strtotime($dtm_birth)) return FALSE;//检查生日日期是否正确
        else {
            //检验18位身份证的校验码是否正确。
            //校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
            $arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
            $arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
            $sign = 0;
            for ($i = 0; $i < 17; $i++) {
                $b = (int)$id{$i};
                $w = $arr_int[$i];
                $sign += $b * $w;
            }
            $n = $sign % 11;
            $val_num = $arr_ch[$n];
            if ($val_num != substr($id, 17, 1)) return FALSE;
            else return TRUE;//phpfensi.com
        }
    }
}

/**
 * 生成混合code
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:登录token值
 * @englishAnnotation:
 * @param              integer $length [description]
 * @return             [type]          [description]
 */
function make_blend_code($length = 20): string
{
    $chars = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h',
        'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's',
        't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D',
        'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O',
        'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
        '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
    $array = array_rand($chars, $length);
    $rand = '';
    for ($i = 0; $i < $length; $i++) $rand .= $chars[$array[$i]];
    return $rand;
}

/**
 * 生成UUID
 * @param string $string
 * @return string
 */
if (!function_exists('make_uuid')) {
    function make_uuid(string $string = ''): string
    {
        $string = '' === $string ? uniqid(mt_rand(), true) : (0 === (int)preg_match('/[A-Z]/', $string) ? $string : mb_strtolower($string, 'UTF-8'));
        $code = hash('sha1', $string . ':UUID');
        $uuid = substr($code, 0, 10);
        $uuid .= substr($code, 10, 4);
        $uuid .= substr($code, 16, 4);
        $uuid .= substr($code, 22, 4);
        $uuid .= substr($code, 28, 12);
        $uuid = strtoupper($uuid);
        unset($string, $code);
        return $uuid;
    }
}

function getFirstChar($s)
{
    $s0 = mb_substr($s, 0, 3); //获取名字的姓
    $s = iconv('UTF-8', 'gb2312', $s0); //将UTF-8转换成GB2312编码
    if (ord($s0) > 128) { //汉字开头，汉字没有以U、V开头的
        $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
        if ($asc >= -20319 and $asc <= -20284) return "A";
        if ($asc >= -20283 and $asc <= -19776) return "B";
        if ($asc >= -19775 and $asc <= -19219) return "C";
        if ($asc >= -19218 and $asc <= -18711) return "D";
        if ($asc >= -18710 and $asc <= -18527) return "E";
        if ($asc >= -18526 and $asc <= -18240) return "F";
        if ($asc >= -18239 and $asc <= -17760) return "G";
        if ($asc >= -17759 and $asc <= -17248) return "H";
        if ($asc >= -17247 and $asc <= -17418) return "I";
        if ($asc >= -17417 and $asc <= -16475) return "J";
        if ($asc >= -16474 and $asc <= -16213) return "K";
        if ($asc >= -16212 and $asc <= -15641) return "L";
        if ($asc >= -15640 and $asc <= -15166) return "M";
        if ($asc >= -15165 and $asc <= -14923) return "N";
        if ($asc >= -14922 and $asc <= -14915) return "O";
        if ($asc >= -14914 and $asc <= -14631) return "P";
        if ($asc >= -14630 and $asc <= -14150) return "Q";
        if ($asc >= -14149 and $asc <= -14091) return "R";
        if ($asc >= -14090 and $asc <= -13319) return "S";
        if ($asc >= -13318 and $asc <= -12839) return "T";
        if ($asc >= -12838 and $asc <= -12557) return "W";
        if ($asc >= -12556 and $asc <= -11848) return "X";
        if ($asc >= -11847 and $asc <= -11056) return "Y";
        if ($asc >= -11055 and $asc <= -10247) return "Z";
    } else if (ord($s) >= 48 and ord($s) <= 57) { //数字开头
        switch (iconv_substr($s, 0, 1, 'utf-8')) {
            case 1:
                return "Y";
            case 2:
                return "E";
            case 3:
                return "S";
            case 4:
                return "S";
            case 5:
                return "W";
            case 6:
                return "L";
            case 7:
                return "Q";
            case 8:
                return "B";
            case 9:
                return "J";
            case 0:
                return "L";
        }
    } else if (ord($s) >= 65 and ord($s) <= 90) { //大写英文开头
        return substr($s, 0, 1);
    } else if (ord($s) >= 97 and ord($s) <= 122) { //小写英文开头
        return strtoupper(substr($s, 0, 1));
    } else {
        return iconv_substr($s0, 0, 1, 'utf-8');
        //中英混合的词语，不适合上面的各种情况，因此直接提取首个字符即可
    }
}

function addPeople()
{
    $userName = array('张三', '马大帅', '李四', '王五', '小二', '猫蛋', '狗蛋', '王花', '三毛', '小明', '李刚', '张飞');
    sort($userName);
    $charArray = [];
    foreach ($userName as $name) {
        $char = getFirstChar($name);
        $nameArray = array();
        if (count($charArray[$char]) != 0) {
            $nameArray = $charArray[$char];
        }
        array_push($nameArray, $name);
        $charArray[$char] = $nameArray;
    }
    ksort($charArray);
}

function strip_html_tags($tags, $str)
{
    $html = array();
    foreach ($tags as $tag) $html[] = "/(<(?:\/" . $tag . "|" . $tag . ")[^>]*>)/i";
    $data = preg_replace($html, '', $str);
    return $data;
}


/**
 * 过滤HTML的标签，只保留文本
 * @param $string
 * @return string
 */
function filtering_html_tags($string)
{
    return strip_tags(htmlspecialchars_decode($string), '');
}

/**
 * 数组按照指定字段进行分组
 * @param array $array
 * @param string $field
 * @return array
 */
function array_field_group(array $array = [], string $field = ''): array
{
    $new_ary = [];
    foreach ($array as $k => $val) $new_ary[$val[$field]][] = $val;
    return $new_ary;
}


//计算中奖概率 
function get_random_probability($proArr) {
    $rs = ''; //z中奖结果 
    $proSum = array_sum($proArr); //概率数组的总概率精度 
    //概率数组循环 
    foreach ($proArr as $key => $proCur) {
        $randNum = mt_rand(0, $proSum);
        if ($randNum <= $proCur) {
            $rs = $key;
            break;
        } else $proSum -= $proCur;
    }
    unset($proArr);
    return $rs;
}

/**
 * 对比数组的不同：
 *  第二个数组对于第一个数组的不同数据返回
 * 并不是array_diff数组的效果，找两个数组之间的差集
 * @param $array1
 * @param $array2
 * @return array
 */
function get_array_diff($array1, $array2)
{
    $diff = [];
    foreach ($array1 as $key => $val) {
        if (!isset($array2[$val])) $diff[$val] = $val;
    }
    return $diff;
}

if (!function_exists('writeLog')) {
    function writeLog($msg, $file_name, $path = APP_PATH)
    {
        /**
         * 第一部分路径
         */
        $dirPath = $path . '/logs';
        if (!is_dir($dirPath)) @mkdir($dirPath);
        $dirPath .= '/' . date('Y');
        if (!is_dir($dirPath)) @mkdir($dirPath);
        $dirPath .= '/' . date('n');
        if (!is_dir($dirPath)) @mkdir($dirPath);
        /**
         * 第二部分
         */
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', "\n\n" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', print_r($msg, TRUE), FILE_APPEND);
    }
}

function cnpscy_config($config_name)
{
    return config('cnpscy.' . $config_name);
}

/**
 * 通过省市区的名称获取对应的经纬度
 * @param string $area_name
 * @return string
 */
function getNameAcquisitionLongitudeAndLatitude($area_name = '')
{
    if (empty($area_name)) return '';
    $res = request_function('https://restapi.amap.com/v3/geocode/geo', [
        'address' => $area_name,
        'key' => MyC('common_lbsamap_apikey'),
        'output' => 'JSON',
    ], false);
    if ($res['status'] == 1) return empty($res['geocodes']) ? '' : $res['geocodes'][0]['location'];
    return '';
}

/**
 * 倒计时 转化成 天-时分秒 展示
 * @param $time
 * @return string
 */
function countdownConversionTime($time)
{
    if (empty($time)) return '';
    $day = intval($time / (60 * 60 * 24));
    $hour = intval($time / (60 * 60) - $day * 24);
    $minute = intval($time / 60 - $day * 24 * 60 - $hour * 60);
    $second = intval($time - $day * 24 * 60 * 60 - $hour * 60 * 60 - $minute * 60);

    $day = (intval($day) <= 0) ? '' : $day . '天 ';
    if (intval($hour) <= 9) $hour = '0' . $hour;
    if (intval($minute) <= 9) $minute = '0' . $minute;
    if (intval($second) <= 9) $second = '0' . $second;
    return $day . $hour . ':' . $minute . ':' . $second . '';
}

if (!function_exists('returnLastPage')) {
    function returnLastPage()
    {
        echo '<script>history.go(-1);</script>';
    }
}

if (!function_exists('array_key_first')) {
    /**
     * Gets the first key of an array
     *
     * @param array $array
     * @return mixed
     */
    function array_key_first(array $array)
    {
        if (count($array)) {
            reset($array);
            return key($array);
        }
        return null;
    }
}

function hidden_mobile(string $text): string
{
    $start = substr($text, 0, 3);
    $end = substr($text, -4, 4);
    return $start . ' **** ' . $end;
}

/**
 * 生成UUID
 * @param string $string
 * @return string
 */
function make_uuid(string $string = ''): string
{
    $string = '' === $string ? uniqid(mt_rand(), true) : (0 === (int)preg_match('/[A-Z]/', $string) ? $string : mb_strtolower($string, 'UTF-8'));
    $code = hash('sha1', $string . ':UUID');
    $uuid = substr($code, 0, 10);
    $uuid .= substr($code, 10, 4);
    $uuid .= substr($code, 16, 4);
    $uuid .= substr($code, 22, 4);
    $uuid .= substr($code, 28, 12);
    $uuid = strtoupper($uuid);
    unset($string, $code);
    return $uuid;
}

function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr")) {
        if ($suffix)
            return mb_substr($str, $start, $length, $charset) . "...";
        else
            return mb_substr($str, $start, $length, $charset);
    } elseif (function_exists('iconv_substr')) {
        if ($suffix)
            return iconv_substr($str, $start, $length, $charset) . "...";
        else
            return iconv_substr($str, $start, $length, $charset);
    }
    $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef] 
[x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    if ($suffix) return $slice . "…";
    return $slice;
}

// function hidden_bank(string $text, $start = 1): string
// {
//     $start = substr($text, 0, 6);
//     $end = substr($text, -4, 4);
//     return $start . ($start == 1 ? ' ' : '') . '········' . ($start == 1 ? ' ' : '') . $end;
//     return $start . ($start == 1 ? ' ' : '') . '**** ****' . ($start == 1 ? ' ' : '') . $end;
// }


function hidden_bank(string $text): string
{
    $start = substr($text, 0, 4);
    $end = substr($text, -4, 4);
    return $start . '&nbsp;****&nbsp;****&nbsp' . $end;
}

function get_month_first_day()
{
    return date('Y-m-01', strtotime(date("Y-m-d", time())));
}

function get_month_last_day()
{
    $date = date('Y-m-01', strtotime(date("Y-m-d", time())));
    return date('Y-m-d', strtotime("$date +1 month -1 day"));
}


if (!function_exists('writeLog')) {
    function writeLog($msg, $file_name, $path = APP_PATH)
    {
        /**
         * 第一部分路径
         */
        $dirPath = $path . '/logs';
        if (!is_dir($dirPath)) {
            @mkdir($dirPath);
        }
        $dirPath .= '/' . date('Y');
        if (!is_dir($dirPath)) {
            @mkdir($dirPath);
        }
        $dirPath .= '/' . date('n');
        if (!is_dir($dirPath)) {
            @mkdir($dirPath);
        }
        /**
         * 第二部分
         */
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', "\n\n" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);
        file_put_contents($dirPath . '/' . (empty($file_name) ? date('j') : $file_name) . '.txt', print_r($msg, TRUE), FILE_APPEND);
    }
}

/**
 * [Xml_Array xml转数组]
 * @param    [xml] $xmlstring [xml数据]
 * @return   [array]          [array数组]
 */
function Xml_Array($xmlstring)
{
    return json_decode(json_encode((array)simplexml_load_string($xmlstring)), true);
}

/**
 * [Is_Json 校验json数据是否合法]
 * @param    [string] $jsonstr [需要校验的json字符串]
 * @return   [boolean] [合法true, 则false]
 */
function Is_Json($jsonstr)
{
    if (PHP_VERSION > 5.3) {
        json_decode($jsonstr);
        return (json_last_error() == JSON_ERROR_NONE);
    } else {
        return is_null(json_decode($jsonstr)) ? false : true;
    }
}

/**
 * [IsMobile 是否是手机访问]
 * @return  [boolean] [手机访问true, 则false]
 */
function IsMobile()
{
    /* 如果有HTTP_X_WAP_PROFILE则一定是移动设备 */
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) return true;

    /* 此条摘自TPM智能切换模板引擎，适合TPM开发 */
    if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT']) return true;

    /* 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息 */
    if (isset($_SERVER['HTTP_VIA']) && stristr($_SERVER['HTTP_VIA'], 'wap') !== false) return true;

    /* 判断手机发送的客户端标志,兼容性有待提高 */
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipad', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile'
        );
        /* 从HTTP_USER_AGENT中查找手机浏览器的关键字 */
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }

    /* 协议法，因为有可能不准确，放到最后判断 */
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        /* 如果只支持wml并且不支持html那一定是移动设备 */
        /* 如果支持wml和html但是wml在html之前则是移动设备 */
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) return true;
    }
    return false;
}

/**
 * [EmptyDir 清空目录下所有文件]
 * @param    [string]    $dir_path [目录地址]
 * @return   [boolean]             [成功true, 失败false]
 */
function EmptyDir($dir_path)
{
    if (is_dir($dir_path)) {
        $dn = @opendir($dir_path);
        if ($dn !== false) {
            while (false !== ($file = readdir($dn))) {
                if ($file != '.' && $file != '..') {
                    if (!unlink($dir_path . $file)) {
                        return false;
                    }
                }
            }
        } else {
            return false;
        }
    }
    return true;
}

/**
 * [FileSizeByteToUnit 文件大小转常用单位]
 * @param    [int]                   $bit [字节数]
 */
function FileSizeByteToUnit($bit)
{
    //单位每增大1024，则单位数组向后移动一位表示相应的单位
    $type = array('Bytes', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $bit >= 1024; $i++) {
        $bit /= 1024;
    }

    //floor是取整函数，为了防止出现一串的小数，这里取了两位小数
    return (floor($bit * 100) / 100) . $type[$i];
}

/**
 * json带格式输出
 * @author   Devil
 * @param   [array]          $data   [数据]
 * @param   [string]         $indent [缩进字符，默认4个空格 ]
 */
function JsonFormat($data, $indent = null)
{
    // json encode  
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    // 缩进处理  
    $ret = '';
    $pos = 0;
    $length = strlen($data);
    $indent = isset($indent) ? $indent : '    ';
    $newline = "\n";
    $prevchar = '';
    $outofquotes = true;

    for ($i = 0; $i <= $length; $i++) {
        $char = substr($data, $i, 1);

        if ($char == '"' && $prevchar != '\\') {
            $outofquotes = !$outofquotes;
        } elseif (($char == '}' || $char == ']') && $outofquotes) {
            $ret .= $newline;
            $pos--;
            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $ret .= $char;

        if (($char == ',' || $char == '{' || $char == '[') && $outofquotes) {
            $ret .= $newline;
            if ($char == '{' || $char == '[') {
                $pos++;
            }

            for ($j = 0; $j < $pos; $j++) {
                $ret .= $indent;
            }
        }

        $prevchar = $char;
    }

    return $ret;
}

/**
 * 根据身份证号码得到年龄
 */

function getAgeByID($id)

{ //过了这年的生日才算多了1周岁

    if (empty($id)) return '';

    $date = strtotime(substr($id, 6, 8)); //获得出生年月日的时间戳

    $today = strtotime('today'); //获得今日的时间戳

    $diff = floor(($today - $date) / 86400 / 365); //得到两个日期相差的大体年数

//strtotime加上这个年数后得到那日的时间戳后与今日的时间戳相比

    $age = strtotime(substr($id, 6, 8) . '+' . $diff . 'years') > $today ? ($diff + 1) : $diff;

    return $age;
}

/**
 * [api_url]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:API请求地址
 * @englishAnnotation:API request URL address
 * @return              [string] [URL]
 */
function web_url()
{
    return http_type() . $_SERVER['HTTP_HOST'];
}

/**
 * [http_type]
 * @author:cnpscy <[2278757482@qq.com]>
 * @chineseAnnotation:获取http类型：http\https
 * @englishAnnotation:Get the HTTP type: http\https
 * @return             [string] [description]
 */
function http_type(): string
{
    return $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
}

/**
 * 格式化字节大小
 * @param  number $size      字节数
 * @param  string $delimiter 数字和单位分隔符
 * @return string            格式化后的带单位的大小
 */
function format_bytes($size, $delimiter = '') {
    $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    for ($i = 0; $size >= 1024 && $i < 5; $i++) {
        $size /= 1024;
    }
    return $size . $delimiter . $units[$i];
}