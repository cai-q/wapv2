function caingPostShare(share_mark,share_title,share_description,share_url,share_pic,app_id){  
 	if(typeof(share_description)=="undefined") share_description = "";
 	if(typeof(share_pic)=="undefined") share_pic = "";
 	if(typeof(app_id)=="undefined") app_id = 100;
 	switch(share_mark) {
  	case "qqweibo": {
	 			var _appkey = "39e9ee0a88cd4e77bbb10881a6af9d26";//从腾讯获得的appkey
	 			var _site = 'http://www.caixin.com/';//你的网站地址
	 			if(share_pic!=""){
   				window.open("http://share.v.t.qq.com/index.php?c=share&a=index&title="+share_title +"&url="+share_url+"&appkey="+_appkey+"&site="+_site+"&pic="+share_pic,"newwindow","height=300,width=200,toolbar =no,menubar=no,scrollbars=no,resizable=no,location=no,status=no")
	 			}else{
   				window.open("http://share.v.t.qq.com/index.php?c=share&a=index&title="+share_title +"&url="+share_url+"&appkey="+_appkey+"&site="+_site,"newwindow","height=300,width=200,toolbar =no,menubar=no,scrollbars=no,resizable=no,location=no,status=no")
	 			}
   		}
	 		break;
  	case "tsina": {
	 			var _appkey = "2046696190";//从腾讯获得的appkey
  			if(share_pic!=""){
					window.open("http://service.weibo.com/share/share.php?title="+share_title+"&url="+share_url+"&appkey="+_appkey+"&pic="+share_pic,"newwindow","height=300,width=300,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no");
  			}else{
					window.open("http://service.weibo.com/share/share.php?title="+share_title+"&url="+share_url+"&appkey="+_appkey,"newwindow","height=300,width=300,toolbar=no,menubar=no,scrollbars=no,resizable=no,location=no,status=no");
  			}
  		}
	 		break;
 	}
}
