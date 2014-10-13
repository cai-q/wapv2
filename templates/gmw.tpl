
<!DOCTYPE html>
<html>
<head>  <script type='text/javascript' src='http://js.adm.cnzz.net/js/mm.js'></script>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="Cache-Control"content="max-age=0"/>
<meta http-equiv="pragma" content="no-cache" />
<meta http-equiv="Expires" content="0" />
<meta http-equiv = "X-UA-Compatible" content = "IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0"/>
<meta name="description" content="光明网是光明日报在网络时代的新延伸，也是国内唯一一家定位于思想理论领域的中央重点新闻网站。光明网坚持“可读、可信、可用”的办网原则，以“新闻视野、文化视角、思想深度、理论高度”为理念，努力打造“知识分子网上精神家园，权威思想理论文化网站”。" />
<meta name="keywords" content="光明网,时政,经济,教育,文化" />
<meta name="filetype" content="0">
<meta name="publishedtype" content="1">
<meta name="pagetype" content="2">
<META name="catalogs" content="50149">
<title>手机荆楚网</title>
<link rel="stylesheet" href="http://m.gmw.cn/20141.files/css/common.css" />
<link rel="Shortcut Icon" type="image/x-icon" href="http://www.gmw.cn/favicon.ico" />

<script type="text/javascript" name="baidu-tc-cerfication" src="http://apps.bdimg.com/cloudaapi/lightapp.js#4c215a42938797bf3bcfc0b242d679f1"></script>
<script type="text/javascript">
	{literal}
	window.bd && bd._qdc && bd._qdc.init({app_id: '0048c558a7650ec02a7bdc23'});
	{/literal}
</script>
<style type="text/css">
{literal}
.banner {margin:0;padding:0; width:90%;margin-left: 5%; position: relative;}
.banner ul{padding: 0;margin:0; width:80%; position: relative;}
    .banner li { list-style: none; width: 80%;}
        .banner ul li { float: left; margin:0; padding:0;-webkit-text-size-adjust: none}
.banner img {width: 100%; height: auto;}
.dots {
  position: absolute;
  right: 5%;
  top: 5%;
}

.dots li {
  display: inline-block;
  width: 4px;
  height: 4px;
  margin: 0 1px;
  text-indent: -999em;
  border: 2px solid #fff;
  border-radius: 6px;
  cursor: pointer;
  opacity: .4;
  -webkit-transition: background .5s, opacity .5s;
  -moz-transition: background .5s, opacity .5s;
  transition: background .5s, opacity .5s;
}

.dots li.active {
  background: #fff;
  opacity: 1;
}
{/literal}
</style>
<script type="text/javascript" src="../statics/js/jquery.min.js"></script>
<script type="text/javascript" src="../statics/js/unslider.js"></script>
<script type="text/javascript" src="../statics/js/jquery.event.move.js"></script>
</head>
<body>
	<!--<div class="bannerAd" id="bannerAdTop"><a href="http://shouji.360.cn/360safe/106175/360MobileSafe.apk" target="_blank"><img width="100%" atremote src="http://m.gmw.cn/images/201405_mAd_360.jpg"/></a></div>-->
	<div class="header_main" style="height: 115px;">
		<div class="header_logo_bg">
			<div style="padding:0;" class="header_logo"><img style="width:95px; height:44px; display:block; padding:3px 0;" src="http://m.gmw.cn/20141.files/images/mlogo.png"/></div>
			<div class="header_right">
				<a href="node_51551.htm" class="ico_nav_info">
					<span>导航</span>
					<img src="http://m.gmw.cn/20141.files/images/header_nav_btn_blue1.png" />
				</a>
			</div>
			<div class="clear"></div>
		</div>
		<div class="header_nav_bg" style="height: 65px;">
			<div class="header_nav nav_top">
				<a href="node_32337.htm"><span>国内</span></a>
				<a href="node_32338.htm"><span>国际</span></a>
				<a href="node_50186.htm"><span>娱乐</span></a>
				<a href="node_50180.htm"><span>养生</span></a>
				<a href="node_56897.htm"><span>幽默</span></a>
			</div>
			<div class="header_nav nav_bottom">
				<a href="node_42675.htm"><span>财经</span></a>
				<a href="node_51444.htm"><span>社会</span></a>
				<a href="node_32204.htm"><span>体育</span></a>
				<a href="node_50175.htm"><span>乐活</span></a>
				<a href="node_50155.htm"><span>文化</span></a>
			</div>
		</div>
	</div>
	<div>
		<div id="scroller" data-role="slide" class="slide_img_list">
			{foreach from = $block_list item = block}
				{if $block['name'] eq '要闻头条'}
					<div class="hot_top1">
						{foreach from = $block['article_list'] item = article}
							<div>
								<a class="hot_top_big" style="height:auto;" href="{$article['url']}" target="_self">{$article['title']}</a>
							</div>
								<div class="hot_top_small"><a>
							{foreach from = $article['appendix_list'] item = appendix}
									<A href="{$appendix['appendix_url']}" style="font-size:12px;" target="_self">{$appendix['appendix_title']}</A>
							{/foreach}
								</a></div>
						{/foreach}
					</div>
				{elseif $block['name'] eq '要闻图片'}
					<div class="banner">
							<ul>
						{foreach from = $block['article_list'] item = article}
								<li style="position:relative;">
									<a href="{$article['url']}" target="_self">
										<img class="slide" src="{$article['img']}">
									</a>
									<div style="width:100%; height:25px; font-size:17px;position:absolute; bottom:0%; background-color:#000; opacity:0.5;">
										<a style="color:white; bottom: 5%;" href="{$article['url']}">{$article['title']}</a>
									</div>
								</li>
						{/foreach}
							</ul>
					</div>
					<script>
					{literal}
					    $(function()
					    {
						    var slidey = $('.banner').unslider(),
	    					data = slidey.data('unslider');

						    var slides = $('.slide');

						    slides
						    .on('swipeleft', function(e)
						    {
						    	data.next();
						    })
						    .on('swiperight', function(e)
						    {
						    	data.prev();
						    })
					    	$('.banner').unslider({fluid: true, dots: true, keys: true});
					    });

				    {/literal}
					</script>
				{else}
					<div class="mainlistsbox">
						<div class="bar_nav">
							<div class="bar_nav_main"><a href="node_42675.htm">{$block['name']}</a></div>
						</div>
						<div class="nav_content_list" style="white-space:normal;">
							<div class="nav_content_item" style="height:auto;">
								<ul class="news_list" style="line-height:normal;">
									{foreach from = $block['article_list'] item = article}
						  			<li style="line-height:150%;height:auto;">
						  				<div style="height:auto;">
						  					<a class="list_title_l" style="font-size:17pxfloat:left;display:inline;" href="{$article['url']}" target="_self">{$article['title']}</a>
						  					{if array_key_exists('appendix_list', $article)}
						  						{foreach from = $article['appendix_list'] item = appendix}
						  							<a class="list_title_l" style="font-size:17px;display:inline;" href="{$appendix['appendix_url']}" target="_self">{$appendix['appendix_title']}</a>
						  						{/foreach}
						  					{/if}
						  				</div>
						  			</li>
						  			{/foreach}
						  		</ul><!--end 3865840-51446-1-->
							</div>

						</div>
					</div>
				{/if}
			{/foreach}
		<div id="nav" class="img_indicator"></div>
		</div>
	</div>
<script src="http://m.gmw.cn/20141.files/js/zepto.js"></script>
<!-- <script src="http://m.gmw.cn/20141.files/js/common.js"></script> -->
</body>