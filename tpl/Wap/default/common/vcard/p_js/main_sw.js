/**
 *  全局函数处理
 *  -----------------------------
 *  作者：叼怎么写！- -||
 *  时间：2014-03-26
 *  准则：Zpote、字面量对象
 *  联系：wechat--shoe11414255
 *  一张网页，要经历怎样的过程，才能抵达用户面前
 *  一个特效，要经历这样的修改，才能让用户点个赞
 *  一个产品，创意源于生活，源于内心，需要慢慢品味
 *********************************************************************************************/
var car2 = {
/****************************************************************************************************/
/*  对象私有变量/函数返回值/通用处理函数
*****************************************************************************************************/	
/*************************
 *  = 对象变量，判断函数
 *************************/
	_events 		: {},									// 自定义事件---this._execEvent('scrollStart');
	_windowHeight	: $(window).height(),					// 设备屏幕高度
	_windowWidth 	: $(window).width(),

	_rotateNode		: $('.p-ct'),							// 旋转体

	_page 			: $('.m-page'),							// 模版页面切换的页面集合
	_pageNum		: $('.m-page').size(),					// 模版页面的个数
	_pageNow		: 0,									// 页面当前的index数
	_pageNext		: null,									// 页面下一个的index数

	_touchStartValY	: 0,									// 触摸开始获取的第一个值
	_touchDeltaY	: 0,									// 滑动的距离

	_moveStart		: true,									// 触摸移动是否开始
	_movePosition	: null,									// 触摸移动的方向（上、下）
	_movePosition_c	: null,									// 触摸移动的方向的控制
	_mouseDown		: false,								// 判断鼠标是否按下
	_moveFirst		: true,
	_moveInit		: false,

	_firstChange	: false,

	_map 			: $('.ylmap'),							// 地图DOM对象
	_mapValue		: null,									// 地图打开时，存储最近打开的一个地图
	_mapIndex		: null,									// 开启地图的坐标位置

	_audioNode		: $('.u-audio'),						// 声音模块
	_audio			: null,									// 声音对象
	_audio_val		: true,									// 声音是否开启控制
	
	_elementStyle	: document.createElement('div').style,	// css属性保存对象

	_UC 			: RegExp("Android").test(navigator.userAgent)&&RegExp("UC").test(navigator.userAgent)? true : false,
	_weixin			: RegExp("MicroMessenger").test(navigator.userAgent)? true : false,
	_iPhoen			: RegExp("iPhone").test(navigator.userAgent)||RegExp("iPod").test(navigator.userAgent)||RegExp("iPad").test(navigator.userAgent)? true : false,
	_Android		: RegExp("Android").test(navigator.userAgent)? true : false,
	_IsPC			: function(){ 
						var userAgentInfo = navigator.userAgent; 
						var Agents = new Array("Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"); 
						var flag = true; 
						for (var v = 0; v < Agents.length; v++) { 
							if (userAgentInfo.indexOf(Agents[v]) > 0) { flag = false; break; } 
						} 
						return flag; 
					} ,
	_isload			: true ,//是否加载音乐
	_audio_src		:"", //音乐url

/***********************
 *  = gobal通用函数
 ***********************/
 	// 判断函数是否是null空值
	_isOwnEmpty		: function (obj) { 
						for(var name in obj) { 
							if(obj.hasOwnProperty(name)) { 
								return false; 
							} 
						} 
						return true; 
					},
	// 微信初始化函数
	_WXinit			: function(callback){
						if(typeof window.WeixinJSBridge == 'undefined' || typeof window.WeixinJSBridge.invoke == 'undefined'){
							setTimeout(function(){
								this.WXinit(callback);
							},200);
						}else{
							callback();
						}
					},
	// 判断浏览器内核类型
	_vendor			: function () {
						var vendors = ['t', 'webkitT', 'MozT', 'msT', 'OT'],
							transform,
							i = 0,
							l = vendors.length;
				
						for ( ; i < l; i++ ) {
							transform = vendors[i] + 'ransform';
							if ( transform in this._elementStyle ) return vendors[i].substr(0, vendors[i].length-1);
						}
						return false;
					},
	// 判断浏览器来适配css属性值
	_prefixStyle	: function (style) {
						if ( this._vendor() === false ) return false;
						if ( this._vendor() === '' ) return style;
						return this._vendor() + style.charAt(0).toUpperCase() + style.substr(1);
					},
	// 判断是否支持css transform-3d（需要测试下面属性支持）
	_hasPerspective	: function(){
						var ret = this._prefixStyle('perspective') in this._elementStyle;
						if ( ret && 'webkitPerspective' in this._elementStyle ) {
							this._injectStyles('@media (transform-3d),(-webkit-transform-3d){#modernizr{left:9px;position:absolute;height:3px;}}', function( node, rule ) {
								ret = node.offsetLeft === 9 && node.offsetHeight === 3;
							});
						}
						return !!ret;
					},
		_translateZ : function(){
						if(car2._hasPerspective){
							return ' translateZ(0)';
						}else{
							return '';
						}
					},

	// 判断属性支持是否
	_injectStyles 	: function( rule, callback, nodes, testnames ) {
						var style, ret, node, docOverflow,
							div = document.createElement('div'),
							body = document.body,
							fakeBody = body || document.createElement('body'),
							mod = 'modernizr';

						if ( parseInt(nodes, 10) ) {
							while ( nodes-- ) {
								node = document.createElement('div');
								node.id = testnames ? testnames[nodes] : mod + (nodes + 1);
								div.appendChild(node);
								}
						}

						style = ['&#173;','<style id="s', mod, '">', rule, '</style>'].join('');
						div.id = mod;
						(body ? div : fakeBody).innerHTML += style;
						fakeBody.appendChild(div);
						if ( !body ) {
							fakeBody.style.background = '';
							fakeBody.style.overflow = 'hidden';
							docOverflow = docElement.style.overflow;
							docElement.style.overflow = 'hidden';
							docElement.appendChild(fakeBody);
						}

						ret = callback(div, rule);
						if ( !body ) {
							fakeBody.parentNode.removeChild(fakeBody);
							docElement.style.overflow = docOverflow;
						} else {
							div.parentNode.removeChild(div);
						}

						return !!ret;
					},
	// 自定义事件操作
 	_handleEvent 	: function (type) {
						if ( !this._events[type] ) {
							return;
						}

						var i = 0,
							l = this._events[type].length;

						if ( !l ) {
							return;
						}

						for ( ; i < l; i++ ) {
							this._events[type][i].apply(this, [].slice.call(arguments, 1));	
						}
					},
	// 给自定义事件绑定函数
	_on				: function (type, fn) {
						if ( !this._events[type] ) {
							this._events[type] = [];
						}

						this._events[type].push(fn);
					},
	//禁止滚动条
	_scrollStop		: function(){
						//禁止滚动
						$(window).on('touchmove.scroll',this._scrollControl);
						$(window).on('scroll.scroll',this._scrollControl);
					},
	//启动滚动条
	_scrollStart 	: function(){		
						//开启屏幕禁止
						$(window).off('touchmove.scroll');
						$(window).off('scroll.scroll');
					},
	//滚动条控制事件
	_scrollControl	: function(e){e.preventDefault();},

/**
 *  对象函数事件绑定处理
 *  -->start touch开始事件
 *  -->mov   move移动事件
 *  -->end   end结束事件
 */
 	haddle_envent_fn : function(){
 		// 当前页面移动，延迟加载以后的图片
		car2._on('start',car2.lazy_bigP);

		// 当前页面移动
		car2._on('move',function(){
			
		});

		
		// 切换成功事件
		car2._on('success',function(){
			// 判断最后一页让，开启循环切换
			if (car2._pageNext == 0 && car2._pageNow == car2._pageNum -1) {
				car2._firstChange = true;
                //window.location.href="http://www.5.cn/magazine/822/1883/index.html";
			}

			
			setTimeout(function(){
				
				
				// 切换停止视频的播放
				$('video').each(function(){
					if(!this.paused) this.pause();
				})

				
                if(car2._pageNow == car2._pageNum-1){
                     car2.lightapp_intro_show();
                     car2.lightapp_intro();
                   // $(".market-notice").show();
                 }else{
                    car2.lightapp_intro_hide(false);
                    $(".market-notice").hide();
                 }
				/*if(car2._page.eq(car2._pageNow).next('.m-page').length == 0){
	 				car2.lightapp_intro_hide(false);
	 				$(".market-notice").hide();
	 			}*/
	 		},300)

			
		})
 	},


 
	//绑定地图出现函数
	mapCreate	: function(){
		
		
		if('.j-map'.length<=0) return;

		var node = $('.j-map');
		var mpid=node.attr('data-mapid');
		
		var detal=eval('('+node.attr('data-detal')+')');
		//alert(points)
		node.on('click',function(){
			//$('#'+mpid).;
			
			$('#'+mpid).addClass(' show')
			setTimeout(function(){
				$('#'+mpid).addClass(' mapOpen');
			},300)
			$('#vcmap').css('z-index',1000)
			//var p=node.attr('data-point');p=eval(p); 
			var lng=node.attr('data-longitude');
			var lat=node.attr('data-latitude');
			// 百度地图API功能
			//$('#'+mpid).html('<div id="bmap" style="height:100%;width:100%"></div>');
			setTimeout(function(){
				 var bmap = new BMap.Map('allmap');
					var point = new BMap.Point(lng,lat);
					
					bmap.centerAndZoom(point, 15);
					bmap.addControl(new BMap.ZoomControl());          
					
					var infoWindow = new BMap.InfoWindow(detal.contact_tel+'<br>'+detal.address);  // 创建信息窗口对象
					bmap.openInfoWindow(infoWindow,point); //开启信息窗口
				
					var marker1 = new BMap.Marker(new BMap.Point(lng,lat));  // 创建标注
					bmap.addOverlay(marker1);              // 将标注添加到地图中
					$('.daohang').on("click", function(){
						var start = {
							 name:""
						}
						var end = {
							name:detal.address
						}
						var opts = {
							mode:BMAP_MODE_DRIVING,
							
						}
						var ss = new BMap.RouteSearch();
						ss.routeCall(start,end,opts);
					});
			},1000)
	      
			
			
		})
		
		$('.closemap').on('click',function(){
			$('#'+mpid).removeClass(' mapOpen');
			setTimeout(function(){
				$('#'+mpid).removeClass(' show');
			},1000)
			//$('#'+mpid).css('top','110%');
		})
		
		

	},

/**
 *  media资源管理
 *  -->绑定声音控制事件
 *  -->函数处理声音的开启和关闭
 *  -->异步加载声音插件（延迟做）
 *  -->声音初始化
 *  -->视频初始化
 *  -->声音和视频切换的控制
 */
 	// 声音初始化
 	audio_init : function(){
 		// media资源的加载
		var options_audio = {
			loop: true,
			//autoplay:"autoplay",
            preload: "auto",
            src: car2._audioNode.attr('data-src')
		}
		
       car2._audio = new Audio(); 

        for(var key in options_audio){
            if(options_audio.hasOwnProperty(key) && (key in car2._audio)){
                car2._audio[key] = options_audio[key];
            }
        }
        car2._audio.load();
 	},

 	// 声音事件绑定
 	audio_addEvent : function(){
 		if(car2._audioNode.length<=0) return;

 		// 声音按钮点击事件
 		var txt = car2._audioNode.find('.txt_audio'),
 			time_txt = null;
 		car2._audioNode.find('.btn_audio').on('click',car2.audio_contorl);

 		// 声音打开事件
 		$(car2._audio).on('play',function(){
 			car2._audio_val = false;

 			audio_txt(txt,true,time_txt);

 			// 开启音符冒泡
 			$.fn.coffee.start();
 			$('.coffee-steam-box').show(500);
 		})

 		// 声音关闭事件
 		$(car2._audio).on('pause',function(){
 			audio_txt(txt,false,time_txt)

 			// 关闭音符冒泡
 			$.fn.coffee.stop();
 			$('.coffee-steam-box').hide(500);
 		})

 		function audio_txt(txt,val,time_txt){
 			if(val) txt.text('打开');
 			else txt.text('关闭');

 			if(time_txt) clearTimeout(time_txt);

 			txt.removeClass('z-move z-hide');
 			time_txt = setTimeout(function(){
 				txt.addClass('z-move').addClass('z-hide');
 			},1000)
 		}
 	},

 	// 声音控制函数
 	audio_contorl : function(){
 		
 		if(!car2._audio_val){
 			car2.audio_stop();
 		}else{
 			car2.audio_play();
 		}
 	},	

 	// 声音播放
 	audio_play : function(){
 		car2._audio_val = false;
 		if(car2._audio) car2._audio.play();
 	},

 	// 声音停止
 	audio_stop	: function(){
 		car2._audio_val = true;
 		if(car2._audio) car2._audio.pause(); 
 	},

 	// 视频初始化
 	video_init : function(){
 		// 视频
        $('.j-video').each(function(){
        	var option_video = {
        		controls: 'controls',
        		preload : 'none',
        		// poster : $(this).attr('data-poster'),
        		width : $(this).attr('data-width'),
        		height : $(this).attr('data-height'),
        		src : $(this).attr('data-src')
        	}

        	var video = $('<video class="f-hide"></video>')[0];

        	for(var key in option_video){
                if(option_video.hasOwnProperty(key) && (key in video)){
                    video[key] = option_video[key];
                }
                this.appendChild(video);
            }

            var img = $(video).prev();

            $(video).on('play',function(){
            	img.hide();
            	$(video).removeClass('f-hide');
            });

            $(video).on('pause',function(){
            	img.show();
            	$(video).addClass('f-hide');
            });
        })

        $('.j-video .img').on('click',function(){
        	var video = $(this).next()[0];

        	if(video.paused){
        		$(video).removeClass('f-hide');
        		video.play();
        		$(this).hide();
        	}
        })
 	},

 	//处理声音和动画的切换
	media_control : function(){
		if(!car2._audio) return;
		if($('video').length<=0) return;

		$(car2._audio).on('play', function(){
			$('video').each(function(){
				if(!this.paused){
					this.pause();
				}
			});	
		});

		$('video').on('play', function(){
			if(!car2._audio_val){
				car2.audio_contorl();			
			}
		});
	},

	// media管理初始化
	media_init : function(){
		// 声音初始化
		car2.audio_init();

        // 视频初始化
       // car2.video_init();

		// 绑定音乐加载事件
		car2.audio_addEvent();

		// 音频切换
		car2.media_control();
	},

/**
 *  图片延迟加载功能
 *  -->替代需要延迟加载的图片
 *  -->优化加载替代图片
 *  -->切换功能触发图片的延迟加载
 *  -->替代图片为400*400的透明大图片
 */
	/* 图片延迟加载 */
	lazy_img : function(){
		var lazyNode = $('.lazy-img');
		lazyNode.each(function(){
			var self = $(this);
			if(self.is('img')){
				self.attr('src','http://img0.hx.com/magazine/img/load.gif');
			}else{
				// 把原来的图片预先保存下来
				var position = self.css('background-position'),
					size = self.css('background-size');

				self.attr({
					'data-position' : position,
					'data-size'	: size
				});

				//if(self.attr('data-bg')=='no'){
					self.css({
						'background-repeat'	: 'no-repeat'
					})
				//}

				self.css({
					'background-image'	: 'url(http://img0.hx.com/magazine/img/load.gif)',
					'background-size'	: '120px 120px',
					'background-position': 'center'
				})

				if(self.attr('data-image')=='no'){
					self.css({
						'background-image'	: 'none'
					})
				}
			}
		})
	},

	// 开始加载前三个页面
	lazy_start : function(){
		// 前三个页面的图片延迟加载
		setTimeout(function(){
			
				var node = $(".lazy-img");
				if(node.length==0) return;
			    for(var i=0;i<node.length;i++){
				  car2.lazy_change(node,false);
			    }
		},200)
	},
	
	 

	// 图片延迟替换函数
	lazy_change : function(node,goon){
		
		


		// 其他图片的延迟加载
		var lazy = $('.lazy-img');
		lazy.each(function(){
			var self = $(this),
				srcImg = self.attr('data-src'),
				position = self.attr('data-position'),
				size = self.attr('data-size');

			if(self.attr('data-bg')!='no'){
				$('<img />')
					.on('load',function(){
						if(self.is('img')){
							self.attr('src',srcImg)
						}else{
							self.css({
								'background-image'	: 'url('+srcImg+')',
								'background-position'	: position,
								'background-size' : 'cover'
							})
						}

						// 判断下面页面进行加载
						if(goon){
							for(var i =0;i<$(".m-page").size();i++){
								var page = $(".m-page").eq(i);
								if($(".m-page").find('.lazy-img').length==0) continue
								else{
									car2.lazy_change(page,true);
								}
							}
						}
					})
					.attr("src",srcImg);

				self.removeClass('lazy-img').addClass('lazy-finish');
			}else{
				if(self.attr('data-auto')=='yes') self.css('background','none');
			}
		})	
	},

 


/**************************************************************************************************************/
/*  单个处理函数
***************************************************************************************************************/
/**
 * 单个函数处理-unit
 * -->高度的计算
 * -->文本的展开
 * -->文本的收起
 * -->输入表单的操作
 * -->微信的分享提示
 */
	// 根据设备的高度，来适配每一个模版的高度，并且静止滑动
	// --文档初始化计算
	// --页面切换完成计算
	height_auto	: function(ele,val){
		ele.children('.page-con').css('height','auto');
		var height = $(window).height();

		// 需要解除固定高度的page卡片
		var vial = true;
		if(!vial){
			if(ele.height()<=height){
				ele.children('.page-con').height(height);
				if((!$('.p-ct').hasClass('fixed'))&&val=='true') $('.p-ct').addClass('fixed');
			}else{
				car2._scrollStart();
				if(val=='true') $('.p-ct').removeClass('fixed');
				ele.children('.page-con').css('height','100%');
				return;
			}
		}else{
			ele.children('.page-con').height(height);
			if((!$('.p-ct').hasClass('fixed'))&&val=='true') $('.p-ct').addClass('fixed');
		}
	},

	
	
	
 	
 	// loading显示
	loadingPageShow : function(){
		$('.u-pageLoading').show();
	},
	
	// loading隐藏
	loadingPageHide : function (){
		$('.u-pageLoading').hide();	
	},

	// 对象私有变量刷新
	refresh	: function(){
		$(window).height() = $(window).height();
		car2._windowWidth = $(window).width();
	},

/**************************************************************************************************************/
/*  函数初始化
***************************************************************************************************************/
/**
 *  相关插件的启动
 */
	//插件启动函数
 	plugin : function(){
		// 地图
		car2.mapCreate();

		// 音符飘逸
		$('#coffee_flow').coffee({
			steams				: ["<img src='http://img0.hx.com/magazine/img/audio_widget_01@2x.png' />","<img src='http://img0.hx.com/magazine/img/audio_widget_01@2x.png' />"], 
			steamHeight			: 100,
			steamWidth			: 44 
		});

		
		car2.set_finger();


		// 微信分享
		

		
 	},
    //显示二维码
	
	show_qrcode:function(){
		
		if($(".show_qrcode").length==0)return;
		
		$(".show_qrcode").append();
		
		
		},
 	
 	
 	// 指纹验证
 	set_finger:function(){
 		
 		car2._scrollStart();
 		// 开启页面切换
		//car2.page_start();
		
		if(!car2._audio) {return;}
		car2._audioNode.removeClass('f-hide');
		
 		if($('#finger').length<=0) {car2._audio.play();return;}
   		var _fingerNode=$('#finger');   		
 		$("#finger_div").on('touchstart mousedown',function(){
        $("#finger_line").show();
	     $("#finger_info").html("扫描中...");
	    $("#finger_line").animate({top:"50%",marginTop:"-87px"},800,function(){
		$("#finger_div").unbind();
		$("#finger_info").hide().html("扫描成功").show();
		 
			$("#finger_line").hide();
			$("#finger_zw").hide();
			$("#finger_div").hide();
			$("#finger_info").hide();
			setTimeout(function(){
				    $("#finger_background").hide()
				    _fingerNode.empty().removeClass('z-current');
				    car2._audio.play();
			    },500)
       
	 })
    })
 		
 		
 	},
 	

/**
 * app初始化
 */
	// 样式适配
	styleInit : function(){
		// 禁止文版被拖动
		//document.body.style.userSelect = 'none';
		//document.body.style.mozUserSelect = 'none';
		//document.body.style.webkitUserSelect = 'none';

		

		// 图片延迟加载的处理
		this.lazy_img();

		

		$('.u-arrow').on('touchmove',function(e){e.preventDefault()})

		//$('.p-ct').height($(window).height());
		$('.m-page').height($(window).height());
		$('.translate-back').height($(window).height());
	},

	// 对象初始化
	init : function(){
		// 样式，标签的渲染
		// 对象操作事件处理
		this.styleInit();
		
		
		
		
		// 图片预先加载
		//$('<img />').attr('src',$('#r-cover').val());
		//$('<img />').attr('src',$('.m-fengye').find('.page-con').attr('data-src'));
        
		// loading执行一次
		var loading_time = new Date().getTime();
		
		$(window).on('load',function(){
			var now = new Date().getTime();
			var loading_end = false;
			var time;
			var time_del = now - loading_time;

			if ( time_del >= 500 ) {
				loading_end = true;
			}

			if ( loading_end ) {
				time = 0;
			} else {
				time = 500 - time_del;
			}

			// loading完成后请求
			setTimeout(function(){


				// 显示封面内容
				setTimeout(function(){
					$('.translate-back').removeClass('f-hide');
					$('.m-fengye').removeClass('f-hide');
					car2.height_auto(car2._page.eq(car2._pageNow),'false');
				},1000)

				car2.lazy_start();
				// media初始化
				car2.media_init();

				
				
				// 插件加载
		        car2.plugin();
				
				

			 	$('.container').height($(window).height());
				$('.m-page').height($(window).height());
				$('.translate-back').height($(window).height());
			},time)
		})
	}
};

/*初始化对象函数*/
car2.init();