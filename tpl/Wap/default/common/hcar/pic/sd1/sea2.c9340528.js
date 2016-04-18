!function(a, b) {
function c(a) {
return function(b) {
return {}.toString.call(b) == "[object " + a + "]";
};
}
function d() {
return A++;
}
function e(a) {
return a.match(D)[0];
}
function f(a) {
for (a = a.replace(E, "/"); a.match(F); ) a = a.replace(F, "/");
return a = a.replace(G, "$1/");
}
function g(a) {
var b = a.length - 1, c = a.charAt(b);
return "#" === c ? a.substring(0, b) :".js" === a.substring(b - 2) || a.indexOf("?") > 0 || ".css" === a.substring(b - 3) || "/" === c ? a :a + ".js";
}
function h(a) {
var b = v.alias;
return b && x(b[a]) ? b[a] :a;
}
function i(a) {
var b, c = v.paths;
return c && (b = a.match(H)) && x(c[b[1]]) && (a = c[b[1]] + b[2]), a;
}
function j(a) {
var b = v.vars;
return b && a.indexOf("{") > -1 && (a = a.replace(I, function(a, c) {
return x(b[c]) ? b[c] :a;
})), a;
}
function k(a) {
var b = v.map, c = a;
if (b) for (var d = 0, e = b.length; e > d; d++) {
var f = b[d];
if (c = z(f) ? f(a) || a :a.replace(f[0], f[1]), c !== a) break;
}
return c;
}
function l(a, b) {
var c, d = a.charAt(0);
if (J.test(a)) c = a; else if ("." === d) c = f((b ? e(b) :v.cwd) + a); else if ("/" === d) {
var g = v.cwd.match(K);
c = g ? g[0] + a.substring(1) :a;
} else c = v.base + a;
return 0 === c.indexOf("//") && (c = location.protocol + c), c;
}
function m(a, b) {
if (!a) return "";
a = h(a), a = i(a), a = j(a), a = g(a);
var c = l(a, b);
return c = k(c);
}
function n(a) {
return a.hasAttribute ? a.src :a.getAttribute("src", 4);
}
function o(a, b, c) {
var d = U.test(a), e = L.createElement(d ? "link" :"script");
if (c) {
var f = z(c) ? c(a) :c;
f && (e.charset = f);
}
p(e, b, d, a), d ? (e.rel = "stylesheet", e.href = a) :(e.async = !0, e.src = a), 
Q = e, T ? S.insertBefore(e, T) :S.appendChild(e), Q = null;
}
function p(a, b, c, d) {
function e() {
a.onload = a.onerror = a.onreadystatechange = null, c || v.debug || S.removeChild(a), 
a = null, b();
}
var f = "onload" in a;
return !c || !V && f ? void (f ? (a.onload = e, a.onerror = function() {
C("error", {
uri:d,
node:a
}), e();
}) :a.onreadystatechange = function() {
/loaded|complete/.test(a.readyState) && e();
}) :void setTimeout(function() {
q(a, b);
}, 1);
}
function q(a, b) {
var c, d = a.sheet;
if (V) d && (c = !0); else if (d) try {
d.cssRules && (c = !0);
} catch (e) {
"NS_ERROR_DOM_SECURITY_ERR" === e.name && (c = !0);
}
setTimeout(function() {
c ? b() :q(a, b);
}, 20);
}
function r() {
if (Q) return Q;
if (R && "interactive" === R.readyState) return R;
for (var a = S.getElementsByTagName("script"), b = a.length - 1; b >= 0; b--) {
var c = a[b];
if ("interactive" === c.readyState) return R = c;
}
}
function s(a) {
var b = [];
return a.replace(Y, "").replace(X, function(a, c, d) {
d && b.push(d);
}), b;
}
function t(a, b) {
this.uri = a, this.dependencies = b || [], this.exports = null, this.status = 0, 
this._waitings = {}, this._remain = 0;
}
if (!a.seajs) {
var u = a.seajs = {
version:"2.2.0"
}, v = u.data = {}, w = c("Object"), x = c("String"), y = Array.isArray || c("Array"), z = c("Function"), A = 0, B = v.events = {};
u.on = function(a, b) {
var c = B[a] || (B[a] = []);
return c.push(b), u;
}, u.off = function(a, b) {
if (!a && !b) return B = v.events = {}, u;
var c = B[a];
if (c) if (b) for (var d = c.length - 1; d >= 0; d--) c[d] === b && c.splice(d, 1); else delete B[a];
return u;
};
var C = u.emit = function(a, b) {
var c, d = B[a];
if (d) for (d = d.slice(); c = d.shift(); ) c(b);
return u;
}, D = /[^?#]*\//, E = /\/\.\//g, F = /\/[^/]+\/\.\.\//, G = /([^:/])\/\//g, H = /^([^/:]+)(\/.+)$/, I = /{([^{]+)}/g, J = /^\/\/.|:\//, K = /^.*?\/\/.*?\//, L = document, M = e(L.URL), N = L.scripts, O = L.getElementById("seajsnode") || N[N.length - 1], P = e(n(O) || M);
u.resolve = m;
var Q, R, S = L.getElementsByTagName("head")[0] || L.documentElement, T = S.getElementsByTagName("base")[0], U = /\.css(?:\?|$)/i, V = +navigator.userAgent.replace(/.*AppleWebKit\/(\d+)\..*/, "$1") < 536;
u.request = o;
var W, X = /"(?:\\"|[^"])*"|'(?:\\'|[^'])*'|\/\*[\S\s]*?\*\/|\/(?:\\\/|[^\/\r\n])+\/(?=[^\/])|\/\/.*|\.\s*require|(?:^|[^$])\brequire\s*\(\s*(["'])(.+?)\1\s*\)/g, Y = /\\\\/g, Z = u.cache = {}, $ = {}, _ = {}, ab = {}, bb = t.STATUS = {
FETCHING:1,
SAVED:2,
LOADING:3,
LOADED:4,
EXECUTING:5,
EXECUTED:6
};
t.prototype.resolve = function() {
for (var a = this, b = a.dependencies, c = [], d = 0, e = b.length; e > d; d++) c[d] = t.resolve(b[d], a.uri);
return c;
}, t.prototype.load = function() {
var a = this;
if (!(a.status >= bb.LOADING)) {
a.status = bb.LOADING;
var b = a.resolve();
C("load", b);
for (var c, d = a._remain = b.length, e = 0; d > e; e++) c = t.get(b[e]), c.status < bb.LOADED ? c._waitings[a.uri] = (c._waitings[a.uri] || 0) + 1 :a._remain--;
if (0 === a._remain) return void a.onload();
var f = {};
for (e = 0; d > e; e++) c = Z[b[e]], c.status < bb.FETCHING ? c.fetch(f) :c.status === bb.SAVED && c.load();
for (var g in f) f.hasOwnProperty(g) && f[g]();
}
}, t.prototype.onload = function() {
var a = this;
a.status = bb.LOADED, a.callback && a.callback();
var b, c, d = a._waitings;
for (b in d) d.hasOwnProperty(b) && (c = Z[b], c._remain -= d[b], 0 === c._remain && c.onload());
delete a._waitings, delete a._remain;
}, t.prototype.fetch = function(a) {
function b() {
u.request(f.requestUri, f.onRequest, f.charset);
}
function c() {
delete $[g], _[g] = !0, W && (t.save(e, W), W = null);
var a, b = ab[g];
for (delete ab[g]; a = b.shift(); ) a.load();
}
var d = this, e = d.uri;
d.status = bb.FETCHING;
var f = {
uri:e
};
C("fetch", f);
var g = f.requestUri || e;
return !g || _[g] ? void d.load() :$[g] ? void ab[g].push(d) :($[g] = !0, ab[g] = [ d ], 
C("request", f = {
uri:e,
requestUri:g,
onRequest:c,
charset:v.charset
}), void (f.requested || (a ? a[f.requestUri] = b :b())));
}, t.prototype.exec = function() {
function a(b) {
return t.get(a.resolve(b)).exec();
}
var c = this;
if (c.status >= bb.EXECUTING) return c.exports;
c.status = bb.EXECUTING;
var e = c.uri;
a.resolve = function(a) {
return t.resolve(a, e);
}, a.async = function(b, c) {
return t.use(b, c, e + "_async_" + d()), a;
};
var f = c.factory, g = z(f) ? f(a, c.exports = {}, c) :f;
return g === b && (g = c.exports), delete c.factory, c.exports = g, c.status = bb.EXECUTED, 
C("exec", c), g;
}, t.resolve = function(a, b) {
var c = {
id:a,
refUri:b
};
return C("resolve", c), c.uri || u.resolve(c.id, b);
}, t.define = function(a, c, d) {
var e = arguments.length;
1 === e ? (d = a, a = b) :2 === e && (d = c, y(a) ? (c = a, a = b) :c = b), !y(c) && z(d) && (c = s(d.toString()));
var f = {
id:a,
uri:t.resolve(a),
deps:c,
factory:d
};
if (!f.uri && L.attachEvent) {
var g = r();
g && (f.uri = g.src);
}
C("define", f), f.uri ? t.save(f.uri, f) :W = f;
}, t.save = function(a, b) {
var c = t.get(a);
c.status < bb.SAVED && (c.id = b.id || a, c.dependencies = b.deps || [], c.factory = b.factory, 
c.status = bb.SAVED);
}, t.get = function(a, b) {
return Z[a] || (Z[a] = new t(a, b));
}, t.use = function(b, c, d) {
var e = t.get(d, y(b) ? b :[ b ]);
e.callback = function() {
for (var b = [], d = e.resolve(), f = 0, g = d.length; g > f; f++) b[f] = Z[d[f]].exec();
c && c.apply(a, b), delete e.callback;
}, e.load();
}, t.preload = function(a) {
var b = v.preload, c = b.length;
c ? t.use(b, function() {
b.splice(0, c), t.preload(a);
}, v.cwd + "_preload_" + d()) :a();
}, u.use = function(a, b) {
return t.preload(function() {
t.use(a, b, v.cwd + "_use_" + d());
}), u;
}, t.define.cmd = {}, a.define = t.define, u.Module = t, v.fetchedList = _, v.cid = d, 
u.require = function(a) {
var b = t.get(t.resolve(a));
return b.status < bb.EXECUTING && b.exec(), b.exports;
};
var cb = /^(.+?\/)(\?\?)?(seajs\/)+/;
v.base = (P.match(cb) || [ "", P ])[1], v.dir = P, v.cwd = M, v.charset = "utf-8", 
v.preload = function() {
var a = [], b = location.search.replace(/(seajs-\w+)(&|$)/g, "$1=1$2");
return b += " " + L.cookie, b.replace(/(seajs-\w+)=1/g, function(b, c) {
a.push(c);
}), a;
}(), u.config = function(a) {
for (var b in a) {
var c = a[b], d = v[b];
if (d && w(d)) for (var e in c) d[e] = c[e]; else y(d) ? c = d.concat(c) :"base" === b && ("/" !== c.slice(-1) && (c += "/"), 
c = l(c)), v[b] = c;
}
return C("config", a), u;
};
}
}(this), define("scripts/cardListData", [ "./vendor/zepto", "./vendor/promise", "./environment" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./vendor/promise"), e = a("./environment");
b.fetch = function(a) {
a = a || "jsonp.json";
var b = new d(function(b, d) {
c.ajax({
url:e.getHost() + "/weixin/card/" + a,
dataType:"json",
success:function(a) {
b("string" == typeof a ? JSON.parse(a) :a);
},
error:function() {
d();
}
});
});
return b;
};
}), define("scripts/cardLoader", [ "./utils", "./environment", "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./vendor/promise", "./tpl/mugedaLoad", "./tpl/loader", "./page", "./navibar", "./tpl/navibar", "./custom", "./tpl/customForm", "./promo", "./tpl/promoHtml" ], function(a, b) {
var c = a("./utils"), d = a("./vendor/promise"), e = a("./tpl/mugedaLoad"), f = a("./tpl/loader"), g = a("./page"), h = a("./navibar"), i = a("./environment"), j = a("./custom"), k = (a("./message"), 
{});
b.loadCard = function() {
return new d(function(a, b) {
m(), r(k).then(q).then(s).then(o).then(n).then(l).then(x).then(function(b) {
b.cssMode && c.setMode("css"), a(b);
}, function(a) {
b(a);
});
});
};
var l = function(a) {
return new d(function(b, c) {
d.all([ t(a), u() ]).then(function(a) {
b(a);
}, function(a) {
c(a);
});
});
}, m = function() {
window.cardFrame = window.cardFrame || {}, cardFrame.setFootContent = function(a, b) {
window.cardFrame.footContent = {
content:a,
link:b
};
}, cardFrame.footContent = null, cardFrame.disableFoot = !1, cardFrame.setOfficialCustom = function(a) {
window.cardFrame.canOfficialCustom = a;
}, cardFrame.canOfficialCustom = !1, cardFrame.setAsReceipt = function() {
window.cardFrame.isReceiptCard = !0;
}, cardFrame.isReceiptCard = !1, cardFrame.Custom = j, cardFrame.Navibar = h, cardFrame.Page = g, 
cardFrame.Utils = c;
}, n = function(a) {
return new d(function(b) {
var d = $(a.loadTpl);
g.remove(0);
var e = g.setNewPage("loading", {
type:"fix"
});
e.dom.append(d), g.addToLayout(e.id), g.setActive(e.id, !0), a.loadTpl = d, a.loadingPage = e, 
c.isCustomLoad() && e.dom.find("#mcard-load").css("visibility", "hidden");
var f = d.find(".cacheImage"), h = f.length;
h ? (d.hide(), f.each(function() {
this.onload = this.onerror = function() {
0 == --h && (d.show(), b(a));
};
})) :b(a);
});
}, o = function(a) {
return new d(function(b) {
var c = a.customObj;
if (cardFrame.getCustomObj = function() {
return a.customObj;
}, cardFrame.getQueryObj = function() {
return a.searchObj;
}, a.searchObj.m_profile) var d = {
type:2,
imgUrl:decodeURIComponent(a.searchObj.m_profile),
background:"#FFF",
pieColor:"#FF5500",
text:c.m_slogan,
textColor:"#FFF"
};
var f = (a.bizData || {})._loading || d || {};
$(document).trigger("cardLoader:showLoading", f), p(f).then(function(c) {
if (a.loadTpl = c, $(document.body).css("background", "black"), f.url) {
var d = new Image();
d.src = f.url, d.onload = d.onerror = function() {
b(a);
};
} else b(a);
}, function() {
a.loadTpl = e({
type:0,
url:"images/5347ba39a3664e9b74000049.png",
width:134,
background:"black"
}), $(document.body).css("background", "black"), b(a);
});
});
}, p = function(a) {
return new d(function(b, c) {
var d = 1 * a.type;
switch (a.background = a.background || "#000", d) {
case 0:
a.width = a.width || 280, b(e(a));
break;

case 1:
case 2:
b(e(a));
break;

default:
c();
}
});
}, q = function(a) {
return new d(function(b) {
var d = a.searchObj.m_bizId;
return null == d ? b(a) :void $.ajax({
url:i.getHost() + "/custom/" + c.getCrid() + "/" + d + ".json",
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function(c) {
a.bizData = c, b(a), a.bizId = d;
},
error:function() {
b(a);
}
});
});
}, r = b.getCustomObj = function(a) {
return new d(function(b, d) {
var e = MugedaUrl.current.getQueryObj();
if (e.customId) v(e.customId).then(function(c) {
a.customId = e.customId, a.customObj = c, a.searchObj = e, b(a);
}, function() {
d();
}); else {
var f = {}, g = e.custom;
if (g) {
var h = c.base64.decode(decodeURIComponent(g));
f = new MugedaUrl("http://a?" + h).getQueryObj();
}
a.customObj = f, a.searchObj = e, b(a);
}
});
}, s = b.resolveFoot = function(a) {
return new d(function(b) {
var c = a.customObj, d = a.customId, e = a.bizData;
if (d) for (var f in c) c.hasOwnProperty(f) && (c[f] = decodeURIComponent(c[f])); else if (2 == c._v) {
for (f in c) c.hasOwnProperty(f) && (c[decodeURIComponent(f)] = decodeURIComponent(c[f]), 
decodeURIComponent(f) != f && delete c[f]);
delete c._v;
} else c._footcontent && (c._footcontent = decodeURIComponent(c._footcontent)), 
c._footurl && (c._footurl = decodeURIComponent(c._footurl));
if (e) {
for (var f in e) if (e.hasOwnProperty(f)) if ("string" == typeof e[f]) e[f] = decodeURIComponent(e[f]); else for (var g in e[f]) e[f].hasOwnProperty(g) && (e[f][g] = decodeURIComponent(e[f][g]));
e._footcontent && (c._footcontent = e._footcontent), e._footurl && (c._footurl = e._footurl);
}
c._footcontent || (c._footcontent = "关注 {{木疙瘩微卡}} 更多酷炫内容"), c._footurl || (c._footurl = "http://t.cn/RP7kPN1"), 
cardFrame.setFootContent(c._footcontent, c._footurl), b(a);
});
}, t = function(a) {
return new d(function(b, d) {
var e = a.customObj;
window._mrmcp = {
creative_path:base + "cards/" + c.getCrid() + "/",
render_mode:"inline",
ga_url:base + "ga.js"
}, j.hookMugedaCard(e);
var g = base + "cards/" + c.getCrid() + "/loader.js?v=125", h = $(f({}));
$(document.body).append(h), h.find(".foot-mark").bind("click", function() {
window.open(cardFrame.footContent.link, "_blank");
}), cardFrame.showFootContent = function() {
if (cardFrame.footContent.content && !cardFrame.disableFoot) {
var a = h.find(".foot-mark").html(cardFrame.footContent.content.replace(/\{\{/g, "<span>").replace(/\}\}/g, "</span>"));
a.insertAfter(a.parent().children().last()), setTimeout(function() {
a.css("bottom", "0");
}, 0);
}
};
var i = document.createElement("script");
i.src = g, i.id = "Mugeda_" + c.getCrid(), i.onload = function() {
document.title = _mrmcp.title;
}, i.onerror = function() {
d();
};
var k = function() {
"complete" != document.readyState ? $(document).one("readystatechange", k) :h.append(i);
};
k(), a.stageParent = h, w(a, b, d), $(document).one("mugedaReady", function() {
var a = Mugeda.currentAni;
a.addEventListener("beforeLoadImage", function(a, b, c) {
var d = !0, f = b.dom.map(function(a) {
return a.object && a.object.getPath ? a.object.getPath() :"@";
}).filter(function(a) {
return 0 != a.indexOf("<-") && 0 != a.indexOf("@") ? !0 :void 0;
});
f.length ? f.forEach(function(a) {
e.hasOwnProperty(a) || (d = !1);
}) :d = !1, c.noCache = d;
}), a.addEventListener("scriptReady", function() {
for (var b in e) if (e.hasOwnProperty(b)) {
var c = e[b];
try {
c = JSON.parse(c).u;
} catch (d) {}
null == c && (c = e[b]), (c.lastIndexOf(".png") == c.length - 4 || c.lastIndexOf(".jpg") == c.length - 4) && a.loadImage("dom_back", {
url:c,
dom:[]
});
}
});
});
});
}, u = function() {
return new d(function(a) {
setTimeout(function() {
a();
}, 3e3);
});
}, v = function(a) {
return new d(function(b) {
$.ajax({
url:i.getHost() + "/custom/" + c.getCrid() + "/" + a + ".json",
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function(a) {
b(a);
},
error:function() {
b({});
}
});
});
}, w = function(a, b) {
var c = a.stageParent, d = function(b) {
var g = $("[id$='_stage']"), h = $(b.target);
if (h.hasClass("MugedaStage") || 0 != g.length) {
if (c.unbind("DOMSubtreeModified", d), a.cssMode = g.length > 0, a.cssMode) g.addClass("MugedaStage"); else {
g = h;
var i = $("meta[name=viewport]");
i.attr("content", "width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0"), 
Mugeda.updateViewport = function() {};
var j = function(a) {
var b = $(a.target);
"IMG" == b.prop("tagName") && (b.attr("src") || []).indexOf("close_button.png") > -1 && b.bind("load", function() {
g.unbind("DOMNodeInserted", j), B();
});
};
g.bind("DOMNodeInserted", j);
}
a.stage = g, a.cssMode ? (a.loadDiv = $("#mugeda_loading_progress"), f()) :a.stage.bind("DOMSubtreeModified", e);
}
}, e = function(b) {
var c = $(b.target);
"loadingDiv" == c.prop("id") && (a.stage.unbind("DOMSubtreeModified", e), a.loadDiv = c, 
f());
}, f = function() {
var c = a.loadingPage.dom, d = null, e = 0, f = c.find(".message"), g = c.find(".process"), h = c.find(".process-frame"), i = a.loadDiv;
h.css("visibility", "visible"), i.bind("DOMNodeRemoved", function() {
t();
}), i.bind("DOMSubtreeModified", function() {
var b = this.innerHTML, c = b.match(/Loading\.\.\.(\d+)\/(\d+)/);
if (c && 3 == c.length) {
var f = parseInt(c[1]), g = parseInt(c[2]);
if (isNaN(f) || isNaN(g)) return;
g != d && (e++, d = g);
var h = 0;
a.cssMode ? h = Math.round(100 * (1 + f) / g) :1 == e ? h = Math.round(f / g * 25) :2 == e && (h = 25 + Math.round(f / g * 75)), 
m(h);
}
});
var j = 0, k = 0, l = 0, m = function(a) {
j != k && (j = k), k = a, l = (a - j) / 12, r(j);
var b = function() {
k > j && (j = Math.min(j + l, k), r(j), setTimeout(b, 50));
};
b();
};
if (a.searchObj.m_profile) var n = a.loadTpl.find(".leftPie"), o = a.loadTpl.find(".rightPie"), p = a.loadTpl.find(".icon"), q = 1e-4;
var r = function(b) {
if (a.searchObj.m_profile) {
var c = Math.min(100, b), d = 360 * c / 100, e = Math.max(180, d), h = Math.min(360, d + 180);
n[0].style.webkitTransform = "rotate(" + e + "deg)", n[0].style.mozTransform = "rotate(" + e + "deg)", 
n[0].style.msTransform = "rotate(" + e + "deg)", n[0].style.transform = "rotate(" + e + "deg)", 
o[0].style.webkitTransform = "rotate(" + h + "deg)", o[0].style.mozTransform = "rotate(" + h + "deg)", 
o[0].style.msTransform = "rotate(" + h + "deg)", o[0].style.transform = "rotate(" + h + "deg)", 
p[0].style.webkitTransform = "rotate(" + q + "deg)", p[0].style.mozTransform = "rotate(" + q + "deg)", 
p[0].style.msTransform = "rotate(" + q + "deg)", p[0].style.transform = "rotate(" + q + "deg)", 
q += .001, e > 180 && (o[0].style.webkitTransition = "none", o[0].style.mozTransition = "none", 
o[0].style.msTransition = "none", o[0].style.transition = "none");
} else b = ~~b, f.html(b + "%"), g.css("width", b + "%");
}, s = !1, t = function() {
s || (s = !0, a.stage.unbind("DOMSubtreeModified"), b(a));
};
};
c.bind("DOMSubtreeModified", d);
}, x = function(a) {
return new d(function(b) {
j.saveCardParam(a[0]), a = a[0], B(), $("#stageParent").show(), g.remove(a.loadingPage.id), 
g.setActive(1);
var c = $(".MugedaStage .symbol");
if (c.each(function() {
$(this).html().indexOf("酷炫贺卡") > -1 && $(this).remove();
}), !a.cssMode) {
var d = function() {
this.length - 1 === Math.floor(this.currentId) && (e.scene.removeEventListener("enterframe", d), 
cardFrame.showFootContent());
}, e = Mugeda.getMugedaObject();
e.scene.addEventListener("enterframe", d), z();
}
b(a);
});
}, y = function() {
function a() {
var a = document, b = a.createElement("div");
b.style.height = "2500px", a.body.insertBefore(b, a.body.firstChild);
var c = a.documentElement.clientHeight > 2400;
return a.body.removeChild(b), c;
}
var b = document.documentElement, c = b && 0 === b.clientHeight;
if ("number" == typeof document.clientWidth) return {
width:document.clientWidth,
height:document.clientHeight
};
if (c || a()) {
var d = document.body;
return {
width:d.clientWidth,
height:d.clientHeight
};
}
return {
width:b.clientWidth,
height:b.clientHeight
};
}, z = function() {
window.MugedaCss3 = window.MugedaCss3 || {};
var a = window.MugedaCss3.getEventPosition;
window.MugedaCss3.getEventPosition = function(b, c) {
var d = a.call(window.MugedaCss3, b, c);
return d.x = d.x / A, d.y = d.y / A, d;
};
}, A = null, B = (document.getElementById("card-title"), function() {
if (window._mrmcp) {
var a = c.windowSize = y(), b = a.width, d = a.height, e = _mrmcp.width, f = _mrmcp.height, g = b / e, h = d / f;
A = $.os.phone ? g :Math.min(g, h), .1 > A && (A = .1);
var i = Math.floor((b - e * A) / 2), j = Math.floor((d - f * A) / 2);
f = parseInt(A * f), 0 > j && (f += j), k.stage && (k.stage[0].style.cssText += "-webkit-transform: scale(" + A + ");transform: scale(" + A + ");-moz-transform: scale(" + A + ");-ms-transform: scale(" + A + ");", 
k.stage[0].parentNode.style.cssText += "height: " + f + "px;margin-left: " + i + "px;margin-top: " + j + "px;", 
Array.prototype.forEach.call(document.querySelectorAll("[data-audio-icon]"), function(a) {
a.style.top = (1 > -j / A ? 0 :-j / A) + 10 + "px";
}), Mugeda && Mugeda.scene && (Mugeda.scene.adaption = Mugeda.scene.adaption || {}, 
Mugeda.scene.adaption.scale = A, Mugeda.scene.adaption.marginTop = Mugeda.scene.adaption.marginBottom = j / A, 
Mugeda.scene.adaption.marginLeft = Mugeda.scene.adaption.marginRight = i / A, Mugeda.scene.event && Mugeda.scene.event.resize && Mugeda.scene.event.resize instanceof Array && Mugeda.scene.event.resize.forEach(function(a) {
a.call(Mugeda.scene);
})));
}
});
window.addEventListener("resize", B);
}), define("scripts/cardTipview", [ "./vendor/zepto", "./page", "./tpl/cardTip", "./tpl/template" ], function(a) {
var b = a("./vendor/zepto"), c = a("./page"), d = a("./tpl/cardTip");
window.createMugedaTip = function() {
var a = b(d()), e = c.setNewPage("cardTip", {
background:"rgba(0,0,0,0.7)"
}), f = "touchstart";
(!window.isMobile || window.isMobile && !window.isMobile()) && (f = "click"), a.one(f, function() {
c.remove(e.id), c.back();
}), e.dom.append(a), b(document.body).bind("weixin:shareOK", function() {
c.remove(e.id), c.back();
}), c.addToLayout(e.id), c.setActive(e.id, !0);
};
}), define("scripts/cardview", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./navibar", "./tpl/navibar", "./environment", "./gallery", "./page", "./cardTipview", "./tpl/cardTip", "./tpl/replyInvite", "./tpl/cardviewBottom", "./tpl/audioIco", "./tpl/customImageMessage", "./custom", "./tpl/customForm", "./utils", "./vendor/promise", "./promo", "./tpl/promoHtml", "./cardLoader", "./tpl/mugedaLoad", "./tpl/loader", "./customSound", "./user", "./photoservice", "./tpl/audioPlay", "./tpl/recordAudio", "./tpl/profileCustom" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./navibar"), f = a("./gallery"), g = a("./page"), h = a("./environment"), i = (a("./cardTipview"), 
a("./tpl/replyInvite")), j = a("./tpl/cardviewBottom"), k = a("./tpl/audioIco"), l = a("./tpl/customImageMessage"), m = a("./custom"), n = a("./utils"), o = a("./cardLoader"), p = null, q = a("./customSound");
window.showMugedaAlert = function(a) {
a.showConfirm(a || "未知消息");
}, b.init = function(a) {
p = a, o.loadCard().then(function(a) {
r(a);
});
};
var r = function(a) {
u(a), c(document).trigger("pc:cardFrameReady", cardFrame);
};
b.defineCustomImageData = function(a) {
s = a;
};
var s = [], t = function() {
if (window.Mugeda && Mugeda.getMugedaObject) {
var a = Mugeda.getMugedaObject(), b = {};
if ("loaded" == a.loadProcess) {
if (window.MugedaCard && MugedaCard.data) for (var d in MugedaCard.data) MugedaCard.data.hasOwnProperty(d) && MugedaCard.data[d] && MugedaCard.data[d].obj && MugedaCard.data[d].obj.length && MugedaCard.data[d].obj[0].data && 2005 == MugedaCard.data[d].obj[0].data.type && "signature" != MugedaCard.data[d].obj[0].cardRefParam && (b[d] = MugedaCard.data[d].obj[0].src);
cardFrame.getCustomImageList = function() {
return b;
}, c(document).trigger("pc:customImageListChange");
} else setTimeout(t, 500);
}
}, u = function(b) {
n.reportGATime(), t(), b.cssMode && (window.MugedaCard = window.MugedaCard || {}, 
window.MugedaCard.finalizeCustomParameters = m.finalizeCustomParametersV2);
var i = b.customObj, o = b.bizData;
m.activeCustom(i);
var r = m.bindWeiParamater(i);
m.defineWeixinBridge(r), m.exportCustomFunction(i);
var u = Mugeda.scene ? Mugeda.scene.scene :null;
if (b.cssMode) var v = c(".定制"); else {
var w = u ? u.getObjectByName("定制") :null, x = u ? u.getObjectByName("发送") :null;
w && (w.visible = !1), x && (x.visible = !1);
}
if (h.isPublic() && (cardFrame.disableFoot = !0), w || b.cssMode && v.length) {
cardFrame.disableFoot = !0, cardFrame.isThreeCircleBtnHidden = function() {
return !1;
};
var y = c(j({
isCustomed:n.getParam("m_bizId")
})), z = {
tpl:y
};
c(document).trigger("cardview:cardViewBottomTpl", z), y = z.tpl, p.append(y);
var A = y.filter("#cardview_button").addClass("hide"), B = y.filter("#cardview_bottom").addClass("hide"), C = y.find(".btn_custom"), D = y.find(".btn_more"), E = 0;
A.addClass("init");
var F = function() {
A.addClass("fade");
}, G = function() {
clearTimeout(K), A.removeClass("fade"), A.removeClass("init"), I || (K = setTimeout(F, 5e3));
}, H = window.MugedaCss3 && MugedaCss3.getAnimation ? MugedaCss3.getAnimation() :null, I = !1;
if (H) {
var J = function() {
this.length - 1 === Math.floor(this.currentId) && (H.scene.removeEventListener("enterframe", J), 
I = !0, G());
};
H.scene.addEventListener("enterframe", J);
}
var K = setTimeout(F, 5e3), L = function() {
A.addClass("hide"), B.css("display", "none"), setTimeout(function() {
B.css("display", "block"), setTimeout(function() {
B.removeClass("hide");
});
}), window.clearTimeout(E), E = window.setTimeout(function() {
M(), A.removeClass("init");
}, 5e3);
}, M = function() {
A.removeClass("hide"), B.addClass("hide"), G();
};
A.on("click", L), h.isPublic() || (C.on("click", function() {
if (v) {
var a = new Function("MugedaBehavior", c(v).attr("onclick"));
if (window.MugedaBehavior) a(window.MugedaBehavior); else {
var b = null;
Object.defineProperty(window, "MugedaBehavior", {
set:function(c) {
setTimeout(function() {
a(window.MugedaBehavior);
}, 0), b = c;
},
get:function() {
return b;
}
});
}
} else w.data && w.data.param && w.data.param.form ? MugedaBehavior.popupForm(JSON.parse(w.data.param.form)) :console.error("we cannot find form data");
}), D.on("click", function() {
window.cardFrame && cardFrame.footContent && (ga("send", "event", "button", "click", "more button"), 
setTimeout(function() {
window.open(cardFrame.getCustomObj()._footurl, "_blank");
}, 100));
}), y.find(".btn_show_tip").on("click", function() {
window.createMugedaTip();
}), y.find(".btn_collect").on("click", function() {
d.showLoading("收藏中"), a.async("./collectService", function(a) {
a.add();
});
})), setTimeout(function() {
A.removeClass("hide");
}, 1e3);
} else cardFrame.isThreeCircleBtnHidden = function() {
return !0;
};
if (window.cardFrame.showAudioBtn = function() {
if (i.custaudio) {
var b = a("./tpl/audioPlay"), d = c(b({})).appendTo(p).filter(".clickSound");
d.css("top", "75%"), d.addClass("transition"), setTimeout(function() {
d.css("opacity", "1"), d.css("left", "50%"), d.css("top", "75%"), setTimeout(function() {
d.removeClass("transition");
}, 1e3);
}, 100), q.soundPlay(d, i.custaudio, m.getBackgroundAudio);
}
}, setTimeout(window.cardFrame.showAudioBtn, 5e3), b.cssMode) {
var N = c(k({}));
p.append(N);
var O = null, P = null, Q = function(a) {
P != a && (P = a, clearTimeout(O), "loading" == a ? O = setTimeout(function() {
Q("off");
}, 2e3) :"on" == a && (O = setTimeout(function() {
R && R[0].pause();
}, 9e4)), N.find(".ico").hide(), N.find("." + a).show());
};
Q("off");
var R = null;
if (Mugeda.getAudioCacheAll) {
var S = Mugeda.getAudioCacheAll();
for (var T in S) if (S.hasOwnProperty(T)) {
R = c(S[T]), m.getBackgroundAudio = R[0] ? R[0] :m.getBackgroundAudio, N.find(".off").bind("click", function() {
R[0].currentTime = 0, R[0].play(), Q("loading");
}), N.find(".on").bind("click", function() {
R[0].pause();
}), R.bind("timeupdate", function() {
"loading" == P && Q("on");
}), R.bind("pause", function() {
Q("off");
}), R.bind("ended", function() {
Q("off");
}), R[0].paused || Q("on");
break;
}
}
}
if (window.cardFrame && cardFrame.isReceiptCard) {
var U = MugedaUrl.current.getQueryObj().error;
if (U) window.localStorage && localStorage.setItem && localStorage.setItem("receiptError", U), 
delete MugedaUrl.current.getQueryObj().error, location.href = MugedaUrl.current.getURL(); else if (window.localStorage && localStorage.getItem) {
var V = parseInt(localStorage.getItem("receiptError"));
localStorage.removeItem("receiptError"), 1 == V ? d.showConfirm("抱歉，微信认证失败。") :2 == V && d.showConfirm("抱歉，没有权限。");
}
}
window.MugedaBehavior = window.MugedaBehavior || {};
var W = window.MugedaBehavior.popupForm, X = !0, Y = null, Z = [];
s = [], window.MugedaBehavior.popupForm = function(b) {
m.setLastFormData(b);
var j = m.getCardParam().customObj || {};
if (c.isArray(b.items)) for (var k in j) j.hasOwnProperty(k) && b.items.filter(function(a) {
return a.id == k;
}).forEach(function(a) {
[ "input", "textarea", "phone", "email" ].indexOf(a.type) > -1 && (a.value = j[k]);
});
"string" == typeof b.msg && (b.msg = b.msg.replace("或发到您的朋友圈", "")), X && setTimeout(function() {
d.setWeixinToolbarBotton(!1), X = !1, W.call(window, b);
var j = c(window.popupFormDiv).addClass("content"), k = g.setNewPage("popupForm", {
dom:j,
inDom:!0
});
j.find("style").remove(), j.find(".popupFormSubmit").hide(), j.find(".popupFormCancel").hide();
var p = j.find("form");
p.addClass("form-horizontal").children("div").addClass("form-group box").children("label").css("min-width", "100px").addClass("control-label").next().addClass("form-control box-flexible"), 
j.find("textarea").css("height", "90px").css("width", "100%").wrap('<div class="box-flexible"></div>'), 
j.find("select").css("overflow", "hidden"), j.find(".popupFormRadioList").children("h3").each(function() {
c(this).replaceWith('<label style="min-width:100px" class="control-label">' + c(this).html() + "</label>");
}), j.find(".mformradio, .mformcheck").removeClass("form-control").find("label").bind("click", function(a) {
c(a.target).is("input") || c(this).find("[type=radio]").click();
}), j.find(".mformcheck").find("label").each(function() {
var a = c(this).parent();
a.prev().append(c(this)), a.remove();
});
var r = m.getCardParam().customObj || {};
if (c.isArray(b.items)) for (var t in r) if (r.hasOwnProperty(t)) try {
j.find('[name="' + t + '"]').val(r[t]);
} catch (u) {
setTimeout(function() {
throw new Error("queryselectorall抛出错误，错误的字符串是：" + t);
}, 0);
}
if (window.MugedaCard && MugedaCard.data) for (var v in MugedaCard.data) {
var w = MugedaCard.data[v];
if ("receipt" == w.type) {
var x = w.des;
if ("string" == typeof x) {
var y = j.find("#_map_" + x);
y.length && (Y = y, Z.push(v));
}
}
}
s = [];
for (var z in window.data) for (var A = window.data[z], B = (A.formDescription, 
A.obj), C = !1, D = 0; D < B.length; D++) {
var v = B[D];
if (!C && ("image" === v.cardRefParam || "signature" === v.cardRefParam || "masked" === v.cardRefParam)) {
C = !0;
var E = {
src:v.srcUserImg || v.oriSrc || v.src,
id:z,
type:v.cardRefParam,
showSrc:v.newSrc || v.src,
ori:v.oriSrc
};
s.push(E);
}
}
if (s.length) if (h.isApp2() || h.haveURL()) {
var F = c(l({
env:h.isApp2() ? "APP" :"OTHER"
}));
p.append(F);
var G = f.init(c(j.find(".custom-image-list")), s.sort(function(a, b) {
a = a.id, b = b.id;
for (var c = Math.min(a.length, b.length), d = 0; c > d; d++) if (a.charCodeAt(d) != b.charCodeAt(d)) return a.charCodeAt(d) - b.charCodeAt(d);
return a.length - b.length;
}), {
minWidth:150,
src:"src",
padding:16,
ratio:1,
render:!0,
cover:!0,
innerCover:!0,
tplCallback:function(b, e) {
var g = function() {
var a = c('<div class="close"></div>');
b.find(".coverImage").append(a), e.src == e.raw.showSrc && a.hide(), a.one("click", function() {
a.hide(), f.replaceImage(G, e.id, e.raw.showSrc), e.raw.ori = null, e.raw.imgNew = null;
});
};
!h.isApp() && h.haveURL() ? a.async("./photoservice", function(a) {
var c = e.raw.imageUploader = new a.MugedaImageUploader();
c.initUploader(b.find(".coverImage")[0], function(b, c) {
"ok" == b ? (n.localStorage.set("supportCustomImage", 1), F.filter("#customImagePromptMessage").hide(), 
d.hideLoading(!0), a.customImage(e.raw, function(a) {
f.replaceImage(G, e.id, a);
})) :"error" == b ? (d.hideLoading(!0), d.showMessage(c)) :d.showLoading(c);
}), g();
}) :g();
},
callback:function(b, c) {
var d = n.localStorage.get("supportCustomImage");
d || F.filter("#customImagePromptMessage").show(), h.isApp2() && a.async("./user", function() {
a.async("./photoservice", function(a) {
a.customImage(b, function(a) {
f.replaceImage(G, c.id, a);
});
});
});
}
});
} else p.append('<small class="tip-pic customImageItem"><i class="fa fa-light"></i> 你知道吗？下载并使用木疙瘩微卡APP，还可以为微卡添加头像签名，以及其它酷炫功能哦！<a href="install.html">点击下载</a></small>');
cardFrame.setImageCustomFormOff = function() {
j.find(".custom_voice").hide(), j.find(".loading_page").hide();
};
var H = c(a("./tpl/recordAudio")({}));
p.append(H);
var I = H.find(".recording1"), J = H.find(".recording2");
h.isApp2() ? I.on("click", function() {
var a = j.find("textarea"), b = a.length ? a.first().val() :"";
q.addAudio(b).then(function() {
var a = !0;
q.isCustSound && (q.init(), J.show(), q.audio.addEventListener("ended", function() {
a = !0, this.src = q.audioPath, J.removeClass("stop");
}, !1), J.on("click", function() {
a ? (a = !1, q.playAudio(), J.addClass("stop")) :(a = !0, q.pause(), J.removeClass("stop"));
}));
}, function(a) {
a && d.showMessage(a);
});
}) :I.on("click", function() {
d.showConfirm("该特性需要在木疙瘩微卡App中使用。您需要现在下载木疙瘩微卡App吗？", !0, {
labelConfirm:"下载",
confirm:function() {
window.top.location.href = "install.html";
},
cancel:function() {
d.hideConfirm();
}
});
}), p.append(a("./tpl/profileCustom")({
isWeixin:h.isWeixin() || h.isApp2()
})), p.on("click", ".changeLogo", function() {
h.isOffical() ? d.showConfirm("登录木疙瘩微卡PC端，可完成企业定制，为微卡添加图片或logo，以及更多企业专属功能。\nweika.mugeda.com") :d.showConfirm("此功能需要加入我们的企业定制服务，免费注册，只需30秒。\n企业服务可以上传logo、修改关注链接方便粉丝关注、更方便的定制图片语音。还有企业专属模板！", !0, {
labelConfirm:"立即加入",
labelCancel:"下次再说",
confirm:function() {
window.open("http://weika.mugeda.com/server/page/apply.html?referer=" + encodeURIComponent(location.href) + "&source=custom_form");
},
cancel:function() {
d.hideConfirm();
}
});
});
var K = null;
p.on("click", "input.custom-logo[type=radio]", function() {
var a = c(this), b = 1 * a.val();
if (K == b && (p.find("input.custom-logo[type=radio]").prop("checked", !1), b = null), 
K = b, p.find(".spanPcCustomValue").hide(), p.find(".spanSloganCustomValue").hide(), 
1 == b) {
if (h.isApp2()) {
var e = null, f = null, g = null;
if (window.localStorage) {
var i = localStorage.wechat_userinfo, j = localStorage.wechat_userinfo_time, k = new Date().getTime();
if (i && 7200 > k - j) try {
e = JSON.parse(i), e && e.headimgurl && e.unionid && (f = e.headimgurl, g = e.unionid);
} catch (l) {
d.showConfirm("获取微信用户信息时出错：" + l.toString());
}
}
var m = function(a, c) {
if (a) {
var e = p.find("input#inputProfileImage");
e.length || p.append("<input id='inputProfileImage' type='hidden' value='" + a + "'></input>"), 
e.value = a;
} else d.showConfirm("获取微信用户信息失败" + (c ? "（" + c + "）" :"") + "。请稍后重试。"), p.find("input.custom-logo[type=radio]").prop("checked", !1), 
b = null, K = b, 1 == b ? p.find(".spanSloganCustomValue").show() :p.find(".spanSloganCustomValue").hide();
}, n = !1;
if (!f && window.mucard && mucard.wechatLogin) {
d.showLoading("获取微信头像"), n = !0;
var o = !1;
mucard.wechatLogin(function(a) {
d.hideLoading(), o = !0;
try {
e = JSON.parse(a), e && e.headimgurl && e.unionid && (f = e.headimgurl, g = e.unionid, 
localStorage.wechat_userinfo = a, localStorage.wechat_userinfo_time = new Date().getTime());
} catch (b) {
d.showConfirm("获取微信用户信息时出错：" + b.toString());
}
m(f);
}), setTimeout(function() {
o || (d.hideLoading(), o = !0, m(null, "等待超时"));
}, 3e4);
}
f || n || m(f);
}
} else 2 == b && p.find(".spanPcCustomValue").show();
var q = a.prop("checked"), r = a.parent().next();
q ? r.show() :r.hide();
}), p.on("change", "#selectSlogan", function() {
1 * c(this).val() == 999 ? c(this).parent().next().show() :c(this).parent().next().hide();
}), j.on("click", ".tip-pic-btn", function() {
c(this).parent().next().toggle(), c(this).find(".tip-pic-btn > .fa").toggleClass("fa-rotate-270");
}), i._bid || o && (o._loading || o._footurl) || MugedaUrl.current.getQueryObj().m_bizId || !h.isWeixin() || window.cardFrame.canOfficialCustom;
var L = function(a) {
a.srcElement == j[0] && (X = !0, setTimeout(function() {
d.setWeixinToolbarBotton(!0);
}), a.srcElement.className.indexOf("page-scroll") > -1 && (k.dom.unbind("DOMNodeRemoved", L), 
setTimeout(function() {
g.remove(k.id), g.back();
}, 0)));
};
k.dom.bind("DOMNodeRemoved", L);
var M = j.find("h2").first().html();
document.title = M;
var N = e.getNaviBar(M, {
leftTpl:c('<span class="btn btnCancel' + (c.os.ios ? " needsclick" :"") + '">取消</span>'),
rightTpl:c('<span class="btn btnSubmit">确定</span>'),
hideWeiIcon:!0
});
N.navibarTpl.on("click", ".btnSubmit", function(a) {
j.find(".popupFormSubmit").trigger("click"), a.preventDefault();
}), N.navibarTpl.on("click", ".btnCancel", function(a) {
j.find(".popupFormCancel").trigger("click"), a.preventDefault();
}), j.append(N.navibarTpl), cardFrame.event = {
dom:j,
data:b
}, c(document).trigger("beforepopupcustomform"), g.setActive(k.id, !0), c(document).trigger("popupcustomform", j);
var O = MugedaUrl.current.getQueryObj().m_bizId;
O && cardFrame.setImageCustomFormOff();
}, 300);
};
var $ = function(a, b, d, e) {
e = e || {};
for (var f = a.split("&"), g = 0; g < s.length; g++) {
var j = s[g];
j.imgNew ? f.push(j.id + "=" + encodeURIComponent(JSON.stringify({
u:j.imgNew.src,
l:0,
t:0,
w:j.imgNew.rawWidth,
h:j.imgNew.rawHeight
}))) :j.ori && j.ori !== j.src && f.push(j.id + "=" + encodeURIComponent(j.src));
}
q.isCustSound && f.push("custaudio=" + encodeURIComponent(q.audioPath));
var k = c(window.popupFormDiv);
if (!i._bid && h.isOffical() && window.cardFrame.canOfficialCustom && k) {
var l = k.find("#weixingfootContent"), n = k.find("#weixingfootUrl");
if (l.length && n.length) {
var o = l.val(), p = n.val();
m.addFootToCustom(f, o, p), window.localStorage && (localStorage.mugeda_foot_content = o, 
localStorage.mugeda_foot_url = p);
}
} else for (var r in i) i.hasOwnProperty(r) && 0 == r.indexOf("_") && f.push(r + "=" + encodeURIComponent(i[r]));
var t = document.getElementById("checkboxProfile"), u = document.getElementById("selectSlogan"), v = document.getElementById("inputSlogan"), w = document.getElementById("inputProfileImage");
if (t) {
var x = t.checked ? 1 :0, y = u.value, z = w ? w.value :"";
999 == y && (y = v.value), m.addCustomProfile(f, x, y, z);
}
for (var g = 0; g < f.length; g++) -1 != f.indexOf("=") && (Z.indexOf(f[g].split("=")[0]) > -1 ? f.splice(g--, 1) :"_footcontent=%E5%85%B3%E6%B3%A8%20%7B%7B%E6%9C%A8%E7%96%99%E7%98%A9%E5%BE%AE%E5%8D%A1%7D%7D%20%E6%9B%B4%E5%A4%9A%E9%85%B7%E7%82%AB%E5%86%85%E5%AE%B9" == f[g] ? f.splice(g--, 1) :"_footurl=http%3A%2F%2Ft.cn%2FRP7kPN1" == f[g] ? f.splice(g--, 1) :0 == f[g].split("=")[1].indexOf("__") && f.splice(g--, 1));
if (Y) {
var A = Y.get(0).selectedIndex, B = A > 0;
e.receiptCard = !0, e["public"] = B;
}
a = f.join("&"), d(a, e);
};
window.customImage = $;
};
b.animationReady = u, window.cardFrame = window.cardFrame || {}, cardFrame.showReceiptList = function() {
var a = MugedaUrl.current.getQueryObj().hash;
if (!a) return void d.showConfirm("这张卡片还没有定制哦。");
var b = "https://weika.mugeda.com/card/invite_card.php/list", c = encodeURIComponent(MugedaUrl.current.getURL());
window.open(b + "?redirect=" + c + "&receipt=" + a);
}, cardFrame.replyReceiptCard = function() {
var a = MugedaUrl.current.getQueryObj().hash;
if (!a) return void d.showConfirm("这张卡片还没有定制哦。");
var b = e.getNaviBar("回复", {
leftTpl:c('<span class="btn btnCancel">取消</span>'),
rightTpl:c('<span class="btn btnSubmit">确定</span>')
});
b.navibarTpl.on("click", ".btnSubmit", function() {
g.remove(f.id);
var b = parseInt(f.dom.find(".answer").val()) || 0, c = encodeURIComponent(n.escapeHTML(f.dom.find(".message").val())), d = "https://weika.mugeda.com/card/invite_card.php/attend", e = encodeURIComponent(MugedaUrl.current.getURL());
window.open(d + "?redirect=" + e + "&receipt=" + a + "&value=" + b + "&data=" + c);
}), b.navibarTpl.on("click", ".btnCancel", function() {
g.remove(f.id);
});
var f = g.setNewPage("replyInvitePage", {});
f.dom.append(b.navibarTpl), f.dom.append(i()), g.addToLayout(f.id), g.setActive(f.id, !0);
};
}), define("scripts/collectGallery", [ "./vendor/zepto", "./environment" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./environment"), e = [], f = 0;
c(window).bind("resize", function() {
for (var a = []; e.length; ) {
var c = e.pop();
c.container.isExist() && (b.adaptSize(c), a.push(c));
}
e = a;
}), b.adaptSize = function(a) {
var b = a.container.find(".imgItem"), c = a.container.innerWidth(), d = Math.ceil(c / (a.minWidth + a.padding)), e = c / d - a.padding;
if (b.children("img ,.preload").height(e * a.ratio), a.oneline && !a.disable) {
var f = a.container.children(), g = f.length, h = 0;
"number" == typeof a.oneline ? h = a.oneline :(a.oneline = "a") ? h = Math.max(1, Math.floor(g / d)) :null != a.oneline && (h = 1);
var i = Math.max(g - d * h, 0);
f.show();
for (var b = [], j = 0; i > j; j++) {
var k = a.notRandom ? g - 1 - j :Math.floor(Math.random() * g);
b.indexOf(k) > -1 ? j-- :(b.push(k), f.eq(k).hide());
}
}
}, b.check = function(a, b, d) {
if (null == d) {
var e = b.container.find(".check");
a ? e.show() :e.hide();
} else c.isArray(d) || (d = [ d ]), d.forEach(function(c) {
var d = b.imageData[c].dom.find(".check");
a ? d.show() :d.hide();
});
}, b.removeItem = function(a, b) {
c.isArray(b) || (b = [ b ]), b.forEach(function(b) {
var c = a.imageData[b];
delete a.imageData[b], c.dom.remove();
});
}, b.replaceImage = function(a, c, d) {
var e = a.imageData[c];
e.src = d;
var f = g(a, c);
e.dom.replaceWith(f), e.dom = f, b.adaptSize(a);
}, b.render = function(a) {
var d = null, e = "s" + f++;
for (var h in a.imageData) {
var i = a.imageData[h];
i.dom === !1 ? (i.dom = g(a, h), i.dom.attr("data-stamp", e), d ? i.dom.insertBefore(d) :i.dom.appendTo(a.container)) :i.dom.attr("data-stamp", e), 
d = i.dom;
}
a.container.children(":not([data-stamp=" + e + "])").each(function() {
c(this).remove()[0].data.dom = !1;
}), b.adaptSize(a);
};
var g = function(a, b) {
var d = a.imageData[b], e = '<div class="imgItem collectItems couldOpen" datasrc="' + encodeURIComponent(d.link) + '" style="overflow: hidden;padding:' + a.padding / 2 + 'px;position:relative;"> <div class="check" style="position:absolute;left:8px;bottom:50%;display:none;"><i class="fa-lg fa-check-circle fa-select fa"></i></div> <div class="preload box-center" style="width:100%;height:100%;border:1px solid white;color:#999999;background: url(./images/refresh.gif) center center no-repeat"></div>' + (a.cover ? ' <div class="coverImage" style="float:left;display:none;width:80px;height:80px;"></div>' :"") + ' <img style="display:none;width: 100%;" src="' + d.src + '"/ >' + (d.title ? ' <div class="text-left" style="margin-left:20px;float:left;white-space: nowrap;overflow: hidden;padding:3px 0;font-size:90%;color:#333;width:45%">' + d.title + "</div>" :"") + "<br>" + (d.time ? ' <div class="text-left" style="margin-left:20px;float:left;white-space: nowrap;overflow: hidden;padding:3px 0;font-size:90%;color:#a1a1a1;width:45%">' + d.time + "</div>" :"") + "</div>", f = c(e);
return c.isFunction(a.tplCallback) && a.tplCallback(f, d), f[0].data = d, f.find("img").bind("load", function() {
a.cover ? (a.innerCover ? c(this).prev(".coverImage").css("background-image", "url(" + c(this).attr("src") + ");background-size: contain;background-position: center center;background-repeat: no-repeat;").show().prev(".preload").remove() :c(this).prev(".coverImage").css("background-image", "url(" + c(this).attr("src") + ");background-size: cover;background-position: center;").show().prev(".preload").remove(), 
c(this).remove()) :c(this).show().prev(".preload").remove();
}).bind("error", function() {
var b = c(this), d = c('<small style="width:100%;height: 100%">加载失败<br />点击重试</small>');
c(this).prev().prev().append(d).css("background", "none");
var e = function(d) {
a.forceSelect || (c(this).css("background", "url(./images/refresh.gif) center center no-repeat"), 
c(this).find("small").remove(), b.attr("src", b.attr("src")), d.stopPropagation());
};
d.parent().one("click", e);
}), f;
};
b.filter = function(a, d) {
var e = {};
for (var f in a.imageData) {
var g = a.imageData[f].raw, h = !0;
if (c.isFunction(d)) h = d(g); else for (var i in d) {
var j = d[i];
if (g[i] != j) {
h = !1;
break;
}
}
h && (e[f] = a.imageData[f]);
}
var k = {};
c.extend(k, a), k.imageData = e, a.num = Object.getOwnPropertyNames(e).length, b.render(k);
}, b.renderCard = function(a, c) {
var e = {};
c.forEach(function(b) {
var c = "i" + f++, g = b.thumb.replace("[HOST]", d.getHost());
g = g.indexOf("//") > -1 ? g :a.base + b.crid + "/" + g, e[c] = {
id:c,
raw:b,
src:g,
title:b.title,
dom:!1
};
}), a.imageData = e, b.render(a);
}, b.init = function(a, d, f) {
var g = {};
return d && d.forEach(function(a) {
g[a.id] = {
id:a.id,
raw:a,
src:a.thumb,
title:a.title,
time:a._date,
link:a.collecturl,
dom:!1
};
}), e.push({
base:f.base,
container:a,
imageData:g,
minWidth:f.minWidth || 100,
padding:f.padding || 32,
ratio:f.ratio,
callback:f.callback,
cover:f.cover || !1,
canSelect:f.canSelect || !1,
oneline:f.oneline || !1,
subTitle:f.subTitle || null,
tplCallback:f.tplCallback || null,
innerCover:f.innerCover || !1,
notRandom:f.notRandom || !1
}), f.render && b.render(e[e.length - 1]), a.on("touchstart", ".imgItem", function() {
var a = c(this);
a.css("background-color", "#e4e4e4");
}), a.on("touchend", ".imgItem", function() {
var a = c(this);
a.css("background-color", "#fff"), a.hasClass("couldOpen") && window.open(decodeURIComponent(a.attr("datasrc")));
}), a.on("click", ".imgItem", function(a) {
c.isFunction(f.callbackSubTitle) && c(a.target).parents("div.subTitle").length ? f.callbackSubTitle(c(this)[0].data.raw, c(this)[0].data) :c.isFunction(f.callback) && f.callback(c(this)[0].data.raw, c(this)[0].data);
}), e[e.length - 1];
};
}), define("scripts/collectService", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./vendor/promise", "./user", "./environment", "./utils", "./userview", "./tpl/login", "./tpl/register", "./tpl/userinfo", "./navibar", "./tpl/navibar", "./page", "./photoservice", "./gallery", "./collectGallery", "./tpl/help" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./vendor/promise"), f = a("./user"), g = "http://weika.mugeda.com/server/ucenter.php/ucenter/", h = a("./utils"), i = a("./environment"), j = (a("./userview"), 
{
remove:g + "removeCard",
list:g + "myCollect",
add:g + "collectCard"
});
b.getUserCollectList = function() {
return new e(function(a, b) {
var e = j.list, g = function(f) {
c.ajax({
url:e,
data:f,
xhrFields:{
withCredentials:!0
},
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function(c) {
d.hideLoading(), 0 === c.status ? a(c.data) :l(function() {
b("获取用户收藏列表失败");
});
},
error:function() {
d.hideLoading(), l(function() {
b("获取用户收藏列表失败");
});
}
});
}, k = function() {
d.showLoading("正在加载"), i.isApp2() ? f.getUrid().then(function(a) {
g({
urid:a,
t:+new Date()
});
}, function() {
d.hideLoading(), d.showConfirm("您需要先登录才能查看保存的收藏，是否现在登录？", !0, {
labelConfirm:"立即登录",
labelCancel:"下次再说",
confirm:function() {
c(document.body).trigger("user:login", {
callback:function() {
k();
}
});
},
cancel:function() {
b("用户没有登录");
}
});
}) :i.isWeixin && g({
token:h.cookie.get("token"),
t:+new Date()
});
};
k();
var l = function(a) {
d.showConfirm("获取用户收藏列表失败，是否重试？", !0, {
labelConfirm:"重试",
confirm:function() {
k();
},
cancel:function() {
a();
}
});
};
});
}, b.add = function() {
return new e(function(a, b) {
var e = j.add, g = {}, k = function() {
window.open("http://mp.weixin.qq.com/s?__biz=MzA3MzMwMTgwNQ==&mid=202908196&idx=1&sn=8bb6504461a6cdf7ce809b6ac6bbf9a8#rd ");
}, l = function(f) {
c.ajax({
url:e,
data:f,
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function(e) {
return d.hideLoading(), 0 == e.isfollowed ? (d.showConfirm("关注我们才能收藏，要现在去关注吗？", !0, {
confirm:k,
labelConfirm:"去关注",
labelCancel:"下次再说"
}), void b()) :void (0 === e.status ? ("Ok" != e.error ? "favorited" === e.error ? d.showConfirm("已经收藏过此卡", !1, {
labelConfirm:"我知道了"
}) :n(function() {
d.showConfirm("收藏失败，请稍后重试"), b();
}) :d.showConfirm("收藏成功！\n\n可在微卡列表底部个人中心 > 我的收藏中查看。", !0, {
labelConfirm:"我知道了",
labelCancel:"查看收藏",
cancel:function() {
c(document.body).trigger("user:collect", {
viewMode:"view"
});
}
}), a()) :n(function() {
d.showConfirm("收藏失败，请稍后重试"), b();
}));
},
error:function() {
d.hideLoading(), n(function() {
d.showConfirm("收藏出错，请稍后重试"), b();
});
},
fail:function() {
d.hideLoading(), n(function() {
d.showConfirm("收藏失败，请稍后重试"), b();
});
}
});
};
if (i.isApp2()) f.getUrid().then(function(a) {
g = {
t:+new Date(),
crid:h.getCrid(),
url:encodeURIComponent(location.href),
urid:a,
redirect:encodeURIComponent(location.href),
collecturl:encodeURIComponent(location.href),
isWeixin:i.isWeixin()
}, l(g);
}, function() {
d.hideLoading(), d.showConfirm("您需要先登录才能保存收藏，是否现在登录？", !0, {
labelConfirm:"立即登录",
labelCancel:"下次再说",
confirm:function() {
c(document.body).trigger("user:login", {
callback:function(a) {
a || d.showMessage("收藏失败，请稍后重试"), f.getUrid().then(function(a) {
g = {
t:+new Date(),
crid:h.getCrid(),
url:encodeURIComponent(location.href),
urid:a,
redirect:encodeURIComponent(location.href),
collecturl:encodeURIComponent(location.href),
isWeixin:i.isWeixin()
}, l(g);
});
}
});
},
cancel:function() {
b("用户没有登录");
}
});
}); else if (i.isWeixin()) {
var m = location.href;
h.getParam("successed") && (m = h.removeParam("successed", m)), h.getParam("token") && (m = h.removeParam("token", m)), 
m = m.replace("#wechat_redirect", ""), g = {
t:+new Date(),
crid:h.getCrid(),
url:encodeURIComponent(m),
token:h.cookie.get("token"),
redirect:encodeURIComponent(m),
collecturl:encodeURIComponent(m),
isWeixin:i.isWeixin()
}, g.token ? l(g) :window.location.href = e + "?crid=" + g.crid + "&url=" + g.url + "&redirect=" + g.redirect + "&collecturl=" + g.collecturl + "&isWeixin=" + g.isWeixin + "&t=" + g.t;
}
var n = function(a) {
d.showConfirm("收藏失败，是否重试？", !0, {
labelConfirm:"重试",
confirm:function() {
l(g);
},
cancel:function() {
a();
}
});
};
});
}, b.deleteCollect = function(a) {
return new e(function(b, d) {
var e = j.remove;
i.isApp2() ? f.getUrid().then(function(f) {
c.ajax({
url:e,
data:{
collectid:a,
urid:f
},
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function(a) {
0 == a.status ? b() :d();
},
error:function() {
d();
}
});
}) :i.isWeixin() && c.ajax({
url:e,
data:{
collectid:a,
token:h.cookie.get("token")
},
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
success:function() {
b();
},
error:function() {
d();
}
});
});
};
}), define("scripts/custom", [ "./environment", "./vendor/zepto", "./tpl/customForm", "./tpl/template", "./utils", "./message", "./tpl/loading", "./tpl/dialog", "./vendor/promise", "./promo", "./tpl/promoHtml" ], function(a, b) {
var c = a("./environment"), d = a("./tpl/customForm"), e = a("./utils"), f = a("./vendor/promise"), g = a("./message"), h = a("./promo"), i = 1024, j = null;
b.saveCardParam = function(a) {
j = a;
}, b.getCardParam = function() {
return j || {};
};
var k = null;
b.setLastFormData = function(a) {
k = a;
}, b.activeCustom = function(a) {
var b = function(a) {
a = a.split("/");
for (var b = 0; b < a.length; b++) a[b] = b < a.length - 1 ? "." + a[b] + " .layer" :"." + a[b];
a = a.join(" ");
try {
return $(".MugedaStage " + a);
} catch (c) {
return $();
}
};
if (e.isCssMode()) {
if (window.data = window.data || {}, window._mrmcp && _mrmcp.customInfo && _mrmcp.customInfo.images) {
var c = _mrmcp.customInfo.images;
c.forEach(function(a) {
var c = b(a.name).css("background-image").match(/url\('*(.+?)'*\)/);
c = 2 == c.length ? c[1] :"", data[a.name] = {
obj:[ {
cardRefParam:a.type,
group:a.group,
src:c
} ]
};
});
}
for (var d in a) if (a.hasOwnProperty(d)) {
var f = a[d], g = b(d), h = window.data[d] || {};
if (h.defined = !0, h.obj && h.obj[0]) {
var i = h.obj[0].cardRefParam;
if ("image" == i || "signature" == i || "masked" == i) {
try {
f = JSON.parse(f);
} catch (j) {
f = {
u:f
};
}
if (h.obj[0].oriSrc = h.obj[0].src, h.obj[0].srcUserImg = f.u, h.obj[0].src = f.u, 
new Image().src = f.u, g.css("background-image", "url('" + f.u + "')"), "signature" == i) {
var k = parseInt(g.css("width")) / 2 + "px", l = parseInt(g.css("height")) / 2 + "px";
g.css("border-top-left-radius", k + " " + l), g.css("border-top-right-radius", k + " " + l), 
g.css("border-bottom-right-radius", k + " " + l), g.css("border-bottom-left-radius", k + " " + l);
}
}
} else g.children().html(f);
}
for (var m in a) if (a.hasOwnProperty(m)) {
for (var d in window.data) if (window.data.hasOwnProperty(d) && !window.data[d].defined) {
var g = b(window.data[d].obj[0].group);
g && g.hide();
}
break;
}
}
}, b.bindWeiParamater = function() {
if (e.isCssMode()) {
var a = _mrmcp.metadata;
if (!a) return;
var b = a && a.weixinTitle ? a.weixinTitle :"木疙瘩贺卡", c = a && a.weixinDesc ? a.weixinDesc :"请定义贺卡描述", d = _mrmcp.creative_path + a.thumb;
return b = J(b), c = J(c), b = b.replace(/\{\{[a-zA-Z0-9]+\}\}/g, ""), c = c.replace(/\{\{[a-zA-Z0-9]+\}\}/g, ""), 
b = K(b), c = K(c), {
mtitle:b,
mdesc:c,
thumb:d
};
}
}, b.defineWeixinBridge = function(a) {
e.isCssMode() && s(a);
}, b.exportCustomFunction = function(a) {
b.getFormHTML(a), c.isPublic() && (cardFrame.getFormHTML = function() {
return b.getFormHTML(a);
}, cardFrame.customConfirm = b.customConfirm);
}, b.getFormHTML = function(a) {
var b = v();
return b ? (u(b, a || {}), d(b)) :null;
}, b.hookMugedaCard = function(a) {
r(window, "activateCustomParameters", function(b) {
MugedaCard && MugedaCard.data ? q(a) :b();
}), r(window, "MugedaCard", null, function(a) {
r(a, "showAudioControl", function(a, b) {
t.apply(this, b);
}, function(a) {
window.showAudioControl = a;
}), r(a, "finalizeCustomParameters", function(a, b) {
m.apply(this, b);
}, function() {
window.finalizeCustomParameters = m;
}), r(a, "defineCustomParameters", function(a, c) {
b.defineCustomParametersV2.apply(this, c);
}, function() {
window.defineCustomParameters = b.defineCustomParametersV2;
}), r(a, "defineWechatParameters", function() {
b.defineWechatParameters.apply(this.args);
}, function() {
window.defineWechatParameters = b.defineWechatParameters;
});
});
var c = !1;
r(window, "bindWeiEvent", function() {
c || (s(window.weiParam), c = !0);
});
};
var l = /[\x00-\x1F%\/\.#\?;:$,\+@&=\{\}\|\\^~\[\]'\<\>"]/g;
b.encodeURL = function(a) {
return a.replace(l, function(a) {
return encodeURIComponent(a);
});
};
var m = function(a, c, d) {
window.customImage(a, c, function(a, d) {
d = d || {};
var f = new MugedaUrl("http://a.com/?" + a), g = f.getQueryObj();
for (var h in g) if (g.hasOwnProperty(h)) {
var i = decodeURIComponent(g[h]);
delete g[h], h = decodeURIComponent(h), g[b.encodeURL(h)] = b.encodeURL(i);
}
g._v = "2";
var j = b.getCardParam().bizData;
for (var h in g) g.hasOwnProperty(h) && j && j[h] && delete g[h];
$(document).trigger("custom:setCustomObj", g);
var k = encodeURIComponent(e.base64.encode(f.getQuerystring()));
MugedaUrl.current.getQueryObj().custom = k, delete MugedaUrl.current.getQueryObj().m_profile, 
j && (MugedaUrl.current.getQueryObj().bizId = b.getCardParam().bizId), delete MugedaUrl.current.getQueryObj().customId;
var l = MugedaUrl.current, m = l.getURL();
if (d.receiptCard) {
var o = encodeURIComponent(m), p = d["public"];
l = new MugedaUrl("https://weika.mugeda.com/card/invite_card.php/custom");
var q = l.getQueryObj();
q.redirect = o, q.public = p ? 0 :1, q.crid = e.getCrid(), m = l.getURL();
}
if (d.callback) m = d.callback(m), m && n(m, c); else {
var r = {
url:m,
cancel:!1,
callback:function() {
m = r.url, n(m, c);
}
};
if ($(document.body).trigger("custom:beforeNavigate", r), r.cancel) return void (c.cancel = !0);
m = r.url, n(m, c);
}
}, d);
}, n = function(a, c) {
var d = new MugedaUrl(a), f = e.base64.decode(decodeURIComponent(d.getQueryObj().custom)), g = new MugedaUrl("http://a.com/?" + f).getQueryObj();
if (a.length > i || g.m_usep) {
var h = g.m_profile ? g.m_profile :"";
delete g.m_profile;
var j = g.m_usep ? 1 * g.m_usep :0;
delete g.m_usep;
var k = {};
for (key in g) g.hasOwnProperty(key) && (k[decodeURIComponent(key)] = g[key]);
delete k._v, k = b.encodeURL(JSON.stringify(k));
var l = a.length > i ? 1 :0;
l && delete d.getQueryObj().custom, delete g.m_usep;
var m = {
m_usep:j,
m_useid:l,
m_profile:h,
crid:e.getCrid(),
url:b.encodeURL(d.getURL())
};
l && (m.custom = k);
var n = "http://weika.mugeda.com/server/card_custom.php/open", p = {
data:m,
url:n,
goOn:!0,
server:!0
};
$(document).trigger("custom:beforeCustom", p), m = p.data;
var q = '<form action="' + p.url + '" method="post">';
for (var r in m) m.hasOwnProperty(r) && (q += '<input type="hidden" name="' + r + '" value="' + m[r] + '"/>');
q += "</form>";
var s = $(q);
o(c, function() {
p.goOn && s.submit();
});
} else o(c, function() {
var b = {
url:a,
goOn:!0
};
$(document).trigger("custom:beforeCustom", b), b.goOn && window.open(a, "_self");
});
}, o = function(a, b) {
window.alert = function() {}, a.cancel = !0, k.msg ? g.showConfirm(k.msg, !1, {
confirm:function() {
b(), a.callback();
}
}) :(b(), a.callback());
};
b.defineWechatParameters = function(a) {
window.weiParam || (window.weiParam = {});
for (var b in a) weiParam[b] = a[b];
weiParam.success_share_callback_report = function(a) {
window.rpWX && window.rpWX("share", a), (weiParam.success_share_callback || function() {})(a);
var b = MugedaUrl.current.getQueryObj().m_bizId;
cardFrame.promotionDisabled || b || h.showPromo(e.getCrid()), $(document.body).trigger("weixin:shareOK");
}, weiParam.defined = !0;
try {
bindWeiEvent();
} catch (c) {}
}, b.finalizeCustomParametersV2 = m, b.defineCustomParametersV2 = function(a, b) {
window.MugedaCard || (window.MugedaCard = {}), MugedaCard.data = data = MugedaCard.data || {};
for (var c = 0; c < b.length; c++) for (var d = b[c], e = d.formName || new Date().getTime() + "" + MugedaCard.sum++, f = d.formDescription, g = d.mugedaObj, h = d.userUndefined, i = data[e] = {
des:f,
userUndefined:h,
obj:(data[e] ? data[e].obj :[]) || []
}, j = 0; j < g.length; j++) {
var k = g[j].name, l = g[j].attribute;
if ("receipt" == l) i.type = l; else if ("data" != l) {
for (var m = k.split("/"), n = {
scene:a
}, o = 0; o < m.length; o++) {
if (!n.scene) throw "getObjectByName error!";
n = n.scene.getObjectByName(m[o]);
}
n && (n.cardRefParam = l, i.obj.push(n));
}
}
Mugeda.getMugedaObject().evt && (Mugeda.getMugedaObject().evt.stopLoad = !0), activateCustomParameters();
};
var p = function(a) {
return new f(function(b, c) {
var d = new Image();
d.src = a, d.onload = function() {
b(d);
}, d.onerror = function() {
c(d);
};
});
}, q = function(a) {
var b = function() {
for (var a in MugedaCard.data) if (MugedaCard.data.hasOwnProperty(a)) {
var b = MugedaCard.data[a];
!b.handled && b.userUndefined && b.userUndefined();
}
}, c = 0;
for (var d in a) if (a.hasOwnProperty(d) && 0 != d.indexOf("_")) {
var e = MugedaCard.data[d];
if (null == e && "string" == typeof d && d.length >= 5 && 0 == d.indexOf("form")) {
var g = d.replace("form", "");
g = g.substr(0, 1).toLowerCase() + g.substr(1), e = MugedaCard.data[g];
}
if (!e) continue;
e.handled = !0, e.value = a[d];
for (var h = 0; h < e.obj.length; h++) {
var i = e.obj[h], j = i.cardRefParam;
if ("image" == j || "signature" == j || "masked" == j) {
c++;
try {
var k = JSON.parse(a[d]);
} catch (l) {
k = {
u:a[d],
l:0,
t:0,
w:0,
h:0
};
}
!function(a, b) {
f.all([ p(b.u, i) ]).then(function(d) {
var e = d[0], f = a.dom, g = a.width, h = a.height, i = e.width, j = e.height, k = b.w || i, l = b.h || j, m = g / k, n = h / l, o = i * m, p = j * n, q = b.l * m, r = b.t * n, s = document.createElement("div");
a.dom.parentElement.replaceChild(s, a.dom), a.oriSrc = a.src, s.className = f.className, 
s.style.cssText = f.style.cssText, "image" == a.cardRefParam || "masked" == a.cardRefParam ? (s.appendChild(f), 
f.setAttribute("style", null), f.className = "", f.style.width = o + "px", f.style.height = p + "px", 
f.style.clip = "rect(" + r + "px " + (q + k * m) + "px " + (r + l * n) + "px " + q + "px)", 
f.style.position = "absolute", f.style.marginLeft = -q + "px", f.style.marginTop = -r + "px", 
a.src = e.src, a.srcUserImg = e.src) :"signature" == a.cardRefParam && (a.srcUserImg = e.src, 
s.style.cssText += "background-image: url(" + e.src + ");background-size:" + o + "px " + p + "px;background-position: " + -q + "px " + -r + "px;"), 
a.dom = s, 0 == --c && Mugeda.getMugedaObject().evt && Mugeda.getMugedaObject().evt.goOnLoad();
}, function() {
0 == --c && Mugeda.getMugedaObject().evt && Mugeda.getMugedaObject().evt.goOnLoad();
});
}(i, k);
} else i[i.cardRefParam] = a[d];
}
}
b(), Mugeda.getMugedaObject().evt && 0 == c && Mugeda.getMugedaObject().evt.goOnLoad();
};
b.activateCustomParametersV2 = q;
var r = function(a, b, c, d) {
var e = null;
Object.defineProperty(a, b, {
set:function(a) {
e = a, $.isFunction(d) && d(a);
},
get:function() {
return $.isFunction(c) ? function() {
var a = this, b = arguments, d = function() {
$.isFunction(e) && e.apply(a, b);
};
c(d, b);
} :e;
}
});
}, s = function(a) {
if (c.isApp() && window.mucard && mucard.share) {
var b = a.img_url || a.thumb || "http://cdn-cn.mugeda.com/weixin/card/i/card_logo_default.jpg", d = a.fdesc || a.desc || a.mdesc || "请定义贺卡描述", e = a.ftitle || a.title || a.mtitle || "木疙瘩贺卡";
mucard.share(e, d, b);
}
window.WeixinJSBridge && WeixinJSBridge._hasInit !== !1 ? [ {
event:"menu:share:appmessage",
action:"sendAppMessage",
mes1:"send_app_msg:confirm",
mes2:"send_app_msg:ok",
cb:"send",
ac:"转发"
}, {
event:"menu:share:timeline",
action:"shareTimeline",
mes1:"share_timeline:confirm",
mes2:"share_timeline:ok",
cb:"share",
ac:"分享"
} ].forEach(function(b) {
window.WeixinJSBridge.on(b.event, function() {
window.WeixinJSBridge.invoke(b.action, {
appid:"wx75babd529e23776c",
img_url:a.img_url || a.thumb || "http://cdn-cn.mugeda.com/weixin/card/i/card_logo_default.jpg",
img_width:128,
img_height:128,
link:$.isFunction(a.url_callback) ? a.url_callback() :H(),
desc:a.fdesc || a.desc || a.mdesc || "请定义贺卡描述",
title:a.ftitle || a.title || a.mtitle || "木疙瘩贺卡"
}, function(c) {
(c.err_msg == b.mes1 || c.err_msg == b.mes2) && ((a.success_share_callback_report || function() {})(b.cb), 
MugedaTracker && MugedaTracker.fireEvent({
category:"微卡",
action:b.ac,
label:"i=" + I() + "&t=" + new Date().getTime(),
value:0
}));
});
});
}) :document.addEventListener("WeixinJSBridgeReady", function() {
s(a);
});
}, t = function(a, c, d) {
var e = a.getObjectByName(c);
if (b.getBackgroundAudio = e ? e :"", e.src) {
var f = e.play;
e.play = function() {
e.muted = !1, f.call(e), m("load"), setTimeout(function() {
"load" == l && m("stop");
}, 3e3), e._playing = !0;
};
var g = e.pause;
e.pause = function() {
e._playing = !1, e.muted = !0, g.call(e);
};
var h = function(b, c, d) {
var e = new MugedaCss3.aObject({
guid:Mugeda.guidGen(),
type:2005,
param:{
imageSrc:d || "",
rawWidth:b || 32,
rawHeight:c || 25,
left:0,
right:b,
top:0,
bottom:c,
scaleX:1,
scaleY:1,
rotate:0,
lineWidth:1,
alpha:1,
width:b,
height:c
}
});
return a.appendChild(e), e.width = b, e.height = c, e.top = -80, e.left = 275, e;
}, i = h(36, 22, "close_button.png");
i.dom.addEventListener("load", function() {
i.setSrc ? (i.top = 8, i.dom.style.cssText += "position: absolute; left:0; top: 0", 
i.dom.setAttribute("data-audio-icon", 1), l && m(l)) :(i.setSrc = !0, i.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAWCAYAAACosj4+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6OEQxQjlEMkZBMzc0MTFFM0E3NEI4OTcyREFGRTI1QUUiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6OEQxQjlEMzBBMzc0MTFFM0E3NEI4OTcyREFGRTI1QUUiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo4RDFCOUQyREEzNzQxMUUzQTc0Qjg5NzJEQUZFMjVBRSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo4RDFCOUQyRUEzNzQxMUUzQTc0Qjg5NzJEQUZFMjVBRSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pvx6Om4AAASDSURBVHjazFZ/SFtXFD4v5uWHUZfGH5k2plZni40JiXMJOusPlK5GXXXTLXSiTnG1rBQZKEREKCrYIUr1j41SWlhXHK6bMhGF0daxdVhZlQ1di1rXGLRuJDqfiTE2yd151konCnuVlh348l7evffxve+c+91DEULg/xS86Oho2I76+vqtCRaLBXJzc6G2tvYtp9N5DR+9/9JZlpeXA6vc7OwsKBQKnsFgOMMwjIs8CQYRxo6/CPB2IiSVSmFxcRFSU1NBLpdXDQwMtAYGBoo2h2lEwEtVKD8/HzQaDWi12kqbzbZK/h1ORJRUKISKTSXVWi1ERESkoIpjPkL6KsrKJMdTUiAnPX0De1aot7cX3G53yeDgYFtwcLB4+/ja2trGlXpaiCIRzM/P5w7dvavFZ7lFpaVNS04nsLNcHg8nMfhbN3w+YFrEPp/PDws7v6+vrwPTtWNqlpaWKPwcnoOQIvz7SbVafXN8ePh176lTYDeZ4JjJVBWand3nunXrphiV4hzh4eHRIyMjPZieBwsLC384HA432T2cdrs9CpfFHcvM7Geqq4lVJCL3KYoZBli+A+C5r1CQeb1+6E+jUcQ0NnJKGRsH8vLyviT/PZ7U0L59kY1C4Z05iiKOwkLy+/nz/WqpNOMowHc/A5BfEVM6XYetvZ3HtYZewzQd5qrq12Vlke+53fE/SSRuy7lz67G1tfFhev1DlG5ZTlHAw6JfGRv7kC+XGzgZI8LveXZiltdrwnIVtjgc339z794sFqPyUkDA1RaAYk9Ojie8vR18aA9r09NHuRIim+AU9pmZGBfAX+MAP1htthXS1QWBAwNv9gJMf67X/xZcXLzxpR67XcaV0POFTDYjxN8MPv+wrLOTdpw8CdMuV38DwNkZn4+B0VF4zLpoWJiNMyHc8hRXPiKNpltM06Te4yk+MTFxiGRnLw8pFGftfn6Tb/D5+1cuXGB9yiGMibnNmdDk5OQaHpycCD3w+X78JTT0Uhhye1UiEbh1uql1gUD8ttdbknTx4sFHPT0QpFJdWR0ZGebyXlaZWMRHOp1Oq1ar/T0ej1ClUoXW1dUpd1mzilAJaNqSkZT08fXExE4vOjszNweO9XUGC1kiVCr9BLGxo+iymeKoqL8DL18GripFIrQIDeIE4guz2fxwNx9CA2WNUVNgMt1eYBjftzU1VxspqqYjIGB4wWwmzPg4udbc/MEguvSN0lLOxrg92M1hRHQ1NDRYdiKE59ZBmqY1/iJRS0ll5ZlDBgNbiPRn3d2D7ISJqakbxwsK+O+ePg3vVFTsmdDTFiMP8VVTU5N1G6FVPFyjsEVhlRVIZTLYr1TSISEhnewgkp1NTk6OiTtyBOLV6g3smVBRURFkZWUJ8LaANeW2tra5Zwg9RhwICgp6dskriOb09PQrRqNRu/19eyaE7Sq4XC6Ii4tjm7JCxPXW1tZH2JIQq9XagwsF/v7+IJFIICEhAXAz8BDBrIOgUiDEYwNTuoU9E2JfmpaWxravG5bD9myIT7EtqUJlJImJifCiWth/BBgAGnzh/Lti8S4AAAAASUVORK5CYII=");
});
var j = h(36, 22, "close_button.png");
j.dom.addEventListener("load", function() {
j.setSrc ? (j.top = 8, j.dom.style.cssText += "position: absolute; left:0; top: 0", 
j.dom.setAttribute("data-audio-icon", 1), l && m(l)) :(j.setSrc = !0, j.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACQAAAAWCAYAAACosj4+AAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoV2luZG93cykiIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6RERCNkFBMzBBMzc0MTFFMzk4NzRENEEwRUIxQjUzN0UiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6RERCNkFBMzFBMzc0MTFFMzk4NzRENEEwRUIxQjUzN0UiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDpEREI2QUEyRUEzNzQxMUUzOTg3NEQ0QTBFQjFCNTM3RSIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDpEREI2QUEyRkEzNzQxMUUzOTg3NEQ0QTBFQjFCNTM3RSIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/Pt3X9zcAAAP1SURBVHjazFZZSFtZGD5JTDSLa7TimLaOZdwNURRH3FuhjAs6qDMg0YIiVGgt+CAIKiIKZer41AffiqHFSgsVClUQX4TU0bFYHAaKQce4JOLGRE1icLn9/lstrVTaXKL0h+/ee8659/Dd7/ybiOM49j2ZODIykp1GS0vLxxfMZjMrKipiTU1NN+12+xNM/X7hLGtqahgpt7i4yDQajTgtLe3O9va2k/tg28AlWj8PiL9EKCAggG1tbbHs7GwWGhp6e2hoqNvX19fneFkKqC5UodLSUqbVaplOp6vb2NhwcJ+bHYgICgpitbW1/F8lJCSw8PDwDKg4jfHL6upqZWJiIktOTvaMQoODg8zlclUPDw/3qNVq+en1vb09/i4Sifi7l5eXbGVlRT81NaXDsKisrKxzZ2fn47ogw6YsMDBQ7u/vr0pKStIvLy//z33Z7BaL5UcoJNbr9ZUY/9vR0XGLiMTExLwGMXrH1djYeD06OtpthXgLCwuLnJycfIHjmVtdXf1vd3fXxZ1t9s3NzQj8fWJeXt4IlOQODw+tOOafsVVtfX29mV4aGxt7FhcXJ4jQ1eLi4sfctxvvQ/juCvBna2urhSaNRuNDkIxSqVQD+ClufX3dYjAYooUQulFYWPi3u4RCQkKYQqFIxv2VyWSi+Y309PR47Hevt7d3F6pxs7OzvwlxaokQn6OUEBUV9RZKGEdHRx2YUldVVZFyMxMTExaxWMxAVuO2LwPcMdyy6elp5nA4jvD4zmq1EiFFVlZWEIJjwWaz7RznswAhhATZ/Pz8yeM+IpOPbz8/P5tMJjuUSqX8+OjoyElKuVXLjkPe7YQBf6HoZCDwQ2pqKilxMDMzYwIJZXx8fDB8iLK91e3iShc43x4Kp1sf+vj4sIODAwUiND8zM5P8cKS9vd2EpBlTUlKiwXHaxsfH/xGi/k/AAyTDEaR8Y2Vl5VRXV5f5a1GGo5CkpKTUQQV+cmBg4BfaLCcnx0BjZO2xjIwMkZCwJ5UuA5T2tUAJYGhubl44ixASKEWTtry8/K+1tTWur6/vAfyGyeXy6yDCM2xoaKiizQVl6lNG8hcA/W1tbeazSgcIaEHgPlS9i5LBJBJJeH9//xt6YW5ubhTFVeIpQictRjHwtLOzc+kUIQf8JAIRTcrKKLJR6aXIOQ9pEWQXkaOuoY8ikp4hVFFRwfLz82V4/BV41tPTs/IJoX3gKkL800/8ga7c3NxHBQUFupNJRKBnCKFdZU6nk8XGxlJTVg487+7utlIhXVpaeoEPZSgbTKlU8j0PjkcMqCmDBAcHM29vb0Y+RfAIIdoU0ULtKx/h1LMBf6Dfvg1llIgudl4t7HsBBgCzULCMcQhmvwAAAABJRU5ErkJggg==");
});
var k = h(36, 22, "close_button.png");
k.dom.addEventListener("load", function() {
k.setSrc ? (k.top = 8, k.dom.style.cssText += "position: absolute; left:0; top: 0; width: 20px; height: 20px", 
k.dom.setAttribute("data-audio-icon", 1), l && m(l)) :(k.setSrc = !0, k.src = "images/audioloading.gif");
});
var l = null, m = function(a) {
switch (a) {
case "stop":
d ? (j.visible = !1, i.visible = !1, k.visible = !1) :(j.visible = !1, i.visible = !0, 
k.visible = !1);
break;

case "play":
j.visible = !0, i.visible = !1, k.visible = !1, l != a && (e.playTimeout && clearTimeout(e.playTimeout), 
e.playTimeout = setTimeout(function() {
e.pause();
}, 9e4));
break;

case "load":
j.visible = !1, i.visible = !1, k.visible = !0;
}
l = a;
}, n = function() {
k.visible || (i.visible ? e.play() :e.pause());
};
i.addEventListener("inputend", n), j.addEventListener("inputend", n), m("stop");
var o = function() {
var a = e.currentTime, b = function() {
e.currentTime != a ? c() :setTimeout(b, 100);
};
setTimeout(b, 10);
var c = function() {
m("play"), e._playing = !0;
};
};
e.addEventListener("playing", function() {
o();
}), e.autoplay && o(), e.addEventListener("pause", function() {
m("stop");
});
}
}, u = function(a, b) {
$.isArray(a.items) && a.items.forEach(function(a) {
b.hasOwnProperty(a.id) && (a.valueCustom = b[a.id]);
});
}, v = function() {
var a = [];
if (e.isCssMode()) {
var b = $(".定制");
b.each(function() {
var b = $(this).attr("onclick"), c = w(b);
c && a.push(c);
});
} else if (window.Mugeda && Mugeda.getMugedaObject) {
var c = Mugeda.getMugedaObject();
if (c && c.scene) {
var d = c.scene, f = d.getObjectByName("定制");
if (f && f.param && "form" == f.param.action && f.param.form) {
try {
var g = JSON.parse(f.param.form);
} catch (h) {}
g && a.push(g);
} else {
var i = z(c.aniData);
i.forEach(function(b) {
try {
var c = JSON.parse(b.param.form);
} catch (d) {}
c && a.push(c);
});
}
}
}
var j = null;
if (1 == a.length && (j = a[0]), a.length > 1) for (var k = 0; k < a.length; k++) if (a.items && $.isArray(a.items) && a.some(function(a) {
return a.description.indexOf("收卡人") > -1;
})) {
j = a[k];
break;
}
return j;
}, w = function(a) {
var b = new Function("MugedaBehavior", a), c = null, d = {
popupForm:function(a) {
c = a;
}
};
return b(d), c;
}, x = {}, y = {}, z = function(a) {
x = {}, y = {}, A(a);
var b = [];
return B(a.layers, {
action:"form"
}, b), b;
}, A = function(a) {
x = function(a) {
var b = {};
return (a.symbols || []).forEach(function(a) {
b[a.id] = a;
}), b;
}(a), y = {};
}, B = function(a, b, c) {
a.forEach(function(a) {
a.units && C(a.units, b, c);
});
}, C = function(a, b, c) {
a.forEach(function(a) {
a.objects && D(a.objects, b, c);
});
}, D = function(a, b, c) {
a.forEach(function(a) {
F(a, b, c);
});
}, E = function(a, b) {
var c = x[a];
if (c.layers) {
var d = [];
return B(c.layers, b, d), d;
}
}, F = function(a, b, c) {
if (a.param) {
for (var d in b) b.hasOwnProperty(d) && a.param[d] == b[d] && c.push(a);
if (2014 == a.type && a.items.forEach(function(a) {
F(a, b, c);
}), 2021 == a.type) {
var e = a.param.symbolId;
c = c.concat(E(e, b));
}
}
};
b.customConfirm = function(a, b) {
MugedaUrl.current.getQueryObj().customId = a, b && (MugedaUrl.current.getQueryObj().m_bizId = b), 
location.href = MugedaUrl.current.getURL();
}, b.activeCustomId = function(a) {
if (a = a || {}, MugedaCard.data) for (var b in a) if (a.hasOwnProperty(b)) {
var c = a[b], d = MugedaCard.data[b];
if ("_" == b.substr(0, 1)) continue;
if (null == d && "string" == typeof b && b.length >= 5 && 0 == b.indexOf("form")) {
var e = b.replace("form", ""), e = e.substr(0, 1).toLowerCase() + e.substr(1);
d = MugedaCard.data[e];
}
if (!d) continue;
d.handled = !0, d.value = c;
for (var f = 0; f < d.obj.length; f++) {
var g = d.obj[f];
"image" == g.cardRefParam || "signature" == g.cardRefParam || "masked" === g.cardRefParam || (g[g.cardRefParam] = c);
}
}
}, b.addFootToCustom = function(a, b, c) {
b = encodeURIComponent(e.escapeHTML(b)), c = encodeURIComponent(c), a.push("_footcontent=" + b), 
a.push("_footurl=" + c);
}, b.addCustomProfile = function(a, b, c, d) {
c = encodeURIComponent(c), b && (a.push("m_usep=" + (b ? 1 :0)), a.push("m_slogan=" + c), 
a.push("m_profile=" + d));
};
var G = [ "custom", "customId", "crid", "audio", "hash", "s", "plug" ];
b.appendUrlParamList = function(a) {
G.push(a);
};
var H = cardFrame.getWeixinSendUrl = function() {
var a = new MugedaUrl(location.href), b = a.getQueryObj();
for (var c in b) b.hasOwnProperty(c) && (G.indexOf(c) > -1 || 0 == c.indexOf("m_") || delete b[c]);
b.s = 1 * (isNaN(b.s) ? 0 :b.s) + 1, b.t = I();
var d = a.getURL(), e = {
url:d
};
return $(document).trigger("custom:forwardingURL", e), e.url;
}, I = function() {
var a = 0;
return window._gaq && window._gaq.push && _gaq.push(function() {
var b = _gat._getTrackers()[0];
a = b._visitCode();
}), a;
}, J = function(a) {
if (e.isCssMode()) {
var b = new RegExp("\\{\\{(.*)\\}\\}", "g");
return a.replace(b, function(a, b) {
var c = $("." + b);
return c.children().html();
});
}
}, K = function(a) {
for (var b = " 　，。／；‘」、｀,.;[]`", c = 0, d = a.length; d > c; c++) {
var e = a[c];
if (-1 === b.indexOf(e)) break;
}
return a.substr(c);
};
}), define("scripts/customSound", [ "./message", "./vendor/zepto", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./utils", "./environment", "./vendor/promise", "./user", "./photoservice", "./page", "./navibar", "./tpl/navibar" ], function(a, b) {
var c = a("./message"), d = a("./utils"), e = (a("./user"), a("./photoservice")), f = a("./vendor/promise");
b.audio = null, b.isCustSound = !1, b.addAudio = function(a) {
return new f(function(d, f) {
var g = function(a, g, h) {
switch (a) {
case 0:
e.uploadAudioBase64(h, g).then(function(a) {
b.audioPath = a, b.isCustSound = !0, d();
}, function(a) {
f(a);
});
break;

case 1:
c.showMessage("没有音频数据");
}
}, h = function() {
$(document.body).trigger("user:audio", {
viewMode:"select",
callback:function(a) {
a ? (b.audioPath = a.audioUrl, b.isCustSound = !0, d()) :f("没有选中语音");
},
type:"audio"
});
};
window.mucard ? mucard.useCustomAudio(g, h, a, !0) :f("当前版本的APP不支持定制声音。");
});
}, b.soundPlay = function(a, c, e) {
var f = !1, g = 0, h = 0, i = !1, j = a.find(".playing"), k = a.find(".still"), l = document.createElement("audio");
l.src = c, l.addEventListener("ended", function() {
f = !1, j.hide(), k.show(), e && (e.volume = 1);
});
var m = function() {
n && setTimeout(m, 1e3 / 12), null != o && a.css("left", o + 70 + "px"), null != p && a.css("top", p + 19 + "px");
}, n = !1, o = null, p = null, q = function(b) {
i = !0;
var c = b.touches[0].clientX - g, e = b.touches[0].clientY - h;
return 0 > c ? c = 0 :c > d.windowSize.width - a[0].offsetWidth && (c = d.windowSize.width - a[0].offsetWidth), 
0 > e ? e = 0 :e > d.windowSize.height - a[0].offsetHeight && (e = d.windowSize.height - a[0].offsetHeight), 
p = e, o = c, b.preventDefault(), !1;
}, r = function() {
i = !1, b.left = a.position().left, b.top = a.position().top, document.removeEventListener("touchmove", q, !1), 
document.removeEventListener("touchend", r, !1);
};
a.bind("touchend", function() {
a.removeClass("moving"), n = !1, i || (f ? l.ended || (j.hide(), k.show(), l.pause(), 
f = !1, e && (e.volume = 1)) :(f = !0, j.show(), k.hide(), l.currentTime > 0 && (l.currentTime = 0), 
l.play(), e && (e.volume = .3)));
}, !1), a.bind("touchstart", function(b) {
g = b.touches[0].clientX - this.offsetLeft, h = b.touches[0].clientY - this.offsetTop, 
a.addClass("moving"), document.addEventListener("touchmove", q, !1), document.addEventListener("touchend", r, !1), 
n = !0, setTimeout(m, 1e3 / 12);
}, !1);
}, b.audioPath = null, b.left = 0, b.top = 0, b.ended = !1, b.init = function() {
b.audio = null == b.audio ? document.createElement("audio") :b.audio, b.audio.src = b.audioPath;
}, b.playAudio = function() {
b.audio.src !== b.audioPath && (b.audio.src = b.audioPath), b.audio.play();
}, b.pause = function() {
b.audio.pause();
};
}), define("scripts/environment", [ "./vendor/zepto" ], function(a, b) {
var c = a("./vendor/zepto"), d = navigator.userAgent.toLowerCase();
b.isWeixin = function() {
return "micromessenger" == d.match(/MicroMessenger/i);
}, b.isApp1 = function() {
return null != window.mucard;
}, b.isApp2 = function() {
return d.indexOf("mugedacardandroidwebview_v1.5") > -1;
}, b.isPublic = function() {
return "Mucard_public" == top._CUSTOM_TAG;
}, b.getClientName = function() {
return b.isWeixin() ? "weixin" :b.isApp1() ? "AppVer1" :b.isApp2() ? "AppVer2" :"other";
}, b.haveURL = function() {
return null != (window.webkitURL || window.URL);
}, b.isApp = b.isApp1, b.getHost = function() {
var a = location.host, b = [ "ads.oss-cn-hangzhou.aliyuncs.com", "mucard.mugeda.com", "cdn-cn.mugeda.com", "mucard.b0.upaiyun.com", "card.mugeda.com", "card-back.mugeda.com:8080" ], c = "";
return b.forEach(function(b) {
a == b && (c = b);
}), a.indexOf("mugeda.com") > -1 && (c = a), "" == c && (c = "mucard.mugeda.com"), 
"//" + c;
}, b.isOffical = function() {
return null != c.cookie.get("cookie_openid") && c.cookie.get("cookie_openid").length > 0;
};
}), define("scripts/gallery", [ "./vendor/zepto", "./environment" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./environment"), e = [], f = 0;
c(window).bind("resize", function() {
for (var a = []; e.length; ) {
var c = e.pop();
c.container.isExist() && (b.adaptSize(c), a.push(c));
}
e = a;
}), b.adaptSize = function(a) {
var b = a.container.find(".imgItem"), c = a.container.innerWidth(), d = Math.ceil(c / (a.minWidth + a.padding)), e = c / d - a.padding;
if (a.cover ? b.children("img ,.preload, .coverImage").height(e * a.ratio) :b.children("img ,.preload").height(e * a.ratio), 
b.css("width", 100 / d + "%"), a.oneline && !a.disable) {
var f = a.container.children(), g = f.length, h = 0;
"number" == typeof a.oneline ? h = a.oneline :(a.oneline = "a") ? h = Math.max(1, Math.floor(g / d)) :null != a.oneline && (h = 1);
var i = Math.max(g - d * h, 0);
f.show();
for (var b = [], j = 0; i > j; j++) {
var k = a.notRandom ? g - 1 - j :Math.floor(Math.random() * g);
b.indexOf(k) > -1 ? j-- :(b.push(k), f.eq(k).hide());
}
}
}, b.check = function(a, b, d) {
if (null == d) {
var e = b.container.find(".check"), f = b.container.find(".coverImage");
a ? (e.show(), f.css("opacity", .5)) :(e.hide(), f.css("opacity", 1));
} else c.isArray(d) || (d = [ d ]), d.forEach(function(c) {
var d = b.imageData[c].dom.find(".check"), e = b.imageData[c].dom.find(".coverImage");
a ? (d.show(), e.css("opacity", .5)) :(d.hide(), e.css("opacity", 1));
});
}, b.removeItem = function(a, b) {
c.isArray(b) || (b = [ b ]), b.forEach(function(b) {
var c = a.imageData[b];
delete a.imageData[b], c.dom.remove();
});
}, b.replaceImage = function(a, c, d) {
var e = a.imageData[c];
e.src = d;
var f = g(a, c);
e.dom.replaceWith(f), e.dom = f, b.adaptSize(a);
}, b.render = function(a) {
var d = null, e = "s" + f++;
for (var h in a.imageData) {
var i = a.imageData[h];
i.dom === !1 ? (i.dom = g(a, h), i.dom.attr("data-stamp", e), d ? i.dom.insertAfter(d) :i.dom.appendTo(a.container)) :i.dom.attr("data-stamp", e), 
d = i.dom;
}
a.container.children(":not([data-stamp=" + e + "])").each(function() {
c(this).remove()[0].data.dom = !1;
}), b.adaptSize(a);
};
var g = function(a, b) {
var d = a.imageData[b], e = '<div class="imgItem" style="float:left;overflow: hidden;padding:' + a.padding / 2 + 'px;position:relative;"> <div class="preload box-center" style="width:100%;height:100%;border:1px solid white;color:#999999;background: url(./images/refresh.gif) center center no-repeat"></div>' + (a.cover ? ' <div class="coverImage" style="display:none;width: 100%;height:100%;"></div>' :"") + ' <img style="display:none;width: 100%;" src="' + d.src + '"/ >' + (a.canSelect ? ' <div class="check" style="position:absolute;right: 14px;bottom:14px;display:none;"><i class="fa-lg fa-check-circle fa-select fa"></i></div>' :"") + (d.title ? ' <div class="text-center" style="white-space: nowrap;overflow: hidden;padding:3px 0;font-size:90%">' + d.title + "</div>" :"") + (c.isFunction(a.subTitle) ? '<div class="text-center subTitle">' + a.subTitle(d.raw) + "</div>" :"") + "</div>", f = c(e);
return c.isFunction(a.tplCallback) && a.tplCallback(f, d), f[0].data = d, f.find("img").bind("load", function() {
a.cover ? (a.innerCover ? c(this).prev(".coverImage").css("background-image", "url(" + c(this).attr("src") + ");background-size: contain;background-position: center center;background-repeat: no-repeat;").show().prev(".preload").remove() :c(this).prev(".coverImage").css("background-image", "url(" + c(this).attr("src") + ");background-size: cover;background-position: center;").show().prev(".preload").remove(), 
c(this).remove()) :c(this).show().prev(".preload").remove();
}).bind("error", function() {
var b = c(this), d = c('<small style="width:100%;height: 100%">加载失败<br />点击重试</small>');
c(this).prev().prev().append(d).css("background", "none");
var e = function(d) {
a.forceSelect || (c(this).css("background", "url(./images/refresh.gif) center center no-repeat"), 
c(this).find("small").remove(), b.attr("src", b.attr("src")), d.stopPropagation());
};
d.parent().one("click", e);
}), f;
};
b.filter = function(a, d) {
var e = {};
for (var f in a.imageData) {
var g = a.imageData[f].raw, h = !0;
if (c.isFunction(d)) h = d(g); else for (var i in d) {
var j = d[i];
if (g[i] != j) {
h = !1;
break;
}
}
h && (e[f] = a.imageData[f]);
}
var k = {};
c.extend(k, a), k.imageData = e, a.num = Object.getOwnPropertyNames(e).length, b.render(k);
}, b.renderCard = function(a, c) {
var e = {};
c.forEach(function(b) {
var c = "i" + f++, g = b.thumb.replace("[HOST]", d.getHost());
g = g.indexOf("//") > -1 ? g :a.base + b.crid + "/" + g, e[c] = {
id:c,
raw:b,
src:g,
title:b.title,
dom:!1
};
}), a.imageData = e, b.render(a);
}, b.init = function(a, d, g) {
var h = {};
d && d.forEach(function(a) {
var b = "i" + f++;
h[b] = {
id:b,
raw:a,
src:(g.base && -1 == (g.src ? a[g.src] :a).indexOf("http://") ? g.base :"") + (g.src ? a[g.src] :a),
title:g.title ? a[g.title] :null,
link:g.url ? a[g.url] :null,
dom:!1
}, g.isAudio || (h[b] = {
id:b,
raw:a,
src:(g.base && -1 == (g.src ? a[g.src] :a).indexOf("http://") ? g.base :"") + (g.src ? a[g.src] :a),
link:g.url ? a[g.url] :null,
dom:!1
});
}), e.push({
base:g.base,
container:a,
imageData:h,
minWidth:g.minWidth || 100,
padding:g.padding || 32,
ratio:g.ratio,
callback:g.callback,
cover:g.cover || !1,
canSelect:g.canSelect || !1,
oneline:g.oneline || !1,
subTitle:g.subTitle || null,
tplCallback:g.tplCallback || null,
innerCover:g.innerCover || !1,
notRandom:g.notRandom || !1,
isAudio:g.isAudio
}), g.render && b.render(e[e.length - 1]);
var i = -1;
if (a.on("click", ".imgItem", function(b) {
if (c.isFunction(g.callbackSubTitle) && c(b.target).parents("div.subTitle").length) g.callbackSubTitle(c(this)[0].data.raw, c(this)[0].data); else if (c.isFunction(g.callback)) {
var d = !1;
c(b.target).children().each(function() {
c(this).hasClass("audioCheck") && (d = !0, -1 !== i && i !== c(this).parent().index() && l[c(this).parent().index()].flag && (d = !1), 
i = c(this).parent().index());
}), c(b.target).each(function() {
c(this).hasClass("audioCheck") && (d = !0, -1 !== i && i !== c(this).parent().index() && l[c(this).parent().index()].flag && (d = !1), 
i = c(this).parent().index());
}), "i" != b.target.nodeName.toLowerCase() || c(b.target).hasClass("audioCheck") || (d = !0, 
-1 !== i && i !== c(b.target).parent().parent().index() && l[c(b.target).parent().parent().index()].flag && (d = !1), 
i = c(b.target).parent().parent().index()), d ? (l[i].flag ? (l[i].pause(), l[i].flag = !1, 
a.find(".imgItem .audioCheck").eq(i).css("backgroundImage", 'url("images/play.png")')) :(l[i].play(), 
a.find(".imgItem .audioCheck").eq(i).css("backgroundImage", 'url("images/pause.png")'), 
l[i].flag = !0), g.callback(c(this)[0].data.raw, c(this)[0].data)) :g.callback(c(this)[0].data.raw, c(this)[0].data);
}
}), d && /(\.mp3)$/.test(d[0])) {
a.find(".imgItem .preload").hide(), a.find(".imgItem .check").css("zIndex", "99999"), 
a.find(".imgItem").append("<i style='position: absolute;' class='audioCheck'></i>");
var j = a.find(".imgItem").eq(0).width() - 16, k = a.find(".imgItem").eq(0).height() - 16;
a.find(".imgItem .audioCheck").css({
left:"8px",
top:"8px",
height:k + "px",
width:j + "px",
background:"#ffffff",
borderRadius:"5px",
backgroundImage:'url("images/play.png")',
backgroundPosition:"center center",
backgroundRepeat:"no-repeat",
backgroundSize:"64px 64px"
});
var l = [];
d && d.forEach(function(b, c) {
var d = document.createElement("audio");
d.src = b, l[c] = d, l[c].index = c, l[c].flag = !1, l[c].addEventListener("ended", function() {
this.src = l[this.index].src, l[this.index].flag = !1, a.find(".imgItem .audioCheck").eq(this.index).css("backgroundImage", 'url("images/play.png")');
}, !1);
});
}
return e[e.length - 1];
};
}), define("scripts/list", [ "./environment", "./vendor/zepto", "./navibar", "./tpl/navibar", "./tpl/template", "./message", "./tpl/loading", "./tpl/dialog", "./utils", "./vendor/promise", "./gallery", "./cardListData", "./tpl/listV2", "./tpl/help", "./page", "./custom", "./tpl/customForm", "./promo", "./tpl/promoHtml" ], function(a, b) {
var c = a("./environment"), d = a("./navibar"), e = a("./utils"), f = a("./message"), g = a("./gallery"), h = a("./cardListData"), i = a("./tpl/listV2"), j = a("./tpl/help"), k = a("./page"), l = a("./custom");
window.ga = window.ga || function() {}, setTimeout(function() {
ga("set", "dimension1", c.getClientName());
}, 0);
var m = null, n = function() {
var a = location.pathname + location.search + location.hash;
a != m && (ga("send", "pageview", a, document.title), m = a);
}, o = function(b) {
MugedaUrl.current.getQueryObj().bid ? a.async("./tpl/list_custom_" + MugedaUrl.current.getQueryObj().bid, function(a) {
b(a({}));
}) :b(i({}));
};
b.init = function(b) {
o(function(i) {
$(window).bind("hashchange", function() {
var a = parseInt(location.hash.replace("#", ""));
isNaN(a) && (a = 0), /search/.test(location.hash.replace("#", "")) ? (p.find(".list.clist .search p").remove(), 
p.find(".list.clist .main").hide(), p.find(".list.clist .search").show(), O("search")) :(p.find(".list.clist .main").show(), 
p.find(".list.clist .search").hide(), O(a)), n();
});
var m = {};
f.showLoading("加载微卡列表");
var o = function(a) {
var b = MugedaUrl.current.getQueryObj().bid ? "custom_list_" + MugedaUrl.current.getQueryObj().bid + ".json" :null;
h.fetch(b).then(function(b) {
e.reportGATime(), f.hideLoading(), a(b);
}, function() {
f.hideLoading(), f.showConfirm("加载微卡列表失败，是否重试?", !0, {
labelConfirm:"重试",
labelCancel:"取消",
confirm:function() {
o(a);
}
});
});
};
o(function(a) {
a && $.isArray(a.catalog) || f.showConfirm("加载微卡列表失败，是否重试?", !0, {
labelConfirm:"重试",
labelCancel:"取消",
confirm:function() {
location.reload();
}
});
var b = function(a) {
a.forEach(function(a) {
m[a.id] = a, $.isArray(a.items) && b(a.items);
});
};
b(a.catalog);
var c = parseInt(location.hash.replace("#", ""));
isNaN(c) && (c = 0), /search/.test(location.hash.replace("#", "")) ? (p.find(".list.clist .search p").remove(), 
p.find(".list.clist .main").hide(), p.find(".list.clist .search").show(), O("search")) :(O(c), 
p.find(".list.clist .main").show(), p.find(".list.clist .search").hide()), n();
});
var p = $(i);
b.append(p);
var q = function(a) {
if (a.ad && "search" !== a.ad) a.url.indexOf("http://") > -1 || a.url.indexOf("https://") > -1 ? window.open(a.url, "_blank") :window.open(c.getHost() + "" + a.url, "_self"); else if (MugedaUrl.current.getQueryObj().bid) {
var b = window.businessParam || {};
b.url = decodeURIComponent(MugedaUrl.current.getQueryObj().ref), window.customImage = function(c, d, e) {
var f = [];
l.addFootToCustom(f, b.content, b.url), f.push("_bid=" + encodeURIComponent(b.bid)), 
c = f.join("&"), e(c, {
callback:function(b) {
var c = new MugedaUrl(b).getQueryObj().custom;
window.open(location.href.substr(0, location.href.length - location.search.length - window.location.hash.length) + "?" + ("crid=" + ("1" == a.custom_logo ? "_" :"") + a.crid + (a.audio ? "&audio=" + a.audio :"")) + "&custom=" + c, "_self");
}
});
}, l.finalizeCustomParametersV2();
} else if (!a.addition || 0 != a.addition.indexOf("http://") && 0 != a.addition.indexOf("https://")) {
var d = new e.MugedaUrl(location.href.substr(0, location.href.length - location.search.length - window.location.hash.length)), f = d.getQueryObj();
f.crid = ("1" == a.custom_logo ? "_" :"") + a.crid, a.audio && (f.audio = a.audio);
var g = d.getURL();
a.addition && (g += "&" + a.addition), window.open(g, "_self");
} else window.open(a.addition);
}, r = '<div style="position: absolute;right:0;display:block;margin-top: -35px;color:#d60;text-align: right;padding-right: 13px;"><span style="display: inline-block;background-color: rgba(255, 255, 255, 0.75);border-radius:12.5px; padding: 2px 6px 1px 6px;"><i class="fa-official-card fa"></i></span></div>', s = function(a, b) {
b.raw.official_custom && c.isOffical() && !MugedaUrl.current.getQueryObj().bid && a.filter(".imgItem").first().children().eq(1).after($(r));
}, t = function(a) {
return "search" == a.ad ? '<a data-id="' + a.hashId + '" data-sid="' + a.sub_catogory_refid + '"><small class="noCallback" style="color:#888;white-space: nowrap;overflow: hidden;">' + a.title2 + ' <i class="fa fa-caret-down fa-rotate-270""></i></small></a>' :a.ad ? '<a data-id="' + a.cataId + '" data-sid="' + a.sub_catogory_refid + '"><small class="noCallback" style="color:#888;white-space: nowrap;overflow: hidden;">' + a.title2 + ' <i class="fa fa-caret-down fa-rotate-270""></i></small></a>' :'<a data-id="' + a.cataId + '" data-sid="' + a.sub_catogory_refid + '"><small class="noCallback" style="color:#888;white-space: nowrap;overflow: hidden;">更多' + a.title2 + ' <i class="fa fa-caret-down fa-rotate-270""></i></small></a>';
}, u = function(a) {
a.ad ? "1" == a.ad ? window.open(a.url, "_blank") :"search" == a.ad && (location.hash = a.hashId, 
p.find(".list.clist .search").hide()) :(location.hash = a.cataId, null != a.mainId && (E = a.mainId, 
H(E)));
}, v = p.find(".list.clist .main"), w = g.init(v.first(), null, {
minWidth:150,
src:"thumb",
title:"title",
padding:8,
base:c.getHost() + "/weixin/card/cards/",
ratio:140 / 124,
callback:q,
tplCallback:s
}), x = g.init(p.find(".list.clist .rec").first(), null, {
minWidth:150,
src:"thumb",
title:"title",
padding:8,
base:c.getHost() + "/weixin/card/cards/",
ratio:140 / 124,
callback:q,
tplCallback:s,
subTitle:t,
callbackSubTitle:u,
notRandom:!0
}), y = g.init(p.find(".list.clist .search").first(), null, {
minWidth:150,
src:"thumb",
title:"title",
padding:8,
base:c.getHost() + "/weixin/card/cards/",
ratio:140 / 124,
callback:q,
tplCallback:s,
subTitle:t,
callbackSubTitle:u,
notRandom:!0
}), z = p.find(".list.nav .line.red").hide(), A = p.find(".list.nav .cas"), B = p.filter(".list.cas-mask"), C = p.find(".list.nav .cas-frame"), D = p.find(".label-main"), E = null, F = function() {
A.hide(), B.hide(), C.hide(), E ? H(E) :z.hide();
}, G = function() {
A.show(), B.show(), C.show();
}, H = function(a, b) {
var c = (b || p.find('.list.nav .con .it[data-id="' + a + '"]')).children().offset();
null != c && z.css("margin-left", c.left + c.width / 2 - 21.5 + "px").show();
};
$(window).on("resize", function() {
H(E);
});
var I = function(a, b) {
var c = m[a];
if (0 == a) return z.hide(), void (location.hash = 0);
if (null != a && null != c) {
if (c.items && 0 == c.items.length) return void f.showMessage("列表为空");
if (z.show(), H(a, b), c.items) {
{
"none" == A.css("display");
}
c.items.length ? (G(), A.find(".hide").removeClass("more"), A.find(".more").show()) :F(), 
A.empty();
var d = !1;
if (c.items.forEach(function(a) {
if (0 != a.cards.length && !(5 == a.parent && a.cards && a.cards.length < 5 || a.hideCata)) {
if (a.hasNew) var b = $('<div class="item" data-id="' + a.id + '">' + a.title + '<span class="new-label"></span></div>').appendTo(A); else var b = $('<div class="item" data-id="' + a.id + '">' + a.title + "</div>").appendTo(A);
a.hide && (b.addClass("hide"), d = !0);
}
}), A.find(".item .selected").removeClass("selected"), A.find('.item[data-id="' + J + '"]').addClass("selected"), 
d) {
$('<div class="item more">更多' + c.title + "</div>").appendTo(A);
}
} else F(), location.hash = c.id, E = a;
}
}, J = null, K = p.find(".rec-frame"), L = "", M = p.find("#seachOk"), N = p.find("#seachText");
N.on("focus", function() {
$(".cfoot").hide();
}), N.on("blur", function() {
$(".cfoot").show();
}), N.on("keyup", function(a) {
L = N.val(), "" !== L && 13 == a.keyCode && (window.location.hash = "search=" + encodeURIComponent(L));
}), M.on("click", function() {
if (0 == N.width()) N.animate({
width:"175px",
border:"1px solid #bbbbbb",
paddingLeft:"4px"
}, 500, "ease-out", function() {
setTimeout(function() {
N[0].focus();
}, 100);
}); else {
var a = N.val();
"" !== a && (L = a, window.location.hash = "search=" + encodeURIComponent(a));
}
});
var O = function(a) {
if (J != a || "search" === a) {
if (L = decodeURIComponent(window.location.hash.slice(window.location.hash.indexOf("=") + 1)), 
J = a, 0 == a) return Q();
K.addClass("highlight");
var b = "search" !== a ? m[a] :m, c = b.title, d = b.parent;
if (d > 0 ? (d = m[d].id, c = m[d].title + '<span style="font-family: simsun,arial,helvetica,clean,sans-serif;"> &gt; </span>' + c, 
document.title = b.title) :0 == d ? d = a :"search" === a && "" !== L && (d = 0, 
c = '搜索<span style="font-family: simsun,arial,helvetica,clean,sans-serif;"> &gt; </span>' + L), 
D.show().html(c), "search" !== a && v.show(), E = d, z.show(), H(d), "search" === a) {
if ("" !== L) {
var e = [], f = L.split(" "), h = new RegExp(f[0]);
for (var i in b) if (b.hasOwnProperty(i) && "0" !== i) if (h.test(b[i].title)) {
if (b[i].hasOwnProperty("items")) for (var j = 0; j < b[i].items.length; j++) for (var k = 0; k < b[i].items[j].cards.length; k++) b[i].items[j].cards[k].ad = "search", 
b[i].items[j].cards[k].hashId = b[i].items[j].id, b[i].items[j].cards[k].url = "/weixin/card/index.html?crid=" + b[i].items[j].cards[k].crid + "&audio=" + b[i].items[j].cards[k].audio, 
b[i].items[j].cards[k].title2 = "更多" + b[i].items[j].title, e.push(b[i].items[j].cards[k]); else if (b[i].hasOwnProperty("cards")) for (var j = 0; j < b[i].cards.length; j++) b[i].cards[j].ad = "search", 
b[i].cards[j].hashId = b[i].id, b[i].cards[j].url = "/weixin/card/index.html?crid=" + b[i].cards[j].crid + "&audio=" + b[i].cards[j].audio, 
b[i].cards[j].title2 = "更多" + b[i].title, e.push(b[i].cards[j]);
} else if (b[i].hasOwnProperty("items")) {
for (var j = 0; j < b[i].items.length; j++) if (h.test(b[i].items[j].title)) for (var k = 0; k < b[i].items[j].cards.length; k++) b[i].items[j].cards[k].ad = "search", 
b[i].items[j].cards[k].hashId = b[i].items[j].id, b[i].items[j].cards[k].url = "/weixin/card/index.html?crid=" + b[i].items[j].cards[k].crid + "&audio=" + b[i].items[j].cards[k].audio, 
b[i].items[j].cards[k].title2 = "更多" + b[i].items[j].title, e.push(b[i].items[j].cards[k]);
} else if (b[i].hasOwnProperty("cards")) for (var j = 0; j < b[i].cards.length; j++) h.test(b[i].cards[j].title) && (b[i].cards[j].ad = "search", 
b[i].cards[j].hashId = b[i].id, b[i].cards[j].url = "/weixin/card/index.html?crid=" + b[i].cards[j].crid + "&audio=" + b[i].cards[j].audio, 
b[i].cards[j].title2 = "更多" + b[i].title, e.push(b[i].cards[j]));
e.sort(function(a, b) {
return a.id - b.id;
});
var l = "";
if (e = e.filter(function(a) {
return "" == l ? (l = a.id, !0) :l == a.id ? !1 :l != a.id ? (l = a.id, !0) :void 0;
}), f.length > 1) for (var k = 1; k < f.length; k++) e = e.filter(function(a) {
var b = new RegExp(f[k]);
return b.test(a.title);
});
e = P(e), g.renderCard(y, e);
}
} else e = P(b.cards), g.renderCard(w, e);
var n = P(m[0].cards);
x.oneline = 1, g.renderCard(x, n), 0 == e.length && "" !== L && $("<p style='margin-left: 10px'>非常抱歉，暂时没有搜索到您需要的微卡！</p>").appendTo(".list.clist .search"), 
window.scrollTo(0, 0);
}
}, P = function(a) {
return a.filter(function(a) {
return c.isApp() ? "undefined" == typeof a.hide ? !0 :-1 === a.hide.toLowerCase().indexOf("a") ? !0 :!1 :c.isWeixin() ? "undefined" == typeof a.hide ? !0 :-1 === a.hide.toLowerCase().indexOf("w") ? !0 :!1 :"undefined" == typeof a.hide ? !0 :-1 === a.hide.toLowerCase().indexOf("o") ? !0 :!1;
});
}, Q = function() {
if (m[0]) {
document.title = "推荐微卡", K.removeClass("highlight"), z.hide(), D.hide(), v.hide();
var a = P(m[0].cards);
x.oneline = "a", g.renderCard(w, []), g.renderCard(x, a);
}
};
B.on("click", function() {
F();
}), C.on("click", function() {
F();
}), p.on("click", ".list.nav .con .it", function() {
var a = $(this), b = a.data("id");
I(b, a);
}), A.on("click", ".item", function(a) {
var b = $(this);
if (b.hasClass("more") && !b.hasClass("hide")) return A.find(".hide").addClass("more"), 
b.hide(), void a.stopPropagation();
var c = b.data("id");
location.hash = c, F();
});
var R = p.find(".item-login");
c.isWeixin() ? (R.addClass("weixinBtn"), R.click(function() {
if (location.hash = "userInfo", "" == e.cookie.get("followed")) {
window.localStorage && localStorage.setItem && localStorage.setItem("chkAuth", "a");
var a = location.href.replace("#wechat_redirect", "");
location.href = "http://weika.mugeda.com/server/checkAuthStatus.php?url=" + encodeURIComponent(a);
} else $(document.body).trigger("weixin:official");
})) :c.isApp2() && (R.addClass("userBtn"), f.showLoading("加载用户框架"), a.async("./user", function(b) {
a.async("./userview", function() {
f.hideLoading(), b || f.showConfirm("加载用户框架，请检查网络状况，是否重试？", !0, {
labelConfirm:"重试",
labelCancel:"取消",
confirm:function() {
doUserLoad();
}
}), R.click(function() {
b.showUserForm();
});
});
}));
var S = p.find(".item-help");
S.click(function() {
var a = d.getNaviBar("帮助", {
hideUserIcon:!0,
hideWeiIcon:!0,
cancelLabel:"返回"
}), b = $("<div></div>");
b.append(a.navibarTpl).append(j({})), b.find(".cancelBtn").one("click", function() {
k.remove(c.id), k.back();
});
var c = k.setNewPage("help", {});
c.dom.append(b), k.addToLayout(c.id), k.setActive(c.id, !0);
});
});
};
}), define("scripts/main", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./zeptoExtra", "./page", "./vendor/fastclick", "./weiOfficial", "./tpl/weiofficial", "./navibar", "./tpl/navibar", "./environment", "./utils", "./vendor/promise", "./tpl/help", "./list", "./gallery", "./cardListData", "./tpl/listV2", "./custom", "./tpl/customForm", "./promo", "./tpl/promoHtml" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./zeptoExtra"), f = a("./page"), g = a("./vendor/fastclick"), h = a("./weiOfficial"), i = a("./utils"), j = a("./list");
b.init = function(a) {
if (d) {
if (!(c && e && f && g && h)) return d.hideLoading(), d.pageErr();
new g(document.body), h.init(), i.fixIOSInputProblem(c), "#userInfo" === location.hash ? l() :"#photoView" === location.hash ? m() :"#audioView" === location.hash ? n() :"#collectView" === location.hash ? o() :null == a ? (d.showLoading("载入列表"), 
k()) :p();
}
};
var k = function() {
f.removeAll();
var a = f.setNewPage("list", {});
j.init(a.dom), f.addToLayout(a.id), f.setActive(a.id), d.hideLoading(), h.chk();
}, l = function() {
a.async("./userview", function(b) {
k(), a.async("./environment", function(c) {
c.isApp2() ? b.userInfoView() :c.isWeixin() && a.async("./weiOfficial", function(a) {
a.showAttendForm();
});
});
});
}, m = function() {
a.async("./userview", function(a) {
k(), l(), a.userPhotoView();
});
}, n = function() {
a.async("./userview", function(a) {
k(), l(), a.userAudioView();
});
}, o = function() {
a.async("./userview", function(a) {
k(), l(), a.userCollectView();
});
}, p = function() {
a.async("./cardview", function(a) {
var b = f.setNewPage("card", {
type:"scroll",
background:"transparent"
});
b.dom.css("min-height", 0).attr("id", "card-title"), a.init(b.dom), f.addToLayout(b.id), 
f.setActive(b.id), d.hideLoading(), window.messageMuteStage = 0, h.chk();
}), window.getWeiXinUrl = function() {
return window.location.href;
};
};
}), define("scripts/message", [ "./vendor/zepto", "./tpl/loading", "./tpl/template", "./tpl/dialog" ], function(a, b) {
var c, d = a("./vendor/zepto");
b.showMask = function() {
d(":focus").blur(), c ? c.show() :c = d('<div class="full-screen" style="z-index:99;background-color: rgba(0,0,0,0.3);"></div>').appendTo(document.body), 
window.messageMuteStage > 0 && c.hide();
}, b.hideMask = function() {
c && c.hide();
};
var e, f = [];
b.showLoading = function(c) {
f.push(c), b.showMask();
var d = a("./vendor/zepto");
if (e) e.show().find(".message").html(c); else {
var g = a("./tpl/loading")({
message:c
});
e = d(g).appendTo(document.body);
}
window.messageMuteStage > 0 && e.hide();
}, b.hideLoading = function(a) {
a ? f = [] :f.pop(), f.length ? b.showLoading(f.pop()) :(b.hideMask(), e && e.hide());
};
var g = null;
b.showConfirm = function(c, d, e) {
e = e || {}, b.showMask();
var f = a("./vendor/zepto");
c = c.split("\n");
var h = a("./tpl/dialog")({
message:c,
labelOK:e.labelConfirm,
labelNo:e.labelCancel,
confirm:d
});
g = f(h).appendTo(document.body), g.on("click", ".confirm", function(a) {
b.hideMask(), g.remove(), g = null, f.isFunction(e.confirm) && e.confirm(), a.preventDefault();
}), g.on("click", ".cancel", function() {
b.hideMask(), g.remove(), g = null, f.isFunction(e.cancel) && e.cancel(), event.preventDefault();
});
}, b.hideConfirm = function(a) {
if (a) {
if (b.hideMask(), null == g) return;
g.remove(), g = null;
}
};
var h, i, j, k = [];
b.showMessage = function(b, c) {
k.push(b);
var d = a("./vendor/zepto");
if (!h) {
var e = '<div style="position: absolute;top:70%;z-index:102;left:0;width:100%;text-align: center"><span style="background-color:#d60;color: white;padding: 4px 10px;border-radius: 4px;"></span></div>';
h = d(e).appendTo(document.body);
}
var f = function() {
j = null, k.length ? g(k.splice(0, 1)[0]) :(h.hide(), i = !1);
}, g = function(a) {
i || (h.show(), i = !0), h.find("span").html(a), j = setTimeout(f, 3e3);
};
i ? c && j && (clearTimeout(j), f()) :g(k.splice(0, 1)[0]);
};
var l = !1;
b.pageErr = function() {
l || (l = !0, b.showConfirm("加载页面失败，是否重新加载?\n您的网络状况不太好哦。", !0, {
labelConfirm:"重新加载",
labelCancel:"取消",
confirm:function() {
l = !1, b.showLoading("正在重载"), location.reload();
},
cancel:function() {
l = !1;
}
}));
}, b.setWeixinToolbarBotton = function() {
if ("undefined" != typeof WeixinJSBridge && WeixinJSBridge._hasInit !== !1) try {} catch (a) {}
};
}), define("scripts/navibar", [ "./tpl/navibar", "./tpl/template", "./environment", "./vendor/zepto", "./message", "./tpl/loading", "./tpl/dialog" ], function(a, b) {
var c = a("./tpl/navibar"), d = a("./environment"), e = a("./message");
return c && d ? (b.getNaviBar = function(f, g) {
var h = {}, i = h.navibarTpl = $(c({
title:f
}));
if (g.hash && (location.hash = g.hash), g.leftTpl && (h.leftTpl = g.leftTpl.appendTo(i.find(".navi-left"))), 
g.cancelLabel && $(i.find(".navi-left")).append('<span class="btn cancelBtn">' + g.cancelLabel + "</span>"), 
g.rightTpl && (h.rightTpl = g.rightTpl.appendTo(i.find(".navi-right"))), g.hideUserIcon || !d.isApp2()) i.find(".userBtn").remove(); else {
var j = i.find(".userBtn");
e.showLoading("加载用户框架");
var k = function() {
a.async("./user", function(c) {
a.async("./userview", function() {
e.hideLoading(), c || e.showConfirm("加载用户框架，请检查网络状况，是否重试？", !0, {
labelConfirm:"重试",
labelCancel:"取消",
confirm:function() {
k();
}
}), i.on("click", ".userBtn", function() {
c.showUserForm(b);
}), j.on("user:status", function() {
a();
});
var a = function() {
c.getLogin().then(function(a) {
a ? j.removeClass("nologin") :j.addClass("nologin");
});
};
a();
});
});
};
k();
}
return i.find(".weixinBtn").remove(), h;
}, b.hide = function(a) {
a.navibarTpl.filter(".navi").animate({
"margin-top":"-48px",
opacity:"0.3"
}, "slow");
}, void (b.show = function(a) {
a.navibarTpl.filter(".navi").animate({
"margin-top":"0px",
opacity:"1"
}, "slow");
})) :!1;
}), define("scripts/page", [ "./vendor/zepto" ], function(a, b) {
var c = a("./vendor/zepto");
if (!c) return !1;
var d = {}, e = [];
b.init = function() {};
var f = 0;
b.setNewPage = function(a, b) {
return d[f] = {
dom:b.dom || c("<div></div>"),
inDom:b.inDom || !1,
id:f,
name:a,
type:b.type || "scroll",
background:b.background || "#fff",
index:f
}, d[f].dom.css("background", d[f].background).addClass("page-" + d[f].type).addClass("page-" + a), 
d[f++];
}, b.addToLayout = function(a) {
return d[a].inDom ? page[a] :(d[a].inDom = !0, void c(document.body).append(d[a].dom));
}, b.removeAll = function() {
for (id in d) b.remove(id);
}, b.remove = function(a) {
d[a].inDom && (d[a].dom.remove(), delete d[a]);
}, b.setActive = function(a, f) {
c(":focus").blur(), f && b.currentId && e.push(b.currentId);
for (var g in d) g == a ? d[g].dom.removeClass("inactive").css("z-index", 20 + d[g].index) :d[g].dom.addClass("inactive").css("z-index", d[g].index);
b.currentId = a, c(window).trigger("resize"), c(document.body).trigger("page:changed", a);
}, b.back = function() {
b.setActive(e.pop(), !1);
}, b.setNewPage("loadMessage", {
dom:c("#layout"),
inDom:!0
});
}), define("scripts/photoservice", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./environment", "./vendor/promise", "./page", "./navibar", "./tpl/navibar", "./user" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./environment"), f = a("./vendor/promise"), g = a("./page"), h = a("./navibar"), i = a("./user");
if (e.isWeixin()) var j = "http://weika.mugeda.com/server/ucenter.php/ucenter/", k = {
remove:j + "removeFile",
list:j + "myImgLib",
upload:"http://weika.mugeda.com/server/app_asset.php/u"
}; else var j = "http://weika.mugeda.com/server", k = {
remove:j + "/app_asset.php/r",
list:j + "/app_asset.php/list",
upload:j + "/app_asset.php/u"
};
var l = {
source:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAKAAAADgBAMAAAB2uXByAAAAA3NCSVQICAjb4U/gAAAAMFBMVEX///8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAv3aB7AAAAEHRSTlMAESIzRFVmd4iZqrvM3e7/dpUBFQAAAAlwSFlzAAALEgAACxIB0t1+/AAAABZ0RVh0Q3JlYXRpb24gVGltZQAwOC8xMy8xNByR2gEAAAAcdEVYdFNvZnR3YXJlAEFkb2JlIEZpcmV3b3JrcyBDUzVxteM2AAAErElEQVR4nO3cO2/TUBQH8JMmbVNSWibEQ5R8g/IBUBsJhiKQysAACy0wMUD4BnRAogsyEwyVKCxMSGVnCLAyBAkJxJQiFpjKG0rbmMR2HNv3Yd97/hlQfYZOvT9d+z59HyEXHJSD/yHYfrZ8+6Qfc8srDSa4df8ExaM098QefH+FZFG82rICP1+Wct0YurphDO7cUnLek98xBDdrWq8Tp4zAd/vSPKL9rezg23SuEyONrGA2r/MiZaIE/JTR6+RR8tQiuJnh/fXiQAawfTa7R3Q8HXxk4hE5aeAvM4+KyTaTANs1Q5AO68GXph7Rqg7cNijhXpR14Atzj6iuBm0y2KneatAqg4ksRkG7DBKNqsCPdl68oKOgUaOLxrgc/GPrUWFDCj61BmleBrar9mBZBv6294iaEpDxxESzErDKAcsiaF/G3Si0BPADC6QFAVzkgZUkuMPzqJQETccSIRoJ8DUXnE+AN7hgJQ62LbvCfgzHwU2uR9SKgd/4YD0GshqyH0dj4CIfHIuB7DIJqzahyqRXKj74EwE6EfALApyNgA8R4N4IuIgAyxEQUMi9YvbAbYRH1Ad540kYayEIqTVEN0MQUmuCekOgrqEbEyG4hAH3hKD1xDAeoyFYxYClHtjGeH5F7P7ZQoHNAATVa3/yTrh67dfsLvgdBc4HIKih+ANfF3yOAicCENTy/PkN4Vqe32cTagCIgjUUWArAKhpEeV5jJv58XQBhfYM3cx8ECOtsvPnSIED2N08/6h74AwfODwb8igOnPRDWvxJNDgZcx4ETHgjrsL0ue7eBezxwCQeWcxAEsldY+jHigTUcWMrBHMzBHMzBHMxB2/AH+kU0uLSbQH9KfBcNAufYe9Eg/EsK/q0H/7yFf4D7aw6wFdMBrIrA121a6JUlf+0LttCOX+4bQa9wwtdg4avE8HXs6QBcR4ELAQjYT/fDQW9/NNAbNOgtpHCTC9VUyiFYw4AV9M7jJHpvdAG9e+ug95eb4B3wIfQe/WgfxOz5VCIgZPnraASE1Jt6BIRMb9YiIGIHxC/kAPwLAEejIOKwyHgMBBTzdAx8zgedGAgYpzZiIL81944oByB/BpY4jMef38wmQHbjW02A3KMJQTvpg9yzaeUkyO0SpwRwiQc6Asg7WNs/kR2CvKotOUvMOxY6JYK8l+hIQM5LjBxq74OclzjmSkBOTZyWgozlloYUtP+yH3aloP0MZ0oO2o9UjgK0rThFVwHaDvfjKtD2lJ+jBO2eueQqQbtnPqIG7ca+NQ1ocyknfgkpAdrU7RkdaNGeCy0taD6Vrbha0D1mCq6mgKZVMVEkImh6m6ueBhreNysmk4ug2SRnJh00qjnCjUcZaJJFMYMS0CCLkgzKwOxXWy+KiWVg5oIW6qAK3K5mA4VbrSowYy92SJZUDmbqaIea0qRyMEu5yEpECbpvUr2D8oQqMHX6WWoZgts1rVeQlrAOdP9oX+N1VTI1qL1bfkaZSgNqbr+fVifSge5bxVPrPC3ofqrKyuOaLokedLcuCd7wA22KFNB1H8czWbig/iWHbKC7c68WckPn5e3XCOzEq+Vz3d/YWF7J8L+ZQKPYheA/ZsbYyYiTKxIAAAAASUVORK5CYII=",
loaded:!1,
use:!1,
image:new Image()
};
b.customImage = function(a, b) {
d.showLoading("获取图片"), f.all([ n(a), o(a), m(a) ]).then(p).then(function(a) {
var b = {
src:a
};
return d.showLoading("获取图片"), n(b);
}).then(function(c) {
d.hideLoading(), a.imgNew = c, b(c.src);
}, function(a) {
a && d.showMessage("定制图片失败：" + a), d.hideLoading(!0);
});
}, b.deletePhoto = function(a) {
return new f(function(b, d) {
var f = k.remove, g = {}, h = function(a) {
c.ajax({
type:"get",
url:f,
data:a,
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
xhrFields:{
withCredentials:!0
},
success:function() {
b();
},
error:function() {
d();
}
});
};
e.isWeixin() ? i.getToken().then(function(b) {
g = {
token:b,
t:+new Date(),
file:encodeURIComponent(a.join(","))
}, h(g);
}) :e.isApp2() && i.getUrid().then(function(e) {
g = {
urid:e,
t:+new Date(),
files:encodeURIComponent(JSON.stringify(a))
}, c.ajax({
type:"POST",
url:f,
data:g,
xhrFields:{
withCredentials:!0
},
success:function() {
b();
},
error:function() {
d();
}
});
});
});
}, b.getUserPhotoList = function(a) {
var b = "图片";
"audio" == a && (b = "声音");
var g = k.list;
return new f(function(f, h) {
var j = {};
j.seed = new Date().getTime(), j.resourceType = "audio" == a ? "audio" :"image";
var k = function() {
d.showLoading("正在加载"), e.isApp2() ? i.getUrid().then(function(a) {
j.urid = a, c.ajax({
type:"GET",
url:g,
data:j,
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(a) {
d.hideLoading(), 0 === a.status ? f(a) :l(function() {
h("获取用户" + b + "列表失败");
});
},
error:function() {
d.hideLoading(), l(function() {
h("获取用户" + b + "列表失败");
});
}
});
}, function() {
d.hideLoading(), d.showConfirm("您需要先登录才能查看保存的" + b + "，是否现在登录？", !0, {
labelConfirm:"立即登录",
labelCancel:"下次再说",
confirm:function() {
c(document.body).trigger("user:login", {
callback:function() {
k();
}
});
},
cancel:function() {
h("用户没有登录");
}
});
}) :e.isWeixin() && i.getToken().then(function(a) {
c.ajax({
type:"get",
url:g,
data:{
token:a,
t:+new Date()
},
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
xhrFields:{
withCredentials:!0
},
success:function(a) {
d.hideLoading(), 0 === a.status ? f(a) :l(function() {
h("获取用户" + b + "列表失败");
});
},
error:function() {
d.hideLoading(), l(function() {
h("获取用户" + b + "列表失败");
});
}
});
}, function() {
d.hideLoading(), d.showConfirm("您需要先登录才能查看保存的" + b + "，是否现在登录？", !0, {
labelConfirm:"立即登录",
labelCancel:"下次再说",
confirm:function() {},
cancel:function() {
h("用户没有登录");
}
});
});
};
k();
var l = function(a) {
d.showConfirm("获取用户" + b + "列表失败，是否重试？", !0, {
labelConfirm:"重试",
confirm:function() {
k();
},
cancel:function() {
a();
}
});
};
});
};
var m = function(a) {
return new f(function(b) {
"masked" == a.type ? (l.use = !0, l.loaded ? b() :(l.image.src = l.source, l.image.onload = function() {
l.width = l.image.width, l.height = l.image.height, l.loaded = !0, b();
})) :(l.use = !1, b());
});
}, n = function(a) {
return new f(function(b, c) {
var e = new Image();
e.src = a.src, e.onload = function() {
a.rawWidth = e.width, a.rawHeight = e.height, a.ratio = e.height / e.width, b(a);
}, e.onerror = function() {
d.showConfirm("读取图片失败，是否重试？", !0, {
labelConfirm:"重试",
confirm:function() {
n(a).then(function(a) {
b(a);
}, function(a) {
c(a);
});
},
cancel:function() {
c("获取源图片失败");
}
});
};
});
}, o = function(a) {
return new f(function(b, c) {
if (e.isApp()) setTimeout(function() {
window.mucard.useCustomImage(function(a, d, e) {
2 == a ? c() :0 != a ? c("获取图片失败") :b({
type:"local",
data:"data:image/jpg;base64," + decodeURI(d),
user:e
});
}, function() {
b({
type:"online"
});
}, !0);
}, 0); else {
var d = 3;
1 == d ? b({
type:"local",
data:"data:image/jpg;base64,/9j/4AAQSkZJRgABAQEAYABgAAD/2wBDAAMCAgMCAgMDAwMEAwMEBQgFBQQEBQoHBwYIDAoMDAsKCwsNDhIQDQ4RDgsLEBYQERMUFRUVDA8XGBYUGBIUFRT/2wBDAQMEBAUEBQkFBQkUDQsNFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBQUFBT/wAARCACcAQQDASIAAhEBAxEB/8QAHQAAAQMFAQAAAAAAAAAAAAAAAwACBAEFBgcICf/EAEYQAAIBAwIEBAQCBQcKBwAAAAECAwAEEQUhBgcSMRNBUWEIFCJxgZEVMkKCoRYjUmJysdEJFyQlM0ODksHwNEVTY5Oisv/EABsBAAMAAwEBAAAAAAAAAAAAAAABAgMEBQYH/8QAJREAAgIBBAICAwEBAAAAAAAAAAECEQMEEiExEyJBUQVhcTKx/9oADAMBAAIRAxEAPwDovpwe1EC0gmaKErqmOuRKgoqR77inJFvR1j3oGDVd6MiYpypR4owe/egSVDI0y3apCx570SOIZ2q3cV8UaRwLw9ea5rl9Fp2mWihpribOBk4AAG7MTsFGST2potUXNUA3NRdX13S+HYlk1XUrPS0PZr24SEH7dRGa4/41+Lfirj+5msOCrU8NaT1FVv5QGvpV9fNYs+gyw9RWv04ETVbhr3Vr2fUr5/qea4YyOx92bJrUyaqEHS5NzHpZZOTvbTuPuFtWnENlxPo13M3aOHUIWY/YBqyZY8YPruPevOr/ADXWMzfTGv26RWxOXuqcX8vJI00XXZ/klO+n3hM1sw9Ohj9P3Ug1ijrY37I2HoJtep2sFzTggrD+X3MeDjKFILmAadqoXLW4bqST1MbHv9juPes4RK6EZKauJy5xlje2SBBc+1OCCjBKcE+1UYwHQKoUqR0CkUFAEUp+NMKZqWUobJ7UARGX1obR57VLMf40NkoKTITx0Fk37Yq4MmaC8WfKgvsgPHmgPH61PaPGdqE0efKgX9LZcpgbbCrTc9YzhmH2NX65i2q0XUXnUsksdzNKpOJXH7xq13F1cAnE8n/OavN1HvVpuo8ZqaFTLVc3t0DtcSj981arjUr1Ttdzj/iH/GrndRnerRcp3pgyG+rXwY/6bcf/ACt/jSoDxnqNKpJN1xxE52oyxD709UJxRo48Y9ayUZa+WUSP1oyQ709I96kJHmihUMSLtgUVYMUVE27UZI8/eih0gaQj7e52FcBfEHzQl538ayWtjcseEdGlaKyhjJ6bmUbNcuPMnBCDyX3Y12N8QevXHCnJLjLUbR2juxp728Dp3WSYiJSPt4mfwrhjhLhuOCyS1SNUwoUEdycevrWrqJOMaRtYIKUuidwhpS29uqoCF7kisygts9JHYeVQ9O0d9OQMA/1d8jcferzbwMCDuQd64ck12eixJVRddItVYAkCsp0vTEzkDNWPTIB1LWZ6VGqlQu59K1/k3Ei46fYtA8csbNFLGwdHU4KkdiK3twrrQ1/RorkgCcfRMq9g47/ge/41qO2iURjI/IVlfLzUxYa21mxxHdrgA/0xuD+IyK6eky7J7X0zma7B5Me5do2T0mndFECZqvh+9d2zywLopdFF8P3pFPeiwA9JppX2o5Qj3ppFFgRzGKG0dSinpTSuKKAhPHihMlTzGD2oTRUh2QWjHpQHiqeyUNkHpmgpMtc0HVkZx96gT6Y0n7YH4VfXi9qjyR+1Kh0jGp9Bd/8AfKP3TVvn4WkfJ+YX/kNZc0dAePB7UUFGET8FyyZ/0tB+4f8AGrbNy/mk7XsYPvGf8a2E8foKjvHk7d6KDhmuH5a3JYkX8Q/4R/xpVsIqc0qmiaYdF2qQkeCPWlFHgVIjTaqLEkfrR0j7UlXJqTHH2oDkpFFUhUx2qqp6dqOke1BLZqf4ordpuRnEYRC+GtWYewuYia440Y9LBunudj2Ndzc/LZZuTHGAIyFsS+P7Lqf+lcK2brA4jQFVyQoHoa52rdUdLR07ZmOkS3dwr9OGjzjvV9iToQZxmo+hQeBYKe3UPxq28R65LZ2/TaQ+JcP9KZIAU+9cpty4O+ltMv076yBWc6BCMqcZ96520fTeNr+4L22rpDK/ZGGAPbzrMtF4r474PvFg1q1tdQtW/wB5GpDj8vzqNivszKb6aN+wjbttR7eYWGo2dyNvCmRzj0DDNYq/F8cOki78MsSBhRuST5Vg097xxxtfPDEbHQ9LzglnZ5nX8NgTVw/12GRejVHavQPLYVXo96sPL1r5+CNFOpT/ADV8LZVlnxgyYyAx9yAM+9ZDg16Zeys8NJbW0M6D61ToPrRMGlg1VCB9FU6aJSpUAAoPtTSp+9SCoppSgCMVBphUipLJTChp2BFKZoLRe2KmslDZc96YEB48fehPHmp7ptUd03qRpkCSLc1HdKuMiZqPInekZO+S3vHUeSPzFT3XP+FR5I8UwILJv2pUd0BNKlwTbDxrnyqQi+lNRdsVIjXtTGPiiqSieVNRcAUZF7UCbHomaOietUjXIo6LTIOZfiz5vahpmq6fy10jTDfy65ZtPqDiToZbfqP0KfInpJJ9BWi7DQYkFu8SSRBFXqimH1bjYmt5/GBw1PpF9acd6bB4uoRaPdaYMDP19SvH+OGk/KueuSF3NdcJammp3dxdayL0zTm5BDqGC4AB3C5yPTY15/VSn5mm+D1+mx4npYuK5+f6bHiiQQKo8h2qx8SafdIFkso0lm8g/YVdVvURsfhU63nV3U46q0W+DdgvhmsoOCeKuKBfW0mqXGmSyhflri0PSkDAgnqAYMwOCDuNjWb3nDOrcLaOI5dYuNVixCqG6y03UEIlYtk7M2CF/Z7ZNbT0K0tjbCUqoftnFYjzO1RLWfT9OjOby8LeGvoi/rMf+/Ohu40bCS3WXrhNRxBwmY/9nOB0lh3FYLqHIRtZ4gn1S41S/wDHe3EAt1l6IY3GwmUbkPgnYYGcHcgVmvLWSO28S3DhduplJ862NAiSIGNEG0gkk+0bS5ayFuCtMhLFntk8Bie56fM/hisnwaxDliWOkXmf1BcfSP3Rmsxr1GB7sUW/o8LqoqGecV9lOmkFJOMVWkVVwVcBkOxB7EeYrOapbRxBphMw+ft8wx+LIPEH0pgHqPtgjf3qicQ6TKzhdStCUIVv59fpJzgHf2P5Vg9jyvv7G31CP5i3nFzYNAOy9MrMuTgqwwFBGcZOdgO4yHUeEJtWiuIHkitoDPC0TbysY4o+gdX6u7eY32889ouX0OkXT+UmkCG3m/Stn4VwemJzOoDn23qSNTsj8vi8gb5jPhFZAQ+Bk49dgawmPl5qa6RHafNoWX5kAGZlVPECAPlVyzbMCO2GxVx1Pl+urXUE9xKyu6FLjpKSYHhGNelnjLE7jOceZGDvSTl9BSL5/KHSWvfkxqNsbrxPC8EP9XXnHT98+VTivpWALwjc/wAtUnF3YiWOf5p7VZW8UwGcsHK47+We1ZjqHEWlaRdw2t9qVpZ3M2DHDPMqO+TgYB777U1z2DRKKZ+9DZfI0yXV9Pi1VNNe+t01GRetLQygSsPUL38j+VDj1nTrjUZtOjvreS/hHVJarKDIg23K9x3H50WIey4oTpmpJHlQ2XFUBCdMfao7pnNTpFAqM60ikyFJHvUd1/Kp0i5GajSL+VBRCZN6VGK70qAsfGO1So1qNGalxdzQAZBmpEa5FBTtUmOgh9hlFFAxQx3oq/rCmxGOcyOFI+MeCtSsHi8SVI/mIB5+KgJX89x+NcdXMVnbao1ywWO+v4zCoIGX6B1HfzxXeEff0rm/4k+X2jaPqOja3p+nQWd5OZVmliXHURg9uwyGOcAZwM1ytdh3Lyr4O7+M1G28L+ejR8kZWU74G1XLTZ8EelWt7oBiO5zT4rjEu5yPKuGejSo2Xw/fDATq29qxrmXoSa1rul6jHIYrmzRkGBkOjEEr+YG9U0a96WBVjgb1jHF/MVbPWJLKGKW4ukxkJGxRfuR/cKS54M6tcouvA/LuRLjV72TVtTSfUJMnNx1iIZOyZH04X6cDyHrvW7rIi3hRFJKBQoycnatD6frPFPy6S6bYnUJJVBEL5t0xkdWXbYYGSDjfHvW0uDNT1DV7eOK8tHtb3ZDEWDZJOxBGxzkVbjVDlwrZ0Py7tvB4XhcjBmkeT+OB/dWTdNRdJsBpemWlmP8AcRLGSPMgb/xzUuvWYo7IRifPc0/JllL7ZTppdNVpVlMI3ppYNOpUgNbcecbajomvS2dpcxQxR20cpyEJDEk5JYHGQMCrgvFd7Jq9tax3dl4b3nhdL4LyAJ1MmzDp2KkEjbOCTjfMZ7G2nbMttDKfWSNW8seY9KadOtC6ubS3LrjDGFcjHbfFRTsdmtuGRqWk8eXN7rdgLa6uNLmub25F0kiIqy5XpA/ZVQEC99i3nUrjLVIbHWbfVNN1SJ9XmtbeOHR5bTxDeRNKWHST9SnDMcjtjetg3drDfWs1tcRrNBMhSSN+zKdiD7UT9XGNgBgewo2uqQGrtQ8IcS6hprb61NxPa3kAKEubZVjPiA4/UVFdSew7edQ9HljW90e0jwut6bqGp3OoAxkvFGwlw79sq/VFjf6sbdtttuc/3UxgGUqQCpGCD2IqXARqjQOYWralBqM9zPbRpCvVHm33JLov0DI6gFYsRuR1AZrJ+DtXvdXS9F3OswhcBG8NUYqxYoSFJG642ztWTiyt0QotvEsZbqKiNQCfXGO/vQoraG2DCGGOEHuI0C5x27U0mu2NtDHGRUeQVKbzqNL51bERnHlUaRRvtUp+9R5e5pGREY5J2FKqMcE0qAorHtUqI1DQ1JjagZLSpMZ7VEQ1IjagxvslKaMpwQajociioc1TESkYA71pP4nNVszY6Lpvihr8ySXHhg5Kx9IXJ9MkjH2rNObvNfReS/L/AFPivXXHy1onTDbBsPdzkHw4E/rMR38gCTsK4j5O8U6hzf4O17i3UL/9IcT3mu3N3e24HSIAURUgj/qiJY+j+zj1rS1d+JpfJvaJpZ4uQzWJDaXJYE/hUWLVUWZcnANF4lYToSpyTn2rBLq8cv0s3Q67ZryybPbvqzcOgaqvhuVYE+lVu+GIdduDNnpbP6w2IrU1nxJe6T0yjEsYO486zbh3mdb9aFiFPZlPlVJNj3qP9Nq8NcK3ttBFG98Xt49hGBg48x3rdvKfhEXmq/paaMi1s/piBGzy4/uXv98VoS25p6Jpdit5qd+thpqSRpPdMpKxK7qgY492FdmaFHYJolodKlhudL6B4FxbyCSKRf6Qddmz3yK6mi0++W99I4/5LWyjDx3y/wDhcD3pU3ypZNehPIjqVNzSzQA6qZptKgCpOapSpZoAVNJyaROaoTQBRjVDsKVMY5qWA1jQWNPdvKhOaEAwnv71GkO1GkbFRpGoYAnO9R5TuaMx86iyt/GkZECPelTWO9KgLGI2TRo2ANQlYjzqRG+RQBPjbyqQjYqBG+DTNV1uw0DSrnU9UvbfTdOtUMk93dSCOKJfVmOwoBovUbmtVc9Pik4H5A2Mq61fDUOIChaDQLBg91IfLr8ol/rPj2BrlL4jfj+uNWiu+HeWEk1hanMc3ErDonlHmLZSMxg/+o31HyC964lv7yS5lmuJ5JJ7iVjJLNM5d5GPcsx3J9zSboxvg2Tz6+Iriz4g+Ik1DX5ltdOtS36P0e1J+XtFbvjO7uQBl23PYYG1G+GvmUOXXMSJLyfwtF1cLaXnU2EjOf5uU/2WOCf6LH0rVeQVFMPmMbH1rFL2VMcZOElI9GuK+DY9VeS5tXEUxP1vgnJ/rgd/7Q39Q1aq17gucSeFcR+C7f7OZN0k/ssNj/f7UL4X+bN/xdZNoOoz+NqGnxAxzufqmhXAHV6suwz5jvXQj6da6hG2ygv/ALSJlDI591O1cfNp039M9TptY1FJ8o5bm4W1S0b6VMiqdsVkXCXDV9e3a5t8erMlbo1nhXSdF0i91O5u10qzs4mnmkkBeJUXvt+sPwJ+1YBpXOvg3Q9WtGnnk1LTXnCT3NvEUS3jxkyyB8EqDgFUycZO2MHWjpssnSRvS1Wngrbou/Nrl6Zfh14xVIjLfNardRdIwQIZEkJHthW/AVxrwHza4x5ayR3HCvE2q6EM5MVjdMkRPqY89B/EGvUtRb3tuHVoru0mQY6cNHJGw8sbEFT+Rryu464UHBHHnE3DYbrXTNQmto2IxlA2U/8AqVrr6VbFtR5vX+7WQ6c5f/5SLmLoJhj4jsNL4uswcO7x/J3RHqJI/pJ+6V2NyV+L7l5zsaGxsr9tD4gk/wDJtXKxSufSJ89Ev2B6v6tePYl8FsfmKlxynYgkYII9j610U7ORuPeY7Eg7EeRqnUK8qeUHx08x+V9rBp15cQ8YaNEOlLXWWYzRr5BLgfWAPRuoV6H8iOdui8++ALfiXR0e0dZGtr2wmYNJaXCgFkJHcEEMreYI7HIplXZsbqpE03qqhJoGOJqmRTaoTijgCpOaWcU1m22oZb1NSA8t60Jm8hVGfPtQ2b0p0BVmxQmOBSZsUB3oApI/51Hc5pztQJH8qRSQ2R6iu2adI+aBI9BQ1nwaVBJyaVA6ArISdzR0bB96t0lylvFJLM6RRRqXeSRgqooGSxJ2AA8zXGHxDfHNI/zXD/LW58NBmObiTH1N5EWwPYf+6d/6IHelYujovnd8UfB3Iy2e31Gc6txCV6o9DsXBm37GVu0S+7bnyU152c8PiJ4t576os2uXS22lQEm00a0JW2g9yDvI/wDXbf0wNq1neXc97cyTTyvPNKxeSWVi7ux7lmO5J9TQOods1LdmPc2PD7nJzQLklo3I32NOJz70x161YZxkYqRCjfKr9qe2wqMkhj2dSANsruKkR4cAj6h5GgbL/wAEcUTcH8SWeqRhmEL/AM4isVLodmXI9v416G8F3+m8SaDp+p2dzK0FxEsiMz5ODXmuMiuovhD5kRi7bg/UJyDKS+ndf6ud2ePPkf2h+8PSsOSNqzd0+SntfRs34t9dudC5KX0Np1u1/dW9tJKv7EQfrYn79Cr+9XJ/CWpR6tbrbTuSZB0OF74JAOPeu8OZOg2PEHAfEOnX0SSRizlkfrGekBCcj7YzXndwQHF+rrmIjHhuDuDgb/8AWsmkm03FC1sbabO1uWfG93yC16Hl3xnM54cnfp4f1ub9WFSf/DzH9kAnY9lJx+qRjnT4rtObSviM4oDIY1vI7W9T36oEUn/mQ12peaBp3NHSOFn1W3iv7a6tIr25ikQMjloRnP3LGuM/i40U8Mc29D0vxZrpbXQYII7q5k65JYxJN0Bj5lVwme5CgmtfH65KNjUc4jTzoGkyRuKf1YO3nTZGwcCkO1b5x6CeJgb11f8A5OHmYOGedGpcKXNwyWfEtliGMn6fm4cun4lBIv5CuTO5xWR8qNcuOGubnCGp2rmKe11SzlVx5YuEB/gSPxouhrs9w+oetU6xVJyEmkUbAMQPzoRcfegsKWz5/wAKYXA2oZYmmlgKAHl80wtimls0NnxT6AeXzQ2cAUx5BQHk96LAI8nvQGc+tNd6C8u1TZaQ55BUZ5M013oDyAedBRV3qO7+dJ5O9R3kzQH8Ks+T3pVHaTelRYqPP340fiK1PiLirVeANFumteHLBhb6h4Rwb24BBdWbv4aHC9I2JUk52rlQykdySKfqWozahd3F3cyvPcTyNLJNIcs7scsxPmSSTUJJw25rG3Zibsk+ICMjcUMn6t6YWCb/ALJ7+1VJ86BIIhyRTzigg4NOydj3oKGgF5Ci9yMk+gqUECgKNgBUV0LN1L9LjsakxSiRe2JB3X/qPagAmPzq4cNcQXvCuu2Or6fIIb2xmS4ic9gynO/sex9iagDfPfarvwYIP5ZaB8yoeA38AkQozggyKMdK7n7DvTfCBfaO7dd5iaZxTyY1LiexkWKz1bSriPoVwWt7lkMbxE+oY4/I+dcWaNa/KukgABVFwV7Z/wCxXcvG13wJd65Z2FnYaY9lBxj8hLHazxC1WOO5LwrOqORGsgYiNMfV4JLAKBWCWunaAON4oL2y06806LinSLaB5ILUtJG+kSSTRs7IeuMy+EXznGRgqxBrDgnst0Zs0/NSMq+FHi79L8KX+jXD+Jc6TL1QMTnNtISygeyv1j7MK0n8fsMUHMLgm96kQz6XNGWLAZ6Zsj/9mtkaRxHZ6Bx7w3qET/Lza9pL/O502xhQ38shPhuLUkjsPof6Vyh3JONl8ffo205o22pva6WbOOC6tI38KJYTbnVLQuIVcEZEXinKLnBwCaxyfvvSM3kbxbGebRkjkUurowBwWDAgfjVfEEYyzBfdjiu7dW1zgbTuad/qeo3WjGKy4Rv7i2nfSrJ4o/8AWEkcUkodOpZFhmAUAZbZmyqgEfCOh8JaRxTx5HCNEjiibh35Fms7BHUmOG5m6YxDkhTIwcjspUMQRmq836NPacLJOkg+l1cYzlTnb1oXzbWupWc8MvhSrlo5FO4YYZSPcEA/hXYPEmhaXrXFN7eHTNN1C4bX7CButbJYIv8AVUPVcshUAxpOpWR48I5YkKDjNhso+Cks+ct9LecNLxNeyXl5Fp19C9s1nBFqds1tDbjwzEGnQszspyFKDAHiEUst/A9tHefw3c6Lfnxyh0fioGNNQPVaalFEwZY7uPAkx6Bshx7OK2cSc4wc+nnXKPIfik6/Zavd6tFcaUt7ez3b6JJJZu2nxCULCENsiK0ThpPDLrn+abcjpNbG1q/kh07WHm+Ye8jjmexRCQz2w8Twxnc4MYjz7dRAJzVeb9D2m5sOxYBWJBwQAdvvTGJXq2P0nB27H0Nc96Fpot5OH9Uvbua3gKFL+3mib65jJKoBYN9HUDgRFfJt/IZFwDHBd8XW91ZXcAhigk8W2UgyOzdZDbYIUBlB6hnO1NZraVBtNutJ3obSGgl/emM9Z7FVj2fNCeQUN5cUB5c0rLSoJJMc0BpMnc0xpBQXlpjHvLUd5Md6a8npQHlwO+aAHSSbb0B3z9qozevagPJk4HalZP8ABzS4OBSoNKlQqPFl1DbHtTSqqo2p7jYn0oBY9qxmNMcHDHGDjtTI3KO0bbkbg+oqh2oc5PiRNnfOPwoLJee9VzQxutLJoQmG6sCqYzuDhh2IpgOcUhsRTAlQT9eUbaQdx6+4orLkHaoMo+jqBIZRkEeVTLZzLArt3IztQgZQKsbKQijAx+qNh6VkHDV5EIflH6epAQuw3XNWMjY0SycrNG4OGVhg/eskHTJl0bN0eVoEV7ciOaJxJE6jBDA5U/gQK3F8aWpQ8U8p+X2soiFLq88YAgYBe2yR+BBH4VpLSJG9aznnbey3Pw1cv1kPV4Wt3cK+yqsmB/E1k1KtRkVppOpr9GgiiqFKoq+f0ril8vC4GY0Iz2Kiq/sL9qcNkFYiewJtYOvHhRnHb6B/hTGVUIVVUKOwwNqfMeh9vTzqOCSwYnc1IM6E+BnmenLTnzplrcFItI4jX9EXQwAqu7AwOftIFH2c16usxD5Iww2ye49RXhVbXkumXUF5bt4dxbyLNG4/ZdWDKfzAr3Js7p7yxtriTHiTRJK+O3Uygn+Jqy1ySC4BJwMnc7d6GWAJOACfQYqjNihMxyaKLSHPL5UB5aazE0JmOKYxzP3oLyUxmJzQnPf70CKtJjvQnkz7CmMSSaA7GgY55NtqEzY3J3qhOM0FiSalkv6E7ljQnfFVc4oGcmnRXQ4uT5Uqae9KiybP/9k=",
user:"user"
}) :2 == d ? b({
type:"online"
}) :3 == d && a.imageUploader && a.imageUploader.imgBigData && b({
type:"local",
data:a.imageUploader.imgBigData,
user:"user"
});
}
});
}, p = function(a) {
var b = a[0], e = a[1];
return new f(function(a, f) {
var i = e.type;
switch (i) {
case "local":
var j = h.getNaviBar("裁剪图片", {
cancelLabel:"取消",
rightTpl:c('<span class="btn okBtn">确认</span>')
});
j.navibarTpl.on("click", ".cancelBtn", function() {
g.remove(m.id), g.back(), f();
});
var k = !1;
j.navibarTpl.on("click", ".okBtn", function() {
k || (k = !0, d.showLoading("生成裁剪"), setTimeout(function() {
k = !1;
var c = 480, h = 756, i = 1.5 * b.rawWidth, j = 1.5 * b.rawHeight;
i > c && (j = c / i * j, i = c), j > h && (i = h / j * i, j = h);
var l = J.getcuttedBase64(i);
d.hideLoading(), q(e.user, l).then(function(b) {
a(b), g.remove(m.id), g.back();
}, function(a) {
f(a), g.remove(m.id), g.back();
});
}, 100));
});
var m = g.setNewPage("cutter", {
type:"fix"
});
if (m.dom.append(j.navibarTpl), g.addToLayout(m.id), g.setActive(m.id, !0), "masked" == b.type) {
var n, o = b.rawWidth, p = b.rawHeight, s = l.width, t = l.height, u = o / p, v = s / t, w = 1;
if (u > v) {
var x = p * v;
w = x / o;
} else {
var x = o * v;
w = x / o;
}
n = (w - .5) / 1.5, w = 100 * n;
for (var y = '<div class="full-screen" style="top:48px;bottom:auto;height:48px;"> <div style="width:100%;max-width:320px;margin:auto;line-height: 48px;position: relative">   <small style="position: absolute;height:48px;">头像比例</small>   <div style="position: absolute;left: 90px;right:0;height:48px;">     <div class="line" style="height:2px;background: #d60;margin-top:23px;"></div>     <img class="hand" src="images/radio.png" style="width: 40px; position: absolute; left: ' + w + '%; margin-left: -20px; top: 4px;"/>   </div> </div></div>', z = c(y).appendTo(m.dom), A = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i), B = z.find(".line")[0], C = 0; B && "BODY" != B.tagName; ) C += B.offsetLeft, 
B = B.offsetParent;
var D = function(a, b) {
A || (a.touches = [ {
clientX:a.clientX,
clientY:a.clientY
} ]);
var c = ((b && A ? a.changedTouches[0] :a.touches[0]).clientX - C) / z.find(".line").width();
c = Math.max(c, 0), c = Math.min(c, 1), E.css("left", 100 * c + "%"), J.changePercent(c);
}, E = z.find(".hand"), F = function(a) {
D(a), a.preventDefault();
}, G = function(a) {
D(a, !0), a.preventDefault(), c(document.body).off(A ? "touchmove" :"mousemove", F), 
c(document.body).off(A ? "touchend" :"mouseup", G);
};
E.on(A ? "touchstart" :"mousedown", function(a) {
D(a), a.preventDefault(), c(document.body).on(A ? "touchmove" :"mousemove", F), 
c(document.body).on(A ? "touchend" :"mouseup", G);
});
}
var H = "masked" == b.type ? 96 :48, I = c('<div class="full-screen" style="top:' + H + 'px;"></div>').appendTo(m.dom), J = r(I, e.data, b.ratio, function() {
d.hideLoading(), J.changePercent(n);
});
break;

case "online":
d.hideLoading(), c(document.body).trigger("user:photo", {
viewMode:"select",
callback:function(b) {
b ? a(b.url) :f(b);
}
});
}
});
}, q = function(a, b, g) {
return g = g || {}, new f(function(f, h) {
var j = k.upload, m = g.isAudio ? "声音" :"图片", n = function() {
d.showLoading("获取用户信息"), i.getUrid().then(function(a) {
d.hideLoading(), p(a, !0);
}, function() {
d.hideLoading(), d.showConfirm("提示：您尚未登录。登录后，" + m + "将保存在您的个人目录中，今后可以反复使用。如果不登录，" + m + "在本次使用后将无法再被用到。\n\n您现在要登录吗？", !0, {
labelConfirm:"立即登录",
labelCancel:"下次再说",
confirm:function() {
c(document.body).trigger("user:login", {
callback:function() {
n();
}
});
},
cancel:function() {
p(a, !1);
}
});
});
};
e.isApp2() ? n() :setTimeout(function() {
p(a, !1);
});
var o = function(a, b, e) {
var i = "png";
0 == b.indexOf("data:image/png;base64") ? b = b.replace("data:image/png;base64,", "") :0 == b.indexOf("data:image/jpeg;base64") ? (b = b.replace("data:image/jpeg;base64,", ""), 
i = "jpeg") :g.isAudio && (i = "mp3");
var k;
k = c.cookie.get("token") ? "" :e, d.showLoading("上传" + m), c.ajax({
type:"POST",
url:j,
data:{
type:i,
urid:a,
imgdata:b,
login:k,
token:c.cookie.get("token")
},
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(a) {
d.hideLoading(), a && 0 === a.status ? f(a.info) :h("上传" + m + "失败");
},
error:function() {
d.hideLoading(), d.showConfirm("上传" + m + "时出现网络问题，是否重试？", !0, {
labelConfirm:"重试",
confirm:function() {
o(a, b, e);
},
cancel:function() {
h("上传" + m + "失败");
}
});
}
});
}, p = function(a, c) {
g.isAudio ? o(a, decodeURIComponent(b), c) :window.mucard && !l.use ? (d.showLoading("压缩" + m), 
mucard.compressToJPEG(b.replace("data:image/png;base64,", ""), function(b) {
d.hideLoading(), o(a, decodeURIComponent(b), c);
})) :o(a, b, c);
};
});
};
b.uploadAudioBase64 = function(a, b) {
return q(a, b, {
isAudio:!0
});
};
var r = function(a, b, d, e) {
var f = s(), g = function() {
a.isExist() ? f.setSize(a.innerWidth(), a.innerHeight()) :c(window).unbind("resize", g);
};
return c(window).bind("resize", g), f.initUI(a[0], a.innerWidth(), a.innerHeight()), 
f.placeImageBase64(function() {
e();
}, b, d), f;
}, s = function() {
var a = void 0, b = void 0, c = void 0, d = void 0, f = void 0, g = void 0, h = void 0, i = void 0, j = void 0, k = void 0, m = void 0, n = void 0, o = void 0, p = void 0, q = void 0, r = void 0, s = new Image(), t = new Image(), u = void 0, v = void 0, w = void 0, x = void 0, y = void 0, z = void 0, A = void 0, B = void 0, C = null;
s.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAVCAYAAABCIB6VAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAALpJREFUeNpi/P//fwMDA4M9ED8A4odAfAHEZmRkBNHkA6DBDv9xg/mUGGyAw9DzQCxAqauxgQIGSgHUddhAAqUG74ca9J6qhoNSBixMQRFGNcOhKUMAiT+fqkGCZtl8qhuKnBQZhg2giW9oEv74Ugw0uSqA2ExUsAtkUQI0ue4HYgdquhoE7kPpBmoHCQzsp0V4g4tbSg0VwFUqUmrwejw1jwELuQYD68RAWAEGpEApApRh9KFsAYAAAwDPG50lOOkShAAAAABJRU5ErkJggg==", 
t.src = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAIAAACQkWg2AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAA7EAAAOxAGVKw4bAAAALUlEQVQokWM8c+YMAzZgbGyMVZwJqygeMKqBGMD4//9/rBJnz56ljg2jGogBAEK1CICe2Pz1AAAAAElFTkSuQmCC";
var D, E, F = function(b, c, d) {
if (a = document.createElement("div"), b.innerHTML = "", u = document.createElement("img"), 
u.style.cssText += "position:absolute;left:0;right:0;top:0;bottom:0;", b.appendChild(u), 
b.appendChild(a), a.style.position = "absolute", a.style.top = "0", G(c, d), O(), 
a.innerHTML = '<span id="mask1" style="position: absolute;background-color: rgba(0,0,0,0.6);left:0;top:0;width:100px;height:100px;-webkit-transform-origin: 0 0;-webkit-transform: translate(-100px, 0);"></span><span id="mask2" style="position: absolute;background-color: rgba(0,0,0,0.6);left:0;top:0;width:100px;height:100px;-webkit-transform-origin: 0 0;-webkit-transform: translate(-100px, 0);"></span><span id="mask3" style="position: absolute;background-color: rgba(0,0,0,0.6);left:0;top:0;width:100px;height:100px;-webkit-transform-origin: 0 0;-webkit-transform: translate(-100px, 0);"></span><span id="mask4" style="position: absolute;background-color: rgba(0,0,0,0.6);left:0;top:0;width:100px;height:100px;-webkit-transform-origin: 0 0;-webkit-transform: translate(-100px, 0);"></span><span id="mask6" style="position: absolute;border: 1px white solid;"><canvas id="maskImg"></canvas></span><span id="mask5" style="position: absolute;background-color: #DD6600; border-radius:20px;border:2px white solid;background-image:url(' + s.src + ');background-repeat:no-repeat;background-position: center;background-position:center;background-repeat:no-repeat;opacity:0.7;"></span>', 
v = document.getElementById("mask1"), w = document.getElementById("mask2"), x = document.getElementById("mask3"), 
y = document.getElementById("mask4"), z = document.getElementById("mask5"), A = document.getElementById("mask6"), 
l.use) {
var e = document.getElementById("maskImg");
C = e.getContext("2d"), C.fillStyle = "#000000";
}
}, G = function(d, e) {
b = d, c = e, a.style.width = d + "px", a.style.height = e + "px", q && (I(), J(k, m, n, o, !0));
}, H = function(a, b, c) {
q = new Image(), q.onload = function() {
B = c, I(!0), a(), u.src = q.src;
}, q.src = b;
}, I = function(a) {
var e = 12, l = q.width, s = q.height, t = l, u = s, v = (b - 2 * e) / t, w = (c - 2 * e) / u, x = Math.min(v, w, 1), y = l * x, z = s * x, A = e + Math.round((b - 2 * e - y) / 2), C = e + Math.round((c - 2 * e - z) / 2);
d = A, f = C, p = B, r = x;
var D = y, E = D * B;
E > z && (E = z, D = E / B), g = A, h = C, i = y, j = z, k = A, m = C, n = D, o = E, 
a && J(A, C, D, E, !0, !0);
}, J = function(a, b, c, d, e, f) {
e && (u.style.cssText += "left:" + g + "px;top:" + h + "px;width:" + i + "px;height:" + j + "px;"), 
(c != D || d != E) && l.use && (f ? (C.canvas.width = c, C.canvas.height = d, M(C.canvas.width, C.canvas.height)) :(C.canvas.style.width = c + "px", 
C.canvas.style.height = d + "px"), D = c, E = d), v.style.cssText += "-webkit-transform: translate(" + g + "px, " + h + "px) scale(" + i / 100 + "," + (b - h) / 100 + ");", 
w.style.cssText += "-webkit-transform: translate(" + g + "px, " + (b + d) + "px) scale(" + i / 100 + "," + (h + j - b - d) / 100 + ");", 
x.style.cssText += "-webkit-transform: translate(" + g + "px, " + b + "px) scale(" + (a - g) / 100 + "," + d / 100 + ");", 
y.style.cssText += "-webkit-transform: translate(" + (c + a) + "px, " + b + "px) scale(" + (g + i - c - a) / 100 + "," + d / 100 + ");", 
z.style.cssText += "left:" + (c + a - 20) + "px;top:" + (b + d - 20) + "px;width:40px;height:40px;", 
A.style.cssText += "left:" + (a - 1) + "px;top:" + (b - 1) + "px;width:" + (c + 2) + "px;height:" + (d + 2) + "px;background-size:contain;opacity:0.8;background-position:center;background-repeat:no-repeat;";
}, K = 1, L = function(a, b, c, d, e, f, g) {
var h = e, i = f;
g > 1 ? i /= g :h *= g, g > 1 ? (a.drawImage(b, 0, 0, c, d, 0, f - i, h, i), a.fillRect(0, 0, e, f - i + 1)) :(a.drawImage(b, 0, 0, c, d, (e - h) / 2, 0, h, i), 
a.fillRect(0, 0, (e - h) / 2 + 1, f), a.fillRect((e + h) / 2 - 1, 0, (e - h) / 2 + 1, f));
}, M = function(a, b) {
a = a || n, b = b || o, l.use && (C.clearRect(0, 0, C.canvas.width, C.canvas.height), 
L(C, l.image, l.width, l.height, a, b, K));
}, N = function(a) {
var b = document.createElement("canvas"), c = b.getContext("2d");
b.width = a, b.height = a * p;
var d = (k - g) / r, f = (m - h) / r, i = n / r, j = o / r, s = a / i;
if (l.use) {
var t = document.createElement("canvas"), u = b.getContext("2d");
t.width = n, t.height = o, u.drawImage(q, d, f, i, j, 0, 0, i * s, j * s), u.globalCompositeOperation = "destination-out", 
L(u, l.image, l.width, l.height, i * s, j * s, K), c.drawImage(t, 0, 0);
} else c.drawImage(q, d, f, i, j, 0, 0, i * s, j * s);
return e.isApp() ? b.toDataURL("image/png") :b.toDataURL("image/jpeg", .7);
}, O = function() {
var b, c, d, e = !1, f = !1, l = !1, q = navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i);
a.addEventListener(q ? "touchstart" :"mousedown", function(g) {
if (g.preventDefault(), !e && (q || (g.touches = [ {
clientX:g.clientX,
clientY:g.clientY
} ]), g.touches && g.touches.length)) {
b = g.touches[0].clientX, c = g.touches[0].clientY;
for (var h = a, i = 0, j = 0; h && "BODY" != h.tagName; ) i += h.offsetTop, j += h.offsetLeft, 
h = h.offsetParent;
var p = b - j + window.pageXOffset, r = c - i + window.pageYOffset;
f = Math.pow(p - (k + n), 2) + Math.pow(r - (m + o), 2) < 576, l = !f && p > k && k + n > p && r > m && m + o > r, 
e = f || l, e && (d = g.touches[0].identifier);
}
}), a.addEventListener(q ? "touchmove" :"mousemove", function(a) {
if (a.preventDefault(), e && (q || (a.touches = [ {
clientX:a.clientX,
clientY:a.clientY
} ]), a.touches && a.touches.length)) {
var d = a.touches[0].clientX - b, f = a.touches[0].clientY - c;
r(d, f);
}
}), a.addEventListener(q ? "touchend" :"mouseup", function(a) {
if (a.preventDefault(), e && (q || (a.changedTouches = [ {
clientX:a.clientX,
clientY:a.clientY
} ]), a.changedTouches && a.changedTouches.length)) for (var f = 0; f < a.changedTouches.length; f++) if (a.changedTouches[f].identifier == d) {
var g = a.changedTouches[f].clientX - b, h = a.changedTouches[f].clientY - c;
r(g, h, !0), e = !1;
break;
}
});
var r = function(a, b, c) {
var d = 64;
if (f) {
var e = p > 1, q = a, r = q * p;
r > b && (r = b, q = b / p);
var s, t;
e ? (t = Math.max(d, o + r), s = t / p) :(s = Math.max(d, n + q), t = s * p), s + k > g + i && (s = g + i - k, 
t = s * p), t + m > h + j && (t = h + j - m, s = t / p), J(k, m, s, t), c && (n = s, 
o = t);
} else if (l) {
var u = Math.max(g, k + a), v = Math.max(h, m + b);
n + u > g + i && (u = g + i - n), o + v > h + j && (v = h + j - o), J(u, v, n, o), 
c && (k = u, m = v);
}
};
}, P = function(a) {
K = a / 1 * 1.5 + .5, l.use && M(C.canvas.width, C.canvas.height);
};
return {
initUI:F,
setSize:G,
placeImageBase64:H,
drawMask:J,
getcuttedBase64:N,
changePercent:P
};
}, t = "http://wslb.mugeda.com/server/";
b.MugedaImageUploader = function() {
function a() {
this.parentDiv = null, this.inited = !1, this.formElem = null, this.ifElem = null, 
this.process = null;
var a = this;
this.handledEvent = function(b) {
a._handleMessage(b);
};
}
a.prototype.initUploader = function(a, b) {
this.parentDiv = a, this.inited = !0, this.callback = b || function() {};
var c = document.createElement("form");
c.action = t + "/game_fbb.php?method=server", c.target = "hiddenIframe", c.enctype = "multipart/form-data", 
c.method = "POST", c.style.cssText += "position: absolute; width: 100%; height: 100%; overflow: hidden; opacity:0;", 
this.parentDiv.appendChild(c), this.formElem = c;
var d = document.createElement("input");
d.type = "file", d.accept = "image/*", d.style.cssText += "position: absolute; width: 100%; height: 100%; overflow: hidden;", 
d.name = "photo", c.appendChild(d);
var e = this;
d.onchange = function() {
e._handleImageFile.call(e, this.files);
};
var f = document.createElement("iframe");
f.name = "hiddenIframe", f.style.display = "none", c.appendChild(f), this.ifElem = f, 
window.addEventListener("message", this.handledEvent);
}, a.prototype.removeUploader = function() {
this.inited && (this.parentDiv.removeChild(this.formElem), window.removeEventListener("message", this.handledEvent), 
this.inited = !1);
}, a.prototype._handleImageFile = function(a) {
if (this._fireEvent("selected", "检查图片类型"), 1 == a.length) {
var b = a[0], c = /image.*/;
b.type && !b.type.match(c) ? this._fireEvent("error", "选择的文件不是有效的图片。", null) :this._handleImage(b);
} else this._fireEvent("error", "用户取消选择文件");
}, a.prototype._handleImage = function(a) {
var b = window.URL;
null == b && (b = window.webkitURL), b && this._handleImageLocally(b, a) || this._handleImageServer(a);
}, a.prototype._handleImageServer = function() {
this._fireEvent("upload", "正在上传图片，可能需要一段时间"), this.formElem.submit();
}, a.prototype._handleImageLocally = function(a, c) {
var e = c, f = 131072;
if (e = c.slice ? c.slice(0, f) :c.webkitSlice ? c.webkitSlice(0, f) :c.mozSlice ? c.mozSlice(0, f) :c, 
!window.FileReader) return !1;
this._fireEvent("read", "正在读取图片");
var g = new FileReader(), h = this;
g.readAsBinaryString(e), g.onload = function() {
var a = new b(this.result), c = d.readFromBinaryFile(a), e = a.getByteAt(0), f = a.getByteAt(1), g = 71 == e && 73 == f || 255 == e && 216 == f || 66 == e && 77 == f || 137 == e && 80 == f, i = 255 == e && 216 == f;
return g ? void (c && c.Orientation ? 8 == c.Orientation ? j(90, i) :3 == c.Orientation ? j(180, i) :6 == c.Orientation ? j(-90, i) :j(0, i) :j(0, i)) :h._fireEvent("error", "选择的文件不是有效的图片。", null);
};
var i = this, j = function(b, d) {
i._fireEvent("scale", "正在缩放图片", null);
var e = new Image();
e.src = a.createObjectURL(c), e.onload = function() {
a.revokeObjectURL(this.src);
var c = e.width, f = e.height, g = m(e, c, f, b, 640, 832, d), h = m(e, c, f, b, 160, 208, d);
i._fireEvent("ok", "处理完毕", {
small:h,
big:g,
method:"local"
});
};
}, k = function(a) {
var b = a.naturalWidth, c = a.naturalHeight;
if (b * c > 1048576) {
var d = document.createElement("canvas");
d.width = d.height = 1;
var e = d.getContext("2d");
return e.drawImage(a, -b + 1, 0), 0 === e.getImageData(0, 0, 1, 1).data[3];
}
return !1;
}, l = function(a, b, c) {
var d = document.createElement("canvas");
d.width = b, d.height = c;
var e = d.getContext("2d");
e.drawImage(a, 0, 0, b, c);
for (var f = e.getImageData(0, 0, 1, c).data, g = 0, h = c, i = c; i > g; ) {
var j = f[4 * (i - 1) + 3];
0 === j ? h = i :g = i, i = h + g >> 1;
}
var k = i / c;
return 0 === k ? 1 :k;
}, m = function(a, b, c, d, e, f, g) {
var h = e / b, i = f / c, j = Math.min(h, i);
j = Math.min(1, j);
var m = Math.floor(b * j), n = Math.floor(c * j), o = 90 == Math.abs(d) ? n :m, p = 90 == Math.abs(d) ? m :n, q = document.createElement("canvas");
q.width = o, q.height = p;
var r = 1;
g && k(a) && (r = l(a, o, p));
var s = q.getContext("2d");
return s.save(), s.translate(o / 2, p / 2), s.rotate(-d / 180 * Math.PI), s.drawImage(a, -m / 2, -n / 2, m, n / r), 
s.restore(), q.toDataURL("image/jpeg");
};
return !0;
}, a.prototype._handleMessage = function(a) {
if (a.source == this.ifElem.contentWindow) if (a.data) {
var b = JSON.parse(a.data);
"over" == b.type && this._fireEvent("ok", "处理完毕", {
small:b.data.thumb,
big:b.data.path,
method:"server"
});
} else this._fireEvent("error", a.data.message);
}, a.prototype.beforeSend = function(a) {
if (this.callback2 = a || function() {}, "server" == this.method) {
var b = this;
setTimeout(function() {
b._fireEvent("upload_m_ok", null, b.imgBigData, this.callback2);
});
} else if ("local" == this.method) {
this._fireEvent("upload_m", "正在上传图片", null, this.callback2);
var d = this.imgBigData.split(","), e = d[0].split(";")[0].split(":")[1], f = d[1], b = this;
c.ajax({
type:"POST",
url:t + "game_fbb.php?method=base64",
data:{
type:e,
base64:f
},
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(a) {
a.err ? b._fireEvent("error", a.message, null, this.callback2) :b._fireEvent("upload_m_ok", null, a.big, this.callback2);
},
error:function(a, c) {
b._fireEvent("error", c, null, this.callback2);
}
});
}
}, a.prototype._fireEvent = function(a, b, c, d) {
"ok" == a ? (this.imgBigData = c.big, this.imgSmallData = c.small, this.method = c.method, 
this.callback(a, null, c)) :"upload_m_ok" == a ? (this.imgBigData = c, this.callback2(a, null, c)) :(d || this.callback)(a, b, c);
};
var b = function(a, b, c) {
var d = a, e = b || 0, f = 0;
this.getRawData = function() {
return d;
}, "string" == typeof a ? (f = c || d.length, this.getByteAt = function(a) {
return 255 & d.charCodeAt(a + e);
}, this.getBytesAt = function(a, b) {
for (var c = [], f = 0; b > f; f++) c[f] = 255 & d.charCodeAt(a + f + e);
return c;
}) :"unknown" == typeof a && (f = c || IEBinary_getLength(d), this.getByteAt = function(a) {
return IEBinary_getByteAt(d, a + e);
}, this.getBytesAt = function(a, b) {
return new VBArray(IEBinary_getBytesAt(d, a + e, b)).toArray();
}), this.getLength = function() {
return f;
}, this.getSByteAt = function(a) {
var b = this.getByteAt(a);
return b > 127 ? b - 256 :b;
}, this.getShortAt = function(a, b) {
var c = b ? (this.getByteAt(a) << 8) + this.getByteAt(a + 1) :(this.getByteAt(a + 1) << 8) + this.getByteAt(a);
return 0 > c && (c += 65536), c;
}, this.getSShortAt = function(a, b) {
var c = this.getShortAt(a, b);
return c > 32767 ? c - 65536 :c;
}, this.getLongAt = function(a, b) {
var c = this.getByteAt(a), d = this.getByteAt(a + 1), e = this.getByteAt(a + 2), f = this.getByteAt(a + 3), g = b ? (((c << 8) + d << 8) + e << 8) + f :(((f << 8) + e << 8) + d << 8) + c;
return 0 > g && (g += 4294967296), g;
}, this.getSLongAt = function(a, b) {
var c = this.getLongAt(a, b);
return c > 2147483647 ? c - 4294967296 :c;
}, this.getStringAt = function(a, b) {
for (var c = [], d = this.getBytesAt(a, b), e = 0; b > e; e++) c[e] = String.fromCharCode(d[e]);
return c.join("");
}, this.getCharAt = function(a) {
return String.fromCharCode(this.getByteAt(a));
}, this.toBase64 = function() {
return window.btoa(d);
}, this.fromBase64 = function(a) {
d = window.atob(a);
};
}, d = (function() {
function a() {
var a = null;
return window.ActiveXObject ? a = new ActiveXObject("Microsoft.XMLHTTP") :window.XMLHttpRequest && (a = new XMLHttpRequest()), 
a;
}
function c(b, c, d) {
var e = a();
e ? (c && ("undefined" != typeof e.onload ? e.onload = function() {
"200" == e.status ? c(this) :d && d(), e = null;
} :e.onreadystatechange = function() {
4 == e.readyState && ("200" == e.status ? c(this) :d && d(), e = null);
}), e.open("HEAD", b, !0), e.send(null)) :d && d();
}
function d(c, d, e, f, g, h) {
var i = a();
if (i) {
var j = 0;
f && !g && (j = f[0]);
var k = 0;
f && (k = f[1] - f[0] + 1), d && ("undefined" != typeof i.onload ? i.onload = function() {
"200" == i.status || "206" == i.status || "0" == i.status ? (i.binaryResponse = new b(i.responseText, j, k), 
i.fileSize = h || i.getResponseHeader("Content-Length"), d(i)) :e && e(), i = null;
} :i.onreadystatechange = function() {
if (4 == i.readyState) {
if ("200" == i.status || "206" == i.status || "0" == i.status) {
var a = {
status:i.status,
binaryResponse:new b("unknown" == typeof i.responseBody ? i.responseBody :i.responseText, j, k),
fileSize:h || i.getResponseHeader("Content-Length")
};
d(a);
} else e && e();
i = null;
}
}), i.open("GET", c, !0), i.overrideMimeType && i.overrideMimeType("text/plain; charset=x-user-defined"), 
f && g && i.setRequestHeader("Range", "bytes=" + f[0] + "-" + f[1]), i.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 1970 00:00:00 GMT"), 
i.send(null);
} else e && e();
}
return function(a, b, e, f) {
f ? c(a, function(c) {
var g, h, i = parseInt(c.getResponseHeader("Content-Length"), 10), j = c.getResponseHeader("Accept-Ranges");
g = f[0], f[0] < 0 && (g += i), h = g + f[1] - 1, d(a, b, e, [ g, h ], "bytes" == j, i);
}) :d(a, b, e);
};
}(), {});
return function() {
function a(a) {
if (255 != a.getByteAt(0) || 216 != a.getByteAt(1)) return !1;
for (var b = 2, c = a.getLength(); c > b; ) {
if (255 != a.getByteAt(b)) return f && console.log("Not a valid marker at offset " + b + ", found: " + a.getByteAt(b)), 
!1;
var d = a.getByteAt(b + 1);
if (22400 == d) return f && console.log("Found 0xFFE1 marker"), e(a, b + 4, a.getShortAt(b + 2, !0) - 2);
if (225 == d) return f && console.log("Found 0xFFE1 marker"), e(a, b + 4, a.getShortAt(b + 2, !0) - 2);
b += 2 + a.getShortAt(b + 2, !0);
}
}
function b(a, b, d, e, g) {
for (var h = a.getShortAt(d, g), i = {}, j = 0; h > j; j++) {
var k = d + 12 * j + 2, l = e[a.getShortAt(k, g)];
!l && f && console.log("Unknown tag: " + a.getShortAt(k, g)), i[l] = c(a, k, b, d, g);
}
return i;
}
function c(a, b, c, d, e) {
var f = a.getShortAt(b + 2, e), g = a.getLongAt(b + 4, e), h = a.getLongAt(b + 8, e) + c;
switch (f) {
case 1:
case 7:
if (1 == g) return a.getByteAt(b + 8, e);
for (var i = g > 4 ? h :b + 8, j = [], k = 0; g > k; k++) j[k] = a.getByteAt(i + k);
return j;

case 2:
var l = g > 4 ? h :b + 8;
return a.getStringAt(l, g - 1);

case 3:
if (1 == g) return a.getShortAt(b + 8, e);
for (var i = g > 2 ? h :b + 8, j = [], k = 0; g > k; k++) j[k] = a.getShortAt(i + 2 * k, e);
return j;

case 4:
if (1 == g) return a.getLongAt(b + 8, e);
for (var j = [], k = 0; g > k; k++) j[k] = a.getLongAt(h + 4 * k, e);
return j;

case 5:
if (1 == g) return a.getLongAt(h, e) / a.getLongAt(h + 4, e);
for (var j = [], k = 0; g > k; k++) j[k] = a.getLongAt(h + 8 * k, e) / a.getLongAt(h + 4 + 8 * k, e);
return j;

case 9:
if (1 == g) return a.getSLongAt(b + 8, e);
for (var j = [], k = 0; g > k; k++) j[k] = a.getSLongAt(h + 4 * k, e);
return j;

case 10:
if (1 == g) return a.getSLongAt(h, e) / a.getSLongAt(h + 4, e);
for (var j = [], k = 0; g > k; k++) j[k] = a.getSLongAt(h + 8 * k, e) / a.getSLongAt(h + 4 + 8 * k, e);
return j;
}
}
function e(a, c) {
if ("Exif" != a.getStringAt(c, 4)) return f && console.log("Not valid EXIF data! " + a.getStringAt(c, 4)), 
!1;
var e, g = c + 6;
if (18761 == a.getShortAt(g)) e = !1; else {
if (19789 != a.getShortAt(g)) return f && console.log("Not valid TIFF data! (no 0x4949 or 0x4D4D)"), 
!1;
e = !0;
}
if (42 != a.getShortAt(g + 2, e)) return f && console.log("Not valid TIFF data! (no 0x002A)"), 
!1;
if (8 != a.getLongAt(g + 4, e)) return f && console.log("Not valid TIFF data! (First offset not 8)", a.getShortAt(g + 4, e)), 
!1;
var h = b(a, g, g + 8, d.TiffTags, e);
if (h.ExifIFDPointer) {
var i = b(a, g, g + h.ExifIFDPointer, d.Tags, e);
for (var j in i) {
switch (j) {
case "LightSource":
case "Flash":
case "MeteringMode":
case "ExposureProgram":
case "SensingMethod":
case "SceneCaptureType":
case "SceneType":
case "CustomRendered":
case "WhiteBalance":
case "GainControl":
case "Contrast":
case "Saturation":
case "Sharpness":
case "SubjectDistanceRange":
case "FileSource":
i[j] = d.StringValues[j][i[j]];
break;

case "ExifVersion":
case "FlashpixVersion":
i[j] = String.fromCharCode(i[j][0], i[j][1], i[j][2], i[j][3]);
break;

case "ComponentsConfiguration":
i[j] = d.StringValues.Components[i[j][0]] + d.StringValues.Components[i[j][1]] + d.StringValues.Components[i[j][2]] + d.StringValues.Components[i[j][3]];
}
h[j] = i[j];
}
}
if (h.GPSInfoIFDPointer) {
var k = b(a, g, g + h.GPSInfoIFDPointer, d.GPSTags, e);
for (var j in k) {
switch (j) {
case "GPSVersionID":
k[j] = k[j][0] + "." + k[j][1] + "." + k[j][2] + "." + k[j][3];
}
h[j] = k[j];
}
}
return h;
}
var f = !1;
d.Tags = {
36864:"ExifVersion",
40960:"FlashpixVersion",
40961:"ColorSpace",
40962:"PixelXDimension",
40963:"PixelYDimension",
37121:"ComponentsConfiguration",
37122:"CompressedBitsPerPixel",
37500:"MakerNote",
37510:"UserComment",
40964:"RelatedSoundFile",
36867:"DateTimeOriginal",
36868:"DateTimeDigitized",
37520:"SubsecTime",
37521:"SubsecTimeOriginal",
37522:"SubsecTimeDigitized",
33434:"ExposureTime",
33437:"FNumber",
34850:"ExposureProgram",
34852:"SpectralSensitivity",
34855:"ISOSpeedRatings",
34856:"OECF",
37377:"ShutterSpeedValue",
37378:"ApertureValue",
37379:"BrightnessValue",
37380:"ExposureBias",
37381:"MaxApertureValue",
37382:"SubjectDistance",
37383:"MeteringMode",
37384:"LightSource",
37385:"Flash",
37396:"SubjectArea",
37386:"FocalLength",
41483:"FlashEnergy",
41484:"SpatialFrequencyResponse",
41486:"FocalPlaneXResolution",
41487:"FocalPlaneYResolution",
41488:"FocalPlaneResolutionUnit",
41492:"SubjectLocation",
41493:"ExposureIndex",
41495:"SensingMethod",
41728:"FileSource",
41729:"SceneType",
41730:"CFAPattern",
41985:"CustomRendered",
41986:"ExposureMode",
41987:"WhiteBalance",
41988:"DigitalZoomRation",
41989:"FocalLengthIn35mmFilm",
41990:"SceneCaptureType",
41991:"GainControl",
41992:"Contrast",
41993:"Saturation",
41994:"Sharpness",
41995:"DeviceSettingDescription",
41996:"SubjectDistanceRange",
40965:"InteroperabilityIFDPointer",
42016:"ImageUniqueID"
}, d.TiffTags = {
256:"ImageWidth",
257:"ImageHeight",
34665:"ExifIFDPointer",
34853:"GPSInfoIFDPointer",
40965:"InteroperabilityIFDPointer",
258:"BitsPerSample",
259:"Compression",
262:"PhotometricInterpretation",
274:"Orientation",
277:"SamplesPerPixel",
284:"PlanarConfiguration",
530:"YCbCrSubSampling",
531:"YCbCrPositioning",
282:"XResolution",
283:"YResolution",
296:"ResolutionUnit",
273:"StripOffsets",
278:"RowsPerStrip",
279:"StripByteCounts",
513:"JPEGInterchangeFormat",
514:"JPEGInterchangeFormatLength",
301:"TransferFunction",
318:"WhitePoint",
319:"PrimaryChromaticities",
529:"YCbCrCoefficients",
532:"ReferenceBlackWhite",
306:"DateTime",
270:"ImageDescription",
271:"Make",
272:"Model",
305:"Software",
315:"Artist",
33432:"Copyright"
}, d.GPSTags = {
0:"GPSVersionID",
1:"GPSLatitudeRef",
2:"GPSLatitude",
3:"GPSLongitudeRef",
4:"GPSLongitude",
5:"GPSAltitudeRef",
6:"GPSAltitude",
7:"GPSTimeStamp",
8:"GPSSatellites",
9:"GPSStatus",
10:"GPSMeasureMode",
11:"GPSDOP",
12:"GPSSpeedRef",
13:"GPSSpeed",
14:"GPSTrackRef",
15:"GPSTrack",
16:"GPSImgDirectionRef",
17:"GPSImgDirection",
18:"GPSMapDatum",
19:"GPSDestLatitudeRef",
20:"GPSDestLatitude",
21:"GPSDestLongitudeRef",
22:"GPSDestLongitude",
23:"GPSDestBearingRef",
24:"GPSDestBearing",
25:"GPSDestDistanceRef",
26:"GPSDestDistance",
27:"GPSProcessingMethod",
28:"GPSAreaInformation",
29:"GPSDateStamp",
30:"GPSDifferential"
}, d.StringValues = {
ExposureProgram:{
0:"Not defined",
1:"Manual",
2:"Normal program",
3:"Aperture priority",
4:"Shutter priority",
5:"Creative program",
6:"Action program",
7:"Portrait mode",
8:"Landscape mode"
},
MeteringMode:{
0:"Unknown",
1:"Average",
2:"CenterWeightedAverage",
3:"Spot",
4:"MultiSpot",
5:"Pattern",
6:"Partial",
255:"Other"
},
LightSource:{
0:"Unknown",
1:"Daylight",
2:"Fluorescent",
3:"Tungsten (incandescent light)",
4:"Flash",
9:"Fine weather",
10:"Cloudy weather",
11:"Shade",
12:"Daylight fluorescent (D 5700 - 7100K)",
13:"Day white fluorescent (N 4600 - 5400K)",
14:"Cool white fluorescent (W 3900 - 4500K)",
15:"White fluorescent (WW 3200 - 3700K)",
17:"Standard light A",
18:"Standard light B",
19:"Standard light C",
20:"D55",
21:"D65",
22:"D75",
23:"D50",
24:"ISO studio tungsten",
255:"Other"
},
Flash:{
0:"Flash did not fire",
1:"Flash fired",
5:"Strobe return light not detected",
7:"Strobe return light detected",
9:"Flash fired, compulsory flash mode",
13:"Flash fired, compulsory flash mode, return light not detected",
15:"Flash fired, compulsory flash mode, return light detected",
16:"Flash did not fire, compulsory flash mode",
24:"Flash did not fire, auto mode",
25:"Flash fired, auto mode",
29:"Flash fired, auto mode, return light not detected",
31:"Flash fired, auto mode, return light detected",
32:"No flash function",
65:"Flash fired, red-eye reduction mode",
69:"Flash fired, red-eye reduction mode, return light not detected",
71:"Flash fired, red-eye reduction mode, return light detected",
73:"Flash fired, compulsory flash mode, red-eye reduction mode",
77:"Flash fired, compulsory flash mode, red-eye reduction mode, return light not detected",
79:"Flash fired, compulsory flash mode, red-eye reduction mode, return light detected",
89:"Flash fired, auto mode, red-eye reduction mode",
93:"Flash fired, auto mode, return light not detected, red-eye reduction mode",
95:"Flash fired, auto mode, return light detected, red-eye reduction mode"
},
SensingMethod:{
1:"Not defined",
2:"One-chip color area sensor",
3:"Two-chip color area sensor",
4:"Three-chip color area sensor",
5:"Color sequential area sensor",
7:"Trilinear sensor",
8:"Color sequential linear sensor"
},
SceneCaptureType:{
0:"Standard",
1:"Landscape",
2:"Portrait",
3:"Night scene"
},
SceneType:{
1:"Directly photographed"
},
CustomRendered:{
0:"Normal process",
1:"Custom process"
},
WhiteBalance:{
0:"Auto white balance",
1:"Manual white balance"
},
GainControl:{
0:"None",
1:"Low gain up",
2:"High gain up",
3:"Low gain down",
4:"High gain down"
},
Contrast:{
0:"Normal",
1:"Soft",
2:"Hard"
},
Saturation:{
0:"Normal",
1:"Low saturation",
2:"High saturation"
},
Sharpness:{
0:"Normal",
1:"Soft",
2:"Hard"
},
SubjectDistanceRange:{
0:"Unknown",
1:"Macro",
2:"Close view",
3:"Distant view"
},
FileSource:{
3:"DSC"
},
Components:{
0:"",
1:"Y",
2:"Cb",
3:"Cr",
4:"R",
5:"G",
6:"B"
}
}, d.readFromBinaryFile = function(b) {
return a(b);
};
}(), a;
}();
}), define("scripts/promo", [ "./vendor/promise", "./tpl/promoHtml", "./tpl/template" ], function(a, b) {
var c = a("./vendor/promise"), d = a("./tpl/promoHtml"), e = function(a) {
return new c(function(b, c) {
$.ajax({
url:"http://weika.mugeda.com/server/cards.php/cards/recommend",
method:"GET",
dataType:"jsonp",
jsonpCallback:"Mucard_callback",
data:{
crid:a
},
success:function(a) {
b(a);
},
error:function(a) {
c(a);
}
});
});
}, f = function(a) {
if (a.recommend) {
var b = d({
data:a
});
MugedaTracker.fireEvent({
category:"promotion",
action:"view",
value:0,
label:"展示推荐"
});
var c = $("#js-promo");
c[0] ? $("#js-promo").show() :($("#stageParent").append($(b)), c = $("#js-promo"), 
$("#js-promo-show").html(), $("#js-promo-close").on("click", function() {
c.hide(), MugedaTracker.fireEvent({
category:"promotion",
action:"clickCancel",
value:2,
label:"下次吧"
});
}), $("#js-promo-more").on("click", function() {
MugedaTracker.fireEvent({
category:"promotion",
action:"clickMore",
value:1,
label:"更多"
}), window.location = "http://mp.weixin.qq.com/s?__biz=MzA3MzMwMTgwNQ==&mid=201265173&idx=1&sn=8a370252d80d26320ba08d3ba114bc6b#rd";
}));
}
};
b.showPromo = function(a) {
e(a).then(f);
};
}), define("scripts/receipt", [ "./tpl/receipt.frame", "./tpl/receipt_item", "./tpl/template", "./vendor/moment", "./vendor/fastclick" ], function(a, b) {
var c = a("./tpl/receipt.frame"), d = a("./tpl/receipt_item"), e = a("./vendor/moment"), f = a("./vendor/fastclick");
new f(document.body);
var g = null, h = null, i = function(a) {
var b = document.createElement("div");
return b.innerHTML = a, b;
};
b.buildFrame = function(a, b) {
for (var d = b.list, f = 0, j = 0, k = 0, l = 0; l < d.length; l++) {
var m = parseInt(d[l].value);
0 == m ? f++ :1 == m ? j++ :2 == m && k++;
}
for (var n = {
title:b.title,
rev:b.replyCount,
time:e(1e3 * b.customTime).format("YYYY-M-D HH:mm"),
thumb:"http://mucard.mugeda.com/weixin/card/cards/" + b.crid + "/" + b.thumb,
back:b.back,
numAttend:f,
numNotAttend:j,
numMaybe:k,
goback:b.redirect,
privateCard:b["private"]
}, o = i(c(n)).childNodes, l = 0; l < o.length; l++) a.appendChild(o[l]);
g = a.getElementsByClassName("main")[0], h = a;
};
var j = function(a) {
var b = e(1e3 * a), c = e(), d = c.diff(b, "hours"), f = c.diff(b, "days"), g = c.diff(b, "months");
return 1 > d ? b.fromNow() :b.format(24 > d ? "Ah:mm" :7 > f ? "dddd" :12 > g ? "M-D" :"YYYY-M-D");
};
b.renderOne = function(a) {
var b = {
attend:parseInt(a.value),
name:a.name,
avatar:a.avatar,
time:j(a.time),
content:decodeURIComponent(a.data).replace(/\n/g, "<br />")
}, c = i(d(b)).childNodes[0];
return c;
}, b.renderAll = function(a) {
for (var c = 0; c < a.length; c++) b.append(b.renderOne(a[c]));
};
var k = function(a, b) {
return a.className.match(new RegExp("(\\s|^)" + b + "(\\s|$)"));
}, l = function(a, b) {
k(a, b) || (a.className += " " + b);
}, m = function(a, b) {
if (k(a, b)) {
var c = new RegExp("(\\s|^)" + b + "(\\s|$)");
a.className = a.className.replace(c, " ");
}
}, n = null, o = null, p = null, q = function(a) {
if ("all" == a) m(n, "disable"), m(o, "disable"), m(p, "disable"); else {
l(n, "disable"), l(o, "disable"), l(p, "disable");
var b = h.querySelectorAll(".numline ." + a)[0];
m(b, "disable");
}
}, r = "all", s = function(a) {
r = a != r ? a :a = "all", q(a);
for (var b = g.querySelectorAll(".item"), c = 0; c < b.length; c++) {
var d = b[c];
d.style.display = d.querySelectorAll("." + a).length || "all" == a ? "block" :"none";
}
};
b.init = function(a) {
return a ? (a.list.sort(function(a, b) {
return b.time - a.time;
}), b.buildFrame(document.body, a), b.renderAll(a.list), n = h.querySelectorAll(".numline .attend")[0], 
o = h.querySelectorAll(".numline .notattend")[0], p = h.querySelectorAll(".numline .maybe")[0], 
n.addEventListener("click", function() {
s("attend");
}), o.addEventListener("click", function() {
s("notattend");
}), void p.addEventListener("click", function() {
s("maybe");
})) :(alert("抱歉，您没有权限查看回复列表"), void history.back());
}, b.append = function(a) {
g.appendChild(a);
};
}), define("scripts/tpl/audioIco", [ "./template" ], function(a) {
return a("./template")("audioIco", '<div style="position: fixed; top: 20px; right: 10px;"> <div class="on ico" style="background: url(\'images/audioon.png\');width:36px;height:22px;"></div> <div class="off ico" style="background: url(\'images/audiooff.png\');width:36px;height:22px;"></div> <div class="loading ico" style="background: url(\'images/audioloading.gif\');width:20px;height:20px;margin-right:10px;"></div> </div>');
}), define("scripts/tpl/audioPlay", [ "./template" ], function(a) {
return a("./template")("audioPlay", '<style> .clickSound { position: fixed; left: -200px; top: 75%; margin-top: -19px; width:135px; background: url("images/audio_corner.png") left center no-repeat; background-size: 8px 11px; padding-left: 7px; vertical-align: middle; margin-left: -70px; } .clickSound > div{ border-radius: 5px; height: 38px; line-height: 38px; background: #abd320; background: -moz-linear-gradient(top, #abd320 0%, #8fc31f 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#abd320), color-stop(100%,#8fc31f)); background: -webkit-linear-gradient(top, #abd320 0%,#8fc31f 100%); background: -o-linear-gradient(top, #abd320 0%,#8fc31f 100%); background: -ms-linear-gradient(top, #abd320 0%,#8fc31f 100%); background: linear-gradient(top, #abd320 0%,#8fc31f 100%); } .clickSound .co{ padding-left: 30px; background-position:13px center; background-repeat: no-repeat; background-size: 10px 18px; } .clickSound .still .co{ background-image: url("images/audio_still.png"); } .clickSound .playing .co{ background-image: url("images/audio_playing.png"); color: #fff; } .clickSound.moving { background: url("images/audio_corner_hot.png") left center no-repeat; } .clickSound.moving > div{ background: #95b232; background: -moz-linear-gradient(top, #95b232 0%, #719527 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#95b232), color-stop(100%,#719527)); background: -webkit-linear-gradient(top, #95b232 0%,#719527 100%); background: -o-linear-gradient(top, #95b232 0%,#719527 100%); background: -ms-linear-gradient(top, #95b232 0%,#719527 100%); background: linear-gradient(top, #95b232 0%,#719527 100%); } .clickSound .playing{ display: none; } </style> <div class="clickSound sm"> <div class="still"><div class="co">点击收听留言</div></div> <div class="playing"><div class="co">收听中 ...</div></div> </div>');
}), !function(a) {
"use strict";
var b = function(a, c) {
return b[/string|function/.test(typeof c) ? "compile" :"render"].apply(b, arguments);
}, c = b.cache = {}, d = function(a, b) {
return "string" != typeof a && (b = typeof a, "number" === b ? a += "" :a = "function" === b ? d(a.call(a)) :""), 
a;
}, e = {
"<":"&#60;",
">":"&#62;",
'"':"&#34;",
"'":"&#39;",
"&":"&#38;"
}, f = function(a) {
return d(a).replace(/&(?![\w#]+;)|[<>"']/g, function(a) {
return e[a];
});
}, g = Array.isArray || function(a) {
return "[object Array]" === {}.toString.call(a);
}, h = function(a, b) {
if (g(a)) for (var c = 0, d = a.length; d > c; c++) b.call(a, a[c], c, a); else for (c in a) b.call(a, a[c], c);
}, i = function(a, b) {
var c = /(\/)[^/]+\1\.\.\1/, d = a.replace(/^([^.])/, "./$1").replace(/[^/]+$/, ""), e = d + b;
for (e = e.replace(/\/\.\//g, "/"); e.match(c); ) e = e.replace(c, "/");
return e;
}, j = b.helpers = {
$include:function(a, c, d) {
var e = i(d, a);
return b.render(e, c);
},
$string:d,
$escape:f,
$each:h
}, k = function(b) {
var c = "";
for (var d in b) c += "<" + d + ">\n" + b[d] + "\n\n";
return c && a.console && console.error("Template Error\n\n" + c), function() {
return "{Template Error}";
};
};
b.render = function(a, c) {
var d = b.get(a) || k({
id:a,
name:"Render Error",
message:"No Template"
});
return c ? d(c) :d;
}, b.compile = function(a, b) {
var d = "function" == typeof b, e = c[a] = function(c) {
try {
return d ? new b(c, a) + "" :b;
} catch (e) {
return k(e)();
}
};
return e.prototype = j, d && (b.prototype = j), e.toString = function() {
return b + "";
}, e;
}, b.get = function(a) {
return c[a.replace(/^\.\//, "")];
}, b.helper = function(a, b) {
j[a] = b;
}, "function" == typeof define ? define("scripts/tpl/build/template", [], function() {
return b;
}) :"undefined" != typeof exports ? module.exports = b :a.template = b;
}(this), define("scripts/tpl/cardTip", [ "./template" ], function(a) {
return a("./template")("cardTip", '<style> .cardTip_div{ position:absolute; top:0; left:0; height:0; bottom:0; width:100%; height:100%; background:rgba(0, 0, 0, 0.40); overflow:hidden; } .cardTip_img img{ height:110px; width:75px; animation: personShake 1.6s infinite linear; -webkit-animation: personShake 1.6s infinite linear; } @keyframes personShake{ 0% { transform: translate(-13px, 17px); } 8.7% { transform: translate(0, 0); } 17.4% { transform: translate(-13px, 17px); } 26.1% { transform: translate(0, 0); } 34.8% { transform: translate(-13px, 17px); } 100% { transform: translate(-13px, 17px); } } @-webkit-keyframes personShake{ 0% { -webkit-transform: translate(-13px, 17px); } 8.7% { -webkit-transform: translate(0, 0); } 17.4% { -webkit-transform: translate(-13px, 17px); } 26.1% { -webkit-transform: translate(0, 0); } 34.8% { -webkit-transform: translate(-13px, 17px); } 100% { -webkit-transform: translate(-13px, 17px); } } .cardTip_arrow{ position:absolute; top:5px; right:40px; } .cardTip_weixin{ position:absolute; top:-20px; left:0; right:0; bottom:0; width:160px; height:81.4px; margin:auto; } </style> <div class="cardTip_div"> <div class="cardTip_img cardTip_arrow"> <img class=\'send_arrow\' src="images/smallPerson.gif" /> </div>  </div> ');
}), define("scripts/tpl/cardviewBottom", [ "./template" ], function(a) {
return a("./template")("cardviewBottom", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.isCustomed), d = "";
return d += '<style> #cardview_bottom .btn-frame{ display: inline-block; width: 25%; height: 71px; border-radius: 50%; float: left; } #cardview_bottom .btn-frame div{ width: 71px; height: 71px; margin:auto; background-size: 71px 71px!important; } #cardview_bottom.hide{ bottom: -60px!important; opacity: 0; text-align: left!important; margin-left: 50px; } #cardview_bottom.hide .btn-frame{ margin: 0 -35.5px!important; } #cardview_button.hide { bottom: -70px!important; opacity: 0; } #cardview_button.init { opacity: 0.8; } #cardview_button.fade{ -webkit-animation: cardview_fade_animation 5s infinite linear; -moz-animation: cardview_fade_animation 5s infinite linear; -ms-animation: cardview_fade_animation 5s infinite linear; -o-animation: cardview_fade_animation 5s infinite linear; animation: cardview_fade_animation 5s infinite linear; } #cardview_button .cardview_scale_animation{ opacity: 0.3; } .cardview_button_transition{ transition: all 333ms;-moz-transition: all 333ms;-webkit-transition: all 333ms;-o-transition: all 333ms; } .cardview_button_transition.init{ transition: all 1s;-moz-transition: all 1s;-webkit-transition: all 1s;-o-transition: all 1s; } .cardview_scale_animation { -webkit-animation: cardview_scale_animation 910ms infinite linear; -moz-animation: cardview_scale_animation 910ms infinite linear; -ms-animation: cardview_scale_animation 910ms infinite linear; -o-animation: cardview_scale_animation 910ms infinite linear; animation: cardview_scale_animation 910ms infinite linear; } @-webkit-keyframes cardview_scale_animation{ 0%{ -webkit-transform : scale(0.9); opacity: 0.6; } 100%{ -webkit-transform : scale(1.2); opacity: 0; } } @-moz-keyframes cardview_scale_animation{ 0%{ -moz-transform : scale(0.9); opacity: 0.6; } 100%{ -moz-transform : scale(1.2); opacity: 0; } } @-ms-keyframes cardview_scale_animation{ 0%{ -ms-transform : scale(0.9); opacity: 0.6; } 100%{ -ms-transform : scale(1.2); opacity: 0; } } @-o-keyframes cardview_scale_animation{ 0%{ -o-transform : scale(0.9); opacity: 0.6; } 100%{ -o-transform : scale(1.2); opacity: 0; } } @keyframes cardview_scale_animation{ 0%{ transform : scale(0.9); opacity: 0.6; } 100%{ transform : scale(1.2); opacity: 0; } } @-webkit-keyframes cardview_fade_animation{ 0%{ opacity: 0.8; } 50%{ opacity: 0.2; } 100%{ opacity: 0.8; } } @-moz-keyframes cardview_fade_animation{ 0%{ opacity: 0.8; } 50%{ opacity: 0.2; } 100%{ opacity: 0.8; } } @-ms-keyframes cardview_fade_animation{ 0%{ opacity: 0; } 50%{ opacity: 1; } 100%{ opacity: 0; } } @-o-keyframes cardview_fade_animation{ 0%{ opacity: 0.8; } 50%{ opacity: 0.2; } 100%{ opacity: 0.8; } } @keyframes cardview_fade_animation{ 0%{ opacity: 0.8; } 50%{ opacity: 0.2; } 100%{ opacity: 0.8; } } </style> <div id="cardview_button" class="cardview_button_transition" style="position: fixed; left:20px; margin-left: 0px;width: 60px;height:60px;bottom: 14px;border-radius: 50%;"> <div class="cardview_scale_animation" style="width: 60px;height:60px;background: url(images/circel.png);background-size:contain;border-radius: 50%;"></div> <div style="width: 60px;height:60px;background: url(images/custom2.png);background-size:contain;border-radius: 50%;margin-top: -60px; -webkit-transform : scale(1); -o-transform : scale(1); -moz-transform : scale(1); -ms-transform : scale(1); transform : scale(1)"></div> </div> <div id="cardview_bottom" class="cardview_button_transition" style="position: fixed;bottom:10px;width:100%;left:0;"> <div class="btn-frame cardview_button_transition "> <div class="btn_custom" style="background: url(images/custom6.png)"></div> </div> <div class="btn-frame cardview_button_transition "> <div class="btn_show_tip" style="background: url(images/send6.png)"></div> </div> ', 
c || (d += ' <div class="btn-frame cardview_button_transition btn_collect"> <div class="" style="background: url(images/add.png)"></div> </div> '), 
d += ' <div class="btn-frame cardview_button_transition btn_more"> <div class="" style="background: url(images/more6.png)"></div> </div> </div> ', 
new String(d);
});
}), define("scripts/tpl/customForm", [ "./template" ], function(a) {
return a("./template")("customForm", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.i), d = a.items, e = a.item, f = b.$string, g = a.list, h = (a.r, 
a.g, a.j), i = a.val, j = "";
j += "<form> ";
for (var c = 0; c < d.length; c++) {
j += " ";
var e = d[c];
if (j += " ", "input" == e.type && (j += ' <div class="form-group"> <label class="control-label">', 
j += f(e.description), j += '</label> <input type="text" name="', j += f(e.id), 
j += '" class="form-control" value="', j += f(e.valueCustom || e.value), j += '"> </div> '), 
j += " ", "textarea" == e.type && (j += ' <div class="form-group"> <label class="control-label">', 
j += f(e.description), j += '</label> <textarea rows="4" name="', j += f(e.id), 
j += '" class="form-control">', j += f(e.valueCustom || e.value), j += "</textarea> </div> "), 
j += " ", "phone" == e.type && (j += ' <div class="form-group"> <label class="control-label">', 
j += f(e.description), j += '</label> <input type="tel" name="', j += f(e.id), j += '" class="form-control" value="', 
j += f(e.valueCustom || e.value), j += '"> </div> '), j += " ", "email" == e.type && (j += ' <div class="form-group"> <label class="control-label">', 
j += f(e.description), j += '</label> <input type="email" name="', j += f(e.id), 
j += '" class="form-control" value="', j += f(e.valueCustom || e.value), j += '"> </div> '), 
j += " ", "radio" == e.type) {
j += ' <div class="form-radio-group"> <label class="control-label">', j += f(e.description), 
j += "</label> ";
var g = e.value.replace(/\r/g, "").split("\n");
j += " ";
for (var h = 0; h < g.length; h++) {
j += " ";
var i = g[h];
j += ' <div class="radio"> <label> <input type="radio" name="', j += f(e.id), j += '" class="form-radio-control" value="', 
j += f(i), j += '" ', j += f(e.valueCustom == i ? "selected" :""), j += "> ", j += f(i), 
j += " </label> </div> ";
}
j += " </div> ";
}
if (j += " ", "checkbox" == e.type) {
j += ' <div class="form-checkbox-group ', j += f(e.rangeType), j += '"> <label class="control-label">', 
j += f(e.description), j += "</label> ";
var g = e.value.replace(/\r/g, "").split("\n");
j += " ";
for (var h = 0; h < g.length; h++) {
j += " ";
var i = g[h];
j += ' <div class="checkbox"> <label> <input type="checkbox" name="', j += f(e.id), 
j += '" class="form-checkbox-control" value="', j += f(i), j += '" ', j += f(e.valueCustom == i ? "selected" :""), 
j += "> ", j += f(i), j += " </label> </div> ";
}
j += " </div> ";
}
if (j += " ", "select" == e.type) {
j += ' <div class="form-group"> <label class="control-label">', j += f(e.description), 
j += '</label> <select id="disabledSelect" class="form-control" name="', j += f(e.id), 
j += '"> ';
var g = e.value.replace(/\r/g, "").split("\n");
j += " ";
for (var h = 0; h < g.length; h++) {
j += " ";
var i = g[h];
j += ' <option value="', j += f(i), j += '" ', j += f(e.valueCustom == i ? "selected" :""), 
j += ">", j += f(i), j += "</option> ";
}
j += " </select> </div> ";
}
j += " ";
}
return j += " </form>", new String(j);
});
}), define("scripts/tpl/customImageMessage", [ "./template" ], function(a) {
return a("./template")("customImageMessage", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.env), d = "";
return d += '<div align="center" class="split"><span>定制图片</span></div> <div><a class="tip-pic-btn sm"><i class="fa fa-caret-down fa-rotate-270"></i> 这是什么？</a></div> <small class="tip-pic customImageItem" style="display: none;">木疙瘩微卡支持图片定制，如果不定制，将会根据内容显示一个默认图片或者隐藏，不会影响最终效果。</small> ', 
"APP" != c && (d += ' <small class="customImageItem" id="customImagePromptMessage" style="display: none;"> 木疙瘩微卡试图打开系统对话框选择图像。如果您没有看到该对话框（例如某些三星手机），表明您的设备暂不支持在微信中选择图像，您可以<a href="install.html">点击这里</a>下载木疙瘩微卡App，并在App中进行图像定制。 </small> '), 
d += ' <div class="custom-image-list clearfix customImageItem"></div> ', new String(d);
});
}), define("scripts/tpl/dialog", [ "./template" ], function(a) {
return a("./template")("dialog", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.index), d = a.message, e = b.$string, f = a.confirm, g = a.labelNo, h = a.labelOK, i = "";
i += '<style> .dialog .button{ margin:0; border-top-width: 1px; pading-top:13px; padding-bottom:13px; display:block; padding:10px 25px; float: left; color: #fff; border: 1px solid transparent; } .dialog .button:hover{ color: #fff; } .dialog .confirmWrap{ background: #d0d0d0; border-top: 1px solid #e66427; } .dialog .dialog_font{ color: #5a5a5a; } </style> <div class="box-center full-screen dialog fix" style="z-index:100"> <div class="border-radius" style="max-width: 80%; min-width:200px;background: rgba(255,255,255,0.9);overflow: hidden"> <div style="padding:14px 30px;;min-height:100px;background:#eee"> ';
for (var c = 0; c < d.length; c++) i += ' <p class="dialog_font">', i += e(d[c]), 
i += "</p> ";
return i += ' </div> <div class="confirmWrap clearfix"> ', f ? (i += ' <a class="button normal pull-left cancel" style="width:49%;background:#d0d0d0;color:#5a5a5a;" href="#">', 
i += e(g || "取消"), i += '</a> <div style="width:1px;border:none;background:#e66427;height:44px;float:left"></div> <a class="button normal pull-left confirm" style="width:50%;background:#d0d0d0;color:#5a5a5a" href="#">', 
i += e(h || "确定"), i += "</a> ") :(i += ' <a class="button normal pull-left confirm" style="width:100%;background:#d0d0d0;color:#5a5a5a" href="#">', 
i += e(h || "确认"), i += "</a> "), i += " </div> </div> </div>", new String(i);
});
}), define("scripts/tpl/help", [ "./template" ], function(a) {
return a("./template")("help", '<style> .help-page .red{ color: #d60; margin: 2em 0; } </style> <div style="margin-top: 56px;padding-left: 16px; padding-right: 16px;" class="help-page"> <p class="red">问：我是个人，想要定制新的贺卡？</p> <p>答：所有的微卡均支持在微卡中直接定制并转发的功能。目前暂时不开放单个用户特殊需求的微卡定制。但你们可以把想要的微卡创意和形式通过微信发给我们，我们会参考你们的建议，添加新的微卡。</p> <p class="red">问：我想要在微卡中添加我自己的图片</p> <p>答：现在“木疙瘩微卡App”支持定制个人头像，App已支持iOS和Android两个平台，欢迎下载。添加更多图片功能正在开发中，敬请关注。</p> <p class="red">问：定制和转发要收费吗？</p> <p>答：完全免费，欢迎定制，转发！如果你要专门制作新的贺卡，我们可能会根据复杂度收取一定的费用。</p> <p class="red">问：我是企业相关负责人，想要为企业或者我们的客户定制贺卡。</p> <p>答：如果你是企业用户，想要定制贺卡，或者咨询公众号维护事宜，或者商务合作事宜，请直接发信至邮箱 biz@mugeda.com，并提供相关商务联系方式。为保证质量，请至少在需要微卡一周前和我们联系。</p> <p class="red">问：某些节日的贺卡没有？</p> <p>答：我们会在每个节日前三天左右上线对应的节日微卡，请随时关注。我们还提供更多日常微卡包括感谢卡、生日卡、鼓励卡等等，还有更多精彩的游戏放送。</p> <p class="red">问：我有一个公众号，我要想定制微卡与粉丝互动？</p> <p>答：木疙瘩微卡对公众号提供免费的微卡服务，可以对木疙瘩微卡进行高级定制，例如添加对自己公众号的关注信息，通过自己的公众号发送给粉丝，让公众号和粉丝的关系更亲密。更多服务敬请期待。</p> </div>');
}), define("scripts/tpl/listV2", [ "./template" ], function(a) {
return a("./template")("listV2", '<style> .list.nav{ position: fixed; top: 0; left: 0; width: 100%; } .list.nav .line{ background: #fafafa; background: -moz-linear-gradient(top, #fafafa 0%, #cdcdcd 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fafafa), color-stop(100%,#cdcdcd)); background: -webkit-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); background: -o-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); background: -ms-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); height: 4px; } .list.nav .line.red{ /*background: #d06000; background: -moz-linear-gradient(top, #d06000 0%, #b25200 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d06000), color-stop(100%,#b25200)); background: -webkit-linear-gradient(top, #d06000 0%,#b25200 100%); background: -o-linear-gradient(top, #d06000 0%,#b25200 100%); background: -ms-linear-gradient(top, #d06000 0%,#b25200 100%); background: linear-gradient(top, #d06000 0%,#b25200 100%);*/ background: #d60;; height: 3px; margin-top: -3px; margin-left: -100px; width: 43px; transition: margin-left 0.5s; -moz-transition: margin-left 0.5s; /* Firefox 4 */ -webkit-transition: margin-left 0.5s; /* Safari 和 Chrome */ -o-transition: margin-left 0.5s; /* Opera */ } .list.nav .con{ background: #fff;; } .list.nav .con .it{ display: inline-block; width: 20%; text-align: center; padding-top: 11px; padding-bottom: 11px; } .list.nav .cas-frame{ position: fixed; width: 100%; bottom: 0; top: 48px; display: none; } .list.nav .cas{ background-color: #fff; opacity: 0.95; -webkit-box-shadow: 0 0 5px 0 rgba(0,0,0,0.25); box-shadow: 0 0 5px 0 rgba(0,0,0,0.25); display: none; overflow: auto; max-height: 100%; } .list.cas-mask{ position: fixed; /*background: red;*/ top:0; left:0; right: 0; bottom: 0; display: none; } .list.nav .cas .item{ width: 50%; display: inline-block; /*font-size: 14px;*/ padding: 13px 0 13px 13px; } .list.nav .cas .item.hide{ display: none; } .list.nav .cas .item.hide.more{ display: inline-block; } .list.nav .cas .item.selected{ background-color: #f1f1f1; display: inline-block!important; } .list.clist .highlight{ background: #fef7e5; border-top: 2px #ebebeb solid; border-bottom: 2px #ebebeb solid; } .list.clist{ margin: 48px 0; padding: 0; padding-top: 1px;; } .list.clist .main, .list.clist .rec{ padding: 0 4px; } .list.clist .main{ margin-bottom: 16px; } .list.cfoot{ position:fixed; bottom:0; left: 0; right: 0; } .list.cfoot .item{ display: inline-block; width: 33.333333333%; padding: 5px 0; text-align: center; background-color: #f8f8f8; } .list.cfoot .line{ float:left; } .list.cfoot .line{ height: 1px; background-color: rgba(216, 216, 216, 1); width: 33.333333333%; } .list.cfoot .line.red{ height: 1px; background-color: #d60; width: 33.333333333%; } .list.cfoot .list-icon{ background: url(images/icons_big.png) no-repeat; display: block; width: 20px; margin: 0 auto; height: 20px; background-size: 120px 20px; } .list.cfoot .list-icon-title{ vertical-align: middle; font-size: 12px; color: #e87d54; } .list.cfoot .list-icon.login{ background-position: 0 0; } .list.cfoot .list-icon.login.on{ background-position: -20px 0; } .list.cfoot .list-icon.help{ background-position: -100px 0; } .list .new-label { display: inline-block; width: 6px; height: 6px; border-radius: 4px; background-color: #d60; position: relative; left: 4px; top: -8px; content: \'new\'; } </style> <div class="list clist"> <div class="label label-main"></div> <div class="main clearfix"></div> <div class="search clearfix"></div> <div class="highlight rec-frame" style="position: relative"> <div class="label">推荐微卡</div> <div class="rec clearfix"></div> </div> <div style="position: absolute;right: 8px;top: 56px;height: 22px;overflow: hidden;"> <input type="text" class="form-control" placeholder="输入关键字，例如：生日" id="seachText" style="border: none;background:none;outline: none;width: 0px;height:22px;padding: 0px;float:left;margin-right: 4px;font-size: 14px;" > <input type="button" value="" id="seachOk" style="border:none;background-size: 16px;background-color: #ffffff;background-image:url(\'images/search.png\');background-repeat: no-repeat;background-position: center center;outline: none;height: 22px;width:22px;padding: 0px;float: right;" />  </div> </div> <div class="list cfoot"> <div style="border-bottom: 1px #d8d8d8 solid;"> <div class="line"></div><div class="line"></div><div class="line"></div> </div> <div class="item item-login"><i class="list-icon login"></i><span class="list-icon-title">个人中心</span></div><div class="item item-home" onclick="javascript:location.hash = 0"><i class="list-icon" style="background-position: -59px 0"></i><span class="list-icon-title">挑选微卡</span></div><div class="item item-help"><i class="list-icon help"></i><span class="list-icon-title">帮助</span></div> </div> <div class="list cas-mask"></div> <div class="list nav"> <div class="con"> <div class="it" data-id="1"><span>节日</span></div><div class="it" data-id="2"><span>日常</span></div><div class="it" data-id="3"><span>邀请</span></div><div class="it" data-id="4"><span>娱乐</span></div><div class="it" data-id="5"><span>动漫</span></div> </div> <div class="line"></div> <div class="line red"></div> <div class="cas-frame"> <div class="cas">  </div> </div> </div>');
}), define("scripts/tpl/list_custom_1", [ "./template" ], function(a) {
return a("./template")("list_custom_1", '<style> .list.nav{ position: fixed; top: 0; left: 0; width: 100%; } .list.nav .line{ background: #fafafa; background: -moz-linear-gradient(top, #fafafa 0%, #cdcdcd 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#fafafa), color-stop(100%,#cdcdcd)); background: -webkit-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); background: -o-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); background: -ms-linear-gradient(top, #fafafa 0%,#cdcdcd 100%); height: 4px; } .list.nav .line.red{ /*background: #d06000; background: -moz-linear-gradient(top, #d06000 0%, #b25200 100%); background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#d06000), color-stop(100%,#b25200)); background: -webkit-linear-gradient(top, #d06000 0%,#b25200 100%); background: -o-linear-gradient(top, #d06000 0%,#b25200 100%); background: -ms-linear-gradient(top, #d06000 0%,#b25200 100%); background: linear-gradient(top, #d06000 0%,#b25200 100%);*/ background: #d60;; height: 3px; margin-top: -3px; margin-left: -100px; width: 43px; transition: margin-left 0.5s; -moz-transition: margin-left 0.5s; /* Firefox 4 */ -webkit-transition: margin-left 0.5s; /* Safari 和 Chrome */ -o-transition: margin-left 0.5s; /* Opera */ } .list.nav .con{ background: #fff;; } .list.nav .con .it{ display: inline-block; width: 33.333333%; text-align: center; padding-top: 11px; padding-bottom: 11px; color: white; text-shadow:0px 3px 3px #7C7C7C; } .list.nav .cas{ background-color: #fff; opacity: 0.95; -webkit-box-shadow: 0 0 5px 0 rgba(0,0,0,0.25); box-shadow: 0 0 5px 0 rgba(0,0,0,0.25); display: none; } .list.cas-mask{ position: fixed; /*background: red;*/ top:0; left:0; right: 0; bottom: 0; display: none; } .list.nav .cas .item{ width: 50%; display: inline-block; /*font-size: 14px;*/ padding: 13px 0 13px 13px; } .list.nav .cas .item.hide{ display: none; } .list.nav .cas .item.hide.more{ display: inline-block; } .list.nav .cas .item.selected{ background-color: #f1f1f1; display: inline-block!important; } .list.clist .highlight{ background: #fef7e5; border-top: 2px #ebebeb solid; border-bottom: 2px #ebebeb solid; } .list.clist{ margin: 12px 0; padding: 0; padding-top: 1px;; } .list.clist .main, .list.clist .rec{ padding: 0 4px; } .list.clist .main{ margin-bottom: 16px; } .list .new-label { display: inline-block; width: 6px; height: 6px; border-radius: 4px; background-color: #d60; position: relative; left: 4px; top: -8px; content: \'new\'; } /*hide recommand*/ .rec-frame { display: none;; } </style> <div class="list clist"> <div class="label label-main"></div> <div class="main clearfix"></div> <div class="highlight rec-frame"> <div class="label">推荐微卡</div> <div class="rec clearfix"></div> </div> </div> <div class="list cas-mask"></div> <div class="list nav"> <div class="con" style="background: #b0c0c0"> <div class="it" data-id="1" style="background: #e6c4bb"><span>节日贺卡</span></div><div class="it" data-id="2" style="background: #d5d2bf"><span>日常关怀</span></div><div class="it" data-id="3" style="background: #b0c0c0"><span>邀请请帖</span></div></div> </div> <div class="line"></div> <div class="line red"></div> <div class="cas">  </div> </div> <script> window.businessParam = { "url":null,"content":"关注{{吉姆兄弟}}更多酷炫内容","bid":1 }; </script>');
}), define("scripts/tpl/loader", [ "./template" ], function(a) {
return a("./template")("loader", '<div id="stageParent" style="display: none;"> <div class="foot-mark"></div> </div>');
}), define("scripts/tpl/loading", [ "./template" ], function(a) {
return a("./template")("loading", function(a) {
"use strict";
var b = this, c = (b.$helpers, b.$string), d = a.message, e = "";
return e += '<div class="box-center full-screen" style="z-index:100"> <div class="border-radius" style="width: 120px; height: 120px;background: url(./images/logo.png) center 30px no-repeat rgba(255,255,255,0.9); text-align: center"> <img class="rotate" style="margin-top:22px" src="./images/loader.png" /> <div class="box-center message" style="height: 49px;">', 
e += c(d), e += "</div> </div> </div>", new String(e);
});
}), define("scripts/tpl/login", [ "./template" ], function(a) {
return a("./template")("login", '<form class="content form-horizontal"> <p>木疙瘩微卡的注册用户可以将头像等图片保存到自己账号中以便重复使用。今后还会有其它注册用户专用功能和惊喜等着你哦！</p> <div class="form-group box"> <label class="control-label" style="width:80px">用户名</label> <input class="form-control box-flexible username"/> </div> <div class="form-group box"> <label class="control-label" style="width:80px">密码</label> <input type="password" class="form-control box-flexible password" /> </div> <div class="form-group box"> <a class="button normal box-flexible hot login" style="display: block;margin-right:8px;border-width:1px">登录</a> <a class="button normal box-flexible register" style="display: block;margin-left: 8px;border-width:1px">注册</a> </div> <div style="height: 200px;"></div> </form> ');
}), define("scripts/tpl/mugedaLoad", [ "./template" ], function(a) {
return a("./template")("mugedaLoad", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.type), d = b.$string, e = a.background, f = a.width, g = a.url, h = a.hideProcessBar, i = a.processBackColor, j = a.processFrontColor, k = a.processTextColor, l = a.showFooter, m = a.footAlign, n = a.footMargin, o = a.footWidth, p = a.footerUrl, q = a.pieColor, r = a.textColor, s = a.imgUrl, t = a.text, u = "";
return 0 == c && (u += ' <div id="mcard-load" style="background: ', u += d(e), u += '"> <div id="mpqxqenk_o0" class="logo-outer"> <img width="', 
u += d(f), u += '" src="', u += d(g), u += '"> </div> <div id="mpqxqenk_o1" class="process-outer"> ', 
h || (u += ' <div class="process-frame" style="', i && (u += "background:", u += d(i)), 
u += '"> <div class="process" style="', j && (u += "background:", u += d(j)), u += '"></div> </div> '), 
u += ' <small class="message" style="', k && (u += "color:", u += d(k)), u += '">正在连接服务器...</small> </div> ', 
l && (u += ' <div class="foot-frame" style="', m && (u += ";text-align:", u += d(m)), 
n && (u += ";padding:", u += d(n)), u += '"> <img width="', u += d(o), u += '" src="', 
u += d(p), u += '" /> </div> '), u += " </div> "), u += " ", 1 == c && (u += ' <div id="mcard-load" style="background: ', 
u += d(e), u += " url('", u += d(g), u += '\') no-repeat center; position: fixed; left: 0; right: 0; top: 0; bottom: 0; background-size: cover"> <div id="mpqxqenk_o1" class="process-outer" style="margin-left: auto; margin-right: auto; border-radius: 17px; padding-top: 8px; background-color: rgba(0,0,0,0.6); width: 120px; position: fixed; bottom: 30%;"> <div class="process-frame"> <div class="process"></div> </div> <small class="message">正在连接服务器...</small> </div> </div> '), 
u += " ", 2 == c && (u += ' <div id="mcard-load" style="background: black;border:none;"> <style> .logo-outer{ background: rgba(0,0,0,0); border:none; overflow: visible; width: 128px; height:128px; margin-left:auto; margin-right: auto; } .logo-outer .holder{ background: ', 
u += d(e), u += "; border-radius: 64px; width:100%; height:100%; position: relative; padding-top:96px; } .logo-outer .holder .left{ position: absolute; width: 50%; height: 100%; overflow: hidden; left:0; top:0; } .logo-outer .holder .left .leftPie{ position: absolute; width:100%; height:100%; -webkit-transform-origin:right center; -moz-transform-origin:right center; -ms-transform-origin:right center; -webkit-transform:rotate(180deg); -moz-transform:rotate(180deg); -ms-transform:rotate(180deg); -webkit-transition: 250ms linear; -moz-transition: 250ms linear; -ms-transition: 250ms linear; overflow: hidden; } .logo-outer .holder .pie{ width:200%; height:100%; border-radius: 64px; background: ", 
u += d(q), u += "; position: absolute; } .logo-outer .holder .right .pie{ left: -100%; } .logo-outer .holder .right{ position: absolute; width: 50%; height: 100%; overflow: hidden; left:50%; top:0; } .logo-outer .holder .right .rightPie{ position: absolute; width:100%; height:100%; -webkit-transform-origin:left center; -moz-transform-origin:left center; -ms-transform-origin:left center; -webkit-transform:rotate(180deg); -moz-transform:rotate(180deg); -ms-transform:rotate(180deg); overflow: hidden; } .logo-outer .holder .icon{ border-radius: 62px; width: 120px; height: 120px; position: absolute; left: 4px; top: 4px; } .logo-outer .slogan{ text-align:center; position: absolute; width: 100%; color: ", 
u += d(r), u += '; font-family:\'Microsoft YaHei\', \'微软雅黑\', \'华文细黑\'; left:0; margin-top: 16px; } </style> <div id="mpqxqenk_o0" class="logo-outer"> <div class="holder"> <div class="left"> <div class="leftPie"><div class="pie"></div></div> </div> <div class="right" style=""> <div class="rightPie"><div class="pie"></div></div> </div> <img class="icon cacheImage" src="', 
u += d(s), u += '" style="" /> </div> <div class="slogan"> ', u += d(t), u += " </div> </div> </div> "), 
new String(u);
});
}), define("scripts/tpl/navibar", [ "./template" ], function(a) {
return a("./template")("navibar", function(a) {
"use strict";
var b = this, c = (b.$helpers, b.$string), d = a.title, e = "";
return e += '<div class="box box-align-center navi fix" style="height:44px;padding:0 8px;background:#f0eff5;position: fixed;top:0; left:0; width:100%;z-index:50"> <div class="main-color navi-left" style="min-width: 80px;"></div> <div class="box-flexible text-center navi-title">', 
e += c(d), e += '</div> <div class="main-color text-right navi-right" style="min-width: 80px;"> <span class="btn userBtn"><i class="fa fa-user"></i></span> <span class="btn weixinBtn"><i class="fa fa-weixin"></i></span> </div> </div>', 
new String(e);
});
}), define("scripts/tpl/profileCustom", [ "./template" ], function(a) {
return a("./template")("profileCustom", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.isWeixin), d = "";
return d += '<div class="loading_page"><div align="center" class="split"><span>定制加载页面</span></div> <div><a class="tip-pic-btn sm"><i class="fa fa-caret-down fa-rotate-270"></i> 这是什么？</a></div> <small class="tip-pic customImageItem" style="display: none;">你可以定制微卡的加载页面效果。如果不定制，不会影响最终效果。</small> <div class="popupFormInputBox form-group box customProfile"> ', 
c && (d += ' <div id="spanProfile" class="form-group"> <input type="radio" name="radio-custom-logo" class="custom-radio custom-logo" id="checkboxProfile" value="1"> <label for="checkboxProfile">在加载页面显示微信头像</label> </div> <div class="spanSloganCustomValue clearfix" style="display: none"> <div id="spanSlogan" class="popupFormSelectList form-group box"> <label class="inputlabel control-label" style="min-width: 100px;">个人宣言:</label> <select id="selectSlogan" class="form-control box-flexible" style="overflow: hidden;"> <option>正准备上菜，别急，别急</option> <option>在路上ING</option> <option>分享我的美一刻</option> <option>正在摆pose，稍等</option> <option>我想对你说</option> <option value= "999"> --- 自己输入 --- </option> </select> </div> <div id="spanCustomSlogan" class="popupFormInputBox form-group box" style="display: none"> <label class="inputlabel control-label" style="min-width: 100px;">自己输入:</label> <input id="inputSlogan" type="text" name="slogan" class="form-control box-flexible"> </div> </div> '), 
d += ' <div id="spanPcCustom" class="form-group"> <input type="radio" name="radio-custom-logo" class="custom-radio custom-logo" id="spanPcCustomCheckBox" value="2"> <label for="spanPcCustomCheckBox">上传企业logo显示在加载页面</label> </div> <div class="spanPcCustomValue clearfix" style="display: none;margin-bottom: 21px;"> <div style="width: 180px; height:120px;display: inline-block; float: left; background: black url(\'./images/5347ba39a3664e9b74000049.png\') no-repeat;background-size: 80%;background-position: center center;"></div> <a class="button normal changeLogo" style="border-width: 1px; margin: 40px 0 0 16px">更换</a> </div> </div></div>', 
new String(d);
});
}), define("scripts/tpl/promoHtml", [ "./template" ], function(a) {
return a("./template")("promoHtml", function(a) {
"use strict";
var b = this, c = (b.$helpers, b.$string), d = a.data, e = a.i, f = "";
for (f += '<style> .promo-content{ width:100%; color:rgba(255,255,255,1); padding:0 25px; height:100%; background-color:rgba(0,0,0,0.73); top:0; left:0; position:absolute; } .promo-content p{ word-wrap: break-word; word-break: normal; margin:5px 0; } .promo-card-list{ padding:0; list-style: none; overflow: hidden; } .promo-card-list li{ float: left; width:33%; } .promo-card-list li img{ width:95%; border:1px solid #fff; } .promo-card-list li p{ margin:0; font-size: 12px; text-align: center; } .promo-more{ display: block; width: 70%; text-align: center; margin: 0 auto; background: #dd6600; color: #fff; border-radius: 3px; padding: 5px; height: 35px; line-height: 25px; clear: both; } .promo-close{ clear: both; text-align: center; color:#969696; font-size: 16px; margin-top: 15px!important; } a.promo-more:hover,a.promo-more:focus,a.promo-more:visited{ color: #fff; } .promo-des{ margin: 55px 0 10px; height: 80px; color: #fff; overflow: hidden; font-size: 16px; font-weight: normal; } .promo-dex-sucss{ font-weight: normal; height:25px; line-height: 25px; font-size: 16px; background:url(images/success_1.png) 100px 0 no-repeat; -webkit-background-size: contain; background-size: contain; color: #dd6600; } </style> <div id="js-promo" class="promo-content"> <div id="js-promo-text" class="promo-des"><div class="promo-dex-sucss">发送成功！</div><p>', 
f += c(d.description), f += '</p></div> <ul id="js-promo-show" class="promo-card-list"> ', 
e = 0; e < d.cards.length; e++) f += ' <li><img src="', f += c(d.cards[e].thumb), 
f += '" alt=""><p>', f += c(d.cards[e].title), f += "</p></li> ";
return f += ' </ul> <p><a id="js-promo-more" class="promo-more">点击挑选更多微卡</a></p> <p id="js-promo-close" class="promo-close">谢谢了！下次吧</p> </div>', 
new String(f);
});
}), define("scripts/tpl/recordAudio", [ "./template" ], function(a) {
return a("./template")("recordAudio", '<style> .custSound{ text-align: center; width: 120px; margin: auto; padding-top: 8px; } .custSound div{ width: 50px; height: 50px; background: url(\'./images/record.png\') no-repeat left top; background-size: 50px 150px; display: inline-block; margin: 0 5px; } .custSound div.recording2{ background-position: 0 -50px; } .custSound div.recording2.stop{ background-position: 0 -100px; } </style> <div class="custom_voice"><div align="center" class="split"><span>定制语音</span></div> <div><a class="tip-pic-btn sm"><i class="fa fa-caret-down fa-rotate-270"></i> 这是什么？</a></div> <small class="tip-pic customImageItem" style="display: none;">你可以为发送的微卡添加一段语音祝福。如果不添加，不会影响最终效果。</small> <div class="popupFormInputBox form-group box customAudioItem"> <div class="custSound"> <div class="recording1"></div><div style="display: none;" class="recording2"></div> </div> </div></div>');
}), define("scripts/tpl/register", [ "./template" ], function(a) {
return a("./template")("register", '<form class="content form-horizontal"> <p>木疙瘩微卡的注册用户可以将头像等图片保存到自己账号中以便重复使用。今后还会有其它注册用户专用功能和惊喜等着你哦！</p> <small>注意：木疙瘩微卡的用户名与您的微信账号无关。请只用字母，数字，和符号"_"生成用户名，请不要用中文。用户名不少于6个字符。</small> <div class="form-group box"> <label class="control-label" style="width:80px">用户名</label> <input class="form-control box-flexible username" placeholder="字母或数字"/> </div> <div class="form-group box"> <label class="control-label" style="width:80px">密码</label> <input type="password" class="form-control box-flexible password" placeholder="不少于6个字符"/> </div> <div class="form-group box"> <label class="control-label" style="width:80px">确认密码</label> <input type="password" class="form-control box-flexible password2" placeholder="不少于6个字符"/> </div> <div class="form-group box"> <label class="control-label" style="width:80px">验证码</label> <input class="form-control box-flexible ver-code" placeholder="输入右侧字符"/> <img class="captch" height="36" style="margin-left: 8px"> </div> <div class="form-group box"> <a class="button normal hot box-flexible register" style="display: block;border-width:1px">注册</a> </div> </form>');
}), define("scripts/tpl/replyInvite", [ "./template" ], function(a) {
return a("./template")("replyInvite", '<form class="content form-horizontal"> <div class="form-group box"> <label class="control-label" style="min-width: 100px;">回复</label> <select class="form-control box-flexible answer"> <option value="0" selected="selected">参加</option> <option value="1">不参加</option> <option value="2">不确定</option> </select> </div> <div class="form-group box"> <label class="control-label" style="min-width: 100px;">留言</label> <textarea class="form-control box-flexible message" style="height:90px;" cols="5"></textarea> </div> </form> ');
}), !function() {
function a(a, b) {
return (/string|function/.test(typeof b) ? h :g)(a, b);
}
function b(a, c) {
return "string" != typeof a && (c = typeof a, "number" === c ? a += "" :a = "function" === c ? b(a.call(a)) :""), 
a;
}
function c(a) {
return l[a];
}
function d(a) {
return b(a).replace(/&(?![\w#]+;)|[<>"']/g, c);
}
function e(a, b) {
if (m(a)) for (var c = 0, d = a.length; d > c; c++) b.call(a, a[c], c, a); else for (c in a) b.call(a, a[c], c);
}
function f(a, b) {
var c = /(\/)[^/]+\1\.\.\1/, d = ("./" + a).replace(/[^/]+$/, ""), e = d + b;
for (e = e.replace(/\/\.\//g, "/"); e.match(c); ) e = e.replace(c, "/");
return e;
}
function g(b, c) {
var d = a.get(b) || i({
filename:b,
name:"Render Error",
message:"Template not found"
});
return c ? d(c) :d;
}
function h(a, b) {
if ("string" == typeof b) {
var c = b;
b = function() {
return new k(c);
};
}
var d = j[a] = function(c) {
try {
return new b(c, a) + "";
} catch (d) {
return i(d)();
}
};
return d.prototype = b.prototype = n, d.toString = function() {
return b + "";
}, d;
}
function i(a) {
var b = "{Template Error}", c = a.stack || "";
if (c) c = c.split("\n").slice(0, 2).join("\n"); else for (var d in a) c += "<" + d + ">\n" + a[d] + "\n\n";
return function() {
return "object" == typeof console && console.error(b + "\n\n" + c), b;
};
}
var j = a.cache = {}, k = this.String, l = {
"<":"&#60;",
">":"&#62;",
'"':"&#34;",
"'":"&#39;",
"&":"&#38;"
}, m = Array.isArray || function(a) {
return "[object Array]" === {}.toString.call(a);
}, n = a.utils = {
$helpers:{},
$include:function(a, b, c) {
return a = f(c, a), g(a, b);
},
$string:b,
$escape:d,
$each:e
}, o = a.helpers = n.$helpers;
a.get = function(a) {
return j[a.replace(/^\.\//, "")];
}, a.helper = function(a, b) {
o[a] = b;
}, define("scripts/tpl/template", [], function() {
return a;
});
}(), define("scripts/tpl/userinfo", [ "./template" ], function(a) {
return a("./template")("userinfo", function(a) {
"use strict";
var b = this, c = (b.$helpers, b.$string), d = a.nickname, e = "";
return e += '<div class="content form-horizontal" style="padding:0;background:#efeff4"> <div class="userprofile"> <div> <img class="headimgurl" src="images/defaulthead.png" width="73" height="73" alt=""> <span style="float:left;margin-left:10px"> <span class="nickname" style="display:block;margin-bottom:20px">', 
e += c(d || "个人昵称"), e += '</span> </span> </div> </div> <ul class="profile-items"> <li class="item-imgLib"><i class="fa fa-photo"></i>我的图片库<i class="r-arrow"></i></li> <li class="item-audioLib"><i class="fa fa-photo"></i>我的语音库<i class="r-arrow"></i></li> <li class="item-collectLib"><i class="fa fa-heart"></i>我的收藏<i class="r-arrow"></i></li> </ul> <div class="form-group box" style="position:absolute;width:100%;bottom:45px;border:1px solid #dedede;border-right:none;border-left:none;"> <a class="button normal box-flexible hot logout" style="display: block;color:#333;background-color:#fff;padding:0;height:44px;line-height:44px">退出登录</a> </div> <div class="list cfoot"> <div style="border-bottom: 1px #d8d8d8 solid;"> <div class="line"></div><div class="line"></div><div class="line"></div> </div> <div class="item item-login"><i class="list-icon login on"></i><span class="list-icon-title">个人中心</span></div><div class="item item-home" onclick="javascript:location.hash = 0"><i class="list-icon" style="background-position: -40px 0"></i><span class="list-icon-title">挑选微卡</span></div><div class="item item-help"><i class="list-icon help"></i><span class="list-icon-title">帮助</span></div> </div> </div>', 
new String(e);
});
}), define("scripts/tpl/weiofficial", [ "./template" ], function(a) {
return a("./template")("weiofficial", function(a) {
"use strict";
var b = this, c = (b.$helpers, a.followed), d = "";
return d += c ? ' <div class="content form-horizontal" style="padding:0;background:#efeff4"> <div class="userprofile"> <div> <img class="headimgurl" src="images/defaulthead.png" width="73" height="73" alt=""> <span style="float:left;margin-left:10px"> <span class="nickname" style="display:block;margin-bottom:20px">个人昵称</span>  </span> </div> </div> <ul class="profile-items"> <li class="item-imgLib"><i class="fa fa-photo"></i>我的图片库<i class="r-arrow"></i></li>  <li class="item-collectLib"><i class="fa fa-heart"></i>我的收藏<i class="r-arrow"></i></li> </ul>  <div class="list cfoot"> <div style="border-bottom: 1px #d8d8d8 solid;"> <div class="line"></div><div class="line"></div><div class="line"></div> </div> <div class="item item-login"><i class="list-icon login on"></i><span class="list-icon-title">个人中心</span></div><div class="item item-home" onclick="javascript:location.hash = 0"><i class="list-icon" style="background-position: -40px 0"></i><span class="list-icon-title">挑选微卡</span></div><div class="item item-help"><i class="list-icon help"></i><span class="list-icon-title">帮助</span></div> </div> </div> ' :' <div class="content form-horizontal" style="background:#efeff4"> <div class="notfollowedpic"></div> <p>您还没有关注我们，暂时不能为您提供专属的个人中心微卡服务。但是您依旧可以挑选喜欢的微卡定制发送。</p> <p><a href="http://mp.weixin.qq.com/s?__biz=MzA3MzMwMTgwNQ==&amp;mid=202908196&amp;idx=1&amp;sn=8bb6504461a6cdf7ce809b6ac6bbf9a8#rd" target="_blank">点击这里</a>马上关注。</p> <p>也可以在微信中搜索“木疙瘩微卡”添加关注体验最炫动的微卡。</p>  </div> ', 
new String(d);
});
}), define("scripts/user", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./vendor/promise", "./environment" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./vendor/promise"), f = a("./environment");
if (!c) return !1;
var g = null, h = function() {
j != g && (g = j, c(".userBtn").trigger("user:status"), c(".gallery").trigger("user:status", j));
}, i = c.cookie, j = void 0, k = null, l = i.get("mcardUserName") || "", m = "http://weika.mugeda.com/server", n = {
login:m + "/app_login.php/login",
register:m + "/app_login.php/add",
check:m + "/app_login.php/check",
out:m + "/app_login.php/out"
};
b.showUserForm = function() {
b.getLogin().then(function(a) {
a ? (location.hash = "userInfo", c(document.body).trigger("user:userInfo")) :c(document.body).trigger("user:login");
});
}, b.showUserPhoto = function() {
c(document.body).trigger("user:photo");
}, b.login = function(a, b) {
var f = new e(function(e, f) {
a = ("" || a).replace(/(^\s*)|(\s*$)/g, ""), a ? a.length < 6 ? (d.showConfirm("用户名最小长度为6，请检查后重新登录。", !1), 
f()) :/^[a-zA-Z0-9_]+$/.test(a) ? !b || b.length < 6 ? (d.showConfirm("密码格式不正确，请检查后重新登录。", !1), 
f()) :(d.showLoading("正在登录"), c.ajax({
type:"POST",
url:n.login,
data:{
username:a,
password:b
},
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(b) {
d.hideLoading(), 0 != b.status ? (d.showConfirm("用户名或密码错误，请重试。", !1), f()) :(d.showMessage("登录成功"), 
j = !0, l = a, i.set("mcardUserName", a), h(), e());
},
error:function() {
d.hideLoading(), d.showConfirm("由于网络问题登录失败，请重试。", !1), f();
}
})) :(d.showConfirm("用户名只能包含字母、数字和下划线，请检查后重新登录。", !1), f()) :(d.showConfirm("用户名为空，请检查后重新登录。", !1), 
f());
});
return f;
}, b.reg = function(a, b, f, g) {
return new e(function(e, k) {
a = ("" || a).replace(/(^\s*)|(\s*$)/g, ""), !a || a.length < 6 || !/^[a-zA-Z0-9_]+$/.test(a) ? (d.showConfirm("用户名格式不正确，请检查后重新注册。", !1), 
k()) :!b || b.length < 6 ? (d.showConfirm("密码格式不正确，请检查后重新注册。", !1), k()) :b != f ? (d.showConfirm("两次输入密码不一致，请检查后重新注册。", !1), 
k()) :4 != g.length ? (d.showConfirm("验证码不正确，请检查后重新注册。", !1), k()) :(d.showLoading("正在注册"), 
c.ajax({
type:"POST",
url:n.register,
data:{
nickname:a,
pass:b,
repass:f,
captcha_code:g
},
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(b) {
d.hideLoading(), 8 == b.status ? (d.showConfirm("用户名 " + a + " 已被占用，请更换用户名后重新注册。", !1), 
k()) :3 == b.status ? (b.error.indexOf("captcha_error") > -1 ? d.showConfirm("验证码错误，请检查。", !1) :b.error.indexOf("length_error") > -1 ? d.showConfirm("密码长度错误，请检查。", !1) :b.error.indexOf("pass_error") > -1 ? d.showConfirm("两次输入的密码不一致，请检查。", !1) :d.showConfirm("请检查用户名、密码、验证码是否正确。", !1), 
k()) :0 != b.status ? (d.showConfirm("由于网络问题注册失败，请重试。", !1), k()) :(d.showMessage("注册成功"), 
l = a, i.set("mcardUserName", a), j = !0, h(), e());
},
error:function() {
d.hideLoading(), d.showConfirm("由于网络问题注册失败，请重试。", !1), k();
}
}));
});
}, b.getLogin = function() {
return new e(function(a) {
void 0 === j ? b.checkLoginFromServer().then(function(b) {
a(b);
}) :a(j);
});
}, b.checkLoginFromServer = function() {
return new e(function(a) {
f.isWeixin() ? a(c.cookie.get("token") ? !0 :!1) :f.isApp2() && c.ajax({
url:n.check,
xhrFields:{
withCredentials:!0
},
dataType:"json",
success:function(b) {
b.urid ? (j = !0, k = b.urid, h(), a(!0)) :(j = !1, k = null, h(), a(!1));
},
error:function() {
j = !1, k = null, h(), a(!1);
}
});
});
}, b.getUserNameFromCookie = function() {
return l = i.get("mcardUserName") || "";
}, b.logout = function() {
return new e(function(a, b) {
d.showLoading("正在注销"), c.ajax({
type:"POST",
url:n.out,
dataType:"json",
xhrFields:{
withCredentials:!0
},
success:function(c) {
d.hideLoading(), 0 != c.status && 2 != c.status ? (d.showConfirm("由于网络问题注销失败，请重试。", !1), 
b()) :(d.showMessage("注销成功"), i.set("mcardUserName", "", -1), j = !1, k = void 0, 
h(), a());
},
error:function() {
d.hideLoading(), d.showConfirm("由于网络问题注销失败，请重试。", !1), b();
}
});
});
}, b.getToken = function() {
return new e(function(a, b) {
var d = c.cookie.get("token");
d ? a(d) :b("needLogin");
});
}, b.getUrid = function() {
return new e(function(a, c) {
j && k ? a(k) :j || void 0 == j ? b.checkLoginFromServer().then(function(b) {
b ? a(k) :c("needLogin");
}) :c("needLogin");
});
};
}), define("scripts/userview", [ "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./user", "./vendor/promise", "./environment", "./tpl/login", "./tpl/register", "./tpl/userinfo", "./navibar", "./tpl/navibar", "./page", "./photoservice", "./gallery", "./collectGallery", "./tpl/help" ], function(a, b) {
var c = a("./vendor/zepto"), d = a("./message"), e = a("./user"), f = a("./tpl/login"), g = a("./tpl/register"), h = a("./tpl/userinfo"), i = a("./navibar"), j = a("./page"), k = a("./photoservice"), l = a("./gallery"), m = a("./collectGallery"), n = a("./environment");
if (!(c && d && e && f && i && j && g)) return !1;
var o = function() {
c(document.body).bind("user:login", p), c(document.body).bind("user:userInfo", r), 
c(document.body).bind("user:photo", u), c(document.body).bind("user:collect", s), 
c(document.body).bind("user:audio", t);
};
setTimeout(function() {
o();
}, 0);
var p = function(a, b) {
b = b || {};
var d = i.getNaviBar("登录", {
hideUserIcon:!0,
cancelLabel:"取消"
}), g = c(f({}));
g.children().first().before(d.navibarTpl), g.find(".cancelBtn").one("click", function() {
j.remove(h.id), j.back(), c.isFunction(b.callback) && b.callback(!1);
}), g.find(".login").bind("click", function() {
e.login(g.find("input.username").val(), g.find("input.password").val()).then(function() {
j.remove(h.id), j.back(), c.isFunction(b.callback) && b.callback(!0);
});
}), g.find(".register").bind("click", function() {
q({
loginCallback:function() {
j.remove(h.id), j.back(), c.isFunction(b.callback) && b.callback(!0);
}
});
}), g.find("input.username").val(e.getUserNameFromCookie());
var h = j.setNewPage("login", {});
h.dom.append(g), j.addToLayout(h.id), j.setActive(h.id, !0);
}, q = function(a) {
a = a || {};
var b = i.getNaviBar("注册", {
hideUserIcon:!0,
cancelLabel:"登录"
}), d = c(g({}));
d.children().first().before(b.navibarTpl), d.find(".cancelBtn").one("click", function() {
j.remove(h.id), j.back();
});
var f = function() {
d.find(".captch").attr("src", "https://weika.mugeda.com/card/captcha.php?" + Math.random());
};
d.find(".captch").bind("click", f), f(), d.find(".register").bind("click", function() {
e.reg(d.find("input.username").val(), d.find("input.password").val(), d.find("input.password2").val(), d.find("input.ver-code").val()).then(function() {
j.remove(h.id), j.back(), c.isFunction(a.loginCallback) && a.loginCallback();
});
});
var h = j.setNewPage("register", {});
h.dom.append(d), j.addToLayout(h.id), j.setActive(h.id, !0);
}, r = b.userInfoView = function() {
var b = i.getNaviBar("个人中心", {
hideUserIcon:!0
}), d = c(h({
nickname:c.cookie.get("mcardUserName")
}));
d.children().first().before(b.navibarTpl), d.find(".item-home").one("click", function() {
location.hash = "0", j.remove(k.id), j.back();
}), d.find(".logout").bind("click", function() {
e.logout().then(function() {
location.hash = "", j.remove(k.id), j.back();
});
}), d.find(".item-imgLib").bind("click", function() {
location.hash = "photoView", c(document.body).trigger("user:photo", {
viewMode:"view"
});
}), d.find(".item-audioLib").bind("click", function() {
location.hash = "audioView", c(document.body).trigger("user:audio", {
viewMode:"view"
});
}), d.find(".item-collectLib").bind("click", function() {
location.hash = "collectView", c(document.body).trigger("user:collect", {
viewMode:"view"
});
});
var f = d.find(".item-help"), g = a("./tpl/help");
f.click(function() {
var a = i.getNaviBar("帮助", {
hideUserIcon:!0,
hideWeiIcon:!0,
cancelLabel:"返回"
}), b = c("<div></div>");
b.append(a.navibarTpl).append(g({})), b.find(".cancelBtn").one("click", function() {
j.remove(d.id), j.back();
});
var d = j.setNewPage("help", {});
d.dom.append(b), j.addToLayout(d.id), j.setActive(d.id, !0);
}), d.on("touchstart", ".profile-items li", function() {
var a = c(this);
a.css("background-color", "#e4e4e4");
}), d.on("touchend", ".profile-items li", function() {
var a = c(this);
a.css("background-color", "#fff");
}), d.on("touchstart", ".logout", function() {
var a = c(this);
a.css("background-color", "#e4e4e4");
}), d.on("touchend", ".logout", function() {
var a = c(this);
a.css("background-color", "#fff");
}), d.find(".username").html(e.getUserNameFromCookie());
var k = j.setNewPage("userinfo", {
background:"#efeff4"
});
k.dom.append(d), j.addToLayout(k.id), j.setActive(k.id, !0);
}, s = b.userCollectView = function(b, e) {
e = e || {};
var f = i.getNaviBar("我的收藏", {
hideUserIcon:!0,
cancelLabel:"返回",
rightTpl:c('<span class="btnEdit btn">编辑</span><span class="btnDelete btn btn-disable-grey">删除</span><span class="btnOk btn" style="display: none">确定</span>')
});
f.navibarTpl.on("click", ".btnEdit", function() {
g = "edit";
var a = function() {
c(".gallery").find(".coverImage").css("margin-left", "25px"), c(".gallery").find(".imgItem").removeClass("couldOpen");
};
o(a);
}), f.navibarTpl.on("click", ".btnOk", function() {
c.isFunction(e.callback) && k && e.callback(k), j.remove(p.id), j.back();
}), f.navibarTpl.on("click", ".cancelBtn", function() {
if ("edit" == g) {
g = "view";
var a = function() {
c(".gallery").find(".coverImage").css("margin-left", "0"), c(".gallery").find(".imgItem").addClass("couldOpen");
};
o(a);
} else location.hash = "userInfo", c.isFunction(e.callback) && e.callback(null), 
j.remove(p.id), j.back();
}), f.navibarTpl.on("click", ".btnDelete", function() {
c.isArray(l) && l.length && d.showConfirm("选中" + l.length + "张微卡收藏，确认删除？", !0, {
labelConfirm:"确认",
confirm:function() {
var b = [];
l.forEach(function(a) {
h.imageData[a] && b.push(h.imageData[a].id);
}), d.showLoading("正在删除"), a.async("./collectService", function(a) {
a.deleteCollect(b).then(function() {
d.hideLoading(), m.removeItem(h, l), l = [];
}, function() {
d.hideLoading(), d.showConfirm("删除收藏时出现网络错误，请重试", !1);
});
});
}
});
});
var g = e.viewMode || "view", h = null, k = null, l = null, n = function() {}, o = function(a) {
"select" == g ? (f.navibarTpl.find(".btnEdit").show(), f.navibarTpl.find(".btnDelete").hide(), 
q.find(".message-edit").hide(), q.find(".message-select").show(), h ? (m.check(!1, h), 
h.forceSelect = !1) :n = function() {
m.check(!1, h), h.forceSelect = !1;
}, l = null) :"edit" == g ? (f.navibarTpl.find(".btnEdit").hide(), f.navibarTpl.find(".btnDelete").show(), 
q.find(".message-edit").show(), q.find(".message-select").hide(), h ? (m.check(!1, h), 
h.forceSelect = !0) :n = function() {
m.check(!1, h), h.forceSelect = !0;
}, l = []) :"view" == g && (f.navibarTpl.find(".btnEdit").show(), f.navibarTpl.find(".btnDelete").hide(), 
q.find(".message-edit").show(), q.find(".message-select").hide()), a && c.isFunction(a) && a();
}, p = j.setNewPage("userCollect", {});
p.dom.append(f.navibarTpl), j.addToLayout(p.id), j.setActive(p.id, !0);
var q = c('<div class="content clearfix" style="padding-left:8px;padding-right: 8px;"> <div class="gallery"></div></div>');
p.dom.append(q), o(), a.async("./collectService", function(a) {
a.getUserCollectList().then(function(a) {
if (0 === a.length) return void d.showMessage("还有没有收藏");
var i = function() {
j.currentId == p.id ? (j.remove(p.id), j.back(), s(b, e)) :c(document.body).one("page:changed", function() {
i();
});
};
q.find(".gallery").one("user:status", i), h = m.init(q.find(".gallery").first(), a, {
minWidth:150,
src:null,
title:null,
padding:16,
ratio:1,
callback:function(a, b) {
o(a, b);
},
render:!0,
cover:!0,
canSelect:!0
}), n();
var o = function(a, b) {
var c = b.id;
if (k = a, "select" == g) null != l && c == l ? (m.check(!1, h, c), l = null) :null != l && c != l ? (m.check(!1, h, l), 
m.check(!0, h, c), l = c) :(m.check(!0, h, c), l = c), l ? f.navibarTpl.find(".btnOk").show() :f.navibarTpl.find(".btnOk").hide(); else if ("edit" == g) {
var d = l.indexOf(c);
d > -1 ? (l.splice(d, 1), m.check(!1, h, c)) :(l.push(c), m.check(!0, h, c)), l.length > 0 ? f.navibarTpl.find(".btnDelete").removeClass("btn-disable-grey") :f.navibarTpl.find(".btnDelete").addClass("btn-disable-grey");
}
};
}, function(a) {
d.showMessage(a), j.remove(p.id), j.back();
});
});
}, t = b.userAudioView = function(a, b) {
b = b || {}, b.type = "audio", u(a, b);
}, u = b.userPhotoView = function(a, b) {
b = b || {};
var e = "audio" == b.type ? "语音" :"图片", f = "audio" == b.type, g = i.getNaviBar("我的" + e + "库", {
hideUserIcon:!0,
cancelLabel:"返回",
rightTpl:c('<span class="btnEdit btn">编辑</span><span class="btnDelete btn btn-disable-grey" style="display: none">删除</span><span class="btnOk btn" style="display: none">确定</span>')
});
g.navibarTpl.on("click", ".btnEdit", function() {
h = "edit", r();
}), g.navibarTpl.on("click", ".btnOk", function() {
c.isFunction(b.callback) && o && b.callback(o), j.remove(s.id), j.back();
}), g.navibarTpl.on("click", ".cancelBtn", function() {
"select" == h ? (j.remove(s.id), j.back()) :"edit" == h ? (h = "view", r()) :"view" == h && (location.hash = "userInfo", 
c.isFunction(b.callback) && b.callback(null), j.remove(s.id), j.back());
}), g.navibarTpl.on("click", ".btnDelete", function() {
c.isArray(p) && p.length && d.showConfirm(e + "删除后，若已有微卡使用了这个图片，该微卡可能会显示异常。\n\n选中" + p.length + "个" + e + "，确认删除？", !0, {
labelConfirm:"确认",
confirm:function() {
var a = [];
p.forEach(function(b) {
m.imageData[b] && a.push(f ? m.imageData[b].raw.audioUrl :m.imageData[b].src);
}), d.showLoading("正在删除"), k.deletePhoto(a).then(function() {
d.hideLoading(), l.removeItem(m, p), p = [];
}, function() {
d.hideLoading(), d.showConfirm("删除" + e + "时出现网络错误，请重试", !1);
});
}
});
});
var h = b.viewMode || "view", m = null, o = null, p = null, q = function() {}, r = function() {
"select" == h ? (g.navibarTpl.find(".btnEdit").hide(), g.navibarTpl.find(".btnDelete").hide(), 
g.navibarTpl.find(".btnOk").hide(), t.find(".message-edit").hide(), t.find(".message-select").show(), 
m ? (l.check(!1, m), m.forceSelect = !1) :q = function() {
l.check(!1, m), m.forceSelect = !1;
}, p = null) :"edit" == h ? (g.navibarTpl.find(".btnEdit").hide(), g.navibarTpl.find(".btnDelete").show(), 
t.find(".message-edit").show(), t.find(".message-select").hide(), m ? (l.check(!1, m), 
m.forceSelect = !0) :q = function() {
l.check(!1, m), m.forceSelect = !0;
}, p = []) :"view" == h && (g.navibarTpl.find(".btnEdit").show(), g.navibarTpl.find(".btnDelete").hide(), 
t.find(".message-edit").show(), t.find(".message-select").hide(), m ? (l.check(!1, m), 
m.forceSelect = !1) :q = function() {
l.check(!1, m), m.forceSelect = !1;
}, p = null);
}, s = j.setNewPage("userPhoto", {});
s.dom.append(g.navibarTpl), j.addToLayout(s.id), j.setActive(s.id, !0);
var t = c('<div class="content clearfix" style="padding-left:8px;padding-right: 8px;"> <small class="message-edit"><i class="fa fa-info-circle"></i> <b>提示：</b>请直接在定制微卡过程上传你需要定制的' + (f ? "语音，语音" :"头像图片，图片") + '库会为你自动保存，快捷重复使用节省流量。</small> <small class="message-select"><i class="fa fa-info-circle"></i> <b>选择模式：</b>点击想要使用的' + (f ? "语音" :"图片") + '。然后点击右上角确认选择，或者点击左上角“取消”返回微卡定制。</small> <small class="message-app"><br/><i class="fa fa-info-circle"></i> 如果需要上传新的' + (f ? "语音" :"图片") + '，您需要使用木疙瘩微卡应用。 <a class="" href="' + n.getHost() + '/card/install.html">点击这里安装</a> 。</small> <div class="gallery"></div></div>');
s.dom.append(t), (n.isApp2() || !f) && t.find(".message-app").hide();
var v = "audio" == b.type ? "audio" :"image";
if (r(), k.getUserPhotoList(v).then(function(d) {
var e = d.assets, i = d.v, k = function() {
j.currentId == s.id ? (j.remove(s.id), j.back(), u(a, b)) :c(document.body).one("page:changed", function() {
k();
});
};
t.find(".gallery").one("user:status", k), null == i && (e = e.map(function(a) {
return {
url:a
};
})), f && (e = e.map(function(a) {
return a.audioUrl = a.url, a.url = "images/audio.png", a.time = a.time, a;
})), m = l.init(t.find(".gallery").first(), e, {
minWidth:150,
src:"url",
title:"0.1.1" == i ? "time" :null,
padding:16,
ratio:1,
callback:function(a, b) {
n(a, b);
},
render:!0,
cover:!0,
canSelect:!0,
tplCallback:function() {},
isAudio:f
}), q();
var n = function(a, b) {
var c = b.id;
if (o = a, "select" == h) null != p && c == p ? (l.check(!1, m, c), p = null) :null != p && c != p ? (l.check(!1, m, p), 
l.check(!0, m, c), p = c) :(l.check(!0, m, c), p = c), p ? g.navibarTpl.find(".btnOk").show() :g.navibarTpl.find(".btnOk").hide(); else if ("edit" == h) {
var d = p.indexOf(c);
d > -1 ? (p.splice(d, 1), l.check(!1, m, c)) :(p.push(c), l.check(!0, m, c)), p.length > 0 ? g.navibarTpl.find(".btnDelete").removeClass("btn-disable-grey") :g.navibarTpl.find(".btnDelete").addClass("btn-disable-grey");
}
f && y(b, p);
};
}, function(a) {
d.showMessage(a), j.remove(s.id), j.back();
}), f) {
var w = new Audio(), x = function() {
w.currentDom.removeClass("imPlaying");
};
w.addEventListener("ended", x), c(document.body).one("page:changed", function() {
w.currentDom && w.pause(), w.removeEventListener("ended", x);
});
var y = function(a, b) {
var c = a.dom.find(".coverImage"), d = a.id;
b ? (c.addClass("imPlaying"), w.currentId == d ? w.play() :(w.currentDom && w.currentDom.removeClass("imPlaying"), 
w.currentId = d, w.currentDom = c, w.src = a.raw.audioUrl, w.play())) :w.currentDom && (w.currentDom.removeClass("imPlaying"), 
w.currentId = null, w.currentDom = null, w.pause());
};
}
};
}), define("scripts/utils", [ "./environment", "./vendor/zepto", "./message", "./tpl/loading", "./tpl/template", "./tpl/dialog", "./vendor/promise" ], function(a, b) {
var c = a("./environment"), d = a("./message"), e = a("./vendor/promise");
b.init = function() {
j(), m(), k(), l(function() {
n(), o(g, h);
});
}, b.reportGATime = function() {
var a = new Date().getTime() - window.pageLoadTime;
window._gaq && _gaq.push([ "_trackTiming", "微卡2.0版界面", "界面显示时间", a, document.title, 1 ]);
}, b.fixIOSInputProblem = function(a) {
var b;
a(document).on("focus", "input, textarea, select", function() {
a.os.ios && a(".navi").css("position", "absolute"), b = document.body.scrollTop;
}), a(document).on("blur", "input, textarea, select", function() {
a.os.ios ? (a(".navi").css("position", "fixed"), setTimeout(function() {
a(".navi").css("top", 0);
}, 0)) :setTimeout(function() {
document.body.scrollTop < 0 && window.scrollTo(0, b);
}, 0);
});
}, b.MugedaUrl = window.MugedaUrl, b.cookie = {
get:function(a) {
var b = new RegExp("(^| )" + a + "(?:=([^;]*))?(;|$)"), c = document.cookie.match(b);
return c && c[2] ? unescape(c[2]) :"";
},
set:function(a, b, c) {
var d = new Date(), e = arguments[2] || 24 * (c || 7) * 60, f = arguments[3] || window.location.pathname.substr(0, window.location.pathname.lastIndexOf("/") + 1), g = arguments[4] || null, h = arguments[5] || !1;
e ? d.setMinutes(d.getMinutes() + parseInt(e)) :"", document.cookie = a + "=" + escape(b) + (e ? ";expires=" + d.toGMTString() :"") + (f ? ";path=" + f :"") + (g ? ";domain=" + g :"") + (h ? ";secure" :"");
}
}, b.localStorage = {
get:function(a) {
if (window.localStorage && localStorage.getItem) try {
return localStorage.getItem(a);
} catch (b) {
return null;
}
},
set:function(a, c, d) {
if (window.localStorage && localStorage.getItem) try {
localStorage.setItem(a, c);
} catch (e) {
if (!d) try {
localStorage.clear(), b.localStorage.set(a, c, !0);
} catch (e) {}
}
}
};
var f = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
b.base64 = {
decode:function(a) {
function b(a) {
for (var b = "", c = 0, d = c1 = c2 = 0; c < a.length; ) d = a.charCodeAt(c), 128 > d ? (b += String.fromCharCode(d), 
c++) :d > 191 && 224 > d ? (c2 = a.charCodeAt(c + 1), b += String.fromCharCode((31 & d) << 6 | 63 & c2), 
c += 2) :(c2 = a.charCodeAt(c + 1), c3 = a.charCodeAt(c + 2), b += String.fromCharCode((15 & d) << 12 | (63 & c2) << 6 | 63 & c3), 
c += 3);
return b;
}
var c, d, e, g, h, i, j, k = "", l = 0;
for (a = a.replace(/[^A-Za-z0-9\+\/\=]/g, ""); l < a.length; ) g = f.indexOf(a.charAt(l++)), 
h = f.indexOf(a.charAt(l++)), i = f.indexOf(a.charAt(l++)), j = f.indexOf(a.charAt(l++)), 
c = g << 2 | h >> 4, d = (15 & h) << 4 | i >> 2, e = (3 & i) << 6 | j, k += String.fromCharCode(c), 
64 != i && (k += String.fromCharCode(d)), 64 != j && (k += String.fromCharCode(e));
return k = b(k);
},
encode:function(a) {
var b, c, d, e, g, h, i, j = function(a) {
a = a.replace(/\r\n/g, "\n");
for (var b = "", c = 0; c < a.length; c++) {
var d = a.charCodeAt(c);
128 > d ? b += String.fromCharCode(d) :d > 127 && 2048 > d ? (b += String.fromCharCode(d >> 6 | 192), 
b += String.fromCharCode(63 & d | 128)) :(b += String.fromCharCode(d >> 12 | 224), 
b += String.fromCharCode(d >> 6 & 63 | 128), b += String.fromCharCode(63 & d | 128));
}
return b;
}, k = "", l = 0;
for (a = j(a); l < a.length; ) b = a.charCodeAt(l++), c = a.charCodeAt(l++), d = a.charCodeAt(l++), 
e = b >> 2, g = (3 & b) << 4 | c >> 4, h = (15 & c) << 2 | d >> 6, i = 63 & d, isNaN(c) ? h = i = 64 :isNaN(d) && (i = 64), 
k = k + f.charAt(e) + f.charAt(g) + f.charAt(h) + f.charAt(i);
return k;
}
}, b.getCrid = function() {
return g;
}, b.isCustomLoad = function() {
return h;
}, b.setMode = function(a) {
i = a;
}, b.isCssMode = function() {
return "css" == i;
}, b.getParam = function(a) {
var b = window.location.search, c = new RegExp("(\\?|&)" + a + "=([^&?]*)", "i"), d = b.match(c);
return d ? d[2] :void 0;
}, b.removeParam = function(a, b) {
var c, d = b.split("?")[0], e = [], f = -1 !== b.indexOf("?") ? b.split("?")[1] :"";
if ("" !== f) {
e = f.split("&");
for (var g = e.length - 1; g >= 0; g -= 1) c = e[g].split("=")[0], c === a && e.splice(g, 1);
d = d + "?" + e.join("&");
}
return d;
}, b.escapeHTML = function() {
var a = {
"&":"&amp;",
"<":"&lt;",
">":"&gt;",
'"':"&quot;",
"'":"&#x27;",
"/":"&#x2F;"
}, b = /[&<>"'\/]/g;
return function(c) {
return ("" + c).replace(b, function(b) {
return a[b];
});
};
}();
var g = null, h = !1, i = "player", j = function() {
window.APPVER = 2.2, window.MugedaUrl = b.MugedaUrl, window.cardFrame = {
MugedaUrl:b.MugedaUrl,
Message:d
};
}, k = function() {
var a = b.MugedaUrl.current, c = !1, e = a.getQueryObj();
if ("true" == e.auth && (b.cookie.set("cookie_openid", "1", 3650), delete e.auth, 
c = !0), "false" == e.auth && (b.cookie.set("cookie_openid", "0", -1), delete e.auth, 
c = !0), "1" == e.followed && (b.cookie.set("followed", "1", 7), delete e.followed, 
c = !0), "0" == e.followed && (b.cookie.set("followed", "0", 7), delete e.followed, 
c = !0), e.token && b.cookie.set("token", e.token, 3650), "a" == e.weiReg && (b.localStorage.set("weiReg", "a"), 
delete e.weiReg, c = !0), e.crid && (location.hash = ""), e.successed && (delete e.successed, 
d.showConfirm("收藏成功\n可在微卡列表底部个人中心 > 我的收藏中查看。", !0)), e.oid) {
var f = b.localStorage.get("oid"), g = e.oid;
f != g && (b.localStorage.set("oid", "|" + g), b.localStorage.set("wantOid", 1)), 
delete e.oid, c = !0;
}
e.needToken && (delete e.needToken, a.setHost("weika.mugeda.com"), a.setProtocol("http"), 
a.setPort("80"), a.setPathname("/server/cards.php/open"), c = !0), c && (window.alert = function() {}, 
location.href = MugedaUrl.current.getURL());
}, l = function(c) {
var d = b.MugedaUrl.current, f = d.getQueryObj(), g = f.plug;
if (g) {
var h = g.split("|");
h = h.map(function(a) {
return "scripts/" + a + "/index.js";
}), a.async(h, function() {
var a = Array.prototype.map.call(arguments, function(a) {
return a.init();
});
e.all(a).then(c, c);
});
} else setTimeout(c);
}, m = function() {
location.host.indexOf("mugeda.com") > -1 && (document.domain = "mugeda.com"), c.isPublic() || top.location !== self.location && (window.alert = function() {}, 
top.location.href = self.location.href);
}, n = function() {
var a = b.MugedaUrl.current.getQueryObj();
g = a.crid, 0 == (g || "").indexOf("_") && (g = g.replace("_", ""), h = !0);
}, o = function(b, c) {
a.async("./main", function(a) {
a.init(b, c);
});
};
}), define("scripts/vendor/fastclick", [], function(a, b, c) {
function d(a, b) {
"use strict";
function c(a, b) {
return function() {
return a.apply(b, arguments);
};
}
var f;
if (b = b || {}, this.trackingClick = !1, this.trackingClickStart = 0, this.targetElement = null, 
this.touchStartX = 0, this.touchStartY = 0, this.lastTouchIdentifier = 0, this.touchBoundary = b.touchBoundary || 10, 
this.layer = a, this.tapDelay = b.tapDelay || 200, !d.notNeeded(a)) {
for (var g = [ "onMouse", "onClick", "onTouchStart", "onTouchMove", "onTouchEnd", "onTouchCancel" ], h = this, i = 0, j = g.length; j > i; i++) h[g[i]] = c(h[g[i]], h);
e && (a.addEventListener("mouseover", this.onMouse, !0), a.addEventListener("mousedown", this.onMouse, !0), 
a.addEventListener("mouseup", this.onMouse, !0)), a.addEventListener("click", this.onClick, !0), 
a.addEventListener("touchstart", this.onTouchStart, !1), a.addEventListener("touchmove", this.onTouchMove, !1), 
a.addEventListener("touchend", this.onTouchEnd, !1), a.addEventListener("touchcancel", this.onTouchCancel, !1), 
Event.prototype.stopImmediatePropagation || (a.removeEventListener = function(b, c, d) {
var e = Node.prototype.removeEventListener;
"click" === b ? e.call(a, b, c.hijacked || c, d) :e.call(a, b, c, d);
}, a.addEventListener = function(b, c, d) {
var e = Node.prototype.addEventListener;
"click" === b ? e.call(a, b, c.hijacked || (c.hijacked = function(a) {
a.propagationStopped || c(a);
}), d) :e.call(a, b, c, d);
}), "function" == typeof a.onclick && (f = a.onclick, a.addEventListener("click", function(a) {
f(a);
}, !1), a.onclick = null);
}
}
var e = navigator.userAgent.indexOf("Android") > 0, f = /iP(ad|hone|od)/.test(navigator.userAgent), g = f && /OS 4_\d(_\d)?/.test(navigator.userAgent), h = f && /OS ([6-9]|\d{2})_\d/.test(navigator.userAgent), i = navigator.userAgent.indexOf("BB10") > 0;
d.prototype.needsClick = function(a) {
"use strict";
switch (a.nodeName.toLowerCase()) {
case "button":
case "select":
case "textarea":
if (a.disabled) return !0;
break;

case "input":
if (f && "file" === a.type || a.disabled) return !0;
break;

case "label":
case "video":
return !0;
}
return /\bneedsclick\b/.test(a.className);
}, d.prototype.needsFocus = function(a) {
"use strict";
switch (a.nodeName.toLowerCase()) {
case "textarea":
return !0;

case "select":
return !e;

case "input":
switch (a.type) {
case "button":
case "checkbox":
case "file":
case "image":
case "radio":
case "submit":
return !1;
}
return !a.disabled && !a.readOnly;

default:
return /\bneedsfocus\b/.test(a.className);
}
}, d.prototype.sendClick = function(a, b) {
"use strict";
var c, d;
document.activeElement && document.activeElement !== a && document.activeElement.blur(), 
d = b.changedTouches[0], c = document.createEvent("MouseEvents"), c.initMouseEvent(this.determineEventType(a), !0, !0, window, 1, d.screenX, d.screenY, d.clientX, d.clientY, !1, !1, !1, !1, 0, null), 
c.forwardedTouchEvent = !0, a.dispatchEvent(c);
}, d.prototype.determineEventType = function(a) {
"use strict";
return e && "select" === a.tagName.toLowerCase() ? "mousedown" :"click";
}, d.prototype.focus = function(a) {
"use strict";
var b;
f && a.setSelectionRange && 0 !== a.type.indexOf("date") && "time" !== a.type ? (b = a.value.length, 
a.setSelectionRange(b, b)) :a.focus();
}, d.prototype.updateScrollParent = function(a) {
"use strict";
var b, c;
if (b = a.fastClickScrollParent, !b || !b.contains(a)) {
c = a;
do {
if (c.scrollHeight > c.offsetHeight) {
b = c, a.fastClickScrollParent = c;
break;
}
c = c.parentElement;
} while (c);
}
b && (b.fastClickLastScrollTop = b.scrollTop);
}, d.prototype.getTargetElementFromEventTarget = function(a) {
"use strict";
return a.nodeType === Node.TEXT_NODE ? a.parentNode :a;
}, d.prototype.onTouchStart = function(a) {
"use strict";
var b, c, d;
if (a.targetTouches.length > 1) return !0;
if (b = this.getTargetElementFromEventTarget(a.target), c = a.targetTouches[0], 
f) {
if (d = window.getSelection(), d.rangeCount && !d.isCollapsed) return !0;
if (!g) {
if (c.identifier && c.identifier === this.lastTouchIdentifier) return a.preventDefault(), 
!1;
this.lastTouchIdentifier = c.identifier, this.updateScrollParent(b);
}
}
return this.trackingClick = !0, this.trackingClickStart = a.timeStamp, this.targetElement = b, 
this.touchStartX = c.pageX, this.touchStartY = c.pageY, a.timeStamp - this.lastClickTime < this.tapDelay && a.preventDefault(), 
!0;
}, d.prototype.touchHasMoved = function(a) {
"use strict";
var b = a.changedTouches[0], c = this.touchBoundary;
return Math.abs(b.pageX - this.touchStartX) > c || Math.abs(b.pageY - this.touchStartY) > c ? !0 :!1;
}, d.prototype.onTouchMove = function(a) {
"use strict";
return this.trackingClick ? ((this.targetElement !== this.getTargetElementFromEventTarget(a.target) || this.touchHasMoved(a)) && (this.trackingClick = !1, 
this.targetElement = null), !0) :!0;
}, d.prototype.findControl = function(a) {
"use strict";
return void 0 !== a.control ? a.control :a.htmlFor ? document.getElementById(a.htmlFor) :a.querySelector("button, input:not([type=hidden]), keygen, meter, output, progress, select, textarea");
}, d.prototype.onTouchEnd = function(a) {
"use strict";
var b, c, d, i, j, k = this.targetElement;
if (!this.trackingClick) return !0;
if (a.timeStamp - this.lastClickTime < this.tapDelay) return this.cancelNextClick = !0, 
!0;
if (this.cancelNextClick = !1, this.lastClickTime = a.timeStamp, c = this.trackingClickStart, 
this.trackingClick = !1, this.trackingClickStart = 0, h && (j = a.changedTouches[0], 
k = document.elementFromPoint(j.pageX - window.pageXOffset, j.pageY - window.pageYOffset) || k, 
k.fastClickScrollParent = this.targetElement.fastClickScrollParent), d = k.tagName.toLowerCase(), 
"label" === d) {
if (b = this.findControl(k)) {
if (this.focus(k), e) return !1;
k = b;
}
} else if (this.needsFocus(k)) return a.timeStamp - c > 100 || f && window.top !== window && "input" === d ? (this.targetElement = null, 
!1) :(this.focus(k), this.sendClick(k, a), f && "select" === d || (this.targetElement = null, 
a.preventDefault()), !1);
return f && !g && (i = k.fastClickScrollParent, i && i.fastClickLastScrollTop !== i.scrollTop) ? !0 :(this.needsClick(k) || (a.preventDefault(), 
this.sendClick(k, a)), !1);
}, d.prototype.onTouchCancel = function() {
"use strict";
this.trackingClick = !1, this.targetElement = null;
}, d.prototype.onMouse = function(a) {
"use strict";
return this.targetElement ? a.forwardedTouchEvent ? !0 :a.cancelable && (!this.needsClick(this.targetElement) || this.cancelNextClick) ? (a.stopImmediatePropagation ? a.stopImmediatePropagation() :a.propagationStopped = !0, 
a.stopPropagation(), a.preventDefault(), !1) :!0 :!0;
}, d.prototype.onClick = function(a) {
"use strict";
var b;
return this.trackingClick ? (this.targetElement = null, this.trackingClick = !1, 
!0) :"submit" === a.target.type && 0 === a.detail ? !0 :(b = this.onMouse(a), b || (this.targetElement = null), 
b);
}, d.prototype.destroy = function() {
"use strict";
var a = this.layer;
e && (a.removeEventListener("mouseover", this.onMouse, !0), a.removeEventListener("mousedown", this.onMouse, !0), 
a.removeEventListener("mouseup", this.onMouse, !0)), a.removeEventListener("click", this.onClick, !0), 
a.removeEventListener("touchstart", this.onTouchStart, !1), a.removeEventListener("touchmove", this.onTouchMove, !1), 
a.removeEventListener("touchend", this.onTouchEnd, !1), a.removeEventListener("touchcancel", this.onTouchCancel, !1);
}, d.notNeeded = function(a) {
"use strict";
var b, c, d;
if ("undefined" == typeof window.ontouchstart) return !0;
if (c = +(/Chrome\/([0-9]+)/.exec(navigator.userAgent) || [ , 0 ])[1]) {
if (!e) return !0;
if (b = document.querySelector("meta[name=viewport]")) {
if (-1 !== b.content.indexOf("user-scalable=no")) return !0;
if (c > 31 && document.documentElement.scrollWidth <= window.outerWidth) return !0;
}
}
if (i && (d = navigator.userAgent.match(/Version\/([0-9]*)\.([0-9]*)/), d[1] >= 10 && d[2] >= 3 && (b = document.querySelector("meta[name=viewport]")))) {
if (-1 !== b.content.indexOf("user-scalable=no")) return !0;
if (document.documentElement.scrollWidth <= window.outerWidth) return !0;
}
return "none" === a.style.msTouchAction ? !0 :!1;
}, d.attach = function(a, b) {
"use strict";
return new d(a, b);
}, "function" == typeof define && "object" == typeof define.amd && define.amd ? define(function() {
"use strict";
return d;
}) :"undefined" != typeof c && c.exports ? (c.exports = d.attach, c.exports.FastClick = d) :window.FastClick = d;
}), define("scripts/vendor/promise", [], function(a, b, c) {
!function(a, b) {
var d = b();
"object" == typeof c && c && (c.exports = d), "function" == typeof define && define.amd && define(b), 
a.PromisePolyfill = d, a.Promise || (a.Promise = d);
}("undefined" != typeof global ? global :this, function() {
function a(a) {
return "[object Array]" === toString.apply(a);
}
function b(a, b) {
for (var c in b) b.hasOwnProperty(c) && (a[c] = b[c]);
}
function c(a) {
if (!(this instanceof c)) return c._log("Promises should always be created with new Promise(). This will throw an error in the future", "error"), 
new c(a);
var b = new c.Resolver(this);
this._resolver = b, a(function(a) {
b.resolve(a);
}, function(a) {
b.reject(a);
});
}
function d(a) {
this._callbacks = [], this._errbacks = [], this.promise = a, this._status = "pending", 
this._result = null;
}
return b(c.prototype, {
then:function(a, b) {
var d, e, f = new this.constructor(function(a, b) {
d = a, e = b;
});
return this._resolver._addCallbacks("function" == typeof a ? c._makeCallback(f, d, e, a) :d, "function" == typeof b ? c._makeCallback(f, d, e, b) :e), 
f;
},
"catch":function(a) {
return this.then(void 0, a);
}
}), c._makeCallback = function(a, b, c, d) {
return function(e) {
var f;
return f = d(e), f === a ? void c(new TypeError("Cannot resolve a promise with itself")) :void b(f);
};
}, c._log = function(a, b) {
"undefined" != typeof console && console[b](a);
}, c.resolve = function(a) {
return a && a.constructor === this ? a :new this(function(b) {
b(a);
});
}, c.reject = function(a) {
var b = new this(function() {});
return b._resolver._result = a, b._resolver._status = "rejected", b;
}, c.all = function(b) {
var c = this;
return new c(function(d, e) {
function f(a) {
return function(b) {
j[a] = b, g--, g || d(j);
};
}
if (!a(b)) return void e(new TypeError("Promise.all expects an array of values or promises"));
var g = b.length, h = 0, i = b.length, j = [];
if (1 > i) return d(j);
for (;i > h; h++) c.resolve(b[h]).then(f(h), e);
});
}, c.race = function(b) {
var c = this;
return new c(function(d, e) {
if (!a(b)) return void e(new TypeError("Promise.race expects an array of values or promises"));
for (var f = 0, g = b.length; g > f; f++) c.resolve(b[f]).then(d, e);
});
}, c.async = "undefined" != typeof setImmediate ? function(a) {
setImmediate(a);
} :"undefined" != typeof process && process.nextTick ? process.nextTick :function(a) {
setTimeout(a, 0);
}, b(d.prototype, {
fulfill:function(a) {
var b = this._status;
("pending" === b || "accepted" === b) && (this._result = a, this._status = "fulfilled"), 
"fulfilled" === this._status && (this._notify(this._callbacks, this._result), this._callbacks = [], 
this._errbacks = null);
},
reject:function(a) {
var b = this._status;
("pending" === b || "accepted" === b) && (this._result = a, this._status = "rejected", 
this._errbacks.length || c._log("Promise rejected but no error handlers were registered to it", "info")), 
"rejected" === this._status && (this._notify(this._errbacks, this._result), this._callbacks = null, 
this._errbacks = []);
},
resolve:function(a) {
"pending" === this._status && (this._status = "accepted", this._value = a, (this._callbacks && this._callbacks.length || this._errbacks && this._errbacks.length) && this._unwrap(this._value));
},
_unwrap:function(a) {
var b, c = this, d = !1;
return !a || "object" != typeof a && "function" != typeof a ? void c.fulfill(a) :(b = a.then, 
void ("function" == typeof b ? b.call(a, function(a) {
d || (d = !0, c._unwrap(a));
}, function(a) {
d || (d = !0, c.reject(a));
}) :c.fulfill(a)));
},
_addCallbacks:function(a, b) {
var c = this._callbacks, d = this._errbacks;
switch (c && c.push(a), d && d.push(b), this._status) {
case "accepted":
this._unwrap(this._value);
break;

case "fulfilled":
this.fulfill(this._result);
break;

case "rejected":
this.reject(this._result);
}
},
_notify:function(a, b) {
a.length && c.async(function() {
var c, d;
for (c = 0, d = a.length; d > c; ++c) a[c](b);
});
}
}), c.Resolver = d, c;
});
}), define("scripts/vendor/zepto", [], function(a, b, c) {
var d = function() {
function a(a) {
return null == a ? String(a) :U[V.call(a)] || "object";
}
function b(b) {
return "function" == a(b);
}
function c(a) {
return null != a && a == a.window;
}
function d(a) {
return null != a && a.nodeType == a.DOCUMENT_NODE;
}
function e(b) {
return "object" == a(b);
}
function f(a) {
return e(a) && !c(a) && Object.getPrototypeOf(a) == Object.prototype;
}
function g(a) {
return "number" == typeof a.length;
}
function h(a) {
return D.call(a, function(a) {
return null != a;
});
}
function i(a) {
return a.length > 0 ? x.fn.concat.apply([], a) :a;
}
function j(a) {
return a.replace(/::/g, "/").replace(/([A-Z]+)([A-Z][a-z])/g, "$1_$2").replace(/([a-z\d])([A-Z])/g, "$1_$2").replace(/_/g, "-").toLowerCase();
}
function k(a) {
return a in G ? G[a] :G[a] = new RegExp("(^|\\s)" + a + "(\\s|$)");
}
function l(a, b) {
return "number" != typeof b || H[j(a)] ? b :b + "px";
}
function m(a) {
var b, c;
return F[a] || (b = E.createElement(a), E.body.appendChild(b), c = getComputedStyle(b, "").getPropertyValue("display"), 
b.parentNode.removeChild(b), "none" == c && (c = "block"), F[a] = c), F[a];
}
function n(a) {
return "children" in a ? C.call(a.children) :x.map(a.childNodes, function(a) {
return 1 == a.nodeType ? a :void 0;
});
}
function o(a, b, c) {
for (w in b) c && (f(b[w]) || Z(b[w])) ? (f(b[w]) && !f(a[w]) && (a[w] = {}), Z(b[w]) && !Z(a[w]) && (a[w] = []), 
o(a[w], b[w], c)) :b[w] !== v && (a[w] = b[w]);
}
function p(a, b) {
return null == b ? x(a) :x(a).filter(b);
}
function q(a, c, d, e) {
return b(c) ? c.call(a, d, e) :c;
}
function r(a, b, c) {
null == c ? a.removeAttribute(b) :a.setAttribute(b, c);
}
function s(a, b) {
var c = a.className, d = c && c.baseVal !== v;
return b === v ? d ? c.baseVal :c :void (d ? c.baseVal = b :a.className = b);
}
function t(a) {
var b;
try {
return a ? "true" == a || ("false" == a ? !1 :"null" == a ? null :/^0/.test(a) || isNaN(b = Number(a)) ? /^[\[\{]/.test(a) ? x.parseJSON(a) :a :b) :a;
} catch (c) {
return a;
}
}
function u(a, b) {
b(a);
for (var c in a.childNodes) u(a.childNodes[c], b);
}
var v, w, x, y, z, A, B = [], C = B.slice, D = B.filter, E = window.document, F = {}, G = {}, H = {
"column-count":1,
columns:1,
"font-weight":1,
"line-height":1,
opacity:1,
"z-index":1,
zoom:1
}, I = /^\s*<(\w+|!)[^>]*>/, J = /^<(\w+)\s*\/?>(?:<\/\1>|)$/, K = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi, L = /^(?:body|html)$/i, M = /([A-Z])/g, N = [ "val", "css", "html", "text", "data", "width", "height", "offset" ], O = [ "after", "prepend", "before", "append" ], P = E.createElement("table"), Q = E.createElement("tr"), R = {
tr:E.createElement("tbody"),
tbody:P,
thead:P,
tfoot:P,
td:Q,
th:Q,
"*":E.createElement("div")
}, S = /complete|loaded|interactive/, T = /^[\w-]*$/, U = {}, V = U.toString, W = {}, X = E.createElement("div"), Y = {
tabindex:"tabIndex",
readonly:"readOnly",
"for":"htmlFor",
"class":"className",
maxlength:"maxLength",
cellspacing:"cellSpacing",
cellpadding:"cellPadding",
rowspan:"rowSpan",
colspan:"colSpan",
usemap:"useMap",
frameborder:"frameBorder",
contenteditable:"contentEditable"
}, Z = Array.isArray || function(a) {
return a instanceof Array;
};
return W.matches = function(a, b) {
if (!b || !a || 1 !== a.nodeType) return !1;
var c = a.webkitMatchesSelector || a.mozMatchesSelector || a.oMatchesSelector || a.matchesSelector;
if (c) return c.call(a, b);
var d, e = a.parentNode, f = !e;
return f && (e = X).appendChild(a), d = ~W.qsa(e, b).indexOf(a), f && X.removeChild(a), 
d;
}, z = function(a) {
return a.replace(/-+(.)?/g, function(a, b) {
return b ? b.toUpperCase() :"";
});
}, A = function(a) {
return D.call(a, function(b, c) {
return a.indexOf(b) == c;
});
}, W.fragment = function(a, b, c) {
var d, e, g;
return J.test(a) && (d = x(E.createElement(RegExp.$1))), d || (a.replace && (a = a.replace(K, "<$1></$2>")), 
b === v && (b = I.test(a) && RegExp.$1), b in R || (b = "*"), g = R[b], g.innerHTML = "" + a, 
d = x.each(C.call(g.childNodes), function() {
g.removeChild(this);
})), f(c) && (e = x(d), x.each(c, function(a, b) {
N.indexOf(a) > -1 ? e[a](b) :e.attr(a, b);
})), d;
}, W.Z = function(a, b) {
return a = a || [], a.__proto__ = x.fn, a.selector = b || "", a;
}, W.isZ = function(a) {
return a instanceof W.Z;
}, W.init = function(a, c) {
var d;
if (!a) return W.Z();
if ("string" == typeof a) if (a = a.trim(), "<" == a[0] && I.test(a)) d = W.fragment(a, RegExp.$1, c), 
a = null; else {
if (c !== v) return x(c).find(a);
d = W.qsa(E, a);
} else {
if (b(a)) return x(E).ready(a);
if (W.isZ(a)) return a;
if (Z(a)) d = h(a); else if (e(a)) d = [ a ], a = null; else if (I.test(a)) d = W.fragment(a.trim(), RegExp.$1, c), 
a = null; else {
if (c !== v) return x(c).find(a);
d = W.qsa(E, a);
}
}
return W.Z(d, a);
}, x = function(a, b) {
return W.init(a, b);
}, x.extend = function(a) {
var b, c = C.call(arguments, 1);
return "boolean" == typeof a && (b = a, a = c.shift()), c.forEach(function(c) {
o(a, c, b);
}), a;
}, W.qsa = function(a, b) {
var c, e = "#" == b[0], f = !e && "." == b[0], g = e || f ? b.slice(1) :b, h = T.test(g);
return d(a) && h && e ? (c = a.getElementById(g)) ? [ c ] :[] :1 !== a.nodeType && 9 !== a.nodeType ? [] :C.call(h && !e ? f ? a.getElementsByClassName(g) :a.getElementsByTagName(b) :a.querySelectorAll(b));
}, x.contains = function(a, b) {
return a !== b && a.contains(b);
}, x.type = a, x.isFunction = b, x.isWindow = c, x.isArray = Z, x.isPlainObject = f, 
x.isEmptyObject = function(a) {
var b;
for (b in a) return !1;
return !0;
}, x.inArray = function(a, b, c) {
return B.indexOf.call(b, a, c);
}, x.camelCase = z, x.trim = function(a) {
return null == a ? "" :String.prototype.trim.call(a);
}, x.uuid = 0, x.support = {}, x.expr = {}, x.map = function(a, b) {
var c, d, e, f = [];
if (g(a)) for (d = 0; d < a.length; d++) c = b(a[d], d), null != c && f.push(c); else for (e in a) c = b(a[e], e), 
null != c && f.push(c);
return i(f);
}, x.each = function(a, b) {
var c, d;
if (g(a)) {
for (c = 0; c < a.length; c++) if (b.call(a[c], c, a[c]) === !1) return a;
} else for (d in a) if (b.call(a[d], d, a[d]) === !1) return a;
return a;
}, x.grep = function(a, b) {
return D.call(a, b);
}, window.JSON && (x.parseJSON = JSON.parse), x.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(a, b) {
U["[object " + b + "]"] = b.toLowerCase();
}), x.fn = {
forEach:B.forEach,
reduce:B.reduce,
push:B.push,
sort:B.sort,
indexOf:B.indexOf,
concat:B.concat,
map:function(a) {
return x(x.map(this, function(b, c) {
return a.call(b, c, b);
}));
},
slice:function() {
return x(C.apply(this, arguments));
},
ready:function(a) {
return S.test(E.readyState) && E.body ? a(x) :E.addEventListener("DOMContentLoaded", function() {
a(x);
}, !1), this;
},
get:function(a) {
return a === v ? C.call(this) :this[a >= 0 ? a :a + this.length];
},
toArray:function() {
return this.get();
},
size:function() {
return this.length;
},
remove:function() {
return this.each(function() {
null != this.parentNode && this.parentNode.removeChild(this);
});
},
each:function(a) {
return B.every.call(this, function(b, c) {
return a.call(b, c, b) !== !1;
}), this;
},
filter:function(a) {
return b(a) ? this.not(this.not(a)) :x(D.call(this, function(b) {
return W.matches(b, a);
}));
},
add:function(a, b) {
return x(A(this.concat(x(a, b))));
},
is:function(a) {
return this.length > 0 && W.matches(this[0], a);
},
not:function(a) {
var c = [];
if (b(a) && a.call !== v) this.each(function(b) {
a.call(this, b) || c.push(this);
}); else {
var d = "string" == typeof a ? this.filter(a) :g(a) && b(a.item) ? C.call(a) :x(a);
this.forEach(function(a) {
d.indexOf(a) < 0 && c.push(a);
});
}
return x(c);
},
has:function(a) {
return this.filter(function() {
return e(a) ? x.contains(this, a) :x(this).find(a).size();
});
},
eq:function(a) {
return -1 === a ? this.slice(a) :this.slice(a, +a + 1);
},
first:function() {
var a = this[0];
return a && !e(a) ? a :x(a);
},
last:function() {
var a = this[this.length - 1];
return a && !e(a) ? a :x(a);
},
find:function(a) {
var b, c = this;
return b = "object" == typeof a ? x(a).filter(function() {
var a = this;
return B.some.call(c, function(b) {
return x.contains(b, a);
});
}) :1 == this.length ? x(W.qsa(this[0], a)) :this.map(function() {
return W.qsa(this, a);
});
},
closest:function(a, b) {
var c = this[0], e = !1;
for ("object" == typeof a && (e = x(a)); c && !(e ? e.indexOf(c) >= 0 :W.matches(c, a)); ) c = c !== b && !d(c) && c.parentNode;
return x(c);
},
parents:function(a) {
for (var b = [], c = this; c.length > 0; ) c = x.map(c, function(a) {
return (a = a.parentNode) && !d(a) && b.indexOf(a) < 0 ? (b.push(a), a) :void 0;
});
return p(b, a);
},
parent:function(a) {
return p(A(this.pluck("parentNode")), a);
},
children:function(a) {
return p(this.map(function() {
return n(this);
}), a);
},
contents:function() {
return this.map(function() {
return C.call(this.childNodes);
});
},
siblings:function(a) {
return p(this.map(function(a, b) {
return D.call(n(b.parentNode), function(a) {
return a !== b;
});
}), a);
},
empty:function() {
return this.each(function() {
this.innerHTML = "";
});
},
pluck:function(a) {
return x.map(this, function(b) {
return b[a];
});
},
show:function() {
return this.each(function() {
"none" == this.style.display && (this.style.display = ""), "none" == getComputedStyle(this, "").getPropertyValue("display") && (this.style.display = m(this.nodeName));
});
},
replaceWith:function(a) {
return this.before(a).remove();
},
wrap:function(a) {
var c = b(a);
if (this[0] && !c) var d = x(a).get(0), e = d.parentNode || this.length > 1;
return this.each(function(b) {
x(this).wrapAll(c ? a.call(this, b) :e ? d.cloneNode(!0) :d);
});
},
wrapAll:function(a) {
if (this[0]) {
x(this[0]).before(a = x(a));
for (var b; (b = a.children()).length; ) a = b.first();
x(a).append(this);
}
return this;
},
wrapInner:function(a) {
var c = b(a);
return this.each(function(b) {
var d = x(this), e = d.contents(), f = c ? a.call(this, b) :a;
e.length ? e.wrapAll(f) :d.append(f);
});
},
unwrap:function() {
return this.parent().each(function() {
x(this).replaceWith(x(this).children());
}), this;
},
clone:function() {
return this.map(function() {
return this.cloneNode(!0);
});
},
hide:function() {
return this.css("display", "none");
},
toggle:function(a) {
return this.each(function() {
var b = x(this);
(a === v ? "none" == b.css("display") :a) ? b.show() :b.hide();
});
},
prev:function(a) {
return x(this.pluck("previousElementSibling")).filter(a || "*");
},
next:function(a) {
return x(this.pluck("nextElementSibling")).filter(a || "*");
},
html:function(a) {
return 0 === arguments.length ? this.length > 0 ? this[0].innerHTML :null :this.each(function(b) {
var c = this.innerHTML;
x(this).empty().append(q(this, a, b, c));
});
},
text:function(a) {
return 0 === arguments.length ? this.length > 0 ? this[0].textContent :null :this.each(function() {
this.textContent = a === v ? "" :"" + a;
});
},
attr:function(a, b) {
var c;
return "string" == typeof a && b === v ? 0 == this.length || 1 !== this[0].nodeType ? v :"value" == a && "INPUT" == this[0].nodeName ? this.val() :!(c = this[0].getAttribute(a)) && a in this[0] ? this[0][a] :c :this.each(function(c) {
if (1 === this.nodeType) if (e(a)) for (w in a) r(this, w, a[w]); else r(this, a, q(this, b, c, this.getAttribute(a)));
});
},
removeAttr:function(a) {
return this.each(function() {
1 === this.nodeType && r(this, a);
});
},
prop:function(a, b) {
return a = Y[a] || a, b === v ? this[0] && this[0][a] :this.each(function(c) {
this[a] = q(this, b, c, this[a]);
});
},
data:function(a, b) {
var c = this.attr("data-" + a.replace(M, "-$1").toLowerCase(), b);
return null !== c ? t(c) :v;
},
val:function(a) {
return 0 === arguments.length ? this[0] && (this[0].multiple ? x(this[0]).find("option").filter(function() {
return this.selected;
}).pluck("value") :this[0].value) :this.each(function(b) {
this.value = q(this, a, b, this.value);
});
},
offset:function(a) {
if (a) return this.each(function(b) {
var c = x(this), d = q(this, a, b, c.offset()), e = c.offsetParent().offset(), f = {
top:d.top - e.top,
left:d.left - e.left
};
"static" == c.css("position") && (f.position = "relative"), c.css(f);
});
if (0 == this.length) return null;
var b = this[0].getBoundingClientRect();
return {
left:b.left + window.pageXOffset,
top:b.top + window.pageYOffset,
width:Math.round(b.width),
height:Math.round(b.height)
};
},
css:function(b, c) {
if (arguments.length < 2) {
var d = this[0], e = getComputedStyle(d, "");
if (!d) return;
if ("string" == typeof b) return d.style[z(b)] || e.getPropertyValue(b);
if (Z(b)) {
var f = {};
return x.each(Z(b) ? b :[ b ], function(a, b) {
f[b] = d.style[z(b)] || e.getPropertyValue(b);
}), f;
}
}
var g = "";
if ("string" == a(b)) c || 0 === c ? g = j(b) + ":" + l(b, c) :this.each(function() {
this.style.removeProperty(j(b));
}); else for (w in b) b[w] || 0 === b[w] ? g += j(w) + ":" + l(w, b[w]) + ";" :this.each(function() {
this.style.removeProperty(j(w));
});
return this.each(function() {
this.style.cssText += ";" + g;
});
},
index:function(a) {
return a ? this.indexOf(x(a)[0]) :this.parent().children().indexOf(this[0]);
},
hasClass:function(a) {
return a ? B.some.call(this, function(a) {
return this.test(s(a));
}, k(a)) :!1;
},
addClass:function(a) {
return a ? this.each(function(b) {
y = [];
var c = s(this), d = q(this, a, b, c);
d.split(/\s+/g).forEach(function(a) {
x(this).hasClass(a) || y.push(a);
}, this), y.length && s(this, c + (c ? " " :"") + y.join(" "));
}) :this;
},
removeClass:function(a) {
return this.each(function(b) {
return a === v ? s(this, "") :(y = s(this), q(this, a, b, y).split(/\s+/g).forEach(function(a) {
y = y.replace(k(a), " ");
}), void s(this, y.trim()));
});
},
toggleClass:function(a, b) {
return a ? this.each(function(c) {
var d = x(this), e = q(this, a, c, s(this));
e.split(/\s+/g).forEach(function(a) {
(b === v ? !d.hasClass(a) :b) ? d.addClass(a) :d.removeClass(a);
});
}) :this;
},
scrollTop:function(a) {
if (this.length) {
var b = "scrollTop" in this[0];
return a === v ? b ? this[0].scrollTop :this[0].pageYOffset :this.each(b ? function() {
this.scrollTop = a;
} :function() {
this.scrollTo(this.scrollX, a);
});
}
},
scrollLeft:function(a) {
if (this.length) {
var b = "scrollLeft" in this[0];
return a === v ? b ? this[0].scrollLeft :this[0].pageXOffset :this.each(b ? function() {
this.scrollLeft = a;
} :function() {
this.scrollTo(a, this.scrollY);
});
}
},
position:function() {
if (this.length) {
var a = this[0], b = this.offsetParent(), c = this.offset(), d = L.test(b[0].nodeName) ? {
top:0,
left:0
} :b.offset();
return c.top -= parseFloat(x(a).css("margin-top")) || 0, c.left -= parseFloat(x(a).css("margin-left")) || 0, 
d.top += parseFloat(x(b[0]).css("border-top-width")) || 0, d.left += parseFloat(x(b[0]).css("border-left-width")) || 0, 
{
top:c.top - d.top,
left:c.left - d.left
};
}
},
offsetParent:function() {
return this.map(function() {
for (var a = this.offsetParent || E.body; a && !L.test(a.nodeName) && "static" == x(a).css("position"); ) a = a.offsetParent;
return a;
});
}
}, x.fn.detach = x.fn.remove, [ "width", "height" ].forEach(function(a) {
var b = a.replace(/./, function(a) {
return a[0].toUpperCase();
});
x.fn[a] = function(e) {
var f, g = this[0];
return e === v ? c(g) ? g["inner" + b] :d(g) ? g.documentElement["scroll" + b] :(f = this.offset()) && f[a] :this.each(function(b) {
g = x(this), g.css(a, q(this, e, b, g[a]()));
});
};
}), O.forEach(function(b, c) {
var d = c % 2;
x.fn[b] = function() {
var b, e, f = x.map(arguments, function(c) {
return b = a(c), "object" == b || "array" == b || null == c ? c :W.fragment(c);
}), g = this.length > 1;
return f.length < 1 ? this :this.each(function(a, b) {
e = d ? b :b.parentNode, b = 0 == c ? b.nextSibling :1 == c ? b.firstChild :2 == c ? b :null, 
f.forEach(function(a) {
if (g) a = a.cloneNode(!0); else if (!e) return x(a).remove();
u(e.insertBefore(a, b), function(a) {
null == a.nodeName || "SCRIPT" !== a.nodeName.toUpperCase() || a.type && "text/javascript" !== a.type || a.src || window.eval.call(window, a.innerHTML);
});
});
});
}, x.fn[d ? b + "To" :"insert" + (c ? "Before" :"After")] = function(a) {
return x(a)[b](this), this;
};
}), W.Z.prototype = x.fn, W.uniq = A, W.deserializeValue = t, x.zepto = W, x;
}();
window.Zepto = d, void 0 === window.$ && (window.$ = d), function(a) {
function b(a) {
return a._zid || (a._zid = m++);
}
function c(a, c, f, g) {
if (c = d(c), c.ns) var h = e(c.ns);
return (q[b(a)] || []).filter(function(a) {
return !(!a || c.e && a.e != c.e || c.ns && !h.test(a.ns) || f && b(a.fn) !== b(f) || g && a.sel != g);
});
}
function d(a) {
var b = ("" + a).split(".");
return {
e:b[0],
ns:b.slice(1).sort().join(" ")
};
}
function e(a) {
return new RegExp("(?:^| )" + a.replace(" ", " .* ?") + "(?: |$)");
}
function f(a, b) {
return a.del && !s && a.e in t || !!b;
}
function g(a) {
return u[a] || s && t[a] || a;
}
function h(c, e, h, i, k, m, n) {
var o = b(c), p = q[o] || (q[o] = []);
e.split(/\s/).forEach(function(b) {
if ("ready" == b) return a(document).ready(h);
var e = d(b);
e.fn = h, e.sel = k, e.e in u && (h = function(b) {
var c = b.relatedTarget;
return !c || c !== this && !a.contains(this, c) ? e.fn.apply(this, arguments) :void 0;
}), e.del = m;
var o = m || h;
e.proxy = function(a) {
if (a = j(a), !a.isImmediatePropagationStopped()) {
a.data = i;
var b = o.apply(c, a._args == l ? [ a ] :[ a ].concat(a._args));
return b === !1 && (a.preventDefault(), a.stopPropagation()), b;
}
}, e.i = p.length, p.push(e), "addEventListener" in c && c.addEventListener(g(e.e), e.proxy, f(e, n));
});
}
function i(a, d, e, h, i) {
var j = b(a);
(d || "").split(/\s/).forEach(function(b) {
c(a, b, e, h).forEach(function(b) {
delete q[j][b.i], "removeEventListener" in a && a.removeEventListener(g(b.e), b.proxy, f(b, i));
});
});
}
function j(b, c) {
return (c || !b.isDefaultPrevented) && (c || (c = b), a.each(y, function(a, d) {
var e = c[a];
b[a] = function() {
return this[d] = v, e && e.apply(c, arguments);
}, b[d] = w;
}), (c.defaultPrevented !== l ? c.defaultPrevented :"returnValue" in c ? c.returnValue === !1 :c.getPreventDefault && c.getPreventDefault()) && (b.isDefaultPrevented = v)), 
b;
}
function k(a) {
var b, c = {
originalEvent:a
};
for (b in a) x.test(b) || a[b] === l || (c[b] = a[b]);
return j(c, a);
}
var l, m = 1, n = Array.prototype.slice, o = a.isFunction, p = function(a) {
return "string" == typeof a;
}, q = {}, r = {}, s = "onfocusin" in window, t = {
focus:"focusin",
blur:"focusout"
}, u = {
mouseenter:"mouseover",
mouseleave:"mouseout"
};
r.click = r.mousedown = r.mouseup = r.mousemove = "MouseEvents", a.event = {
add:h,
remove:i
}, a.proxy = function(c, d) {
if (o(c)) {
var e = function() {
return c.apply(d, arguments);
};
return e._zid = b(c), e;
}
if (p(d)) return a.proxy(c[d], c);
throw new TypeError("expected function");
}, a.fn.bind = function(a, b, c) {
return this.on(a, b, c);
}, a.fn.unbind = function(a, b) {
return this.off(a, b);
}, a.fn.one = function(a, b, c, d) {
return this.on(a, b, c, d, 1);
};
var v = function() {
return !0;
}, w = function() {
return !1;
}, x = /^([A-Z]|returnValue$|layer[XY]$)/, y = {
preventDefault:"isDefaultPrevented",
stopImmediatePropagation:"isImmediatePropagationStopped",
stopPropagation:"isPropagationStopped"
};
a.fn.delegate = function(a, b, c) {
return this.on(b, a, c);
}, a.fn.undelegate = function(a, b, c) {
return this.off(b, a, c);
}, a.fn.live = function(b, c) {
return a(document.body).delegate(this.selector, b, c), this;
}, a.fn.die = function(b, c) {
return a(document.body).undelegate(this.selector, b, c), this;
}, a.fn.on = function(b, c, d, e, f) {
"tap" !== b || a.os.tablet || a.os.phone || (b = "click");
var g, j, m = this;
return b && !p(b) ? (a.each(b, function(a, b) {
m.on(a, c, d, b, f);
}), m) :(p(c) || o(e) || e === !1 || (e = d, d = c, c = l), (o(d) || d === !1) && (e = d, 
d = l), e === !1 && (e = w), m.each(function(l, m) {
f && (g = function(a) {
return i(m, a.type, e), e.apply(this, arguments);
}), c && (j = function(b) {
var d, f = a(b.target).closest(c, m).get(0);
return f && f !== m ? (d = a.extend(k(b), {
currentTarget:f,
liveFired:m
}), (g || e).apply(f, [ d ].concat(n.call(arguments, 1)))) :void 0;
}), h(m, b, e, d, c, j || g);
}));
}, a.fn.off = function(b, c, d) {
var e = this;
return b && !p(b) ? (a.each(b, function(a, b) {
e.off(a, c, b);
}), e) :(p(c) || o(d) || d === !1 || (d = c, c = l), d === !1 && (d = w), e.each(function() {
i(this, b, d, c);
}));
}, a.fn.trigger = function(b, c) {
return "tap" !== b || a.os.tablet || a.os.phone || (b = "click"), b = p(b) || a.isPlainObject(b) ? a.Event(b) :j(b), 
b._args = c, this.each(function() {
"dispatchEvent" in this ? this.dispatchEvent(b) :a(this).triggerHandler(b, c);
});
}, a.fn.triggerHandler = function(b, d) {
var e, f;
return this.each(function(g, h) {
e = k(p(b) ? a.Event(b) :b), e._args = d, e.target = h, a.each(c(h, b.type || b), function(a, b) {
return f = b.proxy(e), e.isImmediatePropagationStopped() ? !1 :void 0;
});
}), f;
}, "focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select keydown keypress keyup error".split(" ").forEach(function(b) {
a.fn[b] = function(a) {
return a ? this.bind(b, a) :this.trigger(b);
};
}), [ "focus", "blur" ].forEach(function(b) {
a.fn[b] = function(a) {
return a ? this.bind(b, a) :this.each(function() {
try {
this[b]();
} catch (a) {}
}), this;
};
}), a.Event = function(a, b) {
p(a) || (b = a, a = b.type);
var c = document.createEvent(r[a] || "Events"), d = !0;
if (b) for (var e in b) "bubbles" == e ? d = !!b[e] :c[e] = b[e];
return c.initEvent(a, d, !0), j(c);
};
}(d), function(a) {
function b(b, c, d) {
var e = a.Event(c);
return a(b).trigger(e, d), !e.isDefaultPrevented();
}
function c(a, c, d, e) {
return a.global ? b(c || s, d, e) :void 0;
}
function d(b) {
b.global && 0 === a.active++ && c(b, null, "ajaxStart");
}
function e(b) {
b.global && !--a.active && c(b, null, "ajaxStop");
}
function f(a, b) {
var d = b.context;
return b.beforeSend.call(d, a, b) === !1 || c(b, d, "ajaxBeforeSend", [ a, b ]) === !1 ? !1 :void c(b, d, "ajaxSend", [ a, b ]);
}
function g(a, b, d, e) {
var f = d.context, g = "success";
d.success.call(f, a, g, b), e && e.resolveWith(f, [ a, g, b ]), c(d, f, "ajaxSuccess", [ b, d, a ]), 
i(g, b, d);
}
function h(a, b, d, e, f) {
var g = e.context;
e.error.call(g, d, b, a), f && f.rejectWith(g, [ d, b, a ]), c(e, g, "ajaxError", [ d, e, a || b ]), 
i(b, d, e);
}
function i(a, b, d) {
var f = d.context;
d.complete.call(f, b, a), c(d, f, "ajaxComplete", [ b, d ]), e(d);
}
function j() {}
function k(a) {
return a && (a = a.split(";", 2)[0]), a && (a == x ? "html" :a == w ? "json" :u.test(a) ? "script" :v.test(a) && "xml") || "text";
}
function l(a, b) {
return "" == b ? a :(a + "&" + b).replace(/[&?]{1,2}/, "?");
}
function m(b) {
b.processData && b.data && "string" != a.type(b.data) && (b.data = a.param(b.data, b.traditional)), 
!b.data || b.type && "GET" != b.type.toUpperCase() || (b.url = l(b.url, b.data), 
b.data = void 0);
}
function n(b, c, d, e) {
return a.isFunction(c) && (e = d, d = c, c = void 0), a.isFunction(d) || (e = d, 
d = void 0), {
url:b,
data:c,
success:d,
dataType:e
};
}
function o(b, c, d, e) {
var f, g = a.isArray(c), h = a.isPlainObject(c);
a.each(c, function(c, i) {
f = a.type(i), e && (c = d ? e :e + "[" + (h || "object" == f || "array" == f ? c :"") + "]"), 
!e && g ? b.add(i.name, i.value) :"array" == f || !d && "object" == f ? o(b, i, d, c) :b.add(c, i);
});
}
var p, q, r = 0, s = window.document, t = /<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, u = /^(?:text|application)\/javascript/i, v = /^(?:text|application)\/xml/i, w = "application/json", x = "text/html", y = /^\s*$/;
a.active = 0, a.ajaxJSONP = function(b, c) {
if (!("type" in b)) return a.ajax(b);
var d, e, i = b.jsonpCallback, j = (a.isFunction(i) ? i() :i) || "jsonp" + ++r, k = s.createElement("script"), l = window[j], m = function(b) {
a(k).triggerHandler("error", b || "abort");
}, n = {
abort:m
};
return c && c.promise(n), a(k).on("load error", function(f, i) {
clearTimeout(e), a(k).off().remove(), "error" != f.type && d ? g(d[0], n, b, c) :h(null, i || "error", n, b, c), 
window[j] = l, d && a.isFunction(l) && l(d[0]), l = d = void 0;
}), f(n, b) === !1 ? (m("abort"), n) :(window[j] = function() {
d = arguments;
}, k.src = b.url.replace(/\?(.+)=\?/, "?$1=" + j), s.head.appendChild(k), b.timeout > 0 && (e = setTimeout(function() {
m("timeout");
}, b.timeout)), n);
}, a.ajaxSettings = {
type:"GET",
beforeSend:j,
success:j,
error:j,
complete:j,
context:null,
global:!0,
xhr:function() {
return new window.XMLHttpRequest();
},
accepts:{
script:"text/javascript, application/javascript, application/x-javascript",
json:w,
xml:"application/xml, text/xml",
html:x,
text:"text/plain"
},
crossDomain:!1,
timeout:0,
processData:!0,
cache:!0
}, a.ajax = function(b) {
var c = a.extend({}, b || {}), e = a.Deferred && a.Deferred();
for (p in a.ajaxSettings) void 0 === c[p] && (c[p] = a.ajaxSettings[p]);
d(c), c.crossDomain || (c.crossDomain = /^([\w-]+:)?\/\/([^\/]+)/.test(c.url) && RegExp.$2 != window.location.host), 
c.url || (c.url = window.location.toString()), m(c), c.cache === !1 && (c.url = l(c.url, "_=" + Date.now()));
var i = c.dataType, n = /\?.+=\?/.test(c.url);
if ("jsonp" == i || n) return n || (c.url = l(c.url, c.jsonp ? c.jsonp + "=?" :c.jsonp === !1 ? "" :"callback=?")), 
a.ajaxJSONP(c, e);
var o, r = c.accepts[i], s = {}, t = function(a, b) {
s[a.toLowerCase()] = [ a, b ];
}, u = /^([\w-]+:)\/\//.test(c.url) ? RegExp.$1 :window.location.protocol, v = c.xhr(), w = v.setRequestHeader;
if (e && e.promise(v), c.crossDomain || t("X-Requested-With", "XMLHttpRequest"), 
t("Accept", r || "*/*"), (r = c.mimeType || r) && (r.indexOf(",") > -1 && (r = r.split(",", 2)[0]), 
v.overrideMimeType && v.overrideMimeType(r)), (c.contentType || c.contentType !== !1 && c.data && "GET" != c.type.toUpperCase()) && t("Content-Type", c.contentType || "application/x-www-form-urlencoded"), 
c.headers) for (q in c.headers) t(q, c.headers[q]);
if (v.setRequestHeader = t, v.onreadystatechange = function() {
if (4 == v.readyState) {
v.onreadystatechange = j, clearTimeout(o);
var b, d = !1;
if (v.status >= 200 && v.status < 300 || 304 == v.status || 0 == v.status && "file:" == u) {
i = i || k(c.mimeType || v.getResponseHeader("content-type")), b = v.responseText;
try {
"script" == i ? (1, eval)(b) :"xml" == i ? b = v.responseXML :"json" == i && (b = y.test(b) ? null :a.parseJSON(b));
} catch (f) {
d = f;
}
d ? h(d, "parsererror", v, c, e) :g(b, v, c, e);
} else h(v.statusText || null, v.status ? "error" :"abort", v, c, e);
}
}, f(v, c) === !1) return v.abort(), h(null, "abort", v, c, e), v;
var x = "async" in c ? c.async :!0;
if (v.open(c.type, c.url, x, c.username, c.password), c.xhrFields) for (q in c.xhrFields) v[q] = c.xhrFields[q];
for (q in s) w.apply(v, s[q]);
return c.timeout > 0 && (o = setTimeout(function() {
v.onreadystatechange = j, v.abort(), h(null, "timeout", v, c, e);
}, c.timeout)), v.send(c.data ? c.data :null), v;
}, a.get = function() {
return a.ajax(n.apply(null, arguments));
}, a.post = function() {
var b = n.apply(null, arguments);
return b.type = "POST", a.ajax(b);
}, a.getJSON = function() {
var b = n.apply(null, arguments);
return b.dataType = "json", a.ajax(b);
}, a.fn.load = function(b, c, d) {
if (!this.length) return this;
var e, f = this, g = b.split(/\s/), h = n(b, c, d), i = h.success;
return g.length > 1 && (h.url = g[0], e = g[1]), h.success = function(b) {
f.html(e ? a("<div>").html(b.replace(t, "")).find(e) :b), i && i.apply(f, arguments);
}, a.ajax(h), this;
};
var z = encodeURIComponent;
a.param = function(a, b) {
var c = [];
return c.add = function(a, b) {
this.push(z(a) + "=" + z(b));
}, o(c, a, b), c.join("&").replace(/%20/g, "+");
};
}(d), function(a) {
a.fn.serializeArray = function() {
var b, c = [];
return a([].slice.call(this.get(0).elements)).each(function() {
b = a(this);
var d = b.attr("type");
"fieldset" != this.nodeName.toLowerCase() && !this.disabled && "submit" != d && "reset" != d && "button" != d && ("radio" != d && "checkbox" != d || this.checked) && c.push({
name:b.attr("name"),
value:b.val()
});
}), c;
}, a.fn.serialize = function() {
var a = [];
return this.serializeArray().forEach(function(b) {
a.push(encodeURIComponent(b.name) + "=" + encodeURIComponent(b.value));
}), a.join("&");
}, a.fn.submit = function(b) {
if (b) this.bind("submit", b); else if (this.length) {
var c = a.Event("submit");
this.eq(0).trigger(c), c.isDefaultPrevented() || this.get(0).submit();
}
return this;
};
}(d), function(a) {
"__proto__" in {} || a.extend(a.zepto, {
Z:function(b, c) {
return b = b || [], a.extend(b, a.fn), b.selector = c || "", b.__Z = !0, b;
},
isZ:function(b) {
return "array" === a.type(b) && "__Z" in b;
}
});
try {
getComputedStyle(void 0);
} catch (b) {
var c = getComputedStyle;
window.getComputedStyle = function(a) {
try {
return c(a);
} catch (b) {
return null;
}
};
}
}(d), c.exports = d;
}), define("scripts/weiOfficial", [ "./tpl/weiofficial", "./tpl/template", "./navibar", "./tpl/navibar", "./environment", "./vendor/zepto", "./message", "./tpl/loading", "./tpl/dialog", "./page", "./utils", "./vendor/promise", "./tpl/help" ], function(a, b) {
var c = a("./tpl/weiofficial"), d = a("./navibar"), e = a("./page"), f = (a("./environment"), 
a("./message")), g = (a("./page"), a("./utils"));
b.init = function() {
$(document.body).bind("weixin:official", h);
}, b.chk = function() {
window.localStorage && localStorage.setItem && ("a" == localStorage.getItem("chkAuth") ? localStorage.setItem("chkAuth", "b") :"a" == localStorage.getItem("weiReg") && (localStorage.setItem("weiReg", "b"), 
$(document.body).trigger("weixin:official")));
};
var h = b.showAttendForm = function() {
var b = d.getNaviBar("个人中心", {
hideUserIcon:!0,
hideWeiIcon:!0
}), h = parseInt(g.cookie.get("followed")), i = $(c({
followed:h
}));
i.children().first().before(b.navibarTpl), i.find(".item-home").one("click", function() {
location.hash = "0", e.remove(q.id), e.back();
});
var j = i.find(".nickname"), k = i.find(".headimgurl"), l = function() {
$.ajax({
url:"http://weika.mugeda.com/server/cards.php/userinfo",
dataType:"json",
data:{
token:$.cookie.get("token"),
t:+new Date(),
from:"uc"
},
success:function(a) {
0 == a.status ? (window.localStorage && localStorage.setItem && (localStorage.setItem("nickname", a.nickname), 
localStorage.setItem("headimgurl", a.headimgurl)), j.html(a.nickname), k.attr("src", a.headimgurl)) :f.showMessage("头像昵称加载出错，请稍候再试");
},
error:function() {
f.showMessage("头像昵称加载出错，请稍候再试");
}
});
};
window.localStorage && localStorage.getItem && (localStorage.getItem("nickname") && localStorage.getItem("headimgurl") ? (j.html(localStorage.getItem("nickname")), 
k.attr("src", localStorage.getItem("headimgurl"))) :l()), i.find(".logout").bind("click", function() {
g.cookie.set("cookie_openid", "", -1), g.cookie.set("followed", "", -1), g.cookie.set("token", "", -1), 
location.hash = "", e.remove(q.id), e.back(), f.showMessage("登出成功");
});
var m = i.find(".item-help"), n = a("./tpl/help");
m.click(function() {
var a = d.getNaviBar("帮助", {
hideUserIcon:!0,
hideWeiIcon:!0,
cancelLabel:"返回"
}), b = $("<div></div>");
b.append(a.navibarTpl).append(n({})), b.find(".cancelBtn").one("click", function() {
e.remove(c.id), e.back();
});
var c = e.setNewPage("help", {});
c.dom.append(b), e.addToLayout(c.id), e.setActive(c.id, !0);
});
var o = i.find(".item-imgLib");
o.click(function(b) {
location.hash = "photoView", a.async("./userview", function(a) {
a.userPhotoView(b, {
viewMode:"view"
});
});
}), i.on("touchstart", ".profile-items li", function() {
var a = $(this);
a.css("background-color", "#e4e4e4");
}), i.on("touchend", ".profile-items li", function() {
var a = $(this);
a.css("background-color", "#fff");
}), i.on("touchstart", ".logout", function() {
var a = $(this);
a.css("background-color", "#e4e4e4");
}), i.on("touchend", ".logout", function() {
var a = $(this);
a.css("background-color", "#fff");
});
var p = i.find(".item-collectLib");
p.click(function(b) {
location.hash = "collectView", a.async("./userview", function(a) {
a.userCollectView(b, {
viewMode:"view"
});
});
});
var q = e.setNewPage("weiOfficial", {
background:"#efeff4"
});
q.dom.append(i), e.addToLayout(q.id), e.setActive(q.id, !0);
};
}), define("scripts/zeptoExtra", [ "./vendor/zepto" ], function(a, b, c) {
var d = a("./vendor/zepto");
d.fn.isVisible = function() {
return "none" !== this.css("display");
}, d.fn.isExist = function() {
return d(this).parents("body").length ? !0 :!1;
}, d.cookie = {
get:function(a) {
var b = new RegExp("(^| )" + a + "(?:=([^;]*))?(;|$)"), c = document.cookie.match(b);
return c && c[2] ? unescape(c[2]) :"";
},
set:function(a, b, c) {
var d = new Date(), e = arguments[2] || 24 * (c || 7) * 60, f = arguments[3] || window.location.pathname.substr(0, window.location.pathname.lastIndexOf("/") + 1), g = arguments[4] || null, h = arguments[5] || !1;
e ? d.setMinutes(d.getMinutes() + parseInt(e)) :"", document.cookie = a + "=" + escape(b) + (e ? ";expires=" + d.toGMTString() :"") + (f ? ";path=" + f :"") + (g ? ";domain=" + g :"") + (h ? ";secure" :"");
}
}, function(a) {
var b = function(a, b) {
return function(c) {
var d, e, f;
return this ? (f = this, e = f[a](), d = {
width:[ "left", "right" ],
height:[ "top", "bottom" ]
}, d[a].forEach(function(a) {
e -= parseInt(f.css("padding-" + a), 10), b || (e -= parseInt(f.css("border-" + a + "-width"), 10)), 
c && (e += parseInt(f.css("margin-" + a), 10));
}), e) :null;
};
};
[ "width", "height" ].forEach(function(c) {
var d = c.substr(0, 1).toUpperCase() + c.substr(1);
a.fn["inner" + d] = b(c, !1), a.fn["outer" + d] = b(c, !0);
});
}(d), function(a) {
function b(a) {
var b = this.os = {}, c = this.browser = {}, d = a.match(/Web[kK]it[\/]{0,1}([\d.]+)/), e = a.match(/(Android);?[\s\/]+([\d.]+)?/), f = !!a.match(/\(Macintosh\; Intel /), g = a.match(/(iPad).*OS\s([\d_]+)/), h = a.match(/(iPod)(.*OS\s([\d_]+))?/), i = !g && a.match(/(iPhone\sOS)\s([\d_]+)/), j = a.match(/(webOS|hpwOS)[\s\/]([\d.]+)/), k = a.match(/Windows Phone ([\d.]+)/), l = j && a.match(/TouchPad/), m = a.match(/Kindle\/([\d.]+)/), n = a.match(/Silk\/([\d._]+)/), o = a.match(/(BlackBerry).*Version\/([\d.]+)/), p = a.match(/(BB10).*Version\/([\d.]+)/), q = a.match(/(RIM\sTablet\sOS)\s([\d.]+)/), r = a.match(/PlayBook/), s = a.match(/Chrome\/([\d.]+)/) || a.match(/CriOS\/([\d.]+)/), t = a.match(/Firefox\/([\d.]+)/), u = a.match(/MSIE\s([\d.]+)/) || a.match(/Trident\/[\d](?=[^\?]+).*rv:([0-9.].)/), v = !s && a.match(/(iPhone|iPod|iPad).*AppleWebKit(?!.*Safari)/), w = v || a.match(/Version\/([\d.]+)([^S](Safari)|[^M]*(Mobile)[^S]*(Safari))/);
(c.webkit = !!d) && (c.version = d[1]), e && (b.android = !0, b.version = e[2]), 
i && !h && (b.ios = b.iphone = !0, b.version = i[2].replace(/_/g, ".")), g && (b.ios = b.ipad = !0, 
b.version = g[2].replace(/_/g, ".")), h && (b.ios = b.ipod = !0, b.version = h[3] ? h[3].replace(/_/g, ".") :null), 
k && (b.wp = !0, b.version = k[1]), j && (b.webos = !0, b.version = j[2]), l && (b.touchpad = !0), 
o && (b.blackberry = !0, b.version = o[2]), p && (b.bb10 = !0, b.version = p[2]), 
q && (b.rimtabletos = !0, b.version = q[2]), r && (c.playbook = !0), m && (b.kindle = !0, 
b.version = m[1]), n && (c.silk = !0, c.version = n[1]), !n && b.android && a.match(/Kindle Fire/) && (c.silk = !0), 
s && (c.chrome = !0, c.version = s[1]), t && (c.firefox = !0, c.version = t[1]), 
u && (c.ie = !0, c.version = u[1]), w && (f || b.ios) && (c.safari = !0, f && (c.version = w[1])), 
v && (c.webview = !0), b.tablet = !!(g || r || e && !a.match(/Mobile/) || t && a.match(/Tablet/) || u && !a.match(/Phone/) && a.match(/Touch/)), 
b.phone = !(b.tablet || b.ipod || !(e || i || j || o || p || s && a.match(/Android/) || s && a.match(/CriOS\/([\d.]+)/) || t && a.match(/Mobile/) || u && a.match(/Touch/)));
}
b.call(a, navigator.userAgent), a.__detect = b;
}(d), function(a, b) {
function c(a) {
return a.replace(/([a-z])([A-Z])/, "$1-$2").toLowerCase();
}
function d(a) {
return e ? e + a :a.toLowerCase();
}
var e, f, g, h, i, j, k, l, m, n, o = "", p = {
Webkit:"webkit",
Moz:"",
O:"o"
}, q = window.document, r = q.createElement("div"), s = /^((translate|rotate|scale)(X|Y|Z|3d)?|matrix(3d)?|perspective|skew(X|Y)?)$/i, t = {};
a.each(p, function(a, c) {
return r.style[a + "TransitionProperty"] !== b ? (o = "-" + a.toLowerCase() + "-", 
e = c, !1) :void 0;
}), f = o + "transform", t[g = o + "transition-property"] = t[h = o + "transition-duration"] = t[j = o + "transition-delay"] = t[i = o + "transition-timing-function"] = t[k = o + "animation-name"] = t[l = o + "animation-duration"] = t[n = o + "animation-delay"] = t[m = o + "animation-timing-function"] = "", 
a.fx = {
off:e === b && r.style.transitionProperty === b,
speeds:{
_default:400,
fast:200,
slow:600
},
cssPrefix:o,
transitionEnd:d("TransitionEnd"),
animationEnd:d("AnimationEnd")
}, a.fn.animate = function(c, d, e, f, g) {
return a.isFunction(d) && (f = d, e = b, d = b), a.isFunction(e) && (f = e, e = b), 
a.isPlainObject(d) && (e = d.easing, f = d.complete, g = d.delay, d = d.duration), 
d && (d = ("number" == typeof d ? d :a.fx.speeds[d] || a.fx.speeds._default) / 1e3), 
g && (g = parseFloat(g) / 1e3), this.anim(c, d, e, f, g);
}, a.fn.anim = function(d, e, o, p, q) {
var r, u, v, w = {}, x = "", y = this, z = a.fx.transitionEnd, A = !1;
if (e === b && (e = a.fx.speeds._default / 1e3), q === b && (q = 0), a.fx.off && (e = 0), 
"string" == typeof d) w[k] = d, w[l] = e + "s", w[n] = q + "s", w[m] = o || "linear", 
z = a.fx.animationEnd; else {
u = [];
for (r in d) s.test(r) ? x += r + "(" + d[r] + ") " :(w[r] = d[r], u.push(c(r)));
x && (w[f] = x, u.push(f)), e > 0 && "object" == typeof d && (w[g] = u.join(", "), 
w[h] = e + "s", w[j] = q + "s", w[i] = o || "linear");
}
return v = function(b) {
if ("undefined" != typeof b) {
if (b.target !== b.currentTarget) return;
a(b.target).unbind(z, v);
} else a(this).unbind(z, v);
A = !0, a(this).css(t), p && p.call(this);
}, e > 0 && (this.bind(z, v), setTimeout(function() {
A || v.call(y);
}, 1e3 * e + 25)), this.size() && this.get(0).clientLeft, this.css(w), 0 >= e && setTimeout(function() {
y.each(function() {
v.call(this);
});
}, 0), this;
}, r = null;
}(Zepto), function() {
var a = [ "Webkit", "Moz", "O", "ms" ], b = d.map(a, function(a) {
return a.toLowerCase();
}), c = function(b) {
b = e(b);
var c = document.createElement("div").style, d = b.charAt(0).toUpperCase() + b.substring(1);
if (b in c) return b;
for (var f = 0; f < a.length; f++) if (a[f] + d in c) return a[f] + d;
return null;
}, e = function(a) {
return a.replace(/-+(.)?/g, function(a, b) {
return b ? b.toUpperCase() :"";
});
}, f = function(a) {
var c = a.replace(/([a-z\d])([A-Z])/g, "$1-$2").toLowerCase();
return -1 !== d.inArray(c.split("-")[0], b) ? "-" + c :c;
}, g = function(a, b, c, d) {
return c = "linear" === b ? "to bottom" === c ? "top" :"left" :-1 !== c.indexOf("ellipse") ? "center, ellispe cover" :"center, circle cover", 
"-" + a + "-" + b + "-gradient(" + c + "," + d + ")";
}, h = function(a, b, c) {
b = "linear" === a ? "to bottom" === b ? "left top, left bottom" :"left top, right top" :"center center, 0px, center center, 100%";
var e, f = [];
c = c.split(",");
for (var g = 0; g < c.length; g++) e = d.trim(c[g]).split(" "), f.push("color-stop(" + e[1] + "," + e[0] + ")");
return "-webkit-gradient(" + a + ", " + b + ", " + f.join(",") + ")";
}, i = function(a) {
var b = document.createElement("div");
return b.style.background = a, -1 !== b.style.background.indexOf("gradient");
}, j = function(a, c) {
if (i(c)) return c;
var e = c.match(/.*?\(([a-z ]+?),(.+?)\)/);
if (!e || e.length < 3) return c;
var f, j, k = d.trim(e[1]), l = d.trim(e[2]);
for (j = 0; j < b.length; j++) if (f = g(b[j], a, k, l), i(f)) return f;
var m = h(a, k, l);
return i(m) ? m :c;
}, k = {}, l = function(a) {
if (k[a]) return k[a];
var b = c(a);
return b ? (b = f(b), k[a] = b, b) :"";
}, m = function(a, b) {
return "display" === a && "box" === b ? l("box-flex").replace("-flex", "") :"transition" !== a && "transition-property" !== a || -1 === b.indexOf("transform") ? "transform" === a && -1 !== b.indexOf("translate3d(") ? "" !== l("perspective") ? b :b.replace("translate3d(", "translate(").replace(/(.*?,.*?)(,.*)/, "$1)") :"background-image" === a && -1 !== b.indexOf("linear-gradient(") ? j("linear", b) :"background-image" === a && -1 !== b.indexOf("radial-gradient(") ? j("radial", b) :b :b.replace("transform", l("transform"));
}, n = d.fn.css;
d.fn.css = function(a, b) {
if ("string" == typeof a) return 1 === arguments.length ? n.call(this, l(a)) :n.call(this, l(a), m(a, b));
for (var c in a) a[l(c)] = m(c, a[c]);
return n.call(this, a);
}, d.css = d.css || {}, d.css.getProp = l;
}(), c.exports = d;
});