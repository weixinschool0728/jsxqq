<?php
class StorenewAction extends WapAction{
	//public $token;
	//public $wecha_id = '';
	public $product_model;
	public $product_cat_model;
	public $session_cart_name;
	public $_cid = 0;
	public $_set;
	public $_isgroup = 0;
	
	public $mainCompany = null;
	
	public $_twid = '';
	
	public $mytwid = '';
	
	private $randstr = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
	
	public function _initialize() 
	{
		parent::_initialize();
		$tpl = $this->wxuser;
		$tpl['color_id'] = intval($tpl['color_id']);
		$this->tpl = $tpl;
		$agent = $_SERVER['HTTP_USER_AGENT']; 
		if (!strpos($agent, "MicroMessenger")) {
			//	echo '此功能只能在微信浏览器中使用';exit;
		}
		
		//查询用户是否在黑名单内
		$lockuser = M("New_product_lockuser")->where(array("token" => $this->token,'wecha_id'=>$this->wecha_id))->find();
		if ($lockuser) {
			echo '微信帐号系统问题，暂时无法访问，请稍后重试！';exit;
			//$this->error("微信帐号系统问题，暂时无法访问，请稍后重试");
		}
		
		//一键关注公众号的URL
		$gzhurl = M("Home")->where(array("token" => $this->token))->getField("gzhurl");
		$this->assign('gzhurl', $gzhurl);
		//dump($gzhurl);
		//die;
		
		if (!$this->isSubscribe()) {
			$is_sub = 2;
			$this->assign('is_sub', $is_sub);
		}
		
		$wxuserinfo = M("Home")->where(array('token' => $this->token))->field('gzhurl')->find();
		$this->assign('wxuserinfo', $wxuserinfo);
		
		
		$this->_cid = session("session_company_{$this->token}");
		
		$this->session_cart_name = "session_cart_products_{$this->token}_{$this->_cid}";//'session_cart_products_' . $this->token;
		$this->product_model = M('New_product');
		$this->product_cat_model = M('New_product_cat');
		$this->mainCompany = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
		if (C('zhongshuai')) {
			$cid = $this->mainCompany['id'];
			$set = M("New_product_setting")->where(array('token' => $this->token, 'cid' => $this->mainCompany['id']))->find();
			$this->_isgroup = isset($set['isgroup']) ? intval($set['isgroup']) : 0;
		}
		$twitter_set = null;
		if ($this->_cid) {
			$this->_set = M("New_product_setting")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
			$this->assign('productSet', $this->_set);
			$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
			$cats = $this->product_cat_model->where(array('token' => $this->token, 'cid' => $cid, 'parentid' => 0))->order("sort ASC, id DESC")->select();
			$this->assign('cats', $cats);
			$twitter_set = M("New_twitter_set")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		}
		
		
		$this->_twid = isset($_REQUEST['twid']) ? $_REQUEST['twid'] : '';//来自推广人的推广标示
		$this->mytwid = session('twid');//我自己的推广标示
		
		$login = session("login");
		if ($twitter_set && empty($this->wecha_id) && empty($this->mytwid) && empty($login) && !in_array(ACTION_NAME, array('register', 'login'))) {
			$callbackurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			session('callbackurl', $callbackurl);
			//点击链接时的推广记录
			session("login", 1);
			$this->redirect(U('Storenew/login', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid, 'rget' => 1)));
		}

		if (empty($this->wecha_id) && $this->mytwid) {
			$fansInfo = M('Userinfo')->where(array('token' => $this->token, 'twid' => $this->mytwid))->find();
			$this->fans = $fansInfo;
			$this->assign('fans', $fansInfo);
		}
		
		if($this->_twid){
			$fromtwid = isset($_REQUEST['twid']) ? $_REQUEST['twid'] : '';//来自推广人的推广标示
			if($fromtwid){
				$fromtwidinfo = M('Userinfo')->where(array('token'=>$this->token,'twid'=>$fromtwid))->find();
				$addtwid = $fromtwidinfo['fromtwid'];
			}
		}
		
		if ($this->fans && empty($this->fans['twid']) && empty($this->fans['fromtwid']) && empty($this->fans['addtwid'])) {
			$twid = $this->randstr{rand(0, 51)} . $this->randstr{rand(0, 51)} . $this->randstr{rand(0, 51)} . $this->fans['id'];
			if (D('Userinfo')->where(array('id' => $this->fans['id']))->save(array('twid' => $twid, 'fromtwid' => $fromtwid, 'addtwid' => $addtwid))) {
				S('fans_'.$this->token.'_'.$this->wecha_id,null);
			}
// 			D('Userinfo')->where(array('id' => $this->fans['id']))->save(array('twid' => $twid));
			$this->fans['twid'] = $twid;
			$this->assign('fans', $this->fans);
		} elseif (empty($this->fans) && $this->wecha_id) { //TODO 没有用户信息时候的处理
			
		}
		$this->mytwid = $this->fans['twid'];
		
		$this->_cid || $this->_cid = $this->mainCompany['id'];
		$this->wecha_id || $this->wecha_id = $this->mytwid;
		$wei_user = M("Wechat_group_list")->where(array('openid' => $this->fans['wecha_id']))->find();
		$weiuser = $wei_user['subscribe_time'];
		$wei_user2 = M("Userinfo")->where(array('wecha_id' => $this->fans['wecha_id']))->find();
		$createtime = $wei_user2['create_time']?$wei_user2['create_time']:$weiuser;
		$this->assign('staticFilePath', str_replace('./', '/', '/tpl/static/Storenew/'));
		
		$istwittersave = session('twitter_save');
		if (empty($istwittersave) && $this->_cid) {
			//$this->savelog(1, $this->_twid, $this->token, $this->_cid);
			session('twitter_save', 1);
		}
		if ($this->mytwid){
			$userinfo = M('Userinfo')->where(array('token' => $this->token, 'twid' => $this->mytwid))->find();
			
			if ($userinfo['fromtwid']){
					$user = M('Userinfo')->where(array('token' => $this->token, 'twid' => $userinfo['fromtwid']))->find();
					$tgusername = $user['truename'];
					$this->assign('tgusername', $tgusername);
				} else {
					$tgusername = $this->mainCompany['name'];
					$this->assign('tgusername', $tgusername);
				}
		}
		
		//店中店开启与否
		if($this->_set['dzd'] == 1){
			$dzduserinfo = M('Userinfo')->where(array('token' => $this->token, 'twid' => $this->_twid))->find();
			$dzdinfo = M('New_dzd')->where(array('token' => $this->token, 'wecha_id' => $dzduserinfo['wecha_id'], 'cid' => $this->_cid))->find();
			$dzdcount = M('New_product')->where(array('token' => $this->token, 'cid' => $this->_cid))->count();
			if(empty($dzdinfo)){
				$dzdinfo = M('New_dzd')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid))->find();
				$dzduserinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $dzdinfo['wecha_id']))->find();
			}
		}
		//dump($dzdinfo);
		//die;
		
		//商家信息
		$com = $this->mainCompany;

		//购物车
		$calCartInfo = $this->calCartInfo();
		$this->assign('totalProductCount', $calCartInfo[0]);
		$this->assign('totalProductFee', $calCartInfo[1]);
		$this->assign('mytwid', $this->mytwid);
		$this->assign('twid', $this->_twid);
		$this->assign('cid', $this->_cid);
		$this->assign('dzdinfo', $dzdinfo);
		$this->assign('dzdcount', $dzdcount);
		$this->assign('dzduserinfo', $dzduserinfo);
		$this->assign('weiuser', $weiuser);
		$this->assign('createtime',$createtime);
		$this->assign('com', $com);
	}
	
	public function select()
	{
		//session("session_company_{$this->token}", null);
		
		$company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();
		if (count($company) == 1) {
			$this->redirect(U('Storenew/cats',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $company[0]['id'], 'twid' => $this->_twid)));
		}
		
		$this->assign('company', $company);
		$this->assign('metaTitle', '商城分布');
		$this->display(xdxselect);
		
	}
	
	
	/**
	 * 商城首页
	 */
	public function index() 
	{
		//获取商户ID
		session("session_company_{$this->token}", $this->_cid);
		$this->assign('cid', $this->_cid);
		
		//首页商品
		$where = array('token'=>$this->token,'cid'=>$this->_cid,'status'=>0,'tuijian'=>1);
		$itmes = $this->product_model->where($where)->order("sort ASC, id DESC")->limit(4)->select();
		$this->assign('itmes', $itmes);
		
		//首页竞拍一个商品
		$now = time();
		$Product_jingpai_model = M('New_product_jingpai');
		$jingpaiwhere = array('token' => $this->token,'cid'=>$this->_cid,'status'=>0,'endtime'=>array('gt',$now));
		$jingpai = $Product_jingpai_model->where($where)->order("endtime DESC,sort ASC")->limit(1)->select();
			foreach ($jingpai as $key=>$row){
				$jingpai[$key]['intro'] = preg_replace("/<(.*?)>/","",$row['intro']);
			}
		$this->assign('jingpai',$jingpai);
		//
		//dump($jingpai);
		//die;
		
		//幻灯片
		$picdata = M('New_store_flash');
		$picwhere = array('token' =>  $this->token, 'cid' => $this->_cid, 'type' => 0);
		$piclist = $picdata->where($picwhere)->order("id DESC")->limit(4)->select();
		$this->assign('piclist',$piclist);
		
		if($this->_set['dzd'] == 1){
			$dzderweima = M('New_twitter_usererweima')->where(array('token' => $this->token, 'twid' => $this->_twid, 'cid' => $this->_cid))->find();
			$dzduserinfo = M('Userinfo')->where(array('token' => $this->token, 'twid' => $this->_twid))->find();
			$dzdinfo = M('New_dzd')->where(array('token' => $this->token, 'wecha_id' => $dzduserinfo['wecha_id'], 'cid' => $this->_cid))->find();
			$dzdcount = M('New_product')->where(array('token' => $this->token, 'cid' => $this->_cid))->count();
			
			if(!$dzdinfo){
				$dzdinfo = M('New_dzd')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid))->find();
				$dzderweima = M('New_twitter_usererweima')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid))->find();
				$dzduserinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
				//dump($dzdinfo);
				//die;
			}
		}
		if($dzdinfo){
			$this->assign('metaTitle', $dzdinfo['title']);
			$this->assign('dzdinfo',$dzdinfo);
			$this->assign('dzdcount',$dzdcount);
			$this->assign('dzduserinfo',$dzduserinfo);
			$this->assign('erweima',$dzderweima);
		}else{
			$this->assign('metaTitle', '微商城');
		}
		
		
		$this->display(xdxindex);
	}
	
	//限时
	public function xianshi() 
	{
		//if (isset($_G['cid']))
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'groupon' => 0, 'dining' => 0, 'status' => 0,'xianshi'=> 1);
		if ($this->_isgroup) {
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			$gids = array();
			foreach ($relation as $r) {
				$gids[] = $r['gid'];
			}
			if ($gids) $where['gid'] = array('in', $gids);
			$where['cid'] = $this->mainCompany['id'];
		}
		
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		if ($catid) {
			$where['catid'] = $catid;
			$thisCat = $this->product_cat_model->where(array('id'=>$catid))->find();
			$where['cid'] = $thisCat['cid'];
			if (empty($this->_cid) || $this->_cid != $thisCat['cid']) {
				$this->_cid = $thisCat['cid'];
				session("session_company_{$this->token}", $this->_cid);
			}
			$this->assign('thisCat', $thisCat);
		}
		if (IS_POST){
			$key = $this->_post('search_name');
            $this->redirect('/index.php?g=Wap&m=Storenew&a=xianshi&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&keyword=' . $key . '&twid=' . $this->_twid);
		}
		if (isset($_GET['keyword'])){
            $where['name|intro|keyword'] = array('like', "%".$_GET['keyword']."%");
            $this->assign('isSearch', 1);
		}
		$count = $this->product_model->where($where)->count();
		$this->assign('count', $count); 
		//排序方式
		$method = isset($_GET['method']) && ($_GET['method']=='DESC' || $_GET['method']=='ASC') ? $_GET['method'] : 'DESC';
		$orders = array('time', 'discount', 'price', 'salecount');
		$order = isset($_GET['order']) && in_array($_GET['order'], $orders) ? $_GET['order'] : 'time';
		$this->assign('order', $order);
		$this->assign('method', $method);
        	
		$products = $this->product_model->where($where)->order("sort ASC, " . $order.' '.$method)->limit('0, 8')->select();
		$this->assign('products', $products);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "限时商品列表";
		$this->assign('metaTitle', $name);
		$this->display(xdxxianshi);
	}
	
	//新品
	public function xinpin() 
	{
		//if (isset($_G['cid']))
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'groupon' => 0, 'dining' => 0, 'status' => 0,'xinpin'=> 1);
		if ($this->_isgroup) {
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			$gids = array();
			foreach ($relation as $r) {
				$gids[] = $r['gid'];
			}
			if ($gids) $where['gid'] = array('in', $gids);
			$where['cid'] = $this->mainCompany['id'];
		}
		
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		if ($catid) {
			$where['catid'] = $catid;
			$thisCat = $this->product_cat_model->where(array('id'=>$catid))->find();
			$where['cid'] = $thisCat['cid'];
			if (empty($this->_cid) || $this->_cid != $thisCat['cid']) {
				$this->_cid = $thisCat['cid'];
				session("session_company_{$this->token}", $this->_cid);
			}
			$this->assign('thisCat', $thisCat);
		}
		if (IS_POST){
			$key = $this->_post('search_name');
            $this->redirect('/index.php?g=Wap&m=Storenew&a=xinpin&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&keyword=' . $key . '&twid=' . $this->_twid);
		}
		if (isset($_GET['keyword'])){
            $where['name|intro|keyword'] = array('like', "%".$_GET['keyword']."%");
            $this->assign('isSearch', 1);
		}
		$count = $this->product_model->where($where)->count();
		$this->assign('count', $count); 
		//排序方式
		$method = isset($_GET['method']) && ($_GET['method']=='DESC' || $_GET['method']=='ASC') ? $_GET['method'] : 'DESC';
		$orders = array('time', 'discount', 'price', 'salecount');
		$order = isset($_GET['order']) && in_array($_GET['order'], $orders) ? $_GET['order'] : 'time';
		$this->assign('order', $order);
		$this->assign('method', $method);
        	
		$products = $this->product_model->where($where)->order("sort ASC, " . $order.' '.$method)->limit('0, 8')->select();
		$this->assign('products', $products);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "新上市商品列表";
		$this->assign('metaTitle', $name);
		$this->display(xdxxinpin);
	}
	
	//推荐
	public function tuijian() 
	{
		//if (isset($_G['cid']))
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'groupon' => 0, 'dining' => 0, 'status' => 0,'tuijian'=> 1);
		if ($this->_isgroup) {
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			$gids = array();
			foreach ($relation as $r) {
				$gids[] = $r['gid'];
			}
			if ($gids) $where['gid'] = array('in', $gids);
			$where['cid'] = $this->mainCompany['id'];
		}
		
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		if ($catid) {
			$where['catid'] = $catid;
			$thisCat = $this->product_cat_model->where(array('id'=>$catid))->find();
			$where['cid'] = $thisCat['cid'];
			if (empty($this->_cid) || $this->_cid != $thisCat['cid']) {
				$this->_cid = $thisCat['cid'];
				session("session_company_{$this->token}", $this->_cid);
			}
			$this->assign('thisCat', $thisCat);
		}
		if (IS_POST){
			$key = $this->_post('search_name');
            $this->redirect('/index.php?g=Wap&m=Storenew&a=tuijian&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&keyword=' . $key . '&twid=' . $this->_twid);
		}
		if (isset($_GET['keyword'])){
            $where['name|intro|keyword'] = array('like', "%".$_GET['keyword']."%");
            $this->assign('isSearch', 1);
		}
		$count = $this->product_model->where($where)->count();
		$this->assign('count', $count); 
		//排序方式
		$method = isset($_GET['method']) && ($_GET['method']=='DESC' || $_GET['method']=='ASC') ? $_GET['method'] : 'DESC';
		$orders = array('time', 'discount', 'price', 'salecount');
		$order = isset($_GET['order']) && in_array($_GET['order'], $orders) ? $_GET['order'] : 'time';
		$this->assign('order', $order);
		$this->assign('method', $method);
        	
		$products = $this->product_model->where($where)->order("sort ASC, " . $order.' '.$method)->limit('0, 8')->select();
		$this->assign('products', $products);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "热门推荐商品列表";
		$this->assign('metaTitle', $name);
		$this->display(xdxtuijian);
	}
	
	/**
	 * 商城首页
	 */
	public function cats() 
	{
        //是否允许分销
        $setting = M('Distribution_setting');
        $setting = $setting->where(array('token' => $this->token))->find();
        $this->assign('allow_distribution', $setting['allow_distribution']);

        //分销商
        $distributor = array();
        $store = array();
        if (session('distributor')) {
            $distributor = M('Distributor');
            $distributor = $distributor->find(session('distributor'));
            $this->assign('distributor', $distributor);
        }

		$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
		D("New_product_cat")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("Attribute")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("New_product")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("New_product_cart")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("New_product_cart_list")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("New_product_comment")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		D("New_product_setting")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $company['id']));
		
		$cid = $this->_cid = isset($_GET['cid']) ? intval($_GET['cid']) : $company['id'];
		if ($this->_isgroup) {
			$cid = $company['id'];
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			if (empty($relation) && $this->_cid != $cid) {
				$this->error("该店铺暂时没有商品可卖，先逛逛别的", U('Storenew/select',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
			}
		}
		session("session_company_{$this->token}", $this->_cid);
		$this->assign('cid', $this->_cid);
		
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$cats = $this->product_cat_model->where(array('token' => $this->token, 'cid' => $cid))->order("sort ASC, id DESC")->select();
		$info = array();
		$sub = array();
		foreach ($cats as &$row) {
			$row['info'] = $row['des'];
			$row['img'] = $row['logourl'];
			if ($row['isfinal'] == 1) {
				$row['url'] = U('Storenew/products', array('token' => $this->token, 'catid' => $row['id'], 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid));
			} else {
				$row['sub'] = array();
				$row['url'] = U('Storenew/cats', array('token' => $this->token, 'cid' => $this->_cid, 'parentid' => $row['id'], 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid));
			}
			$info[$row['id']] = $row;
			
			$row['parentid'] && $sub[$row['parentid']][] = $row;
		}
		foreach ($sub as $k => $r) {
			if (isset($info[$k]) && $info[$k]) {
				$info[$k]['sub'] = $r;
			}
		}
		$result = array();
		foreach ($info as $kk => $ii) {
			if ($ii['parentid'] == $parentid) {
				$result[$kk] = $ii;
			}
		}
		$this->assign('info', $result);
		
		$this->assign('metaTitle', '商品分类');
		
		include('./weimicms/Lib/ORG/index.Tpl.php');
		include('./weimicms/Lib/ORG/cont.Tpl.php');
		$catemenu[0] = array('id' => 0, 'name' => '所有商品', 'picurl' => '/tpl/static/Storenew/m-act-cat.png', 'k' => 0, 'vo' => array(), 'url' => U('Storenew/cats', array('token'=> $this->token,'wecha_id'=> $this->wecha_id,'cid' => $this->_cid)));
		$catemenu[1] = array('id' => 1, 'name' => '购物车', 'picurl' => '/tpl/static/Storenew/m-act-cart.png', 'k' => 1, 'vo' => array(), 'url' => U('Storenew/cart', array('token'=> $this->token,'wecha_id'=> $this->wecha_id,'cid' => $this->_cid)));
		$catemenu[2] = array('id' => 2, 'name' => '查物流', 'picurl' => '/tpl/static/Storenew/m-act-wuliu.png', 'k' => 2, 'vo' => array(), 'url' => U('Storenew/my', array('token'=> $this->token,'wecha_id'=> $this->wecha_id,'cid' => $this->_cid)));
		$catemenu[3] = array('id' => 3, 'name' => '用户中心', 'picurl' => '/tpl/static/Storenew/user2.png', 'k' => 3, 'vo' => array(), 'url' => U('Storenew/my', array('token'=> $this->token,'wecha_id'=> $this->wecha_id,'cid' => $this->_cid)));
		$this->assign('catemenu', $catemenu);
		$set = M("New_product_setting")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();

			$this->assign('cats', $result);
			$this->display(xdxcats);
	}
	
	public function products() 
	{
		//if (isset($_G['cid']))
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'groupon' => 0, 'dining' => 0, 'status' => 0);
		if ($this->_isgroup) {
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			$gids = array();
			foreach ($relation as $r) {
				$gids[] = $r['gid'];
			}
			if ($gids) $where['gid'] = array('in', $gids);
			$where['cid'] = $this->mainCompany['id'];
		}
		
		$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;
		if ($catid) {
			$where['catid'] = $catid;
			$thisCat = $this->product_cat_model->where(array('id'=>$catid))->find();
			$where['cid'] = $thisCat['cid'];
			if (empty($this->_cid) || $this->_cid != $thisCat['cid']) {
				$this->_cid = $thisCat['cid'];
				session("session_company_{$this->token}", $this->_cid);
			}
			$this->assign('thisCat', $thisCat);
		}
		if (IS_POST){
			$key = $this->_post('search_name');
            $this->redirect('/index.php?g=Wap&m=Storenew&a=products&token=' . $this->token . '&wecha_id=' . $this->wecha_id . '&keyword=' . $key . '&twid=' . $this->_twid);
		}
		if (isset($_GET['keyword'])){
            $where['name|intro|keyword'] = array('like', "%".$_GET['keyword']."%");
            $this->assign('isSearch', 1);
		}
		$count = $this->product_model->where($where)->count();
		$this->assign('count', $count); 
		//排序方式
		$method = isset($_GET['method']) && ($_GET['method']=='DESC' || $_GET['method']=='ASC') ? $_GET['method'] : 'DESC';
		$orders = array('time', 'discount', 'price', 'salecount');
		$order = isset($_GET['order']) && in_array($_GET['order'], $orders) ? $_GET['order'] : 'time';
		$this->assign('order', $order);
		$this->assign('method', $method);
        	
		$products = $this->product_model->where($where)->order("sort ASC, " . $order.' '.$method)->limit('0, 8')->select();
		$this->assign('products', $products);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "商品列表";
		$this->assign('metaTitle', $name);
		$this->display(xdxproducts);
	}
	
	public function ajaxProducts()
	{
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'groupon' => 0, 'dining' => 0, 'status' => 0);
		if ($this->_isgroup) {
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
			$gids = array();
			foreach ($relation as $r) {
				$gids[] = $r['gid'];
			}
			if ($gids) $where['gid'] = array('in', $gids);
			$where['cid'] = $this->mainCompany['id'];
		}
		//$where = array('token' => $this->token, 'cid' => $this->_cid);
		if (isset($_GET['catid'])) {
			$catid = intval($_GET['catid']);
			$where['catid'] = $catid;
		}
		$page = isset($_GET['page']) && intval($_GET['page']) > 1 ? intval($_GET['page']) : 2;
		$pageSize = isset($_GET['pagesize']) && intval($_GET['pagesize']) > 1 ? intval($_GET['pagesize']) : 8;
		
		$method = isset($_GET['method']) && ($_GET['method']=='DESC' || $_GET['method']=='ASC') ? $_GET['method'] : 'DESC';
		$orders = array('time', 'discount', 'price', 'salecount');
		$order = isset($_GET['order']) && in_array($_GET['order'], $orders) ? $_GET['order'] : 'time';
		$start = ($page-1) * $pageSize;
		$products = $this->product_model->where($where)->order("sort ASC, " . $order.' '.$method)->limit($start . ',' . $pageSize)->select();
		exit(json_encode(array('products' => $products)));
	}
	
	public function product() 
	{
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$where = array('token' => $this->token, 'id' => $id);
		$product = $this->product_model->where($where)->find();
		if (empty($product)) {
			$this->redirect(U('Storenew/products',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		
		$product['intro'] = isset($product['intro']) ? htmlspecialchars_decode($product['intro']) : '';
		$this->assign('product', $product);
		if ($product['endtime']){
			$leftSeconds = intval($product['endtime'] - time());
			$this->assign('leftSeconds', $leftSeconds);
		}
        $normsData = M('New_product_norms')->where(array('catid' => $product['catid']))->select();
        foreach ($normsData as $row) {
        	$normsList[$row['id']] = $row['value'];
        }
        if($productCatData = M('New_product_cat')->where(array('id' => $product['catid'], 'token' => $this->token, 'cid' => $cid))->find()) {
        	$this->assign('catData', $productCatData);
        }
		$colorDetail = $normsDeatail = $productDetail = array();
		$attributeData = M("New_product_attribute")->where(array('pid' => $product['id']))->select();
		
		$productDetailData = M("New_product_detail")->where(array('pid' => $product['id']))->select();
		foreach ($productDetailData as $p) {
			$p['formatName'] = $normsList[$p['format']];
			$p['colorName'] = $normsList[$p['color']];
			
			$formatData[$p['format']] = $colorData[$p['color']] = $productDetail[] = $p;
			
			$colorDetail[$p['color']][] = $p;
			$normsDetail[$p['format']][] = $p;
		}
		$productimage = M("New_product_image")->where(array('pid' => $product['id']))->select();
		
		$this->assign('imageList', $productimage);
		$this->assign('productDetail', $productDetail);
		$this->assign('attributeData', $attributeData);
		$this->assign('normsDetail', $normsDetail);
		$this->assign('colorDetail', $colorDetail);
		$this->assign('formatData', $formatData);
		$this->assign('colorData', $colorData);
		$this->assign('metaTitle', $product['name']);
		
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $id, 'isdelete' => 0);
		$product_model = M("New_product_comment");
		$score      = $product_model->where($where)->sum('score');
		$count      = $product_model->where($where)->count();
		$comment = $product_model->where($where)->order('id desc')->limit("0, 10")->select();
		foreach ($comment as &$com) {
			$com['wecha_id'] = $com['truename'];
		}
		
		$percent = "100%";
		if ($count) {
			$score = number_format($score / $count, 1);
			$percent =  number_format($score / 5, 2) * 100 . "%";
		}
		$totalPage = ceil($count / 10);
		$page = $totalPage > 1 ? 2 : 0;
		
		$this->assign('score', $score);
		$this->assign('num', $count);
		$this->assign('page', $page);
		$this->assign('comment', $comment);
		$this->assign('percent', $percent);
		$this->display(xdxproduct);
	}
	
	public function getcomment()
	{
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$start = ($page - 1) * $offset;
		$offset = 10;
		$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
		$where = array('token' => $this->token, 'pid' => $pid, 'isdelete' => 0);
		$product_model = M("New_product_comment");
		$count = $product_model->where($where)->count();
		
		$comment = $product_model->where($where)->order('id desc')->limit($start, $offset)->select();
		foreach ($comment as &$com) {
			$com['wecha_id'] = $com['truename'];
			$com['dateline'] = date("Y-m-d H:i", $com['dateline']);//substr($com['wecha_id'], 0, 7) . "****";
		}
		$totalPage = ceil($count / $offset);
		$page = $totalPage > $page ? intval($page + 1) : 0;
		exit(json_encode(array('error_code' => false, 'data' => $comment, 'page' => $page)));
	}
	
	/**
	 * 添加购物车
	 */
	public function addProductToCart()
	{
		$count = isset($_GET['count']) ? intval($_GET['count']) : 1;
		$carts = $this->_getCart();
		$id = intval($_GET['id']);
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;//商品的详细id,即颜色与尺寸
		if (isset($carts[$id])) {
			if ($did) {
				if (isset($carts[$id][$did])) {
					$carts[$id][$did]['count'] += $count;
				} else {
					$carts[$id][$did]['count'] = $count;
				}
			} else {
				$carts[$id] += $count;
			}
		} else {
			if ($did) {
				$carts[$id][$did]['count'] = $count;
			} else {
				$carts[$id] = $count;
			}
		}
		$_SESSION[$this->session_cart_name] = serialize($carts);
		$calCartInfo = $this->calCartInfo();
		echo $calCartInfo[0].'|'.$calCartInfo[1];
	}
	
	private function calCartInfo($carts='')
	{
		$totalCount = $totalFee = 0;
		if (!$carts) {
			$carts = $this->_getCart();
		}
		$data = $this->getCat($carts);
		if (isset($data[1])) {
			foreach ($data[1] as $pid => $row) {
				$totalCount += $row['total'];
				$totalFee += $row['totalPrice'];
			}
		}
		
		return array($totalCount, $totalFee, $data[2]);
	}
	
	private function _getCart()
	{
		if (!isset($_SESSION[$this->session_cart_name])||!strlen($_SESSION[$this->session_cart_name])){
			$carts = array();
		} else {
			$carts=unserialize($_SESSION[$this->session_cart_name]);
		}
		return $carts;
	}
	
	/**
	 * 购物车列表
	 */
	public function cart()
	{
// 		if (empty($this->wecha_id)) {
// 			unset($_SESSION[$this->session_cart_name]);
// 		}

		$totalCount = $totalFee = 0;
		$data = $this->getCat($this->_getCart());
		if (isset($data[1])) {
			foreach ($data[1] as $pid => $row) {
				$totalCount += $row['total'];
				$totalFee += $row['totalPrice'];
			}
		}
		$list = $data[0];
		
		$this->assign('products', $list);
		$this->assign('totalFee', $totalFee);
		$this->assign('totalCount', $totalCount);
		$this->assign('metaTitle','购物车');
		$this->display(xdxcart);
	}
	
	
	
	/**
	 * 计算一次购物的总的价格与数量
	 * @param array $carts
	 */
	public function getCat($carts = '')
	{
		$carts = empty($carts) ? $this->_getCart() : $carts;
		//邮费
		$mailPrice = 0;
		//商品的IDS
		$pids = array_keys($carts);
		
		//商品分类IDS
		$productList = $cartIds = array();
		if (empty($pids)) {
			return array(array(), array(), array());
		}
		
		//获取分类ID
		$productdata = $this->product_model->where(array('id'=> array('in', $pids)))->select();
		foreach ($productdata as $p) {
			if (!in_array($p['catid'], $cartIds)) {
				$cartIds[] = $p['catid'];
			}
			$mailPrice = max($mailPrice, $p['mailprice']);
			$productList[$p['id']] = $p;
		}
		
		//商品规格参数值
		$catlist = $norms = array();
		if ($cartIds) {
			//产品规格列表
			$normsdata = M('New_product_norms')->where(array('catid' => array('in', $cartIds)))->select();
			foreach ($normsdata as $r) {
				$norms[$r['id']] = $r['value'];
			}
			//商品分类
			$catdata = M('New_product_cat')-> where(array('id' => array('in', $cartIds)))->select();
			foreach ($catdata as $cat) {
				$catlist[$cat['id']] = $cat;
			}
		}
		$dids = array();
		foreach ($carts as $pid => $rowset) {
			if (is_array($rowset)) {
				$dids = array_merge($dids, array_keys($rowset));
			}
		}
		//商品的详细
		$totalprice = 0;
		$data = array();
		if ($dids) {
			$dids = array_unique($dids);
			$detail = M('New_product_detail')->where(array('id'=> array('in', $dids)))->select();
			foreach ($detail as $row) {
				$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
				$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
				$row['count'] = isset($carts[$row['pid']][$row['id']]['count']) ? $carts[$row['pid']][$row['id']]['count'] : 0;
				if ($this->fans['getcardtime'] > 0) {
					$row['price'] = $row['vprice'] ? $row['vprice'] : $row['price'];
				}
				$productList[$row['pid']]['detail'][] = $row;
				$data[$row['pid']]['total'] = isset($data[$row['pid']]['total']) ? intval($data[$row['pid']]['total'] + $row['count']) : $row['count'];
				$data[$row['pid']]['totalPrice'] = isset($data[$row['pid']]['totalPrice']) ? intval($data[$row['pid']]['totalPrice'] + $row['count'] * $row['price']) : $row['count'] * $row['price'];//array('total' => $totalCount, 'totalPrice' => $totalFee);
				$totalprice += $data[$row['pid']]['totalPrice'];
			}
		}
		//商品的详细列表
		$list = array();
		foreach ($productList as $pid => $row) {
			if (!isset($data[$pid]['total'])) {
				$count = $price = 0;
				if (isset($carts[$pid]) && is_array($carts[$pid])) {
					$a = explode("|", $carts[$pid]['count']);
					$count = isset($a[0]) ? $a[0] : 0;
					$price = isset($a[1]) ? $a[1] : 0;
				} else {
					$a = explode("|", $carts[$pid]);
					$count = isset($a[0]) ? $a[0] : 0;
					$price = isset($a[1]) ? $a[1] : 0;
				}
				$data[$pid] = array();
				$row['price'] = $price ? $price : ($this->fans['getcardtime'] > 0 && $row['vprice'] ? $row['vprice'] : $row['price']);
				$row['count'] = $data[$pid]['total'] = $count;
				if (empty($count) && empty($price)) {
					$row['count'] = $data[$pid]['total'] = isset($carts[$pid]['count']) ? $carts[$pid]['count'] : (isset($carts[$pid]) && is_int($carts[$pid]) ? $carts[$pid] : 0);
					if ($this->fans['getcardtime'] > 0) {
						$row['price'] = $row['vprice'] ? $row['vprice'] : $row['price'];
					}
				}
				
				
				$data[$pid]['totalPrice'] = $data[$pid]['total'] * $row['price'];
				$totalprice += $data[$pid]['totalPrice'];
			}
			$row['formatTitle'] =  isset($catlist[$row['catid']]['norms']) ? $catlist[$row['catid']]['norms'] : '';
			$row['colorTitle'] =  isset($catlist[$row['catid']]['color']) ? $catlist[$row['catid']]['color'] : '';
			$list[] = $row;
		}
		if ($obj = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $this->_cid))->find()) {
			if ($totalprice >= $obj['price']) $mailPrice = 0;
		}
		return array($list, $data, $mailPrice);
	}
	
	public function deleteCart()
	{
		$products=array();
		$ids=array();
		$carts=$this->_getCart();
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		if ($did) {
			unset($carts[$id][$did]);
			if (empty($carts[$id])) {
				unset($carts[$id]);
			}
		} else {
			unset($carts[$id]);
		}
		$_SESSION[$this->session_cart_name] = serialize($carts);
		$this->redirect(U('Storenew/cart',array('token'=>$_GET['token'],'wecha_id'=>$_GET['wecha_id'], 'twid' => $this->_twid)));
	}
	
	public function ajaxUpdateCart(){
		$count = isset($_GET['count']) ? intval($_GET['count']) : 1;
		$carts = $this->_getCart();
		$id = intval($_GET['id']);
		$did = isset($_GET['did']) ? intval($_GET['did']) : 0;
		if (isset($carts[$id])) {
			if ($did) {
				$carts[$id][$did]['count'] = $count;
			} else {
				$carts[$id] = $count;
			}
		} else {
			if ($did) {
				$carts[$id][$did]['count'] = $count;
			} else {
				$carts[$id] = $count;
			}
		}
		$_SESSION[$this->session_cart_name] = serialize($carts);
		$calCartInfo = $this->calCartInfo();
		echo $calCartInfo[0].'|'.$calCartInfo[1];
	}
	
	
	public function ordersave()
	{
		$row = array();
		$wecha_id = $this->wecha_id;
		$row['truename'] = $this->_post('truename');
		$row['tel'] = $this->_post('tel');
		$row['address'] = $this->_post('address');
		$row['note'] = $this->_post('note');
		$row['token'] = $this->token;
		$row['wecha_id'] = $wecha_id;
		$row['paymode'] = isset($_POST['paymode']) ? intval($_POST['paymode']) : 0;
		$row['cid'] = $cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		
		if(empty($row['address'])){
			$this->error('未填写收货地址');
		}
		
		if(empty($row['tel'])){
			$this->error('未填写联系电话');
		}
		
		if(empty($row['truename'])){
			$this->error('未填写收货人姓名');
		}
		
		//积分
		$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
		$orid = isset($_POST['orid']) ? intval($_POST['orid']) : 0;
		$product_cart_model = D('New_product_cart');
		
		if ($cartObj = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'id' => $orid))->find()) {
			$carts = unserialize($cartObj['info']);
		} else {
			$carts = $this->_getCart();
		}
		$normal_rt = 0;
		
		$info = array();
		if ($carts){
			$calCartInfo = $this->calCartInfo($carts);
			foreach ($carts as $pid => $rowset) {
				$total = 0;
				$tmp = M('New_product')->where(array('id' => $pid))->find();//setDec('num', $total);
				if (is_array($rowset)) {
					foreach ($rowset as $did => $ro) {
						$temp = M('New_product_detail')->where(array('id' => $did, 'pid' => $pid))->find();//setDec('num', $ro['count']);
						if ($temp['num'] < $ro['count'] && empty($cartObj)) {
							$this->error('购买的量超过了库存');
						}
						$total += $ro['count'];
						$price = $this->fans['getcardtime'] ? ($temp['vprice'] ? $temp['vprice'] : $temp['price']) : $temp['price'];
						$info[$pid][$did] = array('count' => $ro['count'], 'price' => $price);
					}
				} else {
					$total = $rowset;
					$price = $this->fans['getcardtime'] ? ($tmp['vprice'] ? $tmp['vprice'] : $tmp['price']) : $tmp['price'];
					$info[$pid] = $rowset . "|" . $price;
				}
				if ($tmp['num'] < $total && empty($cartObj)) {
					$this->error('购买的量超过了库存');
				}
			}
			
			$setting = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $cid))->find();
			$saveprice = $totalprice = $calCartInfo[1] + $calCartInfo[2];
			if ($score && $setting && $setting['score'] > 0 && $this->fans['total_score'] >= $score) {
				$s = isset($cartObj['score']) ? intval($cartObj['score']) : 0;
				$totalprice -= ($score + $s) / $setting['score'];
				if ($totalprice <= 0) {
					$score = ($calCartInfo[1] + $calCartInfo[2]) * $setting['score'];
					$totalprice = 0;
					$row['paid'] = 1;
					$row['paymode'] = 5;
				} else {
					$score += $s;
				}
			}
			
			$row['total'] = $calCartInfo[0];
			$row['price'] = $totalprice;
			$row['diningtype'] = 0;
			$row['buytime'] = '';
			$row['tableid'] = 0;
			$row['info'] = serialize($info);
			$row['groupon']=0;
			$row['dining'] = 0;
			$row['score'] = $score;
			
			$row['twid'] = $this->_twid;
			$row['totalprice'] = $saveprice;

			if ($cartObj) {
				//$row['score'] = $cartObj['score'] + $score;
				$row['time'] = $time = time();
				$normal_rt = $product_cart_model->where(array('id' => $orid))->save($row);
				$orderid = $cartObj['orderid'];
			} else {
			
				//删除库存
				foreach ($carts as $pid => $rowset) {
					$total = 0;
					if (is_array($rowset)) {
						foreach ($rowset as $did => $ro) {
							M('New_product_detail')->where(array('id' => $did, 'pid' => $pid))->setDec('num', $ro['count']);
							$total += $ro['count'];
						}
					} else {
						if (strstr($rowset, '|')) {
							$a = explode("|", $rowset);
							$total = $a[0];
						} else {
							$total = $rowset;
						}
					}
					$product_model = M('New_product');
					$product_model->where(array('id' => $pid))->setDec('num', $total);
				}
			
				$row['time'] = $time = time();
				$row['orderid'] = $orderid = date("YmdHis") . rand(100000, 999999);
				$normal_rt = $product_cart_model->add($row);
			}
			$_SESSION[$this->session_cart_name] = null;
			unset($_SESSION[$this->session_cart_name]);
			//TODO 发货的短信提醒
			if ($normal_rt && empty($orid)) {
				$tdata = $this->getCat($carts);
				$list = array();
				foreach ($tdata[0] as $va) {
					$t = array();
					if (!empty($va['detail'])) {
						foreach ($va['detail'] as $v) {
							$t = array('num' => $v['count'], 'colorName' => $v['colorName'], 'formatName' => $v['formatName'], 'price' => $v['price'], 'name' => $va['name']);
							$list[] = $t;
						}
					} else {
						$t = array('num' => $va['count'], 'price' => $va['price'], 'name' => $va['name']);
						$list[] = $t;
					}
				}
				$company = D('Company')->where(array('token' =>$this->token, 'id' => $cid))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $row['truename'], 'tel' => $row['tel'], 'address' => $row['address'], 'buytime' => $row['time'], 'orderid' => $row['orderid'], 'sendtime' => '', 'price' => $row['price'], 'total' => $row['total'], 'list' => $list);
				$msg = ArrayToStr::array_to_str($msg);
				$op->printit($this->token, $this->_cid, 'Store', $msg, 0);
				
				$userInfo = D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
				Sms::sendSms($this->token, "您的顾客{$row['truename']}刚刚下了一个订单，订单号：{$orderid}，手机号：{$row['tel']}请您注意查看并处理");
			}
		}
		if ($normal_rt){
			$product_model = M('New_product');
			$product_cart_list_model = M('New_product_cart_list');
			$userinfo_model = M('Userinfo');
			$thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
			if (empty($cartObj)) {
				$crow = array();
				$tdata = $this->getCat($carts);
				foreach ($carts as $k => $c){
					$crow['cartid'] = $normal_rt;
					$crow['productid'] = $k;
					$crow['price'] = $tdata[1][$k]['totalPrice'];//$c['price'];
					$crow['total'] = $tdata[1][$k]['total'];
					$crow['wecha_id'] = $row['wecha_id'];
					$crow['token'] = $row['token'];
					$crow['cid'] = $row['cid'];
					$crow['time'] = $time;
					$product_cart_list_model->add($crow);
					
					//增加销量
					$totalprice || $product_model->where(array('id'=>$k))->setInc('salecount', $tdata[1][$k]['total']);
				}
				
				//保存个人信息
				if ($_POST['saveinfo']) {
					$this->assign('thisUser', $thisUser);
					$userRow = array('tel' => $row['tel'],'truename' => $row['truename'], 'address' => $row['address']);
					if ($thisUser) {
						$userinfo_model->where(array('id' => $thisUser['id']))->save($userRow);
// 						$userinfo_model->where(array('id' => $thisUser['id'], 'total_score' => array('egt', $score)))->setDec('total_score', $score);
						F('fans_token_wechaid', NULL);
					} else {
						$userRow['token'] = $this->token;
						$userRow['wecha_id'] = $wecha_id;
						$userRow['wechaname'] = '';
						$userRow['qq'] = 0;
						$userRow['sex'] = -1;
						$userRow['age'] = 0;
						$userRow['birthday'] = '';
						$userRow['info'] = '';
	
						$userRow['total_score'] = 0;
						$userRow['sign_score'] = 0;
						$userRow['expend_score'] = 0;
						$userRow['continuous'] = 0;
						$userRow['add_expend'] = 0;
						$userRow['add_expend_time'] = 0;
						$userRow['live_time'] = 0;
						$userinfo_model->add($userRow);
					}
				}
				
				if ($thisUser) {
// 					$userinfo_model->where(array('id' => $thisUser['id']))->save($userRow);
					$userinfo_model->where(array('id' => $thisUser['id'], 'total_score' => array('egt', $score)))->setDec('total_score', $score);
					F('fans_token_wechaid', NULL);
				}
			} else {
				$userinfo_model->where(array('id' => $thisUser['id'], 'total_score' => array('egt', $score - $cartObj['score'])))->setDec('total_score', $score - $cartObj['score']);
				F('fans_token_wechaid', NULL);
			}
			
			
			//购买商品时的推广记录
// 			if ($this->_twid) {
// 				$this->savelog(3, $this->_twid, $this->token, $this->_cid, $saveprice);
// 			}
			
// 			$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
// 			if ($totalprice) {
// 				if ($alipayConfig['open'] && $totalprice && $row['paymode'] == 1) {
// 					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Store', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice)));
// 					die;
// 				} elseif ($this->fans['balance'] > 0 && $row['paymode'] == 4) {
// 					$this->success('正在提交中...', U('CardPay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Store', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice)));
// 					die;
// 				}
// 			}
			$model = new templateNews();
			$model->sendTempMsg('TM00184', array('href' => U('Storenew/my',array('token' => $this->token, 'wecha_id' => $wecha_id), true, false, true), 'wecha_id' => $wecha_id, 'first' => ''.$row['truename'].',您好！您的订单未支付', 'ordertape' => date("Y年m月d日H时i分s秒"), 'ordeID' => $orderid, 'remark' => '本次订单金额：'.$row['price'].'元，备注信息：'.$row['note'].'，请及时付款，点击查看详情！'));
			
			//测试消息服务
			//$where=array('token'=>$this->token);
			//$this->thisWxUser=M('Wxuser')->where($where)->find();
			//$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			//$access_token=json_decode($this->curlGet($url_get));
			//$a = $access_token->access_token;
			//客服接口，24小时内发送过内容的用户才有
			//$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$a;
			//消息预览接口
			//$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$a;
			//$data = '{"touser":"'.$wecha_id.'","msgtype":"text", "text":{"content":"'.$row['truename'].'你订购了商品，电话：'.$row['tel'].'，收获地址：'.$row['address'].'，订单号：'.$orderid.',订单金额：'.$row['price'].'，请及时付款，谢谢！"}}';
			//$this->postCurl($url,$data);
			//

			if ($totalprice) {
				if ($this->fans['balance'] > 0 && $row['paymode'] == 4) {
					$this->success('正在提交中...', U('CardPay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice)));
					die;
				} else {
					$notOffline = $setting['paymode'] == 1 ? 0 : 1;
					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice, 'notOffline' => $notOffline)));
					die;
				}
			}
			$this->success('预定成功,进入您的订单页', U('Storenew/my',array('token' => $_GET['token'], 'wecha_id' => $wecha_id, 'success' => 1, 'twid' => $this->_twid)));
		} else {
			$this->error('订单生产失败');
		} 
	}
	
	
	public function orderCart()
	{
		
		$set = M("New_twitter_set")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		if (empty($this->wecha_id) && empty($this->mytwid) && $set) {
			$callbackurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			session('callbackurl', $callbackurl);
			$this->redirect(U('Storenew/login',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid, 'rget' => 1)));
		} elseif (empty($this->wecha_id)) {
			unset($_SESSION[$this->session_cart_name]);
		}
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		$orid = isset($_GET['orid']) ? intval($_GET['orid']) : 0;
		$setting = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $cid))->find();
		$this->assign('setting', $setting);
		//是否要支付
// 		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
// 		$this->assign('alipayConfig', $alipayConfig);

		$totalCount = $totalFee = 0;
		if ($orid && ($cartObj = M('New_product_cart')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => $orid))->find())) {
			$products = unserialize($cartObj['info']);
			$data = $this->getCat($products);
		} else {
			$data = $this->getCat($this->_getCart());
		}
		if (empty($data[0])) {
			$this->redirect(U('Storenew/cart', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		if (isset($data[1])) {
			foreach ($data[1] as $pid => $row) {
				$totalCount += $row['total'];
				$totalFee += $row['totalPrice'];
			}
		}
		if ($cartObj) {
			$totalFee -= $cartObj['score'] / $setting['score'];
		}
		if (empty($totalCount)) {
			$this->error('没有购买商品!', U('Storenew/cart', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}


		if($this->wxuser['winxintype'] ==3 && $this->wxuser['oauth'] == 1){
			$addr = new WechatAddr($this->wxuser);
			$this->assign('addrSign', $addr->addrSign());
		}
		
		
		
		$list = $data[0];
		$this->assign('orid', $orid);
		$this->assign('products', $list);
		$this->assign('totalFee', $totalFee);
		$this->assign('totalCount', $totalCount);
		$this->assign('mailprice', $data[2]);
		$this->assign('metaTitle', '购物车结算');
		$this->display(xdxorderCart);
	}
	
	public function my()
	{
		$offset = 5;
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$start = ($page - 1) * $offset;
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$product_cart_model = M('New_product_cart');
		$orders = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'groupon' => 0, 'dining' => 0 ,'jingpai' => 0))->limit($start, $offset)->order('time DESC')->select();
		$count = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'groupon' => 0, 'dining' => 0 ,'jingpai' => 0))->count();
		$list = array();
		if ($orders){
			foreach ($orders as $o){
				$products = unserialize($o['info']);
				$pids = array_keys($products);
				$o['productInfo'] = array();
				if ($pids) {
					$o['productInfo'] = M('New_product')->where(array('id' => array('in', $pids)))->select();
				}
				$list[] = $o;
			}
		}
		$totalpage = ceil($count / $offset);
		$this->assign('orders', $list);
		$this->assign('ordersCount', $count);
		$this->assign('totalpage', $totalpage);
		$this->assign('page', $page);
		$this->assign('metaTitle', '我的订单');
		
		//是否要支付
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		$this->display(xdxmy);
	}
	
	public function myjingpai()
	{
		$offset = 5;
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$start = ($page - 1) * $offset;
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$product_cart_model = M('New_product_cart');
		$orders = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'groupon' => 0, 'dining' => 0, 'jingpai'=>1))->limit($start, $offset)->order('time DESC')->select();
		$count = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'groupon' => 0, 'dining' => 0,'jingpai'=>1))->count();
		$list = array();
		if ($orders){
			foreach ($orders as $o){
				$products = unserialize($o['info']);
				$pids = array_keys($products);
				$o['productInfo'] = array();
				if ($pids) {
					$o['productInfo'] = M('New_product_jingpai')->where(array('id' => array('in', $pids)))->select();
				}
				$list[] = $o;
			}
		}
		$totalpage = ceil($count / $offset);
		$this->assign('orders', $list);
		$this->assign('ordersCount', $count);
		$this->assign('totalpage', $totalpage);
		$this->assign('page', $page);
		$this->assign('metaTitle', '我的订单');
		
		//是否要支付
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		$this->display(xdxmyjingpai);
	}
	
	public function jingpaiorderCart()
	{

		$set = M("New_twitter_set")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		if (empty($this->wecha_id) && empty($this->mytwid) && $set) {
			$callbackurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			session('callbackurl', $callbackurl);
			$this->redirect(U('Storenew/login',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid, 'rget' => 1)));
		} elseif (empty($this->wecha_id)) {
			unset($_SESSION[$this->session_cart_name]);
		}
		
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		$orid = isset($_GET['orid']) ? intval($_GET['orid']) : 0;
		$setting = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $cid))->find();
		$this->assign('setting', $setting);
		//是否要支付
// 		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
// 		$this->assign('alipayConfig', $alipayConfig);
		
		//dump($wecha_id);
		//die;
		
		
		$cart = M('New_product_cart')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => $orid))->find();
		
			// foreach ($cart as $key=>$val) {
					// $jingpai = M('New_product_jingpai')->where(array('token' => $this->token,'wecha_id' => $this->wecha_id,'id' => $val['productid']))->find();
					// $cart[$key]['logourl']= $jingpai['logourl'];
					// $cart[$key]['name']= $jingpai['name'];
			// }
		$totalCount = $cart['price'];
		$orderid = $cart['orderid'];
		
		//$jingpai = M('New_product_jingpai')->where(array('token' => $this->token,'wecha_id' => $this->wecha_id,'id' =>3))->find();
		//dump($cart);
		//die;
		
		
		if (empty($cart)) {
			$this->redirect(U('Storenew/cart', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		//if (isset($data[1])) {
		//	foreach ($data[1] as $pid => $row) {
		//		$totalCount += $row['total'];
		//		$totalFee += $row['totalPrice'];
		//	}
		//}
		
		
		//if ($cartObj) {
		//	$totalFee -= $cartObj['score'] / $setting['score'];
		//}
		if (empty($totalCount)) {
			$this->error('没有购买商品!', U('Storenew/cart', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}


		//if($this->wxuser['winxintype'] ==3 && $this->wxuser['oauth'] == 1){
		//	$addr = new WechatAddr($this->wxuser);
		//	$this->assign('addrSign', $addr->addrSign());
		//}
		
		$list = $cart;
		$this->assign('orid', $orid);
		$this->assign('orderid', $orderid);
		$this->assign('p', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('metaTitle', '购物车结算');
		$this->display(xdxjingpaiorderCart);
	}
	
	public function jingpaiordersave()
	{	

		//TODO 发货的短信提醒
		if(IS_POST){
			$row = array();
			$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
			$row['truename'] = $this->_post('truename');
			$row['tel'] = $this->_post('tel');
			$row['address'] = $this->_post('address');
			$row['token'] = $this->token;
			$row['note'] = $this->_post('note');
			$row['wecha_id'] = $wecha_id;
			$row['paymode'] = isset($_POST['paymode']) ? intval($_POST['paymode']) : 0;
			$row['cid'] = $cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
			
			$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
			$orid = $this->_post('orderid');
			$product_cart_model = M('New_product_cart');

			if ($cartObj = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'orderid' => $orid))->find()) {
				$carts = $cartObj;
			} else {
				$this->error('未找到订单信息，请联系商家');
			}

			//保存订单信息
			$row['time'] = $time = time();
			$saverow = $product_cart_model->where(array('orderid' => $orid))->save($row);
			
			$userinfo_model = M('Userinfo');
			$thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
			//保存个人信息
			if ($_POST['saveinfo']) {
				$this->assign('thisUser', $thisUser);
				$userRow = array('tel' => $row['tel'],'truename' => $row['truename'], 'address' => $row['address']);
				if ($thisUser) {
					$userinfo_model->where(array('id' => $thisUser['id']))->save($userRow);
					F('fans_token_wechaid', NULL);
				} else {
					$userRow['token'] = $this->token;
					$userRow['wecha_id'] = $wecha_id;
					$userRow['wechaname'] = '';
					$userRow['qq'] = 0;
					$userRow['sex'] = -1;
					$userRow['age'] = 0;
					$userRow['birthday'] = '';
					$userRow['info'] = '';

					$userRow['total_score'] = 0;
					$userRow['sign_score'] = 0;
					$userRow['expend_score'] = 0;
					$userRow['continuous'] = 0;
					$userRow['add_expend'] = 0;
					$userRow['add_expend_time'] = 0;
					$userRow['live_time'] = 0;
					$userinfo_model->add($userRow);
				}
			}
			//保存个人信息end
			
			$orderid = $orid;
			$paymode = $row['paymode'];
			$totalprice = $_POST['totalCount'];
			
			if(empty($_POST['address'])){
				$this->error('未填写收货地址');
			}
		
			if(empty($_POST['tel'])){
				$this->error('未填写联系电话');
			}
			
			if(empty($_POST['truename'])){
				$this->error('未填写收货人姓名');
			}

			$model = new templateNews();
			$model->sendTempMsg('TM00184', array('href' => U('Storenew/my',array('token' => $this->token, 'wecha_id' => $wecha_id), true, false, true), 'wecha_id' => $wecha_id, 'first' => '购买商品提醒', 'ordertape' => date("Y年m月d日H时i分s秒"), 'ordeID' => $orid, 'remark' => ''.$row['truename'].'你参与竞拍成功，订单信息收货人：'.$row['truename'].'，电话：'.$row['tel'].'，收获地址：'.$row['address'].'，备注信息：'.$row['note'].'，付款金额：'.$totalprice.'元，请及时付款，谢谢！'));
			
			//测试消息服务
			$where=array('token'=>$this->token);
			$this->thisWxUser=M('Wxuser')->where($where)->find();
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			$access_token=json_decode($this->curlGet($url_get));
			$a = $access_token->access_token;
			//客服接口，24小时内发送过内容的用户才有
			//$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$a;
			//消息预览接口
			$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$a;
			//获取用户openID
			$where2=array('token'=>$row['token'],'cid'=>$row['cid']);
			$sendwecha_id=M('New_product_set_reply')->where($where2)->field('wecha_id')->find();
			//开始发送消息到商家微信
			$data = '{"touser":"'.$sendwecha_id['wecha_id'].'","msgtype":"text", "text":{"content":"您的用户：'.$row['truename'].'参与竞拍订单信息，收货人：'.$row['truename'].'，电话：'.$row['tel'].'，收获地址：'.$row['address'].'，备注信息：'.$row['note'].'，付款金额：'.$totalprice.'元。（当您看到此信息表示用户已经提交订单，等待付款）"}}';
			$this->postCurl($url,$data);
			//
			
			//dump($orderid);
			//die;

			if ($totalprice) {
				if ($paymode == 4) {
					$this->success('正在提交中...', U('CardPay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice)));
					die;
				} else {
					$notOffline = $setting['paymode'] == 1 ? 0 : 1;
					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice, 'notOffline' => $notOffline)));
					die;
				}
			}
			$this->success('预定成功,进入您的订单页', U('Storenew/my',array('token' => $_GET['token'], 'wecha_id' => $wecha_id, 'success' => 1, 'twid' => $this->_twid)));
		} else {
			$this->error('订单生产失败');
		}
	}
	
	public function myDetail()
	{
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cartid = isset($_GET['cartid']) && intval($_GET['cartid'])? intval($_GET['cartid']) : 0;
		$product_cart_model = M('New_product_cart');

		$list = array();
		if ($cartObj = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'id' => $cartid))->find()){
			$products = unserialize($cartObj['info']);
			$data = $this->getCat($products);
			$pids = array_keys($products);
			$cartObj['productInfo'] = array();
			if ($pids) {
				$cartObj['productInfo'] = M('New_product')->where(array('id' => array('in', $pids)))->select();
			}
			
			$totalCount = $totalFee = 0;
			if (isset($data[1])) {
				foreach ($data[1] as $pid => $row) {
					$totalCount += $row['total'];
					$totalFee += $row['totalPrice'];
				}
			}
			$list = $data[0];
			$commentList = array();
			//if ($cartObj['paid']) {
				$comment = M("New_product_comment")->where(array('token' => $this->token, 'cartid' => $cartid, 'wecha_id' => $wecha_id))->select();
				foreach ($comment as $row) {
					$commentList[$row['pid']][$row['detailid']] = $row;
				}
			//}
			$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
			foreach ($list as &$row) {
				if ($row['detail']) {
					foreach ($row['detail'] as &$r) {
						if (isset($commentList[$row['id']][$r['id']])) {
							$r['comment'] = 0;
						} else {
							$r['comment'] = $alipayConfig['open'] ? ($cartObj['paid'] ? 1 : 0) : 1;
						}
					}
				} else {
					if (isset($commentList[$row['id']][0])) {
						$row['comment'] = 0;
					} else {
						$row['comment'] = $cartObj['paid'] ? 1 : 0;
					}
				}
			}
			
			//订单物流状态查询
			if ($cartObj['sent'] == '1'){
				$wuliu = array('token' => $this->token, 'cid' => $cid, 'logistics' => $cartObj['logistics'], 'logisticsid' => $cartObj['logisticsid']);
				$wuliustat = $this->Getwuliu($wuliu);
				switch ($wuliustat){
						default:
						$this->assign('wuliustat', '未知信息/未查询到');
						break;
						case 1:
						$this->assign('wuliustat', '快递运输中');
						break;
						case 2:
						$this->assign('wuliustat', '快递派送中');
						break;
						case 3:
						$this->assign('wuliustat', '用户已签收');
						break;
						case 4:
						$this->assign('wuliustat', '邮件退回拒签');
						break;
						case 5:
						$this->assign('wuliustat', '其他问题，咨询快递公司');
						break;
				}
			}
			
			$this->assign('commentList', $commentList);
			$this->assign('products', $list);
			$this->assign('totalFee', $totalFee);
			$this->assign('totalCount', $totalCount);
			$this->assign('mailprice', $data[2]);
			$this->assign('cartData', $cartObj);
			$this->assign('cartid', $cartid);
		}
		$this->assign('metaTitle', '我的订单');
		$this->display(xdxmyDetail);
	}
	
	public function myjingpaiDetail()
	{
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cid = isset($_GET['cid']) && intval($_GET['cid'])? intval($_GET['cid']) : 0;
		
		$cartid = isset($_GET['cartid']) && intval($_GET['cartid'])? intval($_GET['cartid']) : 0;

		$cart = M('New_product_cart')->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'cid' => $cid, 'id' => $cartid , 'jingpai'=> 1))->find();
		$cartlist = M('New_product_cart_list')->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'cid' => $cid, 'cartid' => $cart['id']))->find();
		$cart['pid'] = $cartlist['productid'];

		$Product_jingpai = M('New_product_jingpai')->where(array('token' => $this->token, 'cid' => $cid, 'id' => $cart['pid']))->find();
		$cart['name'] = $Product_jingpai['name'];
		$cart['logourl'] = $Product_jingpai['logourl'];
		$totalCount = $cart['price'];
		
		
		//订单物流状态查询
		if ($cart['sent'] == '1'){
			$wuliu = array('token' => $this->token, 'cid' => $cid, 'logistics' => $cart['logistics'], 'logisticsid' => $cart['logisticsid']);
			$wuliustat = $this->Getwuliu($wuliu);
			switch ($wuliustat){
					default:
					$this->assign('wuliustat', '未知信息/未查询到');
					break;
					case 1:
					$this->assign('wuliustat', '快递运输中');
					break;
					case 2:
					$this->assign('wuliustat', '快递派送中');
					break;
					case 3:
					$this->assign('wuliustat', '用户已签收');
					break;
					case 4:
					$this->assign('wuliustat', '邮件退回拒签');
					break;
					case 5:
					$this->assign('wuliustat', '其他问题，咨询快递公司');
					break;
			}
		}	
		
		$this->assign('p', $cart);
		$this->assign('cartData', $cart);
		$this->assign('totalCount', $totalCount);
		$this->assign('cartid', $cartid);
		$this->assign('metaTitle', '我的竞拍订单');
		$this->display(xdxmyjingpaiDetail);
	}
	
	//快递查询实时结果
	function Getwuliu($wuliu){
		//加载快递公司
		$typeCom = $wuliu["logistics"];
		$typeNu = $wuliu["logisticsid"];
		include_once("ickd_companies.php");
		//include 'ickd_companies.php';
		$id='106004';//请将123456替换成您在http://www.ickd.cn/api/reg.html申请到的id
		$secret='9fe2da6307dcb2eed35989466f98c136';//您在http://www.ickd.cn/api/reg.html申请到的secret
		$url ='http://api.ickd.cn/?id='.$id.'&secret='.$secret.'&com='.$typeCom.'&nu='.$typeNu.'&type=json&ord=desc&ver=2';
		
		//查询开始
		//优先使用curl模式发送数据
		if (function_exists('curl_init') == 1){
		  $curl = curl_init();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_HEADER,0);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		  $content = curl_exec($curl);
		  curl_close ($curl);
		}else{
		  include("snoopy.php");
		  $snoopy = new snoopy();
		  $snoopy->referer = $_SERVER['HTTP_REFERER'];
		  $snoopy->fetch($url);
		  $content = $snoopy->results;
		}
		$content=mb_convert_encoding($content,'utf8','gbk');
		$stat=json_decode($content,true);
		$wuliustat = $stat['status'];
		//dump($content);
		//die;
		return $wuliustat;
	}
	
	public function cancelCart()
	{
		$cartid = isset($_GET['cartid']) && intval($_GET['cartid'])? intval($_GET['cartid']) : 0;
		$product_model=M('New_product');
		$product_cart_model = M('New_product_cart');
		$product_cart_list_model = M('New_product_cart_list');
		$thisOrder = $product_cart_model->where(array('id'=> $cartid))->find();
		if (empty($thisOrder)) {
			exit(json_encode(array('error_code' => true, 'msg' => '没有此订单')));
		}
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$id = $thisOrder['id'];
		if (empty($thisOrder['paid'])) {
			//删除订单和订单列表
			$product_cart_model->where(array('id' => $cartid))->delete();
			$product_cart_list_model->where(array('cartid' => $cartid))->delete();
			//还原积分
			$userinfo_model = M('Userinfo');
			$thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
			$userinfo_model->where(array('id' => $thisUser['id']))->setInc('total_score', $thisOrder['score']);
			F('fans_token_wechaid', NULL);
			//商品销量做相应的减少
			$carts = unserialize($thisOrder['info']);
			//还原库存
			foreach ($carts as $pid => $rowset) {
				$total = 0;
				if (is_array($rowset)) {
					foreach ($rowset as $did => $row) {
						M('New_product_detail')->where(array('id' => $did, 'pid' => $pid))->setInc('num', $row['count']);
						$total += $row['count'];
					}
				} else {
					if (strstr($rowset, '|')) {
						$a = explode("|", $rowset);
						$total = $a[0];
					} else {
						$total = $rowset;
					}
				}
				$product_model->where(array('id' => $pid))->setInc('num', $total);
				//$product_model->where(array('id' => $pid))->setDec('salecount', $total);
			}
			exit(json_encode(array('error_code' => false, 'msg' => '订单取消成功')));
		}
		exit(json_encode(array('error_code' => true, 'msg' => '购买成功的订单不能取消')));
	}
	
	public function updateOrder()
	{
		$product_cart_model = M('New_product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id'])))->find();
		//检查权限
		if ($thisOrder['wecha_id']!=$this->wecha_id){
			exit();
		}
		$this->assign('thisOrder',$thisOrder);
		$carts = unserialize($thisOrder['info']);
		$totalCount = $totalFee = 0;
		$listNum = array();
		$data = $this->getCat($carts);
		if (isset($data[1])) {
			foreach ($data[1] as $pid => $row) {
				$totalCount += $row['total'];
				$totalFee += $row['totalPrice'];
				$listNum[$pid] = $row['total'];
			}
		}
		$list = $data[0];
		$this->assign('products', $list);
		$this->assign('totalFee', $totalFee);
		$this->assign('listNum', $listNum);
		$this->assign('metaTitle','修改订单');
		//是否要支付
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$this->assign('alipayConfig', $alipayConfig);
		$this->display(xdxupdateOrder);
	}
	
	/**
	 * 评论
	 */
	public function comment()
	{
		$cartid = isset($_GET['cartid']) && intval($_GET['cartid'])? intval($_GET['cartid']) : 0;
		$pid = isset($_GET['pid']) ? intval($_GET['pid']) : 0;
		$detailid = isset($_GET['detailid']) ? intval($_GET['detailid']) : 0;
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		if ($cartObj = M("New_product_cart")->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'id' => $cartid))->find()){
			if ($cartObj['paid'] == 0 && $alipayConfig['open']) {
				$this->error("您暂时还不能评论该商品");
			}
		} else {
			$this->error("您还没有购买此商品，暂时无法对其评论");
		}
		
		$this->assign('cartid', $cartid);
		$this->assign('detailid', $detailid);
		$this->assign('pid', $pid);
		$this->display(xdxcomment);
	}
	
	public function commentSave()
	{
		$cartid = isset($_POST['cartid']) && intval($_POST['cartid'])? intval($_POST['cartid']) : 0;
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
		$detailid = isset($_POST['detailid']) ? intval($_POST['detailid']) : 0;
		
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		if ($cartObj = M("New_product_cart")->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'id' => $cartid))->find()){
			if ($cartObj['paid'] == 0 && $alipayConfig['open']) {
				$this->error("您暂时还不能评论该商品");
			}
			$data = array();
			if ($product = M("New_product")->where(array('id' => $pid, 'token' => $this->token))->find()) {
				if ($detailid) {
					$products = unserialize($cartObj['info']);
					$result = $this->getCat($products);
					foreach ($result[0] as $row) {
						foreach ($row['detail'] as $d) {
							if ($d['id'] == $detailid) {
								$str = $row['colorTitle'] && $d['colorName'] ? $row['colorTitle'] . ":" . $d['colorName'] : '';
								$str .= $row['formatTitle'] && $d['formatName'] ? ", " . $row['formatTitle'] . ":" . $d['formatName'] : '';
								$data['productinfo'] = $str;
							}
						}
					}
				}
			} else {
				$this->error("此产品可能下架了，暂时无法对其评论");
			}
		} else {
			$this->error("您还没有购买此商品，暂时无法对其评论");
		}
		
		$comment = D("New_product_comment");
		$data['cartid'] = $cartid;
		$data['pid'] = $pid;
		$data['detailid'] = $detailid;
		$data['score'] = $_POST['score'];
		$data['content'] = htmlspecialchars($_POST['content']);
		$data['token'] = $this->token;
		$data['cid'] = $this->_cid;
		$data['wecha_id'] = $wecha_id;
		$data['truename'] = $cartObj['truename'];
		$data['tel'] = $cartObj['tel'];
		$data['__hash__'] = $_POST['__hash__'];
		$data['dateline'] = time();
		if (false !== $comment->create($data)) {
			unset($data['__hash__']);
			$comment->add($data);
			$this->success("评论成功", U('Storenew/myDetail',array('token' => $this->token,'wecha_id' => $this->wecha_id,'cartid' => $cartid, 'twid' => $this->_twid)));
		} else {
			$this->error($comment->error, U('Storenew/myDetail',array('token' => $this->token,'wecha_id' => $this->wecha_id,'cartid' => $cartid, 'twid' => $this->_twid)));
		}
	}
	
	public function deleteOrder()
	{
		$product_model = M('New_product');
		$product_cart_model = M('New_product_cart');
		$product_cart_list_model = M('New_product_cart_list');
		$thisOrder = $product_cart_model->where(array('id' => intval($_GET['id'])))->find();
		//检查权限
		$id = $thisOrder['id'];
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		
		if ($thisOrder['wecha_id'] != $wecha_id || $thisOrder['handled'] == 1) {
			exit();
		}
		//删除订单和订单列表
		$product_cart_model->where(array('id' => $id))->delete();
		$product_cart_list_model->where(array('cartid' => $id))->delete();
		//商品销量做相应的减少
		$carts = unserialize($thisOrder['info']);
		foreach ($carts as $k=>$c) {
			if (is_array($c)) {
				$productid = $k;
				$price = $c['price'];
				$count = $c['count'];
				//$product_model->where(array('id'=>$k))->setDec('salecount',$c['count']);
			}
		}
		$this->redirect(U('Storenew/myinfo', array('token' => $_GET['token'], 'cid' => $_GET['cid'], 'wecha_id' => $_GET['wecha_id'], 'twid' => $this->_twid)));
	}
	
	/**
	 * 支付成功后的回调函数
	 */
	public function payReturn() {
// 	   $orderid = $_GET['orderid'];
		
	   
		if(isset($_GET['nohandle'])){
			//执行跳转
			$this->redirect(U('Storenew/myinfo',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}else {
			$out_trade_no=$_GET['orderid'];
			ThirdPayStorenew::index($out_trade_no);
		}
		
// 	   if ($order = M('New_product_cart')->where(array('orderid' => $orderid, 'token' => $this->token))->find()) {
// 			//TODO 发货的短信提醒
// 			if ($order['paid']) {
// 				$carts = unserialize($order['info']);
// 				$tdata = $this->getCat($carts);
// 				$list = array();
// 				foreach ($tdata[0] as $va) {
// 					$t = array();
// 					$salecount = 0;
// 					if (!empty($va['detail'])) {
// 						foreach ($va['detail'] as $v) {
// 							$t = array('num' => $v['count'], 'colorName' => $v['colorName'], 'formatName' => $v['formatName'], 'price' => $v['price'], 'name' => $va['name']);
// 							$list[] = $t;
// 							$salecount += $v['count'];
// 						}
// 					} else {
// 						$t = array('num' => $va['count'], 'price' => $va['price'], 'name' => $va['name']);
// 						$list[] = $t;
// 						$salecount = $va['count'];
// 					}
// 					D("Product")->where(array('id' => $va['id']))->setInc('salecount', $salecount);
// 				}
				
// 				if ($order['twid']) {
// 					$this->savelog(3, $order['twid'], $this->token, $order['cid'], $order['totalprice']);
// 				}
				
// 				$company = D('Company')->where(array('token' =>$this->token, 'id' => $order['cid']))->find();
// 				$op = new orderPrint();
// 				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $order['truename'], 'tel' => $order['tel'], 'address' => $order['address'], 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'sendtime' => '', 'price' => $order['price'], 'total' => $order['total'], 'list' => $list);
// 				$msg = ArrayToStr::array_to_str($msg, 1);
// 				$op->printit($this->token, $this->_cid, 'Store', $msg, 1);
// 				$userInfo = D('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
// 				Sms::sendSms($this->token, "您的顾客{$userInfo['truename']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理");
// 				$model = new templateNews();
// 				$model->sendTempMsg('TM00820', array('href' => U('Storenew/my',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)), 'wecha_id' => $this->wecha_id, 'first' => '购买商品提醒', 'keynote1' => '订单已支付', 'keynote2' => date("Y年m月d日H时i分s秒"), 'remark' => '购买成功，感谢您的光临，欢迎下次再次光临！'));
// 			}
// 			$this->redirect(U('Storenew/my',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
// 	   }else{
// 	      exit('订单不存在');
// 	    }
	}
	
	public function register()
	{
		if (IS_POST) {
			$tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
			$password2 = isset($_POST['password2']) ? htmlspecialchars($_POST['password2']) : '';
			$truename = isset($_POST['truename']) ? htmlspecialchars($_POST['truename']) : '';
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$wechaname = isset($_POST['wechaname']) ? htmlspecialchars($_POST['wechaname']) : '';
			$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
			//$wecha_id = md5($tel . time());
			$userInfo = M('Userinfo')->where(array('username' => $username))->find();
			if (empty($username)) {
				$this->error("此账号已存在!");
			}
			if ($userInfo) {
				$this->error("此账号已存在!");
			}
			if (empty($tel)) {
				$this->error("电话号码不能为空!");
			}
			if (empty($password)) {
				$this->error("密码不能为空!");
			}
			if ($password != $password2) {
				$this->error("密码不正确");
			}
            $userInfo = M('Userinfo')->where(array('wecha_id' => $this->wecha_id))->find();
            if ($userInfo) {
                D("Userinfo")->save(array('id' => $userInfo['id'], 'truename' => $truename, 'token' => $this->token, 'address' => $address, 'password' => md5($password), 'tel' => $tel, 'username' => $username));
                $uid = $userInfo['id'];
            } else {
                $uid = D("Userinfo")->add(array('truename' => $truename, 'token' => $this->token, 'address' => $address, 'password' => md5($password), 'tel' => $tel, 'username' => $username));
            }
			if ($uid) {
				$twid = $this->randstr{rand(0, 51)} . $this->randstr{rand(0, 51)} . $this->randstr{rand(0, 51)} . $uid;
                $wecha_id = !empty($this->wecha_id) ? $this->wecha_id : $twid;
				D('Userinfo')->where(array('id' => $uid))->save(array('twid' => $twid, 'wecha_id' => $wecha_id));
				$this->savelog(2, $this->_twid, $this->token, $this->_cid);
				session('twid', $twid);
				$callbackurl = session('callbackurl');
				$this->success('注册成功', $callbackurl);
			} else {
				$this->error(D("Userinfo")->error());
			}
		} else {
			$this->assign('metaTitle', '商城会员注册');
			$this->display(xdxregister);
		}
	}
	
	public function getapi(){
		$this->server_url='http://v.012wz.com/api.php';
		$version = './weimicms/Lib/Action/User/Storenewversion.php';
        $ver = include($version);
        $release = include($version);
        $vername = include($version);
        $ver = $ver['ver'];
        //$ver = substr($ver, -3);
        $release = $release['release'];
        $vername = $vername['vername'];
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = $this -> server_url;
        $updatehosturl = $updatehost . '?a=client_check_time&v=' . $ver . '&u=' . $hosturl;
        $domain_time = file_get_contents($updatehosturl);
		 if($domain_time == '0'){
            $domain_time = '授权已过期，请联系官方，客服QQ:800083075';
		}else{
			$domain_time = $domain_time;
		}
		echo $domain_time;
	}
	
	public function login()
	{
        if (session('twid')) {
            $this->redirect(U('Storenew/myinfo', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->mytwid)));
        }

		if (IS_POST) {
			$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
			$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
			$userInfo = M('Userinfo')->where(array('username' => $username))->find();
			if (empty($userInfo)) {
				$this->error("用户不存在");
			} elseif ($userInfo['password'] != md5($password)) {
				$this->error("密码不正确");
			} else {

                //是否是分销商
                $distributor = M('Distributor');
                $distributor = $distributor->where(array('uid' => $userInfo['id']))->find();
                if (!empty($distributor['id'])) {
                    session('distributor', $distributor['id']); //分销商id
                    $store = M('Distributor_store');
                    $store_id = $store->where(array('did' => $distributor['id']))->getField('id');
                }
                
                //所属分销商店铺
                if ($userInfo['store_id']) {
                	session('store_id', $userInfo['store_id']);
                }
               
                session('uid', $userInfo['id']);
				session('twid', $userInfo['twid']);
				$callbackurl = session('callbackurl');
				if ($callbackurl) {
					session('callbackurl', null);
					$this->success('登录成功', $callbackurl);
				} else {
					$this->success('登录成功', U('Storenew/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
				}
			}
		} else {
			$this->assign('metaTitle', '商城会员登录');
			$this->display(xdxlogin);
		}
	}
	
	//用户修改信息
	public function setuserinfo()
	{
		if (IS_POST) {
			$tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
			$bank = isset($_POST['bank']) ? htmlspecialchars($_POST['bank']) : '';
			$number = isset($_POST['number']) ? htmlspecialchars($_POST['number']) : '';
			$truename = isset($_POST['truename']) ? htmlspecialchars($_POST['truename']) : '';
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$qq = isset($_POST['qq']) ? htmlspecialchars($_POST['qq']) : '';
			$alipay = isset($_POST['alipay']) ? htmlspecialchars($_POST['alipay']) : '';
			$weixinpay = isset($_POST['weixinpay']) ? htmlspecialchars($_POST['weixinpay']) : '';
			$username = isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';
			$password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';
			$cid = $this->_cid;
			//$wecha_id = md5($tel . time());
            $userinfo = M('New_twitter_userinfo')->where(array('wecha_id' => $this->wecha_id,'cid' => $cid ,'twid' => $this->mytwid,'token' => $this->token))->find();
			D("Userinfo")->save(array('id' => $userinfo['id'], 'truename' => $truename, 'token' => $this->token, 'address' => $address, 'paypass' => md5($password), 'tel' => $tel,'username' => $username));
            if ($userinfo) {
				D("New_twitter_userinfo")->save(array('id' => $userinfo['id'], 'truename' => $truename, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'address' => $address, 'tel' => $tel, 'qq' => $qq, 'alipay' => $alipay, 'bank' => $bank, 'number' => $number, 'weixinpay' => $weixinpay,'username' => $username,'dateline' => time()));
                $uid = $userinfo['wecha_id'];
            } else {
                $uid = D("New_twitter_userinfo")->add(array('id' => $userinfo['id'], 'cid' => $cid, 'truename' => $truename,'twid' => $this->mytwid, 'token' => $this->token, 'wecha_id' => $this->wecha_id, 'address' => $address, 'tel' => $tel, 'qq' => $qq, 'alipay' => $alipay, 'bank' => $bank, 'number' => $number,'username' => $username, 'weixinpay' => $weixinpay,'dateline' => time()));
            }
			if ($uid) {
				$callbackurl = session('callbackurl');
				$this->success('修改成功', $callbackurl);
			} else {
				$this->error(D("New_twitter_userinfo")->error());
			}
		} else {
			if ($this->mytwid){
				$cid = $this->_cid;
				$userinfo = M("New_twitter_userinfo")->where(array('wecha_id' => $this->wecha_id,'cid' => $cid,'token' => $this->token))->find();
				//$userinfo = M("Userinfo")->where(array('twid' => $this->mytwid))->find();
				//dump($userinfo);
				//die;
				if ($userinfo) {
					$this->assign('remove',$userinfo);
				} else {
					$userinfo1 = M("Userinfo")->where(array('wecha_id' => $this->wecha_id,'token' => $this->token))->find();
					$this->assign('remove',$userinfo1);
				}
				//dump($userinfo1);
				$this->assign('metaTitle', '商城会员');
				$this->display(xdxsetuserinfo);
			}
		}
	}
	
	/**
	 * 分佣记录
	 */
	private function savelog($type, $twid, $token, $cid, $param = 1)
	{
		if ($twid && $token && $cid) {
			$set = M("New_twitter_set")->where(array('token' => $token, 'cid' => $cid))->find();
			if (empty($set)) return false;
			$db = D("New_twitter_log");
			// 1.点击， 2.注册会员， 3.购买商品
	// 		$twitter = $db->where(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => $type))->order("id DESC")->limit("0, 1")->find();
			if ($type == 3) {//购买商品
				$price = $set['percent'] * 0.01 * $param;
	// 			if ($twitter && (date("Ymd") == date("Ymd", $twitter['dateline']))) {
	// 				$db->where(array('id' => $twitter['id']))->save(array('param' => $param + $twitter['param'], 'price' => $twitter['price'] + $price));
	// 			} else {
					$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 3, 'dateline' => time(), 'param' => $param, 'price' => $price));
	// 			}
	//		} elseif ($type == 2) {//注册会员
	//			$price = $set['registerprice'];
	// 			if ($twitter && (date("Ymd") == date("Ymd", $twitter['dateline'])) && $twitter['param'] < $set['registermax']) {
	// 				$db->where(array('id' => $twitter['id']))->save(array('param' => $param + $twitter['param'], 'price' => $twitter['price'] + $set['registerprice']));
	// 			} else {
	//				$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 2, 'dateline' => time(), 'param' => $param, 'price' => $set['registerprice']));
	// 			}
	//		} else {//点击
	//			$price = $set['clickprice'];
	// 			if ($twitter && (date("Ymd") == date("Ymd", $twitter['dateline'])) && $twitter['param'] < $set['clickmax']) {
	// 				$db->where(array('id' => $twitter['id']))->save(array('param' => $param + $twitter['param'], 'price' => $twitter['price'] + $set['clickprice']));
	// 			} else {
	//				$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 1, 'dateline' => time(), 'param' => $param, 'price' => $set['clickprice']));
	// 			}
			}
			//统计总收入
			if ($count = M("New_twitter_count")->where(array('token' => $token, 'cid' => $cid, 'twid' => $twid))->find()) {
				D("New_twitter_count")->where(array('id' => $count['id']))->setInc('total', $price);
			} else {
				D("New_twitter_count")->add(array('twid' => $twid, 'token' => $token, 'cid' => $cid, 'total' => $price, 'remove' => 0));
			}
		}
	}
	
	/**
	 * 我的个人信息
	 */
	public function myinfo()
	{
//         if (!session('twid')) {
//             $this->redirect(U('Storenew/login', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->mytwid)));
//         }
		if ($this->mytwid) {
			$userinfo = M("Userinfo")->where(array('twid' => $this->mytwid))->find();
			
			$cid = $this->_cid;
			$this->assign('cid', $cid);
			
			$count = M("New_twitter_count")->where(array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $this->mytwid))->find();
			$total = $count['total'] - $count['remove'];
			$this->assign('total', $total);
			$this->assign('count', $count);
			$this->assign('metaTitle', '我的个人信息');
			
			//开始查询我的竞拍订单数量
			$jingpaicount = M("New_product_cart")->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id,'cid' => $cid,'jingpai'=> 1,'groupon'=> 0))->count();
			$jingpaicount = $jingpaicount ? $jingpaicount : 0;
			$this->assign('jingpaicount', $jingpaicount);
			//开始查询我的订单数量
			$dingdancount = M("New_product_cart")->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id,'cid' => $cid,'jingpai'=> 0,'groupon'=> 0))->count();
			$dingdancount = $dingdancount ? $dingdancount : 0;
			$this->assign('dingdancount', $dingdancount);
			//订单总数
			$ocount = $jingpaicount + $dingdancount;
			$this->assign('ocount', $ocount);
			
			//开始查询一级代理数量
			$fromtwidcount = M("Userinfo")->where(array('token' => $this->token, 'fromtwid' => $this->mytwid))->count();
			$fromtwidcount = $fromtwidcount ? $fromtwidcount : 0;
			$this->assign('fromtwidcount', $fromtwidcount);
			//开始查询二级代理数量
			$addtwidcount = M("Userinfo")->where(array('token' => $this->token, 'addtwid' => $this->mytwid))->count();
			$this->assign('addtwidcount', $addtwidcount);
			//发展会员总数量
			$twidcount = $fromtwidcount + $addtwidcount;
			$this->assign('twidcount', $twidcount);
			//下级订单总数量
			$count = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid,'price'=>array('gt','0')))->count();
			$this->assign('ordersCount', $count);
			
			//团购开团总量
			$allcount = M("New_product_groupon")->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id,'groupon'=> 1))->count();
			$this->assign('allcount', $allcount);
		}

        //分销商
        $distributor = array();
        $store = array();
        if (session('distributor')) {
			$distributor = M('Distributor');
			$distributor = $distributor->find(session('distributor'));

			//分销商店铺
			$store = M('Distributor_store');
			$where = array();
        	$where['did'] = session('distributor');
        	$store = $store->where($where)->find();
        }
        
        $drp_register = U('DrpUcenter/index');	
        
		$this->assign('userinfo', $userinfo);
        $this->assign('distributor', $distributor);
        $this->assign('store', $store);
        $this->assign('twid', $this->_twid);
        $this->assign('drp_register', $drp_register);
		
		//店中店开启
		$dzduserinfo = M('Userinfo')->where(array('token' => $this->token, 'twid' => $this->_twid))->find();
		$dzdinfo = M('New_dzd')->where(array('token' => $this->token, 'wecha_id' => $dzduserinfo['wecha_id'], 'cid' => $this->_cid))->find();
		if($this->_set['dzd'] == 1 && !empty($dzdinfo)){
			$this->display(xdxmyinfodzd);
		}else{
			$this->display(xdxmyinfo);
		}
	}
	
	/**
	 * 我的下级代理会员
	 */
	public function myfansDetail()
	{
		//echo $this->mytwid;die;
		$level = $this->_get('level','intval');
		
		if ($this->mytwid) {
			$offset = 10;
			$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
			$start = ($page - 1) * $offset;
			if($level == '2'){
				$log = M("userinfo")->where(array('addtwid' => $this->mytwid, 'token' => $this->token))->limit($start, $offset)->order('id DESC')->select();
				foreach ($log as $key=>$val) {
					$wei_user = M("Wechat_group_list")->where(array('openid' => $val['wecha_id']))->find();
					$wei_user2 = M("userinfo")->where(array('wecha_id' => $val['wecha_id']))->find();
					$regtime = $wei_user2['create_time'];
					$subscribetime = $wei_user['subscribe_time'];
					$log[$key]['regtime'] = $regtime?$regtime:$subscribetime;
				}
				$logcount = M("userinfo")->where(array('addtwid' => $this->mytwid, 'token' => $this->token))->order('id DESC')->count();
			}else{
				$log = M("userinfo")->where(array('fromtwid' => $this->mytwid, 'token' => $this->token))->limit($start, $offset)->order('id DESC')->select();
				foreach ($log as $key=>$val) {
					$wei_user = M("Wechat_group_list")->where(array('openid' => $val['wecha_id']))->find();
					$wei_user2 = M("userinfo")->where(array('wecha_id' => $val['wecha_id']))->find();
					$regtime = $wei_user2['create_time'];
					$subscribetime = $wei_user['subscribe_time'];
					$log[$key]['regtime'] = $regtime?$regtime:$subscribetime;
				}
				$logcount = M("userinfo")->where(array('fromtwid' => $this->mytwid, 'token' => $this->token))->order('id DESC')->count();
			}
			//$log = M("userinfo")->where(array($addtwid => $this->mytwid, 'token' => $this->token))->order('id DESC')->select();
			//$count = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->count();
			$totalpage = ceil($logcount / $offset);
			$this->assign('orders', $log);
			$this->assign('logcount', $logcount);
			$this->assign('ordersCount', $count);
			$this->assign('totalpage', $totalpage);
			$this->assign('level', $level);
			$this->assign('page', $page);
		}
		//dump($log);
		$this->assign('metaTitle', '我的推广会员列表');
		$this->display(xdxmyfansDetail);
	}
	
	/**
	 * 我的推广二维码
	 */
	public function myerweima_1()
	{
		include './weimicms/Lib/ORG/phpqrcode.php';
		$userinfo = M("Userinfo")->where(array('twid' => $this->mytwid))->find();
		$erweima = M("New_twitter_usererweima")->where(array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $this->mytwid))->find();
		$mytwid = $this->mytwid;
		$token = $this->token;
		$cid = $this->_cid;
		$twid = $this->mytwid;
		$url = $this->siteUrl . '/index.php?g=Wap&m=Storenew&a=index&token=' .$token. '&cid=' .$cid. '&twid=' .$twid;
		QRcode::png($url, false, 1, 11);
	}
	public function myerweima()
	{
		//echo $this->mytwid;die;
			$userinfo = M("Userinfo")->where(array('twid' => $this->mytwid))->find();
			$erweima = M("New_twitter_usererweima")->where(array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $this->mytwid))->find();
			$mytwid = $this->mytwid;

			/*if ($erweima == false) {
				include './weimicms/Lib/ORG/phpqrcode.php';
				$host = $_SERVER['HTTP_HOST'];
				$value = 'http://'.$host.'/index.php?g=Wap&m=Storenew&a=index&token='.$this->token.'&cid='.$this->_cid.'&twid='.$this->mytwid.'';
				//二维码内容
				
				//$value = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; 
				$errorCorrectionLevel = 'L';//容错级别   
				$matrixPointSize = 4;//生成图片大小
				$imgurl = './uploads/erweima/shareerweima/share'.$mytwid.'.png';
				//生成二维码图片   
				QRcode::png($value, $imgurl, $errorCorrectionLevel, $matrixPointSize, 2);   
				$logo = $imgurl;//准备好的logo图片   
				$QR = './uploads/erweima/shareimg/share.jpg';//已经生成的原始二维码图

				if ($logo !== false) {   
					$QR = imagecreatefromstring(file_get_contents($QR));   
					$logo = imagecreatefromstring(file_get_contents($logo));   
					$QR_width = imagesx($QR);//二维码图片宽度   
					$QR_height = imagesy($QR);//二维码图片高度   
					$logo_width = imagesx($logo);//logo图片宽度   
					$logo_height = imagesy($logo);//logo图片高度   
					$logo_qr_width = $QR_width / 2;
					$scale = $logo_width/$logo_qr_width;   
					$logo_qr_height = $logo_height/$scale;
					$from_width = ($QR_width - $logo_qr_width) / 2;
					$from_height = 280;
					//重新组合图片并调整大小   
					imagecopyresampled($QR, $logo, $from_width, $from_height, 0, 0, $logo_qr_width,   
					$logo_qr_height, $logo_width, $logo_height);   
				}   
				//输出图片   
				imagepng($QR, './uploads/erweima/shareimg/share'.$mytwid.'.png');
				
				//写入数据库
				$data['token'] = $this->token;
				$data['cid'] = $this->_cid;
				$data['twid'] = $this->mytwid;
				$data['wecha_id'] = $userinfo['wecha_id'];
				$data['imgurl'] = '/uploads/erweima/shareimg/share'.$mytwid.'.png';
				$data['is_get'] = '1';
				D('New_twitter_usererweima')->add($data);
			}*/
		$token = $this->token;
		$cid = $this->_cid;
		$twid = $this->mytwid;
		$this->assign('erweima', $erweima);
		$this->assign('metaTitle', '我的推广二维码');
		$this->display(xdxmyerweima);
	}
	
	/**
	 * 销售总排行榜
	 */
	public function phb()
	{
		//echo $this->mytwid;die;
		if ($this->mytwid) {
			$offset = 5;
			$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
			$start = ($page - 1) * $offset;
			
			$log = M("userinfo")->where(array('token'=>$this->token))->order('twid desc')->limit(15)->select();
			foreach ($log as $key=>$val) {
				$pgb[$key] = M("New_twitter_log")->where(array('twid' => $val['twid'], 'token' => $this->token))->count();
				$log[$key]['nums'] = $pgb[$key];
			}
			$totalpage = ceil($count / $offset);
			$this->assign('orders', $log);
			$this->assign('logcount', $logcount);
			$this->assign('ordersCount', $count);
			$this->assign('totalpage', $totalpage);
			$this->assign('page', $page);
		}
		//dump($log);
		$this->assign('metaTitle', '佣金获取记录');
		$this->display(xdxphb);
	}
	
	/**
	 * 商城说明介绍
	 */
	public function help()
	{
		//echo $this->mytwid;die;
		if ($this->mytwid) {
			$help = M("New_twitter_help")->where(array('token'=>$this->token,'cid'=>$this->_cid))->find();
			$note = $help['note'];
			$this->assign('note', $note);
		}
		//dump($help);
		$this->assign('metaTitle', '商城说明介绍');
		$this->display(xdxhelp);
	}

	
	/**
	 * 佣金的获取记录
	 */
	public function detail()
	{
// 		echo $this->mytwid;die;
		if ($this->mytwid) {
			$offset = 5;
			$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
			$start = ($page - 1) * $offset;
			$log = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->limit($start, $offset)->order('id DESC')->select();
			$count = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->count();
			$totalpage = ceil($count / $offset);
			$this->assign('orders', $log);
			$this->assign('ordersCount', $count);
			$this->assign('totalpage', $totalpage);
			$this->assign('page', $page);
		}
		$this->assign('metaTitle', '佣金获取记录');
		$this->display(xdxdetail);
	}
	
	/**
	 * 佣金的获取记录
	 */
	public function fansorder()
	{
// 		echo $this->mytwid;die;
		if ($this->mytwid) {
			$offset = 10;
			$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
			$start = ($page - 1) * $offset;
			$log = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->limit($start, $offset)->order('id DESC')->select();
			foreach ($log as $key=>$val) {
				$user = M("userinfo")->where(array('wecha_id' => $val['wecha_id'], 'token' => $this->token))->find();
				$log[$key]['wechaname'] = $user['wechaname'];
				$log[$key]['mytwid'] = $user['twid'];
			}

			$count = M("New_twitter_log")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->count();
			
			$totalpage = ceil($count / $offset);
			$this->assign('orders', $log);
			$this->assign('ordersCount', $count);
			$this->assign('totalpage', $totalpage);
			$this->assign('page', $page);
		}
		$this->assign('metaTitle', '佣金获取记录');
		$this->display(xdxfansorder);
	}
	
	/**
	 * 提现记录
	 */
	public function remove()
	{
		if ($this->mytwid) {
			$offset = 5;
			$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
			$start = ($page - 1) * $offset;
			$log = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->limit($start, $offset)->order('id DESC')->select();
			$count = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->count();
			$myremove = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid, 'status' => '0'))->find();
			$mycount = M("New_twitter_count")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->find();
			$totalpage = ceil($count / $offset);
			$total = $mycount['total']-$mycount['remove']-$myremove['price'];
			$this->assign('orders', $log);
			$this->assign('ordersCount', $count);
			$this->assign('count', $mycount);
			$this->assign('totalpage', $totalpage);
			$this->assign('total', $total);
			$this->assign('page', $page);
		}
		$this->assign('metaTitle', '我的个人信息');
		$this->display(xdxremove);
	}
	
	public function logout()
	{
		session('twid', null);
		session('login', null);
		session('twitter_save', null);
		$this->redirect(U('Storenew/cats',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
	}
	
	public function setre()
	{	
		if ($this->mytwid) {
			$userinfo = M("Userinfo")->where(array('twid' => $this->mytwid))->find();
			$remove = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid, 'status' => 0))->find();
			$count = M("New_twitter_count")->where(array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $this->mytwid))->find();
			$total = $count['total'] - $count['remove'] - $remove['price'];
			$this->assign('remove', $remove);
			$this->assign('total', $total);
			$this->assign('count', $count);
			$this->assign('metaTitle', '我的个人信息');
			
		}
		$this->display(xdxsetre);
	}
	
	/**
	 * 提现请求
	 */
	public function setremove()
	{
		if ($this->mytwid) {
			$count = M("New_twitter_count")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid))->find();
			$remove = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid, 'status' => 0))->find();
			$total = $count['total'] - $count['remove'];
			$shengyu = $count['total'] - $count['remove'] - $remove['price'];
			$this->assign('shengyu', $shengyu);
			$this->assign('total', $total);
			$this->assign('count', $count);
			$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
			$tel = isset($_POST['tel']) ? htmlspecialchars($_POST['tel']) : '';
			$alipay = isset($_POST['alipay']) ? htmlspecialchars($_POST['alipay']) : '';
			$weixinpay = isset($_POST['weixinpay']) ? htmlspecialchars($_POST['weixinpay']) : '';
			$number = isset($_POST['number']) ? htmlspecialchars($_POST['number']) : '';
			$bank = isset($_POST['bank']) ? htmlspecialchars($_POST['bank']) : '';
			$address = isset($_POST['address']) ? htmlspecialchars($_POST['address']) : '';
			$price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : 0;
			$data = array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $this->mytwid, 'name' => $name, 'alipay' => $alipay, 'weixinpay' => $weixinpay, 'tel' => $tel, 'number' => $number, 'bank' => $bank, 'address' => $address, 'price' => $price);
			if (IS_POST) {
				if ($shengyu < $price) $this->error("请不要贪心，您现在还没有{$price}元钱供你提现");
				if (empty($name)) $this->error("提款人姓名不能为空");
				if (empty($number)) $this->error("提款人账号不能为空");
				if (empty($bank)) $this->error("提款银行名称不能为空");
				if ($remove) {
					D('New_twitter_remove')->where(array('id' => $remove['id']))->save($data);
				} else {
					$data['dateline'] = time();
					$data['status'] = 0;
					D('New_twitter_remove')->add($data);
				}
				$this->success('提现提交成功',U('Storenew/setre',array('token' => $this->token,'wecha_id' => $this->wecha_id,'cartid' => $cartid, 'twid' => $this->_twid)));
				die;
			} else {
				if (empty($remove)) {
					$remove = M("New_twitter_remove")->where(array('twid' => $this->mytwid, 'token' => $this->token, 'cid' => $this->_cid, 'status' => 1))->order('id DESC')->limit('0, 1')->find();
					$remove['price'] = 0;
				}
				$this->assign('remove', $remove);
			}
			
		}
		$this->assign('metaTitle', '填写提现信息');
		$this->display(xdxsetremove);
	}

    //检测店铺名称
    public function check_name()
    {
        $store = M('Distributor_store');

        $name = $this->_post('name', 'trim'); //店铺名称

        $store = $store->where(array('name' => $name))->find();
        if ($store) {
            echo false;
        } else {
            echo true;
        }
        exit;
    }
	
	//竞拍物品详情
	public function biddingproduct() 
	{
		$this->product_jingjia_model = M("New_product_jingpai");
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$now = time();
		$this->assign('now', $now);
		$where = array('token' => $this->token, 'id' => $id);
		$product = $this->product_jingjia_model->where($where)->find();

		if (empty($product)) {
			$this->redirect(U('Storenew/jingpai',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		
		$product['intro'] = isset($product['intro']) ? htmlspecialchars_decode($product['intro']) : '';
		$this->assign('product', $product);
		if ($product['endtime']){
			$leftSeconds = intval($product['endtime'] - time());
			$this->assign('leftSeconds', $leftSeconds);
		}

		$productimage = M("New_product_jingpai_image")->where(array('pid' => $product['id'],'image'=>array('neq','')))->select();
		//dump($productimage);
		//die;
		$this->assign('imageList', $productimage);
		$this->assign('metaTitle', $product['name']);
		
		$allcount = M('New_product_jingpai_user')->where(array('token' => $this->token, 'cid' => $this->_cid, 'pid' => $product['id']))->count();
		$this->assign('allcount', $allcount);
		
		//竞拍记录列表
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $id, 'is_jingpai' => 1);
		$product_model = M("New_product_jingpai_user");
		$jingpailog = $product_model->where($where)->order('id desc')->limit(100)->select();
			foreach ($jingpailog as $key=>$val) {
				$userinfo = M("Userinfo")->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->field('truename,portrait')->find();
				$jingpailog[$key]['truename'] = $userinfo['truename'] ? $userinfo['truename'] : '匿名';
				$jingpailog[$key]['portrait'] = $userinfo['portrait'] ? $userinfo['portrait'] : '';
				$jingpailog[$key]['counts'] = $product_model->where(array('token' => $this->token, 'cid' => $cid, 'wecha_id' => $val['wecha_id'], 'is_jingpai' => 1))->order('id desc')->count();
				if($jingpailog[$key]['price'] == $product['price']){
					$jingpailog[$key]['is_frist'] = '1';
				}else{
					$jingpailog[$key]['is_frist'] = '2';
				}
			}
		$this->assign('log', $jingpailog);
		
		//竞拍评论列表
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $id, 'is_ok' => 1);
		$comment = M("New_product_jingpai_comment");
		$jingpaicomment = $comment->where($where)->order('id desc')->limit(20)->select();
			foreach ($jingpaicomment as $key=>$val) {
				$userinfo = M("Userinfo")->where(array('token' => $this->token, 'wecha_id' => $val['wecha_id']))->field('truename,portrait')->find();
				$jingpaicomment[$key]['truename'] = $userinfo['truename'] ? $userinfo['truename'] : '匿名';
				$jingpaicomment[$key]['portrait'] = $userinfo['portrait'] ? $userinfo['portrait'] : '';
			}
		$this->assign('comment', $jingpaicomment);
		$commentcount = M('New_product_jingpai_comment')->where(array('token' => $this->token, 'cid' => $this->_cid, 'pid' => $product['id']))->count();
		$this->assign('commentcount', $commentcount);
		
		//竞拍胜出者信息
		$wecha_id = $this->wecha_id;
		$who = array('token' => $this->token, 'cid' => $cid, 'pid' => $id);
		$product_jingpai_user_model = M("New_product_jingpai_user");
		$user = $product_jingpai_user_model->where($who)->order('id desc')->find();
		if($user['wecha_id'] == $wecha_id){
			$wechaid = '1';
			$this->assign('wechaid', $wechaid);
			$this->assign('wecha_id', $wecha_id);
		}
		
		//dump($wecha_id);
		//die;
		$this->display(xdxbiddingproduct);
	}
	
	//竞拍出价
	public function jpmyprice(){
		if (IS_POST) {
			$_POST['wecha_id'] = $this->wecha_id;
			$_POST['token'] = $this->token;
			$_POST['cid'] = $this->_cid;
			$_POST['pid'] = $this->_post('pid', 'trim');
			$_POST['price'] = round($_POST['price'],2);
			//dump($_POST);
			//die;
			$product = M('New_product_jingpai')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $_POST['pid']))->find();
			$where = array('token' => $this->token, 'cid' => $this->_cid, 'pid' => $_POST['pid']);
			$product_jingpai_user_model = M("New_product_jingpai_user");
			$user = $product_jingpai_user_model->where($where)->order('id desc')->find();

			//领先时，再次出价
			if($user['wecha_id'] == $_POST['wecha_id']){
				$is_error = '1';
				$this->error("你的出价已经领先了，无需再次出价");
				die;
			}
			//用户不存在
			if(empty($this->wecha_id)){
				$is_error = '1';
				$this->error("请先登录，再出价吧");
				die;
			}
			//出价低于要求
			if($_POST['price'] < ($product['price'] + $product['increase'])){
				$is_error = '1';
				$this->error('你的出价过低，当前价格为'.$product['price'].'元，每次加价幅度必须大于'.$product['increase'].'元');
				die;
			}
			//活动时间到了，不能再次出价了
			if($product['endtime'] < time()){
				$is_error = '1';
				$this->error('竞拍已经结束！请参与下期其他商品的竞拍');
				die;
			}
			//活动还没开始
			if($product['starttime'] > time()){
				$is_error = '1';
				$this->error('竞拍还木有开始！请耐心等待');
				die;
			}
			//出价不得大于20W
			if($_POST['price'] > '200000'){
				$is_error = '1';
				$this->error('你的出价'.$_POST['price'].'元过于离谱，我们决定将你送到火星去玩！');
				die;
			}
			//每次出价不得大于100
			if(($_POST['price']-$product['price']) > '101' && $product['increase'] < '101'){
				$is_error = '1';
				$this->error('你的出价'.$_POST['price'].'元，有点过多了，建议减小幅度！');
				die;
			}
			//出价成功
			if($is_error != '1'){
				$_POST['dateline'] = time();
				$_POST['is_jingpai'] = 1;
				D('New_product_jingpai_user')->add($_POST);
				$id = $_POST['pid'];
				$now = time();
				//判断竞拍结束时间，如果小于X分钟，每次出价，增加1分钟，防止最后秒杀
				if($product['is_zj'] == 1 && ($product['endtime']-$now) < $product['lasttime']){
					$_POST['endtime'] = $product['endtime']+$product['zjtime'];
					$is_zj = 1;
				}
				D('New_product_jingpai')->where(array('token' => $this->token, 'cid' => $this->_cid,'id' => $id))->field('price')->save($_POST);
				if($is_zj == 1){
				$this->success('出价成功，目前你的出价'.$_POST['price'].'元暂时领先！竞拍马上结束了，给'.$product['zjtime'].'秒，召唤你的小伙伴一起来竞拍吧！',U('Storenew/biddingproduct',array('token' => $this->token,'wecha_id' => $this->wecha_id,'cid' => $cid, 'id' => $id)));
				}else{
				$this->success('出价成功，目前你的出价'.$_POST['price'].'元暂时领先！',U('Storenew/biddingproduct',array('token' => $this->token,'wecha_id' => $this->wecha_id,'cid' => $cid, 'id' => $id)));	
				}
				die;
			}
			
		}else{
			$this->error("未知错误");
		}
	}
	//竞拍列表
	public function jingpai() 
	{
		//if (isset($_G['cid']))
		$now = time();
		$xianshi_now = $now-272800;
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0,'endtime'=>array('gt',$xianshi_now));
		//dump($now);
		//die;
		$this->product_jingpai_model = M('New_product_jingpai');
		$this->product_jingpai_user_model = M('New_product_jingpai_user');

		$count = $this->product_jingpai_model->where($where)->count();
		$this->assign('count', $count); 
		
        	
		$jingpai = $this->product_jingpai_model->where($where)->order("endtime DESC,sort ASC")->select();
		foreach ($jingpai as $key=>$val) {
			$logcount = $this->product_jingpai_user_model->where(array('token' => $this->token, 'cid' => $this->_cid, 'pid'=>$val['id']))->count();
			$jingpai[$key]['logcount'] = $logcount;
		}

		
		$this->assign('now', $now);
		$this->assign('products', $jingpai);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "限时竞拍";
		$this->assign('metaTitle', $name);
		$this->display(xdxjingpai);
	}
	
	//竞拍评论保存
	public function jingpaicommentSave()
	{
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cid = isset($_POST['cid']) ? intval($_POST['cid']) : 0;
		
		$data = array();
		$comment = D("New_product_jingpai_comment");
		$data['is_ok'] = '1';
		$data['pid'] = $pid;
		$data['cid'] = $cid;
		$data['content'] = htmlspecialchars($_POST['content']);
		$data['token'] = $this->token;
		$data['wecha_id'] = $wecha_id;
		$data['__hash__'] = $_POST['__hash__'];
		$data['dateline'] = time();
		//dump($data);
		//die;
		
		if(empty($data['content'])){
			$this->error('缺少评论内容，请认真填写！');
		}
		
		if (false !== $comment->create($data)) {
			unset($data['__hash__']);
			$comment->add($data);
			$this->success("评论成功", U('Storenew/biddingproduct',array('token' => $this->token,'wecha_id' => $this->wecha_id,'id'=>$pid,'cid' => $cid, 'twid' => $this->_twid)));
		} else {
			$this->error($comment->error, U('Storenew/biddingproduct',array('token' => $this->token,'wecha_id' => $this->wecha_id,'id'=>$pid,'cid' => $cid, 'twid' => $this->_twid)));
		}
	}
	
	/**
	 * 竞拍成功的会员手机端自己生成竞拍订单
	 */
	public function addjingpaiorder()
	{
		$token = $this->token;
		$product_user_model = M('New_product_jingpai_user');
		$product_model = M('New_product_jingpai');
		$Product_cart = M('New_product_cart');
		$Product_cart_list = M('New_product_cart_list');
		if($this->_get('token')!=$token){$this->error('非法操作');}
		$id = $this->_get('id');
		$wecha_id = $this->_get('wecha_id');
		$now = time();
        if (IS_GET) {                              
            $where = array('token'=>$token, 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			$whereuser = array('token'=>$token, 'cid' => $this->_cid , 'pid'=>$id);
			$checkuser = $product_user_model->where($whereuser)->order('id desc')->find();
			
			$order['token'] = $check['token'];
			$order['cid'] = $check['cid'];
			$order['paymode'] = 1;
			$order['price'] = $check['price'];
			$order['oprice'] = $check['price'];
			$order['vprice'] = $check['price'];
			$order['totalprice'] = $check['price'];
			$order['wecha_id'] = $checkuser['wecha_id'];
			$order['total'] = 1;
			$order['pid'] = $check['id'];
			$order['time'] = $now;
			$order['orderid'] = $orderid = date("YmdHis") . rand(100000, 999999);
			$order['jingpai'] = 1;
			
			//写入订单测试
			$orderinfo = array($order['pid']=>array(''.$check['id'].''=> '1|'.$check['price'].''));
			$order['info'] = serialize($orderinfo);
			
			//判断用户是否和出价者一致
			if ($checkuser['wecha_id'] != $wecha_id){
				$this->error('错误操作！');
			}
			
			//生成订单判断
			if ($check['is_order'] == '1'){
				$this->error('已经生成，无需再次生成');
			}else{
				D("New_product_jingpai")->where(array('token' => $this->token, 'cid' => $order['cid'], 'id'=>$id))->save(array('is_order' => '1'));
			}
			
            if ($check == false) $this->error('错误操作，无信息');
			//写入订单
            $cart = $Product_cart->add($order);

            if ($cart == true) {
            	//$count = $product_model->where(array('cid' => $check['cid']))->count();
				
				//发送订单状态更新的模版消息
				$myhost = $_SERVER['HTTP_HOST'];
				$model = new templateNews();
				$model->sendTempMsg('TM00017', array('href' => 'http://'.$myhost.'/index.php?g=Wap&m=Storenew&a=myjingpaiDetail&token='.$order['token'].'&cartid='.$cart.'&wecha_id='.$order['wecha_id'].'&cid='.$order['cid'].'', 'wecha_id' => $order['wecha_id'], 'first' => '您好！'.$user['truename'].'，你参与的竞拍成功胜出', 'OrderSn' => $order['orderid'], 'OrderStatus' =>'未付款' , 'remark' => '本次订单金额：'.$order['price'].'元，请及时付款，点击查看详情！'));
				
				//测试消息服务
				$where=array('token'=>$this->token);
				$this->thisWxUser=M('Wxuser')->where($where)->find();
				$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
				$access_token=json_decode($this->curlGet($url_get));
				$a = $access_token->access_token;
				//客服接口，24小时内发送过内容的用户才有
				//$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$a;
				//消息预览接口
				$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$a;
				//获取用户的名字
				$where3=array('token'=>$order['token'],'wecha_id'=>$order['wecha_id']);
				$user=M('Userinfo')->where($where3)->field('truename')->find();
				//获取商家微信的openID
				$where2=array('token'=>$order['token'],'cid'=>$order['cid']);
				$sendwecha_id=M('New_product_set_reply')->where($where2)->field('wecha_id')->find();
				//开始发送消息到商家微信
				$data = '{"touser":"'.$sendwecha_id['wecha_id'].'","msgtype":"text", "text":{"content":"您的用户：'.$user['truename'].'参与竞拍【'.$check['name'].'】成功并生成订单信息，订单金额：'.$order['price'].'元。（当您看到此信息表示用户已经自己生成了竞拍的订单）"}}';
				$this->postCurl($url,$data);
				//
				
				//写入购物车列表
				$order['cartid'] = $cart;
				$order['productid'] = $check['id'];
            	$cart_list = $Product_cart_list->add($order);
                $this->success('竞拍订单已生成，请及时付款', U('Storenew/biddingproduct', array('token' => $token, 'cid'=>$cid, 'id'=>$pid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/biddingproduct', array('token'=>$token,'cid'=>$cid, 'id'=>$pid)));
            }
        }
	}
	
	//新闻列表
	public function news()
	{
		//if (isset($_G['cid']))
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0);
		
		$this->product_news_model = M('New_product_news');
		
		$count = $this->product_news_model->where($where)->count();
		$this->assign('count', $count); 
		
		$offset = 10;
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$start = ($page - 1) * $offset;
		$totalpage = ceil($count / $offset);
        	
		$news = $this->product_news_model->where($where)->order('id desc')->limit($start, $offset)->select();
		//dump($news);
		//die;
		$this->assign('ordersCount', $count);
		$this->assign('totalpage', $totalpage);
		$this->assign('page', $page);
			
		$this->assign('news', $news);
		$this->display(xdxnews);
	}
	//新闻详情
	public function views()
	{
		//if (isset($_G['cid']))
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0, 'id'=>$id);
		$this->product_news_model = M('New_product_news');
		$news = $this->product_news_model->where($where)->find();
		$where2 = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0);
		$newslist = $this->product_news_model->where($where2)->order("id DESC")->limit('0, 5')->select();
		//dump($news);
		//die;
		$this->assign('id', $id);
		$this->assign('news', $news);
		$this->assign('newslist', $newslist);
		$this->display(xdxnewsviews);
	}
	
	//店中店设置
	public function dzdset()
	{
		$dzd = M('New_dzd')->where(array('token' => $this->token, 'cid' => $this->_cid, 'wecha_id' => $this->wecha_id))->find();
		$userinfo = M('Userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		if (IS_POST) {
			$_POST['token'] = $this->token;
			$_POST['cid'] = $this->_cid;
			$_POST['wecha_id'] = $this->wecha_id;
			$_POST['title'] = htmlspecialchars($_POST['title']);
			$_POST['info'] = htmlspecialchars($_POST['info']);
			$_POST['truename'] = htmlspecialchars($_POST['truename']);
			$_POST['tel'] = htmlspecialchars($_POST['tel']);
			$_POST['qq'] = htmlspecialchars($_POST['qq']);
			unset($_POST['id']);
			if ($dzd) {
				$where = array('token' => $this->token, 'cid' => $this->_cid, 'id' => $dzd['id']);
				$t = D('New_dzd')->where($where)->save($_POST);
				if ($userinfo) {
					$where = array('token' => $this->token, 'wecha_id' => $_POST['wecha_id']);
					D("Userinfo")->where($where)->save(array('wecha_id' => $_POST['wecha_id'],'truename' => $_POST['truename'], 'token' => $this->token, 'tel' => $_POST['tel'],'qq' => $_POST['qq']));
				}
				
				if ($t) {
					$this->success('修改成功');
				} else {
					$this->error('操作失败');
				}
			} else {
				$_POST['addtime'] = time();
				$tid = D('New_dzd')->add($_POST);
				if ($userinfo) {
					$where = array('token' => $this->token, 'wecha_id' => $_POST['wecha_id']);
					D("Userinfo")->where($where)->save(array('wecha_id' => $_POST['wecha_id'],'truename' => $_POST['truename'], 'token' => $this->token, 'tel' => $_POST['tel'],'qq' => $_POST['qq']));
				}
				if ($tid) {
					$this->success('创建成功');
				} else {
					$this->error('创建失败');
				}
			}
		} else {
		//dump($userinfo);
		//die;
			$this->assign('set', $dzd);
			$this->assign('user', $userinfo);
			$this->display(xdxdzdset);
		}
	}
	
	//团购列表
	public function groupon() 
	{
		//if (isset($_G['cid']))
		$now = time();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0, 'is_tg' => 1);
		//dump($now);
		//die;
		$this->product_jingpai_model = M('New_product');

		$count = $this->product_jingpai_model->where($where)->count();
		$this->assign('count', $count); 
		
        	
		$jingpai = $this->product_jingpai_model->where($where)->order("id DESC,sort ASC")->select();
		foreach ($jingpai as $key=>$val) {
			$img = M('New_product_image')->where(array('token' => $this->token, 'cid' => $this->_cid, 'pid'=>$val['id']))->find();
			$jingpai[$key]['topimg'] = $img['image'];
		}
		//dump($jingpai);
		//die;
		
		$this->assign('now', $now);
		$this->assign('products', $jingpai);
		$name = isset($thisCat['name']) ? $thisCat['name'] . '列表' : "分享团购";
		$this->assign('metaTitle', $name);
		$this->display(xdxgroupon);
	}
	//团购单品详情
	public function grouponview() 
	{
		$codeid = $_GET['codeid'];
		$this->product_model = M('New_product');
		$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
		$where = array('token' => $this->token, 'id' => $id);
		$product = $this->product_model->where($where)->find();
		if (empty($product)) {
			$this->redirect(U('Storenew/groupon',array('token' => $this->token,'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		
		$product['intro'] = isset($product['intro']) ? htmlspecialchars_decode($product['intro']) : '';
		$this->assign('product', $product);
		if ($product['endtime']){
			$leftSeconds = intval($product['endtime'] - time());
			$this->assign('leftSeconds', $leftSeconds);
		}
        

		$productimage = M("New_product_image")->where(array('pid' => $product['id']))->select();
		
		$this->assign('imageList', $productimage);
		$this->assign('metaTitle', $product['name']);
		
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $id, 'isdelete' => 0);
		$product_model = M("New_product_comment");
		$score      = $product_model->where($where)->sum('score');
		$count      = $product_model->where($where)->count();
		$comment = $product_model->where($where)->order('id desc')->limit("0, 10")->select();
		foreach ($comment as &$com) {
			$com['wecha_id'] = $com['truename'];
		}
		
		$percent = "100%";
		if ($count) {
			$score = number_format($score / $count, 1);
			$percent =  number_format($score / 5, 2) * 100 . "%";
		}
		$totalPage = ceil($count / 10);
		$page = $totalPage > 1 ? 2 : 0;
		
		$this->assign('score', $score);
		$this->assign('num', $count);
		$this->assign('codeid', $codeid);
		$this->assign('page', $page);
		$this->assign('comment', $comment);
		$this->assign('percent', $percent);
		$this->display(xdxgrouponview);
	}
	
	//团购分享好友详情
	public function grouponshare() 
	{
		$codeid = $_GET['codeid'];
		$wecha_id = $this->wecha_id;
		$count =  M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'code'=>$codeid))->count();
		$paidcount =  M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'code'=>$codeid, 'paid'=>'1'))->count();
		
		$groupon = M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid,'code'=>$codeid))->order('id ASC')->select();
			foreach ($groupon as $key=>$val){
				$where = array('token' => $this->token, 'wecha_id' => $val['wecha_id']);
				$user = M("Userinfo")->where($where)->find();
				$groupon[$key]['truename'] = $user['truename'];
				$groupon[$key]['portrait'] = $user['portrait'];
			}
		$grouponinfo = M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'code'=>$codeid))->order('id ASC')->limit(0,1)->find();
		$product = M('New_product')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'id'=>$grouponinfo['pid']))->find();
		$shengyu = $product['tgnum'] - $count;
		
		$now = time();
		$deltime = $now-$grouponinfo['addtime'];
		
		if($deltime > ($product['tgend']*86400)){
			$this->delgrouponorder($this->token,$product['tgend'],$product['id'],$codeid);
			$gsave = M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'code'=>$codeid))->save(array('status'=>2));
			//dump($gsave);
			//die;
		}
		
		//dump($groupon);
		//die;
		$this->assign('p', $product);
		$this->assign('g', $groupon);
		$this->assign('glist', $groupon);
		$this->assign('grouponinfo', $grouponinfo);
		$this->assign('paidcount', $paidcount);
		$this->assign('count', $count);
		$this->assign('wecha_id', $wecha_id);
		$this->assign('codeid', $codeid);
		$this->assign('shengyu', $shengyu);
		$this->assign('metaTitle', '开团抢购');
		$this->display(xdxgrouponshare);
	}
	
	//团购玩法
	public function grouponrule() 
	{
		$this->display(xdxgrouponrule);
	}
	
	//团购我的团列表
	public function grouponmy() 
	{
		$list = $this->_get('list');
		$offset = 5;
		$page = isset($_GET['page']) ? max(intval($_GET['page']), 1) : 1;
		$start = ($page - 1) * $offset;
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$New_product_groupon = M('New_product_groupon');
		$where = array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cid' => $this->_cid);
		if($list == '1'){
			$where['paid'] = '0';
		}elseif($list == '2'){
			$where['paid'] = '1';
		}elseif($list == '3'){
			$where['openid'] = $this->wecha_id;
		}
		
		$now = time();
		
		$orders = $New_product_groupon->where($where)->limit($start, $offset)->order('addtime DESC')->select();
			foreach ($orders as $key=>$o){
				$orders[$key]['codeid'] = $orders[$key]['code'];
				$product = M('New_product')->where(array('token' => $this->token,'id' => $o['pid'], 'cid'=>$o['cid']))->find();
				$cart = M('New_product_cart')->where(array('token' => $this->token,'orderid' => $o['orderid'], 'cid'=>$o['cid']))->find();
				$orders[$key]['logourl'] = $product['logourl'];
				$orders[$key]['name'] = $product['name'];
				$orders[$key]['tgprice'] = $product['tgprice'];
				$orders[$key]['sent'] = $cart['sent'];
				$orders[$key]['logistics'] = $cart['logistics'];
				$orders[$key]['logisticsid'] = $cart['logisticsid'];
				$deltime = $now-$o['addtime'];
				$codeid = $orders[$key]['codeid'];
				if($deltime > ($product['tgend']*86400)){
					$this->delgrouponorder($this->token,$product['tgend'],$product['id'],$codeid);
					$gsave = M('New_product_groupon')->where(array('token' => $this->token, 'cid'=>$this->_cid, 'code'=>$codeid))->save(array('status'=>2));
					//dump($gsave);
					//die;
				}
			}
		$count = $New_product_groupon->where($where)->count();
		//dump($cart);
		//die;
		$totalpage = ceil($count / $offset);
		$this->assign('orders', $orders);
		$this->assign('ordersCount', $count);

		$this->assign('totalpage', $totalpage);
		$this->assign('page', $page);
		$this->assign('metaTitle', '我的团购');
		
		//是否要支付
		$alipayConfig = M('Alipay_config')->where(array('token' => $this->token))->find();
		$this->assign('alipayConfig',$alipayConfig);
		
		if($list == '1'){
			$this->display(xdxmygroupon);
		}elseif($list == '2'){
			$this->display(xdxmygroupon);
		}elseif($list == '3'){
			$this->display(xdxgrouponmy);
		}else{
			$this->display(xdxmygroupon);
		}
	}
	
	/**
	 * 竞拍成功的会员手机端自己生成竞拍订单
	 */
	public function addgrouponorder()
	{
		$token = $this->token;
		$product_groupon_model = M('New_product_groupon');
		$product_model = M('New_product');
		$Product_cart = M('New_product_cart');
		$Product_cart_list = M('New_product_cart_list');
		if($this->_get('token')!=$token){$this->error('非法操作');}
		$id = $this->_get('id');
		$code = $this->_get('codeid');
		if(empty($code)){
			 $code = $this->getKey();
		}
		$wecha_id = $this->wecha_id;
		$now = time();
        if (IS_GET) {                              
            $where = array('token'=>$token, 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			$whereuser = array('token'=>$token, 'cid' => $this->_cid , 'pid'=>$id,'code'=>$code);
			
			$checkuser = $product_groupon_model->where($whereuser)->order('id ASC')->find();
			$order['token'] = $check['token'];
			$order['cid'] = $check['cid'];
			$order['paymode'] = 1;
			$order['price'] = $check['tgprice'];
			$order['oprice'] = $check['tgprice'];
			$order['vprice'] = $check['tgprice'];
			$order['totalprice'] = $check['tgprice'];
			$order['wecha_id'] = $this->wecha_id;
			$order['total'] = 1;
			$order['pid'] = $check['id'];
			$order['time'] = $now;
			$order['orderid'] = $orderid = date("YmdHis") . rand(100000, 999999);
			$order['groupon'] = 1;
			if (empty($checkuser)){
				$order['openid'] = $this->wecha_id;
			}else{
				$order['openid'] = $checkuser['openid'];
			}
			
			$deltime = $now-$checkuser['addtime'];
			
			if ($checkuser){
				//判断此团时间结束没
				if ($checkuser['addtime'] < ($deltime/86400)){
					$this->error('此团购已经结束，你还是去另开新团吧！');
				}
				
				//判断此团是否失败
				if ($checkuser['status'] == 2){
					$this->error('此团购已经失败了，你还是去另开新团吧！');
				}
			}
			
			//写入订单测试
			//$orderinfo = array($order['pid']=>array('1'=> array('count'=>'1','price'=>$check['tgprice'])));
			$orderinfo = array($order['pid']=>array(''.$check['id'].''=> '1|'.$check['tgprice'].''));
			$order['info'] = serialize($orderinfo);
			
			$whereus = array('token'=>$token, 'cid' => $this->_cid , 'pid'=>$id, 'code'=>$code, 'wecha_id'=>$this->wecha_id);
			$checkus = $product_groupon_model->where($whereus)->order('id desc')->find();
			
			$checkcount = $product_groupon_model->where($whereuser)->order('id desc')->count();
			//判断团购人数时候达到限制
			if ($checkcount >= $check['tgprice']){
				$this->error('此团人数已经满团，请另新开团！');
			}
			
			//判断此团是否是同一用户
			if ($checkus['openid'] == $this->wecha_id){
				$this->error('此团是你开，此树是你载，你不能再开团了！');
			}
			
			//判断用户是否参与过此团
			if ($checkus['wecha_id'] == $this->wecha_id){
				$this->error('你已经购买过了！');
			}
			
			$whereu = array('token'=>$token, 'cid' => $this->_cid , 'pid'=>$id, 'openid'=>$this->wecha_id);
			$checku = $product_groupon_model->where($whereu)->order('id desc')->find();
			//判断用户是否已经开团，防止多次开团
			if ($checku['openid'] == $this->wecha_id){
				$this->error('你已经开团了，未付款购买，不能再次开团');
			}
			
			//dump($checkus);
			//die;	
            if ($check == false) $this->error('错误操作，无信息');
			//写入订单
            $cart = $Product_cart->add($order);
			//写入团购列表
			if($cart){
				$order['cartid'] = $cart;
				$order['productid'] = $check['id'];
				$order['code'] = $code;
				$order['addtime'] = $now;
			}
			$groupon = $product_groupon_model->add($order);

            if ($cart == true) {
				//写入购物车列表
				$order['cartid'] = $cart;
				$order['productid'] = $check['id'];
            	$cart_list = $Product_cart_list->add($order);
                $this->success('团购订单已生成，请及时付款', U('Storenew/grouponorderCart', array('token' => $token, 'cid'=>$cid,'orid' => $cart)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/groupon', array('token'=>$token,'cid'=>$cid,'twid' => $twid)));
            }
        }
	}
	
	public function grouponorderCart()
	{

		$set = M("New_twitter_set")->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		if (empty($this->wecha_id) && empty($this->mytwid) && $set) {
			$callbackurl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
			session('callbackurl', $callbackurl);
			$this->redirect(U('Storenew/login',array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid, 'rget' => 1)));
		} elseif (empty($this->wecha_id)) {
			unset($_SESSION[$this->session_cart_name]);
		}
		
		$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
		$cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
		$orid = $_GET['orid'];
		$setting = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $cid))->find();
		$this->assign('setting', $setting);
		
		
		
		$cart = M('New_product_cart')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'id' => $orid))->find();
		$totalCount = $cart['price'];
		$orderid = $cart['orderid'];

		if (empty($cart)) {
			$this->redirect(U('Storenew/groupon', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}

		if (empty($totalCount)) {
			$this->error('没有购买商品!', U('Storenew/groupon', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'twid' => $this->_twid)));
		}
		
		$list = M('New_product_cart_list')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'cartid' => $orid))->find();
		$grouponlist = M('New_product')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $list['productid']))->find();
		$list['logourl'] = $grouponlist['logourl'];
		$list['name'] = $grouponlist['name'];
		$this->assign('orid', $orid);
		$this->assign('orderid', $orderid);
		$this->assign('p', $list);
		$this->assign('totalCount', $totalCount);
		$this->assign('metaTitle', '团购商品购物车结算');
		$this->display(xdxgrouponorderCart);
	}
	
	public function grouponordersave()
	{	

		//TODO 发货的短信提醒
		if(IS_POST){
			$row = array();
			$wecha_id = $this->wecha_id ? $this->wecha_id : session('twid');
			$row['truename'] = $this->_post('truename');
			$row['tel'] = $this->_post('tel');
			$row['address'] = $this->_post('address');
			$row['token'] = $this->token;
			$row['note'] = $this->_post('note');
			$row['wecha_id'] = $wecha_id;
			$row['paymode'] = isset($_POST['paymode']) ? intval($_POST['paymode']) : 0;
			$row['cid'] = $cid = $this->_isgroup ? $this->mainCompany['id'] : $this->_cid;
			
			$score = isset($_POST['score']) ? intval($_POST['score']) : 0;
			$orid = $this->_post('orderid');
			$product_cart_model = M('New_product_cart');

			if ($cartObj = $product_cart_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id, 'orderid' => $orid))->find()) {
				$carts = $cartObj;
			} else {
				$this->error('未找到订单信息，请联系商家');
			}

			//保存订单信息
			$row['time'] = $time = time();
			$saverow = $product_cart_model->where(array('orderid' => $orid))->save($row);
			
			$userinfo_model = M('Userinfo');
			$thisUser = $userinfo_model->where(array('token' => $this->token, 'wecha_id' => $wecha_id))->find();
			//保存个人信息
			if ($_POST['saveinfo']) {
				$this->assign('thisUser', $thisUser);
				$userRow = array('tel' => $row['tel'],'truename' => $row['truename'], 'address' => $row['address']);
				if ($thisUser) {
					$userinfo_model->where(array('id' => $thisUser['id']))->save($userRow);
					F('fans_token_wechaid', NULL);
				} else {
					$userRow['token'] = $this->token;
					$userRow['wecha_id'] = $wecha_id;
					$userRow['wechaname'] = '';
					$userRow['qq'] = 0;
					$userRow['sex'] = -1;
					$userRow['age'] = 0;
					$userRow['birthday'] = '';
					$userRow['info'] = '';

					$userRow['total_score'] = 0;
					$userRow['sign_score'] = 0;
					$userRow['expend_score'] = 0;
					$userRow['continuous'] = 0;
					$userRow['add_expend'] = 0;
					$userRow['add_expend_time'] = 0;
					$userRow['live_time'] = 0;
					$userinfo_model->add($userRow);
				}
			}
			//保存个人信息end
			
			$orderid = $orid;
			$paymode = $row['paymode'];
			$totalprice = $_POST['totalCount'];
			
			if(empty($_POST['address'])){
				$this->error('未填写收货地址');
			}
		
			if(empty($_POST['tel'])){
				$this->error('未填写联系电话');
			}
			
			if(empty($_POST['truename'])){
				$this->error('未填写收货人姓名');
			}

			$model = new templateNews();
			$model->sendTempMsg('TM00184', array('href' => U('Storenew/myinfo',array('token' => $this->token, 'wecha_id' => $wecha_id), true, false, true), 'wecha_id' => $wecha_id, 'first' => '购买商品提醒', 'ordertape' => date("Y年m月d日H时i分s秒"), 'ordeID' => $orid, 'remark' => ''.$row['truename'].'你参与团购成功，订单信息收货人：'.$row['truename'].'，电话：'.$row['tel'].'，收获地址：'.$row['address'].'，备注信息：'.$row['note'].'，付款金额：'.$totalprice.'元，请及时付款，谢谢！'));
			
			//测试消息服务
			$where=array('token'=>$this->token);
			$this->thisWxUser=M('Wxuser')->where($where)->find();
			$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
			$access_token=json_decode($this->curlGet($url_get));
			$a = $access_token->access_token;
			//客服接口，24小时内发送过内容的用户才有
			//$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$a;
			//消息预览接口
			$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$a;
			//获取用户openID
			$where2=array('token'=>$row['token'],'cid'=>$row['cid']);
			$sendwecha_id=M('New_product_set_reply')->where($where2)->field('wecha_id')->find();
			//开始发送消息到商家微信
			$data = '{"touser":"'.$sendwecha_id['wecha_id'].'","msgtype":"text", "text":{"content":"您的用户：'.$row['truename'].'参与团购，收货人：'.$row['truename'].'，电话：'.$row['tel'].'，收获地址：'.$row['address'].'，备注信息：'.$row['note'].'，付款金额：'.$totalprice.'元。（当您看到此信息表示用户已经提交团购订单，等待付款）"}}';
			$this->postCurl($url,$data);
			//
			
			//dump($orderid);
			//die;

			if ($totalprice) {
				if ($paymode == 4) {
					$this->success('正在提交中...', U('CardPay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice)));
					die;
				} else {
					$notOffline = $setting['paymode'] == 1 ? 0 : 1;
					$this->success('正在提交中...', U('Alipay/pay', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'success' => 1, 'from'=> 'Storenew', 'orderName' => $orderid, 'single_orderid' => $orderid, 'price' => $totalprice, 'notOffline' => $notOffline)));
					die;
				}
			}
			$this->success('预定成功,进入您的订单页', U('Storenew/myinfo',array('token' => $_GET['token'], 'wecha_id' => $wecha_id, 'success' => 1, 'twid' => $this->_twid)));
		} else {
			$this->error('订单生产失败');
		}
	}
	
	/**
	 * 团购失败退款申请，确认
	 */
	public function groupontuikuanstatus()
	{
		if ($this->mytwid) {
			if($this->_get('token')!= $this->token){$this->error('非法操作');}
			$id = $this->_get('id');
			$codeid = $this->_get('codeid');
			if (IS_GET) {
				if ($remove = M('New_product_groupon')->where(array('id' => $id,'token' => $this->token,'cid' => $this->_cid,'code' => $codeid,'status'=>2,'tuikuan' => 0))->find()) {
					$wecha = M('Userinfo')->where(array('token' => $this->token,'wecha_id' => $remove['wecha_id']))->find();
					if(empty($wecha)){
						$this->error('系统出错，请联系管理员');
					}
					D('New_product_groupon')->where(array('id' => $id,$this->token,'cid' => $this->_cid,'code' => $codeid,'status'=>2,'tuikuan' => 0))->save(array('tuikuan' => 1));
					D('Userinfo')->where(array('token' => $this->token,'wecha_id' => $remove['wecha_id']))->setInc('balance',$remove['price']);
					$this->success('申请退款操作成功，'.$remove['price'].'元已经充入你的会员卡余额中！', U('Storenew/myinfo',array('token' => $this->token, 'cid' => $this->_cid)));
				}
			}
		}else{
			$this->error('非法操作');
		}
	}
	
	//分享key  最长32
	public function getKey($length=16){
		$str = substr(md5(time().mt_rand(1000,9999)),0,$length);
		return $str;
	}
	
	//整理团购失败的订单，删除超时团购订单
	public function delgrouponorder($token,$tgend,$id,$codeid){
		$now = time();
		$product = M('New_product')->where(array('token' => $token,'cid' => $this->_cid,'id'=>$id))->find();
		$deltime = $now-($product['tgend']*86400);
		$this->m_order = M('New_product_cart');
		$where['time'] = array('lt',$deltime);
		$where['token'] = $token;
		$where['paid'] = 0;
		$where['groupon'] = 1;
		$where['cid'] = $this->_cid;
		$order = $this->m_order->where($where)->select();
			foreach($order_list as $vo){
				//删除购物车列表
				$cartlist = M('New_product_cart_list')->where(array('token' => $token,'cid' => $this->_cid,'cartid'=>$vo['id']))->find();
				$del_cartlist = M('New_product_cart_list')->where(array('token' => $token,'cid' => $this->_cid,'id'=>$cartlist['id']))->delete();
			}
		//删除购物车订单
		$del_order = $this->m_order->where($where)->delete();
	}
	
	// Post Request
	function postCurl($url, $data){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$tmpInfo = curl_exec($ch);
		$errorno=curl_errno($ch);
		if ($errorno) {
			
			return array('rt'=>false,'errorno'=>$errorno);
		}else{
			$js=json_decode($tmpInfo,1);
			if ($js['errcode']=='0'){
				
				return array('rt'=>true,'errorno'=>0);
			}else {
				//exit('模板消息发送失败。错误代码'.$js['errcode'].',错误信息：'.$js['errmsg']);
				return array('rt'=>false,'errorno'=>$js['errcode'],'errmsg'=>$js['errmsg']);

			}
		}
	}

// Get Access_token Request
	function curlGet($url){
		$ch = curl_init();
		$header = "Accept-Charset: utf-8";
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
		//curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$temp = curl_exec($ch);
		return $temp;
	}
}

?>