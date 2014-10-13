<?php
session_start();
ob_start();

include_once("../init.php");

$previlege = $_POST['previlege'];
$previlege1 = 0;
$previlege2 = 0;

foreach ($previlege as $key => $value) {
	if($value == "previlege1")
		$previlege1 = 1;
	if($value == "previlege2")
		$previlege2 = 1;
}

$user = array('account' => $_POST['account'],
	'password' => $_POST['password'],
	'name' => $_POST['name'],
	'previlege1' => $previlege1,
	'previlege2' => $previlege2,
	);


$status = alter_user($user);

if ($status)
{
	header("Location:success.php?info=userlist.php");
	ob_end_flush();
	exit();
}
else
{
	header("Location:error.php?ecode=E04");
	ob_end_flush();
	exit();
}

include_once("../exit.php");

function alter_user($user)
{
	global $_SGLOBAL;
	$sql = "SELECT * FROM `user` WHERE `account` = '".$user['account']."'";
	$temp = $_SGLOBAL['db']->query($sql);
	if($_SGLOBAL['db']->num_rows($temp) != 0)
		return false;

	$sql = "INSERT INTO `user` (`account`, `password`, `name`, `previlege-publish`, `previlege-delete`) VALUES ('".$user['account']."', '".md5($user['password'])."', '".$user['name']."', '".$user['previlege1']."', '".$user['previlege2']."')";

	$result = $_SGLOBAL['db']->query($sql);

	return true;
}
?>