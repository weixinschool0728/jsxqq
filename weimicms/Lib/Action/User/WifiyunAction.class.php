<?php
class wifiyunAction extends UserAction{
	public $alipay_config_db;
	public function _initialize() {
		parent::_initialize();
		$this->wifiyun=M('Wifiyun');
		$this->router=M('Wifiyunrouter');
		if (!$this->token){
			exit();
		}
		//是否是添加设备
		$this->isRouter=0;
		if (isset($_GET['isRouter'])&&intval($_GET['isRouter'])){
			$this->isRouter=1;
		}
		$this->assign('isRouter',$this->isRouter);
	}
	public function index(){
		$config = $this->wifiyun->where(array('token'=>$this->token))->find();
		if(IS_POST){
			$row['authcode']=$this->_post('authcode');
			$row['wxcode']=$this->_post('wxcode');
			$row['wxname']=$this->_post('wxname');
			$row['shopname']=$this->_post('shopname');
			$row['url']=$this->_post('url');
			$row['token']=$this->_post('token');
			$row['open']=$this->_post('open');
			$row['picurl']=$this->_post('picurl');
			//$row['address']=$this->_post('address');
		
			if ($config){
				$where=array('token'=>$this->token);
				$this->wifiyun->where($where)->save($row);
			}else {
				$this->wifiyun->add($row);
			}
			//postpost 数据到路由器
			$url="http://vdian.winmo.com.cn/getwifidata.php";
			$ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	        curl_setopt($ch, CURLOPT_POST, 1);//post方式提交 
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($row));//要提交的信息 
            $respose=curl_exec($ch); //执行cURL
			if($respose=="success")
			 $this->success('设置成功',U('Wifiyun/index',$where));
			else
			  {header("Content-type:text/html;charset=utf-8");
			  
			    $this->error($respose,U('Wifiyun/index',array('token'=>session('token'))));

			  }
		}else{
			$this->assign('config',$config);
			$this->display();
		}
	}
	public function router (){
	$router = $this->router->where(array('token'=>$this->token))->select();
	$this->assign('router',$router);
	$this->display();
	
	}
	public function addrouter(){
	        $config = $this->wifiyun->where(array('token'=>$this->token))->find();
			$router = $this->router->where(array('token'=>$this->token))->find();
		if(IS_POST){
		    $row['authcode']=$config['authcode'];
			$row['mac']=$this->_post('mac');
			$row['address']=$this->_post('address');
			$addrow['token']=$this->token;
			$addrow['mac']=$this->_post('mac');
			$addrow['address']=$this->_post('address');
           
			//postpost 数据到路由器
			$url="http://vdian.winmo.com.cn/getrouterdata.php";
			$ch = curl_init(); 
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HEADER, 0); 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	        curl_setopt($ch, CURLOPT_POST, 1);//post方式提交 
	        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($row));//要提交的信息 
            $respose=curl_exec($ch); //执行cURL
			if($respose=="success")
			{
			 $this->router->add($addrow);
			 $this->success('设置成功',U('Wifiyun/router',array('token'=>$token,'isRouter'=>1)));
			 
			 }
			else
			  {
			    $this->error($respose,U('Wifiyun/router',array('token'=>$token,'isRouter'=>1)));
			  }
		}else{
			$this->assign('config',$config);
			$this->display();
		}
	}
	
	public function del(){
		$where['id']=$this->_get('id','intval');
		$where['token']=session('token');
		if(M('wifiyunrouter')->where($where)->delete()){
           	$this->success('操作成功',U('Wifiyun/router',array('token'=>$token,'isRouter'=>1)));
		}else{
			$this->error('操作失败',U('Wifiyun/router',array('token'=>$token,'isRouter'=>1)));
		}
	}
}


?>