<?php
class StorenewindexReply
{	
	public $item;
	public $wechat_id;
	public $siteUrl;
	public $token;
	public function __construct($token,$wechat_id,$data,$siteUrl)
	{
		$this->item=M('New_product_set_reply')->where(array('id'=>$data['pid']))->find();
		$this->wechat_id=$wechat_id;
		$this->siteUrl=$siteUrl;
		$this->token=$token;
	}
	public function index(){
		$thisItem=$this->item;
		return array(array(array($thisItem['title'],$thisItem['info'],$thisItem['logourl'],$this->siteUrl.U('Wap/Storenew/index',array('cid'=>$thisItem['cid'],'token'=>$this->token,'wecha_id'=>$this->wechat_id)))),'news');
	}
	
}
?>

