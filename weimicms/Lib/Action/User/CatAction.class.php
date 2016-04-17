<?php
class CatAction extends UserAction{
		public $token;	
	public function _initialize() {
		parent::_initialize();		
		$this->token=session('token');
		$this->assign('token',$this->token);
	}
	public function index(){
		 $this->reply_info_model=M('cat_set');
		 $thisInfo = $this->reply_info_model->where(array('token'=>$this->token))->find();		
		if ($thisInfo&&$thisInfo['token']!=$this->token){
			exit();
		}       
		if(IS_POST){			
			$row['url']=strip_tags(htmlspecialchars_decode($_POST['url']));
			$row['title']=$this->_post('title');
			$row['text']=$this->_post('text');
			$row['picurl']=$this->_post('picurl');
			$row['token']=$this->_post('token');										
		  if ($thisInfo){				
				$where=array('token'=>$this->token);
				$this->reply_info_model->where($where)->save($row);
				$keyword_model=M('Keyword');
				$this->success('修改成功',U('Cat/index',$where));						
			}else {
				$where=array('token'=>$this->token);
				$this->reply_info_model->add($row);
				$this->success('设置成功',U('Cat/index',$where));
			}
		}else{
			$this->assign('set',$thisInfo);
			
			$this->display();
		}
	}
}
?>