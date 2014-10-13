<?php

/**
 * transform_all.php
 * author: ronaldoooo
 * last modified: 2014-09-24 14:57
 *
 * -------------------------------------------------------------------------------------------------------------
 * ---------------------Transform all articles, depends on the last pubtime in database.------------------------
 * -------------------------------------------------------------------------------------------------------------
 */
include_once("init.php");
// echo "<BR>START:".Toolbox::get_time();

/**
 * Get out all interfaces and last pubtime out of database.
 * @var array
 */
$interface_list = Toolbox::get_interface_list();

foreach ($interface_list as $url => $time) {
	/**
	 * Initial an interface connection, using the specific url and last pubtime.
	 * @var InterfaceConnector
	 */
	$interface_conn = new InterfaceConnector($url.'?last_time='.$time);

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
	// echo "<BR><BR>INTERFACE URL:".$url;
	// echo "<BR>AMOUNT:".$reader->getNum();
	// echo "<BR>LASTTIME:".$time;
	// echo "<BR>MAX PUBTIME:".$reader->getMaxPubtime();
	// echo "<BR>";

	/**
	 * Update max pubtime to database.
	 */
	if($reader->getMaxPubtime() != -1)
	{
		Toolbox::update_max_pubtime($url, $reader->getMaxPubtime());
	}

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
}

// echo "<BR>END:".Toolbox::get_time();
include_once("exit.php");

?>