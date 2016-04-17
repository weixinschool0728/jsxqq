<?php

class KawahkAction extends WapAction {

	public function index(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
	
		$this->display();

	}
		public function hcar_view(){
	    
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$words=$this->_get('words');
		$cardid=$this->_get('cardid');
		$mode=$this->_get('mode');
		$music=$this->_get('music');
		$hk=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		$info=M('kawahk')->where(array('token'=>$this->_get('token')))->select();
		$target=$this->_get('target');
		$targetanswer=$this->_get('targetanswer');
		if($cardid==""){
			echo "非法操作";
			exit;
		}
			$hk['lj'] = str_replace("&amp;","&",$hk['lj']);
		$hk['lj']=$hk['lj'].'#wechat_redirect';
		//dump($info);exit;
		
		$this->assign('hk',$hk);
		$this->assign('info',$info);
		$this->assign('fxlj',$t_carreplay['lj']);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->assign('music',$music);
		$this->assign('targetanswer',$targetanswer);
		$this->assign('id',$id);
		$this->assign('cardid',$cardid);
		$this->assign('words',$words);
		$this->assign('target',$target);
		$this->assign('mode',$mode);
		$this->display($cardid);
	}
	public function mp3(){
	
		
		
		$this->display();

	}
	public function xinqing(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		$this->display();

	}
	public function aiqing(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		
		$this->display();

	}
		public function zhufu(){
	
		
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		$this->display();

	}
	public function shengri(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		
		$this->display();

	}
	public function gaoguai(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		
		$this->display();

	}
	public function remen(){
	
		$ad=M('kawahkreplay')->where(array('token'=>$this->_get('token')))->find();
		//dump($ad);exit;
		$this->assign('ad',$ad);
		
		
		$this->display();

	}
	public function make(){
	
		$wecha_id= $this->_get('wecha_id');
		$cardid=$this->_get('cardid');
		$token = $this->_get('token');
		$words=$this->_get('words');
		$id=$this->_get('id');
		$message=$this->_get('message');
		$cardid=$this->_get('cardid');
		
		$action=$this->_get('action');
		$t_hcar=M('Hcar');
		$hcar=$t_hcar->where(array('token'=>$token))->select();
		if($cardid==""){
			echo "非法操作";
			exit;
		}
		if($message){
			$arr = array("，" => "，<br>", "。" => "。<br>","！"=>"！<br>","？"=>"？<br>");
			$message=strtr($message,$arr);
		}
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->assign('cardid',$cardid);
	
		$this->assign('words',$words);
		$this->assign('hcar',$hcar);
		$this->assign('message',$message);
		$t_carreplay=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('fxlj',$t_carreplay['lj']);
		if($action==1){
		$this->display($cardid.'_view');
		}else
		$this->display();
		

		
	}
	public function hcar_make1(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$message=$this->_get('message');
		$cardid=$this->_get('cardid');
		$words=$this->_get('words');
		$music=$this->_get('music');
		$action=$this->_get('action');
		$t_hcar=M('Hcar');
		$hcar=$t_hcar->where(array('token'=>$token))->select();
		if($cardid==""){
			echo "非法操作";
			exit;
		}
		if($message){
			$arr = array("，" => "，<br>", "。" => "。<br>","！"=>"！<br>","？"=>"？<br>");
			$message=strtr($message,$arr);
		}
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->assign('cardid',$cardid);
		$this->assign('hcar',$hcar);
		$this->assign('music',$music);
		$this->assign('words',$words);
		$this->assign('message',$message);
		$t_carreplay=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('fxlj',$t_carreplay['lj']);
		if($action==1){
		$this->display($cardid.'_view');
		}else
		$this->display();
		

		
	}
	
}