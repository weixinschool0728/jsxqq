<?php
class LapiaoAction extends WapAction{
	public $token;
	public function __construct(){
		parent::_initialize();
		$this->token=$this->_get('token');
		if(strlen($this->token)>32){
		die($this->token);
		}		
	}
	public function index(){
		if(!strpos($agent,"Mobile")) {
			//echo '此功能只能在微信浏览器中使用';exit;
		}
		$wxid = $this->wecha_id;
		$lpid = $this->_get('id');
		$wlp = M('lapiao')->where(array('token'=>$this->token,'id'=>$lpid))->find();
		
		$wlp['moreurl']=str_replace(array('{wechat_id}', '{siteUrl}','amp;'), array($wxid, C('site_url'),''), $wlp['moreurl']);
		if($wlp['moreurl']==''){
			$wlp['moreurl'] = "javascript:showshare();";
		}
		$time = date('Y-m-d H:i:s',time());
		$this->assign('time',$time);
		$this->assign('token',$this->token);
		$this->assign('wxid',$wxid);
		$this->assign('wlp',$wlp);
		$this->assign('status',$wlp['kssj']);
		$this->assign('tgsysj',$wlp['jssj']);	
		//$tgr = new Model('lapiao_record');
		$sn = '';
		// 计算排名
		$pm=0;
		$where['tid'] =$lpid;
		$list = M('lapiao_record')->where($where)->order('sl desc')->limit('100')->select();
		$find = 0;
		foreach ($list as $li){
			$pm++;
			if($li['wxid'] == $wxid)
			{
				$find = 1;
				break;
			}
		}
		if($find == 0){
			$pm='100之后';
		}
		$this->assign('pm',$pm);
		$this->assign('list',$list);		
		
		$sl=0;
		$sn='';
		$lpres=M('lapiao_record')->where(array('token'=>$this->token,'tid'=>$lpid,'wxid'=>$wxid,'isused'=>'0'))->find();
		if($lpres){
			$sn = $lpres['sn'];
			$sl = $lpres['sl'];
		}
		$this->assign('sn',$sn);
		$this->assign('sl',$sl);
		$this->display();
	}
	public function buy(){
		$wxid = $this->_get('wecha_id');
		$lpid = $this->_get('id');
		$wlp = M('lapiao')->where(array('token'=>$this->token,'id'=>$lpid))->find();
		
		$lpres=M('lapiao_record')->where(array('token'=>$this->token,'tid'=>$lpid,'wxid'=>$wxid,'isused'=>'0'))->find();
		if($lpres){
			//$sn = $lpres['sn'];
			die('你已经领取过了');
			//return $this->index();
		}
			if($wxid==''){
				die( "<html><head>
						<meta name='viewport' content='width=device-width' />
						<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
						</head>
						<body>
						<div id='content' width='100%'> 请点击右上角->查看公众账号，关注".$this->wxuser['weixin']."后回复:".$wlp['keyword']."</div></body></html>");
			}
		//查找用户信息
		$op = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->_get('wecha_id')))->find();
		
		$this->assign('token',$this->token);
		$this->assign('wxid',$wxid);
		$this->assign('wlp',$wlp);
		$this->assign('op',$op);
		$this->display();
	}
	
	public function tobuy(){
		$wxid = $this->wecha_id;
		$lpid = $this->_get('id');
		$wlp = M('lapiao')->where(array('token'=>$this->token,'id'=>$lpid))->find();
		$lpres=M('lapiao_record')->where(array('tid'=>$lpid,'wxid'=>$wxid,'isused'=>'0'))->find();
		if($lpres){
				exit(json_encode(array('error_code' => true, 'msg' => '您已经领取过了，请勿重复领取')));

		}
		if(!$tgr){
			if($wxid==''){
				exit(json_encode(array('error_code' => true, 'msg' => '请关注公众号'.$this->wxuser['weixin'].'后回复:'.$wlp['keyword'])));
			}
			$data['wxid'] = $wxid;
			$data['token'] = $this->token;
			$data['tid'] = $lpid;
			$data['ctime'] = time();
			$data['un'] = $this->_get('un');
			$data['tel'] = $this->_get('tel');
			$data['sl'] = $this->_get('num');
			$data['sn'] = time();
			$id = M('lapiao_record')->add($data);
			$lpres=M('lapiao_record')->where(array('tid'=>$lpid,'wxid'=>$wxid,'isused'=>'0'))->find();
			$no = $lpres['id'];
			$data['id'] = $no;
			$data['sn'] = $no;
			M('lapiao_record')->save($data);
			
			$wlp['ctrs'] = intval($data['ctrs'])+1;
			$wlp['tgsl'] = intval($data['tgsl'])+intval($data['sl']);
			M('lapiao_record')->save($wlp);
	
			//查找用户信息
			$op = M('userinfo')->where(array('token'=>$this->token,'wecha_id'=>$this->_get('wecha_id')))->find();
			if(!$op) {
				$user['token'] = $this->token;
				$user['wecha_id'] = $wxid;
				$user['wechaname'] = $data['un'];
				$user['truename'] = $data['un'];
				$user['tel'] = $data['tel'];
				M('userinfo')->add($user);
			}else if($op['un'] != $data['un'] || $op['tel'] != $data['tel']){
				$op['un'] = $data['un'];
				$op['tel'] = $data['tel'];
				M('userinfo')->save($op);
			}
			exit(json_encode(array('error_code' => false, 'msg' => '领取成功')));
		}
		exit(json_encode(array('error_code' => true, 'msg' => '已经领取过')));
	}
}
?>