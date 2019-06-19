<?php
// 检测PHP环境
if(version_compare(PHP_VERSION,'7.1','<'))  die('PHP版本最低 7.1');

// 系统版本
define('APPLICATION_VERSION', 'v1.0.0');

// 定义系统目录分隔符
define('DS', '/');

// HTTP类型
define('__MY_HTTP__', ((!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || (!empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')) ? 'https' : 'http');

// 根目录
$my_root = empty($_SERVER['SCRIPT_NAME']) ? '' : substr($_SERVER['SCRIPT_NAME'], 1, strrpos($_SERVER['SCRIPT_NAME'], '/'));
define('__MY_ROOT__', defined('IS_ROOT_ACCESS') ? $my_root : str_replace('public'.DS, '', $my_root));
define('__MY_ROOT_PUBLIC__', defined('IS_ROOT_ACCESS') ? DS.$my_root.'public'.DS : DS.$my_root);

// 项目HOST
define('__MY_HOST__', empty($_SERVER['HTTP_HOST']) ? '' : $_SERVER['HTTP_HOST']);

// 项目URL地址
define('__MY_URL__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__.'://'.__MY_HOST__.DS.$my_root);

// 项目public目录URL地址
define('__MY_PUBLIC_URL__',  empty($_SERVER['HTTP_HOST']) ? '' : __MY_HTTP__.'://'.__MY_HOST__.__MY_ROOT_PUBLIC__);

// 当前页面url地址
$request_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
define('__MY_VIEW_URL__', substr(__MY_URL__, 0, -1).$request_url);

// 系统根目录
define('ROOT_PATH', dirname(__FILE__).DS);

// 系统根目录 去除public
define('ROOT', str_replace('public'.DS, '', ROOT_PATH));

// 定义应用目录
define('APP_PATH', ROOT.'application'.DS);

// 请求应用 [web, app] 默认web
define('APPLICATION', empty($_REQUEST['application']) ? 'web' : trim($_REQUEST['application']));

// 请求客户端 [default, ...] 默认default
define('APPLICATION_CLIENT', empty($_REQUEST['application_client']) ? 'default' : trim($_REQUEST['application_client']));

// 请求客户端 [pc, h5, alipay, weixin, baidu] 默认pc
define('APPLICATION_CLIENT_TYPE', empty($_REQUEST['application_client_type']) ? 'pc' : trim($_REQUEST['application_client_type']));
