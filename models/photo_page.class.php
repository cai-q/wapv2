<?php
/**
 * photo_page.class.php
 * author: ronaldoooo
 * last modified: 2014-09-23 14:35
 */

include_once("page.class.php");

/**
 * This is the model of photo page(serial photos). Extends from Page.
 * @access public
 * @version 2.0
 * @author ronaldoooo <coachacai@hotmail.com>
 */
class PhotoPage extends Page
{
	/**
	 * Initial funtion. Set the default filters and template, then call parent's intial funtion.
	 * @param  array $element_list
	 * @param  string $pc_url
	 * @param  string $physical_path
	 * @param  string $physical_dir
	 * @access public
	 */
	public function init(
		$element_list,
		$pc_url,
		$physical_path,
		$physical_dir
		)
	{
		$type = "photo";

        parent::init(
			$type,
			$element_list,
			$pc_url,
			$physical_path,
			$physical_dir
		);

		$this->loadContent();
	}

	/**
	 * Tool function.
	 * Use this to load the infomation stored in image_url_set and image_url_de
	 * @return [type] [description]
	 */
	private function loadContent()
	{
		$this->_elementList['content'] = '<div class="pic-box box" id="picId">';
        for ($i = 0; $i < count($this->_elementList['image_url_list']); $i++)
        {
            $this->_elementList['content'] .= '<div class="pic-box-1"><div class="pic-box-1-1 box box-pack box-align"><img style="width:100%" src="';
            $this->_elementList['content'] .= $this->_elementList['image_url_list'][$i];
            $this->_elementList['content'] .= '" alt=""/></div><div class="sum-box">'.$this->_elementList['title'].'<tt>'.($i + 1).'</tt>/'.count($this->_elementList['image_url_list']).'</div><div class="doc-box">';
            $this->_elementList['content'] .= $this->_elementList['image_desc_list'][$i];
            $this->_elementList['content'] .= '</div></div>';
        }
        $this->_elementList['content'] .= "</div>";
	}
}
?>