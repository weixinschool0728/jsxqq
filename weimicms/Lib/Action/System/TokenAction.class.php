<?php
class TokenAction extends BackAction{
	private $_type = array('1'=>'订阅号', '2'=>'服务号', '3'=>'认证服务号', '4'=>'认证订阅号');
	public function index(){
		$map = array();
		$UserDB = D('Wxuser');
		//if (isset($_GET['agentid'])){
			$map=array('agentid'=>intval($_GET['agentid']));
		//}
		$count = $UserDB->where($map)->count();
		$Page       = new Page($count,10);// 实例化分页类 传入总记录数
		// 进行分页数据查询 注意page方法的参数的前面部分是当前的页数使用 $_GET[p]获取
		$nowPage = isset($_GET['p'])?$_GET['p']:1;
		$show       = $Page->show();// 分页显示输出
		$list = $UserDB->where($map)->order('id ASC')->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		//dump($list);
		foreach($list as $key=>$value){
			$user=M('Users')->field('id,gid,username')->where(array('id'=>$value['uid']))->find();
			if($user){
				$list[$key]['user']['username']=$user['username'];
				$list[$key]['user']['gid']=$user['gid']-1;
			}
		}
		//dump($list);
		$this->assign('list',$list);
		$this->assign('page',$show);// 赋值分页输出
		$this->display();
		
		
	}
	public function del(){
		$id=$this->_get('id','intval',0);
		$wx=M('Wxuser')->where(array('id'=>$id))->find();
		if (0 < $wx['is_syn']) {
			$this->error('不允许删除对接公众号');
		}
		if ($wx['agentid']){
			M('Agent')->where(array('id'=>$wx['agentid']))->setDec('wxusercount');
		}
		M('Img')->where(array('token'=>$wx['token']))->delete();
		M('Text')->where(array('token'=>$wx['token']))->delete();
		M('Lottery')->where(array('token'=>$wx['token']))->delete();
		M('Keyword')->where(array('token'=>$wx['token']))->delete();
		M('Photo')->where(array('token'=>$wx['token']))->delete();
		M('Home')->where(array('token'=>$wx['token']))->delete();
		M('Areply')->where(array('token'=>$wx['token']))->delete();
		$diy=M('Diymen_class')->where(array('token'=>$wx['token']))->delete();
		M('Wxuser')->where(array('id'=>$id))->delete();
		$this->success('操作成功');
	}
	
	public function edit(){
		$id=$this->_get('id','intval',0);
		if(IS_POST){
			$merchant_id = $this->_post('merchant_id', 'trim');
			M('Wxuser')->where(array('id'=>$id))->setField('merchant_id', $merchant_id);
			$this->success('修改成功', U('Token/index'));
		}else{
			if($id==0)$this->error('非法操作');
			$this->assign('tpltitle','编辑');
			$wxuser = M('Wxuser')->where(array('id'=>$id))->find();
			$user=M('Users')->field('id,gid,username')->where(array('id'=>$wxuser['uid']))->find();
			$wxuser['username'] = $user['username'];
			$this->assign('info',$wxuser);
			$this->assign('weixintype', $this->_type[$wxuser['winxintype']]);
			$this->display('add');
		}
	}
}
?>