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

    // showWindow(".w0");//չʾ���ڣ������ò���Ч
});
//���ڹر�
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

//3.�����������
function clickaShowWindow(a, b) {//a�ǵ�ǰ����İ�ť��b����ʾ�Ĵ���
    $(b).click(function() {
        centerWindow(a);
        $(".fullBg").show();
        $(a).slideDown(300);
        return false;
    });
}
//2.�����ӷ�����������������㷨ͳһ����
function centerWindow(a) {
    center(a);
    //$(a).show();
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
