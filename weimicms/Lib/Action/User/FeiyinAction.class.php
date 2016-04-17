<?php
/**
 *飞印配置
**/
class FeiyinAction extends UserAction{
	public function index(){
		$db=D('Feiyin');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		$this->assign('feiyin',$res);
		$this->display();
	}
	
	public function insert(){
		$db=M('Feiyin');
		$_POST['uid']=$_SESSION['uid'];
		$_POST['token']=$_SESSION['token'];
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		if($res==false){
			$id=$db->add($_POST);
			if($id){
				$this->success('添加成功',U('Feiyin/index'));
			}else{
				$this->error('添加失败',U('Feiyin/index'));
			}
		}else{
			$id=$db->where($where)->save($_POST);
			if($id){
				$this->success('更新成功',U('Feiyin/index'));
			}else{
				$this->error('更新失败',U('Feiyin/index'));
			}
		}
	}
}
?>