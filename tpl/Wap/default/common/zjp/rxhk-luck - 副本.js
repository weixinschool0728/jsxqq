$(function() {
	// 初始化页面元素
	$('#topbg').height($(document).height());
	$('#armArea').height($(window).height() - 300);
	$('.popup-bg').height($(window).height());
	// 显示我的奖品
	$('.my-prize').click(function() {
		$('.popup-prize').css('display', 'block');
	})
	// 点击事件：切换手臂样式
	$('.switch-btn').click(function() {
		switchArm();
	})
	// 初始化手臂事件
	initArmEvent();
	// 微信分享
	//initWeixin();
});
/**
 * 初始化微信分享
 */
function initWeixin() {
	if (typeof WeixinJSBridge == "undefined") {
		if (document.addEventListener) {
			document.addEventListener('WeixinJSBridgeReady', onBridgeReady,
					false);
		} else if (document.attachEvent) {
			document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
			document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
		}
	} else {
		onBridgeReady();
	}
}
function onBridgeReady() {
	// 显示右上角按钮
	WeixinJSBridge.call('showOptionMenu');
	// 分享到朋友圈
	WeixinJSBridge.on('menu:share:timeline', function(argv) {
		shareTimeline();
	});
	// 发送给好友
	WeixinJSBridge.on('menu:share:appmessage', function(argv) {
		shareFriend();
	});
	// 分享到微博
	WeixinJSBridge.on('menu:share:weibo', function(argv) {
		shareWeibo();
	});
}
/**
 * 分享到朋友圈
 */
function shareTimeline() {
	WeixinJSBridge.invoke('shareTimeline', {
		"img_url" : imgUrl,
		"link" : lineLink,
		"desc" : descContent,
		"title" : descContent
	}, function(res) {
		if (res.err_msg == "share_timeline:ok") {
			$.post(_webApp + '/activity/rxhk/shareRecord', {
			}, function(data) {
				closeShareMask();
				if (data.result == 'success') {
					isShare = 1;
					surplusCount = parseInt(data.message);
					shareSuccess();
				} else {
					alert(data.message);
				}
			}, 'json');
		}
	});
}

function shareFriend() {
	WeixinJSBridge.invoke('sendAppMessage', {
		"img_url" : imgUrl,
		"link" : lineLink,
		"desc" : descContent,
		"title" : descContent
	}, function(res) {

	});
}

function shareWeibo() {
	WeixinJSBridge.invoke('shareWeibo', {
		"img_url" : imgUrl,
		"url" : lineLink,
		"content" : descContent,
		"title" : descContent
	}, function(res) {

	});
}

/**
 * 初始化手臂事件
 */
function initArmEvent() {
	// 手臂
	var arm = document.getElementById('arm');
	// 手指
	var finger = document.getElementById('finger');
	// 抽奖结果弹出框
	var luckPopup = document.getElementById('luckPopup');
	// 抽奖箱
	var luckBox = document.getElementById('luckBox');
	// 抽奖标语提示
	var luckTips = document.getElementById('luckTips');
	// 手臂最大下拉高度
	var maxDragH = document.body.clientHeight - 500;

	arm.addEventListener('touchstart', function(event) {
		event.preventDefault();// 阻止其他事件
		// 如果这个元素的位置内只有一个手指的话
		if (event.targetTouches.length == 1) {
			var touch = event.targetTouches[0]; // 把元素放在手指所在的位置
			startY = touch.pageY;
		}
	}, false);

	arm.addEventListener('touchmove', function(event) {
		event.preventDefault();// 阻止其他事件
		if (event.targetTouches.length == 1) {
			var touch = event.targetTouches[0]; // 把元素放在手指所在的位置
			// 手臂被拖拉高度
			armDragH = touch.pageY - startY;
			if (armDragH > maxDragH) {
				arm.className = "arm arm-shake";
			} else {
				arm.style.webkitTransform = 'translate(' + 0 + 'px, '+ armDragH + 'px)';
			}
			luckBox.className = "box-animation";
			finger.className = "";
			luckTips.className = "";
		}
	}, false);

	arm.addEventListener('touchend', function(event) {
		event.preventDefault();// 阻止其他事件
		if (!arm)
			return;
		if (armDragH > maxDragH) {
			// 延迟执行抽奖
			setTimeout('lottery()', 1000);
		} else {
			resetArm();
		}

	}, false);
}
/**
 * 复位手臂状态
 */
function resetArm() {
	arm.style.webkitTransform = 'translate(0px, 0px)';
	arm.className = "arm";
	luckBox.className = "box-front";
	finger.className = "finger";
	luckTips.className = "luck-tips";
	
}
/**
 * 切换手臂样式
 */
function switchArm() {
	var armSize = 5;
	var i = Math.floor(Math.random() * armSize);
	var cIndex = $('#arm').attr('data-index');
	if (i == cIndex) {
		switchArm();
	} else {
		$('#arm').attr('data-index',i).attr('style','background-image:url('+_webApp+'source/modules/xc_zjp/template/images/arm-bg-'+i+'.png);')
		$('#armImg').attr('style','background-image:url('+_webApp+'source/modules/xc_zjp/template/images/arm-'+i+'.png);')
	}
}
var isOperate=true;
/**
 * 抽奖
 */
function lottery() {
	if(isOperate)
	{
		isOperate=false;
		$.post(_Awaryurl, {

		}, function(data) {
				if(data.message.status == 0) {
	
				surplusCount = data.message.surplusCount;
			//	phone = data.phone;
				if (data.message.hasPrize) {
					// 有奖品
	
					$('#prizeName').html(data.message.gift);
					$('#prizeImg').attr("src", _webApp+"resource/attachment/"+data.message.giftimg);
					$('#luckText').html('你还有<span>'+surplusCount+'</span>次抽奖机会，继续加油！')
					if (surplusCount > 0) {
						$('#luckPopupBtn').html("继续抽奖").attr('href','javascript:closeLuckPopup();');
					} else {
						//if (parseInt(isShare)) {
							$('#luckText').html('您的抽奖次数已经用完，谢谢参与！')
							$('#luckPopupBtn').html("我的奖品").attr('href',_GiftsUrl);
			
					}
					if(data.message.needinfo)
					{
						$('#luckPopupBtn').html("登记信息").attr('href','javascript:openPhonePopup();');
					}
					openLuckPopup();
				} else {
					// 无奖品
					//<img src="'+_webApp+'source/modules/xc_zjp/template/images/noprize.png" style="margin: 0 auto;">
						$('#promptHead').html('啥也没抽中');
				
							if (surplusCount > 0) {
					$('#promptWord').html(
								'<p>你还有<span id="surplusCount">'
									+ surplusCount
										+ '</span>次抽奖机会，继续加油！</p>');
						$('#prompBtn').html("继续抽奖").attr('href', 'javascript:closePromptPopup();');
					}else
					{
						//<img src="'+_webApp+'source/modules/xc_zjp/template/images/noprize.png" style="margin: 0 auto;">
						$('#promptHead').html('啥也没抽中');
							$('#promptWord').html('您的抽奖次数已经用完，谢谢参与！')
							$('#prompBtn').html("我的奖品").attr('href',_GiftsUrl);
					}
		
					openPromptPopup();
				}
			} else if (data.message.nochance) {
				noCountPopup();
			} else {
					surplusCount = data.message.surplusCount;

				if(data.message.useCount)
				{
					//<img src="'+_webApp+'source/modules/xc_zjp/template/images/noprize.png" style="margin: 0 auto;">
						$('#promptHead').html('啥也没抽中');
						if (surplusCount > 0) {
								$('#promptWord').html(
								'<p>你还有<span id="surplusCount">'
									+ surplusCount
										+ '</span>次抽奖机会，继续加油！</p>');
									$('#prompBtn').html("继续抽奖").attr('href', 'javascript:closePromptPopup();');
							
					}else
					{
								
							$('#promptWord').html('您的抽奖次数已经用完，转发获得更多抽奖机会！')
							$('#prompBtn').html("我的奖品").attr('href',_GiftsUrl);
					}
						
						openPromptPopup();
				}else
				{
				alert(data.message.message);
				isOperate=true;
				}
			}
		}, 'json');
	}
}
function nochangesPopup()
{
$('#promptWord').html('您的抽奖次数已经用完，转发获得更多抽奖机会！')
$('#prompBtn').html("我的奖品").attr('href',_GiftsUrl);	
openPromptPopup();
	
}
/**
 * 弹出无次数的提示
 */
function noCountPopup() {
	//if (parseInt(isShare)) {
		$('#promptHead').html("谢谢参与分享可获取更多抽奖机会");
		$('#promptWord').html("您的抽奖次数已经用完");
		$('#prompBtn').html("我的奖品").attr('href',_GiftsUrl);
		openPromptPopup();
/*	} else {
		$('#promptHead').html("分享可获取<span>5</span>次抽奖机会");
		$('#promptWord').html("您的抽奖次数已经用完,分享可获取<span>5</span>次抽奖机会");
		$('#prompBtn').html("分享").attr('href', 'javascript:openShareMask();');
		openPromptPopup();
	}*/
}
// 随机产品宣导提示
var tipsArr = [];
tipsArr.push('');
function randomTips() {
	//var n = tipsArr.length;
	//var i = Math.floor(Math.random() * n);
	//var cIndex = $('.tips').attr('data-index');
	//if (i == cIndex) {
	//	randomTips();
	//} else {
	//	$('.tips').attr('data-index', i).text(tipsArr[i]);
	//}
}
/**
 * 设置电话号码
 */
function setPhone() {
	var phone = $('input[name="phone"]').val();
	if (!/^1\d{10}$/.test(phone)) {
		alert("请输入正确的电话号码");
		return false;
	}
	var realname = $('input[name="realname"]').val();
			if (realname.length < 2) {
				alert('请填写您的姓名!');
				return false;
			}
	$.post(_SetPhoneUrl, {
		phone : phone,	realname : realname
	}, function(data) {
		if (data.result == 'success') {
			alert("设置成功");
		
		} else {
			alert(data.message);
		}
		closePhonePopup();
				closeLuckPopup();
					closePromptPopup();
	}, 'json');
}
/**
 * 分享成功
 */
function shareSuccess() {
	$('#promptHead').html("分享成功");
	$('#promptWord').html("恭喜你获得<span>5</span>次抽奖机会，继续加油！");
	$('#prompBtn').html("继续抽奖").attr('href', 'javascript:closePromptPopup();');
	openPromptPopup();
}
/**
 * 打开抽奖结果弹出框
 */
function openLuckPopup() {
		isOperate=false;
	randomTips();
	$('#luckPopup').show();
	resetArm();
	//随机切换手臂样式
	switchArm();
}
/**
 * 关闭抽奖结果弹出框
 */
function closeLuckPopup() {
	
	isOperate=true;
	$('#luckPopup').hide();

}
/**
 * 打开提示弹出框
 */
function openPromptPopup() {
		isOperate=false;
	randomTips();
	$('#promptPopup').show();
	resetArm();
	//随机切换手臂样式
	switchArm();
}
/**
 * 关闭提示弹出框
 */
function closePromptPopup() {
	isOperate=true;
	$('#promptPopup').hide();
}
/**
 * 打开电话弹出框
 */
function openPhonePopup() {
		isOperate=false;
	//randomTips();
	$('#phonePopup').show();
}
/**
 * 关闭电话弹出框
 */
function closePhonePopup() {

	isOperate=true;
	$('#phonePopup').hide();
}
/**
 * 打开分享遮罩层
 */
function openShareMask() {
	$('.popup').hide();
	$('#shareMask').show();
}
/**
 * 关闭分享遮罩层
 */
function closeShareMask() {
	isOperate=true;
	$('#shareMask').hide();
}