<?php
header("Content-type: text/html; charset=utf-8");

session_start();
ob_start();
include_once("../init.php");

$url = $_POST['url'];

if (!validate_url($url))
{
	header("Location:error.php?ecode=E03");
	ob_end_clean();
	exit();
}
if (!validate_user())
{
	header("Location:error.php?ecode=E02");
	ob_end_clean();
	exit();
}

ob_end_clean();
$out = publish($url);
if ($out)
{
	header("Location:success.php?source=".$url."&dest=".$out);
	exit();
}

include_once("../exit.php");

/**
 * 检查传入url地址的合法性
 * @param  [string] $url
 * @return [bool]
 */
function validate_url($url)
{
	if (strstr($url, "cnhubei.com"))
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 检查用户是否已登录，并检查用户是否具有权限
 * @return [bool]
 */
function validate_user()
{
	if
		(	isset($_SESSION['uinfo'])
		&& 	isset($_SESSION['login'])
		&& 	$_SESSION['login'] == 1
		&& 	$_SESSION['uinfo']['previlege-publish'] == 1
		)
	{
		return true;
	}
	else
	{
		return false;
	}
}

/**
 * 发布指定url的文章，并将记录加入数据库
 * @param  [string] $url [源url]
 * @return [bool]      [发布成功的标志]
 */
function publish($url)
{
	$c = curl_init();

	curl_setopt($c, CURLOPT_URL, WAP_INTERFACE."?url=".$url);

	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);

	$output = curl_exec($c);
	curl_close($c);

	global $_SGLOBAL;
	$account = $_SESSION['uinfo']['account'];
	$sql = "INSERT INTO `admin_log` (`account`, `operation`, `object`) VALUES ('$account', 'publish', '$url')";
	$_SGLOBAL['db']->query($sql);

	$wap_url = WAP_ROOT."/".$output;
		return Toolbox::pcurl_to_wapurl($url);
}


?>