<?php

class HcarAction extends WapAction {

	public function index(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function aiqing(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function gaoguai(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function xinqing(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function shengri(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function zhufu(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$sm=M('Hcarreplay')->where('id='.$id)->find();
		$this->assign('sm',$sm);
		
		$this->display();

	}
	public function hcar_view(){
	
		$wecha_id= $this->_get('wecha_id');
		$token = $this->_get('token');
		$id=$this->_get('id');
		$cardid=$this->_get('cardid');
		$mode=$this->_get('mode');
		
		$target=$this->_get('target');
		$targetanswer=$this->_get('targetanswer');
		if($cardid==""){
			echo "非法操作";
			exit;
		}
		$t_carreplay=M('Hcarreplay')->where('id='.$id)->find();
		
		$this->assign('fxlj',$t_carreplay['lj']);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->assign('targetanswer',$targetanswer);
			$this->assign('id',$id);
		$this->assign('cardid',$cardid);
		$this->assign('target',$target);
		$this->assign('mode',$mode);
		$this->display($cardid.'_view');
	}
	public function hcar_make(){
	
		$wecha_id= $this->_get('wecha_id');
		$cardids= $this->_get('cardids');
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
		$this->assign('cardids',$cardids);
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