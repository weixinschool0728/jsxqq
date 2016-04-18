var playbox = {//��ȡ����
	play: function(){
		var src = $("#audio").attr("src");
		var cls = $("#playbox").attr("class");
		if(src!=""){
			var audio = document.getElementById("audio"); 
			if(cls=="btn_music"){
				audio.play();
				$("#playbox").removeAttr("class");
				$("#playbox").attr("class", "btn_music on");
			}else{
				audio.pause();
				$("#playbox").removeAttr("class");
				$("#playbox").attr("class", "btn_music");
			}
			
		}
	}
};
/*var playbox = (function(){
    var _playbox = function(){
        var that = this;
        that.box = null;
        that.player = null;
        that.src = null;
        that.on = false;
        that.autoPlayFix = {
            on: true,
            evtName: ("ontouchstart" in window)?"touchend":"click"
        }

    }
    _playbox.prototype = {
        init: function(box_ele){
            this.box = "string" === typeof(box_ele)?document.getElementById(box_ele):box_ele;
            this.player = this.box.querySelectorAll("audio")[0];
            this.src = this.player.src;
            this.init = function(){return this;}
            this.autoPlayEvt(true);
            return this;
        },
        play: function(){
			alert(434);
            if(this.autoPlayFix.on){
                this.autoPlayFix.on = false;
                this.autoPlayEvt(false);
            }
            this.on = !this.on;
            if(true == this.on){
                this.player.src = this.src;
                this.player.play();
            }else{
                this.player.pause();
                this.player.src = null;
            }
            if("function" == typeof(this.play_fn)){
                this.play_fn.call(this);
            }
        },
        handleEvent: function(evt){
			alert(5);
            this.play();
        },
        autoPlayEvt: function(important){
            if(important || this.autoPlayFix.on){
                document.body.addEventListener(this.autoPlayFix.evtName, this, false);
            }else{
                document.body.removeEventListener(this.autoPlayFix.evtName, this, false);
            }
        }
    }
    return new _playbox();
})();

playbox.play_fn = function(){
    this.box.className = this.on?"btn_music on":"btn_music";
}*/