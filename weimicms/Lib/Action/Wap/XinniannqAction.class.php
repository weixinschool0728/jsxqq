<?php

class XinniannqAction extends WapAction {

	public function index(){
	    $token = $this->_get('token');
		$nqs=M('Xinniannq')->where(array('token'=> $token))->getField('nq',true);
		//$nqs['nq'] =str_replace("&amp;","&",$nqs['nq']);
		
	 foreach($nqs as $key=>$value){
	
       $resault[$key]=str_replace("&amp;","&",$value);
       }
      
		
		$this->assign('nq',$resault);
	
		$this->display();

	}
		
	
}