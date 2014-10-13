<?php
/**
 * length_filter.class.php
 * author: ronaldoooo
 * last modified: 2014-09-20 22:05
 */
include_once("filter.class.php");

/**
 * Control the length of content. If too long, add js control.
 * @access public
 * @version 1.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class LengthFilter extends Filter{

    /**
     * The seperator of page control.
     * Use this to indentify where should add an page control.
     * @var string
     * @access private
     */
    private $sep;

    public function __construct($sep)
    {
        $this->sep = $sep;
        $this->_name = "LengthFilter";
    }
    /**
     * Add length control to the page.
     * @param  Page $page
     * @access public
     */
    public function filter(Page &$page){
        if(strstr($page->getElement('content'), $this->sep)){
            $content_list = explode($this->sep, $page->getElement('content'));
            $result = $content_list[0].'<div id="hide_pages" style="display:none;">';
            for($i = 1; $i < count($content_list); $i++)
                $result .= $content_list[$i];
            $result .= '<P>（全文完）</P>';
            $result .= '</div>';
            $result .= '<button id="hide_control" type="button" onclick="showHideCode()" style="width:100%;align:middle;">查看更多</button>';
            $page->setElement('content', $result);
        }
    }
}
?>