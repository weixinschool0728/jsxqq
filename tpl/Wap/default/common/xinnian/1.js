﻿new Image().src="decode.png";
new Image().src="234.png";
var start, showDecode, jumpToDecode, lastTime, lastAcc, isStarted = false;

start = function() {
	isStarted = true;
	$('.decode').hide();
	$('.result').show();
	alert('sda');exit;
	setTimeout(showDecode, 3000);
}

showDecode = function(){
	$('.result').hide();
	$('.decode').show();
	setTimeout(jumpToDecode, 3000);
}

jumpToDecode = function(){
	var urls = ["http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=8&sn=94ca6bb9f55d052cfd7176757edbb233#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=7&sn=3c45041686c26bff48630a68302dbfd4#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=6&sn=6bdb06d1971a723f759581954affff18#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=5&sn=7d86c1085c224cfdde37603224d0e764#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=4&sn=33f94c1afd01226f9f4a2c603eb012e3#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=4&sn=33f94c1afd01226f9f4a2c603eb012e3#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=2&sn=a5da2a734cf4b78468add071bd11622f#rd", 
	"http://mp.weixin.qq.com/s?__biz=MjM5NjQ5NDYxMg==&mid=205004167&idx=1&sn=81f8665ad38807133b2d0116bfa7e29d#rd", 
	];
	var jumpTo = urls[parseInt(Math.random() * urls.length)];
	window.location = jumpTo;
}

$('.do').click(start);

//摇一�&#65533;
$(window).on('deviceorientation', function(e) {
	if (isStarted) {
		return true;
	}
	if (!lastAcc) {
		lastAcc = e;
		return true;
	}
	var speed = e.alpha + e.beta + e.gamma - lastAcc.alpha - lastAcc.beta - lastAcc.gamma;
	if (Math.abs(speed) > 50) {
		start();
	}
	lastAcc = e;
});

//微信分享

var shareMeta = {
	img_url: "http://yu.weiju100.com/2015newyeardraw/thumbnail.gif",
	image_width: 100,
	image_height: 100,
	link: 'http://119.29.6.117/2015/1.php',
	title: "2015乙未羊，为自己摇枚新年签�&#65533;",
	desc: "这是对过去的感悟和对新年的祈望，希望它能为你带来好运...",
	appid: ''
};
document.addEventListener('WeixinJSBridgeReady', function () {
	WeixinJSBridge.on('menu:share:appmessage', function(){
		WeixinJSBridge.invoke('sendAppMessage', shareMeta);
	});
	WeixinJSBridge.on('menu:share:timeline', function(){
		WeixinJSBridge.invoke('shareTimeline', shareMeta);
	});
	WeixinJSBridge.on('menu:share:weibo', function(){
		WeixinJSBridge.invoke('shareWeibo', {
			content: shareMeta.title + shareMeta.desc,
			url: shareMeta.link
		});
	});
});