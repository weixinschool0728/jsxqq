H = 960;
function qp_a(a) {}
var qp_b = 10,
qp_c, qp_d = 5,
qp_e = 3,
qp_f = qp_e,
qp_g = 420,
qp_h = 0,
qp_i = [],
qp_j = 20,
qp_k = 0,
qp_l,
qp_m = 0,
qp_n = 0,
qp_o = 0;
function qp_p() {
	qipaStage.stage.arrow.visible = !0;
	qp_q = qipaApp.score = 0;
	qp_n = qp_b;
	qp_m = -1;
	qipaStage.stage.num.txt.text = qp_n + '"';
	qp_k = 0;
	qp_o = 1;
	qipaApp.onGameStarted()
}
function qp_r() {
	qipaStage.stage.splash.visible = !0
}
function qp_s() {
	qipaStage.stage.arrow.visible = !1;
	qp_m = 0
}
function qp_t() {
	qp_o = 3;
	qp_l = setTimeout(function() {
		window.clearTimeout(qp_l)
	},
	900);
	qp_u();
	qipaApp.onNewScore(qipaApp.score);
	qipaApp.onGameOver();
	qipaStage.stage.gameover.visible = !0;
	qipaStage.stage.gameover.refresh()
}
function qp_v(a) {
	IS_ANDROID && (createjs.Sound.registMySound("count", 0), createjs.Sound.registMySound("silenttail", 0.25));
	qp_w();
	qp_u()
}
function Qp_x() {
	this.initialize();
	this.bg = new createjs.Shape;
	this.bg.graphics.beginFill("#312551").drawRect(0, 0, W, H);
	this.addChild(this.bg);

	this.label = new createjs.Bitmap(qipaStage.queue.getResult("splashtitle"));
	this.label.x = (W - this.label.getBounds().width) / 2;
	this.label.y = 100;
	this.addChild(this.label);
	this.start = new createjs.Bitmap(qipaStage.queue.getResult("m0"));
	this.start.y = H - 300;
	this.start.x = (W - this.start.getBounds().width) / 2-4;
	this.addChild(this.start);
	this.startBg = new createjs.Bitmap(qipaStage.queue.getResult("mb0"));
	this.startBg.y = H - 350;
	this.startBg.x = (W - this.startBg.getBounds().width) / 2;
	this.addChild(this.startBg);
	this.arrow = new createjs.Bitmap(qipaStage.queue.getResult("starttip"));
	this.arrow.y = H - 480;
	this.arrow.x = (W - this.arrow.getBounds().width) / 2;
	this.addChild(this.arrow);

	this.cover = new createjs.Bitmap(qipaStage.queue.getResult("cover"));
	this.cover.y = H - 220;
	this.cover.x = (W - this.cover.getBounds().width) / 2;
	this.addChild(this.cover);


	this.tip = new createjs.Text("快速上滑，滑动次数越多，优惠越多！", "bold 34px Arial", "white");
	this.tip.textAlign = "center";
	this.tip.textBaseline = "middle";
	this.tip.x = W /2;
	this.tip.y = H-400;
	this.addChild(this.tip);


	var a, b;
	this.start.on("mousedown",
	function(c) {
		0 == qp_o && (a = c.localY, b = H - 300)
	});
	this.start.on("pressmove",
	function(c) {
		0 == qp_o && SplashPressmoveEvent(c.localY - a, b)
	});
	this.start.on("pressup",
	function(b) {
		0 == qp_o && 30 > a - b.localY && (createjs.Sound.play("count", !0), createjs.Tween.get(qipaStage.stage.splash.start).to({
			y: -H
		},
		400).call(function(a) {
			qipaStage.stage.splash.arrow.visible = !1;
			qp_p();
			qipaStage.stage.splash.visible = !1
		}))
	})
}
Qp_x.prototype = new createjs.Container;
function SplashPressmoveEvent(a, b) {
	qipaStage.stage.splash.start.y + a < b && (qipaStage.stage.splash.start.y += a)
}
var ii=0;

function qp_w() {
	var a = new createjs.Shape;
	a.graphics.beginFill("#312551").drawRect(0, 0, W, H);
	qipaStage.stage.addChild(a);
	var b = new createjs.Shape;
	b.graphics.beginFill("white").rect(0, 200, W, H);
	a.hitArea = b;
	var c = 0,
	d = 0;
	a.on("mousedown",
	function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || 2 != qp_o && 1 != qp_o || (c = a.localY, d = qipaStage.stage.player.m[qp_f].y)
	});
	a.on("pressmove",
	function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || (1 == qp_o && (qp_s(), qp_o = 2), 2 == qp_o && (qipaStage.stage.player.m[qp_f].visible = !0, qipaStage.stage.player.m[qp_f].y += (a.localY - c) / 1.5))
	});
	var f = 0;
	a.on("pressup",
	function(a) {
		//ii+=1;
		//console.log(ii);
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || 2 != qp_o || (50 < c - a.localY ? (a = (new Date).getTime(), 0 < qp_i.length && qp_i[qp_i.length - 1] + 50 > a ? qp_a("WARNING: Too fast! maybe engine error.") : (f = qp_y(a), f <= qp_j ? (qp_k++, qipaApp.score += Math.round(Math.random()*99)+1, qipaStage.stage.player.playAnimation(qipaStage.stage.player.m[qp_f]), createjs.Sound.play("count", !0)) : (qp_i.length--, qp_a("WARN: " + f)))) : (qp_z(d), qipaStage.stage.player.m[qp_f].visible = !1))}
	);
	qp_c = [];
	for (a = 0; a <= qp_e; a++) for (qp_c[a] = [], b = 0; b < qp_d; b++) {
		var e = new createjs.Bitmap(qipaStage.queue.getResult("d0"));
		e.regX = e.getBounds().width / 2;
		e.regY = e.getBounds().height / 2;
		e.x = genRandom(W);
		e.y = -H / 2 + genRandom(H);
		e.visible = !1;
		qp_c[a].push(e);
		qipaStage.stage.addChild(qp_c[a][b])
	}
	qipaStage.stage.player = new Qp_A;
	qipaStage.stage.addChild(qipaStage.stage.player);
	qipaStage.stage.num = new Qp_B;
	qipaStage.stage.num.y = 30;
	qipaStage.stage.addChild(qipaStage.stage.num);
	qipaStage.stage.arrow = new createjs.Bitmap(qipaStage.queue.getResult("starttip"));
	qipaStage.stage.arrow.x = (W - qipaStage.stage.arrow.getBounds().width) / 2;
	qipaStage.stage.arrow.y = 290;
	qipaStage.stage.arrow.visible = !1;
	qipaStage.stage.addChild(qipaStage.stage.arrow);

	qipaStage.stage.cover = new createjs.Bitmap(qipaStage.queue.getResult("cover"));
	qipaStage.stage.cover.x = (W - qipaStage.stage.cover.getBounds().width) / 2;
	qipaStage.stage.cover.y = H-280;
	//qipaStage.stage.cover.visible = !1;
	qipaStage.stage.addChild(qipaStage.stage.cover);

	qipaStage.stage.gameover = new Qp_C;
	qipaStage.stage.gameover.x = 0;
	qipaStage.stage.gameover.y = 0;
	qipaStage.stage.gameover.visible = !1;
	qipaStage.stage.addChild(qipaStage.stage.gameover);
	qipaStage.stage.splash = new Qp_x;
	qipaStage.stage.addChild(qipaStage.stage.splash);
	setInterval(qp_D, 1E3);
	createjs.Ticker.addEventListener("tick",
	function(a) {
		0 <= qp_m && (qp_m += a.delta, a = 10 - parseInt(qp_m / 1E3), a != qp_n && (qp_n = a, qipaStage.stage.num.txt.text = qp_n + '"'), 0 >= qp_n && (qp_m = -1, qp_t()));
		qipaStage.stage.num.sum.text = "\uffe5" + qipaApp.score
	})
}
function Qp_A() {
	this.initialize();
	this.mb = new createjs.Bitmap(qipaStage.queue.getResult("mb0"));
	this.mb.regX = this.mb.getBounds().width / 2;
	this.mb.regY = this.mb.getBounds().height / 2;
	this.mb.y = qp_g;
	this.x = W / 2;
	this.y = H / 2 - 260;
	this.addChild(this.mb);
	this.m = [];
	for (var a = 0; 3 >= a; a++) this.m[a] = new createjs.Bitmap(qipaStage.queue.getResult("m0")),
	this.m[a].regX = this.m[a].getBounds().width / 2,
	this.m[a].regY = this.m[a].getBounds().height / 2,
	this.m[a].y = qp_g,
	this.m[a].visible = !1,
	this.addChild(this.m[a]);
	for (a = 0; a <= qp_e; a++) this.m[a].image = qipaStage.queue.getResult("m0");
	for (a = 0; a < qp_c.length; a++) for (var b = 0; b < qp_c[a].length; b++) qp_c[a][b].image = qipaStage.queue.getResult("d0")
}
Qp_A.prototype = new createjs.Container;
Qp_A.prototype.playAnimation = function(a) {
	a.visible = !0;
	createjs.Tween.get(a).to({
		scaleX: 0.5,
		scaleY: 0.5,
		y: -H
	},
	300).to({
		visible: !1,
		y: qp_g,
		scaleX: 1,
		scaleY: 1
	},
	0);
	0 < qp_f ? qp_f--:qp_f = qp_e
};
function genRandom(a) {
	return parseInt(Math.random() * a)
}
function qp_E(a) {
	return 10
}
var qp_F = 0;
function qp_D() {
	for (var a = 0; a < qp_d; a++) qp_c[qp_F][a].visible = !0,
	createjs.Tween.get(qp_c[qp_F][a]).to({
		y: H + qp_c[qp_F][a].getBounds().height / 2 + 100,
		rotation: 720 + genRandom(400),
		x: genRandom(W)
	},
	1E3 + genRandom(800)).to({
		visible: !1
	},
	10).to({
		x: genRandom(W),
		y: -H / 2 + genRandom(H / 2),
		rotation: 0
	},
	10);
	qp_F < qp_e ? qp_F++:qp_F = 0
}
function qp_z(a) {
	var b = Math.abs(qipaStage.stage.player.m[qp_f] - a);
	createjs.Tween.get(qipaStage.stage.player.m[qp_f]).to({
		y: a
	},
	20 * b)
}
function Qp_C() {
	this.initialize();
	var fullBg=new createjs.Shape,
		xxBg=qipaStage.queue.getResult("rgbabg");
	fullBg.setBounds(0,0,W,H);
	fullBg.graphics.bf(xxBg).r(0,0, W, H);

	var a = new createjs.Shape,

	b = qipaStage.queue.getResult("dlgbg");
	a.setBounds(0,0, W-80, b.height);
	a.graphics.bf(b).r(40,(H- b.height)/2, W-80, b.height);



	var box1 = new createjs.Bitmap(qipaStage.queue.getResult("open"));
	box1.scaleX=0.7;
	box1.scaleY=0.7;
	box1.x = (W - box1.getBounds().width*0.7) / 2;
	box1.y = (H- b.height)/2+180;


	this.addChild(fullBg);
	this.addChild(a);
	this.addChild(box1);

	var backIndex=new createjs.Bitmap(qipaStage.queue.getResult("xclosed"));
	backIndex.x=W-80;
	backIndex.y=90;
	backIndex.on('click',function(){
		location.href='http://www.tanytree.com/xzcms/money/';
	});
	this.addChild(backIndex);

		b = new createjs.Bitmap(qipaStage.queue.getResult("start"));
	b.x = 60;
	b.y = a.y + a.getBounds().height - 40;
	b.on("click",
	function(a) {
		//qp_p();
		//qipaStage.stage.gameover.visible = !1
		location.href='http://www.tanytree.com/xzcms/money/';
	});
	//var c = new createjs.Bitmap(qipaStage.queue.getResult("rank"));
	//c.x = W / 2;
	//c.y = b.y;
	//c.regX = c.getBounds().width / 2;
	//c.on("click",
	//function(a) {
	//	clickMore();
	//});
	var d = new createjs.Bitmap(qipaStage.queue.getResult("share"));
	d.x = W+60 - d.getBounds().width;
	d.y = b.y;
	d.regX = 120;
	d.on("click",
	function(a) {
		IS_TOUCH && a.nativeEvent instanceof MouseEvent || dp_share()
	});
	this.addChild(b);
	//this.addChild(c);
	this.addChild(d);
	this.scoreText = new createjs.Text("", "bold 60px Arial", "#ffd900");
	this.scoreText.textAlign = "center";
	this.scoreText.x = W / 2;
	this.scoreText.y = a.y + 150;
	this.addChild(this.scoreText);
	this.shareText = new createjs.Text("", "38px Arial", "#eee");
	this.shareText.textAlign = "center";
	this.shareText.x = W / 2;
	this.shareText.y = b.y-160;
	this.addChild(this.shareText);
	this.starText = new createjs.Text("继续抢优惠(2次)", "26px Arial", "#fff");
	this.starText.textAlign = "left";
	this.starText.x = 85;
	this.starText.y = b.y+10;
	this.addChild(this.starText);
	this.share1Text = new createjs.Text("炫耀一下", "26px Arial", "#fff");
	this.share1Text.textAlign = "left";
	this.share1Text.x = W - d.getBounds().width+10;
	this.share1Text.y = d.y+10;
	this.addChild(this.share1Text);
}
Qp_C.prototype = new createjs.Container;
Qp_C.prototype.refresh = function() {
	this.scoreText.text = "￥" + qipaApp.score;
	this.shareText.text = 0 < qipaApp.score ? qipaShare.desc.replace("比", "\n比").replace("我是", "\n我是") : ""
};

function Qp_B() {
	this.initialize();
	this.tmbg = new createjs.Bitmap(qipaStage.queue.getResult("money"));
	this.tmbg.x = W - this.tmbg.getBounds().width-20;
	this.tmbg.y = 30;
	this.addChild(this.tmbg);
	this.sum = new createjs.Text("\uffe5" + qipaApp.score, "bold 34px Arial", "yellow");
	this.sum.textAlign = "left";
	this.sum.textBaseline = "middle";
	this.sum.x = W - this.tmbg.getBounds().width+50;
	this.sum.y = 65;
	this.addChild(this.sum);
	this.tmbg1 = new createjs.Bitmap(qipaStage.queue.getResult("time"));
	//this.tmbg1.scaleX = 0.7;
	this.tmbg1.x = 20;
	this.tmbg1.y = 30;
	this.addChild(this.tmbg1);
	//this.tmicon = new createjs.Bitmap(qipaStage.queue.getResult("tmicon"));
	//this.tmicon.x = this.tmbg1.x + 14;
	//this.tmicon.y = this.tmbg1.y + 14;
	//this.addChild(this.tmicon);
	this.txt = new createjs.Text(qp_n + '"', "bold 34px Arial", "white");
	this.txt.textAlign = "left";
	this.txt.textBaseline = "middle";
	this.txt.x =100;
	this.txt.y = 65;
	this.addChild(this.txt)
}
Qp_B.prototype = new createjs.Container;
function qp_y(a) {
	var b = 0;
	if (0 != qp_i.length) {
		var c;
		for (c = 0; c < qp_i.length && !(qp_i[c] > a - 1E3); c++);
		for (var b = qp_i.length - c,
		d = c; d < qp_i.length; d++) qp_i[d - c] = qp_i[d];
		qp_i.length -= c
	}
	qp_i.push(a);
	return parseInt(b)
}
function qp_u() {
	qipaShare.title = "数钱数到手抽筋！你是数钱高手吗？";
	if (0 == qipaApp.score) qipaShare.desc = qipaShare.title;
	else {
		var a = parseInt(Math.sqrt(1E4 * qipaApp.score / 17E3));
		99 < a && (a = "99.9");
		qipaShare.desc = "您获得本次优惠￥" + qipaApp.score + "，比" + a + "%的人有钱！"+"你是" + (500 > qipaApp.score ? "屌丝" : 1000 > qipaApp.score ? "贫农" : 2000 > qipaApp.score ? "富农" : 3000 > qipaApp.score ? "土豪" : 4000 > qipaApp.score ? "煤老板" : "资本家") + "。"
	}
	dp_submitScore(qipaApp.score);
}
var _cfg = {
	startFunc: qp_v,
	img: {
		path: "img/",
		manifest: [{
			src: "m0.png",
			id: "m0"
		},
		{
			src: "mb0.png",
			id: "mb0"
		},
		{
			src: "d0.png",
			id: "d0"
		},
		{
			src: "starttip.png",
			id: "starttip"
		},
		{
			src: "tmbg.png",
			id: "tmbg"
		},
		{
			src: "splashtitle.png",
			id: "splashtitle"
		},
		{
			src: "tmicon.png",
			id: "tmicon"
		},
		{
			src: "start.png",
			id: "start"
		},
		{
			src: "rank.png",
			id: "rank"
		},
		{
			src: "share.png",
			id: "share"
		},
			{
				src: "dlgbg.png",
				id: "dlgbg"
			},
			{
				src: "cover.png",
				id: "cover"
			},
			{
				src: "time.png",
				id: "time"
			},
			{
				src: "money.png",
				id: "money"
			},
			{
				src: "rgbabg.png",
				id: "rgbabg"
			},
			{
				src: "open.png",
				id: "open"
			},
			{
				src: "xclosed.png",
				id: "xclosed"
			}]
	},
	audio: {
		path: "audio/",
		manifest: [{
			src: "count.mp3",
			id: "count"
		}]
	}
};
qipaStage.init(_cfg);