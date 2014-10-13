<?php
session_start();
ob_start();

include_once("../init.php");

$account = $_POST['account'];
$password = $_POST['password'];

$uinfo = validate($account, $password);

if ($uinfo)
{
	$_SESSION['login'] = 1;
	$_SESSION['uinfo'] = $uinfo;

	$_SESSION['uinfo']['password'] = "********";

	header("Location:index.php");
	ob_end_flush();
	exit();
}
else
{
	header("Location:error.php?ecode=E01");
	ob_end_flush();
	exit();
}

include_once("../exit.php");

function validate($account, $password)
{
	global $_SGLOBAL;
	$sql = "SELECT * FROM `user` WHERE `account` = '$account' AND `password` = MD5('$password')";

	$result = $_SGLOBAL['db']->query($sql);

	if($_SGLOBAL['db']->num_rows($result) == 1)
	{
		return  $_SGLOBAL['db']->fetch_array($result);
	}
	else
	{
		return false;
	}
}
?>