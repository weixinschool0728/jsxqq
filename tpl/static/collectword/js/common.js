/**
 * Created by tanytree on 2015/7/15.
 */
var timer=0;
$(function(){
    var docHeight = $(document).height();
    $(".fullBg").height(docHeight);
    tab(".oTab");
    centerWindow(".w1");
    centerWindow(".w2");
    centerWindow(".w3");
    centerTop(".w4");
    centerTop(".w0");
    $(".w4,.fullBg,.window .oClosed").click(function(){
        $(".window").removeClass("animate").hide();
        $(".fullBg").hide();
        clearTimeout(timer);
    });
    timeShow();//倒计时
    lastLi(".actPart2 .priceList");//处理最后的边框
    lastLi(".userLsit");//处理最后的边框
});

function lastLi(a){
    $(a).find("li").last().css('borderBottom','0');
}
function showWindow(oWindow){
    $(".fullBg").show();
    $(oWindow).addClass("animate").show();$('.getCode').click(function(){
		var tel = $('input[name=tel]').val();
		var tel_length = tel.length;
		var thisget = $(this).text();
		if(tel == ''){
			alert('请填写您的手机号');
		}else if(tel_length != 11){
			alert('请填写正确的手机号');
		}else{
			if(thisget == '获取验证码' || thisget == '重新获取'){
				$('.getCode').text('正在发送');
				$.ajax({
					type:"POST",
					url:$('params').attr('url-sms'),
					dataType:"json",
					data:{
						tel:tel,
						token:$('params').attr('token'),
						id:$('params').attr('id'),
						wecha_id:$('params').attr('wecha_id')
					},
					success:function(data){
						data.error = 0;
						if(data.error == 0){
							smsdaoshi()
						}else if(data.error == 'tel'){
							alert('该手机号码已被注册');$('.getCode').text('获取验证码');
						}else if(data.error == 4085){
							alert('该手机号码发送验证码超过每日限制');$('.getCode').text('获取验证码');
						}else if(data.error == null){
							alert('发送失败：该商家未购买短信');$('.getCode').text('获取验证码');
						}else{
							alert('发送失败：请稍后再试<br/>失败原因：'+data.error);$('.getCode').text('获取验证码');
						}
					}
				});
				
			}
		}
		
	});
	$('#telyzbut').click(function(){
		var tel = $('input[name=tel]').val();
		var code = $('input[name=code]').val();
		var tel_length = tel.length;
		if(tel == ''){
			alert('请填写您的手机号');
		}else if(tel_length != 11){
			alert('请填写正确的手机号');
		}else if(code == ''){
			alert('请填写您收到的验证码');
		}else{
			$('#telyzbut').text('手机号码验证中');
			$.ajax({
				type:"POST",
				url:$('params').attr('url-smsyz'),
				dataType:"json",
				data:{
					tel:tel,
					code:code,
					token:$('params').attr('token'),
					id:$('params').attr('id'),
					wecha_id:$('params').attr('wecha_id')
				},
				success:function(data){
					if(data.error == 0){
						$('#telyzbut').text('验证成功');
						window.location.href=$('params').attr('url-smsyz-success')+'&isjoin=1';
					}else if(data.error == 1){
						alert('验证码不正确');
					}else if(data.error == 2){
						alert('验证码和手机不匹配');
					}else if(data.error == 3){
						alert('验证码超时');
					}
				}
			});
		}
	});
}
var daoshi = 60;
function smsdaoshi(){
	if(daoshi < 1){
		daoshi = 60;
		$('.getCode').text('重新获取');
		$('.getCode').addClass('butCode');
	}else{
		if(daoshi == 60){
			$('.getCode').text('发送成功');
		}else{
			$('.getCode').text(daoshi+'s');
		}
		daoshi--;
		setTimeout("smsdaoshi()",1000);
	}
}
function showW1(){
    $(".fullBg").show();
    $(".w1").addClass("animate").show();
    closedWindow();
}
function closedWindow(){
    timer=setTimeout(function(){
        $(".fullBg").hide();$(".window").removeClass("animate").hide();;
    },4000);
}


function tab(obj){
    var tabObj=$(obj)
    var len=tabObj.find('.hd ul li');
    var row=tabObj.find('.bd .row');
    len.bind("click",function(){
        var index = 0;
        $(this).addClass('on').siblings().removeClass('on');
        index = len.index(this);
        row.eq(index).show().siblings().hide();
        return false;
    }).eq(0).trigger("click");
}
//2.将盒子方法放入这个方，方便法统一调用
function centerWindow(a) {
    center(a);
    //自适应窗口
    $(window).bind('scroll resize',
        function() {
            center(a);
        });
}

//1.居中方法，传入需要剧中的标签
function center(a) {
    var wWidth = $(window).width();
    var wHeight = $(window).height();
    var boxWidth = $(a).width();
    var boxHeight = $(a).height();
    var scrollTop = $(window).scrollTop();
    var scrollLeft = $(window).scrollLeft();
    var top = scrollTop + (wHeight - boxHeight) / 2;
    var left = scrollLeft + (wWidth - boxWidth) / 2;
    $(a).css({
        "top": top,
        "left": left
    });
}
function centerTop(a) {
    var wWidth = $(window).width();
    var boxWidth = $(a).width();
    var scrollLeft = $(window).scrollLeft();
    var left = scrollLeft + (wWidth - boxWidth) / 2;
    $(a).css({
        "left": left
    });
}

function timeShow(){
    var show_time = $(".timeShow");
    endtime = new Date($('params').attr('date'));//结束时间
    today = new Date();//当前时间
    delta_T = endtime.getTime() - today.getTime();//时间间隔
    if(delta_T < 0){
    	clearInterval(timeShow);
    	$('#lottery a').removeAttr('class');
        alert("活动已结束");
        return false;
    }
    window.setTimeout(timeShow,1000);
    total_days = delta_T/(24*60*60*1000);//总天数
    total_show = Math.floor(total_days);//实际显示的天数
    total_hours = (total_days - total_show)*24;//剩余小时
    hours_show = Math.floor(total_hours);//实际显示的小时数
    total_minutes = (total_hours - hours_show)*60;//剩余的分钟数
    minutes_show = Math.floor(total_minutes);//实际显示的分钟数
    total_seconds = (total_minutes - minutes_show)*60;//剩余的分钟数
    seconds_show = Math.floor(total_seconds);//实际显示的秒数
    if(total_days<10){
        total_days="0"+total_days;
    }
    if(hours_show<10){
        hours_show="0"+hours_show;
    }
    if(minutes_show<10){
        minutes_show="0"+minutes_show;
    }
    if(seconds_show<10){
        seconds_show="0"+seconds_show;
    }
    show_time.find("li").eq(0).text(total_show);//显示在页面上
    show_time.find("li").eq(2).text(hours_show);
    show_time.find("li").eq(4).text(minutes_show);
    show_time.find("li").eq(6).text(seconds_show);
}

$(function(){
   $(".putTags a").click(function(){
       $(this).addClass("on").siblings().removeClass("on");
   });
});
$(function(){
    var t=null;
    clearTimeout(t);
    t=setTimeout(function(){
        $(".animate").removeClass("animate");
    },4000);
});


$(function(){
    var wHeight = $(window).height();
    var boxHeight = $("footer").height();
    var top = wHeight - boxHeight;
    if(gt_ios8()){
        $("footer").css({
            bottom:"inherit",
            top:top
        });
    }else{
        return false
    }
});

function gt_ios8() {
    // 判断是否 iPhone 或者 iPod
    if((navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPod/i))) {
        // 判断系统版本号是否小于 8，下面条件成立就表示小于8否则>=8
        return Boolean(navigator.userAgent.match(/OS [3-7]_\d[_\d]* like Mac OS X/i));
    } else {
        return false;
    }
}


var lottery={
    index:0,	//当前转动到哪个位置
    count:0,	//总共有多少个位置
    timer:0,	//setTimeout的ID，用clearTimeout清除
    speed:200,	//初始转动速度
    times:0,	//转动次数
    cycle:50,	//转动基本次数：即至少需要转动多少次再进入抽奖环节
    prize:-1,	//中奖位置
    data :null,
    init:function(id){
        if ($("#"+id).find(".lottery-unit").length>0) {
            $lottery = $("#"+id);
            $units = $lottery.find(".lottery-unit");
            this.obj = $lottery;
            this.count = $units.length;
            $lottery.find(".lottery-unit-"+this.index).addClass("active");
        };
    },
    roll:function(){
        var index = this.index;
        var count = this.count;
        var lottery = this.obj;
        $(lottery).find(".lottery-unit-"+index).removeClass("active");
        index += 1;
        if (index>count-1) {
            index = 0;
        };
        $(lottery).find(".lottery-unit-"+index).addClass("active");
        this.index=index;
        return false;
    },
    stop:function(index){
        this.prize=index;
        return false;
    }
};

function roll(){
    lottery.times += 1;
    lottery.roll();
    if (lottery.times > lottery.cycle+10 && lottery.prize==lottery.index) {
        clearTimeout(lottery.timer);
        lottery.prize=-1;
        lottery.times=0;
        click=false;
        lodingGif(true);
    }else{
        if (lottery.times<lottery.cycle) {
            lottery.speed -= 10;
        }else if(lottery.times==lottery.cycle) {
            var index = Math.random()*(lottery.count)|0;
            //lottery.prize = lottery.pos; // 自定义停止位置
        }else{
            if (lottery.times > lottery.cycle+10 && ((lottery.prize==0 && lottery.index==7) || lottery.prize==lottery.index+1)) {
                lottery.speed += 110;
            }else{
                lottery.speed += 20;
            }
        }
        if (lottery.speed<40) {
            lottery.speed=40;
        }
        lottery.timer = setTimeout(roll,lottery.speed);
    }
    return false;
}

var click=false;

window.onload=function(){
    lottery.init('lottery');
    $("#lottery a.start").click(function(){
        clearTimeout(timer);
        lodingGif(false);
        if (click) {
            return false;
        }else{
        	$.ajax({
        		url : $('params').attr('url'),
        		dataType : 'JSON',
        		success : function (data) {
        			lottery.prize = data.message;
        			lottery.data = data;
        			if( -1 == data.status) {
        				alert('集字次数已用完，分享给朋友可以增加集字次数哦');
        				window.location = window.location.href;
        			}
        		}
        	});
            lottery.speed=100;
            roll();
            click=true;
            return false;
        }
    });
    $("#lottery a.prize").click(function(){
    	showWindow('.w3');
    });
    $('a.in').click(function () {
    	var is_sms = $('params').attr('is-sms');
    	var tel = $('params').attr('tel');
    	if ('1' == is_sms && tel == '') {
    		showWindow('.w0');
    	} else {
    		window.location = $('params').attr('url-join')+'&isjoin=1';
    	}
    });

};
//点击显示专转圈圈
function lodingGif(stop) {
	if (stop) {
		var is = $('#lottery .lottery-unit-'+lottery.data.message).find('b').html();
		if (undefined == is) {
			$('#lottery .lottery-unit-'+lottery.data.message).append('<b><i></i>'+lottery.data.count+'</b>');
		} else {
			$('#lottery .lottery-unit-'+lottery.data.message).find('b').html('<i></i>'+lottery.data.count);
		}
		$('#lottery .row p').html('('+lottery.data.prize_count+'次)');
		if (1 > lottery.data.prize_count) {
			//$('#lottery a').off('click');
		}
        if (1 == lottery.data.status) {
        	showWindow('.w3');
        	window.shareData.tTitle = $('params').attr('prize_fxtitle');
        	window.shareData.tContent = $('params').attr('prize_fxinfo');
        	window.shareData.fTitile = $('params').attr('prize_fxtitle');
        	$('#lottery a').off('click');
        } else {
        	window.shareData.tTitle = $('params').attr('fxtitle_d').replace('{{字数}}', lottery.data.total);
        	window.shareData.tContent = $('params').attr('fxinfo_d').replace('{{字数}}', lottery.data.total);
        	window.shareData.fTitile = $('params').attr('fxtitle_d').replace('{{字数}}', lottery.data.total);
        }
        $(".lodingGif").hide();
        $("#lottery a").show();
	} else {
	    $("#lottery a").hide();
	    $(".lodingGif").show();
	}
}










