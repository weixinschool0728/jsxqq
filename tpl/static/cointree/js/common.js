function sendMsg(){
	var num = $(":input[name='tel']").val();
	var reg=/^0{0,1}1[0-9]{10}$/i;
	if( num == '' || !reg.test(num)){
		alert('请输入正确的手机号！');
		return false;
	}
   if (confirm("我们将会发送验证码到 "+num) && num != ""){
		jQuery(function($) {
			$.ajax({
				url:"/index.php?g=Wap&m=CoinTree&a=SmsSend",
				type:"post",
				data:"mobile="+num,
				beforeSend: function(){ 
					$("#a_verify").attr("disabled","disabled");
				},
				success:function(data){
					if(data == 'done'){
						alert('获取验证码成功,请及时查收,验证码30分钟内有效');
						$("#a_verify").css({"background":"#ccc","borderColor":"#ccc"});
						fun_timedown(60);
						return false;
					}else if(data == 'not_buy'){
						alert('该商家未购买短信，短信无法发送');
						$(".validate_content").fadeOut(300);
						$(".zhezhao").fadeOut(300);
						return false;
					}else{
						if(!confirm(data+'。\n点击确定继续填写手机号,点击取消关闭。')){
							$(".validate_content").fadeOut(300);
							$(".zhezhao").fadeOut(300);
							return false;
						}
					}
			 },
			complete: function(){$("#a_verify").removeAttr("disabled");}
			});
		});
	}
}

function fun_timedown(time){
	if(time=='undefined'){
		time = 60;
	}

	$("#a_verify").text(time+"秒");
	$("#a_verify").attr("disabled","disabled");
	
	time = time-1;
	if(time>=0){
		setTimeout("fun_timedown("+time+")",1000);
	}else{
		$("#a_verify").removeAttr("disabled");
		$("#a_verify").css({"background":"#3F9D4A","borderColor":"#3F9D4A"});
		$("#a_verify").text('获取验证码');
	}
}