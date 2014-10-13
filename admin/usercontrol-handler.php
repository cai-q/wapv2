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
	'name' => $_POST['name'],
	'password' => $_POST['password'],
	'previlege1' => $previlege1,
	'previlege2' => $previlege2,
	);


$status = alter_user($user);

if ($status)
{
	header("Location:success.php");
	ob_end_flush();
	exit();
}
else
{
	header("Location:error.php");
	ob_end_flush();
	exit();
}

include_once("../exit.php");

function alter_user($user)
{
	global $_SGLOBAL;
	$sql = "SELECT * FROM `user` WHERE `account` = '".$user['account']."'";
	$temp = $_SGLOBAL['db']->query($sql);
	if($_SGLOBAL['db']->num_rows($temp) == 0)
		return false;

	$temp_result = $_SGLOBAL['db']->fetch_array($temp);
	if($temp_result['superuser'] == 1)
		return false;

	$sql = "UPDATE `user` SET `name`='".$user['name']."', `password`=".md5($user['password']).",`previlege-publish`='".$user['previlege1']."', `previlege-delete`='".$user['previlege2']."' WHERE `account`='".$user['account']."'";

	$result = $_SGLOBAL['db']->query($sql);

	return true;
}
?>