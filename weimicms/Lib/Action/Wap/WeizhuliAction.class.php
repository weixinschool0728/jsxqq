<?php
class WeizhuliAction extends WapAction
{
    public function __construct()
    {
        parent::_initialize();
    }
    public function index()
    {
        $token = $this->_get('token');
        $trueip = get_client_ip();
        $wecha_id = $this->wecha_id;
        $do = $this->_get('do');
        $id = $this->_get('id');
		 $ids = $this->_get('ids');
        $user = $this->_get('user');
        $action = $this->_get('action');
        $t_zhuli = M('weizhuli');
        $where = array('token' => $token, 'id' => $ids);
        $zhuli = $t_zhuli->where(array('token' => $token, 'id' =>$ids))->find();
		//dump(  $zhuli);exit;
        $uip = M('weizhuli_record')->where(array('token' => $token, 'item_id' => $ids, 'trueip' => $trueip))->find();
		
		//如果已经助力过了
		if($uip['ip']=$trueip){

		if(!empty($do)){
		  $iszhuli=1;
		 
		 //dump( $iszhuli);exit;
		 $this->assign('iszhuli', $iszhuli);
		 $this->assign('zhuli', $zhuli);
		  $this->assign('ids', $ids);
		 $this->display();exit;}
		}
        //dump($zhuli['zjjf']);exit;
        if (empty($action)) {
            if (empty($uip)) {
                if ($zhuli['mode'] == 0) {
                    $a = rand($zhuli['min'], $zhuli['max']);
                    $click = M('weizhuli_user')->where(array('token' => $token, 'wecha_id' => $user))->setInc('score', $a);
					
                } elseif ($zhuli['mode'] == 1) {
                    $a = $zhuli['zjjf'];
                    $click = M('weizhuli_user')->where(array('token' => $token, 'wecha_id' => $user))->setInc('score', $zhuli['zjjf']);
				//dump($a);exit;
                } else {
                    $a = $zhuli['zjjf'];
                    $click = M('weizhuli_user')->where(array('token' => $token, 'wecha_id' => $user))->setInc('score', $zhuli['zjjf']);
                }
            }
            if ($uip) {
                $a = $uip['score'];
            }
        }
		$this->assign('a', $a);
        //dump($uip);exit;
        //微发财官方旗舰店:012zg.taobao.com     ip判断写入
		 
		$uips = M('weizhuli_record')->where(array('token' => $token, 'item_id' => $ids, 'trueip' => $trueip))->find();
		//dump($trueip);exit;
        if ($user) {
            if (empty($uips)) {
                $date['wecha_id'] = $user;
                $date['token'] = $token;
                $date['item_id'] = $ids;
                $date['trueip'] = $trueip;
                $date['score'] = $a;
				
				$enter = M('weizhuli_record')->add($date);
                $clicks = M('weizhuli_user')->where(array('token' => $token, 'wecha_id' => $user))->setInc('num');
            }
        }
        $this->assign('zhuli', $zhuli);
        $info = M('weizhuli_user')->where(array('token' => $token, 'wecha_id' => $this->wecha_id))->find();
		
        if (empty($info)) {
            if (empty($do)) {
                $this->redirect('Weizhuli/reg', array('token' => $token, 'wecha_id' => $wecha_id, 'ids' => $ids, 'action' => '1'), 0, '页面跳转中...');
            }
        } else {
            $this->assign('info', $info);
            $this->assign('token', $token);
            $this->assign('user', $user);
            $this->assign('wecha_id', $wecha_id);
            $this->assign('ids', $ids);
        }
        
        $this->display();
    }
    public function reg()
    {
        $token = $this->_get('token');
        $wecha_id = $this->wecha_id;
        $ids = $this->_get('ids');
        $trueip = get_client_ip();
        //dump($id );exit;
        $this->assign('token', $token);
        $this->assign('wecha_id', $wecha_id);
        $this->assign('ids', $ids);
        $where = array('token' => $token, 'id' => $ids);
        $t_zhuli = M('weizhuli');
        $zhuli = $t_zhuli->where($where)->find();
        $this->assign('zhuli', $zhuli);
		
        if (IS_POST) {
		
            $where['token'] = session('token');
            $data['token'] = $token;
            $data['pid'] = $ids;
            $data['wecha_id'] = $wecha_id;
            $data['tel'] = strip_tags($_POST['tel']);
            $data['name'] = $_POST['name'];
            $data['score'] = $zhuli['csfz'];
            //dump($data['score']);exit;
            if (empty($data['wecha_id'])) {
                $this->error('注册失败！请先关注公众号后，再注册');
            } else {
                $result = M('weizhuli_user')->add($data);
                $date['wecha_id'] = $wecha_id;
                $date['token'] = $token;
                $date['item_id'] = $ids;
                $date['trueip'] = $trueip;
                $enter = M('weizhuli_record')->add($date);
            }
            if ($result) {
                $this->success('注册成功！', U('Weizhuli/index', array('token' => $this->token, 'wecha_id' => $this->wecha_id, 'ids' => $ids, 'action' => '1')));
            } else {
                $this->error('注册失败！');
            }
        }
        $this->display();
    }
		public function mobile(){
	
		$this->selfform=M('selfform_value');
		$orders=$this->selfform->where(array('token'=>$this->token))->order('time desc')->limit(0,1)->find();
		
		
		
			
			$str="\r\n恭喜您有新的活动报名订单\r\n下单时间：".date('Y-m-d H:i:s',$orders['time'])."\r\n";
			
			
			
			return $str;
		
	}
    public function rule()
    {
        $token = $this->_get('token');
        $wecha_id = $this->wecha_id;
        $id = $this->_get('id');
        $this->assign('token', $token);
        $this->assign('wecha_id', $wecha_id);
        $this->assign('id', $id);
        $where = array('token' => $token, 'id' => $id);
        $t_zhuli = M('weizhuli');
        $zhuli = $t_zhuli->where($where)->find();
        $this->assign('zhuli', $zhuli);
        $this->display();
    }
    public function sorts()
    {
        $token = $this->_get('token');
        $wecha_id = $this->wecha_id;
        $id = $this->_get('id');
        $this->assign('token', $token);
        $this->assign('wecha_id', $wecha_id);
        $this->assign('id', $id);
        $t_zhuli = M('weizhuli');
        $where = array('token' => $token, 'id' => $id);
        $zhuli = $t_zhuli->where($where)->find();
        $t_zhuli = M('weizhuli_user')->where(array('token' => $token))->limit(10)->select();
        //dump($t_zhuli);exit;
        $this->assign('zhuli', $zhuli);
        $this->assign('t_zhuli', $t_zhuli);
        $this->display();
    }
   
}