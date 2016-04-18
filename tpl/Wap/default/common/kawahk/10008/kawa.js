note_id = 0;
win_height = 0;
music_player = null;
pop_up_note_mode = true;

text_prepared = false;
font_img = null;

// pure_card_text

// ---------------------------------------------------------------------
// sdk

function add_keyframes(name, cssbody)
{
    csstext = '@-webkit-keyframes ' + name + '{' + cssbody + '}';

    style = document.createElement('style');
    document.head.appendChild(style);
    sheet = style.sheet;
    sheet.insertRule(csstext, 0);
}

function create_imgdiv(url, idname, visible, x, y)
{
    imgdiv = document.createElement('div');
}

function objid(idname)
{
    return document.getElementById(idname);
}

function _kv(value)
{
    if(typeof(value) == 'undefined')
    {
        return false;
    }

    if(value == '')
    {
        return false;
    }

    if(value.charAt(0) == '#')
    {
        return false;
    }

    return true;
}

function _v(keyname)
{
    if(typeof(kawa_data[keyname]) == 'undefined')
    {
        return '';
    }

    return kawa_data[keyname];
}

// ---------------------------------------------------------------------
// text

function kawa_init_async()
{
    read_base();
    create_textdiv();
    add_kawa_icon();
    create_music();
    create_modify();

}

function kawa_init()
{
    document.body.style.margin = '0px';
    create_base();
    setTimeout("kawa_init_async()", 100);
 }

function is_show_words()
{
    if(typeof(kawa_data.show_words) == 'undefined')
    {
        return true;
    }

    if(kawa_data.show_words != 'no')
    {
        return true;
    }

    return false;
}

function read_base()
{
    win_height = objid('basepoint').offsetTop;
}

function create_base()
{
    div = document.createElement('div');
    div.style.position = 'fixed';
    div.style.bottom = '0px';
    div.style.width = '1px';
    div.style.height = '1px';
    div.style.left = '-100px';
    div.id = 'basepoint';
    document.body.appendChild(div);
}

function make_text_animation()
{
    //if(!is_show_words())
    //    return;

    var mask = objid('textmask');
    var textdiv = objid('textdiv');

    if(kawa_data.mode == 'up')
    {
        var keycss = 'from{-webkit-transform:translate(0px, ' + mask.offsetHeight + 'px);}' +
                 'to{-webkit-transform:translate(0px, -' + textdiv.offsetHeight + 'px);}' 

        add_keyframes('textdivani', keycss);

        var dt = (mask.offsetHeight + textdiv.offsetHeight) / kawa_data.speed;

        textdiv.style.webkitAnimation = 'textdivani ' + dt + 's linear infinite';
    }
    else if(kawa_data.mode == 'left')
    {
        var keycss = 'from{-webkit-transform:translate(' + mask.offsetWidth + 'px, 0px);}' +
                 'to{-webkit-transform:translate(-' + textdiv.offsetWidth + 'px, 0px);}' 

        add_keyframes('textdivani', keycss);

        var dt = (mask.offsetWidth + textdiv.offsetWidth) / kawa_data.speed;

        textdiv.style.webkitAnimation = 'textdivani ' + dt + 's linear infinite';
    }
    else if (kawa_data.mode == 'print')
    {
        onPrint();
        setTimeout("onPrintAni()", 1500);
    }
}
function onPrint()
{
    objid('textdiv').style.top = objid('textmask').offsetHeight;
    gPrText          = card_text();
    gOrgCardText       = card_text();

}

function onPrintAni()
{
    pushText = '';
    
    var reachEnd = 0;
    
    if(gPrText.length <1)
    {
        reachEnd = 1;
    }
    
    var cutlen = 0;

    if(gPrText.length >= 4 && gPrText.substring(0, 4) == '<br>')
    {
        gPrText  = gPrText .substring(4);
        pushText = '<br>';
        cutlen = 4;
    }
    else if(gPrText.substring(0, 2) == '/:')
    {
        result = ConvFaceOnBegin(gPrText );
        cutlen = result[1];
        if(cutlen > 0)
        {
            gPrText  = gPrText .substring(cutlen);
            pushText = result[0];
        }
    }
    
    if(cutlen == 0 && gPrText.length >= 1)
    {
        pushText   = gPrText.substring(0, 1);
        gPrText  = gPrText.substring(1);
    }

    objid('textdiv').innerHTML = objid('textdiv').innerHTML + pushText;
    //alert(objid('textmask').offsetHeight);
    if((objid('textdiv').offsetTop + objid('textdiv').offsetHeight)> objid('textmask').offsetHeight)
    {
        trans = objid('textmask').offsetHeight - objid('textdiv').offsetHeight;
        objid('textdiv').style.top = trans+ 'px';
        //alert(objid('textdiv').style.top);
    }

    if(reachEnd == 1)
    {
        //setTimeout("", 2000); 
        
        setTimeout("pauseShow()",2000);
        
    }
    else
    {
        var gSpeed = kawa_data.speed;
        setTimeout("onPrintAni()", gSpeed);
    }
}
function pauseShow()
{
    reachEnd=0;
    trans = 0;
    objid('textdiv').style.top =trans+'px';
    gPrText              = gOrgCardText;
    objid('textdiv').innerHTML = "";
    setTimeout("onPrintAni()",1000);
}
function show_textdiv()
{
        var box = kawa_data.text_box.split(' ');

        var mask = document.createElement('div');
        mask.id = 'textmask';
        mask.style.position = 'absolute';
        mask.style.left     = box[0] + 'px';
        mask.style.top      = box[1] + 'px';
        mask.style.width    = box[2] + 'px';
        mask.style.height   = box[3] + 'px';
        mask.style.overflow = 'hidden';

        var textdiv = document.createElement('div');
        textdiv.id = 'textdiv';
        textdiv.style.position = 'absolute';
        textdiv.style.color = kawa_data.text_color;
        textdiv.style.fontSize  = kawa_data.font_size;
        textdiv.style.lineHeight = kawa_data.line_height;
		textdiv.style.fontWeight = '600';		
		textdiv.style.fontFamily = '微软雅黑';
        textdiv.style.zIndex = 50000;

        if(_kv(kawa_data.text_align))
        {
            textdiv.style.textAlign = kawa_data.text_align;
        }

        if(_kv(kawa_data.font_weight))
        {
            textdiv.style.fontWeight = kawa_data.font_weight;
        }

        if(kawa_data.mode == 'left')
        {
            textdiv.style.float = 'left';
        }

        document.body.appendChild(mask);    
        mask.appendChild(textdiv);

        set_up_words();
}

function create_textdiv()
{
    if(is_show_words())
    {
        show_textdiv();
    }
}

function set_up_words()
{
    if(_kv(kawa_data.font_family))
    {
        var text = pure_card_text();

        if(kawa_data.mode == 'up')
        {
            text = wrap_text(text);
        }

        var font_ip = 'aliyun7.kagirl.cn:8000';

        if(_kv(kawa_data.font_ip))
        {
            font_ip = kawa_data.font_ip;
        }

        var re_d = /^\d+/;
        var font_size = parseFloat(re_d.exec(kawa_data.font_size)[0]);
        var line_height = parseFloat(re_d.exec(kawa_data.line_height)[0]);
        var gap = line_height - font_size;

        var box = kawa_data.text_box.split(' ');

        var color = kawa_data.text_color.substring(1);

        var url = "http://" + font_ip + "/fontimg?words=" + encodeURIComponent(text) + "&fontname=" + 
            kawa_data.font_family + "&fontsize=" + font_size + "&gap=" + gap + "&width=" + box[2] + 
            "&color=" + color;

        font_img = document.createElement('Img');
        font_img.onload = on_font_img_load;
        font_img.src = url;
        setTimeout('on_check_font_img()', 1000);
    }
    else
    {
        textdiv = objid('textdiv');
        if (kawa_data.mode=='print')
            textdiv.innerHTML = '';
        else
            textdiv.innerHTML = card_text();
        make_text_animation();
    }
}

function on_font_img_load()
{
    if(!text_prepared)
    {
        text_prepared = true;
        var textdiv = objid('textdiv');
        textdiv.appendChild(font_img);
        make_text_animation();
    }
}

function on_check_font_img()
{
    if(!text_prepared)
    {
        var textdiv = objid('textdiv');
        text_prepared = true;
        textdiv.innerHTML = card_text();
        make_text_animation();
    }
}

function pure_card_text()
{
    text = kawa_data.words;

    if(kawa_data.replace_words != '#replace_words#')
    {
        text = kawa_data.replace_words;
    }

    return text;
}

function card_text()
{
    text = pure_card_text();

    if((kawa_data.mode == 'up')||(kawa_data.mode == 'print'))
    {
        text = wrap_text(text);
    }
    else if(kawa_data.mode == 'left')
    {
        text = '<nobr>' + text + '</nobr>';
    }

    return text;
}

function wrap_text(in_text)
{
    text = in_text.replace(/,/g, ',<br>');
    text = text.replace(/，/g, '，<br>');
    text = text.replace(/\./g, '.<br>');
    text = text.replace(/。/g, '。<br>');
    text = text.replace(/;/g, ';<br>');
    text = text.replace(/；/g, '；<br>');
    text = text.replace(/!/g, '!<br>');
    text = text.replace(/！/g, '！<br>');
    text = text.replace(/～/g, '～<br>');
    text = text.replace(/：/g, '：<br>');
    text = text.replace(/:/g, ':<br>');
    return text;
}

// ---------------------------------------------------------------------
// kawa icon

function add_kawa_icon()
{
    url = 'http://tu.kagirl.net/pic/kawa1.gif';

    div = document.createElement('div');
    img = document.createElement('img');
    img.src = url;
    div.appendChild(img);

    div.style.position = 'fixed';
    div.style.top = (win_height - 150) + 'px';
    //div.style.top = (700 - 20) + 'px';
    div.style.right = '10px';
    div.style.zIndex = '30000';
    //div.style.left = '10px';
    //alert('kawa');
    div.onclick = goto_kawa;

    document.body.appendChild(div);
}

function goto_kawa()
{
    location.href = 'http://mp.weixin.qq.com/s?__biz=MjM5MDg0OTE0Mw==&mid=209500314&idx=1&sn=c4b319fb642639a02821411fb859435c#wechat_redirect';
}

// ---------------------------------------------------------------------
// kawa music


function switchsound()
{
    au = music_player
    ai = objid('sound_image');
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
        ai.src = "http://tu.kagirl.net/pic/music_note_big.png";
        objid("music_txt").innerHTML = "关闭";
        objid("music_txt").style.visibility = "visible";
        setTimeout(function(){objid("music_txt").style.visibility="hidden"}, 2500);
    }
}

function create_music()
{
    music = kawa_data.music;

    if(kawa_data.replace_music != '#replace_music#')
    {
        music = kawa_data.replace_music;
    }

    music_player = document.createElement('audio');
    music_player.src = music;
    music_player.loop = 'loop';
    music_player.play();

    sound_div = document.createElement("div");
    sound_div.setAttribute("ID", "cardsound");
    sound_div.style.cssText = "position:fixed;right:20px;top:40px;z-index:50000;visibility:visible;";
    sound_div.onclick = switchsound;
    bg_htm = "<img id='sound_image' src='http://tu.kagirl.net/pic/music_note_big.png'>";
    box_htm = "<div id='note_box' style='height:100px;width:44px;position:absolute;left:0px;top:-80px'></div>";
    txt_htm = "<div id='music_txt' style='color:white;position:absolute;left:-40px;top:30px;width:60px'></div>"
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
    note.style.cssText = "visibility:visible;position:absolute;background-image:url('http://tu.kagirl.net/pic/music_note_small.png');width:15px;height:25px";
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

// ---------------------------------------------------------------------
// weixin

function get_host()
{
    if(location.href.indexOf('jielanhua') != -1)
    {
        return 'hg.jielanhua.cn';
    }

    // n = Math.floor(Math.random() * 30 + 1);
    // return 'cd' + n + '.nightsun-led.cn';

    n = Math.floor(Math.random() * 50 + 1);
    return 'cd' + n + '.naturalgift.cc';
}

function share_url()
{
    if(_v('use_share_url') == 'yes' && _kv(kawa_data.share_url))
    {
        //if(kawa_data.modify == 'yes' || kawa_data.replace_modify == 'yes')
        {
            return kawa_data.share_url;
        }
    }

    /*
    if(_v('short_url') != '')
    {
        if(kawa_data.replace_words == '#replace_words#')
        {
            return _v('short_url'); 
        }

        if(kawa_data.replace_words == kawa_data.words)
        {
            return _v('short_url'); 
        }
    }
    */
    
    url = 'http://' + get_host() + '/kawa/show.php'
    url = url + '?cardid=' + kawa_data.cardid;

    encoded_words = encodeURIComponent(pure_card_text());
    url = url + '&words=' + encoded_words;

    if(kawa_data.replace_music_name != '#replace_music_name#')
    {
        url = url + '&music=' + kawa_data.replace_music_name;
    }

    return url;
}

function share_data()
{
    var desc = '';

    if(_v('user_desc') != '')
    {
        desc = _v('user_desc');
    }
    else
    {
        desc = kawa_data.desc;

        if(kawa_data.replace_words != '#replace_words#')
        {
            desc = kawa_data.replace_words;
        }        
    }

    return{
        'img_url'    : kawa_data.icon,
        'img_width'  : '640',
        'img_height' : '640',
        'link'       : share_url(),
        'desc'       : desc,
        'title'      : kawa_data.title
    }
}

function share_data_timeline()
{
    var desc = '';

    if(_v('user_desc') != '')
    {
        desc = _v('user_desc');
    }
    else
    {
        desc = kawa_data.desc;

        if(kawa_data.replace_words != '#replace_words#')
        {
            desc = kawa_data.replace_words;
        }        
    }

    return{
        'img_url'    : kawa_data.icon,
        'img_width'  : '640',
        'img_height' : '640',
        'link'       : share_url(),
        'desc'       : desc,
        'title'      : desc
    }
}

function on_weixin_reply(res)
{
    switch(res.err_msg)
    {
        case "share_timeline:confirm":
        case "share_timeline:ok":
        case "send_app_msg:confirm":
        case "send_app_msg:ok":
            location.href="http://mp.weixin.qq.com/s?__biz=MjM5MDg0OTE0Mw==&mid=209500314&idx=1&sn=c4b319fb642639a02821411fb859435c#wechat_redirect"
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

// ---------------------------------------------------------------------
// modify

function on_modify_click()
{
    url = 'write.php?cardid=' + kawa_data.cardid;
    url = url + '&optfile=' + kawa_data.modify_optfile;

    if(kawa_data.modify_optwords != '#modify_optwords#')
    {
        url = url + '&optwords=' + encodeURIComponent(kawa_data.modify_optwords);
    }

    if(typeof(kawa_data.write_param) != 'undefined' && kawa_data.write_param != '')
    {
        url = url + '&' + kawa_data.write_param;
    }

    location.href = url;
}

function create_modify()
{
    if(kawa_data.modify == 'yes' || kawa_data.replace_modify == 'yes')
    {
        div = document.createElement('div');
        img = document.createElement('img');
        img.src = 'http://tu.kagirl.net/pic/dingzhi.png';
        div.style.position = 'fixed';
        div.style.left = '0px';
        div.style.top = '0px';
        div.style.height = '80px';
        div.style.textAlign = 'center';
        div.style.zIndex = '10000';
        div.style.backgroundColor = 'black';
        div.style.opacity = 0.6;
        div.style.width = '100%';
        img.style.width = '60%';
        img.style.top = '-5px';
        img.style.position = 'absolute';
        img.style.left = '95px';
        img.style.zIndex = '10001';
        img.onclick = on_modify_click;
        document.body.appendChild(div);
        document.body.appendChild(img);
    }
}



