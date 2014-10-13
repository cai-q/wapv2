var commentSourceList = new Array();
commentSourceList.push({source:"95",name:"评论",flag:"v_comment",desc:"评论",page:2,count:0});
function StringBuffer() {
  this.buffer = [];
  if(arguments[0]) this.append(arguments[0]);
}
StringBuffer.prototype.append = function() {
  this.buffer.push(arguments[0]);
  return this;
}
StringBuffer.prototype.toString = function() {
  return this.buffer.join("");
}
StringBuffer.prototype.release = function() {
  this.buffer = [];
}
Date.prototype.format = function(format) {
  var o = {
    "M+": this.getMonth() + 1,
    "d+": this.getDate(),
    "h+": this.getHours(),
    "m+": this.getMinutes(),
    "s+": this.getSeconds(),
    "q+": Math.floor((this.getMonth() + 3) / 3),
    "S": this.getMilliseconds()
  }
  if(/(y+)/.test(format)) {
    format = format.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
  }
  for(var k in o) {
    if(new RegExp("(" + k + ")").test(format)) {
      format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k] : ("00" + o[k]).substr(("" + o[k]).length));
    }
  }
  return format;
}
var commentDB = null;
var commentDM = null;
var hasSubmit = false;
var isClicked = false;
var startNumber = 0;
var current_app_id = 0;
var current_topic_id = 0;
var current_source_id = 0;
var current_page_nums = 2;
var current_acount_nums = 0;
var current_scount_nums = 0;
var current_comment_url = "";
function showComments(app,id){
  $.getJSON("http://comment.caixin.com/ajax_article_comment.php?app_id="+app+"&topic_id="+id+"&req_type=99&page=1&size=5&source=96&rand="+Math.random()+"&callback=?",function(data) {
    commentDB = data;
    if(commentDB.success==false && commentDB.code==403) {
      return;
    }else{
      current_app_id = app;
      current_topic_id = id;
      current_comment_url = "http://comment.caixin.com/allcomments/m"+id+".html";
      initTopicComment(app,id);
      bindFormSubmit();
      loadCommentsRequest(app,id,'showCommentList');
    }
  });
}
function initTopicComment(app,id) {
  initCommentForm(app,id);
  initCommentDesc();
  initCommentSend();
  initCommentList();
  initCommentView();
}
function initCommentDesc() {
  var sb = new StringBuffer();
  sb.append("<div class='commentTab' onClick='closeAll();'>");
  sb.append("<ul>");
  sb.append("<li id='bt_0' class='hovertab' onclick='hoverLiCms(0);'>全部（<span id='topic_totalcount'>0</span>）</li>");
  for(var i=0;i<commentSourceList.length;i++){
    var source = commentSourceList[i];
    if(i==0){
			var vpinglun = "<img class=\"icon_v\" src=\"http://file.caixin.com/file/comment/images/v_comment1.png\" align='absmiddle'/>"+source.name;
			sb.append("<li id='bt_"+source.source+"' style='color:#163b8b;background-color:#dddddd' class='normaltab' onclick='hoverLiCms("+source.source+");'>"+vpinglun+"(<span id='source_count"+source.source+"'>0</span>)</li>");
    }else{
    	sb.append("<li id='bt_"+source.source+"' class='normaltab' onclick='hoverLiCms("+source.source+");'>"+source.name+"(<span id='source_count"+source.source+"'>0</span>)</li>");
    }
  }
  sb.append("</ul>");
  sb.append("</div>");
  $("#comment").append(sb.toString());
}
function initCommentSend() {
  var sb = new StringBuffer();
  sb.append("<div id='commentSubmit' class='commentSubmit' onClick='closeAll();'></div>");
  $("#comment").append(sb.toString());
}
function initCommentList() {
  var sb = new StringBuffer();
  sb.append("<div class='commentList'>");
  sb.append("<div class='dis'><ul id='container_0'></ul></div>");
  for(var i=0;i<commentSourceList.length;i++){
    var source = commentSourceList[i];
    sb.append("<div class='dis'><ul id='container_"+source.source+"'></ul></div>");
  }
  sb.append("</div>");
  $("#comment").append(sb.toString());
}
function initCommentView() {
  if(typeof(is_showmore)=='undefined') {
    $("#comment").append("<div class='allComment' onClick='closeAll();'><a href='"+current_comment_url+"' target='_self'>查看全部评论</a></div>");
  } else {
    $("#comment").append("<div class='allComment'><a href='javascript:void(0);' onClick='loadMoreComment();' class='allComment_jz' style='display:none;' target='_self'>加载更多评论</a></div>");
  }
}
function loadMoreComment() {
  var size = 5;
  var _CurrPage = 0;
  if(current_source_id>0){
    for(var i=0;i<commentSourceList.length;i++){
      var source = commentSourceList[i];
      if(parseInt(source.source)==current_source_id){
        _CurrPage = source.page;
        break;
      }
    }
  }else{
    _CurrPage = current_page_nums;
  }
  var request = "http://comment.caixin.com/ajax_article_comment.php?app_id="+current_app_id+"&topic_id="+current_topic_id+"&req_type=99&page="+_CurrPage+"&size="+size+"&source="+(current_source_id>0?current_source_id:96)+"&rand="+Math.random()+"&callback=?";
  $.getJSON(request,function(data) {
    if(data.success==true) {
      eval("moreCallback(data.list,"+current_source_id+");");
    }
  });
}
function moreCallback(jsondata,s) {
  var startPage = 0;
  if(typeof(s)=="undefined"){
    s = 0;
  }
  var divName = "#container_"+s;
  if(typeof(jsondata)!="object" || jsondata.length==0) {
    return;
  }
  if(current_source_id>0){
    for(var i=0;i<commentSourceList.length;i++){
      var source = commentSourceList[i];
      if(source.source==current_source_id){
        source.page++;
        break;
      }
    }
  }else{
    current_page_nums++;
  }
  var userInfo = getUserInfo(0);
  var iStart = 0;
  var maxCount = 5;
  if(s==97) maxCount=5;
  else maxCount = jsondata.length;
  if(s>0){
    iStart=startSource;
  }else{
    iStart=startNumber;
  }
  for(var i=0;i<maxCount;i++) {
    var sb = new StringBuffer();
    var flag = "";
    var time = makeTimeString(jsondata[i]);
    var city = makeCityString(jsondata[i]);
    sb.append("<li onClick='showMessage(this,"+s+","+jsondata[i]['id']+",event);'>");
    sb.append(makeAvatarString(jsondata[i]));
    sb.append("<div class='commentbox'>");
    sb.append("<div class='author'>");
    sb.append(makeAuthorString(jsondata[i]));
    sb.append(makeGooderString(jsondata[i]));
    sb.append("</div>");
    if(typeof(jsondata[i]['pt'])=='object') {
      var iCount = 0;
      var replys = new Array();
      var reply = jsondata[i]['pt'];
      while(reply!=null) {   //计算楼层数
        replys.push(reply);
        reply = reply['pt'];
        iCount++;
      }
      if(jsondata[i]['isdebate']=='true') {
        sb.append(makeDebateString(jsondata[i],""));
      }else{
        sb.append("<div class='txt'>"+filterHtml(jsondata[i]['content'])+"</div>");
      }
      sb.append("<div class='op'>"+time+" "+city+"<div class='fl_num'>"+(iCount+1)+"</div></div>");
      sb.append("<div class='floorbox'>");
      for(var j=0;j<iCount;j++) {
        var rpy = replys[j];
        var rTime = makeTimeString(rpy);
        var rCity = makeCityString(rpy);
        sb.append("<div class='floor'>");
        sb.append("<div class='fl_txt'>");
        if(rpy['isdebate']=='true') {
          sb.append(makeDebateString(rpy,makeAuthorString(rpy)+"："));
        }else{
          sb.append(makeAuthorString(rpy)+"：");
          sb.append(filterHtml(rpy['content']));
        }
        sb.append("</div>");
        sb.append("<div class='fl_op'>"+rTime+" "+rCity+"<div class='fl_num'>"+(iCount-j)+"</div></div>");
        sb.append("</div>");
      }
      sb.append("<span class='fl_arrow'></span>");
      sb.append("</div>");
    }else{
      if(jsondata[i]['isdebate']=='true') {
        sb.append(makeDebateString(jsondata[i],""));
      }else{
        sb.append("<div class='txt'>"+filterHtml(jsondata[i]['content'])+"</div>");
      }
      sb.append("<div class='op'>"+time+" "+city+"</div>");
    }
    sb.append("</div>");
    sb.append("<div id='mess_"+s+"_"+jsondata[i]['id']+"' class='function' style='display:none;'>");
    sb.append("<a id='sp_"+s+"_"+jsondata[i]['id']+"' href='javascript:comment_support("+jsondata[i]['id']+","+s+");' target='_self'>支持("+$.trim(jsondata[i]['spCount'])+")</a>|");
    sb.append("<a id='op_"+s+"_"+jsondata[i]['id']+"' href='javascript:comment_oppose("+jsondata[i]['id']+","+s+");' target='_self'>反对("+$.trim(jsondata[i]['opCount'])+")</a>|");
    sb.append("<a href='javascript:void(0);' onClick='replay("+jsondata[i]['id']+");' target='_self'>回复</a>");
    sb.append("<span class='func_arrow'></span>");
    sb.append("</div>");
    sb.append("</li>");
    $(divName).append(sb.toString());
  }
  if(s>0){
    startSource+=jsondata.length;
  }else{
    startNumber+=jsondata.length;
  }
}
function initCommentForm(app,id) {
  var content = GetCookieValue("COMMENT_CONTENT");
  if(content==null){
    content = "";
  }
  var userInfo = getUserInfo(0);
  var sb = new StringBuffer();
  sb.append("<div id='publishTit' class='publish' onClick='closeAll();'><a href='javascript:void(0);' onClick='openCommenter();' class='shut' target='_self'><img src='http://file.caixin.com/file/content/images/mobile/publish_btnbg.png'>发表评论</a></div>");
  sb.append("<div id='publishBox' class='publishBox' style='display:none;' onClick='closeAll();'>");  
  sb.append("<form action='http://comment.caixin.com/ajax_new_comment.php' method='post' target='_self' id='commentForm'>");
  sb.append("<input type='hidden' name='user_id' value='"+getUserInfo(1)[0]+"'/>");
  sb.append("<input type='hidden' name='user_name' value='"+getUserInfo(1)[1]+"'/>");
  sb.append("<input type='hidden' name='topic_id' value='"+id+"'/>");
  sb.append("<input type='hidden' name='app_id' value='"+app+"'/>");
  sb.append("<input type='hidden' id='formReplyId' name='reply_id' value='0'/>");
  sb.append("<div class='txt'>");
  if(content == "" || content == null){
    sb.append("<textarea id='contentMain' name='content' "+(checkUser()>0?"":"readonly")+"></textarea>");
  }else{
    clickedContent = true;
    sb.append("<textarea id='contentMain' name='content' "+(checkUser()>0?"":"readonly")+">"+content+"</textarea>");
    SetCookieValue("COMMENT_CONTENT","");
  }
  sb.append("</div>");
  sb.append("<div class='op'><a class='pub_bt' style='cursor:pointer' onclick='sendComment();' href='javascript:void(0);' target='_self'>发布</a>你好，"+userInfo[1]+" <a class='out_bt' href='"+window.location.href+"' onClick='DelCookieValue(\"http://user.caixin.com/logout/index/?url="+base64encode(base64encode(window.location.href))+"\");' target='_self'>退出</a></div>");
  sb.append("</form></div>");
  $("#comment").append(sb.toString());
}
function openCommenter(){
  if(checkUser()>0){
    $("#publishTit").hide();
    $("#publishBox").show();
  }else{
  	confirmFunc({
  		msg:"登录后发表评论,登录吗",
  		yes:function(){ goPage("http://m.user.caixin.com/usermanage/login/m/1/url/"+base64encode(base64encode(window.location.href))); },
  		not:null
  	});
  }
}
function shutCommenter(){
  $("#formReplyId").val(0);
  $("#publishBox").hide();
  $("#publishTit").show();
}
function replay(id){
  $("#publishTit").hide();
  $("#publishBox").show();
  $("#formReplyId").val(id);
  $("#contentMain").focus();
}
function sendComment(){
  if(isClicked){
  	alertFunc("请勿重复提交！");
    return;
  }
  isClicked = true;
  $("#commentForm").submit();
}
function beforeSubmit() {
  if(hasSubmit) {
  	confirmFunc({
  		msg:"您刚才发出的评论已经进入审核，您是否还要再发一条？",
  		yes:function(){ hasSubmit = false; },
  		not:function(){ isClicked = false; return false; }
  	});
  }
  var charmaxi = 1001;
  var charmaxs = 500;
  var isIE=!!window.ActiveXObject;
  var isIE6=isIE&&!window.XMLHttpRequest;
  if(isIE){
    if(isIE6){
      charmaxi = 401;
      charmaxs = 200;
    }
  }
  $("#contentMain").val($.trim($("#contentMain").val()));
  if($("#contentMain").val()=="") {
    alertFunc("请输入评论内容.");
    $("#contentMain").focus();
    isClicked = false;
    return false;
  }
  if($("#contentMain").val().replace(/[^\x00-\xff]/g,"**").length>charmaxi) {
    alertFunc("字数过多,评论内容不要超过"+charmaxs+"个汉字！");
    isClicked = false;
    return false;
  }
  return true;
}
function bindFormSubmit() {
  $("#commentForm").submit(function() {
    var userInfo = getUserInfo(0);
    if(!beforeSubmit()) {
      isClicked = false;
      return false;
    }
    var cc = $("#contentMain").val();
    var check = beforeSubmit();
    if(!check) {
      isClicked = false;
      return false;
    }
    $.getJSON("http://comment.caixin.com/ajax_new_comment.php?"+$("#commentForm").serialize()+"&jsoncallback=?",function(data) {
      if(data.success==true) {
        var date = new Date();
        var sb = new StringBuffer();
        sb.append("<div>");
        sb.append("<p>"+userInfo[1]+"："+cc+"</p>");
        sb.append("<p>"+data.message+"</p>");
        sb.append("</div>");
        $("#commentSubmit").prepend(sb.toString());
        hasSubmit = true;
        $("#contentMain").val("");
        SetCookieValue("SA_USER_COMMENT","");
        isClicked = false;
      } else {
        isClicked = false;
        alertFunc("您的操作发生错误："+data.message);
      }
    });
    return false;
  });
}
function hoverLiCms(n){
  closeAll();
  $("#bt_0").attr("class","normaltab");
  $("#container_0").attr("class","undis");
  for(var i=0;i<commentSourceList.length;i++){
    var source = commentSourceList[i];
    $("#bt_"+source.source).attr("class","normaltab");
    $("#container_"+source.source).attr("class","undis");
  }
  $("#bt_"+n).attr("class","hovertab");
  $("#container_"+n).attr("class","dis");
  current_source_id = n;
  current_scount_nums = 0;
  $('.allComment_jz').show();
  if(n>0){
    loadCommentsBySourceRequest(current_app_id,current_topic_id,"showCommentListBySource",n);
  }
}
function loadCommentsRequest(app,id,callback) {
  $("#container_0").html("<span><p align='center' style='padding-top:100px;padding-bottom:100px;'>正在加载评论信息，请稍候...</p></span>");
  var request = "http://comment.caixin.com/ajax_article_comment.php?app_id="+app+"&topic_id="+id+"&req_type=99&page=1&size=5&source=96&rand="+Math.random()+"&callback=?";
  $.getJSON(request,function(data) {
    eval(callback+"(data);");
  });
  current_page = 1;
  current_size = 5;
  current_app_id = app;
  current_topic_id = id;
  current_callback = callback;
}
function showCommentList() {
  if(commentDB.success==false) {
    $("#commentBox").hide();
    if(commentDB.code==403) {
      $("#container_0").html("<span>此文章禁止评论！</span>");
      $("#commentForm").hide();
      return;
    }
    $("#moreComment").hide();
    $("#container_0").html("<span>加载评论数据失败："+commentDB.msg+"</span>");
    return;
  }
  $("#topic_totalcount").text(commentDB.cc);
  current_acount_nums = commentDB.cc;
  for(var i=0;i<commentSourceList.length;i++){
    var source = commentSourceList[i];
    var count = commentDB.source_count[source.source];
    if(typeof(count)=="undefined"){
      count = 0;
    }
    if(source.source==97) {
      if(count>20) count = 20;
    }
    source.count = count;
    $("#source_count"+source.source).text(count);
  }
  listCallback(commentDB.list,0);
}
//生成头像代码
function makeAvatarString(obj){
  var temp = "<div class='avatar'>";
  var logo = $.trim(obj['logo']);
  var userLink = obj['user_link'];
  if(obj['authorid']>0) {
    logo = "http://ucenter.caixin.com/avatar.php?uid="+obj['authorid']+"&type=real&size=small";
  }
  else if(obj['source']<=0 && logo.length<11){
    logo = "http://ucenter.caixin.com/images/noavatar_small.gif";
  }
  if(obj['source']==0 && obj['authorid']>0){
    userLink = "http://i.caixin.com/home.php?mod=space&uid="+obj['authorid'];
  }
  if(userLink!="" && userLink!=null){
    temp += "<a href='"+userLink+"'><img class='userlogo' src='"+logo+"'/></a>";
  } else {
    temp += "<img class='userlogo' src='"+logo+"'/>";
  }
  temp += "</div>";
  return temp;
}
//生成作者代码
function makeAuthorString(obj){
  var userLink = obj['user_link'];
  if(obj['source']==0 && obj['authorid']>0){
    userLink = "http://i.caixin.com/home.php?mod=space&uid="+obj['authorid'];
  }
  var author = $.trim(obj['author']);
  if(author=="" || author=="undefined") author = "财新网友";
  if(userLink!="" && userLink!=null){
    return "<a class='userlogo' href='"+userLink+"'>"+author+"</a>";
  } else {
    return author;
  }
}
//生成加精置顶代码
function makeGooderString(obj){
  var temp = "";
  var iSpCount = toInteger(obj['spCount']);
  var iOpCount = toInteger(obj['opCount']);
  if(obj['isGood']==1 || obj['isTop']==1 || (iSpCount+iOpCount)>=5){
    temp = "<span class='sign'>";
    if((iSpCount+iOpCount)>=5){
      temp += "<img src='http://file.caixin.com/file/content/images/mobile/re.png'>";
    }
    if(obj['isGood']==1){
      temp += "<img src='http://file.caixin.com/file/content/images/mobile/jing.png'>";
    }
    if(obj['isTop']==1){
      temp += "<img src='http://file.caixin.com/file/content/images/mobile/ding.png'>";
    }
    temp += "</span>";
  }
  return temp;
}
//生成时间代码
function makeTimeString(obj){
  var temp = obj['createTime'];
  temp = temp.substring(0,4)+"-"+temp.substring(5,7)+"-"+temp.substring(8,10)+" "+temp.substring(11,13)+":"+temp.substring(14,16);
  return temp;
}
//生成来源代码
function makeCityString(obj){
  var temp = obj['city'];
  if(temp==null) {
    temp = "互联网";
  }
  if(obj['source']==1) {
    temp = "腾讯微博";
  }
  if(obj['source']==2) {
    temp = "新浪微博";
  }
  return temp;
}
//生成辩论代码
function makeDebateString(obj,author){
  var spc = Number(obj["spCount"]);    //取赞成数
  var opc = Number(obj["opCount"]);    //取反对数
  var temp = "<div class='argue'>";
  temp += "<div class='argueTit'>"+author+"【辩题】"+filterHtml(obj['debateTitle'])+"</div>";
  temp += "<div class='zf'>";
  temp += "<div class='zf_num'>正方："+spc+"<span><i style='width:"+((spc+opc)>0?((spc*100)/(spc+opc)):0)+"%;'></i></span></div>";
  temp += "<p>"+filterHtml(obj["spcontent"])+"</p>";
  temp += "</div>";
  temp += "<div class='ff'>";
  temp += "<div class='ff_num'>反方："+opc+"<span><i style='width:"+((spc+opc)>0?((opc*100)/(spc+opc)):0)+"%;'></i></span></div>";
  temp += "<p>"+filterHtml(obj["opcontent"])+"</p>";
  temp += "</div>";
  temp += "</div>";
  return temp;
}
//将字符串转为整数
function toInteger(str){
  var iTemp = 0;
  try{
    iTemp=parseInt(str,10);
  }catch(e){
    iTemp=0;
  }
  return iTemp;
}
//定位现实消息窗
function showMWindow(the,obj,evnt){
  var HO = obj.height();
  var Y = (evnt||window.event).offsetY - HO;
  var YT = $(the).offset().top;
  var HT = $(the).height();
  if(Y<0){
    Y = 0;
  }
  obj.css("top",Y + "px"); 
  obj.show();
}
//显示或隐藏消息窗
function showMessage(the,s,id,evnt){
  var obj = (window.event)?window.event.srcElement:evnt.target;
  if(obj.className!='userlogo'){
    if(checkUser()>0){
      if(commentDM!=null){
        var obj = $("#mess_"+s+"_"+id);
        if(commentDM.attr("id")==obj.attr("id")){
          commentDM.hide();
          commentDM = null;
        }else{
          commentDM.hide();
          commentDM = obj;
          showMWindow(the,commentDM,evnt);
        }
      }else{
        commentDM = $("#mess_"+s+"_"+id);
        showMWindow(the,commentDM,evnt);
      }
    }else{
	  	confirmFunc({
	  		msg:"登录后可投票或回复评论,登录吗?",
	  		yes:function(){ goPage("http://m.user.caixin.com/usermanage/login/m/1/url/"+base64encode(base64encode(window.location.href))); },
	  		not:null
	  	});
    }
  }
}
function listCallback(jsondata,s) {
//  if(jsondata.length < 15) {
//    $('.allComment_jz').hide();
//  }
  if(typeof(s)=="undefined"){
    s = 0;
  }
  var divName = "#container_"+s;
  if(typeof(jsondata)!="object" || jsondata.length==0) {
    var sour = ""; 
    for(var i=0;i<commentSourceList.length;i++){
      var source = commentSourceList[i];
      if(source.source==s){
        sour = source.desc;
        break;
      }
    }
    $(divName).html("<span><p style='font-size:14px;text-align:center;padding:20px;'>本篇文章暂无"+sour+"评论!</p></span>");
    return;
  }else{
    $(divName).empty();
  }
  var userInfo = getUserInfo(0);
  for(var i=0;i<jsondata.length;i++) {
    var sb = new StringBuffer();
    var flag = "";
    var time = makeTimeString(jsondata[i]);
    var city = makeCityString(jsondata[i]);
    sb.append("<li onClick='showMessage(this,"+s+","+jsondata[i]['id']+",event);'>");
    sb.append(makeAvatarString(jsondata[i]));
    sb.append("<div class='commentbox'>");
    sb.append("<div class='author'>");
    sb.append(makeAuthorString(jsondata[i]));
    sb.append(makeGooderString(jsondata[i]));
    sb.append("</div>");
    if(typeof(jsondata[i]['pt'])=='object') {
      var iCount = 0;
      var replys = new Array();
      var reply = jsondata[i]['pt'];
      while(reply!=null) {   //计算楼层数
        replys.push(reply);
        reply = reply['pt'];
        iCount++;
      }
      if(jsondata[i]['isdebate']=='true') {
        sb.append(makeDebateString(jsondata[i],""));
      }else{
        sb.append("<div class='txt'>"+filterHtml(jsondata[i]['content'])+"</div>");
      }
      sb.append("<div class='op'>"+time+" "+city+"<div class='fl_num'>"+(iCount+1)+"</div></div>");
      sb.append("<div class='floorbox'>");
      for(var j=0;j<iCount;j++) {
        var rpy = replys[j];
        var rTime = makeTimeString(rpy);
        var rCity = makeCityString(rpy);
        sb.append("<div class='floor'>");
        sb.append("<div class='fl_txt'>");
        if(rpy['isdebate']=='true') {
          sb.append(makeDebateString(rpy,makeAuthorString(rpy)+"："));
        }else{
          sb.append(makeAuthorString(rpy)+"：");
          sb.append(filterHtml(rpy['content']));
        }
        sb.append("</div>");
        sb.append("<div class='fl_op'>"+rTime+" "+rCity+"<div class='fl_num'>"+(iCount-j)+"</div></div>");
        sb.append("</div>");
      }
      sb.append("<span class='fl_arrow'></span>");
      sb.append("</div>");
    }else{
      if(jsondata[i]['isdebate']=='true') {
        sb.append(makeDebateString(jsondata[i],""));
      }else{
        sb.append("<div class='txt'>"+filterHtml(jsondata[i]['content'])+"</div>");
      }
      sb.append("<div class='op'>"+time+" "+city+"</div>");
    }
    sb.append("</div>");
    sb.append("<div id='mess_"+s+"_"+jsondata[i]['id']+"' class='function' style='display:none;'>");
    sb.append("<a id='sp_"+s+"_"+jsondata[i]['id']+"' href='javascript:comment_support("+jsondata[i]['id']+","+s+");' target='_self'>支持("+$.trim(jsondata[i]['spCount'])+")</a>|");
    sb.append("<a id='op_"+s+"_"+jsondata[i]['id']+"' href='javascript:comment_oppose("+jsondata[i]['id']+","+s+");' target='_self'>反对("+$.trim(jsondata[i]['opCount'])+")</a>|");
    sb.append("<a href='javascript:void(0);' onClick='replay("+jsondata[i]['id']+");' target='_self'>回复</a>");
    sb.append("<span class='func_arrow'></span>");
    sb.append("</div>");
    sb.append("</li>");
    $(divName).append(sb.toString());
  }
}
function loadCommentsBySourceRequest(app,id,callback,source) {
  $("#container_"+source).html("<span><p align='center' style='padding-top:100px;padding-bottom:100px;'>正在加载评论信息，请稍候...</p></span>");
  $("#newest_count").html("0");
  $("#debate_count").html("0");
  var request = "http://comment.caixin.com/ajax_article_comment.php?app_id="+app+"&topic_id="+id+"&req_type=99&page=1&size=5&source="+(source>0?source:96)+"&rand="+Math.random()+"&callback=?";
  $.getJSON(request,function(data) {
    eval(callback+"(data,"+source+");");
  });
  current_page = 1;
  current_size = 5;
  current_app_id = app;
  current_topic_id = id;
  current_callback = callback;
}
function showCommentListBySource(ret,source) {
  if(ret.success==false) {
    $("#commentBox").hide();
    if(ret.code==403) {
      $("#container_"+source).html("<span>此文章禁止评论！</span>");
      $("#commentForm").hide();
      return;
    }
    $("#container_"+source).html("<span>加载评论数据失败："+ret.msg+"</span>");
    return;
  }
  listCallback(ret.list,source);
}
String.prototype.replaceAll = function(s1, s2) {
  return this.replace(new RegExp(s1, "gm"), s2);
}
function filterHtml(html) {
  return html;
}
var timer = null;
function comment_support(id,source) {
  comment_vote(id,"sp",source);
}
function comment_oppose(id,source) {
  comment_vote(id,"op",source);
}
function disableLink(id,type,txt,source) {
  $("#"+type+"_"+source+"_"+id).html("<font color=\"#999999\">"+txt+"</font>");
  $("#"+type+"_"+source+"_"+id).click(function(e) {
    return false;
  });
}
function cookiecheck(id) {
  var nd = new Date().getTime();
  var time = GetCookieValue('SA_USER_COMMENT_TIME'+id);
  if(time==undefined || time=="" || (nd-time)>60000) {
    SetCookieValue('SA_USER_COMMENT_TIME'+id,60,1);
    return true;
  } else {
    alertFunc("歇一分钟再顶.");
    return false;
  }
}
function comment_change_count1(jsondata,source) {
  var count = $("#spcount_"+source+"_"+jsondata["cmtid"]);
  if(count) {
    count.html(Number(count.html()) + 1);
  }
  disableLink(jsondata["cmtid"], "sp", "已支持",source);
}
function comment_change_count0(jsondata,source) {
  var count = $("#opcount_"+source+"_"+jsondata["cmtid"]);
  if(count) {
    count.html(Number(count.html()) + 1);
  }
  disableLink(jsondata["cmtid"], "op", "已反对",source);
}
function comment_vote(id, type,source) {
  if(!cookiecheck(id)) return;
  var url = "http://comment.caixin.com/agree_or_not.php?commentId="+id;
  if (type == "op") url = url + "&type=0&callback=?";
  if (type == "sp") url = url + "&type=1&callback=?";
  $.getJSON(url,function(jsondata) {
    if(type=="op") {
      comment_change_count0(jsondata,source);
    }
    if(type=="sp") {
      comment_change_count1(jsondata,source);
    }
  });
}
function showCanNotComment() {
  $("#commentForm").html("此文章禁止评论");
}
function reloadComments(){
  loadCommentsRequest(current_app_id, current_topic_id, 'showCommentList');
}
function showCommentCount(app, id) {
  $.getJSON("http://comment.caixin.com/topic_count.php?app_id="+app+"&topicId="+id+"&callback=?",function(data) {
    $("#hoverComment").text(data.count);
    $("#news_"+app+"_"+id).text(data.count);
    $("#top_cont_riqi").html("<a href=\"http://comment.caixin.com/allcomments/"+id+".html\">评论("+data.count+")</a>");
  });
}
function checkUser(){
  var cxuid = GetCookieValue("SA_USER_UID");
  var cxname = GetCookieValue("SA_USER_NICK_NAME");
  var wbuid = GetCookieValue("SA_USER_weibouser[uid]");
  var wbname = GetCookieValue("SA_USER_weibouser[nickname]");
  if((cxuid && cxname) && (wbuid && wbname)){
    return 3;
  }
  else if(wbuid && wbname){
    return 2;
  }
  else if(cxuid && cxname){
    return 1;
  } else {
    return 0;
  }
}
function getUserInfo(ptype){
  if(ptype==1){
    var uid = GetCookieValue("SA_USER_UID");
    var uname = GetCookieValue("SA_USER_NICK_NAME");
    if(uid && uname){
      return [uid,uname,"http://ucenter.caixin.com/avatar.php?uid="+uid+"&type=real&size=small","http://i.caixin.com/?"+uid];
    }
  }
  else if(ptype==2){
    var uid = GetCookieValue("SA_USER_weibouser[uid]");
    var uname = GetCookieValue("SA_USER_weibouser[nickname]");
    if(uid && uname){
      var avatar = GetCookieValue("SA_USER_weibouser[avatar]");
      if(typeof(avatar)=='undefined' || $.trim(avatar)==''){
        avatar = "http://ucenter.caixin.com/images/noavatar_small.gif";
      }
      return [uid,uname,avatar,GetCookieValue("SA_USER_weibouser[weibourl]")];
    }
  }
  else if(ptype==0){
    var cxuid = GetCookieValue("SA_USER_UID");
    var cxname = GetCookieValue("SA_USER_NICK_NAME");
    if(cxuid && cxname){
      return [cxuid,cxname,"http://ucenter.caixin.com/avatar.php?uid="+cxuid+"&type=real&size=small","http://i.caixin.com/?"+cxuid];
    }
    var wbuid = GetCookieValue("SA_USER_weibouser[uid]");
    var wbname = GetCookieValue("SA_USER_weibouser[nickname]");
    if(wbuid && wbname){
      var avatar = GetCookieValue("SA_USER_weibouser[avatar]");
      if(typeof(avatar)=='undefined' || $.trim(avatar)==''){
        avatar = "http://ucenter.caixin.com/images/noavatar_small.gif";
      }
      return [wbuid,wbname,avatar,GetCookieValue("SA_USER_weibouser[weibourl]")];
    }
  }
  return [0,"财新网友","http://ucenter.caixin.com/images/noavatar_small.gif",""];
}
function goPage(purl){
  window.location.href = purl;
}
function alertFunc(msg){
	$("#loginTit").text(msg);
	$("#loginNot").hide();
	$("#loginYes").one("click",function(){$("#loginDiv").hide();});
	$("#loginDiv").show();
}
function confirmFunc(obj){
	$("#loginTit").text(obj.msg);
	$("#loginNot").show();
	$("#loginDiv").show();
	$("#loginYes").one("click",function(){
		$("#loginDiv").hide();
		if(obj.yes!=null){obj.yes();}
	});
	$("#loginNot").one("click",function(){
		$("#loginDiv").hide();
		if(obj.not!=null){obj.not();}
	});
}