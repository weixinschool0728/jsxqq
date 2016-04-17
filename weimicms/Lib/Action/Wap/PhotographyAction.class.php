<?php
class PhotographyAction extends WapAction{
	public function index(){
		if(isset($_GET['id'])){
			$data['id']=$this->_get('id','intval');
			$data['token']=$this->_get('token');
		}else{
			exit('非法请求');
		}
		$Photography=D('Photography');
		if($Photography->where($data)->count()==0){
			exit('暂无该微摄影信息');	
		}
		$PhotographyData=$Photography->where($data)->find();
		$photo=M('Photo_list')->field('id,picurl,info')->where(array('pid'=>$PhotographyData['pid']))->order('sort desc')->select();
		$ip=D('Photography_ip')->where(array('fid'=>$this->_get('id','intval'),'ip'=>get_client_ip()))->count();
		$company=D('Company')->where(array('token'=>$this->_get('token')))->field('mp','name')->find();
		//检查copyright信息
		$isRight=false;
		$copyRight='';
		$prefix=C('DB_PREFIX');
		$isRight=D('wxuser')->join(array($prefix.'users on '.$prefix.'users.id='.$prefix.'wxuser.uid'
			,$prefix.'user_group on '.$prefix.'user_group.id='.$prefix.'users.gid'))->field($prefix.'user_group.iscopyright')
			->where(array($prefix.'wxuser.token'=>$this->_get('token')))->find();
		if($isRight['iscopyright']==0){
			$copyRight=$company['name'];
		}else{
			$copyRight=C('CopyRight');	
		}
		$this->assign('PhotographyData',$PhotographyData);
		$this->assign('photo',$photo);
		$this->assign('ip',$ip);
		$this->assign('company',$company);
		$this->assign('copyright',$copyRight);
		$this->display();
	}
	
	public function info(){
		if(IS_POST){
			$data['fid']=$this->_post('fid');
			$data['phone']=$this->_post('phone');
			$PhotographyInfo=D('Photography_info');
			if($PhotographyInfo->where(array('fid'=>$this->_post('fid'),'phone'=>$this->_post('phone')))->find()){
				echo '您的手机号已登记过!';	
			}else{
				$data['create_time']=time();
				if($PhotographyInfo->add($data)){//添加手机信息
					$replay=D('Photography')->where(array('id'=>$this->_post('fid'),'token'=>$this->token))->field('success')->find();
					echo ($replay&&trim($replay['success'])!='')?$replay['success']:'登记成功';
				}else{
					echo '登记信息失败，换个姿势，再来一次~';
				}
			}
		}else{
			$this->error('非法操作');
		}
	}

	public function vote(){
		if(IS_POST){
			$data['fid']=$this->_post('fid');
			$data['ip']=get_client_ip();
			$type=$this->_post('type');//投票项
			if($type=='') exit('请选择祝福项');
			$PhotographyIp=D('Photography_ip');
			if($PhotographyIp->where($data)->count()==0){//该ip未投票过
				$PhotographyIp->startTrans();
				if($PhotographyIp->add($data)){//添加该ip信息
					$vote[$type]=array('exp',$type.'+1');
					$vote['nownum']=array('exp','nownum+1');
					if(D('Photography')->where(array('id'=>$this->_post('fid'),'token'=>$this->_post('token')))->save($vote)){
						echo '祝福成功,有你的帮助，他们离大奖又更进一步了！快分享给你的朋友一起来帮他祝福吧！';	
						$PhotographyIp->commit();
					}else{
						echo '很遗憾您的祝福失败了，刷新一下再试试？';
					}
				}else{
					echo '投票失败，换个姿势再试试？';	
				}
				$PhotographyIp->rollback();
			}else{//该ip已投票
				echo '祝福虽好，可不要重复祝福哦';
			}
		}else{
			$this->error('非法操作');
		}
	}
}
?>

