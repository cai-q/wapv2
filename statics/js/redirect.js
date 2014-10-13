function xcode(str){
 var c1,c2,c3,i=0,out="",len=str.length,chs="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
 while(i<len){c1=str.charCodeAt(i++)&0xff;
 	if(i==len){out+=chs.charAt(c1>>2);out+=chs.charAt((c1&0x3)<<4);out+="==";break;}c2=str.charCodeAt(i++);
 	if(i==len){out+=chs.charAt(c1>>2);out+=chs.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));out+=chs.charAt((c2&0xF)<<2);out+="=";break;}
 	c3=str.charCodeAt(i++);out+=chs.charAt(c1>>2);out+=chs.charAt(((c1&0x3)<<4)|((c2&0xF0)>>4));out+=chs.charAt(((c2&0xF)<<2)|((c3&0xC0)>>6));out+=chs.charAt(c3&0x3F);
 }return out;
}
var vpm="",vlk=location.href;
if(vlk.indexOf("//mob.")>0){location=vlk.replace("//mob.","//m.");}
if(vlk.indexOf("NOJP")<0){
 var vua=navigator.userAgent.toLowerCase();
 var isp=vua.indexOf("ipad;")>0,ism=(vua.indexOf("iphone os")>0)||(vua.indexOf("midp")>0)||(vua.indexOf("rv:1.2.3.4")>0)||(vua.indexOf("ucweb")>0)||(vua.indexOf("android")>0)||(vua.indexOf("windows ce")>0)||(vua.indexOf("windows mobile")>0)||(vua.indexOf("iemobile")>0)||(vua.indexOf("wpdesktop")>0);
 if(isp&&(vlk.indexOf("//m.")<0||vlk.indexOf("/ipad/")<0)){
 	if(document.referrer!=""){
 	 if(vlk.indexOf("?")>0){vpm="&m_referer="+xcode(document.referrer);}
 	 else{vpm="?m_referer="+xcode(document.referrer);}
 	}
 	if(vlk.indexOf("//m.")<0){
 	 var vtp=vlk.substr(7);
 	 var vmn=vtp.substr(0,vtp.indexOf("/"));
 	 if(vmn.indexOf("www.")==0){vmn=vmn.substr(4);}
 	 var vtl=vtp.substr(vtp.indexOf("/"));
 	 location="http://m."+vmn+"/ipad"+vtl+vpm;
 	}
 	else if(vlk.indexOf("/ipad/")<0&&vlk.indexOf("/m/")>0){location=vlk.replace("/m/","/ipad/")+vpm;}
 	else if(vlk.indexOf("/ipad/")<0&&vlk.indexOf("/m/")<0){location=vlk.replace(".com/",".com/ipad/")+vpm;}
 }
 if(ism&&(vlk.indexOf("//m.")<0||vlk.indexOf("/m/")<0)){
 	if(document.referrer!=""){
 	 if(vlk.indexOf("?")>0){vpm="&m_referer="+xcode(document.referrer);}
 	 else{vpm="?m_referer="+xcode(document.referrer);}
 	}
 	if(vlk.indexOf("//m.")<0){
 	 var vtp=vlk.substr(7);
 	 var vmn=vtp.substr(0,vtp.indexOf("/"));
 	 if(vmn.indexOf("www.")==0){
 	 	vmn=vmn.substr(4);
 	 	if(vpm!=""){vpm+="&cx_referer="+encodeURIComponent(vlk.substr(0,vlk.indexOf("?")));}
 	 	else{
 	 	 if(vlk.indexOf("?")>0){vpm="&cx_referer="+encodeURIComponent(vlk.substr(0,vlk.indexOf("?")));}
 	 	 else{vpm="?cx_referer="+encodeURIComponent(vlk);}
 	 	}
 	 }
 	 var vtl=vtp.substr(vtp.indexOf("/"));
 	 location="http://m."+vmn+"/m"+vtl+vpm;
 	}
 	else if(vlk.indexOf("/m/")<0&&vlk.indexOf("/ipad/")>0){location=vlk.replace("/ipad/","/m/")+vpm;}
 	else if(vlk.indexOf("/m/")<0&&vlk.indexOf("/ipad/")<0){location=vlk.replace(".com/",".com/m/")+vpm;}
 }
}