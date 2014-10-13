<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<title>{$title}</title>
	<link href="http://s0.pstatp.com/inapp/TTDefaultCSS.css" rel="stylesheet" type="text/css">
</head>

<body>

  <div class="logo">
  	<!--#include virtual="../../public/top.html" -->
  </div>

	<!--header 部分样式必须严格遵守-->
	<header>
		<h1>{$title}</h1>
		<div class="subtitle">
			<a id="source" href="http://www.cnhubei.com/">荆楚网</a>
			<time>{$pubtime}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;编辑：{$editor}</time>
			<!-- <a id="toggle_img" onClick="TouTiao.showImage(); return false" href="#">显示图片</a> -->
		</div>
	</header>

  <article>
  	{$content}
  </article>

  <div class="tt_ad_img">
  	<!--#include virtual="../../public/bottom.html" -->
  </div>

  <!--相关视频-->
  <section>

  </section>





    <script src="http://s0.pstatp.com/inapp/TTDefaultJS.js"></script>
    <script type="text/javascript" >
    {literal}
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','http://www.cnhubei.com/public/js/ga/analytics.js','ga');
    {/literal}
    ga('create', 'UA-33749096-4', 'auto');
    ga('send', 'pageview',"/toutiao/{$pc_dir}");
    </script>
    <script>
    {literal}
    function showHideCode(){
    	document.getElementById("hide_pages").style.display = "block";
    	document.getElementById("hide_control").style.display = "none";
    }
    {/literal}
    </script>
</body>
</html>
