/**
 * Created by tanytree on 2015/10/20.
 */

$(function(){
    $(".otherUser .bd .desc>a.arrowa").on("click",function(){
        $(this).toggleClass('on');
        $(this).parent().parent().parent().find(".subInfo").slideToggle();
    });
});
//窗口处理
$(function(){
$(".aRule").on('click',function(){
    rule();
});
    login();
});

//登录窗口
function login(){
    $(".fullBg").show();
    $(".oLogin").fadeIn();
};

//规则窗口
function rule(){
    setWindow.centerWindow('.wRule');
    $(".fullBg").fadeIn();
    $(".wRule").fadeIn();
}

var setWindow = {
    //1.居中方法，传入需要剧中的标签
    center: function(a) {
        var wWidth = $(window).width();
        var wHeight = $(window).height();
        var boxWidth = $(a).outerWidth(true);
        var boxHeight = $(a).height();
        var scrollTop = $(window).scrollTop();
        var scrollLeft = $(window).scrollLeft();
        var top = scrollTop + (wHeight - boxHeight) / 2;
        var left = scrollLeft + (wWidth - boxWidth) / 2;
        $(a).css({"top": top, "left": left});
    },
    //2.将盒子方法放入这个方，方便法统一调用
    centerWindow: function(a) {
        setWindow.center(a);
        //自适应窗口
        $(window).bind('scroll resize', function() {
            setWindow.center(a);
        });
    },
    //3.点击弹窗方法
    clickaShowWindow: function(a, b) {
        $(b).click(function() {
            setWindow.centerWindow(a);
            $(".fullBg").show();
            $(a).slideDown(300);
            return false;
        });
    },
    xClosed:function(){
        $(".fullBg").hide();
        $(".window").hide();
        $(".flagPosition").removeClass("hidden");
        $(".userWord ").css('visibility','visible');
    },
    closedWindow:function(){
        var timer=null;
        timer=setTimeout(function(){
            $(".fullBg").hide();$(".window").hide();
        },4000);
    },
    windowClosed:function(){
        $(".fullBg").hide();
        $(".window").hide();
    }
};



(function timeShow(){
    var show_time = $(".timeShow");
    var auto=null;
    endtime = new Date("09/11/2016 10:09:10");//结束时间
    today = new Date();//当前时间
    delta_T = endtime.getTime() - today.getTime();//时间间隔
    if(delta_T < 0){
        clearInterval(auto);
        alert("倒计时已经结束");
        return;
    }
    auto=window.setTimeout(timeShow,1000);
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
})();
