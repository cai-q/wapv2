<?php
/**
 * index.class.php
 * author: ronaldoooo
 * last modified: 2014-09-28 12:00
 */

/**
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class Index{

	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------MEMBER VARIABLES-------------------------------
	 * ---------------------------------------------------------------------------
	 */
	protected $_absUrl;

	/**
	 * The source url of xml. Contains the information of index page.
	 * @var string
	 */
	protected $_xmlSource;

	/**
	 * The dom tree read from the xml.
	 * @var DomDocument
	 */
	protected $_dom;

	/**
	 * The structurized data.
	 * @var array
	 */
	protected $_blockList;

	/**
	 * The amount of blocks.
	 * @var int
	 */
	protected $_blockLength;

	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------MEMBER FUNCTIONS-------------------------------
	 * ---------------------------------------------------------------------------
	 */

	public function __construct($source, $absolute_url)
	{
		$this->_absUrl = $absolute_url;
		$this->_xmlSource = $source;
	}

	public function init()
	{
		$this->_dom = new DomDocument();
		$this->_dom->load($this->_xmlSource);

		$this->_blockList = array();
		$this->_blockLength = 0;
	}

	/**
	 * Read the document object model.
	 */
	public function readDom()
	{
		$dom = $this->_dom;
		$r = array();

		$block_list = $dom->getElementsByTagName('block');
		foreach ($block_list as $block)
		{
			$name = $this->uniqueSearch($block, 'name');
			$article_list = $this->readArticleList($block);
			$r[] = array(
				'name' => $name,
				'article_list' => $article_list
				);

			$this->_blockLength ++;
		}

		$this->_blockList = $r;
	}

	private function readArticleList($dom)
	{
		$r = array();

		$article_list = $dom->getElementsByTagName('article');
		foreach ($article_list as $article)
		{
			$title = $this->uniqueSearch($article, 'title');
			$img = $this->uniqueSearch($article, 'img');
			$url = $this->uniqueSearch($article, 'url');
			$description = $this->uniqueSearch($article, 'description');


			$t = array(
				'title' => $title,
				'url' => $url,
				'img' => $img,
				'description' => $description
				);

			$appendix_list = $this->readAppendixList($article);

			if($appendix_list)
				$t['appendix_list'] = $appendix_list;

			$r[] = $t;
		}

		return $r;
	}

	/**
	 * Get the unique dom content. Then transform the contents into correct results.
	 * CORRECT, means we transform the pcurl to wapurl during the process.
	 * If the tag name is not unique in the dom tree, return false.
	 * @param  DomNode $dom [description]
	 * @param  string  $s   [description]
	 * @return string the specific content searched by tag name.
	 */
	private function uniqueSearch($dom, $tagName)
	{
		$node_list = $dom->getElementsByTagName($tagName);

		if($node_list->length != 1)
		{
			echo "error 10086";
			return false;
		}

		$v = trim($node_list->item(0)->nodeValue);

		if(strstr($tagName, "img") && substr($v, 0, 1) == '.')
			$v = str_replace("./", $this->_absUrl."/", $v);

		if(strstr($tagName, "url") && Toolbox::is_pcurl($v))
		{
			$wapurl = Toolbox::pcurl_to_wapurl($v);
			if($wapurl)
				$v = $wapurl;
		}
		return $v;
	}

	private function readAppendixList($dom)
	{
		$appendix_list = $dom->getElementsByTagName('appendix');
		$r = array();

		if($appendix_list->length == 0)
			return false;

		foreach ($appendix_list as $appendix) {

			$title = $this->uniqueSearch($appendix, 'appendix_title');
			$url = $this->uniqueSearch($appendix, 'appendix_url');

			if($title != "" && $url != "")
			{
				$r[] = array(
					'appendix_title' => $title,
					'appendix_url' => $url
					);
			}
		}

		if(count($r) == 0)
			return false;

		return $r;
	}
	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------GETTERS & SETTERS------------------------------
	 * ---------------------------------------------------------------------------
	 */

	public function getBlockList()
	{
		return $this->_blockList;
	}
}

?>