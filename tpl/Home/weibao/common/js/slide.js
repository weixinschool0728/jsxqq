function slide(box,btn,btn_class,time){//box:���о��붯���İ�����btn:��ť�б�btn_class:��ťѡ��״̬����ʽ����time:����ִ�еļ��ʱ��
	var i = 0,timeAnim;
	wid = parseInt(box.width());
	
	btn.click(function(){ //�����ť����
		var eq = btn.index(this);
		i = eq;
		anim(i,box,btn,wid,btn_class);
	})
	
	timeAnim = setInterval(function(){//�Զ�����
		i++;
		if(i>=btn.length){
			i=0;
		}
		anim(i,box,btn,wid,btn_class);
	},time);
	
	box.mouseover(function(){//��banner��Χ�������ʱ����Ч��
		clearInterval(timeAnim)
	})
	box.mouseout(function(){
		timeAnim = setInterval(function(){
			i++;
			if(i>=btn.length){
				i=0;
			}
			anim(i,box,btn,wid,btn_class);
		},time);
	})
	
	btn.mouseover(function(){//�ڰ�ť��Χ�� �����ʱ����Ч��
		clearInterval(timeAnim)
	})
	btn.mouseout(function(){
		timeAnim = setInterval(function(){
			i++;
			if(i>=btn.length){
				i=0;
			}
			anim(i,box,btn,wid,btn_class);
		},time);
	})
	
	function anim(index,box,btn,wid,btn_class){//ͼƬ�ֲ�
		btn.removeClass(btn_class).eq(index).addClass(btn_class);
		if(!box.is(":animated")){
			box.animate({scrollLeft:index*wid},500);
		}
	}
}