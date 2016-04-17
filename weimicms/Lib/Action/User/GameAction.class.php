<?php
class GameAction extends UserAction{
	public $config;
	public $cats;
	public $game;
	//游戏分享回调选项
	// public $share_options =array(
	// 	0=>'再玩一次',
	// 	1=>'红包',
	// 	2=>'卡券',
	// 	3=>'积分'
	// );
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Gamecenter');
		$this->game=new game();
		$this->cats=$this->game->gameCats();
		$this->assign('cats',$this->cats);
	}
	public function config(){
		$config=M('Game_config')->where(array('token'=>$this->token))->find();
		if (IS_POST){
			$data=array(
			'token'=>$this->token,
			'wxid'=>$this->_post('wxid'),
			'wxname'=>$this->_post('wxname'),
			'wxlogo'=>$this->_post('wxlogo'),
			'link'=>$this->_post('link'),
			'attentionText'=>$this->_post('attentionText'),
			);
			if (!$config){
				D('Game_config')->add($data);
			}else {
				D('Game_config')->where(array('id'=>$config['id']))->save($data);
			}
			$data['link']=$this->convertLink($data['link']);
			$rt=$this->game->config($this->token,$data['wxname'],$data['wxid'],$data['wxlogo'],$data['link'],$data['attentionText']);
			D('Game_config')->where(array('token'=>$this->token))->save(array('uid'=>$rt['id'],'key'=>$rt['key']));
			$this->success('设置成功');
		}else {
			if (!$config){
				$config=$this->wxuser;
				$config['wxlogo']=$config['headerpic'];
			}
			$this->assign('info',$config);
			$this->display();
		}
	}
	public function index(){
		$this->_toConfig();
		//
		$where 	= array('token'=>$this->token);

		$count		= M('Games')->where($where)->count();
		$Page       = new Page($count,15);
		$list 		= M('Games')->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$thisUser=M('Game_config')->where(array('token'=>$this->token))->find();
		$this->assign('thisUser',$thisUser);
		$this->assign('count',$count);
		$this->assign('page',$Page->show());
		$this->assign('list',$list);
		$this->display();
	}
	public function delGame(){
		$config=$this->_toConfig();
		$id=isset($_GET['id'])?intval($_GET['id']):0;
		$thisItem=M('games')->where(array('id'=>$id,'token'=>$this->token))->find();
		$data['uid']=$config['uid'];
		$data['gameid']=$thisItem['gameid'];
		$data['ugameid']=$id;
		$rt=$this->game->deleteGame($data);
		//$thisGame=$this->game->getGame(intval($gameid));
		//$this->game->gameSelfSet($config['uid'],$thisGame['id'],$id,'game',$config['key'],'');
		M('games')->where(array('id'=>$id,'token'=>$this->token))->delete();
		$this->success('删除成功',U('Game/index'));
	}
	public function gameSet(){
		$id=isset($_GET['id'])?intval($_GET['id']):0;
		$this->assign('id',$id);
		if ($id){
			$thisItem=M('games')->where(array('id'=>$id,'token'=>$this->token))->find();
			
			$gameid=intval($thisItem['gameid']);
		}else {
			$gameid=intval($_GET['gameid']);
		}
		$config=$this->_toConfig();
		$thisGame=$this->game->getGame(intval($gameid));
		$gameSet = $this->game->gameSet($config['uid'],$thisGame['id'],$id,$config['key']);
		if($gameSet){
			$thisItem['rule'] = htmlspecialchars_decode(base64_decode($gameSet['rule']));
			$thisItem['awards'] = htmlspecialchars_decode(base64_decode($gameSet['awards']));
			$thisItem['attention_url'] = $gameSet['attention_url'];
			$thisItem['is_phone'] = $gameSet['is_phone'];
			$thisItem['is_attention'] = $gameSet['is_attention'];
			$thisItem['start_time'] = $gameSet['start_time'];
			$thisItem['end_time'] = $gameSet['end_time'];
		}
		
		$selfs=$this->game->gameSelfs($thisGame['id'],$config['uid'],$id,$config['key']);
		if (IS_POST){
			$wxtype = M('wxuser')->field("`winxintype`,`qr`")->where(array('token'=>$this->token))->find();
			if ($wxtype['winxintype'] == 3) {
				$gameqr = $this->getQrcode($this->_post('keyword'));
			}else{
				if (empty($wxtype['qr'])) {
					$this->error('请在编辑公众号里，上传您的二维码',U('Index/index',array('id'=>session('wxid'))));
				}else{
					$gameqr = $wxtype['qr'];
				}
			}
			
			// if( $this->_post('share_callback') == 2 ){
			// 	$share_value = $_POST['share_value'].'#'.$_POST['share_title'];
			// }elseif($this->_post('share_callback') == 1){
			// 	$share_value = $_POST['share_value'].'#'.$_POST['share_min'];
			// }else{
			// 	$share_value = $_POST['share_value'];
			// }

			$data=array(
				'token'=>$this->token,
				'title'=>$this->_post('title'),
				'intro'=>$this->_post('intro'),
				'keyword'=>$this->_post('keyword'),
				'picurl'=>$this->_post('picurl'),
				'limit_num'=>$this->_post('limit_num'),
				'start_time'=>strtotime($this->_post('start_time')),
				'end_time'=>strtotime($this->_post('end_time')),
				'time'=>time(),
				'gameid'=>$thisGame['id'],
				'share_callback'=>$this->_post('share_callback'),
				'share_value'=>$share_value
			);
			$selfValues=array();
			$jsonStr='{';
			if ($selfs){
				$comma='';
				foreach ($selfs as $s){
					$selfValues['self_'.$s['id']]=$this->_post('self_'.$s['id']);
					$jsonStr.=$comma.'"self_'.$s['id'].'":"'.$selfValues['self_'.$s['id']].'"';
					$comma=',';
				}
			}
			$jsonStr.='}';
			$data['selfinfo']=$jsonStr;
			if (!isset($_POST['id'])){
				$usergameid=M('Games')->add($data);
			}else {
				$usergameid=intval($_POST['id']);
				M('Games')->where(array('id'=>$usergameid))->save($data);
			}
			$gameSet['title'] = $_POST['title'];
			$gameSet['intro'] = $_POST['intro'];
			$gameSet['picurl'] = $_POST['picurl'];
			$gameSet['rule'] = $_POST['gameSet_rule'];
			$gameSet['awards'] = $_POST['gameSet_awards'];
			$gameSet['is_phone'] = $this->_post('is_phone');
			$gameSet['is_attention'] = $this->_post('is_attention');
			$gameSet['limit_num'] = $_POST['limit_num'];
			$gameSet['start_time'] = strtotime($_POST['start_time']);
			$gameSet['end_time'] = strtotime($_POST['end_time']);
			$gameSet['gameqr'] = $gameqr;
			$gameSet['keyword'] = $this->_post('keyword');
			$gameSet['share_callback'] = $this->_post('share_callback');
			$gameSet['share_value'] = $share_value;
			$home_set = M('Wxuser')->field('id,hurl')->where(array('token'=>$this->token))->find();
			if($gameSet['is_attention'] == 1 && (!isset($home_set['hurl']) || $home_set['hurl'] == '')){
				$this->error('需要关注，请先设置快捷关注配置中的一键关注链接',U('Game/index'));
			}
			$gameSet['attention_url'] = $home_set['hurl'];
			
			$this->game->gameSet($config['uid'],$thisGame['id'],$usergameid,$config['key'],$gameSet,'game');
			$this->handleKeyword($usergameid,'Game',$data['keyword'],$precisions=0,$delete=0);
			$this->game->gameSelfSet($config['uid'],$thisGame['id'],$usergameid,'game',$config['key'],$selfValues);

			$this->success('设置成功',U('Game/index'));
			//
		}else {
			$url = $_SERVER['SERVER_NAME'];
			$this->assign('url',$url);
			$this->assign('thisGame',$thisGame);
			if ($this->_get('gameid')) {
				$gameid = intval($this->_get('gameid'));
			}else{
				$gameid = intval($thisGame['id']);
			}

			$this->assign('gameid',$gameid);

			if (!$id){
				$thisItem=array();
				$thisItem['title']=$thisGame['title'];
				$thisItem['intro']=$thisGame['intro'];
				$thisItem['keyword']=$thisGame['title'];
				$thisItem['rule']=$thisGame['rule'];
			}
			
			if ($id){
				if ($selfs){
					$selfValues=json_decode($thisItem['selfinfo'],1);
					$i=0;
					foreach ($selfs as $s){
						$selfs[$i]['value']=$selfValues['self_'.$s['id']];
						if ($selfs[$i]['value']){
							$selfs[$i]['defaultvalue']=$selfs[$i]['value'];
						}
						$i++;
					}
				}
			}
			$thisItem['rule'] = stripslashes($thisItem['rule']);
			$thisItem['awards'] = stripslashes($thisItem['awards']);
			// $coupon = M('Member_card_coupon')->where(array('is_delete'=>0))->select();
			// $this->assign('coupon', $coupon);
			// $this->assign('share_options', $this->share_options);
			// if(strpos($thisItem['share_value'], '#')){
			// 	$unser = explode('#',$thisItem['share_value']);
			// 	$thisItem['share_value'] = trim($unser[0]);
			// 	$thisItem['share_title'] = trim($unser[1]);
			// 	$thisItem['share_min'] = trim($unser[1]);
			// }
			$this->assign('selfs',$selfs);
			$this->assign('info',$thisItem);

			$this->display();
		}
	}
	public function gameDelete(){
		
	}
	public function gameResults(){
		
	}
	public function gameLibrary(){
		$catid=isset($_GET['catid'])?intval($_GET['catid']):1;
		$games=$this->game->gameList($catid);
		
		$this->assign('games',$games);
		$this->assign('catid',$catid);
		$this->display();
	}
	function _toConfig(){
		$config=M('Game_config')->where(array('token'=>$this->token))->find();
		if (!$config){
			$this->success('请先配置游戏相关信息',U('Game/config'));
			exit();
		}else {
			return $config;
		}
	}
	
	
	function record(){
		$where 	= array('token'=>$this->token,'gameid'=>$this->_get('id','intval'));
		
		$count		= M('Game_records')->where($where)->count();
		$Page       = new Page($count,15);
		$list 	= M('Game_records')->where($where)->order('score desc,time desc')->limit($Page->firstRow.','.$Page->listRows)->select();		
		$wecha_id = array();
		foreach ($list as $key=>$val) {
			if(!in_array($val['wecha_id'],$wecha_id)){
				$wecha_id[] = $val['wecha_id'];
			}
		}
		$data['open_id'] = implode(',',$wecha_id);
		$fans_info = $this->valtokey($this->game->fansInfo($data),'openid');
		foreach ($list as $key=>$val) {
			$list[$key]['username'] = $fans_info[$val['wecha_id']]['wechaname'];
			$list[$key]['phone'] = $fans_info[$val['wecha_id']]['phone'];
		}
		$this->assign('list',$list);
		$this->assign('fans_info',$fans_info);
		$this->assign('page',$Page->show());
		$this->display();
	}
	
	function record_del(){
		$where 	= array('token'=>$this->token,'id'=>$this->_get('id','intval'));
		$record = M('Game_records')->where($where)->select();
		$data['wecha_id'] =  $record['0']['wecha_id'];
		$data['uid'] =  $record['0']['uid'];
		$data['gid'] =  $record['0']['gameid'];
		$this->game->scoredel($data);
		if(M('Game_records')->where($where)->delete()){
			$this->success('删除成功',U('Game/record',array('token'=>$this->token,'id'=>$this->_get('rid','intval'))));
		}	

	}
	function valtokey($data,$field){
		$return = array();
		foreach ($data as $key=>$val) {
			$return[$val[$field]] = $val;
		}
		return $return;
	}

	function gamearr(){
		S('game_'.$this->_get('token').'_'.$this->_get('id'),$_POST);
	}

	public function getQrcode($kword){
		$recdb=M('Recognition');
		$recognis=$recdb->where(array('keyword'=>$kword,'token'=>$this->token))->find();
		$this->thisWxUser=M('Wxuser')->where(array('token'=>$this->token))->find();
		$apiOauth 	= new apiOauth();
		$this->access_token 	= $apiOauth->update_authorizer_access_token($this->thisWxUser['appid']);
		if($recognis != ''){
			if($recognis['code_url'] == ''){
				$qrcode_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
				$data['action_name']='QR_LIMIT_SCENE';
				$data['action_info']['scene']['scene_id']=$recognis['id'];
				$post=$this->api_notice_increment($qrcode_url,json_encode($data));
				$recdb->where(array_merge(array('id'=>$recognis['id'])))->save(array('code_url'=>$post));	
				$wxqr= ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$recognis['code_url']);		
			}else{
				$wxqr= ('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$recognis['code_url']);
			}					
		}else{
			$dataz['keyword']=$kword;
			$dataz['title']=$kword;
			$dataz['token']=$this->token;
			$xid=$recdb->add($dataz);
			$qrcode_url='https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token='.$this->access_token;
			$data['action_name']='QR_LIMIT_SCENE';
			$data['action_info']['scene']['scene_id']=$xid;
			$post=$this->api_notice_increment($qrcode_url,json_encode($data));
			$recdb->where(array_merge(array('id'=>$xid)))->save(array('code_url'=>$post));	
			$wxqr=('https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket='.$post);			
		}
		return $wxqr;
	}

	function api_notice_increment($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			$this->error('发生错误：curl error'.$errorno);
			
		}else{

			$js=json_decode($tmpInfo,true);
			
			if (isset($js['ticket'])){
				return $js['ticket'];
			}else {
				$this->error('发生错误：错误代码'.$js['errcode'].',微信返回错误信息：'.$js['errmsg']);
			}
		}
	}
}

?>