wx.config({
    debug: false,
    appId: appIdstr,
    timestamp: timestampstr,
    nonceStr: nonceStrstr,
    signature: signaturestr,
    jsApiList: [
        'checkJsApi',
        'onMenuShareTimeline',
        'onMenuShareAppMessage',
        'onMenuShareQQ',
        'onMenuShareWeibo',
        'hideMenuItems',
        'showMenuItems',
        'hideAllNonBaseMenuItem',
        'showAllNonBaseMenuItem',
        'translateVoice',
        'startRecord',
        'stopRecord',
        'onRecordEnd',
        'playVoice',
        'pauseVoice',
        'stopVoice',
        'uploadVoice',
        'downloadVoice',
        'chooseImage',
        'previewImage',
        'uploadImage',
        'downloadImage',
        'getNetworkType',
        'openLocation',
        'getLocation',
        'hideOptionMenu',
        'showOptionMenu',
        'closeWindow',
        'scanQRCode',
        'chooseWXPay',
        'openProductSpecificView',
        'addCard',
        'chooseCard',
        'openCard'
    ]
  });

function doWeixin() {  
wx.ready(function () {
 
 	var sharebackurl =document.getElementById('sharebackurl').value;
  // 2. 分享接口
  // 2.1 监听"分享给朋友"，按钮点击、自定义分享内容及分享结果接口
 // document.querySelector('#onMenuShareAppMessage').onclick = function () {
    wx.onMenuShareAppMessage({
      title: document.getElementById('wx-share-title').value,
      desc: document.getElementById('wx-share-desc').value,
      link: document.getElementById("wx-share-link").value,
      imgUrl: document.getElementById('wx-share-img').value,
      trigger: function (res) {
       // alert('用户点击发送给朋友');
      },
      success: function (res) {
       // alert('已分享');
	   	 var image=new Image();   
		image.src=sharebackurl;        
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
      }
    });
 
 // };

  // 2.2 监听"分享到朋友圈"按钮点击、自定义分享内容及分享结果接口
 // document.querySelector('#onMenuShareTimeline').onclick = function () {
    wx.onMenuShareTimeline({
      title: document.getElementById('wx-share-title').value,
      link: document.getElementById("wx-share-link").value,
      imgUrl: document.getElementById('wx-share-img').value,
      trigger: function (res) {
       // alert('用户点击分享到朋友圈');
      },
      success: function (res) {
       	 var image=new Image();   
		image.src=sharebackurl;        
      },
      cancel: function (res) {
      //  alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
      }
    });
   // alert('已注册获取"分享到朋友圈"状态事件');
 // };

  // 2.3 监听"分享到QQ"按钮点击、自定义分享内容及分享结果接口
  //document.querySelector('#onMenuShareQQ').onclick = function () {
    wx.onMenuShareQQ({
      title: document.getElementById('wx-share-title').value,
      desc: document.getElementById('wx-share-desc').value,
      link: document.getElementById("wx-share-link").value,
      imgUrl: document.getElementById('wx-share-img').value,
      trigger: function (res) {
       // alert('用户点击分享到QQ');
      },
      complete: function (res) {
       //alert(JSON.stringify(res));
      },
      success: function (res) {
       	 var image=new Image();   
		image.src=sharebackurl;        
      },
      cancel: function (res) {
       // alert('已取消');
      },
      fail: function (res) {
        //alert(JSON.stringify(res));
      }
    });
  //  alert('已注册获取"分享到 QQ"状态事件');
 // };
  
  // 2.4 监听"分享到微博"按钮点击、自定义分享内容及分享结果接口
 // document.querySelector('#onMenuShareWeibo').onclick = function () {
    wx.onMenuShareWeibo({
      title: document.getElementById('wx-share-title').value,
      desc: document.getElementById('wx-share-desc').value,
      link: document.getElementById("wx-share-link").value,
      imgUrl: document.getElementById('wx-share-img').value,
      trigger: function (res) {
        //alert('用户点击分享到微博');
      },
      complete: function (res) {
       // alert(JSON.stringify(res));
      },
      success: function (res) {
       	 var image=new Image();   
		image.src=sharebackurl;        
      },
      cancel: function (res) {
        //alert('已取消');
      },
      fail: function (res) {
       // alert(JSON.stringify(res));
      }
    });
 
});
}

doWeixin();