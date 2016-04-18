$(function(){
	
	$("#map-close-img" ).bind("click",function(){
				
				$("#map").hide();
				
	});
	
	//开始导航
	$(".right_s").bind("click",function(){
			$("#map").show();
			var posX = $("#data-map").attr("data-map-pos-x");
			var posY = $("#data-map").attr("data-map-pos-y");
			var text = $("#data-map").attr("data-map-text");
			
			//$("#itude-parent-div-"+pageId).show();
			//$("#itude-div-"+pageId).show();
		   //$("#itude-parent-div-"+pageId).css("z-index",1002);
			var guide_map = new BMap.Map("allmap"); // 创建Map实例
			var point = new BMap.Point(posX, posY); // 创建点坐标
			var your_point = null;
			guide_map.centerAndZoom(point, 16); // 初始化地图,设置中心点坐标和地图级别。
			guide_map.enableScrollWheelZoom(); //启用滚轮放大缩小
			guide_map.enableDragging();
			var guide_marker = new BMap.Marker(point); // 创建标注
			guide_map.addOverlay(guide_marker);
			var opts = {
			  position : point,    // 指定文本标注所在的地理位置
			  offset   : new BMap.Size(-50, -50),    //设置文本偏移量
			}
			var label = new BMap.Label(text, opts);  // 创建文本标注对象
			label.setStyle({
				 color : "red",
				 fontSize : "12px",
				 height : "20px",
				 lineHeight : "20px",
				 fontFamily:"微软雅黑"
			 });
			guide_map.addOverlay(label);   
			
			
		});
		
		
		

})

//错误信息
function warningAlert(flag){
	switch (flag){
		case 1: alert("城市列表");break;
		case 2: alert("位置结果未知");break;
		case 3: alert("导航结果未知");break;
		case 4: alert("非法密钥");break;
		case 5: alert("非法请求");break;
		case 6: alert("没有权限");break;
		case 7: alert("服务不可用");break;
		case 8: alert("超时");break;
		default : alert("未知错误");break;
	}
}



