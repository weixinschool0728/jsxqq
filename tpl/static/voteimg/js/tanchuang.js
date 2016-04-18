$(function(){
	$('#btnclick2').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		$('.ttop2').css('height',t);
		$('.divShow2').css('width',w).css('height',h).addClass('show1');
		$('.ttop2').css('height',t);
		$('.dis2').show();
	})
	$('.divShow2').click(function(){
		$(this).hide();
		$('.dis2').hide();
	});
});
//nober

$(function(){
	$('#btnclick1').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		$('.divShow1').css('width',w).css('height',h).addClass('show1');
		$('.dis1').show();
		$("#cabutt").show();
	})
	$('.divShow1').click(function(){
		$(this).hide();
		$('.dis1').hide();
	});
});
//À—À˜
$(function(){
	$('#btnclick').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		var t=document.body.scrollTop;
		// $('.ttop').css('height',t);
		var o=window.screen.height;
		$('.objg1').css('height',o*0.2);
		$('.divShow').css('width',w).css('height',h).addClass('show');
		$('.dis').show();
		//$('.ttop').css('',w).css('top',t).css('width',w);
	})
	$('.divShow').click(function(){
		$(this).hide();
		$('.dis').hide();
	});
});
//≈≈√˚

$(function(){
	$('#btnclick3').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var o=window.screen.availHeight;
		$('.obj').css('height',o*0.7);
		$('.divShow3').css('width',w).css('height',h).addClass('show3');
		$('.dis3').show();
		$('.divShow3').show();
	})
	$('#cabutt').click(function(){
		$(this).hide();
		$('.dis3').hide();
	});
});
//√»±¶œÍ«È
$(function(){
	$('#btnclick4').click(function(){
		var h =document.body.scrollHeight;
		var w = window.innerWidth;
		var t=document.body.scrollTop;
		var t=document.body.scrollTop;
		// $('.ttop').css('height',t);
		var o=window.screen.height;
		$('.objg1').css('height',o*0.2);
		$('.divShow4').css('width',w).css('height',h).addClass('show');
		$('.dis4').show();
		//$('.ttop').css('',w).css('top',t).css('width',w);
	})
	$('.divShow4').click(function(){
		$(this).hide();
		$('.dis4').hide();
	});
});
//¿≠∆±

$(function() {
$('a.jqlightbox').lightBox({
	overlayBgColor: '#000000',
	overlayOpacity: 0.8,
	containerBorderSize: 10,
	containerResizeSpeed: 400,
	fixedNavigation: false        
	//µ„ª˜µØ≥ˆøÚ  Õ∂∆±≥…π¶
	});
});
function monuseoverout1(obj){ 
var description_title=document.getElementById("description_title");
var shipping_title=document.getElementById("shipping_title");

var detail=document.getElementById("detail");
var product_rz=document.getElementById("product_rz");
var ProductReviews=document.getElementById("ProductReviews");
product_rz.style.display="none";
if(obj=="description_title"){
description_title.setAttribute("class","tab-menu hover");
shipping_title.setAttribute("class","tab-menu");
detail.style.display="block";
product_rz.style.display="none";

}else if(obj=="shipping_title"){
description_title.setAttribute("class","tab-menu");
shipping_title.setAttribute("class","tab-menu hover");

detail.style.display="none";
product_rz.style.display="block";

} 

} //«–ªªdiv

//∂¡–¥cookie∫Ø ˝
function GetCookie(c_name) {
	if (document.cookie.length > 0) {
		c_start = document.cookie.indexOf(c_name + "=");
		if (c_start != -1) {
			c_start = c_start + c_name.length + 1;
			c_end   = document.cookie.indexOf(";",c_start);
			if (c_end == -1) {
				c_end = document.cookie.length;
			}
			return unescape(document.cookie.substring(c_start,c_end));
		}
	}
	return null
}
//…Ë÷√cookie
function SetCookie(c_name,value,expiredays) {
	var exdate = new Date();
	exdate.setDate(exdate.getDate() + expiredays);
	document.cookie = c_name + "=" +escape(value) + ';path=/' +((expiredays == null) ? "" : ";expires=" + exdate.toGMTString());
}