<?php
header("Content-type: text/html; charset=utf-8");

session_start();
ob_start();
include_once("../init.php");
ob_end_clean();
$url = $_POST['url'];
if (!validate_user())
{
	header("Location:error.php?ecode=E02");
	exit();
}
$source = "";
$dest = "";

$url_type = validate_url($url);

if (!$url_type)
{
	header("Location:error.php?ecode=E03");
	exit();
}
else
{
	if($url_type == 1)
	{
		$dest = $url;
	}
	else if($url_type == 2)
	{
		$source = $url;
		$dest = Toolbox::pcurl_to_wapurl($url);
	}
}



$out = delete($dest);
if ($out)
{
	header("Location:success.php?source=".$source."&dest=".$dest);
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
	if (strstr($url, WAP_ROOT))
	{
		return 1;
	}
	else if (strstr($url, "cnhubei.com")) {
		return 2;
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
		&& 	$_SESSION['uinfo']['previlege-delete'] == 1
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
function delete($url)
{
	//---
	$file = "../waproot".str_replace(WAP_ROOT, "", $url);
	if(file_exists($file))
		unlink($file);
	//----

	global $_SGLOBAL;
	$account = $_SESSION['uinfo']['account'];
	$sql = "INSERT INTO `waphubei`.`admin_log` (`account`, `operation`, `object`) VALUES ('$account', 'delete', '$url')";
	$_SGLOBAL['db']->query($sql);

	return $url;
}
?>