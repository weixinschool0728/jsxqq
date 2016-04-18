$(document).ready(function () {

    var isclient = false;



    try {

        if (window.external == null || typeof(window.external.JsCrossDomain) == "undefined") {

            $("body").css("background", "#f0f2f4");



            $(".website").show();

            $(".mediaclient").hide();



            //$("#preview").css("height", "auto");

            //$("#preview_content").css("height", "auto");

        } else {

            isclient = true;

        }

    } catch (ex) {

        //alert(ex.message);

    }



    if (!isclient) {

        $("#preview_content").bind("click", function () {



        });

    }



    $(".element-item").bind("click", function () {

        $(".element-item.selected").removeClass("selected");

        //$(this).siblings().removeClass("selected");

        $(this).addClass("selected");



        $("#preview").show();

        $("#preview_content").html("");



        if (isclient) {

            $("#preview_content").html($(".content", this).html());

        } else {

            $("#preview_content").html("<br/>" + $(".content", this).html() + "<br/>");

        }
        

    });

    $("#preview_btok").bind("click", function () {
        var html = $("#preview_content").html();

        //返回数据到主框架
        var origin      = artDialog.open.origin;
        //接收iframe数据
        var iframeData = {
            'editer'      : art.dialog.data('editer'),
        };

        if(html != ''){
        	var re = /<img/img;
        	var img = re.test(html);
        	if(img) {
        		$.ajax({
        			url : '/index.php?g=User&m=Message&a=uploadImage',
        			type : 'POST',
        			data : {'html': html},
        			success : function (data) {
        				iframeData.editer.insertHtml(data);
        				art.dialog.close();
        			}
        		});
        	} else {
				iframeData.editer.insertHtml(html);
				art.dialog.close();
        	}
        }else{
            alert('你还没有选择素材');
        }
        
    });

 function colorSelected(color) {

            var colorattr = $(".element-item.selected").attr("data-color");

            if (colorattr == undefined || colorattr == "") {

                return;

            }

            var cas = colorattr.split(';');

            for (var i = 0; i < cas.length; i++) {

                var ca = cas[i];

                if (ca == undefined || ca == "") {

                    continue;

                    ;

                }

                var cai = ca.split(':');



                if (cai.length < 2) {

                    continue;

                }



                $('#preview_content ' + cai[0]).css(cai[1], color);

            }

        }

    if ($("#picker").length > 0) {

        $.farbtastic('#picker', colorSelected).setColor("#000000");



       

    }

});