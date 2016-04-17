<?php

class FunctionLibrary_Bargain
{
	public $sub;
	public $token;

	public function __construct($token, $sub)
	{
		$this->sub = $sub;
		$this->token = $token;
	}

	public function index()
	{
		if (!$this->sub) {
			return array('name' => '微砍价', 'subkeywords' => 1, 'sublinks' => 1);
		}
		else {
			$db = M('bargain');
			$where = array('token' => $this->token);
			$items = $db->where($where)->select();
			$arr = array(
				'name'        => '微砍价',
				'subkeywords' => array(),
				'sublinks'    => array()
				);

			if ($items) {
				foreach ($items as $v) {
					$arr['subkeywords'][$v['tp_id']] = array('name' => $v['name'], 'keyword' => $v['keyword']);
					$arr['sublinks'][$v['tp_id']] = array('name' => $v['name'], 'link' => '{siteUrl}/index.php?g=Wap&m=Bargain&a=index&token=' . $this->token . '&wecha_id={wechat_id}&id=' . $v['tp_id']);
				}
			}

			return $arr;
		}
	}
}


?>
