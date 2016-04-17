<?php
class WzzAction extends WapAction{
   
   
    public function index(){
	    $token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		
	    $id=$this->_get('id');
		 $catgroy=$this->_get('catgroy');
        $where      = array('token'=>$this->token,'id'=>$id,'catgroy'=>$catgroy);
        $content    = M('Wzzmy')->where($where)->>select();
		
        
        $this->assign('content',$content);
        
        $this->display();
    }

    public function get_list(){


        echo '{result:1,msg:"请求成功!!"}';
    }

    public function test(){

        $this->display();
    }

}

?>