<?php
header("Content-type: text/html; charset=utf-8");

session_start();
ob_start();
include_once("../init.php");

$old_password = $_POST['old-password'];
$account = $_SESSION['uinfo']['account'];
$new_password = $_POST['new-password'];

if (!validate_old($account, $old_password))
{
	header("Location:error.php?ecode=E07");
	ob_end_clean();
	exit();
}
if (!validate_password($new_password))
{
	header("Location:error.php?ecode=E06");
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

$out = change_password($account, $new_password);

if($out)
{
	header("Location:logout-handler.php");
	exit();
}

include_once("../exit.php");

function validate_old($account, $password)
{
	global $_SGLOBAL;
	$sql = "SELECT * FROM `user` WHERE `account` = '$account'";
	$result = $_SGLOBAL['db']->query($sql);
	$u = $_SGLOBAL['db']->fetch_array($result);

	if($u['password'] == md5($password))
		return true;
	else
		return false;
}



/**
 * 检查传入url地址的合法性
 * @param  [string] $url
 * @return [bool]
 */
function validate_password($password)
{
	if (strlen($password) < 20)
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
function change_password($account, $password)
{

	global $_SGLOBAL;
	$sql = "UPDATE `user` SET `password`=MD5('$password') WHERE `account`='$account'";
	$_SGLOBAL['db']->query($sql);

	return true;
}
?>