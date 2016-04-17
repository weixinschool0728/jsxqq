<?php
class VdianAction extends UserAction{
	public $token;
	public $tel;
	public $pwd;
	public function _before() {
		parent::_initialize();
		//微店信息
		$where = array('token' => $this->token);
		$this->thisVdUser = M('Diymen_set')->where($where)->find();
		$this->thisWxUser = M('Wxuser')->where($where)->find();
		if (!$this->thisVdUser['telphone'] || !$this->thisVdUser['password']) {
			$this->error('请先设置微店手机号和密码再使用本功能，谢谢', '?g=User&m=Index&a=edit&id=' . $this->thisWxUser['id']);
		}
		else{//获取通行证
		 $this->tel=$this->thisVdUser['telphone'];
		 $this->pwd=$this->thisVdUser['password'];
		}
	}
	public function index(){		
		
		header("Location: http://vdian.winmo.com.cn/upppp/Weidian_function.html"); 
        exit;
		 
	}
	public function help(){		
		
		header("Location: http://vdian.winmo.com.cn/upppp/help_detail/d=0.html"); 
        exit;
		 
	}
	public function item(){	
	 $this->_before();
	 $url="http://vdian.winmo.com.cn/upppp/item.php?tel=$this->tel&pwd=$this->pwd";
	 header("Location:$url"); 
     
		 
	}
	public function order(){	
	 $this->_before();
	 $url="http://vdian.winmo.com.cn/upppp/order.php?tel=$this->tel&pwd=$this->pwd";
	 header("Location:$url"); 
     
		 
	}


}
?>