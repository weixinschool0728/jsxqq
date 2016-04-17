<?php
class MusiccarAction extends WapAction{
	public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}
	//秀秀卡妞微秀
	public function index(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$mus=$this->_get('mus');
		$name=$this->_get('name');
		$time=time();
		$bigpic=$this->_get('bigpic');
		$wecha_id=$this->_get('wecha_id');
		$note=$this->_get('note');
		$info = M('Musiccar')->where(array('token'=>$token))->find();
	    $this->assign('token',$token);
		 $this->assign('info',$info);
		$this->assign('note',$note);
		$this->assign('name',$name);
		
		$this->assign('time',$time);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('mus',$mus);
	    $this->assign('tx',$tx);
		
		
		$this->display();
		
		
	}
	public function mp3(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$name=$this->_get('name');
		$note=$this->_get('note');
		$bigpic=$this->_get('bigpic');
		$mus=$this->_get('mus');
		$wecha_id=$this->_get('wecha_id');
	    $this->assign('token',$token);
		$this->assign('name',$name);
		$this->assign('note',$note);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
	    $this->assign('tx',$tx);
		
		$this->display();
		
		
	}
	public function bg(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$note=$this->_get('note');
		$mus=$this->_get('mus');
		$name=$this->_get('name');
		$this->assign('name',$name);
		
		$wecha_id=$this->_get('wecha_id');
	    $this->assign('token',$token);
		$bigpic=$this->_get('bigpic');
		$this->assign('note',$note);
		$this->assign('mus',$mus);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
	    $this->assign('tx',$tx);
		
		$this->display();
		
		
	}
	public function youyi(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$note=$this->_get('note');
		$mus=$this->_get('mus');
		$name=$this->_get('name');
		$this->assign('name',$name);
		$wecha_id=$this->_get('wecha_id');
	    $this->assign('token',$token);
		$bigpic=$this->_get('bigpic');
		$this->assign('note',$note);
		$this->assign('mus',$mus);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
	    $this->assign('tx',$tx);
		
		$this->display();
		
		
	}
	public function ganxie(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$name=$this->_get('name');
		$this->assign('name',$name);
		$note=$this->_get('note');
		$mus=$this->_get('mus');
		$wecha_id=$this->_get('wecha_id');
	    $this->assign('token',$token);
		$bigpic=$this->_get('bigpic');
		$this->assign('note',$note);
		$this->assign('mus',$mus);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
	    $this->assign('tx',$tx);
		
		$this->display();
		
		
	}
	public function shengri(){
	    $tx=$this->_get('tx');
	    $token=$this->_get('token');
		$note=$this->_get('note');
		$mus=$this->_get('mus');
		$wecha_id=$this->_get('wecha_id');
		$name=$this->_get('name');
		$this->assign('name',$name);
	    $this->assign('token',$token);
		$bigpic=$this->_get('bigpic');
		$this->assign('note',$note);
		$this->assign('mus',$mus);
		$this->assign('bigpic',$bigpic);
		$this->assign('wecha_id',$wecha_id);
	    $this->assign('tx',$tx);
		
		$this->display();
		
		
	}
	
}



?>