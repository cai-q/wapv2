<?php
/**
 * interface_connector.class.php
 * author: ronaldoooo
 * last modified: 2014-09-22 21:25
 */

/**
 * This is the model of interface connector.
 * When you want to connect an interface to collect infomation, INITIAL an InterfaceConnector with THE URL of the interface, then CALL its read() function.
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class InterfaceConnector
{
	/**
	 * The interface url.
	 * Contains the url and parameters.
	 * e.g. test.cnhubei.com/abc.php?docid=101201222
	 * e.g. test.cnhubei.com/abc.php?last_time=1001201222
	 * @var string
	 * @access public
	 */
	private $_interfaceUrl;

	/**
	 * The construct function.
	 * Nothing to declare.
	 * @param string $interface_url
	 * @access public
	 */
	public function __construct($interface_url)
	{
		$this->_interfaceUrl = $interface_url;
	}

	/**
	 * Read dom infomations from the interface.
	 * @return DomNodeList
	 * @access public
	 */
	public function connect()
	{
		$doc = new DOMDocument();
		$doc->load($this->_interfaceUrl);

		$node_list = $doc->getElementsByTagName("article");
		return $node_list;
	}
}
?>