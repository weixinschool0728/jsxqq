(function(c){var d={version:4};c.WeixinApi=d;if(typeof define==="function"&&(define.amd||define.cmd)){if(define.amd){define(function(){return d})}else{if(define.cmd){define(function(f,e,g){g.exports=d})}}}var b=function(){var f={},j,g;for(var h=0,e=arguments.length;h<e;h++){j=arguments[h];if(typeof j==="object"){for(g in j){j[g]&&(f[g]=j[g])}}}return f};var a=function(i,h,g){g=g||{};var e=function(j){switch(true){case /\:cancel$/i.test(j.err_msg):g.cancel&&g.cancel(j);break;case /\:(confirm|ok)$/i.test(j.err_msg):g.confirm&&g.confirm(j);break;case /\:fail$/i.test(j.err_msg):default:g.fail&&g.fail(j);break}g.all&&g.all(j)};var f=function(k,j){if(i.menu=="menu:share:timeline"||(i.menu=="general:share"&&j.shareTo=="timeline")){var l=k.title;k.title=k.desc||l;k.desc=l||k.desc}if(i.menu==="general:share"){if(j.shareTo=="favorite"||j.scene=="favorite"){if(g.favorite===false){return j.generalShare(k,function(){})}}if(j.shareTo==="timeline"){WeixinJSBridge.invoke("shareTimeline",k,e)}else{if(j.shareTo==="friend"){WeixinJSBridge.invoke("sendAppMessage",k,e)}else{if(j.shareTo==="QQ"){WeixinJSBridge.invoke("shareQQ",k,e)}}}}else{WeixinJSBridge.invoke(i.action,k,e)}};WeixinJSBridge.on(i.menu,function(k){g.dataLoaded=g.dataLoaded||new Function();if(g.async&&g.ready){d._wx_loadedCb_=g.dataLoaded;if(d._wx_loadedCb_.toString().indexOf("_wx_loadedCb_")>0){d._wx_loadedCb_=new Function()}g.dataLoaded=function(m){g.__cbkCalled=true;var l=b(h,m);l.img_url=l.imgUrl||l.img_url;delete l.imgUrl;d._wx_loadedCb_(l);f(l,k)};if(!(k&&(k.shareTo=="favorite"||k.scene=="favorite")&&g.favorite===false)){g.ready&&g.ready(k,h);if(!g.__cbkCalled){g.dataLoaded({});g.__cbkCalled=false}}}else{var j=b(h);if(!(k&&(k.shareTo=="favorite"||k.scene=="favorite")&&g.favorite===false)){g.ready&&g.ready(k,j)}f(j,k)}})};d.shareToTimeline=function(f,e){a({menu:"menu:share:timeline",action:"shareTimeline"},{appid:f.appId?f.appId:"",img_url:f.imgUrl,link:f.link,desc:f.desc,title:f.title,img_width:"640",img_height:"640"},e)};d.shareToFriend=function(f,e){a({menu:"menu:share:appmessage",action:"sendAppMessage"},{appid:f.appId?f.appId:"",img_url:f.imgUrl,link:f.link,desc:f.desc,title:f.title,img_width:"640",img_height:"640"},e)};d.shareToWeibo=function(f,e){a({menu:"menu:share:weibo",action:"shareWeibo"},{content:f.desc,url:f.link},e)};d.generalShare=function(f,e){a({menu:"general:share"},{appid:f.appId?f.appId:"",img_url:f.imgUrl,link:f.link,desc:f.desc,title:f.title,img_width:"640",img_height:"640"},e)};d.addContact=function(e,f){f=f||{};WeixinJSBridge.invoke("addContact",{webtype:"1",username:e},function(h){var g=!h.err_msg||"add_contact:ok"==h.err_msg||"add_contact:added"==h.err_msg;if(g){f.success&&f.success(h)}else{f.fail&&f.fail(h)}})};d.imagePreview=function(e,f){if(!e||!f||f.length==0){return}WeixinJSBridge.invoke("imagePreview",{current:e,urls:f})};d.showOptionMenu=function(){WeixinJSBridge.call("showOptionMenu")};d.hideOptionMenu=function(){WeixinJSBridge.call("hideOptionMenu")};d.showToolbar=function(){WeixinJSBridge.call("showToolbar")};d.hideToolbar=function(){WeixinJSBridge.call("hideToolbar")};d.getNetworkType=function(e){if(e&&typeof e=="function"){WeixinJSBridge.invoke("getNetworkType",{},function(f){e(f.err_msg)})}};d.closeWindow=function(e){e=e||{};WeixinJSBridge.invoke("closeWindow",{},function(f){switch(f.err_msg){case"close_window:ok":e.success&&e.success(f);break;default:e.fail&&e.fail(f);break}})};d.ready=function(h){var f=function(){var i={};Object.keys(WeixinJSBridge).forEach(function(j){i[j]=WeixinJSBridge[j]});Object.keys(WeixinJSBridge).forEach(function(j){if(typeof WeixinJSBridge[j]==="function"){WeixinJSBridge[j]=function(){try{var k=arguments.length>0?arguments[0]:{},l=k.__params?k.__params.__runOn3rd_apis||[]:[];["menu:share:timeline","menu:share:appmessage","menu:share:qq","general:share"].forEach(function(n){l.indexOf(n)===-1&&l.push(n)})}catch(m){}return i[j].apply(WeixinJSBridge,arguments)}}})};if(h&&typeof h=="function"){var e=this;var g=function(){f();h(e)};if(typeof c.WeixinJSBridge=="undefined"){if(document.addEventListener){document.addEventListener("WeixinJSBridgeReady",g,false)}else{if(document.attachEvent){document.attachEvent("WeixinJSBridgeReady",g);document.attachEvent("onWeixinJSBridgeReady",g)}}}else{g()}}};d.openInWeixin=function(){return/MicroMessenger/i.test(navigator.userAgent)};d.scanQRCode=function(e){e=e||{};WeixinJSBridge.invoke("scanQRCode",{needResult:e.needResult?1:0,desc:e.desc||"WeixinApi Desc"},function(f){switch(f.err_msg){case"scanQRCode:ok":case"scan_qrcode:ok":e.success&&e.success(f);break;default:e.fail&&e.fail(f);break}})};d.getInstallState=function(f,e){e=e||{};WeixinJSBridge.invoke("getInstallState",{packageUrl:f.packageUrl||"",packageName:f.packageName||""},function(i){var h=i.err_msg,g=h.match(/state:yes_?(.*)$/);if(g){i.version=g[1]||"";e.success&&e.success(i)}else{e.fail&&e.fail(i)}e.all&&e.all(i)})};d.sendEmail=function(f,e){e=e||{};WeixinJSBridge.invoke("sendEmail",{title:f.subject,content:f.body},function(g){if(g.err_msg==="send_email:sent"){e.success&&e.success(g)}else{e.fail&&e.fail(g)}e.all&&e.all(g)})};d.enableDebugMode=function(e){c.onerror=function(i,g,f,h){if(typeof e==="function"){e({message:i,script:g,line:f,column:h})}else{var j=[];j.push("额，代码有错。。。");j.push("\n错误信息：",i);j.push("\n出错文件：",g);j.push("\n出错位置：",f+"行，"+h+"列");alert(j.join(""))}}}})(window);

 
   // 开启Api的debug模式
          //  WeixinApi.enableDebugMode();
            // 给按钮增加click事件：请不要太纠结这个写法，demo而已
/*            var addEvent = function(elId,listener){
                document.getElementById(elId)
                        .addEventListener('click',function(e){
                            if(!window.WeixinApi || !window.WeixinJSBridge) {
                                alert('请确认您是在微信内置浏览器中打开的，并且WeixinApi.js已正确引用');
                                e.preventDefault();
                                return false;
                            }
                            listener(this,e);
                        },false);
            };*/
     /*       // 刷新
            addEvent('refresh',function(el,e){
                e.preventDefault();
                location.replace('?' + Math.random(),true);
            });*/
            // 需要分享的内容，请放到ready里
            WeixinApi.ready(function(Api) {
				var sharebackurl =document.getElementById('sharebackurl').value;
				WeixinJSBridge.call("hideToolbar");
                // 微信分享的数据
                var wxData = {
                    "appId": "", // 服务号可以填写appId
                    "imgUrl" : document.getElementById('wx-share-img').value,
                    "link" : document.getElementById("wx-share-link").value,
                    "desc" : document.getElementById('wx-share-desc').value,
                    "title" : document.getElementById('wx-share-title').value,

                };
                // 分享的回调
                var wxCallbacks = {
                    // 收藏操作是否触发回调，默认是开启的
                    favorite : false,
                    // 分享操作开始之前
                    ready : function() {
                        // 你可以在这里对分享的数据进行重组
                       // alert("准备分享");
                    },
                    // 分享被用户自动取消
                    cancel : function(resp) {
                        // 你可以在你的页面上给用户一个小Tip，为什么要取消呢？
                       // alert("分享被取消，msg=" + resp.err_msg);
                    },
                    // 分享失败了
                    fail : function(resp) {
                        // 分享失败了，是不是可以告诉用户：不要紧，可能是网络问题，一会儿再试试？
                       // alert("分享失败，msg=" + resp.err_msg);
                    },
                    // 分享成功
                    confirm : function(resp) {
                        // 分享成功了，我们是不是可以做一些分享统计呢？
                        //alert("分享成功，msg=" + resp.err_msg);
						  $.ajax({
						type : "GET",
						url: sharebackurl,
						cache: false
					});
                    },
                    // 整个分享过程结束
                    all : function(resp,shareTo) {
                        // 如果你做的是一个鼓励用户进行分享的产品，在这里是不是可以给用户一些反馈了？
                       // alert("分享" + (shareTo ? "到" + shareTo : "") + "结束，msg=" + resp.err_msg);
                    }
                };
                // 用户点开右上角popup菜单后，点击分享给好友，会执行下面这个代码
                Api.shareToFriend(wxData, wxCallbacks);
                // 点击分享到朋友圈，会执行下面这个代码
                Api.shareToTimeline(wxData, wxCallbacks);
                // 点击分享到腾讯微博，会执行下面这个代码
                Api.shareToWeibo(wxData, wxCallbacks);
                // iOS上，可以直接调用这个API进行分享，一句话搞定
                Api.generalShare(wxData,wxCallbacks);
            });