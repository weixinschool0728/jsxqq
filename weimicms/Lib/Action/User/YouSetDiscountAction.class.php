<?php
class YouSetDiscountAction extends UserAction
{
	public function _initialize()
	{
		parent::_initialize();
		
		$this->canUseFunction('YouSetDiscount');
	}

	public function index()
	{
		$where['token'] = $this->token;
		$where_page['token'] = $this->token;

		if (!empty($_GET['search'])) {
			$where['name'] = array('like', '%' . $_GET['search'] . '%');
			$where_page['search'] = $_GET['search'];
		}

		$count = M('yousetdiscount')->where($where)->count();
		$page = new Page($count, 8);

		foreach ($where_page as $key => $val) {
			$page->parameter .= $key . '=' . urlencode($val) . '&';
		}

		$show = $page->show();
		$list = M('yousetdiscount')->where($where)->order('addtime desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $key => $val) {
			$list[$key]['allcount'] = M('yousetdiscount_users')->where(array('token' => $this->token, 'yid' => $val['id']))->count();
			$list[$key]['ydhcount'] = M('yousetdiscount_users')->where(array(
	'token'    => $this->token,
	'yid'      => $val['id'],
	'discount' => array('gt', 0),
	'state'    => 1
	))->count();
			$list[$key]['wdhcount'] = M('yousetdiscount_users')->where(array(
	'token'    => $this->token,
	'yid'      => $val['id'],
	'discount' => array('gt', 0),
	'state'    => 0
	))->count();
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$this->display();
	}

	public function set()
	{
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		$YouSetDiscount = M('yousetdiscount')->where($where)->find();

		if (IS_POST) {
			$set['token'] = $this->token;
			$set['keyword'] = $_POST['keyword'];
			$set['name'] = $_POST['name'];
			$set['wxpic'] = $_POST['wxpic'];
			$set['wxtitle'] = $_POST['wxtitle'];
			$set['wxinfo'] = $_POST['wxinfo'];
			$set['fxpic'] = $_POST['fxpic'];
			$set['fxtitle'] = $_POST['fxtitle'];
			$set['fxtitle2'] = $_POST['fxtitle2'];
			$set['fxinfo'] = $_POST['fxinfo'];
			$set['fxinfo2'] = $_POST['fxinfo2'];
			$set['startdate'] = strtotime($_POST['startdate']);
			$set['enddate'] = strtotime($_POST['enddate']);
			$set['info'] = $_POST['info'];
			$set['bg1'] = $_POST['bg1'];
			$set['bg2'] = $_POST['bg2'];
			$set['bg3'] = $_POST['bg3'];
			$set['gamepic1'] = $_POST['gamepic1'];
			$set['gamepic2'] = $_POST['gamepic2'];
			$set['my_count'] = intval($_POST['my_count']);
			$set['friends_count'] = intval($_POST['friends_count']);
			$set['playtime'] = intval($_POST['playtime']);
			$set['discount_endtime'] = strtotime($_POST['discount_endtime']);

			if ($_POST['discount_type'] != '') {
				$set['discount_type'] = $_POST['discount_type'];
			}

			$set['money_start'] = floatval($_POST['money_start']);
			$set['money_end'] = floatval($_POST['money_end']);
			$set['discount_start'] = floatval($_POST['discount_start']);
			$set['discount_end'] = floatval($_POST['discount_end']);
			$set['discount_min'] = floatval($_POST['discount_min']);
			$set['is_sms'] = intval($_POST['is_sms']);
			$set['is_attention'] = intval($_POST['is_attention']);
			$set['is_reg'] = intval($_POST['is_reg']);
			$set['is_open'] = intval($_POST['is_open']);
			$at_least = $_POST['at_least'];
			$discount = $_POST['discount'];

			if ($YouSetDiscount) {
				$del_direction = M('yousetdiscount_direction')->where(array('token' => $this->token, 'yid' => $id))->delete();

				foreach ($at_least as $ak => $av) {
					$add_direction['token'] = $this->token;
					$add_direction['yid'] = $id;
					$add_direction['at_least'] = $av;
					$add_direction['discount'] = $discount[$ak];
					$id_direction = M('yousetdiscount_direction')->add($add_direction);
				}

				M('yousetdiscount')->where($where)->save($set);
				$this->handleKeyword($id, 'YouSetDiscount', $this->_post('keyword', 'trim'));
				S($id . 'YouSetDiscount' . $this->token, NULL);
				$this->success('修改成功', U('User/YouSetDiscount/index', array('token' => $this->token)));
			}
			else {
				$set['addtime'] = time();
				$id = M('yousetdiscount')->add($set);

				foreach ($at_least as $ak => $av) {
					$add_direction['token'] = $this->token;
					$add_direction['yid'] = $id;
					$add_direction['at_least'] = $av;
					$add_direction['discount'] = $discount[$ak];
					$id_direction = M('yousetdiscount_direction')->add($add_direction);
				}

				$this->handleKeyword($id, 'YouSetDiscount', $this->_post('keyword', 'trim'));
				
				$this->success('添加成功', U('User/YouSetDiscount/index', array('token' => $this->token)));
			}
		}
		else {
			$this->assign('set', $YouSetDiscount);
			$direction_list = M('yousetdiscount_direction')->where(array('token' => $this->token, 'yid' => $id))->order('id')->select();
			$direction_num = count($direction_list);
			$this->assign('direction_list', $direction_list);
			$this->assign('direction_num', $direction_num);
			$this->display();
		}
	}

	public function del()
	{
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		$YouSetDiscount = M('yousetdiscount')->where($where)->find();
		$this->handleKeyword($id, 'YouSetDiscount', $YouSetDiscount['keyword'], 0, 1);
		S($id . 'YouSetDiscount' . $this->token, NULL);
		M('yousetdiscount')->where($where)->delete();
		M('yousetdiscount_direction')->where(array('token' => $this->token, 'yid' => $id))->delete();
		$this->success('删除成功', U('User/YouSetDiscount/index', array('token' => $this->token)));
	}

	public function data()
	{
		$id = $this->_get('id', 'intval');
		$where = array('token' => $this->token, 'id' => $id);
		$YouSetDiscount = M('yousetdiscount')->where($where)->find();
		$this->assign('info', $YouSetDiscount);

		if ($_GET['ydj'] == 'go') {
			$where_users['state'] = 1;
		}
		else {
			$where_users['state'] = 0;
		}

		$where_users['token'] = $this->token;
		$where_users['yid'] = $id;
		$where_users['discount'] = array('gt', 0);

		if ($_GET['search'] != '') {
			$where_users['id'] = $_GET['search'] - 10000000;
		}

		$count = M('yousetdiscount_users')->where($where_users)->count();
		$page = new Page($count, 15);
		$show = $page->show();
		$list = M('yousetdiscount_users')->where($where_users)->order('discount desc')->limit($page->firstRow . ',' . $page->listRows)->select();

		foreach ($list as $k => $v) {
			$list[$k]['userinfo'] = M('userinfo')->where(array('token' => $this->token, 'wecha_id' => $v['wecha_id']))->find();
			$list[$k]['discount'] = M('yousetdiscount_helps')->where(array('token' => $this->token, 'yid' => $id, 'user' => $v['share_key']))->sum('discount');
		}

		$this->assign('page', $show);
		$this->assign('list', $list);
		$direction = M('yousetdiscount_direction')->where(array('token' => $this->token, 'yid' => $id))->order('discount')->select();
		$this->assign('direction', $direction);
		$this->display();
	}

	public function duijiang()
	{
		$id = $this->_get('id', 'intval');
		$save['state'] = $_GET['state'] ? 0 : 1;

		if ($_GET['direction_id'] != '') {
			$save['did'] = intval($_GET['direction_id']);
		}

		if ($_GET['state'] == 1) {
			$save['did'] = 0;
		}

		M('yousetdiscount_users')->where(array('token' => $this->token, 'id' => $id))->save($save);
		$this->redirect('User/YouSetDiscount/data', array('token' => $this->token, 'id' => $_GET['yid'], 'ydj' => $_GET['ydj']));
	}
}

?>
