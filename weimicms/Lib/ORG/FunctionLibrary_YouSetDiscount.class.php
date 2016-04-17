<?php
class FunctionLibrary_YouSetDiscount{
	public $sub;
	public $token;
	function __construct($token,$sub) {
		$this->sub=$sub;
		$this->token=$token;
	}
	function index(){
		if (!$this->sub){
			return array(
			'name'=>'优惠接力',
			'subkeywords'=>1,
			'sublinks'=>1,
			);
		}else {
			$db		= M('yousetdiscount');
			$where	=array('token'=>$this->token);
			
/*			$count  = $db->where($where)->count();
			$Page   = new Page($count,5);
			$show   = $Page->show();
			$list 	= $db->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id DESC')->select();*/
			$items 	= $db->where($where)->order('addtime DESC')->select();

			$arr=array(
			'name'=>'优惠接力',
			'subkeywords'=>array(
			),
			'sublinks'=>array(
			),
			);
			if ($items){
				foreach ($items as $v){
					$arr['subkeywords'][$v['id']]=array('name'=>$v['name'],'keyword'=>$v['keyword']);
					$arr['sublinks'][$v['id']]=array('name'=>$v['name'],'link'=>'{siteUrl}/index.php?g=Wap&m=YouSetDiscount&a=index&token='.$this->token.'&wecha_id={wechat_id}&id='.$v['id']);
				}
			}
			return $arr;	
		}
	}
}