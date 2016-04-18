define('wd.market.thinkInvite',function(require,exports,module){
var $=require('mobile.zepto');
var _loadscript=require('loadscript');
var _login=require("wg.loginv2");
var _cookie=require('cookie');
var moduleWGUI=require('wg.ui');
var alert=function(s){moduleWGUI.alert({'msg':s})};
var isMobile=(/AppleWebKit.*Mobile.*/).test(navigator.userAgent);
var loading=false;
var rul=window.location.href;
var myUrl='';
var shareInfoObj={'name':'','now':'','left':''};
var ads=window['_AD'];
var shareUin=getQuery('suin');
var myUin=_cookie.get('wg_uin')||_cookie.get('buy_uin');
var myNickName=_cookie.get('nickname');
var isHelp=false;
var sign=getQuery('sign');
var tokenId='';
//var payCgi='http://bases.wanggou.com/presale/subscribe?',skuid='1286133',usersource='1',cosspresaleid='0',globalpresaleid='31382',salestarttime='2014-12-12 10:00:00';
var activeId='lenovok3';
var ext=isQQ()?'hj:q':'hj:w';
var platform=isQQ()?'1':'2';
//var loginCgi='http://party.wanggou.com/tws64/m/wxv3/LoginCheckJsonp?callback=loginCbk';
//var biztype='lenovo1126';
//var reportCgi='http://party.wanggou.com/tws64/appointment/CommonAppointSubmit?biztype=';
//var queryDrawCgi='http://party.wanggou.com/tws64/appointment/CommonAppointQuery?biztype=';
//var drawCgi="http://party.wanggou.com/tws64/activemkt/active/active_draw?";
//var dofundCgi='http://party.wanggou.com/tws64/activetmp/active/dofund2?';
//var queryfundCgi='http://party.wanggou.com/tws64/activetmp/active/queryfund?';
var infoObj={'add':{'1':{'popTxt':'你檬哒哒的看着撸主，说了一句：约吗？！撸主的兑换资金+{#num#}','listTxt':'{#name#}檬哒哒的看着他，说了一句：约吗？,资金+{#money#}元','shareTxt':'我为撸主免费领联想乐檬K3抽中了{#no#}元，撸主，约吗？你看我那么檬哒哒！'},
					'2':{'popTxt':'你与撸主水乳交融！撸主的兑换资金+{#num#}元','listTxt':'与{#name#}水乳交融，资金+{#money#}元','shareTxt':'我为撸主免费领联想乐檬K3抽中了{#no#}元，我们正在水乳交融中，你们也快来帮下他！'},
					'3':{'popTxt':'你与撸主花前月下！撸主的兑换资金+{#num#}元','listTxt':'与{#name#}花前月下，资金+{#money#}元','shareTxt':'我为撸主免费领联想乐檬K3抽中了{#no#}元，我们正在花前月下中，你们也快来帮下他！'},
					'4':{'popTxt':'你真是人见人爱，花见花开的新檬主！撸主的兑换资金+{#num#}元','listTxt':'{#name#}是人见人爱，花见花开的新檬主，资金+{#money#}元','shareTxt':'我随手一抽就为撸主免费领联想乐檬K3抽中了{#no#}元，我真是人见人爱的新柠主，你们也快来帮下他！'},
					'5':{'popTxt':'你们一贱钟情！撸主的兑换资金+{#num#}元','listTxt':'与{#name#}一贱钟情，资金+{#money#}元','shareTxt':'我为撸主免费领联想乐檬K3抽中了{#no#}元，我们两个一贱钟情，你们也快来帮下他！'},
					'6':{'popTxt':'有钱就是任性！撸主的兑换资金+{#num#}元','listTxt':'{#name#}有钱就是任性，甩他资金+{#money#}元','shareTxt':'我有钱就是任性，甩了撸主{#no#}元，你们也快来甩他一脸！'},
					'7':{'popTxt':'你的滑板鞋一定最时尚！撸主的兑换资金+{#num#}元','listTxt':'{#name#}用他的滑板鞋为他摩擦，资金+{#money#}元','shareTxt':'我的滑板鞋时尚最时尚，摩擦摩擦为撸主免费领联想乐檬K3擦出了{#no#}元，你们也快来帮他！'},
					'8':{'popTxt':'你家的挖掘机一定最强！撸主的兑换资金+{#num#}元','listTxt':'{#name#}开了挖掘机为他坐镇，资金+{#money#}元','shareTxt':'我家的挖掘机最强，我为撸主免费领联想乐檬K3挖出了{#no#}元，你们也快来帮他！'},
					'9':{'popTxt':'运气这么好想想也是醉了！撸主的兑换资金+{#num#}元','listTxt':'{#name#}的运气真让人醉，资金+{#money#}元','shareTxt':'今天的运气真是醉了，我为撸主免费领联想乐檬K3抽中了{#no#}元，你们也快来帮他！'},
					'10':{'popTxt':'你傲娇的用乐檬K3手机DIY自己的音乐！撸主的兑换资金+{#num#}元','listTxt':'{#name#}傲娇的用乐檬K3手机DIY自己的音乐， 资金+{#money#}元','shareTxt':'我傲娇的用联想乐檬K3手机DIY自己的音乐，为撸主免费领联想乐檬K3抽中了{#no#}元，你们也快来帮他！'}
					},
	      'reduce':{
					'1':{'popTxt':'没有蓝翔毕业证也敢抽奖！撸主的兑换资金-{#num#}元','listTxt':'{#name#}没有蓝翔毕业证，资金{#money#}元','shareTxt':'蓝翔没毕业我就不该来抽奖，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'2':{'popTxt':'你们一定是八字不合！撸主的兑换资金-{#num#}元','listTxt':'与{#name#}八字不合，资金{#money#}元','shareTxt':'我们一定是八字不和，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'3':{'popTxt':'手滑了吧！撸主的兑换资金-{#num#}元','listTxt':'{#name#}抽奖手滑，资金{#money#}元','shareTxt':'今天真是手滑，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'4':{'popTxt':'你的姿势一定不对！撸主的兑换资金-{#num#}元','listTxt':'{#name#}姿势不对，资金{#money#}元','shareTxt':'今天的姿势一定不对，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'5':{'popTxt':'你们缘分已尽！撸主的兑换资金-{#num#}元','listTxt':'与{#name#}缘分已尽，资金{#money#}元','shareTxt':'我和撸主的缘分已尽，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'6':{'popTxt':'你无情的拒绝了撸主的约P！撸主的兑换资金-{#num#}元','listTxt':'与{#name#}约P被无情拒绝，资金{#money#}元','shareTxt':'我无情的拒绝了撸主的约P，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'7':{'popTxt':'撸主跟你表白，你十动然拒！撸主的兑换资金-{#num#}元','listTxt':'与{#name#}表白被拒绝，资金{#money#}元','shareTxt':'撸主跟我表白，只能是十动然拒了，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'8':{'popTxt':'出来混总是要还的！终于报仇了！撸主的兑换资金-{#num#}元','listTxt':'{#name#}终于报仇了，资金{#money#}元','shareTxt':'出来混总要换的，今天终于报仇啦，撸主免费领联想乐檬K3的资金少了{#no#}元，你们也快来帮我报仇'},
					'9':{'popTxt':'你的脸还没有乐檬K3的屏大！撸主的兑换资金-{#num#}元','listTxt':'{#name#}的脸还没有乐檬K3的屏大，资金-{#money#}元','shareTxt':'我的脸还没有联想乐檬K3的屏大，撸主免费领联想乐檬K3的资金少了{#no#}元，你们快来帮帮他吧'},
					'10':{'popTxt':'撸主看了你这么久，也不带你回家，好坏好坏的！撸主的兑换资金-{#num#}元','listTxt':'{#name#}说你好坏好坏的，资金-{#money#}元','shareTxt':'撸主看了我那么久也不带我回家，好坏好坏的，撸主的免费领免费领联想乐檬K3的资金少了{#no#}元'}
					}
			}
exports.init=function(){
checkPc();checkIdNull();
	//if(isQQ()){location.href='http://bases.wanggou.com/mcoss/mportal/show?tabid=2&ptype=1&actid=1562&tpl=3&pi=1&pc=20';}	
	if(myUin==shareUin)
	{
	//location.href='http://mm.wanggou.com/promote/think/mine.html';
	}
	checklogin();
	if(!_login.isLogin()){_login.login();}
	else{
	showAd(ads);queryFundFunc();bindEvent();}
	}
function bindEvent(){
	$('#view').click(showRule);
	$('.closer').click(closeBox);
	$('#shareBtn').click(shareTip);
	$('#drawBtn').click(getAction);
	$('#wantBtn').click(createId);
	$('.askBtn').click(function(){closeBox();location.reload();});
	$(document).on('tap',function(){
	if($('#shareTips').show()){$('#shareTips').hide()}
	});
}
function getAction(){helpAction(function(no){showPop(no)});}
function queryFundFunc(){
	window['queryfundCBk']=function(json){
	if(json.ret==0){
	if(json.flag1==0){showBtn(false);}
tokenId=json.id.split('|')[0];
shareUin=json.uin;
shareInfoObj.name=$xss(json.nickname,'html');
shareInfoObj.now=(json.totalvalue<=600)?(json.totalvalue):'600';shareInfoObj.left=(600-json.totalvalue>=0)?(600-json.totalvalue):'0';
showMyInfo(shareInfoObj);
if(json.help&&json.help.length!=0){renderHtml(json.help);isHelp=checkIsHelp(json.help);showBtn(isHelp);}
else{showBtn(false);}
changeLink(tokenId,activeId,json.id.split('|')[1]);
window['shareConfig']['desc']='我正在为'+json.nickname+'参与联想乐檬K3免费领取活动，快来帮他抽取兑换资金吧！好人一生平安！';}
else if(
json.ret==2||json.ret==3
){_login.login();}
else if(json.ret==1){_login.login();}
else{alert('系统繁忙，请稍后再试！');}
	}
_loadscript.loadScript(queryfundCgi+'activeid='+activeId+'&flag=1&sign='+sign+'&callback=queryfundCBk&suin='+shareUin+'&t='+Math.random());
}

function helpAction(callback){
if(loading)return false;
loading=true;window['dofundCBk']=function(json){
if(json.ret==0){callback(json.fundvalue);}
else if(json.ret==10){}
	else if(json.ret==11){
		alert('他购机基金已经达到600了');
	}
else if(json.ret==12){
	showBtn(true);
		alert('他已经达到了最大的分享次数限制了');
	}
else if(json.ret==14){
	alert('他已经达到最大的抽奖金额了！');
	}
else if(json.ret==13){
	showBtn(true);
	alert('你已经为该用户抽取过了！');
	}
else if(json.ret==1||json.ret==2||json.ret==3){_login.login();}
else{
	alert('当前参与活动人数过多，请稍后再试！');
	}
}
//_loadscript.loadScript(dofundCgi+'activeid='+activeId+'&flag=1&id='+tokenId+'&sign='+sign+'&suin='+shareUin+'&callback=dofundCBk&t='+Math.random());
}

function isWeixin(){
	return/micromessenger(\/[\d\.]+)*/.test(navigator.userAgent.toLowerCase());
}
function isQQ(){
	var ua=navigator.userAgent.toLowerCase();
	return(/qzone\//.test(ua)||/qq\/(\/[\d\.]+)*/.test(ua));
}

function showTotalNum(n){
	var str=$('#totalNum').html();
	str=str.replace(/{#([^#]+)#}/g,n);
	$('#totalNum').html(str);
	}
function showRule(){
	$('body').css({'height':'100%','position':'relative'});
	$('#rule-box').show();
}
function shareTip(event){
	$('#shareTips').show();event.stopPropagation();
}
function closeBox(){
	$('body').css({'height':'auto','position':'relative'});
	$('.msgbox').hide();
	}
function getQuery(name,url){
	var u=arguments[1]||window.location.search,reg=new RegExp("(^|&)"+name+"=([^&]*)(&|$)"),r=u.substr(u.indexOf("\?")+1).match(reg);
	return r!=null?r[2]:"";
	}
function changeLink(id,activeId,sign){
	if(id&&activeId&&sign){
		//window['shareConfig']['link']='http://mm.wanggou.com/promote/think/invite.html?id='+id+'&activeid='+activeId+'&suin='+shareUin+'&sign='+sign+'&ptag=17013.10.1';
		}
		}
function rd(n,m){var c=m-n+1;return Math.floor(Math.random()*c+n);}
function renderHtml(arr){var arr=checkData(arr);var _html='';$.each(arr.reverse(),function(i,n){if(n['uin']==shareUin){_html+='<li><i><img src="'+n['pic']+'" width="100%" /></i><em>JD赠送他'+n['awardvalue']+'元兑换资金</em><div class="price"><b>'+n['awardvalue']+'</b>元</div></li>';}else{if(n['awardvalue']>0){_html+='<li><i><img src="'+n['pic']+'" width="100%" /></i><em>'+getListTpl(n['nickname'],n['awardvalue'])+'</em><div class="price"><b>'+n['awardvalue']+'</b>元</div></li>';}else{_html+='<li><i><img src="'+n['pic']+'" width="100%" /></i><em>'+getListTpl(n['nickname'],n['awardvalue'])+'</em><div class="price"><b>'+n['awardvalue']+'</b>元</div></li>';}}});if(_html){$('#list').html(_html);}}

function showMyInfo(obj){
var str='{"小小熊"}已经凑集<strong>{"123"}</strong>元，还差<strong>{"99999"}</strong>元';
	str=str.replace(/{#([^#]+)#}/g,
		function(_,key){
		return obj[key];});
		$('#myInfo').html(str)
		}
		
function getListTpl(n,m){
var obj={'name':n,'money':m};
var no=rd(1,10);
var str='';
	if(m>=0){str=infoObj['add'][no]['listTxt'];}
	else{
	str=infoObj['reduce'][no]['listTxt'];
	}
str=str.replace(/{#([^#]+)#}/g,function(_,key){return obj[key];});return str;
}

function checkIsHelp(arr){
var temp=false;
$.each(arr,function(i,n){
if(
n['uin']==myUin
){temp=true;
}});return temp;
}

function showBtn(temp){if(temp){$('#wantBtn').show();$('#drawBtn').hide();}else{$('#wantBtn').hide();$('#drawBtn').show();}}
function showPop(num){var obj={'name':myNickName,'money':num,'num':num,'no':num};var Popstr='';var descStr='';var no=rd(1,10);if(num>=0){Popstr=infoObj['add'][no]['popTxt'];descStr=infoObj['add'][no]['shareTxt'];}else{Popstr=infoObj['reduce'][no]['popTxt'];descStr=infoObj['reduce'][no]['shareTxt'];}
Popstr=Popstr.replace(/{#([^#]+)#}/g,function(_,key){return obj[key];});descStr=descStr.replace(/{#([^#]+)#}/g,function(_,key){return obj[key];});$('#help-box .label').html(num);$('#help-box .info2').html(Popstr);$('body').css({'height':'100%','position':'relative'});$('#help-box .box').css('top','60%');$('#help-box').show();changeDesc(descStr);showBtn(true);$('#myInfo strong').eq(0).html((parseInt($('#myInfo strong').eq(0).text(),10)+num));$('#myInfo strong').eq(0).html((600-parseInt($('#myInfo strong').eq(1).text(),10)));}
function changeDesc(str){window['shareConfig']['desc']=str;}
function checkData(arr){var result=[];for(var i=0;i<=arr.length;i++){if(arr[i]){result.push(arr[i])}else{continue;}}
return result;}
function $xss(str,type){if(!str){return str===0?"0":"";}
switch(type){case"none":return str+"";break;case"html":return str.replace(/[&'"<>\/\\\-\x00-\x09\x0b-\x0c\x1f\x80-\xff]/g,function(r){return"&#"+r.charCodeAt(0)+";"}).replace(/ /g," ").replace(/\r\n/g,"<br />").replace(/\n/g,"<br />").replace(/\r/g,"<br />");break;case"htmlEp":return str.replace(/[&'"<>\/\\\-\x00-\x1f\x80-\xff]/g,function(r){return"&#"+r.charCodeAt(0)+";"});break;case"url":return escape(str).replace(/\+/g,"%2B");break;case"miniUrl":return str.replace(/%/g,"%25");break;case"script":return str.replace(/[\\"']/g,function(r){return"\\"+r;}).replace(/%/g,"\\x25").replace(/\n/g,"\\n").replace(/\r/g,"\\r").replace(/\x01/g,"\\x01");break;case"reg":return str.replace(/[\\\^\$\*\+\?\{\}\.\(\)\[\]]/g,function(a){return"\\"+a;});break;default:return escape(str).replace(/[&'"<>\/\\\-\x00-\x09\x0b-\x0c\x1f\x80-\xff]/g,function(r){return"&#"+r.charCodeAt(0)+";"}).replace(/ /g," ").replace(/\r\n/g,"<br />").replace(/\n/g,"<br />").replace(/\r/g,"<br />");break;}}
function checkIdNull(){if(!getQuery('id')||getQuery('suin')==0||!getQuery('sign')){
//location.href='http://mm.wanggou.com/promote/think/mine.html';
}
}
function createId(){window['createIdCbk']=function(json){if(json.ret==0){
//location.href='http://mm.wanggou.com/promote/think/mine.html';
}}
_loadscript.loadScript(queryfundCgi+'activeid='+activeId+'&flag=2&callback=createIdCbk&t='+Math.random());}
function getWxshouquan(){if(sessionStorage.getItem('shouquan')&&sessionStorage.getItem('shouquan')==1){return true;}else{if(unescape(_cookie.get("wx_nickname"))=='京东用户'){sessionStorage.setItem('shouquan',1);location.href="http://party.wanggou.com/tws64/m/wxv3/Login?rurl="+encodeURIComponent(location.href)+"&appid=wxae3e8056daea8727&rediect_domain=m.buy.qq.com&scope=snsapi_userinfo";return false;}}}
function checkTime(){getServerTime2(function(d){var now=/[&?]now=([^&#]*)/.exec(location.href);now=now?now[1]:"";now=now?decodeURIComponent(now).split(/[-:\s+]/):null;now=now?new Date(now[0],now[1]-1,now[2],now[3],now[4],now[5]):null;d=now?now:d;var endTime=new Date(2014,10,20,23,59,59);var hours=Math.floor(endTime.getTime()-d.getTime());if(hours<=0){location.href='http://mm.wanggou.com/promote/think/rob.html';}})}
function getServerTime2(callback){var cb="gettimeend";_loadscript.loadScript("http://focus.paipai.com/servertime/getservertime?callback="+cb+"&_t="+Math.random());window[cb]=function(json){var now;if(json.errCode=="0"){now=new Date(json.data[0].serverTime);}else{now=new Date();}
callback(now);}}
function showAd(arr){var _html='';$.each(arr,function(i,n){_html+='<a href="'+n['link']+'"><img src="'+n['img']+'"></a>';});if(_html){$('#adList').html(_html);}}
function checkPc(){if(isMobile==false){
window.location.href='http://bases.wanggou.com/mcoss/mportal/show?tabid=2&ptype=1&actid=1562&tpl=3&pi=1&pc=20';}}});
/*  |xGv00|f4ba743e9cc00d7979ca7422b9bfa290 */