<?php
/**
 * dom_reader.class.php
 * author: ronaldoooo
 * last modified: 2014-09-22 16:44
 */


/**
 * This is the class to read a dom node list, get from interface, to initial Page objects.
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class DomReader{

	/**
	 * The max value of pubtime during this transformation.
	 * @var int
	 * @access protected
	 */
	protected $_maxPubtime;

	/**
	 * The dom node list gets from interface connector.
	 * @var DomNodeList
	 * @access protected
	 */
	protected $_nodeList;

	/**
	 * The count of current transformation.
	 * @var int
	 * @access protected
	 */
	protected $_num;

	/**
	 * The list of page. Already Initialed.
	 * @var array<Page>
	 * @access protected
	 */
	protected $_pageList;

	/**
	 * Construct function.
	 * Nothing to declare.
	 * @param DomNodeList $node_list
	 * @access public
	 */
	public function __construct(DomNodeList $node_list)
	{
		$this->_nodeList = $node_list;
		$this->_maxPubtime = -1;
		$this->_num = 0;
		$this->_pageList = array();
	}

	/**
	 * Read the dom node list, get every page infomation to initial a page.
	 * Then add the page to page list.
	 * @access public
	 */
	public function read()
	{
		foreach ($this->_nodeList as $node)
		{
			if(trim($node->getElementsByTagName("article_type")->item(0)->nodeValue) == 'article')
			{
				if(trim($node->getElementsByTagName("article_content")->item(0)->nodeValue) == "")
					continue;

				$page = new ArticlePage();

				$element_list = array();

				//--------读入，从xml获取数据存入对象---------
				$element_list['pcurl'] = trim($node->getElementsByTagName("article_url")->item(0)->nodeValue);
				$element_list['crdate'] = date('Ymd',intval(trim($node->getElementsByTagName("article_createtime")->item(0)->nodeValue)));
				$element_list['pubtime'] = (int) trim($node->getElementsByTagName("article_pubtime")->item(0)->nodeValue);
				$element_list['title'] = trim($node->getElementsByTagName("article_title")->item(0)->nodeValue);
				$element_list['content'] = trim($node->getElementsByTagName("article_content")->item(0)->nodeValue);
				$element_list['createtime'] = date('Y-m-d H:i:s',intval(trim($node->getElementsByTagName("article_createtime")->item(0)->nodeValue)));
				$element_list['editor'] = trim($node->getElementsByTagName("article_editor")->item(0)->nodeValue);
				$element_list['author'] = trim($node->getElementsByTagName("article_author")->item(0)->nodeValue);
				$element_list['source'] = trim($node->getElementsByTagName("article_source")->item(0)->nodeValue);
				//--------读入数据结束

				$physical_path = Toolbox::get_output_location_with_crdate($element_list['pcurl'], $element_list['crdate']);
				$physical_dir = Toolbox::get_output_dir_with_crdate($element_list['pcurl'], $element_list['crdate']);

				$page->init($element_list, $element_list['pcurl'], $physical_path, $physical_dir);

				if($element_list['pubtime'] > $this->_maxPubtime)
					$this->_maxPubtime = $element_list['pubtime'];

				$this->addPage($page);
				$this->_num ++;
			}
			elseif(trim($node->getElementsByTagName("article_type")->item(0)->nodeValue) == 'photo')
			{
				$page = new PhotoPage();

				$element_list = array();

				//--------读入，从xml获取数据存入对象---------
				$element_list['pcurl'] = trim($node->getElementsByTagName("article_url")->item(0)->nodeValue);
				$element_list['crdate'] = date('Ymd',intval(trim($node->getElementsByTagName("article_createtime")->item(0)->nodeValue)));
				$element_list['pubtime'] = (int) trim($node->getElementsByTagName("article_pubtime")->item(0)->nodeValue);
				$element_list['title'] = trim($node->getElementsByTagName("article_title")->item(0)->nodeValue);
				//$element_list['content'] = trim($node->getElementsByTagName("article_content")->item(0)->nodeValue);
				$element_list['createtime'] = date('Y-m-d H:i:s',intval(trim($node->getElementsByTagName("article_createtime")->item(0)->nodeValue)));
				$element_list['editor'] = trim($node->getElementsByTagName("article_editor")->item(0)->nodeValue);
				$element_list['author'] = trim($node->getElementsByTagName("article_author")->item(0)->nodeValue);
				$element_list['source'] = trim($node->getElementsByTagName("article_source")->item(0)->nodeValue);

				$image_set = $node->getElementsByTagName("image");
				$image_url_list = array();
				$image_desc_list = array();

				foreach ($image_set as $image)
				{
					$image_url_list[] = $image->getElementsByTagName("image_url")->item(0)->nodeValue;
					$image_desc_list[] = $image->getElementsByTagName("image_description")->item(0)->nodeValue;
				}
				$element_list['image_url_list'] = $image_url_list;
				$element_list['image_desc_list'] = $image_desc_list;
				//--------读入数据结束

				$physical_path = Toolbox::get_output_location_with_crdate($element_list['pcurl'], $element_list['crdate']);
				$physical_dir = Toolbox::get_output_dir_with_crdate($element_list['pcurl'], $element_list['crdate']);

				$page->init($element_list, $element_list['pcurl'], $physical_path, $physical_dir);

				if($element_list['pubtime'] > $this->_maxPubtime)
					$this->_maxPubtime = $element_list['pubtime'];

				$this->addPage($page);
				$this->_num ++;
			}
		}
	}

	/**
	 * Add a page into page list.
	 * @param Page $page
	 */
	public function addPage(Page $page)
	{
		$this->_pageList[] = $page;
	}

	/**
	 * Get the page list.
	 * @return array list of page
	 */
	public function getPageList()
	{
		return $this->_pageList;
	}

	/**
	 * Get the max pubtime in this transfromation.
	 * @return int
	 */
	public function getMaxPubtime()
	{
		return $this->_maxPubtime;
	}

	/**
	 * Get the amount of pages that have been transfromed.
	 * @return int
	 */
	public function getNum()
	{
		return $this->_num;
	}
}
?>