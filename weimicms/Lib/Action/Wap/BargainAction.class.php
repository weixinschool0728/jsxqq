<?php
class BargainAction extends WapAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->m_bargain = M('bargain');
		$this->m_order = M('bargain_order');
		$this->m_kanuser = M('bargain_kanuser');
		$this->m_userinfo = M('userinfo');

		if ($_GET['id'] != '') {
			$out_where = array(
				'token'      => $this->token,
				'bargain_id' => intval($_GET['id']),
				'state2'     => 1,
				'addtime'    => array('lt', time() - (5 * 60))
				);
			$out_order = $this->m_order->where($out_where)->select();

			foreach ($out_order as $outval) {
				$this->m_bargain->where(array('token' => $this->token, 'tp_id' => $outval['bargain_id']))->setInc('inventory', 1);
				S($_GET['id'] . 'bargain' . $this->token, NULL);
			}

			$this->m_order->where($out_where)->save(array('state2' => 0));
		}
	}

	public function home()
	{
		$where['token'] = $this->token;

		if ($_POST['name'] != '') {
			$where['name'] = array('like', '%' . $_POST['name'] . '%');
		}

		if (($_GET['type'] == '') || ($_GET['type'] == '0')) {
			$bargain_list = $this->m_bargain->where($where)->select();
		}
		else if ($_GET['type'] == '1') {
			$bargain_list = $this->m_bargain->where($where)->order('addtime desc')->select();
		}
		else if ($_GET['type'] == '2') {
			$bargain_list = $this->m_bargain->where($where)->order('pv desc')->select();
		}

		foreach ($bargain_list as $k => $v) {
			$where_order_paynum['token'] = $this->token;
			$where_order_paynum['bargain_id'] = $v['tp_id'];
			$where_order_paynum['paid'] = 1;
			$bargain_list[$k]['paynum'] = $this->m_order->where($where_order_paynum)->count();
		}

		$this->assign('bargain_list', $bargain_list);
		$this->display();
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where['tp_id'] = (int) $_GET['id'];
		$where['state'] = 1;
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();

			if ($bargain == '') {
				$this->error('没有这个活动');
			}

			$save['pv'] = $bargain['pv'] + 1;
			$update = $this->m_bargain->where($where)->save($save);
			$bargain['pv'] = $bargain['pv'] + 1;
			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}
		else {
			$save['pv'] = $bargain['pv'] + 1;
			$update = $this->m_bargain->where($where)->save($save);
			$bargain['pv'] = $bargain['pv'] + 1;
		}

		if ($bargain['state'] == 0) {
			$this->error('没有这个活动');
		}

		if ($bargain['is_new'] == 2) {
			$this->redirect('Wap/Bargain/new_index', array('token' => $this->token, 'id' => $_GET['id']));
			exit();
		}

		if ($bargain['logourl1'] != '') {
			$bargain['logourl1'] = $this->getLink($bargain['logourl1']);
		}

		if ($bargain['logourl2'] != '') {
			$bargain['logourl2'] = $this->getLink($bargain['logourl2']);
		}

		if ($bargain['logourl3'] != '') {
			$bargain['logourl3'] = $this->getLink($bargain['logourl3']);
		}

		if (($bargain['is_attention'] == 2) && !$this->isSubscribe()) {
			$this->memberNotice('', 1);
		}
		else {
			if (($bargain['is_reg'] == 2) && empty($this->fans['tel'])) {
				$this->memberNotice();
			}
		}

		$this->assign('bargain', $bargain);
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['bargain_id'] = (int) $_GET['id'];
		$order = $this->m_order->where($where_order)->find();

		if ($order == '') {
			$type = 'noorder';
		}

		$this->assign('type', $type);
		$where_order_paynum['token'] = $this->token;
		$where_order_paynum['bargain_id'] = (int) $_GET['id'];
		$where_order_paynum['paid'] = 1;
		$paynum = $this->m_order->where($where_order_paynum)->count();
		$this->assign('paynum', $paynum);
		$this->display();
	}

	public function dao()
	{
		$where['token'] = $this->token;
		$where['tp_id'] = (int) $_GET['id'];
		$where['state'] = 1;
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();

			if ($bargain == '') {
				$this->error('没有这个活动');
			}

			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}

		if ($bargain['state'] == 0) {
			$this->error('没有这个活动');
		}

		$myorder = $this->m_order->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bargain_id' => (int) $_GET['id'], 'paid' => 1))->find();
		if (($bargain['inventory'] < 1) && ($myorder == '')) {
			$this->error('此商品都被抢完了！');
		}

		if ($bargain['logourl1'] != '') {
			$bargain['logourl1'] = $this->getLink($bargain['logourl1']);
		}

		if ($bargain['logourl2'] != '') {
			$bargain['logourl2'] = $this->getLink($bargain['logourl2']);
		}

		if ($bargain['logourl3'] != '') {
			$bargain['logourl3'] = $this->getLink($bargain['logourl3']);
		}

		$this->assign('bargain', $bargain);
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);
		$where_order1['token'] = $this->token;
		$where_order1['tp_id'] = (int) $_GET['orderid'];
		$order1 = $this->m_order->where($where_order1)->find();
		if (($order1 == '') || ($order1['wecha_id'] == $this->wecha_id)) {
			$type = 'my';
			$where_order2['token'] = $this->token;
			$where_order2['wecha_id'] = $this->wecha_id;
			$where_order2['bargain_id'] = (int) $_GET['id'];
			$order2 = $this->m_order->where($where_order2)->find();
			if (($order2 == '') || ($order2['bargain_nowprice'] == $bargain['original'])) {
				$type2 = 'noorder';
			}
			else {
				$this->assign('order', $order2);

				if (time() < $order2['endtime']) {
					$time = $order2['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser['token'] = $this->token;
				$where_kanuser['orderid'] = $order2['tp_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
			}

			$this->assign('type2', $type2);
		}
		else {
			$type = 'nomy';
			$where_kanuser['token'] = $this->token;
			$where_kanuser['wecha_id'] = $order1['wecha_id'];
			$where_kanuser['friend'] = $this->wecha_id;
			$where_kanuser['bargain_id'] = (int) $_GET['id'];
			$kanuser = $this->m_kanuser->where($where_kanuser)->find();

			if ($kanuser == '') {
				$type2 = 'nokan';
				$this->assign('order', $order1);

				if (time() < $order1['endtime']) {
					$time = $order1['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = $order1['tp_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser2)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser2)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
				$where_userinfo3['token'] = $this->token;
				$where_userinfo3['wecha_id'] = $order1['wecha_id'];
				$userinfo3 = $this->m_userinfo->where($where_userinfo3)->find();
				$this->assign('userinfo2', $userinfo3);
			}
			else {
				$this->assign('order', $order1);

				if (time() < $order1['endtime']) {
					$time = $order1['endtime'] - time();
					$hour = floor($time / (60 * 60));

					if ($hour < 48) {
						$hour_y = $time % (60 * 60);
						$minute = floor($hour_y / 60);
						$second = $hour_y % 60;
						$this->assign('hour', $hour);
						$this->assign('minute', $minute);
						$this->assign('second', $second);
					}
					else {
						$this->assign('isday', 'yes');
						$Dday = floor($time / (60 * 60 * 24));
						$Dday_y = $time % (60 * 60 * 24);
						$Dhour = floor($Dday_y / (60 * 60));
						$Dhour_y = $Dday_y % (60 * 60);
						$Dminute = floor($Dhour_y / 60);
						$Dsecond = $Dhour_y % 60;
						$this->assign('day', $Dday);
						$this->assign('hour', $Dhour);
						$this->assign('minute', $Dminute);
						$this->assign('second', $Dsecond);
					}
				}

				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = $order1['tp_id'];
				$count_kanuser = $this->m_kanuser->where($where_kanuser2)->count();
				$select_kanuser = $this->m_kanuser->where($where_kanuser2)->order('addtime')->select();
				$price_kanuser = 0;

				foreach ($select_kanuser as $k => $vo) {
					$price_kanuser = $price_kanuser + $vo['dao'];
					$where_userinfo2['token'] = $this->token;
					$where_userinfo2['wecha_id'] = $vo['friend'];
					$userinfo2 = $this->m_userinfo->where($where_userinfo2)->find();
					$select_kanuser[$k]['wechaname'] = $userinfo2['wechaname'];
					$select_kanuser[$k]['portrait'] = $userinfo2['portrait'];
				}

				$this->assign('count', $count_kanuser);
				$this->assign('dao', $price_kanuser);
				$this->assign('kanuser_list', $select_kanuser);
				$where_userinfo3['token'] = $this->token;
				$where_userinfo3['wecha_id'] = $order1['wecha_id'];
				$userinfo3 = $this->m_userinfo->where($where_userinfo3)->find();
				$this->assign('userinfo2', $userinfo3);
				$this->assign('kanuser', $kanuser);
			}

			$this->assign('type2', $type2);
		}

		$this->assign('type', $type);
		$this->display();
	}

	public function operate()
	{
		switch ($_GET['type']) {
		case 'firstdao':
			$where_order['token'] = $this->token;
			$where_order['wecha_id'] = $this->wecha_id;
			$where_order['bargain_id'] = (int) $_GET['id'];
			$order = $this->m_order->where($where_order)->find();

			if ($order == '') {
				$where['token'] = $this->token;
				$where['tp_id'] = (int) $_GET['id'];
				$bargain = S($_GET['id'] . 'bargain' . $this->token);

				if ($bargain == '') {
					$bargain = $this->m_bargain->where($where)->find();
					S($_GET['id'] . 'bargain' . $this->token, $bargain);
				}

				if (($bargain['qdao'] != '') && ($bargain['qdao'] != 0)) {
					$kan = floor($bargain['qprice'] / $bargain['qdao']);
					if ((1 < $kan) && ($kan < $bargain['qprice'])) {
						$jian = rand(1, $kan - 1);
						$kanzhi = rand(1, $kan);
					}
					else {
						$kanzhi = $kan;
					}
				}
				else {
					$cha = $bargain['original'] - $bargain['minimum'];
					$kan = floor($cha / $bargain['dao']);

					if (1 < $kan) {
						$jian = rand(1, $kan - 1);
						$kanzhi = rand(1, $kan);
					}
					else {
						$kanzhi = $kan;
					}
				}

				$add_order['token'] = $this->token;
				$add_order['wecha_id'] = $this->wecha_id;
				$add_order['bargain_id'] = (int) $_GET['id'];
				$add_order['endtime'] = ($bargain['starttime'] * 3600) + time();
				$add_order['bargain_name'] = $bargain['name'];
				$add_order['bargain_logoimg'] = $bargain['logoimg1'];
				$add_order['bargain_original'] = $bargain['original'];
				$add_order['bargain_minimum'] = $bargain['minimum'];
				$add_order['bargain_nowprice'] = $bargain['original'] - $kanzhi;
				$where_userinfo['token'] = $this->token;
				$where_userinfo['wecha_id'] = $this->wecha_id;
				$userinfo = $this->m_userinfo->where($where_userinfo)->find();
				$add_order['phone'] = $userinfo['tel'];
				$add_order['address'] = $userinfo['address'];
				$add_order['addtime'] = time();
				$id_order = $this->m_order->add($add_order);
				$where_order_orderid['tp_id'] = $id_order;
				$randnum = rand(1000, 9999);
				$save_order_orderid['orderid'] = $id_order . 'bargain' . time() . $randnum;
				$update_order_orderid = $this->m_order->where($where_order_orderid)->save($save_order_orderid);
				$add_kanuser['token'] = $this->token;
				$add_kanuser['wecha_id'] = $this->wecha_id;
				$add_kanuser['bargain_id'] = (int) $_GET['id'];
				$add_kanuser['orderid'] = $id_order;
				$add_kanuser['friend'] = $this->wecha_id;
				$add_kanuser['dao'] = $kanzhi;
				$add_kanuser['addtime'] = time();
				$id_kanuser = $this->m_kanuser->add($add_kanuser);
				$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'kanzhi' => $kanzhi));
			}
			else {
				$order = $this->m_order->where($where_order)->delete();
				$this->redirect('Bargain/operate', array('token' => $this->token, 'id' => $_GET['id'], 'type' => 'firstdao'));
			}

			break;

		case 'friendkan':
			$where_order['token'] = $this->token;
			$where_order['tp_id'] = (int) $_GET['orderid'];
			$order = $this->m_order->where($where_order)->find();
			$where_kanuser['token'] = $this->token;
			$where_kanuser['wecha_id'] = $order['wecha_id'];
			$where_kanuser['friend'] = $this->wecha_id;
			$where_kanuser['bargain_id'] = (int) $_GET['id'];
			$kanuser = $this->m_kanuser->where($where_kanuser)->find();

			if ($kanuser == '') {
				$where_kanuser2['token'] = $this->token;
				$where_kanuser2['orderid'] = (int) $_GET['orderid'];
				$count = $this->m_kanuser->where($where_kanuser2)->count();
				$where['token'] = $this->token;
				$where['tp_id'] = (int) $_GET['id'];
				$bargain = S($_GET['id'] . 'bargain' . $this->token);

				if ($bargain == '') {
					$bargain = $this->m_bargain->where($where)->find();
					S($_GET['id'] . 'bargain' . $this->token, $bargain);
				}

				if ($order['bargain_nowprice'] == $bargain['minimum']) {
					$this->show('<script>alert(\'TA的砍价已砍至底价\');window.location.href=\'' . U('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid'])) . '\'</script>');
				}
				else if ($bargain['minimum'] < $order['bargain_nowprice']) {
					if (($bargain['qdao'] != '') && ($bargain['qdao'] != 0)) {
						if ($bargain['qdao'] <= $count) {
							$cha_dao = $bargain['dao'] - $bargain['qdao'];

							if ($cha_dao == 1) {
								$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
							}
							else if (1 < $cha_dao) {
								$cha_dao2 = $bargain['dao'] - $count;

								if ($cha_dao2 == 1) {
									$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
								}
								else {
									$cha_price = $bargain['original'] - $bargain['minimum'] - $bargain['qprice'];
									$kan = floor($cha_price / $cha_dao);

									if (1 < $kan) {
										$jian = rand(1, $kan - 1);
										$kanzhi = rand(1, $kan);
									}
									else {
										$kanzhi = $kan;
									}
								}
							}
						}
						else if ($count < $bargain['qdao']) {
							$cha_dao = $bargain['qdao'] - $count;

							if ($cha_dao == 1) {
								$kanzhi = $bargain['qprice'] - $bargain['original'] - $order['bargain_nowprice'];
							}
							else if (1 < $cha_dao) {
								$kan = floor($bargain['qprice'] / $bargain['qdao']);

								if (1 < $kan) {
									$jian = rand(1, $kan - 1);
									$kanzhi = rand(1, $kan);
								}
								else {
									$kanzhi = $kan;
								}
							}
						}
					}
					else {
						$cha_dao = $bargain['dao'] - $count;

						if ($cha_dao == 1) {
							$kanzhi = $order['bargain_nowprice'] - $bargain['minimum'];
						}
						else {
							$cha = $bargain['original'] - $bargain['minimum'];
							$kan = floor($cha / $bargain['dao']);

							if (1 < $kan) {
								$jian = rand(1, $kan - 1);
								$kanzhi = rand(1, $kan);
							}
							else {
								$kanzhi = $kan;
							}
						}
					}

					$save_order['bargain_nowprice'] = $order['bargain_nowprice'] - $kanzhi;
					$update_order = $this->m_order->where($where_order)->save($save_order);
					$add_kanuser['token'] = $this->token;
					$add_kanuser['wecha_id'] = $order['wecha_id'];
					$add_kanuser['bargain_id'] = (int) $_GET['id'];
					$add_kanuser['orderid'] = $order['tp_id'];
					$add_kanuser['friend'] = $this->wecha_id;
					$add_kanuser['dao'] = $kanzhi;
					$add_kanuser['addtime'] = time();
					$id_kanuser = $this->m_kanuser->add($add_kanuser);
					$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid'], 'kanzhi' => $kanzhi));
				}
			}
			else {
				$this->redirect('Bargain/dao', array('token' => $this->token, 'id' => $_GET['id'], 'orderid' => $_GET['orderid']));
			}

			break;
		}
	}

	public function payuserinfo()
	{
		$where['token'] = $this->token;
		$where['tp_id'] = (int) $_GET['id'];
		$bargain = S($_GET['id'] . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where($where)->find();
			S($_GET['id'] . 'bargain' . $this->token, $bargain);
		}

		$this->assign('bargain', $bargain);
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);

		if ($_GET['orderid'] == '') {
			$add_order['token'] = $this->token;
			$add_order['wecha_id'] = $this->wecha_id;
			$add_order['bargain_id'] = (int) $_GET['id'];
			$add_order['endtime'] = ($bargain['starttime'] * 3600) + time();
			$add_order['bargain_name'] = $bargain['name'];
			$add_order['bargain_logoimg'] = $bargain['logoimg1'];
			$add_order['bargain_original'] = $bargain['original'];
			$add_order['bargain_minimum'] = $bargain['minimum'];
			$add_order['bargain_nowprice'] = $bargain['original'];
			$add_order['phone'] = $userinfo['tel'];
			$add_order['address'] = $userinfo['address'];
			$add_order['addtime'] = time();
			$id_order = $this->m_order->add($add_order);
			$where_order['token'] = $this->token;
			$where_order['tp_id'] = $id_order;
			$randnum = rand(1000, 9999);
			$save_order_orderid['orderid'] = $id_order . 'bargain' . time() . $randnum;
			$update_order_orderid = $this->m_order->where($where_order)->save($save_order_orderid);
			$order = $this->m_order->where($where_order)->find();
			$this->assign('order', $order);
		}
		else {
			$where_order['token'] = $this->token;
			$where_order['tp_id'] = (int) $_GET['orderid'];
			$order = $this->m_order->where($where_order)->find();
			$this->assign('order', $order);
		}

		$this->display();
	}

	public function dobuy()
	{
		$where_order['token'] = $this->token;
		$where_order['tp_id'] = (int) $_GET['orderid'];
		$order = $this->m_order->where($where_order)->find();
		$where['tp_id'] = $order['bargain_id'];
		$where['token'] = $this->token;
		$bargain = $this->m_bargain->where($where)->find();
		if (($bargain['inventory'] < 1) && ($order['state2'] != 1)) {
			$this->error('此商品都被抢完了！', U('Bargain/home', array('token' => $this->token)));
			exit();
		}

		$save_order['username'] = $_GET['name'];
		$save_order['phone'] = $_GET['phone'];
		$save_order['address'] = $_GET['address'];
		$save_order['addtime'] = time();

		if ($order['state2'] != 1) {
			$save_order['state2'] = 1;
			$this->m_bargain->where($where)->setDec('inventory', 1);
			S($order['bargain_id'] . 'bargain' . $this->token, NULL);
		}

		$update_order = $this->m_order->where($where_order)->save($save_order);

		if ($bargain['is_new'] == 2) {
			$save_order2['price'] = $order['bargain_nowprice'] / 100;
		}
		else {
			$save_order2['price'] = $order['bargain_nowprice'];
		}

		$randnum = rand(1000, 9999);
		$save_order2['orderid'] = (int) $_GET['orderid'] . 'bargain' . time();
		$update_order2 = $this->m_order->where($where_order)->save($save_order2);
		$order2 = $this->m_order->where($where_order)->find();
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$save_userinfo['wechaname'] = $_GET['name'];
		$save_userinfo['tel'] = $_GET['phone'];
		$save_userinfo['address'] = $_GET['address'];
		$update_userinfo = $this->m_userinfo->where($where_userinfo)->save($save_userinfo);
		$this->redirect('Alipay/pay', array('token' => $this->token, 'price' => $order2['price'], 'wecha_id' => $this->wecha_id, 'from' => 'Bargain', 'orderid' => $order2['orderid'], 'single_orderid' => $order2['orderid'], 'notOffline' => 1));
	}

	public function payReturn()
	{
		$where_order['token'] = $this->token;
		$where_order['orderid'] = (int) $_GET['orderid'];
		$order = $this->m_order->where($where_order)->find();
		if (($order['paid'] == 1) && ($order['state2'] == 2)) {
			$this->success('支付成功', U('Bargain/my', array('token' => $this->token)));
		}
		else {
			ThirdPayBargain::index($_GET['orderid'], $order['paytype'], $order['third_id']);
			$this->success('支付成功', U('Bargain/my', array('token' => $this->token)));
		}
	}

	public function my()
	{
		$where_userinfo['token'] = $this->token;
		$where_userinfo['wecha_id'] = $this->wecha_id;
		$userinfo = $this->m_userinfo->where($where_userinfo)->find();
		$this->assign('userinfo', $userinfo);
		$this->display();
	}

	public function mybargain()
	{
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$order_list = $this->m_order->where($where_order)->order('addtime desc')->select();

		foreach ($order_list as $k => $v) {
			if (time() < $v['endtime']) {
				$time = $v['endtime'] - time();
				$hour = floor($time / (60 * 60));

				if ($hour < 48) {
					$hour_y = $time % (60 * 60);
					$minute = floor($hour_y / 60);
					$second = $hour_y % 60;
					$order_list[$k]['hour'] = $hour;
					$order_list[$k]['minute'] = $minute;
					$order_list[$k]['second'] = $second;
				}
				else {
					$order_list[$k]['isday'] = 'yes';
					$Dday = floor($time / (60 * 60 * 24));
					$Dday_y = $time % (60 * 60 * 24);
					$Dhour = floor($Dday_y / (60 * 60));
					$Dhour_y = $Dday_y % (60 * 60);
					$Dminute = floor($Dhour_y / 60);
					$Dsecond = $Dhour_y % 60;
					$order_list[$k]['day'] = $Dday;
					$order_list[$k]['hour'] = $Dhour;
					$order_list[$k]['minute'] = $Dminute;
					$order_list[$k]['second'] = $Dsecond;
				}
			}
		}

		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function myorder()
	{
		$where_order['token'] = $this->token;
		$where_order['wecha_id'] = $this->wecha_id;
		$where_order['paid'] = 1;
		$order_list = $this->m_order->where($where_order)->order('addtime desc')->select();
		$this->assign('order_list', $order_list);
		$this->display();
	}

	public function new_index()
	{
		$id = intval($_GET['id']);
		$bargain = S($id . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where(array('token' => $this->token, 'tp_id' => $id))->find();

			if ($bargain == '') {
				$this->error('该砍价活动不存在');
				exit();
			}

			S($id . 'bargain' . $this->token, $bargain);
		}

		if ($bargain['state'] == 0) {
			$this->error('该砍价活动已关闭');
			exit();
		}

		if ($bargain['is_new'] == 1) {
			$this->redirect('Wap/Bargain/index', array('token' => $this->token, 'id' => $_GET['id']));
			exit();
		}

		$this->m_bargain->where(array('token' => $this->token, 'tp_id' => $id))->setInc('pv');
		$bargain['count_canyu'] = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id))->count();
		$bargain['count_pay'] = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'paid' => 1))->count();
		$bargain['original'] = $bargain['original'] / 100;
		$bargain['minimum'] = $bargain['minimum'] / 100;
		$bargain['kan_min'] = $bargain['kan_min'] / 100;
		$bargain['kan_max'] = $bargain['kan_max'] / 100;
		$this->assign('bargain', $bargain);
		$orderid = intval($_GET['orderid']);
		$this->assign('orderid', $orderid);
		if (($bargain['is_attention'] == 2) && !$this->isSubscribe()) {
			if ($orderid == 0) {
				$this->memberNotice('', 1);
			}
			else if ($bargain['is_subhelp'] == 2) {
				$this->memberNotice('', 1);
			}
		}
		else {
			if (($bargain['is_reg'] == 2) && empty($this->fans['tel']) && ($orderid == 0)) {
				$this->memberNotice();
			}
		}

		if ($orderid != 0) {
			$myorder = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'tp_id' => $orderid))->find();
			if (($this->wecha_id == $myorder['wecha_id']) || ($myorder == '')) {
				$this->redirect('Wap/Bargain/new_index', array('token' => $this->token, 'id' => $_GET['id']));
			}

			$userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $myorder['wecha_id']))->find();
		}
		else {
			$myorder = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'wecha_id' => $this->wecha_id))->find();
			$userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
		}

		$this->assign('userinfo', $userinfo);

		if ($myorder != '') {
			$myorder['bargain_nowprice'] = $myorder['bargain_nowprice'] / 100;
			$myorder['bargain_kan'] = round($bargain['original'] - $myorder['bargain_nowprice'], 2);

			if (time() < $myorder['endtime']) {
				if (($myorder['bargain_nowprice'] <= $bargain['minimum']) || ($myorder['paid'] == 1)) {
					if ($myorder['paid'] == 1) {
						$is_over = 3;
					}
					else {
						$is_over = 2;
					}
				}
				else {
					$is_over = 1;
				}

				$time = $myorder['endtime'] - time();
				$Dday = floor($time / (60 * 60 * 24));
				$Dday_y = $time % (60 * 60 * 24);
				$Dhour = floor($Dday_y / (60 * 60));
				$Dhour_y = $Dday_y % (60 * 60);
				$Dminute = floor($Dhour_y / 60);
				$Dsecond = $Dhour_y % 60;
				$this->assign('day', $Dday);
				$this->assign('hour', $Dhour);
				$this->assign('minute', $Dminute);
				$this->assign('second', $Dsecond);
			}
			else if ($myorder['paid'] == 1) {
				$is_over = 5;
			}
			else {
				$is_over = 4;
			}

			$rank_all = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id))->order('bargain_nowprice,changetime')->select();

			foreach ($rank_all as $allkey => $allval) {
				if ($allval['tp_id'] == $myorder['tp_id']) {
					$myorder['rank'] = $allkey + 1;
				}
			}

			$myorder['helpcount'] = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'orderid' => $myorder['tp_id']))->count();
			$kanuser_list = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'orderid' => $myorder['tp_id']))->order('addtime desc')->limit(10)->select();

			foreach ($kanuser_list as $kankey => $kanval) {
				$kanuser_list[$kankey]['userinfo'] = $this->m_userinfo->where(array('token' => $this->token, 'wecha_id' => $kanval['friend']))->find();
			}

			$kanuser_count = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'orderid' => $myorder['tp_id']))->count();
			$this->assign('kanuser_list', $kanuser_list);
			$this->assign('kanuser_count', $kanuser_count);

			if ($orderid != 0) {
				$is_kanguo = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'orderid' => $orderid, 'friend' => $this->wecha_id))->find();
				$this->assign('is_kanguo', $is_kanguo);
			}
			else {
				if ($_GET['kanprice'] == '') {
					$xiuxi_count = $this->m_kanuser->where(array(
	'token'      => $this->token,
	'bargain_id' => $id,
	'orderid'    => $myorder['tp_id'],
	'addtime'    => array('gt', $myorder['logintime'])
	))->count();
					$xiuxi_sum = $this->m_kanuser->where(array(
	'token'      => $this->token,
	'bargain_id' => $id,
	'orderid'    => $myorder['tp_id'],
	'addtime'    => array('gt', $myorder['logintime'])
	))->sum('dao');
					$xiuxi_price = $xiuxi_sum / 100;
					$this->assign('xiuxi_count', $xiuxi_count);
					$this->assign('xiuxi_price', $xiuxi_price);
				}

				$this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'wecha_id' => $this->wecha_id))->save(array('logintime' => time()));
			}
		}
		else {
			$is_over = 0;
		}

		$this->assign('myorder', $myorder);
		$this->assign('is_over', $is_over);
		$rank_list = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id))->order('bargain_nowprice,changetime')->limit($bargain['rank_num'] ? $bargain['rank_num'] : 10)->select();

		foreach ($rank_list as $rankkey => $rankval) {
			$rank_list[$rankkey]['userinfo'] = $this->m_userinfo->where(array('token' => $this->token, 'wecha_id' => $rankval['wecha_id']))->find();
		}

		$this->assign('rank_list', $rank_list);
		$this->display();
	}

	public function new_goodsinfo()
	{
		$id = intval($_GET['id']);
		$bargain = S($id . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where(array('token' => $this->token, 'tp_id' => $id))->find();

			if ($bargain == '') {
				$this->error('该砍价活动不存在');
				exit();
			}

			S($id . 'bargain' . $this->token, $bargain);
		}

		if ($bargain['state'] == 0) {
			$this->error('该砍价活动已关闭');
			exit();
		}

		if ($bargain['is_new'] == 1) {
			$this->redirect('Wap/Bargain/index', array('token' => $this->token, 'id' => $_GET['id']));
			exit();
		}

		$this->assign('bargain', $bargain);
		$this->display();
	}

	public function new_fistblood()
	{
		$id = intval($_GET['id']);
		$bargain = S($id . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where(array('token' => $this->token, 'tp_id' => $id))->find();
			S($id . 'bargain' . $this->token, $bargain);
		}

		$kanprice = rand($bargain['kan_min'], $bargain['kan_max']);

		if (($bargain['original'] - $kanprice) <= $bargain['minimum']) {
			$kanprice = $bargain['original'] - $bargain['minimum'];
		}

		$myorder = $this->m_order->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bargain_id' => $id))->find();

		if ($myorder == '') {
			$userinfo = $this->m_userinfo->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();
			$add_order['token'] = $this->token;
			$add_order['wecha_id'] = $this->wecha_id;
			$add_order['bargain_id'] = $id;
			$add_order['endtime'] = time() + ($bargain['starttime'] * 60 * 60);
			$add_order['bargain_name'] = $bargain['name'];
			$add_order['bargain_logoimg'] = $bargain['logoimg1'];
			$add_order['bargain_original'] = $bargain['original'] / 100;
			$add_order['bargain_minimum'] = $bargain['minimum'] / 100;
			$add_order['bargain_nowprice'] = $bargain['original'] - $kanprice;
			$add_order['phone'] = $userinfo['tel'];
			$add_order['address'] = $userinfo['address'];
			$add_order['orderid'] = 0;
			$add_order['addtime'] = time();
			$add_order['logintime'] = time();
			$add_order['changetime'] = time();
			$id_order = $this->m_order->add($add_order);
			$add_kanuser['token'] = $this->token;
			$add_kanuser['wecha_id'] = $this->wecha_id;
			$add_kanuser['bargain_id'] = $id;
			$add_kanuser['orderid'] = $id_order;
			$add_kanuser['friend'] = $this->wecha_id;
			$add_kanuser['dao'] = $kanprice;
			$add_kanuser['addtime'] = time();
			$id_kanuser = $this->m_kanuser->add($add_kanuser);
			$this->redirect('Wap/Bargain/new_index', array('token' => $this->token, 'id' => $id, 'kanprice' => $kanprice / 100));
		}
		else {
			$this->m_order->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'bargain_id' => $id))->delete();
			$this->redirect('Wap/Bargain/new_fistblood', array('token' => $this->token, 'id' => $_GET['id']));
		}
	}

	public function new_kandao()
	{
		$id = intval($_GET['id']);
		$orderid = intval($_GET['orderid']);
		$bargain = S($id . 'bargain' . $this->token);

		if ($bargain == '') {
			$bargain = $this->m_bargain->where(array('token' => $this->token, 'tp_id' => $id))->find();
			S($id . 'bargain' . $this->token, $bargain);
		}

		$kanprice = rand($bargain['kan_min'], $bargain['kan_max']);
		$myorder = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'tp_id' => $orderid))->find();

		if (($myorder['bargain_nowprice'] - $kanprice) <= $bargain['minimum']) {
			$kanprice = $myorder['bargain_nowprice'] - $bargain['minimum'];
		}

		$kanuser = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'friend' => $this->wecha_id, 'orderid' => $orderid))->find();

		if ($kanuser == '') {
			$save_order['bargain_nowprice'] = $myorder['bargain_nowprice'] - $kanprice;
			$save_order['changetime'] = time();
			$this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'tp_id' => $orderid))->save($save_order);
			$add_kanuser['token'] = $this->token;
			$add_kanuser['wecha_id'] = $myorder['wecha_id'];
			$add_kanuser['bargain_id'] = $id;
			$add_kanuser['orderid'] = $orderid;
			$add_kanuser['friend'] = $this->wecha_id;
			$add_kanuser['dao'] = $kanprice;
			$add_kanuser['addtime'] = time();
			$this->m_kanuser->add($add_kanuser);
			$this->redirect('Wap/Bargain/new_index', array('token' => $this->token, 'id' => $id, 'orderid' => $orderid, 'kanprice' => $kanprice / 100));
		}
		else {
			$this->redirect('Wap/Bargain/new_index', array('token' => $this->token, 'id' => $id, 'orderid' => $orderid));
		}
	}

	public function new_kanuser_more()
	{
		if (IS_POST) {
			$this->wecha_id = $_POST['wecha_id'];
			$this->token = $_POST['token'];
			$id = intval($_POST['id']);
			$orderid = intval($_POST['orderid']);

			if ($orderid != 0) {
				$myorder = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'tp_id' => $orderid))->find();
			}
			else {
				$myorder = $this->m_order->where(array('token' => $this->token, 'bargain_id' => $id, 'wecha_id' => $this->wecha_id))->find();
			}

			$kanuser_list = $this->m_kanuser->where(array('token' => $this->token, 'bargain_id' => $id, 'orderid' => $myorder['tp_id']))->order('addtime desc')->limit($_POST['count'], 10)->select();
			$data['kanuser_list'] = '';

			foreach ($kanuser_list as $kanval) {
				$userinfo = $this->m_userinfo->where(array('token' => $this->token, 'wecha_id' => $kanval['friend']))->find();
				$userinfo['wechaname'] = mb_substr($userinfo['wechaname'], 0, 9, 'utf-8');
				$data['kanuser_list'] .= '<li class="clearfix"><div class="head_img"><img src="' . ($userinfo['portrait'] ? $userinfo['portrait'] : $this->siteUrl . '/tpl/static/bargain/new/images/portrait.jpg') . '" /></div><div class="bargain_content"><h1>' . ($userinfo['wechaname'] ? $userinfo['wechaname'] : '匿名') . '</h1><p>' . date('Y-m-d H:i', $kanval['addtime']) . '</p></div><div class="price">帮砍:<span>￥' . ($kanval['dao'] / 100) . '</span></div></li>';
			}

			$this->ajaxReturn($data, 'JSON');
		}
	}
}

?>
