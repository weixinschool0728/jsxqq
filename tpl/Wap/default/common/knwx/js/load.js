$(document).ready(function () {
  
  $('#modm').click(function(){
	$('img.lazy').lazyload();						
  });
							
  $('img.lazy').lazyload();
  var box1_i=0;
  var box2_i=0;
  
  $(".onload").hide();
  $("#box1,#box2").each(function () {
    $(this).children(".onload").eq(0).fadeIn();
  });
  
  $("#box1_showmore").click(function(){
	box1_i++;
	$("#box1").each(function () {
	   $(this).children(".onload").eq(box1_i).fadeIn();
	   $('img.lazy').lazyload();
	});
	if (box1_i == (showNum1-1)){
		document.getElementById("showmore1").style.display = "none";
	}
  });
  
  $("#box1_hide").click(function(){	
	  box1_i=0;
	  $("#box1 .onload").hide();
	  $("#box1").each(function () {
		$(this).children(".onload").eq(0).fadeIn();
	  });
  });
  
  $("#box2_showmore").click(function(){
	box2_i++;
	$("#box2").each(function () {
	   $(this).children(".onload").eq(box2_i).fadeIn();
	   $('img.lazy').lazyload();
	});
	if (box2_i == (showNum2-1)){
		document.getElementById("showmore2").style.display = "none";
	}
  });
  
  $("#box2_hide").click(function(){	
	  box2_i=0;
	  $("#box2 .onload").hide();
	  $("#box2").each(function () {
		$(this).children(".onload").eq(0).fadeIn();
	  });
  });
  
});