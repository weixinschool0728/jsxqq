define('loadscript',function(require,exports,module){exports.loadScript=$loadScript;function $loadScript(obj){if(!$loadScript.counter){$loadScript.counter=1;}
var isObj=typeof(obj)=="object",url=isObj?obj.url:arguments[0],id=isObj?obj.id:arguments[1],obj=isObj?obj:arguments[2],_head=document.head||document.getElementsByTagName("head")[0]||document.documentElement,_script=document.createElement("script"),D=new Date(),_time=D.getTime(),_isCleared=false,_timer=null,o=obj||{},data=o.data||'',charset=o.charset||"gb2312",isToken=o.isToken,timeout=o.timeout,isAutoReport=o.isAutoReport||false,reportOptions=o.reportOptions||{},reportType=o.reportType||'current',reportRetCodeName=o.reportRetCodeName,reportSuccessCode=typeof(o.reportSuccessCode)=="undefined"?200:o.reportSuccessCode,reportErrorCode=typeof(o.reportErrorCode)=="undefined"?500:o.reportErrorCode,reportTimeoutCode=typeof(o.reportTimeoutCode)=="undefined"?600:o.reportTimeoutCode,onload=o.onload,onsucc=o.onsucc,callbackName=o.callbackName||'',callback=o.callback,errorback=o.errorback,_jsonpLoadState='uninitialized';var complete=function(errCode){if(!_script||_isCleared){return;}
_isCleared=true;if(_timer){clearTimeout(_timer);_timer=null;}
_script.onload=_script.onreadystatechange=_script.onerror=null;if(_head&&_script.parentNode){_head.removeChild(_script);}
_script=null;if(callbackName){if(callbackName.indexOf('.')==-1){window[callbackName]=null;try{delete window[callbackName];}
catch(e){}}
else{var arrJ=callbackName.split("."),p={};for(var j=0,jLen=arrJ.length;j<jLen;j++){var n=arrJ[j];if(j==0){p=window[n];}
else{if(j==jLen-1){try{delete p[n];}
catch(e){}}
else{p=p[n];}}}}}
if(_jsonpLoadState!="loaded"&&typeof errorback=="function"){errorback(errCode);}
if(isAutoReport&&reportType!='cross'){_retCoder.report(_jsonpLoadState=="loaded",errCode);}};var jsontostr=function(d){var a=[];for(var k in d){a.push(k+'='+d[k]);}
return a.join('&');};if(isAutoReport&&reportOptions){if(reportType=='cross'){$returnCode(reportOptions).reg();}
else{reportOptions.url=reportOptions.url||url.substr(0,url.indexOf('?')==-1?url.length:url.indexOf('?'));var _retCoder=$returnCode(reportOptions);}}
if(data){url+=(url.indexOf("?")!=-1?"&":"?")+(typeof data=='string'?data:jsontostr(data));}
if(callbackName&&typeof callback=="function"){var oldName=callbackName;if(callbackName.indexOf('.')==-1){callbackName=window[callbackName]?callbackName+$loadScript.counter++:callbackName;window[callbackName]=function(jsonData){_jsonpLoadState='loaded';if(isAutoReport&&reportRetCodeName){reportSuccessCode=jsonData[reportRetCodeName];}
callback.apply(null,arguments);onsucc&&(onsucc());};}
else{var arrJ=callbackName.split("."),p={},arrF=[];for(var j=0,jLen=arrJ.length;j<jLen;j++){var n=arrJ[j];if(j==0){p=window[n];}
else{if(j==jLen-1){p[n]?(n=n+$loadScript.counter++):'';p[n]=function(jsonData){_jsonpLoadState='loaded';if(isAutoReport&&reportRetCodeName){reportSuccessCode=jsonData[reportRetCodeName];}
callback.apply(null,arguments);onsucc&&(onsucc());};}
else{p=p[n];}}
arrF.push(n);}
callbackName=arrF.join('.');}
url=url.replace('='+oldName,'='+callbackName);}
_jsonpLoadState='loading';id=id?(id+_time):_time;url=(isToken!==false?$addToken(url,"ls"):url);_script.charset=charset;_script.id=id;_script.onload=_script.onreadystatechange=function(){var uA=navigator.userAgent.toLowerCase();if(!(!(uA.indexOf("opera")!=-1)&&uA.indexOf("msie")!=-1)||/loaded|complete/i.test(this.readyState)){if(typeof onload=="function"){onload();}
complete(_jsonpLoadState=="loaded"?reportSuccessCode:reportErrorCode);}};_script.onerror=function(){complete(reportErrorCode);};if(timeout){_timer=setTimeout(function(){complete(reportTimeoutCode);},parseInt(timeout,10));}
setTimeout(function(){_script.src=url;try{_head.insertBefore(_script,_head.lastChild);}catch(e){}},0);}
function $addToken(url,type){var token=$getToken();if(url==""||(url.indexOf("://")<0?location.href:url).indexOf("http")!=0){return url;}
if(url.indexOf("#")!=-1){var f1=url.match(/\?.+\#/);if(f1){var t=f1[0].split("#"),newPara=[t[0],"&g_tk=",token,"&g_ty=",type,"#",t[1]].join("");return url.replace(f1[0],newPara);}else{var t=url.split("#");return[t[0],"?g_tk=",token,"&g_ty=",type,"#",t[1]].join("");}}
return token==""?(url+(url.indexOf("?")!=-1?"&":"?")+"g_ty="+type):(url+(url.indexOf("?")!=-1?"&":"?")+"g_tk="+token+"&g_ty="+type);};function $getCookie(name){var reg=new RegExp("(^| )"+name+"(?:=([^;]*))?(;|$)"),val=document.cookie.match(reg);return val?(val[2]?unescape(val[2]):""):null;};function $getToken(){var skey=$getCookie("skey"),token=skey==null?"":$time33(skey);return token;};function $loadUrl(o){o.element=o.element||'script';var el=document.createElement(o.element);el.charset=o.charset||'utf-8';if(o.noCallback==true){el.setAttribute("noCallback","true");}
el.onload=el.onreadystatechange=function(){if(/loaded|complete/i.test(this.readyState)||navigator.userAgent.toLowerCase().indexOf("msie")==-1){clear();}};el.onerror=function(){clear();};el.src=o.url;document.getElementsByTagName('head')[0].appendChild(el);function clear(){if(!el){return;}
el.onload=el.onreadystatechange=el.onerror=null;el.parentNode&&(el.parentNode.removeChild(el));el=null;}};function $report(url){$loadUrl({'url':url+((url.indexOf('?')==-1)?'?':'&')+"cloud=true&"+Math.random(),'element':'img'});};function $returnCode(opt){var option={url:"",action:"",sTime:"",eTime:"",retCode:"",errCode:"",frequence:1,refer:location.href,uin:"",domain:"paipai.com",from:1,report:report,isReport:false,timeout:3000,timeoutCode:444,formatUrl:true,reg:reg};for(var i in opt){option[i]=opt[i];}
if(option.url){option.sTime=new Date();}
if(option.timeout){setTimeout(function(){if(!option.isReport){option.report(true,option.timeoutCode);}},option.timeout);}
function reg(){this.sTime=new Date();if(!this.action){return;}
var rcookie=$getCookie("retcode"),cookie2=[];rcookie=rcookie?rcookie.split("|"):[];for(var i=0;i<rcookie.length;i++){if(rcookie[i].split(",")[0]!=this.action){cookie2.push(rcookie[i]);}}
cookie2.push(this.action+","+this.sTime.getTime());$setCookie("retcode",cookie2.join("|"),60,"/",this.domain);}
function report(ret,errid){this.isReport=true;this.eTime=new Date();this.retCode=ret?1:2;this.errCode=isNaN(parseInt(errid))?"0":parseInt(errid);if(this.action){this.url="http://retcode.paipai.com/"+this.action;var rcookie=$getCookie("retcode"),ret="",ncookie=[];rcookie=rcookie?rcookie.split("|"):[];for(var i=0;i<rcookie.length;i++){if(rcookie[i].split(",")[0]==this.action){ret=rcookie[i].split(",");}
else{ncookie.push(rcookie[i]);}}
$setCookie("retcode",ncookie.join("|"),60,"/",this.domain);if(!ret){return;}
this.sTime=new Date(parseInt(ret[1]));}
if(!this.url){return;}
var domain=this.url.replace(/^.*\/\//,'').replace(/\/.*/,''),timer=this.eTime-this.sTime,cgi=encodeURIComponent(this.formatUrl?this.url.match(/^[\w|/|.|:|-]*/)[0]:this.url);this.reportUrl="http://c.isdspeed.qq.com/code.cgi?domain="+domain+"&cgi="+cgi+"&type="+this.retCode+"&code="+this.errCode+"&time="+timer+"&rate="+this.frequence+(this.uin?("&uin="+this.uin):"");if(this.reportUrl&&Math.random()<(1/this.frequence)&&this.url){$report(this.reportUrl);}}
return option;};function $setCookie(name,value,expires,path,domain,secure){var exp=new Date(),expires=arguments[2]||null,path=arguments[3]||"/",domain=arguments[4]||null,secure=arguments[5]||false;expires?exp.setMinutes(exp.getMinutes()+parseInt(expires)):"";document.cookie=name+'='+escape(value)+(expires?';expires='+exp.toGMTString():'')+(path?';path='+path:'')+(domain?';domain='+domain:'')+(secure?';secure':'');};function $time33(str){for(var i=0,len=str.length,hash=5381;i<len;++i){hash+=(hash<<5)+str.charAt(i).charCodeAt();};return hash&0x7fffffff;}});
