// JavaScript Document
//===========================点击展开关闭效果====================================
function openShutManager(oSourceObj,oTargetObj,shutAble){
	var sourceObj = typeof(oSourceObj)=="string" ? document.getElementById(oSourceObj) : oSourceObj;
	var targetObj = typeof(oTargetObj)=="string" ? document.getElementById(oTargetObj) : oTargetObj;
	if(targetObj.style.display!="none"){
		sourceObj.className = "shut";
   	targetObj.style.display="none";
	} else {
		sourceObj.className = "open";
   	targetObj.style.display="block";
	}
	if(typeof(commentDM)=='object' && commentDM!=null){
		commentDM.hide();
		commentDM=null;
	}
	if(oTargetObj=="menu_topd"){
		$("#sets_topa").attr("class","shut");
		$("#sets_topd").hide();
	}
	if(oTargetObj=="sets_topd"){
		$("#menu_topa").attr("class","shut");
		$("#menu_topd").hide();
	}
}
function closeAll(){
	$("#menu_topa").attr("class","shut");
	$("#menu_topd").hide();
	$("#sets_topa").attr("class","shut");
	$("#sets_topd").hide();
	if(typeof(commentDM)=='object' && commentDM!=null){
		commentDM.hide();
		commentDM=null;
	}
}
//弹窗
var rightShowFlag = false;
function preventDefaultFun(e){
	e.preventDefault();
}
function hideBG(){
	document.getElementById("bg").style.display="none";
}
var topNum = 0;
var startX,startY,endX,endY;
var preLastMoveX,preLastMoveY,lastMoveX,lastMoveY=-1;
var preLastTime,lastTime=-1;
var animateStartTime,animateStopTime,animateTimeArea;
var animateStopFlag = true;
var animateSpeed,animateNum;
var timeout = 100;
var timer;
function setWindowTimer(){
 timer = $.timer(timeout, resetRightHeight);
 timer.pause();
}
function resetRightHeight(){
	$("#sidebar").height(window.innerHeight);
}
function pupopen(){
	if(!rightShowFlag){
		// 侧栏打开
		$("#open").attr("class","shut");
		timer.resume();
		rightShowFlag = true;
		document.getElementById("bg").style.display="block";
		$("#bg").stop().animate({'opacity':'0.8'},{duration:200,easing:"easeInQuad"});
		$("#sidebar").stop().animate({'right':'0px'},{duration:200,easing:"easeInQuad"});
		$("#open").stop().animate({'right':'335px'},{duration:200,easing:"easeInQuad"});
		document.getElementById("sidebar").style.webkitOverflowScrolling ="touch";
		document.getElementById("sidebarListContent").style.webkitOverflowScrolling ="touch";
		document.getElementById("sidebar").style.overflowY="scroll";
		document.getElementById("sidebarListContent").style.overflowY="scroll";
		document.getElementById("sidebar").style.overflowX="hidden";
		document.getElementById("sidebarListContent").style.overflowX="hidden";
		document.getElementById("sidebar").style.height = "100%";
		document.getElementById("sidebarListContent").style.height = "100%";
		//document.getElementById("sidebarListContent").style.padding = "15px 15px";
		document.body.addEventListener("touchmove",preventDefaultFun,false);
		document.body.addEventListener("touchstart",preventDefaultFun,false);
		document.body.addEventListener("touchend",preventDefaultFun,false);
		//document.getElementById("bg").addEventListener("click", pupopen);
		///document.getElementById("bg").addEventListener("touchmove", preventDefaultFun, false);
		//document.getElementById("bg").addEventListener("touchend", preventDefaultFun, false);
		var container = document.getElementById("sidebar");
		container.addEventListener("touchstart",function(evt){evt.stopPropagation();},false);
		container.addEventListener("touchmove",function(evt){evt.stopPropagation();},false);
		container.addEventListener("touchend",function(evt){evt.stopPropagation();},false);
	}else{
		// 侧栏关闭
		timer.pause();
		$("#open").attr("class","open");
		rightShowFlag = false;
		document.getElementById("sidebar").style.height = "100%";
		document.getElementById("sidebarListContent").style.height = "100%";
		//document.getElementById("bg").removeEventListener("touchstart",pupopen);
		$("#sidebar").stop().animate({'right':'-335px'},{duration:200,easing:"easeInQuad"});
		$("#open").stop().animate({'right':'0px'},{duration:200,easing:"easeInQuad"});
		$("#bg").stop().animate({'opacity':'0'},{duration:200,easing:"easeInQuad",complete:hideBG});
		document.body.removeEventListener("touchmove", preventDefaultFun, false);
		document.body.removeEventListener("touchstart", preventDefaultFun, false);
		document.body.removeEventListener("touchend", preventDefaultFun, false);
	}
}
function pupclose(){
	document.getElementById("bg").style.display="none";
	document.body.parentNode.style.overflow="scroll";
	$("#sidebar").stop().animate({'right':'-325px'},1000);
	$("#open").stop().animate({'right':'0px'},1000);
}
function jdtInit(){
	$("#focus_change").css("width",wunit+"px");
	var focusLiList = $("#focus_change_list li");
	$("#focus_change_list").css("width",(wunit*isize)+"px");
	$.each($("#focus_change_list li"),function(i,n){
		$(n).css("width",wunit+"px");
	});
	var url = $("#focus_change_list li").eq(0).children("a").attr("href");
	var txt = $("#focus_change_list li").eq(0).children("a").children("img").attr("alt");
 	$("#focusTitle").html('<a href="'+url+'">'+txt+'</a>');
}
function change(move){
	if(move>50 && icurr>0){
		icurr--;
	  moveElement("focus_change_list",-1*icurr*wunit,0,isize);
	  focusChange(icurr);
	}
	if(move<-50 && icurr<(isize-1)){
		icurr++;
	  moveElement("focus_change_list",-1*icurr*wunit,0,isize);
	  focusChange(icurr);
	}
}
function focusChange(j){
	$.each($("#focus_change_btn li"),function(i,n){
		if(i==j){
			$(n).attr("class","current");
			$("#focusTitle").html(focusTitle($("#focus_change_list li").eq(i))); 
		}else{$(n).attr("class","");}
	});
}
function focusTitle(obj){
	var url = $(obj).children("a").attr("href");
	var txt = $(obj).children("a").children("img").attr("alt");
	return '<a href="'+url+'">'+txt+'</a>';
}
function moveElement(elementID,final_x,final_y,interval){
	if(!document.getElementById) return false;
	if(!document.getElementById(elementID)) return false;
	var elem = document.getElementById(elementID);
	if(elem.movement){clearTimeout(elem.movement);}
	if(!elem.style.left){elem.style.left = "0px";}
	if(!elem.style.top){elem.style.top = "0px";}
	var xpos = parseInt(elem.style.left);
	var ypos = parseInt(elem.style.top);
	if(xpos==final_x && ypos==final_y){return true;}
	if(xpos<final_x){var dist = Math.ceil((final_x-xpos)/10);xpos = xpos+dist;}
	if(xpos>final_x){var dist = Math.ceil((xpos-final_x)/10);xpos = xpos-dist;}
	if(ypos<final_y){var dist = Math.ceil((final_y-ypos)/10);ypos = ypos+dist;}
	if(ypos>final_y){var dist = Math.ceil((ypos-final_y)/10);ypos = ypos-dist;}
	elem.style.left = xpos+"px";
	elem.style.top = ypos+"px";
	var repeat = "moveElement('"+elementID+"',"+final_x+","+final_y+","+interval+")";
	elem.movement = setTimeout(repeat,interval);
}
function setTab(name,cursel,n){
	for(i=1;i<=n;i++){
		var menu=document.getElementById(name+i);
		var con=document.getElementById("col_"+name+"_"+i);
		menu.className=i==cursel?"current":"";
		con.style.display=i==cursel?"block":"none";
	}
}
//kv关键词,cv频道ID,tv检索类型,ov排序方式,pv页数,sv每页记录数,maxes最大记录数
function loadMoreDatas(kv,cv,tv,ov,pv,sv,maxes){
	var url = "http://m.search.caixin.com/search/jsonmobile.jsp?keyword="+encodeURIComponent(kv);
	if(typeof(cv)!="undefined"){ url += "&channel="+cv; }
	if(typeof(tv)!="undefined"){ url += "&type="+tv; }
	if(typeof(ov)!="undefined"){ url += "&sort="+ov; }
	if(typeof(pv)!="undefined"){ url += "&page="+pv; }
	if(typeof(sv)!="undefined"){ url += "&size="+sv; }
	$.getJSON(url,function(data){
		if(data.count>0){
			if((((data.page-1)*data.size)+data.count)>=(data.maxes>0?data.maxes:maxes)){$("#resultMore").html("");}
			else{$("#resultMore").html("<a target=\"_self\" href=\"javascript:void(0);\" onClick=\"loadMoreDatas('"+kv+"',"+cv+","+tv+","+ov+","+(pv+1)+","+sv+","+(data.maxes>0?data.maxes:maxes)+");\">加载更多</a>");}
			$.each(data.datas,function(ind,obj){
				$("#resultList").append("<dl><dt><a href=\""+buildMobLink(obj.link)+"\">"+obj.desc+"</a></dt><dd><p><a href=\""+buildMobLink(obj.link)+"\">"+trimToEmpty(obj.info)+"</a></p><span>"+obj.time+"</span></dd></dl>");
			});
		}else{
			$("#resultMore").html("");
		}
	});
}
function trimToEmpty(str){
	if(str==null) return "";
	if(str.toLowerCase()=="null") return "";
	return str;
}
function buildAllLink(){
	if(sUserAgent.indexOf("ipad;")>0){
		$.each($("#resultList a"),function(i,n){
			var vLink = n.href;
			n.href = buildPadLink(vLink);
		});
	}
	if((sUserAgent.indexOf("iphone os")>0)||(sUserAgent.indexOf("midp")>0)||(sUserAgent.indexOf("rv:1.2.3.4")>0)||(sUserAgent.indexOf("ucweb")>0)||(sUserAgent.indexOf("android")>0)||(sUserAgent.indexOf("windows ce")>0)||(sUserAgent.indexOf("windows mobile")>0)){
		$.each($("#resultList a"),function(i,n){
			var vLink = n.href;
			n.href = buildMobLink(vLink);
		});
	}
}
function buildMobLink(vLink){
	if(typeof(vLink)!='string') return "";
	if(vLink.indexOf("blog.caixin")>0) return vLink;
	if(vLink.indexOf("//mob.")>0){vLink = vLink.replace("//mob.","//m.");}
	if(vLink.indexOf("//m.")<0){
		var vTemp = vLink.substr(7);
		var vMain = vTemp.substr(0,vTemp.indexOf("/"));
		if(vMain.indexOf("www.")==0){vMain = vMain.substr(4);}
		var vTail = vTemp.substr(vTemp.indexOf("/"));
		return "http://m."+vMain+"/m"+vTail;
	}
	else if(vLink.indexOf("/m/")<0 && vLink.indexOf("/ipad/")>0){
		return vLink.replace("/ipad/","/m/");;
	}else{
		return vLink;
	}
}
function buildPadLink(vLink){
	if(typeof(vLink)!='string') return "";
	if(vLink.indexOf("blog.caixin")>0) return vLink;
	if(vLink.indexOf("//mob.")>0){vLink = vLink.replace("//mob.","//m.");}
	if(vLink.indexOf("//m.")<0){
		var vTemp = vLink.substr(7);
		var vMain = vTemp.substr(0,vTemp.indexOf("/"));
		if(vMain.indexOf("www.")==0){vMain = vMain.substr(4);}
		var vTail = vTemp.substr(vTemp.indexOf("/"));
		return "http://m."+vMain+"/ipad"+vTail;
	}
	else if(vLink.indexOf("/ipad/")<0 && vLink.indexOf("/m/")>0){
		return vLink.replace("/m/","/ipad/");
	}else{
		return vLink;
	}
}
var base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/"; 
function base64encode(str) { 
  var out,i,len; 
  var c1,c2,c3; 
  len = str.length; 
  i = 0; 
  out = ""; 
  while(i < len) { 
	  c1 = str.charCodeAt(i++) & 0xff; 
	  if(i==len) { 
	    out += base64EncodeChars.charAt(c1 >> 2); 
	    out += base64EncodeChars.charAt((c1 & 0x3) << 4); 
	    out += "=="; 
	    break; 
	  } 
	  c2 = str.charCodeAt(i++); 
	  if(i==len) { 
	    out += base64EncodeChars.charAt(c1 >> 2); 
	    out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4)); 
	    out += base64EncodeChars.charAt((c2 & 0xF) << 2); 
	    out += "="; 
	    break; 
	  } 
	  c3 = str.charCodeAt(i++); 
	  out += base64EncodeChars.charAt(c1 >> 2); 
	  out += base64EncodeChars.charAt(((c1 & 0x3)<< 4) | ((c2 & 0xF0) >> 4)); 
	  out += base64EncodeChars.charAt(((c2 & 0xF) << 2) | ((c3 & 0xC0) >>6)); 
	  out += base64EncodeChars.charAt(c3 & 0x3F); 
  } 
  return out; 
}
function searchLink(){
	$.each($("#Main_Content_Val a"),function(i,n){
		var vLink = n.href;
		if(vLink.indexOf("/search.jsp")>0){
			vLink = vLink.replace("//search.","//m.search.");
			n.href = vLink.replace("/search.jsp","/mobile.jsp");
			$(n).attr("target","_self");
		}
	});
}
function changeLink(){
	var vHref = window.location.href;
	if(vHref.indexOf("//mob.")>0){vHref = vHref.replace("//mob.","//m.");}
	if(vHref.indexOf("//m.")>0 && vHref.indexOf("/m/")>0){searchLink();}
	$.each($(".3g a"),function(i,n){
		if(vHref.indexOf("//m.")>0 && vHref.indexOf("/m/")>0){
			var vLink = n.href;
			var vTemp = vLink.substr(7);
			var vMain = vTemp.substr(0,vTemp.indexOf("/"));
			var vTail = vTemp.substr(vTemp.indexOf("/"));
			if(vMain.indexOf("m.")!=0) vMain = "m."+vMain;
			if(vTail.indexOf("/m/")!=0) { 
				if(vTail.indexOf("/ipad/")==0){
					vTail.replace("/ipad/","/m/");
				}else{
					vTail = "/m"+vTail;
				}
			}
			n.href = "http://"+vMain+vTail;
		}
		if(vHref.indexOf("//m.")>0 && vHref.indexOf("/ipad/")>0){
			var vLink = n.href;
			var vTemp = vLink.substr(7);
			var vMain = vTemp.substr(0,vTemp.indexOf("/"));
			var vTail = vTemp.substr(vTemp.indexOf("/"));
			if(vMain.indexOf("m.")!=0) vMain = "m."+vMain;
			if(vTail.indexOf("/ipad/")!=0) { 
				if(vTail.indexOf("/m/")==0){
					vTail.replace("/m/","/ipad/");
				}else{
					vTail = "/ipad"+vTail;
				}
			}
			n.href = "http://"+vMain+vTail;
		}
	});
}