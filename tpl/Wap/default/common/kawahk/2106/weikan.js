note_id = 0;
pop_up_note_mode = true;
music_player = null;
bopen = false;

function objid(id)
{
	return document.getElementById(id);
}
function switchsound()
{
    au = music_player
    ai = objid('sound_image');
    bopen = true;
    if(au.paused)
    {
        au.play();
        ai.src = "http://tu.kagirl.net/pic/music_note_big.png";
        pop_up_note_mode = true;
        popup_note();
        objid("music_txt").innerHTML = "打开";
        objid("music_txt").style.visibility = "visible";
        setTimeout(function(){objid("music_txt").style.visibility="hidden"}, 2500);
    }
    else
    {
        pop_up_note_mode = false;
        au.pause();
        bopen = true;
        ai.src = "http://tu.kagirl.net/pic/music_note_big.png";
        objid("music_txt").innerHTML = "关闭";
        objid("music_txt").style.visibility = "visible";
        setTimeout(function(){objid("music_txt").style.visibility="hidden"}, 2500);
    }
}

function create_music()
{
    music = config.musicUrl;

    music_player = document.createElement('audio');
    music_player.src = music;
    music_player.loop = 'loop';
    music_player.autoplay="autoplay";
    music_player.play();

    sound_div = document.createElement("div");
    sound_div.setAttribute("ID", "cardsound");
    sound_div.style.cssText = "position:fixed;right:20px;top:50px;z-index:50000;visibility:visible;";
    sound_div.onclick = switchsound;
    bg_htm = "<img id='sound_image' width='55px' height='55px' src='http://tu.kagirl.net/pic/music_note_big.png'>";
    box_htm = "<div id='note_box' style='height:100px;width:44px;position:absolute;left:0px;top:-80px'></div>";
    txt_htm = "<div id='music_txt' style='color:white;position:absolute;left:-50px;top:30px;width:60px;font-size:18pt;'></div>"
    sound_div.innerHTML = bg_htm + box_htm + txt_htm;
    document.body.appendChild(sound_div);
    setTimeout("popup_note()", 100);
}   
function on_pop_note_end(event)
{
    note = event.target;
    
    if(note.parentNode == objid("note_box"))
    {
        objid("note_box").removeChild(note);
    }
}

function popup_note()
{
    box = objid("note_box");
    
    note = document.createElement("span");
    note.style.cssText = "visibility:visible;position:absolute;background-image:url('http://tu.kagirl.net/pic/music_note_small.png');width:21px;height:35px";
    note.style.left = Math.random() * 20 + 20;
    note.style.top = "75px";
    this_node = "music_note_" + note_id;
    note.setAttribute("ID", this_node);
    note_id += 1;
    scale = Math.random() * 0.4 + 0.4;
    note.style.webkitTransform = "rotate(" + Math.floor(360 * Math.random()) + "deg) scale(" + scale + "," + scale + ")";
    note.style.webkitTransition = "top 2s ease-in, opacity 2s ease-in, left 2s ease-in";
    note.addEventListener("webkitTransitionEnd", on_pop_note_end);
    box.appendChild(note);

    setTimeout("document.getElementById('" + this_node + "').style.left = '0px';", 100);
    setTimeout("document.getElementById('" + this_node + "').style.top = '0px';", 100);
    setTimeout("document.getElementById('" + this_node + "').style.opacity = '0';", 100);
    
    if(pop_up_note_mode)
    {
        setTimeout("popup_note()", 600);
    }   
}
//微信
function share_data()
{
    return{
        'img_url'    : shareData.imgUrl,
        'img_width'  : '640',
        'img_height' : '640',
        'link'       : shareData.shareUrl,
        'desc'       : shareData.desc,
        'title'      : shareData.title
    }
}

function share_data_timeline()
{
    return{
        'img_url'    : shareData.imgUrl,
        'img_width'  : '640',
        'img_height' : '640',
        'link'       : shareData.shareUrl,
        'desc'       : shareData.desc,
        'title'      : shareData.title
    }
}

function _v(keyname)
{
    if(typeof(config[keyname]) == 'undefined')
    {
        return '';
    }

    return config[keyname];
}

function on_weixin_reply(res)
{
    cardid = _v('cardid');

    if(cardid == '')
    {
        cardid = '0';
    }
    
    switch(res.err_msg)
    {
        case "share_timeline:confirm":
        case "share_timeline:ok":
            location.href = 'tongji.php?action=timeline&cardid='+cardid;
            break;
        case "send_app_msg:confirm":
        case "send_app_msg:ok":
            location.href = 'tongji.php?action=message&cardid='+cardid;
            break;
    }
}

function on_weixin_ready()
{
        WeixinJSBridge.on('menu:share:appmessage', function(argv){
            WeixinJSBridge.invoke('sendAppMessage', share_data(), on_weixin_reply);
        });

        WeixinJSBridge.on('menu:share:timeline', function(argv){
            WeixinJSBridge.invoke('shareTimeline', share_data_timeline(), on_weixin_reply);
        });

}

document.addEventListener('WeixinJSBridgeReady', on_weixin_ready, false);
//载入图片
var imgLoad = (function(){
	return function(url, callback, errorCb){
		var img = new Image();

		img.src = url;

		if (img.complete) {
            callback.call(img);
            return;
        };

        img.onload = function () {
        	callback.call(this);
            img = img.onload = img.onerror = null;
        };

        img.onerror = errorCb || function(){};
	}
})();

$(function(){
	ua = navigator.userAgent,
	curObj = $('#swipe li').eq(config.swipeCur),
	curSrc = getSrc(curObj.find('div')),
	imgLoad(curSrc, initSwipe, initSwipe);
	function getSrc(obj){
		return obj.css('background-image').replace(/^url\(|\)$/g, '');
	}
	function loadImg(){
		$('#swipe li').each(function(i){
			if(i == config.swipeCur) return;

			var src = getSrc($(this).find('div')),
				img = new Image(),
				_this = this;

			img.src = src;
		});
	}

	function initSwipe(){
		loadImg();
		var isInitSwipe = $('#swipe li').length > 1;
		if(isInitSwipe){
			$('#swipe').swipe({
				cur: config.swipeCur,
				dir: config.swipeDir,
				success: function(){
					$(this).find('li').eq(config.swipeCur).removeAttr('style');
					
					if(isInitSwipe) $('#arrow' + (config.swipeDir == 'vertical' ? 'V' : 'H' )).removeClass('f-hide').children().addClass('move');
				}
			});
		}else{
			$('#swipe li').eq(0).show();
			if(isInitSwipe) $('#arrow' + (config.swipeDir == 'vertical' ? 'V' : 'H' )).removeClass('f-hide').children().addClass('move');
		}
	}
});