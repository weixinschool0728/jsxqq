/**
 * Created by tanytree on 2015/10/20.
 */

$(function(){
    $(".otherUser .bd .desc>a.arrowa").on("click",function(){
        $(this).toggleClass('on');
        $(this).parent().parent().parent().find(".subInfo").slideToggle();
    });
});
//���ڴ���
$(function(){
$(".aRule").on('click',function(){
    rule();
});
    login();
});

//��¼����
function login(){
    $(".fullBg").show();
    $(".oLogin").fadeIn();
};

//���򴰿�
function rule(){
    setWindow.centerWindow('.wRule');
    $(".fullBg").fadeIn();
    $(".wRule").fadeIn();
}

var setWindow = {
    //1.���з�����������Ҫ���еı�ǩ
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
    //2.�����ӷ�����������������㷨ͳһ����
    centerWindow: function(a) {
        setWindow.center(a);
        //����Ӧ����
        $(window).bind('scroll resize', function() {
            setWindow.center(a);
        });
    },
    //3.�����������
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
    endtime = new Date("09/11/2016 10:09:10");//����ʱ��
    today = new Date();//��ǰʱ��
    delta_T = endtime.getTime() - today.getTime();//ʱ����
    if(delta_T < 0){
        clearInterval(auto);
        alert("����ʱ�Ѿ�����");
        return;
    }
    auto=window.setTimeout(timeShow,1000);
    total_days = delta_T/(24*60*60*1000);//������
    total_show = Math.floor(total_days);//ʵ����ʾ������
    total_hours = (total_days - total_show)*24;//ʣ��Сʱ
    hours_show = Math.floor(total_hours);//ʵ����ʾ��Сʱ��
    total_minutes = (total_hours - hours_show)*60;//ʣ��ķ�����
    minutes_show = Math.floor(total_minutes);//ʵ����ʾ�ķ�����
    total_seconds = (total_minutes - minutes_show)*60;//ʣ��ķ�����
    seconds_show = Math.floor(total_seconds);//ʵ����ʾ������
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
    show_time.find("li").eq(0).text(total_show);//��ʾ��ҳ����
    show_time.find("li").eq(2).text(hours_show);
    show_time.find("li").eq(4).text(minutes_show);
    show_time.find("li").eq(6).text(seconds_show);
})();
