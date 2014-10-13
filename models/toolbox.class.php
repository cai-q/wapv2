<?php
/**
 * toolbox.class.php
 * author: ronaldoooo
 * last modified: 2014-09-22 10:08
 */

/**
 * This is the class where all tool functions locate in.
 * Usually common functions, could be called at any position.
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class Toolbox{

    public static function is_pcurl($url)
    {
        if (!strstr($url, "cnhubei.com"))
            return false;
        if (strstr($url, WAP_ROOT))
            return false;

        return true;
    }

    public static function pcurl_to_wapurl($url)
    {
        $docid = self::get_docid_by_url($url);
        $interface = self::get_interface_by_url($url);

        if($docid)
        {
            $doc = new DOMDocument();
            $doc->load($interface."?id=".$docid);
            $node_list = $doc->getElementsByTagName("article");

            foreach ($node_list as $node) {
                $crdate = date('Ymd',intval(trim($node->getElementsByTagName("article_createtime")->item(0)->nodeValue)));

                return WAP_ROOT.'/'.Toolbox::get_output_location_with_crdate($url, $crdate);
            }
        }
        return false;
    }


    /**
     * 根据url取出interface_address
     * @param  string $url 源网页的url
     * @return string $config 从数据库中取出的config配置
     */
    public static function get_interface_by_url($url)
    {
        $best_fit_url = self::get_best_match_interface(str_replace("http://", "", $url));

        //echo "best:".$best_fit_url;

        global $_SGLOBAL;
        $query = $_SGLOBAL['db']->query("SELECT * FROM url_interface WHERE url = '".$best_fit_url."'");
        $result = $_SGLOBAL['db']->fetch_array($query);

        return $result['interface_address'];
    }

    /**
     * 由文章地址获取文章的id
     * 这里默认文章id在文件名中，且过滤掉文件名中的字母
     * @param  [string] $url [源地址]
     * @return [string]      [文章id]
     */
    public static function get_docid_by_url($url)
    {
        $file_name = substr($url, strrpos($url, "/") + 1);
        $docid = substr($file_name, 0, strpos($file_name, "."));
        if(strstr($docid, "_"))
            $docid = substr($docid, 0, strrpos($docid, "_"));
        return preg_replace('/[a-z]/i', '', $docid);
    }


    /**
     * 获取接口列表，返回值是接口地址-上次处理时间的值对形式
     * @return [array] [接口地址-上次处理时间]的字典数组。上次处理时间指上次处理该接口内文章的最大的pubtime
     */
    public static function get_interface_list ()
    {
        global $_SGLOBAL;
        $query = $_SGLOBAL['db']->query("SELECT `interface_address`, `last_time` FROM platform_interface;");

        $result = array();
        while($value  = $_SGLOBAL['db']->fetch_array($query)) {
            $result[$value['interface_address']] = $value['last_time'];
        }
        return $result;
    }


    /**
     * 将最高的pubtime写入数据库记录
     * @param  [string] $interface [接口地址]
     * @param  [type] $timestamp [时间戳]
     */
    public static function update_max_pubtime($interface, $timestamp)
    {
        global $_SGLOBAL;

        $_SGLOBAL['db']->query("UPDATE `platform_interface` SET `last_time` = $timestamp WHERE `interface_address` = '$interface'");
    }


    // /**
    //  * 根据url取出config
    //  * @param  string $url 源网页的url
    //  * @return string $config 从数据库中取出的config配置
    //  */
    // public static function get_config($url){
    //     $best_fit_url = self::get_best_match_url(str_replace("http://", "", $url));

    //     global $_SGLOBAL;

    //     $query = $_SGLOBAL['db']->query("SELECT * FROM ".Toolbox::tname('collect_rule')." WHERE url = '".$best_fit_url."'");

    //     $result = array();
    //     if($value  = $_SGLOBAL['db']->fetch_array($query)) {
    //             $result[] = $value;
    //     }
    //     return $result[0];
    // }

    /**
     * 根据url获取数据库中存在的最匹配的接口地址，该函数是一个递归的过程。
     * 先由最底层子目录向上回溯，直至不包含一个子目录。
     * 再从最底层子域名向上回溯，直至不含有一个子域名。
     * 如果找到一个恰巧合适的匹配，函数就会跳出。
     *
     * @param  string $source 源url地址。注意该地址应该已经过处理，不包含'http://'头部
     * @return string         数据库中最匹配的接口的url名称
     */
    public static function get_best_match_interface($url) {

        //echo "url:".$url."<br>";

        if(self::perfect_fit($url))
            return $url;//如果恰巧存在匹配，返回该URL

        elseif(strstr($url, "/"))
        {
            $url = substr($url, 0, strrpos($url, "/"));//如果存在一个子目录，去掉末尾子目录，尝试匹配其父级目录
            return self::get_best_match_interface($url);//recursive
        }
        elseif(strstr(str_replace(".cnhubei.com", "", $url), "."))
        {
            $url = substr(strstr($url, "."), 1);//如果存在一个子域名，去掉其子域名，尝试匹配其父级域名
            return self::get_best_match_interface($url);//recursive
        }
        else{
            return false;
        }
    }

    /**
     * 该函数用于寻找一个url是否存在恰巧相符的匹配。
     * 恰巧相符，是指url地址完全一样。该方法用于被get_best_match_url方法调用
     * @param  string $url
     * @return boolean
     */
    public static function perfect_fit($url){
        global $_SGLOBAL;

        $query = $_SGLOBAL['db']->query("SELECT * FROM url_interface WHERE url = '".$url."'");
        //echo "SELECT * FROM url_interface WHERE url = '".$url."'";
        //echo "numrows:".$_SGLOBAL['db']->num_rows($query)."<br>";

        if($_SGLOBAL['db']->num_rows($query))
            return true;
        else
            return false;
    }


    /**
     * 根据url计算文件存放目录，最后返回的结果包含文件名。
     * @param  string $url 源网页的url
     * @return string $dir 文件存放地址，包含目录和文件名
     */
    public static function get_output_location($url){
        if(!strstr($url, "http://"))
            $url = "http://".$url;

        if(STORAGE_MODE == 'auto'){
            $s = explode("/",$url);
            $s[2] = str_replace(".cnhubei.com", "", $s[2]);//去除域名中的“.cnhubei.com”,$s[2]:jm.news.cnhubei.com -> jm.news
            if(strstr($s[2],".")){
                $temp = explode(".", $s[2]);
                $s[2] = $temp[1]."/".$temp[0];//jm.news -> news.jm,把二级域名放到后面
            }
            $dir = $s[2];
            // for($i = 3; $i < sizeof($s) - 1; $i++)
            //     $dir .= "/".$s[$i];

            //此时存放目录已拼接完成
            $tname_list = explode(".", end($s));//获取文章名
            $tname = $tname_list[0];

            $dir .= "/".$tname.".shtml";//把文章名拼接到目录后，得到存放地址
            return $dir;
        }
        else{
            $s = explode("/", $url);
            $tname_list = explode(".", end($s));
            $tname = $tname_list[0];
            $dir = STORAGE_DIR."/".$tname.".shtml";
            return $dir;
        }
    }
    /**
     * 该函数复制了get_output_location的部分代码，是根据url地址获取路径的另一种实现
     * 这个版本的实现中，在路径增加了一层，区分了稿件的创建时间
     * @param  [type] $url    [description]
     * @param  [type] $crdate [description]
     * @return [type]         [description]
     */
    public static function get_output_location_with_crdate($url, $crdate) {
        if(!strstr($url, "http://"))
            $url = "http://".$url;

        if(STORAGE_MODE == 'auto'){
            $s = explode("/",$url);
            $s[2] = str_replace(".cnhubei.com", "", $s[2]);//去除域名中的“.cnhubei.com”,$s[2]:jm.news.cnhubei.com -> jm.news
            if(strstr($s[2],".")){
                $temp = explode(".", $s[2]);
                $s[2] = $temp[1]."/".$temp[0];//jm.news -> news.jm,把二级域名放到后面
            }
            $dir = $s[2];
            // for($i = 3; $i < sizeof($s) - 1; $i++)
            //     $dir .= "/".$s[$i];

            //此时存放目录已拼接完成
            $tname_list = explode(".", end($s));//获取文章名
            $tname = $tname_list[0];

            $dir .= "/".$crdate."/".$tname.".shtml";//把文章名拼接到目录后，得到存放地址
            return $dir;
        }
        else{
            $s = explode("/", $url);
            $tname_list = explode(".", end($s));
            $tname = $tname_list[0];
            $dir = STORAGE_DIR."/".$tname.".shtml";
            return $dir;
        }
    }

    /**
     * 该函数复制了get_output_location_with_crdate的部分代码
     * 这个版本的实现中，在路径增加了一层，区分了稿件的创建时间
     * @param  [type] $url    [description]
     * @param  [type] $crdate [description]
     * @return [type]         [description]
     */
    public static function get_output_dir_with_crdate($url, $crdate) {
        if(!strstr($url, "http://"))
            $url = "http://".$url;

        if(STORAGE_MODE == 'auto'){
            $s = explode("/",$url);
            $s[2] = str_replace(".cnhubei.com", "", $s[2]);//去除域名中的“.cnhubei.com”,$s[2]:jm.news.cnhubei.com -> jm.news
            if(strstr($s[2],".")){
                $temp = explode(".", $s[2]);
                $s[2] = $temp[1]."/".$temp[0];//jm.news -> news.jm,把二级域名放到后面
            }
            $dir = $s[2];
            // for($i = 3; $i < sizeof($s) - 1; $i++)
            //     $dir .= "/".$s[$i];

            //此时存放目录已拼接完成
            $tname_list = explode(".", end($s));//获取文章名
            $tname = $tname_list[0];

            $dir .= "/".$crdate;//把文章名拼接到目录后，得到存放地址
            return $dir;
        }
        else{
            $s = explode("/", $url);
            $tname_list = explode(".", end($s));
            $tname = $tname_list[0];
            $dir = STORAGE_DIR;
            return $dir;
        }
    }

    /**
     * 获取时间
     * @return string 当前时间
     */
    public static function get_time(){
        return date('y-m-d h:i:s',time());
    }

    /**
     * 获取月份
     * @return string 当前月份
     */
    public static function get_month(){
        return date('Ym',time());
    }

    /**
    * 获取远程HTML
    * @param string $url    获取地址
    * @param array $config  配置
    */
    public static function get_html($url, &$config) {

        global $_SC;
        if (!empty($url) && $html = @file_get_contents($url)) {
        //原网页非配置的编码时进行编码转换
            if ($_SC['charset'] != $config['sourcecharset']) {
                //$html = iconv($config['sourcecharset'], $_SC['charset'].'//IGNORE', $html);
                $html = iconv($config['sourcecharset'],"utf-8".'//IGNORE', $html);
            }
            return $html;
        } else {
            return false;
        }
    }


    //SQL ADDSLASHES
    public static function saddslashes($string) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = saddslashes($val);
            }
        } else {
            $string = addslashes($string);
        }
        return $string;
    }

    //取消HTML代码
    public static function shtmlspecialchars($string) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = shtmlspecialchars($val);
            }
        } else {
            $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1',
                str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
        }
        return $string;
    }

    //字符串解密加密
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {

        $ckey_length = 4;   // 随机密钥长度 取值 0-32;
                    // 加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
                    // 取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
                    // 当此值为 0 时，则不产生随机密钥

        $key = md5($key ? $key : UC_KEY);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya.md5($keya.$keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if($operation == 'DECODE') {
            if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return $keyc.str_replace('=', '', base64_encode($result));
        }
    }
    //写运行日志
    public static function runlog($file, $log, $halt=0) {
        global $_SGLOBAL, $_SERVER;

        $nowurl = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
        $log = sgmdate('Y-m-d H:i:s', $_SGLOBAL['timestamp'])."\t$type\t".getonlineip()."\t$_SGLOBAL[supe_uid]\t{$nowurl}\t".str_replace(array("\r", "\n"), array(' ', ' '), trim($log))."\n";
        $yearmonth = sgmdate('Ym', $_SGLOBAL['timestamp']);
        $logdir = './data/log/';
        if(!is_dir($logdir)) mkdir($logdir, 0777);
        $logfile = $logdir.$yearmonth.'_'.$file.'.php';
        if(@filesize($logfile) > 2048000) {
            $dir = opendir($logdir);
            $length = strlen($file);
            $maxid = $id = 0;
            while($entry = readdir($dir)) {
                if(strexists($entry, $yearmonth.'_'.$file)) {
                    $id = intval(substr($entry, $length + 8, -4));
                    $id > $maxid && $maxid = $id;
                }
            }
            closedir($dir);
            $logfilebak = $logdir.$yearmonth.'_'.$file.'_'.($maxid + 1).'.php';
            @rename($logfile, $logfilebak);
        }
        if($fp = @fopen($logfile, 'a')) {
            @flock($fp, 2);
            fwrite($fp, "<?PHP exit;?>\t".str_replace(array('<?', '?>', "\r", "\n"), '', $log)."\n");
            fclose($fp);
        }
        if($halt) exit();
    }

    //获取目录
    public static function sreaddir($dir, $extarr=array()) {
        $dirs = array();
        if($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if(!empty($extarr) && is_array($extarr)) {
                    if(in_array(strtolower(fileext($file)), $extarr)) {
                        $dirs[] = $file;
                    }
                } else if($file != '.' && $file != '..') {
                    $dirs[] = $file;
                }
            }
            closedir($dh);
        }
        return $dirs;
    }

    //时间格式化
    public static function sgmdate($dateformat, $timestamp='', $format=0) {
        global $_SCONFIG, $_SGLOBAL;
        if(empty($timestamp)) {
            $timestamp = $_SGLOBAL['timestamp'];
        }
        $timeoffset = strlen($_SGLOBAL['member']['timeoffset'])>0?intval($_SGLOBAL['member']['timeoffset']):intval($_SCONFIG['timeoffset']);
        $result = '';
        if($format) {
            $time = $_SGLOBAL['timestamp'] - $timestamp;
            if($time > 24*3600) {
                $result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
            } elseif ($time > 3600) {
                $result = intval($time/3600).lang('hour').lang('before');
            } elseif ($time > 60) {
                $result = intval($time/60).lang('minute').lang('before');
            } elseif ($time > 0) {
                $result = $time.lang('second').lang('before');
            } else {
                $result = lang('now');
            }
        } else {
            $result = gmdate($dateformat, $timestamp + $timeoffset * 3600);
        }
        return $result;
    }

    //字符串时间化
    public static function sstrtotime($string) {
        global $_SGLOBAL, $_SCONFIG;
        $time = '';
        if($string) {
            $time = strtotime($string);
            if(gmdate('H:i', $_SGLOBAL['timestamp'] + $_SCONFIG['timeoffset'] * 3600) != date('H:i', $_SGLOBAL['timestamp'])) {
                $time = $time - $_SCONFIG['timeoffset'] * 3600;
            }
        }
        return $time;
    }

    //数据库连接
    public static function dbconnect() {
        global $_SGLOBAL, $_SC;

        if(empty($_SGLOBAL['db'])) {
            $_SGLOBAL['db'] = new dbstuff;
            $_SGLOBAL['db']->charset = $_SC['dbcharset'];
            $_SGLOBAL['db']->connect($_SC['dbhost'], $_SC['dbuser'], $_SC['dbpw'], $_SC['dbname'], $_SC['pconnect']);
        }
    }

    public static function dbclose() {
        global $_SGLOBAL, $_SC;
        if(!empty($_SGLOBAL['db'])) {
            $_SGLOBAL['db']->close();
        }
    }

    //获取在线IP
    public static function getonlineip($format=0) {
        global $_SGLOBAL;

        if(empty($_SGLOBAL['onlineip'])) {
            if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
                $onlineip = getenv('HTTP_CLIENT_IP');
            } elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
                $onlineip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
                $onlineip = getenv('REMOTE_ADDR');
            } elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
                $onlineip = $_SERVER['REMOTE_ADDR'];
            }
            preg_match("/[\d\.]{7,15}/", $onlineip, $onlineipmatches);
            $_SGLOBAL['onlineip'] = $onlineipmatches[0] ? $onlineipmatches[0] : 'unknown';
        }
        if($format) {
            $ips = explode('.', $_SGLOBAL['onlineip']);
            for($i=0;$i<3;$i++) {
                $ips[$i] = intval($ips[$i]);
            }
            return sprintf('%03d%03d%03d', $ips[0], $ips[1], $ips[2]);
        } else {
            return $_SGLOBAL['onlineip'];
        }
    }

     //语言替换
    public static function lang_replace($text, $vars) {
        if($vars) {
            foreach ($vars as $k => $v) {
                $rk = $k + 1;
                $text = str_replace('\\'.$rk, $v, $text);
            }
        }
        return $text;
    }


    //获取到表名
    public static function tname($name) {
        global $_SC;
        return $_SC['tablepre'].$name;
    }

    //对话框
    public static function showmessage($msgkey, $url_forward='', $second=1, $values=array()) {
        global $_SGLOBAL, $_SC, $_SCONFIG, $_TPL, $space, $_SN;

        obclean();

        //去掉广告
        $_SGLOBAL['ad'] = array();
        //语言
        include_once(S_ROOT.'./language/lang_showmessage.php');
        if(isset($_SGLOBAL['msglang'][$msgkey])) {
            $message = lang_replace($_SGLOBAL['msglang'][$msgkey], $values);
        } else {
            $message = $msgkey;
        }
        //手机
        if($_SGLOBAL['mobile']) {
            include template('showmessage');
            exit();
        }
        //显示
        if(empty($_SGLOBAL['inajax']) && $url_forward && empty($second)) {
            header("HTTP/1.1 301 Moved Permanently");
            header("Location: $url_forward");
        } else {
            if($_SGLOBAL['inajax']) {
                if($url_forward) {
                    $message = "<a href=\"$url_forward\">$message</a><ajaxok>";
                }
                //$message = "<h1>".$_SGLOBAL['msglang']['box_title']."</h1><a href=\"javascript:;\" onclick=\"hideMenu();\" class=\"float_del\">X</a><div class=\"popupmenu_inner\">$message</div>";
                echo $message;
                ob_out();
            } else {
                if($url_forward) {
                    $message = "<a href=\"$url_forward\">$message</a><script>setTimeout(\"window.location.href ='$url_forward';\", ".($second*1000).");</script>";
                }
                include template('showmessage');
            }
        }
        exit();
    }

    //判断提交是否正确
    public static function submitcheck($var) {
        if(!empty($_POST[$var]) && $_SERVER['REQUEST_METHOD'] == 'POST') {
            if((empty($_SERVER['HTTP_REFERER']) || preg_replace("/https?:\/\/([^\:\/]+).*/i", "\\1", $_SERVER['HTTP_REFERER']) == preg_replace("/([^\:]+).*/", "\\1", $_SERVER['HTTP_HOST'])) && $_POST['formhash'] == formhash()) {
                return true;
            } else {
                showmessage('submit_invalid');
            }
        } else {
            return false;
        }
    }

    //添加数据
    public static function inserttable($tablename, $insertsqlarr, $returnid=0, $replace = false, $silent=0) {
        global $_SGLOBAL;

        $insertkeysql = $insertvaluesql = $comma = '';
        foreach ($insertsqlarr as $insert_key => $insert_value) {
            $insertkeysql .= $comma.'`'.$insert_key.'`';
            $insertvaluesql .= $comma.'\''.$insert_value.'\'';
            $comma = ', ';
        }
        $method = $replace?'REPLACE':'INSERT';
        $_SGLOBAL['db']->query($method.' INTO '.tname($tablename).' ('.$insertkeysql.') VALUES ('.$insertvaluesql.')', $silent?'SILENT':'');
        if($returnid && !$replace) {
            return $_SGLOBAL['db']->insert_id();
        }
    }

    //更新数据
    public static function updatetable($tablename, $setsqlarr, $wheresqlarr, $silent=0) {
        global $_SGLOBAL;

        $setsql = $comma = '';
        foreach ($setsqlarr as $set_key => $set_value) {//fix
            $setsql .= $comma.'`'.$set_key.'`'.'=\''.$set_value.'\'';
            $comma = ', ';
        }
        $where = $comma = '';
        if(empty($wheresqlarr)) {
            $where = '1';
        } elseif(is_array($wheresqlarr)) {
            foreach ($wheresqlarr as $key => $value) {
                $where .= $comma.'`'.$key.'`'.'=\''.$value.'\'';
                $comma = ' AND ';
            }
        } else {
            $where = $wheresqlarr;
        }
        $_SGLOBAL['db']->query('UPDATE '.tname($tablename).' SET '.$setsql.' WHERE '.$where, $silent?'SILENT':'');
    }


    //模板调用
    public static function template($name) {
        global $_SCONFIG, $_SGLOBAL;

        if($_SGLOBAL['mobile']) {
            $objfile = S_ROOT.'./api/mobile/tpl_'.$name.'.php';
            if (!file_exists($objfile)) {
                showmessage('m_public static function_is_disable_on_wap');
            }
        } else {
            if(strexists($name,'/')) {
                $tpl = $name;
            } else {
                $tpl = "template/$_SCONFIG[template]/$name";
            }
            $objfile = S_ROOT.'./data/tpl_cache/'.str_replace('/','_',$tpl).'.php';
            if(!file_exists($objfile)) {
                include_once(S_ROOT.'./source/public static function_template.php');
                parse_template($tpl);
            }
        }
        return $objfile;
    }

    //子模板更新检查
    public static function subtplcheck($subfiles, $mktime, $tpl) {
        global $_SC, $_SCONFIG;

        if($_SC['tplrefresh'] && ($_SC['tplrefresh'] == 1 || mt_rand(1, $_SC['tplrefresh']) == 1)) {
            $subfiles = explode('|', $subfiles);
            foreach ($subfiles as $subfile) {
                $tplfile = S_ROOT.'./'.$subfile.'.htm';
                if(!file_exists($tplfile)) {
                    $tplfile = str_replace('/'.$_SCONFIG['template'].'/', '/default/', $tplfile);
                }
                @$submktime = filemtime($tplfile);
                if($submktime > $mktime) {
                    include_once(S_ROOT.'./source/public static function_template.php');
                    parse_template($tpl);
                    break;
                }
            }
        }
    }
    //ob
    public static function obclean() {
        global $_SC;

        ob_end_clean();
        if ($_SC['gzipcompress'] && function_exists('ob_gzhandler')) {
            ob_start('ob_gzhandler');
        } else {
            ob_start();
        }
    }
    //模块
    public static function block($param) {
        global $_SBLOCK;

        include_once(S_ROOT.'./source/public static function_block.php');
        block_batch($param);
    }

    //获取数目
    public static function getcount($tablename, $wherearr=array(), $get='COUNT(*)') {
        global $_SGLOBAL;
        if(empty($wherearr)) {
            $wheresql = '1';
        } else {
            $wheresql = $mod = '';
            foreach ($wherearr as $key => $value) {
                $wheresql .= $mod."`$key`='$value'";
                $mod = ' AND ';
            }
        }
        return $_SGLOBAL['db']->result($_SGLOBAL['db']->query("SELECT $get FROM ".tname($tablename)." WHERE $wheresql LIMIT 1"), 0);
    }

    //调整输出
    public static function ob_out() {
        global $_SGLOBAL, $_SCONFIG, $_SC;

        $content = ob_get_contents();

        $preg_searchs = $preg_replaces = $str_searchs = $str_replaces = array();

        if($_SCONFIG['allowrewrite']) {
            $preg_searchs[] = "/\<a href\=\"space\.php\?(uid|do)+\=([a-z0-9\=\&]+?)\"/ie";
            $preg_searchs[] = "/\<a href\=\"space.php\"/i";
            $preg_searchs[] = "/\<a href\=\"network\.php\?ac\=([a-z0-9\=\&]+?)\"/ie";
            $preg_searchs[] = "/\<a href\=\"network.php\"/i";

            $preg_replaces[] = 'rewrite_url(\'space-\',\'\\2\')';
            $preg_replaces[] = '<a href="space.html"';
            $preg_replaces[] = 'rewrite_url(\'network-\',\'\\1\')';
            $preg_replaces[] = '<a href="network.html"';
        }
        if($_SCONFIG['linkguide']) {
            $preg_searchs[] = "/\<a href\=\"http\:\/\/(.+?)\"/ie";
            $preg_replaces[] = 'iframe_url(\'\\1\')';
        }

        if($_SGLOBAL['inajax']) {
            $preg_searchs[] = "/([\x01-\x09\x0b-\x0c\x0e-\x1f])+/";
            $preg_replaces[] = ' ';

            $str_searchs[] = ']]>';
            $str_replaces[] = ']]&gt;';
        }

        if($preg_searchs) {
            $content = preg_replace($preg_searchs, $preg_replaces, $content);
        }
        if($str_searchs) {
            $content = trim(str_replace($str_searchs, $str_replaces, $content));
        }

        obclean();
        if($_SGLOBAL['inajax']) {
            xml_out($content);
        } else{
            if($_SCONFIG['headercharset']) {
                @header('Content-Type: text/html; charset='.$_SC['charset']);
            }
            echo $content;
            if(D_BUG) {
                @include_once(S_ROOT.'./source/inc_debug.php');
            }
        }
    }

    public static function xml_out($content) {
        global $_SC;
        @header("Expires: -1");
        @header("Cache-Control: no-store, private, post-check=0, pre-check=0, max-age=0", FALSE);
        @header("Pragma: no-cache");
        @header("Content-type: application/xml; charset=$_SC[charset]");
        echo '<'."?xml version=\"1.0\" encoding=\"$_SC[charset]\"?>\n";
        echo "<root><![CDATA[".trim($content)."]]></root>";
        exit();
    }

    //rewrite链接
    public static function rewrite_url($pre, $para) {
        $para = str_replace(array('&','='), array('-', '-'), $para);
        return '<a href="'.$pre.$para.'.html"';
    }

    //外链
    public static function iframe_url($url) {
        $url = rawurlencode($url);
        return "<a href=\"link.php?url=http://$url\"";
    }

    //处理搜索关键字
    public static function stripsearchkey($string) {
        $string = trim($string);
        $string = str_replace('*', '%', addcslashes($string, '%_'));
        $string = str_replace('_', '\_', $string);
        return $string;
    }

    //检查搜索
    public static function cksearch($theurl) {
        global $_SGLOBAL, $_SCONFIG, $space;

        $theurl = stripslashes($theurl)."&page=".$_GET['page'];
        if($searchinterval = checkperm('searchinterval')) {
            $waittime = $searchinterval - ($_SGLOBAL['timestamp'] - $space['lastsearch']);
            if($waittime > 0) {
                showmessage('search_short_interval', '', 1, array($waittime, $theurl));
            }
        }
        if(!checkperm('searchignore')) {
            $reward = getreward('search', 0);
            if($reward['credit'] || $reward['experience']) {
                if(empty($_GET['confirm'])) {
                    $theurl .= '&confirm=yes';
                    showmessage('points_deducted_yes_or_no', '', 1, array($reward['credit'], $reward['experience'], $theurl));
                } else {
                    if($space['credit'] < $reward['credit'] || $space['experience'] < $reward['experience']) {
                        showmessage('points_search_error');
                    } else {
                        //扣分
                        $_SGLOBAL['db']->query("UPDATE ".tname('space')." SET lastsearch='$_SGLOBAL[timestamp]', credit=credit-$reward[credit], experience=experience-$reward[experience] WHERE uid='$_SGLOBAL[supe_uid]'");
                    }
                }
            }
        }
    }

    //是否屏蔽二级域名
    public static function isholddomain($domain) {
        global $_SCONFIG;

        $domain = strtolower($domain);

        if(preg_match("/^[^a-z]/i", $domain)) return true;
        $holdmainarr = empty($_SCONFIG['holddomain'])?array('www'):explode('|', $_SCONFIG['holddomain']);
        $ishold = false;
        foreach ($holdmainarr as $value) {
            if(strpos($value, '*') === false) {
                if(strtolower($value) == $domain) {
                    $ishold = true;
                    break;
                }
            } else {
                $value = str_replace('*', '', $value);
                if(@preg_match("/$value/i", $domain)) {
                    $ishold = true;
                    break;
                }
            }
        }
        return $ishold;
    }

    //连接字符
    public static function simplode($ids) {
        return "'".implode("','", $ids)."'";
    }

    //显示进程处理时间
    public static function debuginfo() {
        global $_SGLOBAL, $_SC, $_SCONFIG;

        if(empty($_SCONFIG['debuginfo'])) {
            $info = '';
        } else {
            $mtime = explode(' ', microtime());
            $totaltime = number_format(($mtime[1] + $mtime[0] - $_SGLOBAL['supe_starttime']), 4);
            $info = 'Processed in '.$totaltime.' second(s), '.$_SGLOBAL['db']->querynum.' queries'.
                    ($_SC['gzipcompress'] ? ', Gzip enabled' : NULL);
        }

        return $info;
    }

    //格式化大小函数
    public static function formatsize($size) {
        $prec=3;
        $size = round(abs($size));
        $units = array(0=>" B ", 1=>" KB", 2=>" MB", 3=>" GB", 4=>" TB");
        if ($size==0) return str_repeat(" ", $prec)."0$units[0]";
        $unit = min(4, floor(log($size)/log(2)/10));
        $size = $size * pow(2, -10*$unit);
        $digi = $prec - 1 - floor(log($size)/log(10));
        $size = round($size * pow(10, $digi)) * pow(10, -$digi);
        return $size.$units[$unit];
    }

    //获取文件内容
    public static function sreadfile($filename) {
        $content = '';
        if(function_exists('file_get_contents')) {
            @$content = file_get_contents($filename);
        } else {
            if(@$fp = fopen($filename, 'r')) {
                @$content = fread($fp, filesize($filename));
                @fclose($fp);
            }
        }
        return $content;
    }

    //写入文件
    public static function swritefile($filename, $writetext, $openmod='w') {
        if(@$fp = fopen($filename, $openmod)) {
            flock($fp, 2);
            fwrite($fp, $writetext);
            fclose($fp);
            return true;
        } else {
            runlog('error', "File: $filename write error.");
            return false;
        }
    }

    //产生随机字符
    public static function random($length, $numeric = 0) {
        PHP_VERSION < '4.2.0' ? mt_srand((double)microtime() * 1000000) : mt_srand();
        $seed = base_convert(md5(print_r($_SERVER, 1).microtime()), 16, $numeric ? 10 : 35);
        $seed = $numeric ? (str_replace('0', '', $seed).'012340567890') : ($seed.'zZ'.strtoupper($seed));
        $hash = '';
        $max = strlen($seed) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $seed[mt_rand(0, $max)];
        }
        return $hash;
    }

    //判断字符串是否存在
    public static function strexists($haystack, $needle) {
        return !(strpos($haystack, $needle) === FALSE);
    }

    //获取数据
    public static function data_get($var, $isarray=0) {
        global $_SGLOBAL;

        $query = $_SGLOBAL['db']->query("SELECT * FROM ".tname('data')." WHERE var='$var' LIMIT 1");
        if($value = $_SGLOBAL['db']->fetch_array($query)) {
            return $isarray?$value:$value['datavalue'];
        } else {
            return '';
        }
    }

    //更新数据
    public static function data_set($var, $datavalue, $clean=0) {
        global $_SGLOBAL;

        if($clean) {
            $_SGLOBAL['db']->query("DELETE FROM ".tname('data')." WHERE var='$var'");
        } else {
            if(is_array($datavalue)) $datavalue = serialize(sstripslashes($datavalue));
            $_SGLOBAL['db']->query("REPLACE INTO ".tname('data')." (var, datavalue, dateline) VALUES ('$var', '".addslashes($datavalue)."', '$_SGLOBAL[timestamp]')");
        }
    }

    //检查站点是否关闭
    public static function checkclose() {
        global $_SGLOBAL, $_SCONFIG;

        //站点关闭
        if($_SCONFIG['close'] && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
            if(empty($_SCONFIG['closereason'])) {
                showmessage('site_temporarily_closed');
            } else {
                showmessage($_SCONFIG['closereason']);
            }
        }
        //IP访问检查
        if((!ipaccess($_SCONFIG['ipaccess']) || ipbanned($_SCONFIG['ipbanned'])) && !ckfounder($_SGLOBAL['supe_uid']) && !checkperm('closeignore')) {
            showmessage('ip_is_not_allowed_to_visit');
        }
    }

    //站点链接
    public static function getsiteurl() {
        global $_SCONFIG;

        if(empty($_SCONFIG['siteallurl'])) {
            $uri = $_SERVER['REQUEST_URI']?$_SERVER['REQUEST_URI']:($_SERVER['PHP_SELF']?$_SERVER['PHP_SELF']:$_SERVER['SCRIPT_NAME']);
            return shtmlspecialchars('http://'.$_SERVER['HTTP_HOST'].substr($uri, 0, strrpos($uri, '/')+1));
        } else {
            return $_SCONFIG['siteallurl'];
        }
    }

    //获取文件名后缀
    public static function fileext($filename) {
        return strtolower(trim(substr(strrchr($filename, '.'), 1)));
    }

    //去掉slassh
    public static function sstripslashes($string) {
        if(is_array($string)) {
            foreach($string as $key => $val) {
                $string[$key] = sstripslashes($val);
            }
        } else {
            $string = stripslashes($string);
        }
        return $string;
    }

    //显示广告
    public static function adshow($pagetype) {
        global $_SGLOBAL;

        @include_once(S_ROOT.'./data/data_ad.php');
        if(empty($_SGLOBAL['ad']) || empty($_SGLOBAL['ad'][$pagetype])) return false;
        $ads = $_SGLOBAL['ad'][$pagetype];
        $key = mt_rand(0, count($ads)-1);
        $id = $ads[$key];
        $file = S_ROOT.'./data/adtpl/'.$id.'.htm';
        echo sreadfile($file);
    }

    //编码转换
    public static function siconv($str, $out_charset, $in_charset='') {
        global $_SC;

        $in_charset = empty($in_charset)?strtoupper($_SC['charset']):strtoupper($in_charset);
        $out_charset = strtoupper($out_charset);
        if($in_charset != $out_charset) {
            if (function_exists('iconv') && (@$outstr = iconv("$in_charset//IGNORE", "$out_charset//IGNORE", $str))) {
                return $outstr;
            } elseif (function_exists('mb_convert_encoding') && (@$outstr = mb_convert_encoding($str, $out_charset, $in_charset))) {
                return $outstr;
            }
        }
        return $str;//转换失败
    }

    //获取用户数据
    public static function getpassport($username, $password) {
        global $_SGLOBAL, $_SC;

        $passport = array();
        if(!@include_once S_ROOT.'./uc_client/client.php') {
            showmessage('system_error');
        }

        $ucresult = uc_user_login($username, $password);
        if($ucresult[0] > 0) {
            $passport['uid'] = $ucresult[0];
            $passport['username'] = $ucresult[1];
            $passport['email'] = $ucresult[3];
        }
        return $passport;
    }

    //用户操作时间间隔检查
    public static function interval_check($type) {
        global $_SGLOBAL, $space;

        $intervalname = $type.'interval';
        $lastname = 'last'.$type;

        $waittime = 0;
        if($interval = checkperm($intervalname)) {
            $lasttime = isset($space[$lastname])?$space[$lastname]:getcount('space', array('uid'=>$_SGLOBAL['supe_uid']), $lastname);
            $waittime = $interval - ($_SGLOBAL['timestamp'] - $lasttime);
        }
        return $waittime;
    }

    //处理上传图片连接
    public static function pic_get($filepath, $thumb, $remote, $return_thumb=1) {
        global $_SCONFIG, $_SC;

        if(empty($filepath)) {
            $url = 'image/nopic.gif';
        } else {
            $url = $filepath;
            if($return_thumb && $thumb) $url .= '.thumb.jpg';
            if($remote) {
                $url = $_SCONFIG['ftpurl'].$url;
            } else {
                $url = $_SC['attachurl'].$url;
            }
        }

        return $url;
    }

    //获得封面图片链接
    public static function pic_cover_get($pic, $picflag) {
        global $_SCONFIG, $_SC;

        if(empty($pic)) {
            $url = 'image/nopic.gif';
        } else {
            if($picflag == 1) {//本地
                $url = $_SC['attachurl'].$pic;
            } elseif ($picflag == 2) {//远程
                $url = $_SCONFIG['ftpurl'].$pic;
            } else {//网络
                $url = $pic;
            }
        }

        return $url;
    }



    //获取好友状态
    public static function getfriendstatus($uid, $fuid) {
        global $_SGLOBAL;

        $query = $_SGLOBAL['db']->query("SELECT status FROM ".tname('friend')." WHERE uid='$uid' AND fuid='$fuid' LIMIT 1");
        if($value = $_SGLOBAL['db']->fetch_array($query)) {
            return $value['status'];
        } else {
            return -1;//没有记录
        }
    }

    //重新组建
    public static function renum($array) {
        $newnums = $nums = array();
        foreach ($array as $id => $num) {
            $newnums[$num][] = $id;
            $nums[$num] = $num;
        }
        return array($nums, $newnums);
    }




    //ip访问允许
    public static function ipaccess($ipaccess) {
        return empty($ipaccess)?true:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipaccess, '/')).")/", getonlineip());
    }

    //ip访问禁止
    public static function ipbanned($ipbanned) {
        return empty($ipbanned)?false:preg_match("/^(".str_replace(array("\r\n", ' '), array('|', ''), preg_quote($ipbanned, '/')).")/", getonlineip());
    }


    //截取链接
    public static function sub_url($url, $length) {
        if(strlen($url) > $length) {
            $url = str_replace(array('%3A', '%2F'), array(':', '/'), rawurlencode($url));
            $url = substr($url, 0, intval($length * 0.5)).' ... '.substr($url, - intval($length * 0.3));
        }
        return $url;
    }

    //产生form防伪码
    public static function formhash() {
        global $_SGLOBAL, $_SCONFIG;

        if(empty($_SGLOBAL['formhash'])) {
            $hashadd = defined('IN_ADMINCP') ? 'Only For UCenter Home AdminCP' : '';
            $_SGLOBAL['formhash'] = substr(md5(substr($_SGLOBAL['timestamp'], 0, -7).'|'.$_SGLOBAL['supe_uid'].'|'.md5($_SCONFIG['sitekey']).'|'.$hashadd), 8, 8);
        }
        return $_SGLOBAL['formhash'];
    }

}
?>