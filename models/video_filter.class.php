<?php
/**
 * video_filter.class.php
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
class VideoFilter extends Filter{
    public function __construct()
    {
        $this->_name = "VideoFilter";
    }
  /**
     * 该方法对页面中所有的视频样式进行过滤
     * @param  Page   $page 页面的引用
     * @return        无
     */
    public function filter(Page &$page){
        if(strstr($page->getElement('content'), "<EMBED")){
            $page->setElement('content', self::get_video_content($page->getElement('content')));
        }
    }

    /**
     * 将内容中embed方式插入的bokecc视频转换成js的方式，以支持移动端浏览
     * @param string $content  包含embed 视频的内容
     */
    public static function get_video_content($content)
    {
        $videoID=self::cut_html($content,"videoid=","&");
        $video_string='<script type="text/javascript" src="http://union.bokecc.com/player?vid='.$videoID.'&siteid=9D9A55C45099FE79&preload=none&width=100%&height=220&playerid=&playertype=0"> </script>';

        $embed_pattern = "/<EMBED[^>]*?>/i";
        $content=preg_replace($embed_pattern,$video_string,$content);

        return $content;
    }

    /**
     *
     * HTML切取
     * @param string $html    要进入切取的HTML代码
     * @param string $start   开始
     * @param string $end     结束
     */
    protected static function cut_html($html, $start, $end) {
        if (empty($html)) return false;
        if(empty($start) || empty($end) || $start == "" || $end == "" || !strstr($html, $start) || !strstr($html, $end)) return "";
        $html = str_replace(array("\r", "\n"), "", $html);
        $start = str_replace(array("\r", "\n"), "", $start);
        $end = str_replace(array("\r", "\n"), "", $end);
        $html = explode(trim($start), $html);
        if(is_array($html)) $html = explode(trim($end), $html[1]);
        return $html[0];
    }

}
?>