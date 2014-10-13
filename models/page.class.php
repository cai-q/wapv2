<?php
/**
 * page.class.php
 * author: ronaldoooo
 * last modified: 2014-09-24 11:00
 */

include_once("filter.class.php");

/**
 * This is the model of page.
 * As the class is abstract, we are supposed to extend it when an new kind of page is included in.
 * ------------------------------------------------------------------------------
 * The life cycle of page:
 * construct()----->init()----->process()----->write()
 * ------------------------------------------------------------------------------
 * @abstract
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
abstract class Page
{
	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------MEMBER VARIABLES-------------------------------
	 * ---------------------------------------------------------------------------
	 */
	/**
	 * This is the page type.
	 * 1.article
	 * 2.photo
	 * @var string
	 * @access protected
	 */
	protected $_type;

	/**
	 * This is the list of element in the page.
	 * The elements and the page are loose coupled, we can change either side if needed. That means, when we create an Page, we can add|delete any element as we want.
	 * USUALLY, this list contains the following elements:
	 * 1. title
	 * 2. author
	 * 3. editor
	 * 4. crtime
	 * 5. pubtime
	 * 6. content
	 * 7. ...
	 * Interfaces are provided to operate this variable
	 * @see addElement()
	 * @see removeElement()
	 * @var array($name => $value)
	 * @access protected
	 */
	protected $_elementList;

	/**
	 * This is the list of filter of the page.
	 * When the page has read all the element into ELEMENTLIST, then it must pass every filter to ensure its elements are CORRECT.
	 * That means, we may do something in the filter function, to ADJUST the elements to perform better.
	 * @var array(<Filter>)
	 * @access protected
	 */
	protected $_filterList;

	/**
	 * This is the source URL of the page.
	 * @var string
	 * @access protected
	 */
	protected $_pcUrl;

	/**
	 * This is the physical path of the page, which determines the physical location the wap file is located.
	 * BASED FROM the waproot directory(WITHOUT the physical prefix), and END UP WITH the file name.
	 * e.g. news/201409/t00000001.shtml
	 * @var string
	 * @access protected
	 */
	protected $_physicalPath;

	/**
	 * This is the physical directory of the page.
	 * BASED FROM the waproot directory(WITHOUT the physical prefix), and END UP WITHOUT the last slash('/').
	 * e.g. news/201409
	 * @var string
	 * @access protected
	 */
	protected $_physicalDir;


	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------MEMBER FUNCTIONS-------------------------------
	 * ---------------------------------------------------------------------------
	 */

	/**
	 * This is the construct function.
	 * We don't do initials in the construct function, we use init() function instead.
	 * @see init()
	 * @access public
	 */
	public function __construct()
	{
		$this->_elementList = array();
		$this->_filterList = array();
	}

	/**
	 * This is the initial function. Call this to initial member variables.
	 * You can extend this function in sub-classes.
	 * @param  array $element_list
	 * @param  array $filter_list
	 * @param  string $pc_url
	 * @param  string $physical_path
	 * @param  string $physical_dir
	 * @access public
	 */
	public function init(
		$type,
		$element_list,
		$pc_url,
		$physical_path,
		$physical_dir
		)
	{
		$filter_list = array();
		$filter_list[] = new VideoFilter();//过滤视频元素
        $filter_list[] = new CssFilter();//css
        $filter_list[] = new UrlFilter();//默认增加对URL的过滤。
        $filter_list[] = new LengthFilter("<TRS_PAGE_SEPARATOR></TRS_PAGE_SEPARATOR>");//长度控制，TRS
        $filter_list[] = new LengthFilter('<p class="mcePageBreak">&nbsp;</p>');//长度控制，CMStop

        $this->_type = $type;
		$this->_elementList = $element_list;
		$this->_filterList = $filter_list;
		$this->_pcUrl = $pc_url;
		$this->_physicalPath = $physical_path;
		$this->_physicalDir = $physical_dir;
	}

	/**
	 * This is the function to process the page elements.
	 * Call each filter's filter function for each element.
	 * @access public
	 */
	public function process()
	{
		foreach ($this->_elementList as $name => $value)
		{
			foreach ($this->_filterList as $filter) {
				$filter->filter($this);
			}
		}
	}

	/**
	 * This is the function called to write html page to physical storage.
	 * Folders will be created if not exist.
	 * @param string $prefix The prefix of storage dir. Such as toutiao, waproot.......
	 * @param string $templage The template file path.
	 * @access public
	 */
	public function write($prefix, $template)
	{
		$smarty = new Smarty();
        foreach ($this->_elementList as $name => $value) {
            $smarty->assign($name, $value);
        }

        //$smarty->assign("base_dir", HTTP_ROOT);

        $contect = $smarty->fetch($template);

        if(!file_exists($prefix.'/'.$this->_physicalDir))
            mkdir($prefix.'/'.$this->_physicalDir, 0777, TRUE);

        $fp = fopen($prefix.'/'.$this->_physicalPath, "w+");
        fwrite($fp, $contect);
        fclose($fp);
	}

	/**
	 * Add a filter to filter list.
	 * @param Filter $filter
	 * @access public
	 */
	public function addFilter(Filter $filter)
	{
		$this->_filterList[] = $filter;
	}

	/**
	 * Remove a specific filter depends on the string of filter name given.
	 * @param  string $str
	 * @access public
	 */
	public function removeFilter($str)
	{
		for($i = 0; $i < count($this->_filterList); $i++)
		{
			if($this->_filterList[$i]->getName() == $str)
			{
				array_splice($this->_filterList, $i);
			}
		}
	}

	/**
	 * Add an element to element list.
	 * @param string $name
	 * @param string $value
	 * @access public
	 */
	public function addElement($name, $value)
	{
		$this->_elementList[$name] = $value;
	}

	/**
	 * ---------------------------------------------------------------------------
	 * ----------------------------GETTERS & SETTERS------------------------------
	 * ---------------------------------------------------------------------------
	 */
	public function getType()
	{
		return $this->_type;
	}

	public function getPcUrl()
	{
		return $this->_pcUrl;
	}

	public function getElementList()
	{
		return $this->_elementList;
	}

	public function getElement($str)
	{
		foreach ($this->_elementList as $name => $value) {
			if($name == $str)
				return $value;
		}
		return false;
	}

	public function setElement($name, $value)
	{
		$this->_elementList[$name] = $value;
	}
}
?>