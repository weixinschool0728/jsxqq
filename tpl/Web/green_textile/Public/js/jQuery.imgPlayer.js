/**
 * --------------------------------------------------------------------
 * jQuery-Plugin "imgPlayer"
 * Version: 1.0, 25.11.2009
 * by wanghe,mail-to:wanghetommy@163.com
 *
 * Copyright (c) 2009 wanghe
 * Licensed under LGPL
 * 		http://www.opensource.org/licenses/lgpl-2.1.php
 * for : make image fade-in-out like flash
 * 声明:
 * 注意避免页面上有class名为'jquery-imagePlayer-'开头的样式声明,以免冲突
 * 例子图片来源互联网,版权归原作者所有.
 * --------------------------------------------------------------------
 */

;(function($) {
	jQuery.fn.imgPlayer = function(settings) {
	// Settings
	settings = jQuery.extend(this,{
		delay:		          3000, 			//停留时间-----------------图片----------
		fadeOutTime:          800, 				//淡出时间(ms)
		fadeInTime:           800, 	    		//淡入时间(ms)
		height:               200,  			//默认高(px)
		width:                720, 				//默认宽(px)
		showTitle:            true, 			//是否显示标题-------标题------------
		titleSlideUpTime:     500, 				//标题上升过程时间(ms)
		titleAnimate:         true, 			//是否启动标题显示时的动画
		tBackgroundColor: 	  '#333333',		//标题背景颜色
		tBackgroundOpacity:   '0.6',			//标题背景透明度
		titleColor:   		  '#ffffff',		//标题文字颜色
		titleHeight:   	 	  30,				//标题显示高度(px)
		titleAlign:   	 	  'bottom',			//标题对齐方式(top,bottom)
		fontSize:   	  	  13,				//标题文字大小(px)
		fontAlign:   		  'left',			//标题文字对齐方式(left,center,right)
		fontWidth:   	      '',				//标题文字显示宽度(px)
		isNewLine: 		  	  false,			//标题文字是否可以换行显示
		isIconShow:   		  true,				//是否显示图标--------------图标------------
		iconSizeW:   		  30,				//图标显示的宽(px)
		iconSizeH:   		  10,				//图标显示的宽(px)
		iconBorder:   		  'none',//图标显示的边框
		iconColor:   		  '#000000',		//图标显示的颜色
		iconFontSize:   	  0,				//图标文字的大小(px)
		iconFontColor:   	  '#ffffff',		//图标的文字颜色
		iconBgUrl:   		  'none',			//图标显示的自定义图标(图片url)
		iconOpacity:   	      '0.4',			//图标显示的透明度
		activeIconSizeW:   	  30,				//激活时图标显示的大小(px)
		activeIconSizeH:   	  10,				//激活时图标显示的大小(px)
		activeIconBorder:     'none',//激活时图标显示的边框
		activeIconColor:   	  '#ffffff',		//激活时图标显示的颜色
		activeIconFontSize:   0,				//激活时文字的大小(px)
		activeIconFontColor:  '#ffffff',		//激活时的文字颜色
		activeIconBgUrl:   	  'none',			//激活时图标显示的自定义图标(图片url)
		activeIconOpacity:    '0.8',			//激活时图标显示的透明度
		iconRight:   	 	  10,   			//图标离右边的距离
		iconBottom:   	  	  6,    			//图标离下边的距离
		iconMargin:   	  	  5,   	 			//图标的间距
		startIndex:     	  0,       		//初始播放序号(若大于图片总数,则从0开始)
		autoPlay:     		  true,     		//自动播放
		mouseEvent:     	  true,     		//鼠标悬浮停止播放
		bindEvent:     		  'click'   		//切换触发的事件
	},settings);
	
	////////////////////////全局变量//////////////////////
	var imgTimer ="";
	var $images = settings.children("img");
	//----------------------主体样式----------------------------------
	if(settings.css("position")=="static"){ //将设置为相对布局
		settings.css("position","relative");
	}
	settings.css({"height":settings.height+'px',"width":settings.width+'px',"overflow":"hidden","text-align":"left","display":"block"});
	//----------------------------图片的样式---------------------------
	$images.css({"position":"absolute","top":"0px","left":"0px","height":settings.height+'px',"width":settings.width+'px',"display":"none"});
	//-----------------------------构造DOM------------------------------
	var imageNum = $images.length;
	var imageIcon = "";
	for(var i=1;i<=imageNum;i++){
		if(i!=imageNum){
			imageIcon += "<span class='jquery-imagePlayer-icon-num'>"+i+"</span><span class='jquery-imagePlayer-icon-blank'></span>";
		}else{
			imageIcon += "<span class='jquery-imagePlayer-icon-num'>"+i+"</span>";
		}
	}
	settings.append("<div class='jquery-imagePlayer-title'><div></div></div>");
	settings.append("<div class='jquery-imagePlayer-icon'>"+imageIcon+"</div>");
	//-----------------------------构造DOM------------------------------
	var $title = settings.children(".jquery-imagePlayer-title");
	var $content = settings.children(".jquery-imagePlayer-title").children("div");
	if(settings.showTitle){//显示标题
		if(settings.fontWidth==''){
			settings.fontWidth = settings.width*7/12;
		}
		//标题样式设置
		$title.css({"position":"absolute","background-color":settings.tBackgroundColor,"height":settings.titleHeight+'px',"width":settings.width+'px',"cursor":"pointer","opacity":settings.tBackgroundOpacity});
		//标题文字样式设置
		$content.css({"width":settings.fontWidth+'px',"overflow":"hidden","font-size":settings.fontSize+'px',"color":settings.titleColor});
		if(jQuery.trim(settings.titleAlign)=="top"){
			$title.css("top","0px");
		}else{
			$title.css("bottom","0px");
		}
		if(jQuery.trim(settings.fontAlign)=="right"){
			$title.css({"text-align":"right","padding-right":"20px"});
		}else if(jQuery.trim(settings.fontAlign)=="center"){
			$title.css({"text-align":"center"});
		}else{
			$title.css({"text-align":"left","padding-left":"20px"});
		}
		if(settings.isNewLine){
			$content.css({"word-break":"break-all","padding-top":"8px","padding-bottom":"5px"});
		}else{
			$content.css({"line-height":settings.titleHeight+'px',"white-space":"nowrap","word-break":"break-all","text-overflow":"ellipsis"});
		}
	}else{
		$title.hide();
	}
	
	//----------------------设置小图标的样式------------------------------
	var $icon = settings.children(".jquery-imagePlayer-icon");
	$icon.css({"position":"absolute","text-align":"left","right":settings.iconRight+'px',"bottom":settings.iconBottom+'px'});
	if(settings.isIconShow){//显示图标
		$icon.children(".jquery-imagePlayer-icon-blank").css({"width":settings.iconMargin+'px',"display":"inline-block"});
		var $smallIcon = $icon.children(".jquery-imagePlayer-icon-num");
		$smallIcon.css({"height":settings.iconSizeH+'px',
						"width":settings.iconSizeW+'px',
						"background-color":settings.iconColor,
						"border":settings.iconBorder,
						"font-size":settings.iconFontSize+'px',
						"text-align":"center",
						"line-height":settings.iconSizeH+'px',
						"color":settings.iconFontColor,
						"background-image":settings.iconBgUrl,
						"opacity":settings.iconOpacity,
						"display":"inline-block"
					});
		$smallIcon.bind(settings.bindEvent,function(){
			if(settings.startIndex==$(this).text()){
				return;
			}
			settings.startIndex = $(this).text()-1;
			imgShow();
		});
		if(settings.autoPlay&&settings.mouseEvent){
			$smallIcon.hover(function(){
				clearInterval(imgTimer);
			},function(){
				imgTimer = setInterval(imgShow,settings.delay);
			});
		}
	}else{
		$icon.hide();
	}
	//初始化数据
	settings.startIndex = settings.startIndex+1>imageNum?0:settings.startIndex;
	
	if(settings.titleSlideUpTime>settings.delay/2){//避免显示不完整
		 settings.titleSlideUpTime = settings.delay/2;
	 }
	var icons = settings.children(".jquery-imagePlayer-icon").children(".jquery-imagePlayer-icon-num");
	////////////////////////显示函数//////////////////////
	function imgShow(){
			var icon = settings.children(".jquery-imagePlayer-icon").children(".jquery-imagePlayer-icon-num").eq(settings.startIndex);
			 icons.css({"height":settings.iconSizeH+'px',
						"width":settings.iconSizeW+'px',
						"background-color":settings.iconColor,
						"border":settings.iconBorder,
						"font-size":settings.iconFontSize+'px',
						"text-align":"center",
						"line-height":settings.iconSizeH+'px',
						"color":settings.iconFontColor,
						"background-image":settings.iconBgUrl,
						"cursor":"pointer",
						"opacity":settings.iconOpacity,
						"display":"inline-block"
					});
					
			 icon.css({ "height":settings.activeIconSizeH+'px',
						"width":settings.activeIconSizeW+'px',
						"background-color":settings.activeIconColor,
						"border":settings.activeIconBorder,
						"font-size":settings.activeIconFontSize+'px',
						"text-align":"center",
						"line-height":settings.activeIconSizeH+'px',
						"color":settings.activeIconFontColor,
						"font-weight":"600",
						"background-image":settings.activeIconBgUrl,
						"cursor":"pointer",
						"opacity":settings.activeIconOpacity,
						"display":"inline-block"
					});
			 
			 var  $activedImg = settings.children("img:visible");
			 var  $preparedImg = settings.children("img").eq(settings.startIndex);
			 if(settings.showTitle&&settings.titleAnimate){//显示动画
				 $activedImg.stop(true,true).fadeOut(settings.fadeOutTime);
				 $title.stop(true,true).fadeOut(settings.fadeOutTime);
				 $preparedImg.stop(true,true).fadeIn(settings.fadeInTime,function(){
				 	titleShow($preparedImg);
				 });
				//$(".print-console").append("$activedImg:"+$activedImg.attr("title")+"$preparedImg:"+$preparedImg.attr("title")+"<br>");
			 }else if(settings.showTitle&&!settings.titleAnimate){//无动画
			 	 var title = $preparedImg.attr("title")||"";
			 	 $activedImg.stop(true,true).fadeOut(settings.fadeOutTime);
				 $preparedImg.stop(true,true).fadeIn(settings.fadeInTime);
				 $content.stop(true,true).fadeOut(settings.fadeOutTime,function(){
				 	 $content.text(title);
				 	 $content.fadeIn(400);
				 });
			 }else{//不显示
			 	 $activedImg.stop(true,true).fadeOut(settings.fadeOutTime);
				 $preparedImg.stop(true,true).fadeIn(settings.fadeInTime);
			 }
			 settings.startIndex++;
			 if(settings.startIndex==imageNum){
			 	settings.startIndex=0;
			 }
	};
	////////////////////////标题动画//////////////////////
	function titleShow($preparedImg){
		 var title = $preparedImg.attr("title")||"";
		 $content.text(title);
		 if(jQuery.trim(settings.titleAlign)=="top"){
			$title.css("top",'-'+settings.titleHeight+'px');
			$title.stop(true,true).show().animate({"top":"0px"},settings.titleSlideUpTime);
		 }else{
			$title.css("bottom",'-'+settings.titleHeight+'px');
			$title.stop(true,true).show().animate({"bottom":"0px"},settings.titleSlideUpTime);
		 }
	}
	//------------------------initImageShow-------------------------------
	if(imageNum>0){
		imgShow();
	}
	//自动播放
	if(settings.autoPlay){
		imgTimer = setInterval(imgShow,settings.delay);
	}
	//鼠标悬浮停止播放
	if(settings.autoPlay&&settings.mouseEvent){
		$images.hover(function(){
			clearInterval(imgTimer);
		},function(){
			imgTimer = setInterval(imgShow,settings.delay);
		});
	}
	$images.click(function(){
		var url = $(this).attr("url");
		if(url){
			window.open(url,"_blank");
		}
	});
	return settings;
	};

})(jQuery);