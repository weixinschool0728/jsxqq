<?php
class WeimicmsvoteAction extends WapAction{

    public function __construct(){
		
        parent::_initialize();
    }


	public function index(){
		
		
		$token		= $this->_get('token');
		$wecha_id	= $this->wecha_id;
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $this->assign('id',$id);

		$t_vote	    = M('Mbvote');
        $t_record   = M('Mbvote_record');
		$where 		= array('token'=>$token,'id'=>$id,'status'=>'1');
		$vote 	    = $t_vote->where($where)->find();
		 
        $clicks = M('Mbvote')->where(array('token' => $token, 'id' => $id))->setInc('click');
		
        if(empty($vote)){
            exit('活动还没开启');
        }

        $success = array();
		$MbvoteWhere = array('vid'=>$vote['id']);
		$item_paiming  = M('Mbvote_item')->where($MbvoteWhere)->order('rank DESC')->select();  
		$keyword = $this->_post('keyword');
		if(!empty($keyword)){
			
			$MbvoteWhere["item"] = array('like','%'.$keyword.'%');
			$this->assign('keyword',$keyword);
		}
        $Mbvote_item  = M('Mbvote_item')->where($MbvoteWhere)->order('rank DESC')->select();
		//dump($Mbvote_item);exit;
		   
        
  
        //检查是否投票过
        $t_item = M('Mbvote_item');
        $where = array('wecha_id'=>$wecha_id,'vid'=>$id);
        $Mbvote_record  = $t_record->where($where)->find();
		

        if($Mbvote_record && $Mbvote_record != NULL){
            $arritem = trim($Mbvote_record['item_id'],',');
            $map['id'] = array('in',$arritem);
            $hasitems = $t_item->where($map)->field('item')->getField('item',true);               
            $success = array('err'=>3,'info'=>'您已经投过票了','hasitems'=>join(',',$hasitems));
        }
      
        $item_count = M('Mbvote_item')->where('vid='.$id)->order('rank DESC')->select();
        
        $vcount     = M('Mbvote_item')->where(array('vid'=>$vote['id']))->sum("vcount");
		$vcountuser = M('Mbvote_item')->where(array('vid'=>$vote['id']))->count();
       
        foreach ($item_count as $k=>$value) {
			if(!empty($Mbvote_item[$k])){
			   $Mbvote_item[$k]['per']=(number_format(($value['vcount'] / $vcount),2))*100;
			   $Mbvote_item[$k]['pro']=$value['vcount'];
			}
        } 

        if($vote['statdate']>time()){
            $success = array('err'=>1,'info'=>'投票还没有开始');
        }

        if($vote['enddate']<time()){
            $success = array('err'=>2,'info'=>'投票已经结束');
        }
        
        $this->assign('user_name',$this->wxuser['weixin']);
        $this->assign('success',$success);
        $this->assign('total',$total);
		 $this->assign('total',$total);
        $this->assign('Mbvote_item', $Mbvote_item);
        $this->assign('vcount',$vcount);
		$this->assign('vcountuser',$vcountuser);
		 $this->assign('vote',$vote);
		 $this->assign('item_paiming',$item_paiming);
		$this->display();
	}

	public function add_vote(){	
		$trueip 	= ip2long($_SERVER['REMOTE_ADDR']);
		$token 		= $this->_post('token');
		$wecha_id	= $this->_post('wecha_id');
		$tid 		= $this->_post('tid');
		$chid 		= rtrim($this->_post('chid'),',');	
		$recdata 	= M('Mbvote_record');
        $where   	= array('vid'=>$tid,'wecha_id'=>$wecha_id,'token'=>$token);  
        $recode 	= $recdata->where($where)->find();
//dump("123");exit;

        $t_vote = M('Mbvote'); 
        $Mbvote_info = $t_vote->where(array('token'=>$token,'id'=>$tid))->find(); 
		     
         
        if($Mbvote_info['statdate']>time()){
            $arr = array('success'=>false,'msg'=>'投票还没有开始');
            echo json_encode($arr);
            exit;
        }

        if($Mbvote_info['enddate']<time()){
            $arr = array('success'=>false,'msg'=>'投票已经结束');
            echo json_encode($arr);
            exit;
        }
        
        if($Mbvote_info['status']==0){
            $arr = array('success'=>false,'msg'=>'投票已经关闭');
            echo json_encode($arr);
            exit;
        }
       
        if($Mbvote_info['refresh'] == 1){
    		$r_where 	= array('token'=>$token,'vid'=>$tid,'wecha_id'=>$wecha_id,'trueip'=>$trueip);
            $is_voted 	= $recdata->where($r_where)->find();
			
			
            if($is_voted){
            	$arr = array('success'=>false,'msg'=>'禁止恶意刷票，换了马甲以为不认识你吗？');
            	echo json_encode($arr);
            	exit;
            }
        }
        $data = array('item_id'=>$chid,'token'=>$token,'vid'=>$tid,'wecha_id'=>$wecha_id,'touch_time'=>time(),'touched'=>1,'trueip'=>$trueip);     
		$ok = $recdata->add($data);
        $map['id'] = array('in',$chid);
        $t_item = M('Mbvote_item');
        $t_item->where($map)->setInc('vcount');       
        $t_vote->where(array('token'=>$token,'id'=>$tid))->setInc('count'); //增加投票人数
        $arr=array('success'=>true,'msg'=>'投票成功');
        echo json_encode($arr);        
        exit;
	}
}?>