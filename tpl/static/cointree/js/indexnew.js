// JavaScript Document
$(function() {
    $(".table_title1").click(function() {
        aaa('table_List2', 'table_List3', 'table_List4', 'table_List1');

    });
    $(".table_title2").click(function() {
        aaa('table_List1', 'table_List3', 'table_List4', 'table_List2');

    });
});
function aaa(sClass1, sClass2, sClass3, sClass4) {
	$('.' + sClass1).hide();
	$('.' + sClass2).hide();
	$('.' + sClass3).hide();
	$('.' + sClass4).show();
}
$(function() {
    $(".table_list_title li").click(function() {
        $(this).addClass("table_list_title_on").siblings().removeClass("table_list_title_on")
    });
});

$(function(){
$(".banner_close").click(function() {
    $(".banner").fadeOut(1000);
})
$(".help").click(function() {
    $(".jibi").show();
    $(".zhezhao").show();
})
$(".zhezhao,.jibi,.hezi_content,.yuanyuan_content,.jibi_share").click(function() {
    $(".jibi").fadeOut(300);
    $(".validate_content").fadeOut(300);
    $(".hezi_content").fadeOut(300);
    $(".zhezhao").fadeOut(300);
	$(".yuanyuan_content").fadeOut(300);
	$(".jibi_share").fadeOut(300);
	$(".tree_tree").attr('tid','ok');
});
$(".jion").click(function() {
    $(".validate_content").show();
    $(".zhezhao").show();

})
})
$(function() {
    centerWindow(".tankuang_content");
})
function centerWindow(a) {
    center(a);
    //自适应窗口
    $(window).bind('scroll resize', function() {
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
    var left = scrollLeft + (wWidth - 355) / 2;
    if (wWidth < 355) {
        $(a).css({
            "top": top,
      "left": left
        });

    } else {
        $(a).css({
            "top": top,
            "left": left
        });
    }
}