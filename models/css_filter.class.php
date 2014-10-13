<?php
/**
 * css_filter.class.php
 * author: ronaldoooo
 * last modified: 2014-09-20 22:05
 */
include_once("filter.class.php");

/**
 * Filter the video in the source content. Make it available on mobile divices.
 * @access public
 * @version 1.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class CssFilter extends Filter
{
    public function __construct()
    {
        $this->_name = "CssFilter";
    }
    /**
     * Filter the css in the page content.
     * In the later versions, it's considered to be done in the frotier, control by javascript.
     * @param  Page&   $page
     */
    public function filter(Page &$page){
        $search = array(
            '/<img/i',
            '/<\/img>/i',
            '/<style[^>]*>[\s\S]*<\/style>/i',//删除文章段首部css块
            '/ alt=[^ \s>]*/i',
            '/ width=[^ \s>]*/i',
            '/ height=[^ \s>]*/i',
            '/ align=[^ \s>]*/i',
            '/<div>&nbsp;<\/div>/i',
            '/<DIV>　　/i',//过滤两个中文空格
            );

        //正则替换的结果
        $replace = array(
            '<img',
            '</img>',
            ' ',
            ' ',
            ' ',
            ' ',
            ' ',
            '',
            '<DIV>',
            );
        $page->setElement('content', trim(preg_replace($search, $replace, $page->getElement('content'))));
    }
}

?>