/**
 * Created by wangmu on 2015/7/31.
 */

        $(function(){
            startRun(100);
            function startRun(a){
                run();
                num();
                function run(){
                    $(".schedule_icon").css("width","0");
                    $(".schedule_icon").animate({
                        width:a+"%"
                    },1000)
                }
                function num(){
                    var c=parseInt($(".schedule_icon").css("width"))/2.36;
					c=parseInt(c);
                    $(".schedule_txt").text(c+'%');
	 
                    var t=null;
                    if(c==a){
                        clearInterval(t);
                    }else{
                        t=setTimeout( num,1);
                    }
                }

            }

        })
$(function() {
    $(".table_list_title li").click(function() {
        $(this).addClass("table_list_title_on").siblings().removeClass("table_list_title_on")
    });
	    $(".vido li").click(function() {
        $(this).addClass("vido_on").siblings().removeClass("vido_on")
    });
});
 
$(function() {
    $(".cate_memu li").click(function() {
        $(this).addClass("cate_curn").siblings().removeClass("cate_curn")
    });
});

$(function() {
    $(".categroy_content_left_list_title").click(function() {


        $(this).toggleClass("list_curn").siblings().removeClass("list_curn");

        $(this).next("ul").slideToggle("slow").parent().siblings().find("ul").slideUp("slow");


    });
});

$(function(){
	var index =0;
	var remind = $(".remind").length;
	if(remind == 1){ return false;}
    var timer = setInterval(function(){
	index = (index == remind-1) ? 0 : index+1;       
	//某个div显示，其他的隐藏
	$(".remind_se .remind").slideUp("slow").eq(index).slideDown("slow");    
}, 3000);})
$(function() {
    $(".categroy_content_left_list ul").click(function() {});
    $(".banner_close").click(function() {
        $(".banner").fadeOut(1000);
		
		        $(".banner_close").fadeOut(500);
    })
    $(".sex div").click(function() {
        $(this).addClass("sex_curn").siblings().removeClass("sex_curn")
    });
    $('label').click(function() {
        var radioId = $(this).attr('name');
        $('.check label').removeAttr('class') && $(this).attr('class', 'checked');
        $('.check input[type="radio"]').removeAttr('checked') && $('#' + radioId).attr('checked', 'checked');
    });

    $(".check_right label").click(function() {
        $(".make_text").slideDown("slow");
		$(".make_button").addClass("make_mar");
		$(".sex").hide();
    });

    $(".check_left label").click(function() {
        $(".make_text").slideUp("slow");
		$(".make_button").removeClass("make_mar");
		$(".sex").show();
    });

   $(".shear").click(function() {
    $(".shear_box").fadeIn(500);
    $(".zhezhao").fadeIn(500);
})
   $(".shear_box,.zhezhao").click(function() {
    $(".shear_box").fadeOut(500);
    $(".zhezhao").fadeOut(600);
})
});


$(function() {
    var wWidth = $(window).width();
    var h = $(window).height();
    $(".body_h").css("height", h);

})
$(window).resize(function() {
 
});

$(function() {
    center(".content");
})
 
/* $(function() {
$(".list_tab li").mousemove(function(){
    $(".list_go").show()
    
    
    })
})*/
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
    var left = scrollLeft + (wWidth - boxWidth) / 2;

    $(a).css({
        "top": top,
        "left": left
    });

}
 
 $(function() {

    var t = null;
    clearTimeout(t);
    t = setTimeout(function() {
        $(".animation").removeClass("animation");
    }, 5500);
})