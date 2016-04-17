<?php

class Car_baoyang_inputAction extends UserAction{
	public function index(){
		
		
		$token_open=M('token_open')->field('queryname')->where(array('token'=>$_SESSION['token']))->find();

		if(!strpos($token_open['queryname'],'Car')){
            $this->error('您还未开启该模块的使用权,请到功能模块中添加',U('Function/index',array('token'=>$_SESSION['token'],'id'=>session('wxid'))));}
		
		$db=D('Car_baoyang_input');
		$where['token']=session('token');
		$where['hid']=$_GET['id'];
		$count=$db->where($where)->count();
		$page=new Page($count,25);
		$info=$db->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('info',$info);
		$this->display();
	}
	
	public function add(){
		$this->display();
	}
	
	public function edit(){
		$id=$this->_get('id','intval');
		$info=M('Car_baoyang_input')->find($id);
		$this->assign('info',$info);
		$this->display();
	}
	
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['uid']=session('uid');
		if(D(MODULE_NAME)->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	public function insert(){
		$this->all_insert();
	}
	public function upsave(){
		$this->all_save();
	}
}
?>