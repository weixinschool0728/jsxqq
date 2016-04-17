<?php
class YouSetDiscountAction extends WapAction
{
	public $userinfo;
	public $YouSetDiscount;

	public function _initialize()
	{
		parent::_initialize();
		D('Userinfo')->synchronousFansInfo($this->wecha_id, $this->token);
		$this->userinfo = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->find();

		if ($this->userinfo == '') {
			$this->userinfo = $this->fans;

			if ($this->userinfo == '') {
				$this->error('获取不到你的信息');
			}
		}

		$this->assign('userinfo', $this->userinfo);
		$id = $this->_get('id', 'intval');
		$YouSetDiscount = S($id . 'YouSetDiscount' . $this->token);

		if ($YouSetDiscount == NULL) {
			$YouSetDiscount = M('yousetdiscount')->where(array('id' => $id, 'token' => $this->token, 'is_open' => 0))->find();

			if ($YouSetDiscount == NULL) {
				$this->error('活动不存在');
				exit();
			}
			else {
				S($id . 'YouSetDiscount' . $this->token, $YouSetDiscount);
			}
		}

		$this->YouSetDiscount = $YouSetDiscount;
		$wxpic = explode('http', $this->YouSetDiscount['wxpic']);

		if (count($wxpic) <= 1) {
			$this->YouSetDiscount['wxpic'] = C('site_url') . $this->YouSetDiscount['wxpic'];
		}

		$this->assign('info', $this->YouSetDiscount);
	}

	public function index()
	{
		$id = $this->_get('id', 'intval');
		$share_key = $this->_get('share_key', 'trim');
		$this->assign('share_key', $share_key);
		$now = time();
		if (($_GET['tel'] != '') && ($this->wecha_id != '')) {
			$userinfo_tel = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $this->wecha_id))->save(array('tel' => $_GET['tel'], 'isverify' => 1));
			$this->userinfo['tel'] = $_GET['tel'];
			$this->redirect('Wap/YouSetDiscount/index', array('token' => $this->token, 'id' => $id, 'share_key' => $_GET['share_key']));
			exit();
		}

		if ($now < $this->YouSetDiscount['startdate']) {
			$is_over = 1;
		}
		else if ($this->YouSetDiscount['enddate'] < $now) {
			$is_over = 2;
		}
		else {
			$is_over = 0;
		}

		$this->assign('is_over', $is_over);

		if ($share_key != '') {
			$is_my = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $id, 'wecha_id' => $this->wecha_id, 'share_key' => $share_key))->find();

			if ($is_my != '') {
				$this->redirect('Wap/YouSetDiscount/index', array('token' => $this->token, 'id' => $id));
				exit();
			}
		}

		$my = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $id, 'wecha_id' => $this->wecha_id))->find();

		if ($my == '') {
			$add_user['token'] = $this->token;
			$add_user['wecha_id'] = $this->wecha_id;
			$add_user['yid'] = $id;
			$add_user['share_key'] = md5($this->token . $this->wecha_id . $id . $now);
			$add_user['addtime'] = $now;
			$uid = M('yousetdiscount_users')->add($add_user);
			$my = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $id, 'wecha_id' => $this->wecha_id))->find();
		}

		if ($share_key != '') {
			$user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $id, 'share_key' => $share_key))->find();
			$user['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $user['wecha_id']))->find();
			$wota = 'TA';
		}
		else {
			$user = $my;
			$user['userinfo'] = $this->userinfo;
			$wota = '自己';
			if (($this->YouSetDiscount['is_attention'] == 1) && !$this->isSubscribe()) {
				$this->memberNotice('', 1);
			}
			else {
				if ((($this->YouSetDiscount['is_reg'] == 1) || ($this->YouSetDiscount['is_sms'] == 1)) && empty($this->userinfo['tel'])) {
					if ($this->YouSetDiscount['is_sms'] == 0) {
						$this->memberNotice();
					}
					else {
						$this->assign('sms', 1);
						$this->assign('memberNotice', '<div style="display:none"></div>');
					}
				}
				else {
					if (($this->YouSetDiscount['is_sms'] == 1) && empty($this->userinfo['tel']) && ($this->userinfo['isverify'] != 1)) {
						$this->assign('sms', 1);
						$this->assign('memberNotice', '<div style="display:none"></div>');
					}
				}
			}
		}

		$playcount = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => $id, 'user' => $user['share_key'], 'help' => $my['share_key']))->getField('playcount');
		$this->assign('playcount', intval($playcount));
		$this->assign('wota', $wota);
		$this->assign('user', $user);
		$helps = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => $id, 'user' => $user['share_key']))->order('discount desc')->select();

		foreach ($helps as $hk => $hv) {
			$help_user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $id, 'share_key' => $hv['help']))->find();
			D('Userinfo')->synchronousFansInfo($help_user['wecha_id'], $this->token);
			$helps[$hk]['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $help_user['wecha_id']))->find();
			$helps[$hk]['helps_data'] = M('yousetdiscount_helps_data')->where(array('token' => $this->token, 'yid' => $id, 'hid' => $hv['id']))->select();
		}

		$this->assign('helps', $helps);
		$helps_sum = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => $id, 'user' => $user['share_key']))->sum('discount');
		$this->assign('helps_sum', round($helps_sum ? $helps_sum : 0, 2));
		$direction = M('yousetdiscount_direction')->where(array('token' => $this->token, 'yid' => $id))->order('id')->select();
		$this->assign('direction', $direction);

		if ($_GET['game'] == 'go') {
			if ($share_key == '') {
				$shengyucishu = $this->YouSetDiscount['my_count'] - intval($playcount);
				$error_title = '您的次数已用完';
			}
			else {
				$shengyucishu = $this->YouSetDiscount['friends_count'] - intval($playcount);
				$error_title = '您帮TA的次数已用完';
			}

			if (($shengyucishu < 1) || ($user['state'] == 1)) {
				$this->redirect('Wap/YouSetDiscount/index', array('token' => $this->token, 'id' => $id, 'share_key' => $share_key));
				exit();
			}

			$this->display('game');
		}
		else if ($_GET['fx'] == 'go') {
			$this->display('fx');
		}
		else {
			$this->display();
		}
	}

	public function todiscount()
	{
		if ($_POST['share_key'] == '') {
			$user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'wecha_id' => $_POST['wecha_id']))->find();
			$help = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $user['share_key'], 'help' => $user['share_key']))->find();

			if ($help['playcount'] < $this->YouSetDiscount['my_count']) {
				M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'wecha_id' => $_POST['wecha_id']))->setInc('discount', floatval($_POST['this_discount']));

				if ($help == '') {
					$help_id = M('yousetdiscount_helps')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $user['share_key'], 'help' => $user['share_key'], 'discount' => floatval($_POST['this_discount']), 'playcount' => 1));
					M('yousetdiscount_helps_data')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'hid' => $help_id, 'discount' => floatval($_POST['this_discount']), 'addtime' => time()));
				}
				else {
					M('yousetdiscount_helps')->where(array('token' => $this->token, 'id' => $help['id']))->save(array('discount' => floatval($help['discount']) + floatval($_POST['this_discount']), 'playcount' => $help['playcount'] + 1));
					M('yousetdiscount_helps_data')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'hid' => $help['id'], 'discount' => floatval($_POST['this_discount']), 'addtime' => time()));
				}
			}
		}
		else {
			$user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'wecha_id' => $_POST['wecha_id']))->find();
			$help = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $_POST['share_key'], 'help' => $user['share_key']))->find();

			if ($help['playcount'] < $this->YouSetDiscount['friends_count']) {
				M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'share_key' => $_POST['share_key']))->setInc('discount', floatval($_POST['this_discount']));

				if ($help == '') {
					$help_id = M('yousetdiscount_helps')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $_POST['share_key'], 'help' => $user['share_key'], 'discount' => floatval($_POST['this_discount']), 'playcount' => 1));
					M('yousetdiscount_helps_data')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'hid' => $help_id, 'discount' => floatval($_POST['this_discount']), 'addtime' => time()));
				}
				else {
					M('yousetdiscount_helps')->where(array('token' => $this->token, 'id' => $help['id']))->save(array('discount' => floatval($help['discount']) + floatval($_POST['this_discount']), 'playcount' => $help['playcount'] + 1));
					M('yousetdiscount_helps_data')->add(array('token' => $this->token, 'yid' => intval($_POST['id']), 'hid' => $help['id'], 'discount' => floatval($_POST['this_discount']), 'addtime' => time()));
				}
			}
		}

		$data['error'] = 0;
		$this->ajaxReturn($data, 'JSON');
	}

	public function iscount()
	{
		if ($_POST['share_key'] == '') {
			$user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'wecha_id' => $_POST['wecha_id']))->find();
			$help = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $user['share_key'], 'help' => $user['share_key']))->find();

			if ($help['playcount'] < $this->YouSetDiscount['my_count']) {
				$data['error'] = 0;
			}
			else {
				$data['error'] = 1;
			}
		}
		else {
			$user = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'wecha_id' => $_POST['wecha_id']))->find();
			$help = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => intval($_POST['id']), 'user' => $_POST['share_key'], 'help' => $user['share_key']))->find();

			if ($help['playcount'] < $this->YouSetDiscount['friends_count']) {
				$data['error'] = 0;
			}
			else {
				$data['error'] = 1;
			}
		}

		$this->ajaxReturn($data, 'JSON');
	}

	public function sms()
	{
		if ($_POST['tel'] != '') {
			$is_tel = M('userinfo')->where(array('token' => $_POST['token'], 'tel' => $_POST['tel'], 'isverify' => 1))->find();

			if ($is_tel == '') {
				$params = array();
				$session_sms = session($_POST['wecha_id'] . 'CODEYOUSETDISCOUNT' . $_POST['token'] . $_POST['id']);
				if ((time() < $session_sms['time']) && ($session_sms['tel'] == $_POST['tel'])) {
					$code = $session_sms['code'];
				}
				else {
					session($_POST['wecha_id'] . 'CODEYOUSETDISCOUNT' . $_POST['token'] . $_POST['id'], NULL);
					$code = rand(100000, 999999);
					$session_sms['tel'] = $_POST['tel'];
					$session_sms['code'] = $code;
					$session_sms['time'] = time() + (60 * 30);
					session($_POST['wecha_id'] . 'CODEYOUSETDISCOUNT' . $_POST['token'] . $_POST['id'], $session_sms);
				}

				$params['sms'] = array('token' => $this->token, 'mobile' => $_POST['tel'], 'content' => '您的验证码是：' . $code . '。 此验证码30分钟内有效，请不要把验证码泄露给其他人。如非本人操作，可不用理会！');
				$data['error'] = MessageFactory::method($params, 'SmsMessage');
				$this->ajaxReturn($data, 'JSON');
			}
			else {
				$data['error'] = 'tel';
				$this->ajaxReturn($data, 'JSON');
			}
		}
	}

	public function smsyz()
	{
		$session_sms = session($_POST['wecha_id'] . 'CODEYOUSETDISCOUNT' . $_POST['token'] . $_POST['id']);

		if ($_POST['code'] != $session_sms['code']) {
			$data['error'] = 1;
		}
		else if ($_POST['tel'] != $session_sms['tel']) {
			$data['error'] = 2;
		}
		else if ($session_sms['time'] < time()) {
			$data['error'] = 3;
		}
		else {
			$data['error'] = 0;
		}

		$this->ajaxReturn($data, 'JSON');
	}
}

?>
