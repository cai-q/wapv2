<?php
/**
 * url_filter.class.php
 * author: ronaldoooo
 * last modified: 2014-09-20 22:05
 */
include_once("filter.class.php");

/**
 * Filter the url in the source content.
 * @access public
 * @version 1.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class UrlFilter extends Filter{

    public function __construct()
    {
        $this->_name = "UrlFilter";
    }

    /**
     * 该方法对页面元素进行过滤，将相对地址转换成绝对地址，并且在解析到图片地址的时候，完成图片转存工作。
     * 在最新一版的需求中，图片转存被拿掉了。这里为防止后续修改，将图片转存的过程作注释处理。
     *
     * @param  Page   $page 页面的引用
     */
    public function filter(Page &$page){
        // //采集的正文转换视频
        // if(strstr($page->data['content'], "<EMBED"))
        //     $page->data['content']=self::get_video_content($page->data['content']);
        $source_dir = substr($page->getPcUrl(), 0, strrpos($page->getPcUrl(), '/'));

        //---------------------------先把相对地址转换为绝对地址------------------
        //需要替换的模式列表
        $search = array(
            '/<img[^>]*src="W/i',//匹配所有的使用相对地址的图片，替换为绝对地址
            '/<img[^>]*src="\./i',//匹配所有的使用相对地址的图片，替换为绝对地址
            '/<\/IMG>/i',
            '/oldsrc=[^ \s>]*/i',
            );

        //正则替换的结果
        $replace = array(
            '<img src="'.$source_dir.'/./W',
            '<img src="'.$source_dir.'/.',
            '</img>',
            '',
            );

        foreach ($page->getElementList() as $key => $value) {
            $page->setElement($key, preg_replace($search, $replace, $value));
        }
        // //----------------------------再把绝对地址中的图片转存-------------------------
        // //图片转存
        // $pattern = '/<img[^>]*src="(.*?)"/i';

        // //遍历抓出的所有内容
        // foreach ($page->data as $key => $value) {
        //     preg_match_all($pattern, $value, $matches);

        //     //遍历所有的匹配项
        //     for ($i = 0; $i < count($matches[0]); $i++){
        //         //$pic_name = substr($matches[1][$i], strrpos($matches[1][$i], needle));
        //         //得到图片的源地址
        //         $image_source = $matches[1][$i];
        //         //转换后图片存放地址
        //         $image_dest = $page->out_pic_dir.substr($image_source, strrpos($image_source, "/"));

        //         echo $image_source."<br>".$image_dest."<br>";

        //         if(FALSE)
        //             $page->log("E1001\t源图片不存在！图片地址：\t".$image_source);
        //         else{
        //             if(!file_exists($image_dest)){//图片在目的文件夹已存在

        //                 if(!file_exists($page->out_pic_dir))
        //                     mkdir($page->out_pic_dir, 0777, TRUE);

        //                 //设置参数
        //                 $percent = 1.0;//尺寸缩放比例，这里设定为1.0，不缩放
        //                 $info = getimagesize($image_source);

        //                 $width = $info[0];
        //                 $height = $info[1];
        //                 $type = $info[2];
        //                 $new_width = $width * $percent;
        //                 $new_height = $height * $percent;

        //                 //创建图片
        //                 $thumb = imagecreatetruecolor($new_width, $new_height);
        //                 if(!$thumb)
        //                     $page->log("E1002\t创建图片失败！图片地址：\t".$image_source);
        //                 else {
        //                     if($type == 2){//JPEG
        //                         $image = imagecreatefromjpeg($image_source);
        //                         imagecopyresized($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        //                         //保存图片，第三参数为图片质量，范围是[0-100]
        //                         imagejpeg($thumb, $image_dest, 20);
        //                     }
        //                     elseif(strstr($image_source, ".bmp")){
        //                         include_once("src/imagebmp.php");
        //                         $image = imagecreatefrombmp($image_source);
        //                         imagecopyresized($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        //                         imagejpeg($thumb, $image_dest, 20);
        //                     }
        //                     elseif($type == 1){//PNG
        //                         $image = imagecreatefrompng($image_source);
        //                         imagecopyresized($thumb, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
        //                         imagepng($thumb, $image_dest, 2);//注意PNG的质量参数是1-10
        //                     }
        //                     imagedestroy($thumb);
        //                     $page->log .= Toolbox::get_time()."\t图片转存，源：".$image_source."\t完成。\r\n";
        //                 }
        //             }
        //             else
        //                 $page->log("已有同名已转存图片，跳过转存。");
        //         }
        //         //------------------------------------再将PC端绝对地址转换成移动端地址-------------------------------
        //         $page->data['content'] = str_replace($image_source, HTTP_ROOT.$image_dest, $page->data['content']);

        //    }
        //}

    }
}
?>