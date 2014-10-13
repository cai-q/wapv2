<?php

include_once('models/article_page.class.php');
include_once('models/class_mysql.php');
include_once('models/css_filter.class.php');
include_once('models/dom_reader.class.php');
include_once('models/filter.class.php');
include_once('models/index.class.php');
include_once('models/interface_connector.class.php');
include_once('models/length_filter.class.php');
include_once('models/page.class.php');
include_once('models/photo_page.class.php');
include_once('models/toolbox.class.php');
include_once('models/url_filter.class.php');
include_once('models/video_filter.class.php');


define('D_BUG', '1');

//这里需要改！！！！改成http根目录+/lib
define('S_ROOT', dirname(__FILE__).DIRECTORY_SEPARATOR);
define('HTTP_ROOT', '');
define('STORAGE_MODE', 'auto');//设置存储模式，为自动识别目录
define('STORAGE_DIR', '');
//define('WAP_INTERFACE', 'localhost/waphubei/c_mobile.php');
define('WAP_ROOT', 'http://test.cnhubei.com/wap/waproot');

$_SGLOBAL = $_SCONFIG = $_SBLOCK = $_TPL = $_SCOOKIE = $_SN = $space = array();

//数据库
$_SC = array();
$_SC['dbhost']  		= '10.99.113.11'; //服务器地址
$_SC['dbuser']  		= 'root'; //用户
$_SC['dbpw'] 	 		= 'sadf@r89wbgb'; //密码
$_SC['dbcharset'] 		= 'utf8'; //字符集
$_SC['pconnect'] 		= 0; //是否持续连接
$_SC['dbname']  		= 'wapv2'; //数据库
$_SC['charset'] 		= 'utf-8'; //页面字符集


//时间
$mtime = explode(' ', microtime());
$_SGLOBAL['timestamp'] = $mtime[1];
$_SGLOBAL['supe_starttime'] = $_SGLOBAL['timestamp'] + $mtime[0];

date_default_timezone_set('PRC');

include_once('C:\\xampp\\php\\includes\\smarty\\libs\\Smarty.class.php');

Toolbox::dbconnect();//链接本地mysql数据库

?>