<?php
class WeizhuliAction extends UserAction{
	
	/**转发有礼首页**/
    public function index(){
		$this->canUseFunction('Weizhuli');
        $token=session('token');
		$Weizhuli=M('Weizhuli');
		$list=$Weizhuli->where(array('token'=>$token))->select();
		//dump($list);exit;
		$this->assign('list',$list);
        $this->display();
		
	}
	/**添加活动**/
	public function add(){
     	$this->canUseFunction('Weizhuli');
		$token=session('token');
		 if(IS_POST){
            $data=D('Weizhuli');
            $_POST['token']=session('token');
            $_POST['title'] = strip_tags($this->_post("title"));
            $_POST['picurl'] = $this->_post("picurl");
			
            $_POST['nr'] =  $this->_post('nr','stripslashes,htmlspecialchars_decode');
            $_POST['keyword'] = $this->_post('keyword');
			$_POST['name'] = $this->_post('name');
			$_POST['tishi'] = $this->_post('tishi');
			$_POST['shuliang'] = $this->_post('shuliang');
			$_POST['bgcolor'] = $this->_post('bgcolor');
			$_POST['biaoti'] = $this->_post('biaoti');
			$_POST['hd'] = $this->_post('hd');
			$_POST['dfxz'] = $this->_post('dfxz');
			$_POST['csfz'] = $this->_post('csfz');
			$_POST['jfdw'] = $this->_post('jfdw');
			$_POST['zjjf'] = $this->_post('zjjf');
			$_POST['hdbg'] = $this->_post('hdbg');
			$_POST['max'] = $this->_post('max');
			$_POST['min'] = $this->_post('min');
			$_POST['sharenr'] = $this->_post('sharenr');
			$_POST['mode'] = $this->_post('mode');
			$_POST['gz'] = $this->_post('gz','stripslashes,htmlspecialchars_decode');
			$_POST['ch'] = $this->_post('ch');
			$_POST['lj'] = $this->_post('lj');
			$_POST['banquan'] = $this->_post('banquan');
			$_POST['statdate'] = strtotime($this->_post('statdate'));
            
            if($data->create()!=false){
                if($id=$data->add()){
                    $data1['pid']=$id;
                    $data1['module']='Weizhuli';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
                    M('keyword')->add($data1);
                    $this->success('添加成功',U('Weizhuli/index',array('token'=>session('token'))));
                }else{
                    $this->error('服务器繁忙,请稍候再试');
                }
            }else{
                $this->error($data->getError());
            }
        }else{
            $this->display();
        }
		
    }
	/**修改活动**/
	public function edit(){
		    $this->canUseFunction('Weizhuli');
		    if(IS_POST){
            $data=D('Weizhuli');
           $data=D('Weizhuli');
            $_POST['token']=session('token');
            $_POST['title'] = strip_tags($this->_post("title"));
            $_POST['picurl'] = $this->_post("picurl");
			
            $_POST['nr'] =  $this->_post('nr','stripslashes,htmlspecialchars_decode');
            $_POST['keyword'] = $this->_post('keyword');
			$_POST['name'] = $this->_post('name');
			$_POST['tishi'] = $this->_post('tishi');
			$_POST['shuliang'] = $this->_post('shuliang');
			$_POST['bgcolor'] = $this->_post('bgcolor');
			$_POST['biaoti'] = $this->_post('biaoti');
			$_POST['hd'] = $this->_post('hd');
			$_POST['dfxz'] = $this->_post('dfxz');
			$_POST['csfz'] = $this->_post('csfz');
			$_POST['jfdw'] = $this->_post('jfdw');
			$_POST['zjjf'] = $this->_post('zjjf');
			$_POST['hdbg'] = $this->_post('hdbg');
			$_POST['max'] = $this->_post('max');
			$_POST['min'] = $this->_post('min');
			$_POST['sharenr'] = $this->_post('sharenr');
			$_POST['mode'] = $this->_post('mode');
			$_POST['gz'] = $this->_post('gz','stripslashes,htmlspecialchars_decode');
			$_POST['ch'] = $this->_post('ch');
			$_POST['lj'] = $this->_post('lj');
			$_POST['banquan'] = $this->_post('banquan');
			$_POST['statdate'] = strtotime($this->_post('statdate'));
            $id = $this->_get('id');
			
            $where=array('id'=>$id);
            $check=$data->where($where)->find();
			//dump($check);exit;
			
            if($check==NULL) exit($this->error('非法操作'));
           
            if($data->create()){
                if($data->where($where)->save($_POST)){
                    $data1['pid']=$_POST['id'];
                    $data1['module']='Weizhuli';
                    $data1['token']=session('token');
                    $da['keyword']=trim($_POST['keyword']);
                    $ok = M('keyword')->where($data1)->save($da);
                    $this->success('修改成功!',U('Weizhuli/index',array('token'=>session('token'))));exit;
                }else{
                   
                    $this->success('修改成功',U('Weizhuli/index',array('token'=>session('token'))));exit;
                }
            }else{
                $this->error($data->getError());
            }
        }else {
		
		    $id=(int)$this->_get('id');
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Weizhuli');
            $check=$data->where($where)->find();
            if($check==NULL)$this->error('非法操作');
            $vo=$data->where($where)->find();
            $this->assign('vo',$vo);
            $this->display('add');
		
		}
       
   	}
   /**删除活动**/
    public function del(){
		$this->canUseFunction('Hforward');
		$id = $this->_get('id');
        $Weizhuli = M('Weizhuli');
        
         $de=M('Weizhuli')->where('id='.$id)->delete();
         if($de){
            M('weizhuli_record')->where('item_id='.$id)->delete();
            M('weizhuli_user')->where('pid='.$id)->delete();
			 M('keyword')->where('pid='.$id)->delete();
          
          
            $this->success('删除成功',U('Weizhuli/index',array('token'=>session('token'))));
         }else{
            $this->error('非法操作！');
         }
		
       
    } 
	
	/**产看中奖名单**/ 
	public function item(){
		
		
		$id = $this->_get('id');
		$token=session('token');
		$weizhuli = M('weizhuli_user');
		
		
        $info = $weizhuli->where(array('pid'=>$id,'token'=>$token))->order('score desc')->select();
		 $this->assign('info',$info);
		 $this->assign('id',$id);
       $this->display();
		
	}
	public function all(){

		$pid = $this->_get('id');
		$token=session('token');
		$weizhuli = M('weizhuli_user');
		
		
        $info = $weizhuli->where(array('pid'=>$pid,'token'=>$token))->delete();
		if($info){
		 $infos = M('weizhuli_record')->where(array('item_id'=>$pid,'token'=>$token))->delete();
				$this->success('删除成功',U('Weizhuli/index',array('token'=>session('token'))));
				
				}else {
				$this->error('删除失败',U('Weizhuli/index',array('token'=>session('token'))));
			}
		
	}
	
	
	public function add_item(){
	
		$this->canUseFunction('Hforward');
		$id = $this->_get('id');
		$token=session('token');
		$hforward = M('Hforward');
		$hf_item  = M('Hfor_item');
		$find = array('id'=>$id);
        $result = $hforward->where($find)->find();
		if($result){
			
			if(IS_POST){
				$data['name']=$this->_post('name');
				$data['phone']=$this->_post('phone');
				$data['tongji']=$this->_post('tongji');
				$data['ip']=$this->_post('ip');
				$data['createtime']=time();
				$data['fid']=$id;
				$res=$hf_item->add($data);
				if($res){
				$this->success('添加成功',U('Hforward/index',array('token'=>session('token'))));
				
				}else 
				$this->error('添加失败',U('Hforward/index',array('token'=>session('token'))));
			}else {
				
				$this->assign('id',$id);
				$this->display('');
			
			}
			
         }else{
            $this->error('非法操作！');
         }
		
	}
	public function delete_item(){
	
		$id=$this->_get('id');
		$hf_item  = M('Hfor_item');
		$res=$hf_item->where('id='.$id)->delete();
		if($res){
				$this->success('删除成功',U('Hforward/index',array('fid'=>session('token'))));
				
				}else 
				$this->error('删除失败',U('Hforward/index',array('token'=>session('token'))));
	}
	

    }



?>