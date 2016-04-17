<?php
class PhotographyAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$function=M('Function')->where(array('funname'=>'Photography'))->find();
		$this->canUseFunction('Photography');
	}
	//影楼配置
	public function index(){
		$Photography=M('Photography');
		$PhotographyInfo=M('Photography_info');
		$where['token']=session('token');
		$count=$Photography->where($where)->count();
		$page=new Page($count,25);
		$list=$Photography->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		foreach($list as &$item){
			$num=$PhotographyInfo->where(array(
				'fid' => $item['id']
			))->count();
			if($num){
				$item['phonenum']=$num;	
			}
		}
		$this->assign('page',$page->show());
		$this->assign('Photography',$list);
		$this->display();
	}
	//添加微摄影数据
	public function add(){
		if(IS_POST){
			$_POST['time']=strtotime($this->_post('time'));
			$this->all_insert('Photography','/index');
		}else{
			$photo=M('Photo')->where(array('token'=>session('token')))->select();
			$this->assign('photo',$photo);
			$this->display();
		}
	}
	//编辑微摄影数据
	public function edit(){
		$Photography=M('Photography')->where(array('token'=>session('token'),'id'=>$this->_get('id','intval')))->find();
		if(IS_POST){
			$_POST['time']=strtotime($this->_post('time'));
			$_POST['id']=$Photography['id'];
			//$keyword_model=M('Keyword');
			//$keyword_model->where(array('token'=>$this->token,'pid'=>$Wedding['id'],'module'=>'Photography'))->save(array('keyword'=>$_POST['keyword']));
			$this->all_save('Photography','/index');	
		}else{
			$photo=M('Photo')->where(array('token'=>session('token')))->select();
			$this->assign('photo',$photo);
			$this->assign('Photography',$Photography);
			$this->display('add');
		}
	}
	//删除微摄影数据
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if(D('Photography')->where($where)->delete()){
			//删除子数据
			D('Photography_info')->where(array('fid'=>$this->_get('id','intval')))->delete();
			D('Photography_ip')->where(array('fid'=>$this->_get('id','intval')))->delete();
			$keyword_model=M('Keyword');
            $keyword_model->where(array('token'=>$this->token,'pid'=>$this->_get('id','intval'),'module'=>'Photography'))->delete();
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
	//添加收集信息
	public function info(){
		$data=D('Photography_info');
		$where['fid']=$this->_get('id','intval');
		$count=$data->where($where)->count();
		$page=new Page($count,25);
		$info=$data->where($where)->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$page->show());
		$this->assign('Photography',$info);
		$this->display();
	}
	//删除手机信息
	public function infodel(){
		$where['id']=$this->_get('id','intval');
		$info=M('Photography_info')->field('fid')->where($where)->find();
		$back=M('Photography')->where(array('token'=>session('token'),'fid'=>$info['fid']))->find();
		if($back==false){$this->error('非法操作');exit;}
		if(D('Photography_info')->where($where)->delete()){
			$this->success('操作成功',U(MODULE_NAME.'/index'));
		}else{
			$this->error('操作失败',U(MODULE_NAME.'/index'));
		}
	}
}



?>