<?php

/**
 * transform_index.php
 * author: ronaldoooo
 * last modified: 2014-09-29 08:00
 * -------------------------------------------------------------------------------------------------------------
 * ---------------------------------------------Transform index-------------------------------------------------
 * -------------------------------------------------------------------------------------------------------------
 */
include_once("init.php");

$index_xml_url = "http://www.cnhubei.com/index_data_3804.xml";
$index_url = "http://www.cnhubei.com";

$index = new Index($index_xml_url, $index_url);

$index->init();
$index->readDom();

$smarty = new Smarty();
$smarty->assign('block_list', $index->getBlockList());

$contect = $smarty->fetch("gmw.tpl");

$fp = fopen("waproot/index2.html", "w+");
fwrite($fp, $contect);
fclose($fp);

include_once("exit.php");
?>
