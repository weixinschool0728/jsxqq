<?php
class LapiaoAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		//$this->canUseFunction('lapiao');
		$this->shake_model=M('Lapiao');
		$this->token_where['token']=$this->token;
		$this->keyword_model=M('Keyword');
		$this->token=$this->token;
		if(strlen($this->token)>32){
			die($this->token);
		}
		$this->assign('token',$this->token);
		 $uid = session('uid');
		 $this->assign('uid',$uid);		 
	}
	public function index(){
		$act = $this->_get('act');
		if('del'==$act){
			//$id = $this->_get('id');;
			$id=intval($_GET['id']);
			$rt=$this->shake_model->where(array('id'=>$id))->delete();
			if ($rt){
				$this->keyword_model->where(array('module'=>'Lapiao','pid'=>$this->token_where['id']))->delete();
				$this->success('操作成功',U('Lapiao/index',array('token'=>$this->token)));
			}else{
				$this->error('操作失败',U('Lapiao/index',array('token'=>$this->token)));
			}
		}else{
			$where['token']=$this->token;
			$res=$this->shake_model->where($where)->order('id desc')->select();
			foreach ($res as $key => $li){
				if(strtotime($li['kssj'])>time()){
			 		$res[$key]['status'] = '<span>未生效</span>';
			 	}else if(strtotime($li['jssj'])<time()){
			 		$res[$key]['status'] = '<span>已失效</span>';
			 	} else {
			 		$res[$key]['status'] = '<span>有效</span>';
			 	}
			}

			$this->assign('res',$res);
			$this->display();
		}
	}
 
	public function add(){
		if(IS_POST){
			$this->all_insert('Lapiao','/index?token='.$this->token);exit;
		}
		$info=array();
		$info['tbtx'] = "1.请您填写个人信息获取唯一票号<br/> 2.将您的票号告诉身边的朋友来关注公众账号并回复您的票号<br/>3.您的票号被回复一次系统就自动给您的的拉票数量+1<br/>4.活动结束后，拉票数量最多的用户将按排名获得奖品<br/>";
		$this->assign('info',$info);
		$this->display('set');
	}
	public function edit(){
		if(IS_POST){
			$this->all_save('Lapiao','/index?token='.$this->token);exit;
		}
		$where['token']=$this->token;
		$where['id']=$this->_get('id','intval');
		$info=$this->shake_model->where($where)->find();
		$this->assign('info',$info);
		$this->display('set');
	}
	public function listall(){
		$act = $this->_get('act');
		if('info'==$act){
			$this->assign('token',$this->token);
			$this->assign('id',$this->_get('id','intval'));
			$this->display('updateinfo');	
		} else if('update'==$act){
			$id = $this->_get('id');
			$sl = $this->_get('sl');
			M('lapiao_record')->where(array('id'=>$id))->save(array('sl'=>$sl));
			exit(json_encode(array('error_code' => false, 'msg' => '操作成功')));
		}else{
			$count=M('lapiao_record')->where(array('token'=>$this->token,'tid'=>$this->_get('id')))->count();
			$page=new Page($count,20);
			$info=M('lapiao_record')->where(array('token'=>$this->token,'tid'=>$this->_get('id')))->order('sl desc')->limit($page->firstRow.','.$page->listRows)->select();
			$this->assign('page',$page->show());
			$this->assign('info',$info);
			$this->display();			
			
		}
	
	}
	public function del(){
		
	}

}


?>
