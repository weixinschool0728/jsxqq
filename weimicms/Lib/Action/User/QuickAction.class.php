<?php
class QuickAction extends UserAction{
	public function index(){
		$db=M('Wxuser');
		$dbs=M('Home');
		$where['token']=$this->token;
		if(IS_POST){
			$data1['qr']=$this->_POST('qr');
			$data1['hurl']=htmlspecialchars_decode($this->_POST('hurl'));
			$save1 = $db->where($where)->save($data1);
			$dbs->where($where)->save(array('gzhurl'=>$data1['hurl']));
			if($save1){
				$this->success('保存成功');
			}else{
				$this->error('保存失败');
			}
		}else{
			$list=$db->where($where)->find();
			if($list['hurl'] == ''){
				$hurl=$dbs->where($where)->getField('gzhurl');
				if($hurl != ''){
					$data2['hurl']=$hurl;
					$save2=$db->where($where)->save($data2);
				}
			}
			$this->assign('list',$list);
			$this->assign('hurl',$hurl);
			$this->display();
		}
	}

	public function set(){
		$this->display();
	}
}
?>