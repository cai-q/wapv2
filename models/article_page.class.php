<?php
/**
 * article_page.class.php
 * author: ronaldoooo
 * last modified: 2014-09-22 16:27
 */

include_once("page.class.php");

/**
 * This is the model of article page. Extends from Page.
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class ArticlePage extends Page
{
	/**
	 * Initial funtion. Set the default filters and template, then call parent's intial funtion.
	 * @param  array $element_list
	 * @param  string $pc_url
	 * @param  string $wap_url
	 * @param  string $physical_path
	 * @param  string $physical_dir
	 */
	public function init(
		$element_list,
		$pc_url,
		$physical_path,
		$physical_dir
		)
	{
		$type = "article";

        parent::init(
        	$type,
			$element_list,
			$pc_url,
			$physical_path,
			$physical_dir
		);
	}
}
?>