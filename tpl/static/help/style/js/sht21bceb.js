var vURL = window.location.search.slice(3);

typeof pgvMain == "function" && pgvMain("", {
virtualURL: vURL
}), document.addEventListener("WeixinJSBridgeReady", function() {
var e = {
img_url: SHARE_IMG_URL,
link: window.location.href,
title: SHARE_TITLE,
desc: SHARE_DESC,
img_width: "120",
img_height: "120"
};
WeixinJSBridge.on("menu:share:appmessage", function(t) {
WeixinJSBridge.invoke("sendAppMessage", e, function(e) {
console.log("分享消息失败");
});
}), WeixinJSBridge.on("menu:share:timeline", function(t) {
WeixinJSBridge.invoke("shareTimeline", e, function(e) {
console.log("分享朋友圈失败");
});
});
}, !1);