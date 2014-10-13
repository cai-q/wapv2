<!DOCTYPE html>
<html lang="en">
	<head>
		{block name = meta}
		<meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
		{/block}

		<link rel="stylesheet" type="text/css" href="../../../statics/css/mobile_common.css">
		<link rel="stylesheet" type="text/css" href="../../../statics/css/mobile_content.css">
		<script src="http://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
		<script src="../../../statics/js/mobile_content.js"></script>
	    <style type="text/css">
	    {literal}
	    	h1, h2, h3, h4, h5, h6 {font-family: Arial,"黑体";}

	    	p {font-family: Arial,"黑体";font-size:17px;line-height:26px;}

			.navbar-header{
	    		background: #614542;
			}
		{/literal}
		</style>

		{block name = extrahead}{/block}<!--预留额外的头文件块-->

		<title>
			{block name = title}荆楚网移动站{/block}
		</title>
	</head>

	<body>
		{block name = navbar}
			<div class="head">
			  <div class="logo">  <a href="http://www.cnhubei.com/"></a> </div>
			  <div class="back"><a href="javascript:history.back();" target="_self">返回</a></div>
			  <div class="menu">
			 	<span id="menu_topa" onClick="openShutManager(this,&#39;menu_topd&#39;,false);" class="shut">导航</span>
			 	<div id="menu_topd" class="menubox" style="display:none;">
			 	 <a href="http://news.cnhubei.com/">新闻</a><a href="http://m.finance.caixin.com/m/">政务</a><a href="http://m.companies.caixin.com/m/">评论</a><a href="http://m.china.caixin.com/m/">娱乐</a><a href="http://m.international.caixin.com/m/">搜索</a>
			 	</div>
			 </div>
			</div>
		{/block}<!-- 导航栏 -->
		{block name = body}{/block}<!-- 预留主要内容块 -->
		{block name = ads}{/block}<!-- 预留广告块 -->
		{block name = footer}
			<div class="weixin" onclick="closeAll();">荆楚网
	    		<img alt="微信图标" src="../../../statics/images/weixin_icon.png"></img>微信号：cnhubeigw
	    	</div>
			<div class="bottom" onClick="closeAll();"> Copyright 荆楚网 All Rights Reserved
	  			<div class="gotop"><a href="javascript:scroll(0,0);" target="_self">gotop</a></div>
				<div class="gotop3" style="display: none;"><a href="javascript:scroll(0,0);" target="_self">gotop3</a></div>
			</div>
		{/block}<!-- 页脚块 -->
	</body>
</html>