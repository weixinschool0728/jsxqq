
function createTBt()
{
    closeTBt();                  //保证只存在一个弹出窗口
    var div = document.createElement('div');
    div.style.position = 'fixed';
    div.style.left = '0px';
    div.style.top = '100px';
    div.style.zIndex = '50002';
    div.style.backgroundColor = 'black';
    div.style.width = '80%';
    div.style.left = '10%'
    div.style.opacity = 0.9;
    div.style.height = '470px';                 //高度不能固定 。。
    div.id = 'wind';
    document.body.appendChild(div);

    
}
function closeTBt()
{
    var TBwind = document.getElementById("wind");
    if(TBwind != null)
    {
        document.body.removeChild(TBwind);
    }
}

function zk_build_card()
{
    url = 'show.php?facemodify=1&cardid=' + kawa_data.cardid;
    url = url + '&words=' + encodeURIComponent(id('words').value);
    if(id('words').value == '')
    {
        alert('您还没有输入祝福语呢');
        return;
    }
    location.href = url;
}
function wordchange()
{
    zkid('words').value = id('wordselect').value;
}
function zkid(idname)
{
    return document.getElementById(idname);
}

function go_to_card(cardid)
{
    location.href = 'show.php?facemodify=1&cardid=' + cardid;
}

function on_modifyface_click()
{

    createTBt();                  //创建容器
/*
    div = document.createElement('div');
    //div.style.position = 'fixed';
    div.style.top = '100px';
    div.style.height = '470px';
    div.style.textAlign = 'left';
    div.style.zIndex = '90004';
    div.style.width = '100%';
    //div.style.left = '10%';
    zkid('wind').appendChild(div);
*/
    var body_html = '<div style="margin:17px"><img width="90px" src="http://tu.kagirl.net/face/tu/10004_2.jpeg" style="padding:15px" onclick="go_to_card(10004)">'
    body_html += '<img width="90" src="http://tu.kagirl.net/face/tu/10005.jpeg" style="padding:15px" onclick="go_to_card(10005)">'
    body_html += '<img width="90" src="http://tu.kagirl.net/face/tu/10006.jpeg" style="padding:15px" onclick="go_to_card(10006)"></div>';
    body_html += '<div style="margin:17px"><img width="90" src="http://tu.kagirl.net/face/tu/10007.jpeg" style="padding:15px" onclick="go_to_card(10007)">'
    body_html += '<img width="90" src="http://tu.kagirl.net/face/tu/10008.jpeg" style="padding:15px" onclick="go_to_card(10008)"</div>';
    body_html += '<div style="text-align:center;position:absolute;width:375px;padding-top:15px"><img width="150px" src="http://tu.kagirl.net/face/tu/button4.png" onclick="closeTBt()"></div>';
    zkid('wind').innerHTML=body_html;

}
function on_modifyword2_click()
{

    createTBt();                  //创建容器

    html_body  = '<div style="margin:15px"><select id="wordselect" onchange="wordchange()" style="width:100%;font-size:20pt">' + '<option>点这里选择祝福语</option>' + optwords + '</select>'
    html_body += '<br><br><textarea id="words" rows=8 style="width:100%;font-size:20pt"></textarea>';
    html_body += '<div style="padding-left:11px;padding-top:10px"><img onclick="zk_build_card()" src="http://tu.kagirl.net/face/tu/button3.png"><img src="http://tu.kagirl.net/face/tu/button4.png" onclick="closeTBt()"></div>'
    html_body += '</div>'
    zkid('wind').innerHTML = html_body;
}
function create_facemodify()
{
    div = document.createElement('div');
    div.style.position = 'fixed';
    div.style.left = '0px';
    div.style.top = '0px';
    div.style.height = '80px';
    div.style.textAlign = 'center';
    div.style.zIndex = '10000';
    div.style.backgroundColor = 'black';
    div.style.opacity = 0.6;
    div.style.width = '100%';
    //祝福语
    img1 = document.createElement('img');
    //img1.src = 'http://tu.kagirl.net/pic/dingzhi.png';
    img1.src = 'http://tu.kagirl.net/face/tu/modifyface.png';
    img1.style.width = '30%';
    img1.style.height = '70px';
    img1.style.top = '5px';
    img1.style.position = 'absolute';
    img1.style.left = '50px';
    img1.style.zIndex = '10001';
    img1.onclick = on_modifyface_click;          //修改文字传参数0
    //音乐
    img2 = document.createElement('img');
    img2.src = 'http://tu.kagirl.net/face/tu/modifyword.png';
    img2.style.width = '30%';
    img2.style.height = '70px';
    img2.style.top = '5px';
    img2.style.position = 'absolute';
    img2.style.right = '70px';
    img2.style.zIndex = '10001';
    img2.onclick = on_modifyword2_click;          //修改音乐传参数1
    document.body.appendChild(div);
    document.body.appendChild(img1);
    document.body.appendChild(img2);
}