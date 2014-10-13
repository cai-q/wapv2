<?php
/**
 * filter.class.php
 * author: ronaldoooo
 * last modified: 2014-09-20 21:55
 */

/**
 * This is the model of Filter.
 * As the class is abstract, we are supposed to extend it when an new kind of page is included in.
 * @abstract
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
abstract class Filter{
	/**
	 * The class name of the Filter.
	 * @var string
	 * @access protected
	 */
	protected $_name;

    public function __construct(){}

    /**
     * The filter function.
     * @param  Page&  $page
	 * @access public
     */
    public abstract function filter(Page& $page);

    /**
     * Get the name of the filter.
     * @return string
	 * @access public
     */
    public function getName()
    {
    	return $this->_name;
    }
}
?>