/**
 * Created by tanytree on 2015/7/17.
 */
$(function(){
    var docHeight = $(document).height();
    // $(".fullBg").height(docHeight).show();
    $(".w0 a.xClosed").hover(function(){
        $(this).removeClass("irotateOut").addClass("irotateIn");
    },function(){
        $(this).removeClass("irotateIn").addClass("irotateOut");
    });

    // showWindow(".w0");//展示窗口，不调用不生效
});
//窗口关闭
function xClosed(){
    $(".window").fadeOut();
    $(".fullBg").css({
	    'display':'none',
	    'height':'0'
    })
    $('body,html').css('overflow', 'auto');
}

function showWindow(a) {
    $('body,html').css('overflow', 'hidden');
    centerWindow(a);
};

//3.点击弹窗方法
function clickaShowWindow(a, b) {//a是当前点击的按钮，b是显示的窗口
    $(b).click(function() {
        centerWindow(a);
        $(".fullBg").show();
        $(a).slideDown(300);
        return false;
    });
}
//2.将盒子方法放入这个方，方便法统一调用
function centerWindow(a) {
    center(a);
    //$(a).show();
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
