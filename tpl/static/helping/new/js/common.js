/**
 * Created by tanytree on 2015/7/15.
 */
var timer=0;
$(function(){
    var docHeight = $(document).height();
    $(".fullBg").height(docHeight);
    tab(".tabBox .hd ul li",".tabBox .bd .row","on");
    centerWindow(".window");
    $(".fullBg").click(function(){
        $(".window").removeClass("animate").hide();
        $(this).hide();
        clearTimeout(timer);
    });
    timeShow();//����ʱ
});
function btnClick(){
    $(".fullBg").show();
    $(".w1").addClass("animate").show();
    closedWindow();
}

function showWindow(){
    $(".fullBg").show();
    $(".w0").addClass("animate").show();
}
function closedWindow(){
    timer=setTimeout(function(){
        $(".fullBg").hide();$(".window").removeClass("animate").hide();;
    },4000);
}

function tab(a,b,c){
    var len=$(a);
    len.bind("click",function(){
        var index = 0;
        $(this).addClass(c).siblings().removeClass(c);
        index = len.index(this);
        $(b).eq(index).show().siblings().hide();
        return false;
    }).eq(0).trigger("click");
}
//2.�����ӷ�����������������㷨ͳһ����
function centerWindow(a) {
    center(a);
    //����Ӧ����
    $(window).bind('scroll resize',
        function() {
            center(a);
        });
}

//1.���з�����������Ҫ���еı�ǩ
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


function timeShow(){
    var show_time = $(".timeShow");
    endtime = new Date("05/01/2015 23:59:59");//����ʱ��
    today = new Date();//��ǰʱ��
    delta_T = endtime.getTime() - today.getTime();//ʱ����
    if(delta_T < 0){
        clearInterval(auto);
        alert("����ʱ�Ѿ�����");
    }
    window.setTimeout(timeShow,1000);
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
}