
var gTextSub        = 0;
var gTextSuper      = 0;
var gTextContent    = 0;
var gPrintText      = '';
var gOrgCardText    = '';

if(typeof(gTextAreaLeft) == 'undefined')
{
    var gTextAreaLeft = 0.1;
}

if(typeof(gTextAreaTop) == 'undefined')
{
    var gTextAreaTop = 0.2;
}

if(typeof(gTextAreaWidth) == 'undefined')
{
    var gTextAreaWidth = 0.8;
}

if(typeof(gTextAreaHeight) == 'undefined')
{
    var gTextAreaHeight = 0.2;
}

if(typeof(gCardText) == 'undefined')
{
    var gCardText = '一张小小的卡片，捎去卡妞对您的无限祝福!';
}

if(typeof(gCardTextNoWrap) == 'undefined')
{
    var gCardTextNoWrap = '一张小小的卡片，捎去卡妞对您的无限祝福!';
}

if(typeof(gSizeMode) == 'undefined')
{
    var gSizeMode = 'img';  // img or bodywidth
}

if(typeof(gAnimateMode) == 'undefined')
{
    var gAnimateMode = 'left';
}

if(typeof(gSpeed) == 'undefined')
{
    var gSpeed = 350;
}

function playsound()
{
    if(typeof(gSound) != 'undefined')
    {
        var audio = document.getElementById('bgsound');
        audio.play();
    }
}

function onLoad()
{
    gTextSub     = document.getElementById("textsub");
    gTextSuper   = document.getElementById("textsuper");
    gTextContent = document.getElementById("textcontent");
    
    if(gSizeMode == 'img')
    {
        var cardimg = document.getElementById("cardimg");
        gTextSuper.style.left   = cardimg.offsetLeft + cardimg.offsetWidth  * gTextAreaLeft + 'px';
        gTextSuper.style.top    = cardimg.offsetTop  + cardimg.offsetHeight * gTextAreaTop + 'px';
        gTextSuper.style.height = cardimg.offsetHeight * gTextAreaHeight + 'px';
        gTextSuper.style.width  = cardimg.offsetWidth  * gTextAreaWidth + 'px';
    }
    else if(gSizeMode == 'bodywidth')
    {
        //var ruler   = document.getElementById("ruler");
        //var baseLen = ruler.offsetWidth;
		var baseLen = window.innerWidth;
        gTextSuper.style.left   = parseInt(baseLen * gTextAreaLeft) + 'px';
        gTextSuper.style.top    = parseInt(baseLen * gTextAreaTop) + 'px';
        gTextSuper.style.height = parseInt(baseLen * gTextAreaHeight) + 'px';
        gTextSuper.style.width  = parseInt(baseLen * gTextAreaWidth) + 'px';
		//alert(gTextSuper.style.left + ' ' + gTextSuper.style.top + ' ' + gTextSuper.style.width + ' ' + gTextSuper.style.height)
    }
    
    if(gAnimateMode == 'print')
    {
        onPrintLoad();
        setTimeout("onPrintAnimate()", 1500);
    }
    else if(gAnimateMode == 'up')
    {
        onUpLoad();
        setTimeout("onUpAnimate()", 10);
    }
    else if(gAnimateMode == 'left')
    {
        onLeftLoad();
        setTimeout("onLeftAnimate()", 10);
    }

    gTextContent.innerHTML = gCardText;
    
    // sound
    var audio = document.getElementById('bgsound');
    audio.src = gSound;
    audio.play();
    
    // font size
    if(typeof(gFontSize) != 'undefined')
    {
        gTextContent.style.fontSize = gFontSize;
    }
	
	// font color
    if(typeof(gColor) != 'undefined')
    {
        gTextContent.style.color = gColor;
    }
}

function onPrintLoad()
{
	gTextSub.style.top = gTextSuper.offsetHeight + 'px';
    gPrintText         = gCardText;
    gOrgCardText       = gCardText;
    gCardText          = '';
}
    
function onUpLoad()
{
    gTextSub.style.top = gTextSuper.offsetHeight + 'px';
}
    
function onLeftLoad()
{
    textwidth = 75 * gCardText.length;
    if(textwidth < 500) textwidth = 500;
    
    gTextSub.style.width = textwidth + 'px';
    gTextSub.style.left  = gTextSuper.offsetWidth + 'px';
}

first = 0

function onPrintAnimate()
{
    pushText = '';
    
	var reachEnd = 0;
	
	if(gPrintText.length == 1)
	{
		reachEnd = 1;
	}
	
    if(gPrintText.length >= 4 && gPrintText.substring(0, 4) == '<br>')
    {
        gPrintText = gPrintText.substring(4);
        pushText = '<br>';
    }
    else if(gPrintText.length >= 1)
    {
        pushText   = gPrintText.substring(0, 1)
        gPrintText = gPrintText.substring(1)
    }
                    
    textcontent.innerHTML = textcontent.innerHTML + pushText

	
    if(textsub.offsetTop + textsub.offsetHeight > textsuper.offsetHeight)
    {
        textsub.style.top = textsuper.offsetHeight - textsub.offsetHeight + 'px';
    }

	if(reachEnd == 1)
	{
		setTimeout("onPrintAnimate()", 5000);
	}
    else if(gPrintText.length == 0)
	{
        gTextSub.style.top     = gTextSuper.offsetHeight + 'px';
        gPrintText             = gOrgCardText;
        gTextContent.innerHTML = gCardText;
        
        setTimeout("onPrintAnimate()", 1500);
    }
    else
	{
		setTimeout("onPrintAnimate()", gSpeed);
    }
}

function onUpAnimate()
{
    textsub.style.top = textsub.offsetTop - 1 + 'px';
    
    if(textsub.offsetTop < - textsub.offsetHeight)
    {
        textsub.style.top = textsuper.offsetHeight + 'px';
    }
    
    setTimeout("onUpAnimate()", 15);
}

function onLeftAnimate()
{
    gTextSub.style.left = gTextSub.offsetLeft - 1 + 'px';
    
    if(gTextSub.offsetLeft < - textwidth)
    {
        gTextSub.style.left = gTextSuper.offsetWidth + 'px';
    }
    
    setTimeout("onLeftAnimate()", 10);
}

function weixinAddContact()
{
    if(typeof(WeixinJSBridge) != "undefined")
    {
        WeixinJSBridge.invoke(
            "addContact",
            {webtype: "1",username: "kagirl"},
            function(e){
                if(e.err_msg == "access_control:not_allow")
                {
                    document.location = "../kagirl/htm/t1.htm"
                }
                }
            )
    }
    else
    {
        document.location = "../kagirl/htm/t1.htm"
    }
}
