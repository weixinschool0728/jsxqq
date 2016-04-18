<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" name="viewport">
<meta content="yes" name="apple-mobile-web-app-capable">
<meta content="black" name="apple-mobile-web-app-status-bar-style">
<meta content="telephone=no" name="format-detection">
<meta content="email=no" name="format-detection">
<link onerror="(this)" rel="stylesheet" type="text/css" href="style/css/index256230.css" />
<script>
var header_bg = new Image();
header_bg.src = 'style/images/airport_title_bg256230.jpg';
header_bg.onload = header_bg_loaded;
header_bg.onerror = header_bg_loaded;
setTimeout(header_bg_loaded,4000);
function header_bg_loaded(){
document.body.style.display = 'block';
}
</script>
<title>配置微信支付</title>
<style type="text/css">
<!--
.STYLE1 {
	color: #CC3300
}
-->
.sd {
	color: #00c800;
	margin: 10px 0;
	display: block;
}
</style>
</head>
<body>
<div class="wrap offline_proj">
  <div class="header airport" style="display:none"><img src="style/css/img/commonweal_title_bg256230.jpg" width="100%" alt="非认证服务号如何设置分享" class="title-bg">
    <h1 style="text-align:center">非认证服务号<br>
      如何设置分享</h1>
  </div>
  <div> &nbsp;&nbsp;<a href="#pz" class="commonweal-bold commonweal-step" style="color:#00c800;font-weight:normal;font-size:14px;">配置微信支付</a>&nbsp;&nbsp; <a href="#qa" class="commonweal-bold commonweal-step" style="color:#00c800;font-weight:normal;font-size:14px;">常见问题</a>&nbsp;&nbsp; <a href="http://action.weixin.qq.com/payact/readtemplate?t=mobile/merchant/guide-publicaccount_tmpl" target="_blank" class="commonweal-bold commonweal-step" style="color:#00c800;font-weight:normal;font-size:14px;">申请微信支付</a> </div>
  <a name="pz"></a>
  <h3 class="detail-title">配置微信支付</h3>
  <div class="intro-cnt">
    <p class="intro mglr">在设置之前，请确保申请过微信支付，并收到了微信支付发给您的申请成功邮件。<span style="color:#f30">只有三步，请耐心详细的看完，感谢配合。</span></p>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>1</em>三步之第一步：设置支付信息</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>在网站<a href="http://www.domain.com" target="_blank">http://www.domain.com</a>中(登录账号：您的账号)，点击【在线支付设置】==> 支付开关先开启==>然后点击微信支付的【配置信息】如图
          
          点击【新版微信支付】</p>
        <p style="text-align:center"><img src="images/weixin-pay-138wo-20150309183405.png" width="90%" /></p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>2</em>三步之第二步：获取商户号和API密钥</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p> 微信支付商户号：在申请微信支付后，收到的邮件里找<br>
          API密钥：获取方法如下<br>
          <a href="http://pay.weixin.qq.com" target="_blank">进入微信支付商户平台 http://pay.weixin.qq.com</a>，左侧菜单中【账户设置】【api安全】中先安装证书，然后再设置API密钥，如下图，设置后复制到上一步中保存即可 </p>
        <p style="text-align:center"><img style="width:95%" src="images/xpay.png" /></p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>3</em>三步之最后一步：配置支付授权目录</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>在微信公众平台<a href="http://mp.weixin.qq.com" target="_blank">http://mp.weixin.qq.com</a>，
          登录微信公众平台之后，点击【微信支付】==>【开发配置】，点击【修改】进去。<br>
          支付授权目录：www.domain.com/wxpay/<br>
          共享收货地址：默认即可[是]<br>
          Native原生支付：http://www.domain.com/wxpay/getpackage/index.php [可选项]<br>
          告警通知URL： http://www.domain.com/wxpay/warning/index.php<br>
          最后点击【保存】,这样微信支付就配置完了</p>
        <br>
        <br>
        <br>
        <br>
      </div>
    </div>
  </div>
  <a name="qa"></a>
  <h3 class="detail-title">常见问题</h3>
  <div class="step-intro-cnt mglr">
    <h4><em>1</em>getBrandWCPayRequest:fail_nopermission to execute undefined</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>出现此问题的原因是：微信支付的开发配置中的测试授权目录或授权目录没有设置正确</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>2</em>缺少必填参数openid!</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>Appsecret 填写不对。可以检查或者重置Appsecret,编辑该公众号重填appsecret</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>3</em>点击支付按钮，调用JSAPI没反应？</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>尝试发起支付的页面url，不在支付授权目录下，请检查url 与支付授权目录是否对应</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>4</em>点击支付按钮，提示“access_denied”</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>尝试发起支付的页面url，不在支付授权目录下，请检查url 与支付授权目录是否对应</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>5</em>点击支付按钮，提示“当前公众号没有权限支付本次交易”</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>请确认使用的APPID 是否正确，确认在MP 平台前三项审核结果均为“审核通过”。</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>6</em>点击支付按钮，提示“众账号支付使用了无效的商户号，无法发起该笔交易”</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>请检查是否使用了正确的商户号，确认MP 平台前三项审核结果均为“审核通过”。</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>7</em>点击支付按钮，提示“该公众号支付签名无效，无法发起该笔交易”</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>调起支付的签名错误，请检查相关签名</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>8</em>没有获取到微信支付预支付id，请管理员检查微信支付配置</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>大部分原因是支付的key（api 密钥）填错了</p>
      </div>
    </div>
  </div>
  <div class="step-intro-cnt mglr">
    <h4><em>9</em>package中的参数partner或者prepay_id为空</h4>
    <div class="step-intro-detail-cnt">
      <div class="step-intro-detail-cnt">
        <p>授权目录写错</p>
      </div>
    </div>
  </div>
  <div class="footer" style="display:none"> <a href="http://action.weixin.qq.com/payact/readtemplate?t=mobile/merchant/project_tmpl" class="btn btn-green">返回</a></div>
</div>
<script onerror="(this)" language="javascript" src="style/js/ping.js"></script><script onerror="(this)" src="style/js/sht21bceb.js"></script>
</body>
</html>