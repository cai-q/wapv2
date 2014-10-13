<?php

/**
 * transform.php
 * author: ronaldoooo
 * last modified: 2014-09-24 15:07
 *
 * -------------------------------------------------------------------------------------------------------------
 * ---------------------Transform single article, a specific pc url must be given.------------------------------
 * -------------------------------------------------------------------------------------------------------------
 */


include_once("init.php");

/**
 * Get the url from the parameter list.
 * @var string
 */
$url = empty($_GET['url'])?"":$_GET['url'];

/**
 * Exit if no url is given.
 */
if(!$url)
{
	exit();
}

/**
 * Get the document id in the url.
 * @var string
 */
$docid = Toolbox::get_docid_by_url($url);

/**
 * Decide which interface is to call.
 * @var string
 */
$interface = Toolbox::get_interface_by_url($url);

/**
 * Adapt the url and parameter.
 * @var string
 */
$connect_url = $interface."?id=".$docid;

/**
 * Initial an interface connection, using the specific url and last pubtime.
 * @var InterfaceConnector
 */
$interface_conn = new InterfaceConnector($connect_url);

/**
 * Receive the xml infomation the interface has given, then initial a dom reader.
 * @var DomReader
 */
$reader = new DomReader($interface_conn->connect());

/**
 * Read the infomation in xml format.
 * A list of Pages are initialed during this process.
 */
$reader->read();

/**
 * Get out the page list stored in the DomReader.
 * @var array
 */
$page_list = $reader->getPageList();

/**
 * Scan the page list, transform every available page.
 */
foreach ($page_list as $page)
{
	/**
	 * Take a copy of page.
	 * Serialize, then unserialize it to make a DEEP COPY of the object.
	 * @var Page
	 */
	$page_toutiao = unserialize(serialize($page));

	/**
	 * Toutiao Page don't need an LengthFilter. Remove it before process().
	 */
	$page_toutiao->removeFilter("LengthFilter");

	/**
	 * Process page elements.
	 */
	$page->process();
	$page_toutiao->process();

	/**
	 * Write to html with different template.
	 * Depends on the page type and in what prefix of folder you need.
	 */
	if($page->getType() == "article")
	{
		$page->write("waproot", "article.tpl");
		$page_toutiao->write("toutiao", "toutiao.tpl");
	}
	elseif($page->getType() == "photo")
	{
		$page->write("waproot", "photo.tpl");
	}
}

include_once("exit.php");

?>