<?php
class WzzReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Wzz')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],'',$thisItem['reply_pic'],$this->siteUrl.U('Wap/Wzz/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
}

?>

