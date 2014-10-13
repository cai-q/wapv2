{extends file='base.tpl'}
{block name = title}
    {$title}
{/block}
{block name = extrahead}
    <script src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=391369"></script>

    <link rel="stylesheet" href="http://bdimg.share.baidu.com/static/api/css/share_style0_32.css?v=240c6d2a.css">
    <link rel="stylesheet" href="http://bdimg.share.baidu.com/static/api/css/share_popup.css?v=027bcc03.css">

{/block}
{block name = body}
    <div class="well article" id="the_content">
        <h3>{$title}</h3>
        <div>{$createtime}&nbsp;&nbsp;&nbsp;&nbsp;{$source}<br></div><br>
        <div>{$content}</div>
    </div>
    <div class="bdsharebuttonbox bdshare-button-style0-32" data-bd-bind="1408935417331">
        <a class="bds_more" data-cmd="more" href="#"></a>
        <a class="bds_qzone" title="分享到QQ空间" data-cmd="qzone" href="#"></a>
        <a class="bds_tsina" title="分享到新浪微博" data-cmd="tsina" href="#"></a>
        <a class="bds_tqq" title="分享到腾讯微博" data-cmd="tqq" href="#"></a>
        <a class="bds_renren" title="分享到人人网" data-cmd="renren" href="#"></a>
        <a class="bds_weixin" title="分享到微信" data-cmd="weixin" href="#"></a>
    </div>
    <div>
        <!-- {$relative_news} -->
    </div>
    <script>
    {literal}
    function showHideCode(){
        document.getElementById("hide_pages").style.display = "block";
        document.getElementById("hide_control").style.display = "none";
    }
    {/literal}
    </script>
{/block}