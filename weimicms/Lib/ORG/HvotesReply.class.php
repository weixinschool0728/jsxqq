<?php
class HvotesReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('Hvotes')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['info'],$thisItem['picurl'],$this->siteUrl.U('Wap/Hvotes/index',array('id'=>$thisItem['id'],'token'=>$this->token,'wecha_id'=>$this->wechat_id,'type'=>'new')))),'news');
	}
}
?>