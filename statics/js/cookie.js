/*
*设置cookie值
*/
function SetCookieValue(_Name,_Value,_Expires,_Type){   
  var _LargeExpDate = new Date();
	if(typeof(_Expires)=='number' && isNaN(_Expires)==false) {
		if(_Expires!=0) {
			if(typeof(_Type)=='number' && isNaN(_Type)==false && _Type==1){
		  	_LargeExpDate.setTime(_LargeExpDate.getTime()+(_Expires*1000));
			}else{
		  	_LargeExpDate.setTime(_LargeExpDate.getTime()+(_Expires*24*3600*1000));
			}
		}else{
			_LargeExpDate = null;
		}
  }else{
	  _LargeExpDate.setTime(_LargeExpDate.getTime()+(36500*24*3600*1000));
  }
  document.cookie = _Name+"="+escape(_Value)+(_LargeExpDate!=null?";expires="+_LargeExpDate.toGMTString():"")+";path=/;domain=.caixin.com";
}  
/*
*获取cookie值
*/
function GetCookieValue(_Name){   
  var _search = _Name+"=";   
  if(document.cookie.length>0) {
		var _offset = document.cookie.indexOf(_search);
    	if(_offset!=-1) {
				_offset += _search.length;
        var _end = document.cookie.indexOf(";",_offset);
        if(_end==-1) _end = document.cookie.length;
           var _cook = document.cookie.substring(_offset,_end);
           return _cook.match(/%u/ig)?unescape(_cook):decodeURIComponent(_cook);
       }   
       else return '';
   }   
}
/*d
*删除cookie值
*/
function DelCookieValue(logoutHref){
	if(GetCookieValue('SA_USER_UID')){
		SetCookieValue('SA_USER_NICK_NAME','', -1);
		SetCookieValue('SA_USER_USER_PWD', '', -1);
		SetCookieValue('SA_USER_USER_TYPE', '', -1);
		SetCookieValue('SA_USER_USER_ACTIVE', '', -1);
		SetCookieValue('SA_USER_USER_SCHOLARSHIP','',-1);
		SetCookieValue('SA_USER_EXT_FUNCTION','',-1);
		SetCookieValue('UID','',-1);
		SetCookieValue('SA_USER_UID','',-1);
		window.location=logoutHref;
	}else{
		SetCookieValue('SA_USER_weibouser[uid]','',-1);
		SetCookieValue('SA_USER_weibouser[name]','',-1);
		SetCookieValue('SA_USER_weibouser[nickname]','',-1);
		SetCookieValue('SA_USER_weibouser[avatar]','',-1);
		SetCookieValue('SA_USER_weibouser[location]','',-1);
		SetCookieValue('SA_USER_weibouser[weiboid]','',-1);
		location.reload();
	}
}
/*
*计算cookie的过期时间
*/
function calcTime(_ExpireDays,_Offset) {
	var _D = new Date();
	_D.setTime(_D.getTime()+(_ExpireDays*3600*1000*24));
	var _Utc = _D.getTime()+(_D.getTimezoneOffset()*60000);
	var _Nd = new Date(_Utc+(3600000*_Offset));
	return _Nd;
}
/*
* 网站heade头对cookie和session初始化操作
*/
function cookieInit(){
	if(GetCookieValue('SA_USER_UID')){
		var cookieStr = GetCookieValue('SA_USER_NICK_NAME');
	}else if (GetCookieValue("SA_USER_weibouser[nickname]")){
		var cookieStr = GetCookieValue("SA_USER_weibouser[nickname]");
	}else{
		var cookieStr = '';
	}
  SetCookieValue('backUrl',window.location.href,365);
	if(GetCookieValue('SA_USER_UID')){
		var loginSpan = "<a href='http://user.caixin.com/'><b>"+cookieStr+"</b></a>　<a href='#' target='_self' onclick=\"javascript:DelCookieValue('"+mainUrl+"/logout/index/')\"><b>退出</b></a>";
	}else if (GetCookieValue("SA_USER_weibouser[nickname]")) {
		var loginSpan = "<b>"+cookieStr+"</b>　<a href='#' target='_self' onclick=\"javascript:DelCookieValue('"+mainUrl+"/logout/index/')\"><b>退出</b></a>";
	}else{
		if(typeof(regFrom) != "undefined") {
			var loginSpan = "<a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a> <span>|</span> <a href='"+mainUrl+"/auth/register/from/"+regFrom+"/' target='_self'>注册</a>";	
		}
		else {
			var loginSpan = "<a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a> <span>|</span> <a href='"+mainUrl+"/auth/register' target='_self'>注册</a>";	
		}
	}
	document.getElementById('showLoginId').innerHTML = loginSpan;
}

/*
* 网站heade头对cookie和session初始化操作
*/
function caixinCookieInit(a,b,c){
	if(GetCookieValue('SA_USER_UID')){
		var cookieStr = GetCookieValue('SA_USER_NICK_NAME');
	}else if (GetCookieValue("SA_USER_weibouser[nickname]")){
		var cookieStr = GetCookieValue("SA_USER_weibouser[nickname]");
	}else{
		var cookieStr = '';
	}
  SetCookieValue('backUrl', window.location.href,365);
	if(cookieStr){
		if(c=='little') {
			var loginSpan = "<span><a href='http://user.caixin.com/' target='_self'>"+cookieStr+"</a></span><i><a href='#' target='_self' onclick=\"javascript:DelCookieValue('"+mainUrl+"/logout/index/')\"\>退出</a></i>";
		} else if (GetCookieValue("SA_USER_weibouser[nickname]")){
			var loginSpan = "<li><span>"+cookieStr+"</span></li><li><a href='#' target='_self' onclick=\"javascript:DelCookieValue('"+mainUrl+"/logout/index/')\"\>退出</a></li>";
		} else {
			var loginSpan = "<li><a href='http://user.caixin.com/' target='_self'>"+cookieStr+"</a></li><li><a href='#' target='_self' onclick=\"javascript:DelCookieValue('"+mainUrl+"/logout/index/')\"\>退出</a></li>";
		}
	}else{
		if(typeof(regFrom)!="undefined") {
			if(c=='little') {
				var loginSpan = " <i></i><i><a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a></i><span><a href='"+mainUrl+"/auth/register/from/"+regFrom+"/' target='_self'>注册</a></span>";
			}
			else {
				var loginSpan = " <li></li><li><a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a></li><li><a href='"+mainUrl+"/auth/register/from/"+regFrom+"/' target='_self'>注册</a></li>";
			}
		} else {
			if(c=='little') {
				var loginSpan = " <i></i><i><a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a></i><span><a href='"+mainUrl+"/auth/register' target='_self'>注册</a></span>";
			}
			else {
				var loginSpan = " <li></li><li><a href='"+mainUrl+"/usermanage/login/' target='_self'>财新通行证登录</a></li><li><a href='"+mainUrl+"/auth/register' target='_self'>注册</a></li>";
			}
		}
	}
	if(c=='little') {
		document.getElementById('showLoginId').innerHTML = loginSpan;
	}else{
		document.getElementById('showLoginId').innerHTML += loginSpan;
	}
}
function runscript(s){
	var jsIframe = window.document.createElement("iframe");
	jsIframe.style.display = "none";
	window.document.body.appendChild(jsIframe);
	with(window.frames[window.frames.length-1]){
		document.open();
		document.write(s); 
		document.close();
 	}
}