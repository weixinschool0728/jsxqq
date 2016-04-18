
var count = 1;
var flag = 0;
$(function() {
	$("#slider").draggable({
		axis: 'x',
		containment: 'parent',
		drag: function(event, ui) {
			if (ui.position.left > 250) {
			alert(1);
				//$("#well").fadeOut();,...
			} else {
			alert(2);
			    // Apparently Safari isn't allowing partial opacity on text with background clip? Not sure.
				// $("h2 span").css("opacity", 100 - (ui.position.left / 5))
			}
		},
		stop: function(event, ui) {
			if (ui.position.left < 250) {
			alert(3);
				flag = 0;
				$(this).animate({
					left: 0
						
				})
			}
		}
	});
	
	// The following credit: http://www.evanblack.com/blog/touch-slide-to-unlock/
	
	
	
	$('#slider')[0].addEventListener('touchmove', function(event) { 
	    event.preventDefault();
	    var el = event.target;
	    var touch = event.touches[0];
	    curX = touch.pageX - this.offsetLeft - 73;
	    if(curX <= 0) return;
		 if(curX > 323){	
		 return;
		 }
	    if(curX > 250){		
			if ((count == 1) && (flag == 0))
			{				
				$('#well1').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 2) && (flag == 0))
			{
				$('#well2').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 3) && (flag == 0))
			{
				$('#well3').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 4) && (flag == 0))
			{
				$('#well4').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 5) && (flag == 0))
			{
				$('#well5').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 6) && (flag == 0))
			{
				$('#well6').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 7) && (flag == 0))
			{
				$('#well7').fadeIn(10);
				count++;
				flag = 1;			
			}
			else if((count == 8) && (flag == 0))
			{	
				$('#well8').fadeIn(10);
				count++;
				flag = 1;			
			}	
			else if((count == 9) && (flag == 0))
			{	
				$('#well9').fadeIn(10);
				setTimeout("showTxt()",2000);
				clickf1();
				count++;
				flag = 1;			
			}
	    }
		else
		{
			flag = 0;
		}
	   	el.style.webkitTransform = 'translateX(' + curX + 'px)'; 
	}, false);
	
	$('#slider')[0].addEventListener('touchend', function(event) {	
	    this.style.webkitTransition = '-webkit-transform 0.3s ease-in';
	    this.addEventListener( 'webkitTransitionEnd', function( event ) { this.style.webkitTransition = 'none'; }, false );
	    this.style.webkitTransform = 'translateX(0px)';
	}, false);

});