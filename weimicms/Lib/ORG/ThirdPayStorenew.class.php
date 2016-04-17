<?php
class ThirdPayStorenew
{	
	
	public function index($orderid, $paytype = '', $third_id = ''){
		if ($order = M('New_product_cart')->where(array('orderid' => $orderid))->find()) {
			//TODO 发货的短信提醒
			if ($order['paid']) {
				$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
				if($order['jingpai'] == 0){
					$carts = unserialize($order['info']);
					$tdata = $this->getCat($carts, $order['token'], $order['cid'], $userInfo['getcardtime']);
					$list = array();
					foreach ($tdata[0] as $va) {
						$t = array();
						$salecount = 0;
						if (!empty($va['detail'])) {
							foreach ($va['detail'] as $v) {
								$t = array('num' => $v['count'], 'colorName' => $v['colorName'], 'formatName' => $v['formatName'], 'price' => $v['price'], 'name' => $va['name']);
								$list[] = $t;
								$salecount += $v['count'];
							}
						} else {
							$t = array('num' => $va['count'], 'price' => $va['price'], 'name' => $va['name']);
							$list[] = $t;
							$salecount = $va['count'];
						}
						D("New_product")->where(array('id' => $va['id']))->setInc('salecount', $salecount);
					}
				} else {
					//竞拍内容
					$order = M('New_product_cart')->where(array('token' => $order['token'], 'cid' => $order['cid'], 'id' => $order['id']))->find();
					$orderlist = M('New_product_cart_list')->where(array('token' => $order['token'], 'cid' => $order['cid'], 'cartid' => $order['id']))->find();
					if($order['jingpai'] == 1){
						$produtcdb = M("New_product_jingpai");
					}else if ($order['groupon'] == 1){
						$produtcdb = M("New_product");
					}
					$jingpai = $produtcdb->where(array('token' => $order['token'], 'cid' => $order['cid'], 'id' => $orderlist['productid']))->find();
					$list = array($orderlist['productid']=>array('num' => '1', 'price' => $jingpai['price'], 'name' => $jingpai['name']));
					if($order['jingpai'] == 1){
						$order['note'] = ''.$order['note'].'(竞拍订单)';
					}else if ($order['groupon'] == 1){
						$order['note'] = ''.$order['note'].'(团购订单)';
						//修改团购记录已付款
						M('New_product_groupon')->where(array('orderid'=>$order['orderid']))->setField('paid',1);
					}
				}
				
		
				if ($order['twid']) {
					$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
					$twid = $order['twid'];
					if($twid == $userInfo['fromtwid']){
						$addtwid = $userInfo['addtwid'];
					}else{
						$addtwid = $userInfo['fromtwid'];
					}
					
				}else{
					$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
					$twid = $userInfo['fromtwid'];
					$addtwid = $userInfo['addtwid'];
				}
				//dump($twid);
				//die;
				if($twid && ($twid != $userInfo['twid'])){
					$token = $order['token'];
					$cid = $order['cid'];
					$orderid = $order['orderid'];
					$totalprice = $order['totalprice'];
					$type = 3;
					$param = 1;
					$set = M("New_twitter_set")->where(array('token' => $token, 'cid' => $cid))->find();
					if (empty($set)) return false;
					//获取单一产品的1、2级佣金
					$yongjinlist = M('New_product_cart_list')->where(array('token' => $token, 'cid' => $cid, 'cartid'=>$order['id']))->select();
						foreach ($yongjinlist as $key => $row) {
							$products = M('New_product')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id'=>$row['productid']))->find();
							if($products['allow_distribution'] == '1'){
								//一级佣金
								switch ($products['commission_type']){
									default:
										$yongjinlist[$key]['commission'] = $set['percent'] * 0.01 * $products['price'] * $row['total'];
										break;
									case 'fixed':
										$yongjinlist[$key]['commission'] = $products['commission']*$row['total'];
										break;
									case 'float':
										$products['commission'] = $products['price']*($products['commission']*0.01)*$row['total'];
										$yongjinlist[$key]['commission'] = $products['commission'];
										break;
								}
								//二级佣金
								switch ($products['addcommission_type']){
									default:
										$yongjinlist[$key]['addcommission'] = $set['addpercent'] * 0.01 * $products['price'] * $row['total'];
										break;
									case 'fixed':
										$yongjinlist[$key]['addcommission'] = $products['addcommission']*$row['total'];
										break;
									case 'float':
										$products['addcommission'] = $products['price']*($products['addcommission']*0.01)*$row['total'];
										$yongjinlist[$key]['addcommission'] = $products['addcommission'];
										break;
								}
								//产品总佣金
								$commission += $yongjinlist[$key]['commission'];
								$addcommission += $yongjinlist[$key]['addcommission'];
							}else{
								$yongjinlist[$key]['addcommission'] = '0';
								$yongjinlist[$key]['commission'] = '0';
								$commission += $yongjinlist[$key]['commission'];
								$addcommission += $yongjinlist[$key]['addcommission'];
							}
						}
					
					//佣金判断结束
					
					if ($twid && $token && $cid && $commission != '0' && $addcommission != '0') {
						$db = D("New_twitter_log");
						// 1.点击， 2.注册会员， 3.购买商品
						//判断佣金分成
						$price = $commission;
						$addprice = $addcommission;
						//上级分成记录
						if ($type == 3) {//购买商品，上级分成
							$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'addtwid' => $addtwid, 'orderid' => $orderid, 'type' => 3, 'dateline' => time(), 'param' => $param, 'price' => $price , 'wecha_id' => $order['wecha_id'] , 'orderprice' => $order['price']));
							//上级的上级参与分成记录
							if(!empty($addtwid) && ($addtwid != $twid)){
								$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $addtwid, 'type' => 4, 'orderid' => $orderid, 'dateline' => time(), 'param' => $param, 'price' => $addprice , 'wecha_id' => $order['wecha_id'], 'orderprice' => $order['price']));
							}
						}
						
						//统计总收入
						if ($count = M("New_twitter_count")->where(array('token' => $token, 'cid' => $cid, 'twid' => $twid))->find()) {
							D("New_twitter_count")->where(array('id' => $count['id']))->setInc('total', $price);
						} else {
							D("New_twitter_count")->add(array('twid' => $twid, 'fromtwid' => $fromtwid, 'token' => $token, 'cid' => $cid, 'total' => $price, 'remove' => 0));
						}
						
						//上级的上级参与分成记录,统计总收入
						if(!empty($addtwid) && ($addtwid != $twid)){
							if ($count = M("New_twitter_count")->where(array('token' => $token, 'cid' => $cid, 'twid' => $addtwid))->find()) {
								D("New_twitter_count")->where(array('id' => $count['id']))->setInc('total', $addprice);
							} else {
								D("New_twitter_count")->add(array('twid' => $addtwid, 'token' => $token, 'cid' => $cid, 'total' => $addprice, 'remove' => 0));
							}
						}
						//分佣写入结束
						
						//查询分销人的信息，如果没有直接写入
						$twiduserinfo = D('Userinfo')->where(array('token' => $order['token'], 'twid' => $twid))->find();
						$twiduser = D('New_twitter_userinfo')->where(array('token' => $order['token'], 'twid' => $twid))->find();
						if(empty($twiduser)){
							$userdata = array(
								'token' => $order['token'],
								'cid' => $order['cid'],
								'wecha_id' => $twiduserinfo['wecha_id'],
								'twid' => $twiduserinfo['twid'],
								'truename' => $twiduserinfo['truename'],
								'status' => '0',
								'dateline' => time()
							);
							D("New_twitter_userinfo")->add($userdata);
						}
						
						//查询分销人的上家的上家信息，如果没有直接写入
						$addtwiduserinfo = D('Userinfo')->where(array('token' => $order['token'], 'twid' => $addtwid))->find();
						$addtwiduser = D('New_twitter_userinfo')->where(array('token' => $order['token'], 'twid' => $addtwid))->find();
						if(empty($addtwiduser)){
							$adduserdata = array(
								'token' => $order['token'],
								'cid' => $order['cid'],
								'wecha_id' => $addtwiduserinfo['wecha_id'],
								'twid' => $addtwiduserinfo['twid'],
								'truename' => $addtwiduserinfo['truename'],
								'status' => '0',
								'dateline' => time()
							);
							D("New_twitter_userinfo")->add($adduserdata);
						}
					}
					
					//$this->savelog(3, $order['twid'], $order['token'], $order['cid'], $order['totalprice']);
				}//分佣判断END
				
				$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
				//获取配置信息
				$where=array('token'=>$order['token']);
				$this->thisWxUser=M('Wxuser')->where($where)->find();
				$url_get='https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->thisWxUser['appid'].'&secret='.$this->thisWxUser['appsecret'];
				$access_token=json_decode($this->curlGet($url_get));
				$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token='.$access_token->access_token;
				//获取用户openID
				$where=array('token'=>$order['token'],'cid'=>$order['cid']);
				$sendwecha_id=M('New_product_set_reply')->where($where)->field('wecha_id')->find();
				//准备发送请求的数据
				$data = '{"touser":"'.$sendwecha_id['wecha_id'].'","msgtype":"text", "text":{"content":"您的用户：'.$userInfo['truename'].'订购了商品，电话：'.$order['tel'].'，收货地址：'.$order['address'].'，备注信息：'.$order['note'].'，订单号：'.$orderid.',订单金额：'.$order['price'].'元，已经付款成功！"}}';
				$this->postCurl($url,$data);
				//打印请求
				$company = D('Company')->where(array('token' => $order['token'], 'id' => $order['cid']))->find();
				$op = new orderPrint();
				$msg = array('companyname' => $company['name'], 'companytel' => $company['tel'], 'truename' => $order['truename'], 'tel' => $order['tel'], 'address' => $order['address'], 'buytime' => $order['time'], 'orderid' => $order['orderid'], 'sendtime' => '', 'price' => $order['price'], 'total' => $order['total'], 'des' => $order['note'],'ptype' => $order['paytype'], 'list' => $list);
				$msg = ArrayToStr::array_to_str($msg, 1);
				$op->printit($order['token'], $order['cid'], 'Store', $msg, 1);
				Sms::sendSms($order['token'], "您的顾客{$userInfo['truename']}刚刚对订单号：{$orderid}的订单进行了支付，请您注意查看并处理",$company['mp']);
				$model = new templateNews();
				$model->sendTempMsg('OPENTM202521011', array('href' => U('Storenew/myinfo',array('token' => $order['token'], 'wecha_id' => $order['wecha_id'], 'twid' => $order['twid']), true, false, true), 'wecha_id' => $order['wecha_id'], 'first' => '购买商品提醒', 'keyword1' => $orderid, 'keyword2' => date("Y年m月d日H时i分s秒"), 'remark' => '购买成功，感谢您的光临，欢迎下次再次光临！'));
			}
			header('Location:/index.php?g=Wap&m=Storenew&a=myinfo&token='.$order['token'].'&wecha_id='.$order['wecha_id'] . '&twid='.$order['twid']);
		}else{
			exit('订单不存在：'.$out_trade_no);
			exit('订单不存在');
		}
	}
	
	public function getCat($carts, $token, $cid, $getcardtime)
	{
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
		$productdata = M('New_product')->where(array('id'=> array('in', $pids)))->select();
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
				if ($getcardtime > 0) {
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
				$row['price'] = $price ? $price : ($getcardtime > 0 && $row['vprice'] ? $row['vprice'] : $row['price']);
				$row['count'] = $data[$pid]['total'] = $count;
				if (empty($count) && empty($price)) {
					$row['count'] = $data[$pid]['total'] = isset($carts[$pid]['count']) ? $carts[$pid]['count'] : (isset($carts[$pid]) && is_int($carts[$pid]) ? $carts[$pid] : 0);
					if ($getcardtime > 0) {
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
		if ($obj = M('New_product_setting')->where(array('token' => $token, 'cid' => $cid))->find()) {
			if ($totalprice >= $obj['price']) $mailPrice = 0;
		}
		return array($list, $data, $mailPrice);
	}
	
	/*private function savelog($type, $twid, $token, $cid, $totalprice)
	{
		//dump($type);
		//die;
		if ($twid && $token && $cid) {
			$set = M("New_twitter_set")->where(array('token' => $token, 'cid' => $cid))->find();
			if (empty($set)) return false;
			$db = D("New_twitter_log");
			// 1.点击， 2.注册会员， 3.购买商品
			if ($type == 3) {//购买商品
				$price = $set['percent'] * 0.01 * $totalprice;
				$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 3, 'dateline' => time(), 'param' => $param, 'price' => $price));
			} elseif ($type == 2) {//注册会员
				$price = $set['registerprice'];
				$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 2, 'dateline' => time(), 'param' => $param, 'price' => $set['registerprice']));
			} else {//点击
				$price = $set['clickprice'];
				$db->add(array('token' => $token, 'cid' => $cid, 'twid' => $twid, 'type' => 1, 'dateline' => time(), 'param' => $param, 'price' => $set['clickprice']));
			}
			//统计总收入
			if ($count = M("New_twitter_count")->where(array('token' => $token, 'cid' => $cid, 'twid' => $twid))->find()) {
				D("New_twitter_count")->where(array('id' => $count['id']))->setInc('total', $price);
			} else {
				D("New_twitter_count")->add(array('twid' => $twid, 'token' => $token, 'cid' => $cid, 'total' => $price, 'remove' => 0));
			}
		}
	}*/
	
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