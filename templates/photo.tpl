{extends file='base.tpl'}
{block name = title}
    {$title}
{/block}
{block name = meta}
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
{/block}
{block name = extrahead}
    <script src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=391369"></script>

    <link rel="stylesheet" href="http://bdimg.share.baidu.com/static/api/css/share_style0_32.css?v=240c6d2a.css">
    <link rel="stylesheet" href="http://bdimg.share.baidu.com/static/api/css/share_popup.css?v=027bcc03.css">
    <link rel="stylesheet" type="text/css" href="http://s2.i.dayoo.com/css/photo.css?v1" />
{/block}
{block name = body}
        <!-- <h3>{$title}</h3>
        <div>{$createtime}&nbsp;&nbsp;&nbsp;&nbsp;{$source}<br></div> -->
        {$content}
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
    {literal}
<script type="text/javascript">

var len_sum = $("#picId .pic-box-1").length;
var kl = 0;
var titleH = $(".main-title-2").html();
//默认加载下一个
function nextPic()
{
    if($("#picId img").length>1)
    {
        $($("#picId img")[1]).attr("src",$($("#picId img")[1]).attr("data-src"));
    }
}
function onLoadW()
{
    var len = $(".pic-box .pic-box-1").length;
    $(".pic-box").css("height",($(window).height()-38)+'px');
    $(".pic-box").css("width",($(window).width()*len)+'px');
    $(".pic-box-1").css("width",$(window).width()+'px');
    $(".cove-box").css("height",$(window).height()+'px');
    $(".pic-box-1-2").css("top",($(window).height()-378)/2+'px');
    //alert($(".pic-box-1").width());
}


$(function()
{
    onLoadW();
    nextPic();
})
</script>

<script type="text/javascript">

swip(document.getElementById("picId"),'right',function(){

    if($("#picId").is(":animated") || len_sum==0 || len_sum==1 || kl==0)
    {
        return false;
    }
    kl = kl-1;
    $("#picId").animate({left:-$(window).width()*kl+'px'},200,function()
    {
        $(".main-title-2").html(titleH);
    })
})

swip(document.getElementById("picId"),'left',function(){
    if($("#picId").is(":animated") || len_sum==0 || len_sum==1 || kl==len_sum-1)
    {
        return false;
    }
    kl = kl+1;

    $("#picId").animate({left:-$(window).width()*kl+'px'},200,function()
    {
        if($($("#picId .pic-box-1")[kl]).find("img").length>1)
        {
            $(".main-title-2").html("推荐图集");
            $.each($($("#picId .pic-box-1")[kl]).find("img"),function(i,obj)
            {
                $(obj).attr("src",$(obj).attr("data-src"));
            })
        }
        else
        {
            $($("#picId .pic-box-1")[kl]).find("img").attr("src",$($("#picId .pic-box-1")[kl]).find("img").attr("data-src"));
        }
    })
})
  var startX,startY,x, y=0; //初始变量
function swip(dom,type,callback){
  function touchSatrt(e){//触摸
    x=0;
    y=0;
    //e.preventDefault();
    var touch=e.touches[0];
    startX = touch.pageX; //刚触摸时的x坐标
    startY = touch.pageY; //刚触摸时的y坐标
  }

  function touchMove(e){//滑动
    e.preventDefault();
    var touch = e.touches[0];
    x = touch.pageX - startX;//滑动的横向距离
    y = touch.pageY - startY;//滑动的纵向距离
  }

  function touchEnd(e){//离开
    //e.preventDefault();
    if(type=='left' && x<-50){
      callback();
    }

    if(type=='right' && x>50){
      callback();
    }

  }//
  dom.addEventListener('touchstart', touchSatrt,false);
  dom.addEventListener('touchmove', touchMove,false);
  dom.addEventListener('touchend', touchEnd,false);
}

function shareoption() {
    var title = document.title;
    title = encodeURI(title);
    var url = document.URL;
    url = encodeURI(url);
    var des = $('meta');
    var description = "";
    for (var i = 0; i < des.length; i++) {
        if (des[i].name.toLowerCase() == "description") {
            description = des[i].content;
            break;
        }
    }
    var content = "";
    if ($("#text_content")) {
        content = $("#text_content").text();
    }
    if (content == "") {
        content = description;
    }
    content = content.slice(0, 100);
    content = encodeURI(content);
    description = encodeURI(description);
    $("#qq").attr("href", "http://v.t.qq.com/share/share.php?title=" + title + "&url=" + url + "&appkey=c5ab4350897b4f9282e213cea6f90ecb");
    $("#sina").attr("href", "http://v.t.sina.com.cn/share/share.php?url=" + url + "&title=" + title + "&ralateUid=1700715830");
    $("#qqzone").attr("href", "http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=" + url + "&title=" + title + "&site=http://www.dayoo.com/");
}
$(function() {
    shareoption();
})

$("#mabox").live("click",function()
{
    $(".cove-box").css("display","block");
    $.ajax(
        {
            type: 'GET',
            url: 'http://i.dayoo.com/QrApi/qrShare',
            data:{
                url: window.location.href
            },
            dataType: 'jsonp',
            success: function(data)
            {
                $("#qrcode img").attr("src",data);
            }
        })
})
//旋转重新获取高宽
window.onorientationchange=function(){
    onLoadW();
    $("#picId").css({left:-$(window).width()*kl+'px'});
}
</script>
{/literal}
{/block}