<?php
class DiscountAction extends BaseAction{
    public function index(){
        $agent = $_SERVER['HTTP_USER_AGENT']; 
        if(!strpos($agent,"MicroMessenger")) {
          //  echo '此功能只能在微信浏览器中使用';exit;
        }
        $token      = $this->_get('token'); 
        $hid         = $this->_get('hid'); 
		$wecha_id        = $this->_get('wecha_id'); 
        $where      = array('token'=>$token,'hid'=>$hid);              
        $set =  M('Discount')->where(array('token'=>$token,'id'=>$hid))->find();
		if (strtotime($set['startdate'])>strtotime(date('Y-m-d H:i:s',time()))||strtotime($set['enddate'])<strtotime(date('Y-m-d H:i:s',time()))) 
		echo "请于订购时间之内下单";
		else
		{
		$userinfo =  M('Discount_input')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'status'=>0))->find();
        $this->assign('userinfo',$userinfo);
		$this->assign('set',$set);
        $this->display();
		}
    }
    
  
      public function book(){ 
        
        if($_POST['action'] == 'book'){           
$data['token']  =  $this->_get('token');
$data['wecha_id']  =  $this->_get('wecha_id');
$data['pname']  =  $this->_post('pname');
$data['adddate']  =  date('Y-m-d H:i:s',time());
$data['status']  =  "未处理";
$data['name']  =  $this->_post('name');
$data['phone']  =  $this->_post('phone');
$data['hid']  =  $this->_get('hid');

 $count = M('Discount_input')->where(array('token'=>$data['token'],'wecha_id'=>$data['wecha_id'],'status'=>0,'hid'=>$data['hid']))->count();

if ($count<1) $order = M('Discount_input')->data($data)->add();       
          
        if($order){
                echo "下订单成功";
            }else{
                echo "您已经下过此商品订单";
            }            
 
        }    
            
        
    }

}
    
?>