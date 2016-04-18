/*
 * 全站公共脚本,基于jquery-1.9.1脚本库
 */
$(function() {
	//帮
	$(document).on("tap","a.back,a.queding,a.close",function(event){
		event.preventDefault();
		$(this).closest(".shade").hide();
		closeAlert();
	})
	function openAlert(){
		$("body").css({"overflow":"hidden"});
		$("#shade").children().hide();
		document.getElementById("shade").style.display = "block";
	}
	function closeAlert(){
		$("body").css({"overflow":""});
		document.getElementById("shade").style.display = "none";
	}
	//我也抢
	$(document).on("tap",".ido",function(event){
		event.preventDefault();
		openAlert();
		document.getElementById("ido").style.display = "block";
	})
	$(document).on("tap",".get",function(event){
		event.preventDefault();
			openAlert();
			document.getElementById("get").style.display = "block";
	})
	//活动规则
	$(document).on("tap",".gz",function(event){
		event.preventDefault();
		openAlert();
		document.getElementById("guize").style.display = "block";
		scroll.refresh();
	})
	var scroll = new IScroll("#scroll", { scrollX: false, freeScroll: true,fadeScrollbars:true,resizeScrollbars:true,shrinkScrollbars:'clip',scrollbars: true, scrollbars: 'custom' });
	$(document).on("tap",".shares",function(event){
		event.preventDefault();
		openAlert();
		document.getElementById("share").style.display = "block";
	})
	$(document).on("tap","#share",function(event){
		event.preventDefault();
		$("#share").hide();
		closeAlert();
	})
	if ( $(".shares").length ){
		openAlert();
		document.getElementById("share").style.display = "block";
	}
	$(document).on("tap",".closes",function(event){
		event.preventDefault();
		$(this).closest(".ad").hide();
	})
})