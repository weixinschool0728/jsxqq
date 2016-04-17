<?php
class KnwxAction extends WapAction{
	public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}
	//秀秀卡妞微秀
	public function index(){
	  
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		
	    $id=$this->_get('id');
		  $catgroy=$this->_get('catgroy');
		
		$tpid=$this->_get('tpid');
		$action=$this->_get('action');
		
		if($tpid!=""){
			$data['style']=$tpid;
			$res=M('Knwxmy')->where(array('token'=>$token))->save($data);
		}
		$Kndata=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->select();


		foreach($Kndata as $key=>$val){
			$list[$val['catgroy']][]=$val;
		}
	

		
		
		
		$info=M('Knwxreplay')->where(array('token'=>$token))->order('id desc')->find();
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->assign('info',$info);
		$this->assign('kndata',$list);
		$this->assign('Kndatas',$Kndatas);
		
		$this->assign('action',$action);
		$tpid=$Kndatas['style'];
		
		if($tpid!=1 && $tpid!=""){
		
		$this->display($tpid.'_view');
		
		}else $this->display();
	}
		public function indexhi(){
	  
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    $catgroy=$this->_get('catgroy');
		 $music=$this->_get('music');
		
		
		$tpid=$this->_get('tpid');
		$action=$this->_get('action');
		if($action!=""){
            $usersDB = M ( 'Knwxmy' );
			$date['save']=$action;
			$res=$usersDB->where(array('token'=>$token,'catgroy'=>$catgroy))->save($date);
		
			            }
		
		if($tpid!=""){
			$data['style']=$tpid;
			$res=M('Knwxmy')->where(array('token'=>$token,'catgroy'=>$catgroy))->save($data);
			
		}
		$Kndatas=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->select();
		$time=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->limit(1)->find();
		
		$click=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->setInc('click');
		$wql=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->order('pic desc')->limit(1)->find();
		$wqlinfo=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->order('content desc')->limit(1)->find();
	    if(empty($wql['pic'])){
		$wql['pic']=C('site_url').'/tpl/static/knwx/kn_deflaut.jpg';
		}
		
		$info=M('Knwxreplay')->where(array('token'=>$token))->order('id desc')->find();
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('music',$music);
		$this->assign('tpid',$tpid);
		$this->assign('id',$id);
		$this->assign('info',$info);
		$this->assign('wql',$wql);
		$this->assign('wqlinfo',$wqlinfo);
		$this->assign('catgroy',$catgroy);
		
		$this->assign('Kndatas',$Kndatas);
		$this->assign('time',$time);
		
		$this->assign('action',$action);
		$tpid=$time['style'];
		
		if($tpid!=1 && $tpid!=""){
		
		$this->display($tpid.'_view');
		
		}else $this->display();
	}
	public function history(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$Kndata=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->select();
		$date=M('knwxreplay')->where(array('token'=>$token))->find();



		foreach($Kndata as $key=>$val){
			$list[$val['catgroy']][]=$val;
		}
		
		$this->assign('kndata',$list);
		$this->assign('date',$date);
		$this->display();
		
	}
	
	//换模板
	public function changbj(){
		
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    $id=$this->_get('id');
		$tpid=$this->_get('tpid');
		$catgroy=$this->_get('catgroy');
		
		$this->redirect('Knwx/indexhi', array('token'=>$token,'wecha_id'=>$wecha_id,'tpid'=>$tpid,'catgroy'=>$catgroy), 0, '页面跳转中...');
		
			
			
	}
	public function delete(){
		
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    $id=$this->_get('id');
		$tpid=$this->_get('tpid');
		$catgroy=$this->_get('catgroy');
		$Kndata=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->delete();
		if($Kndata){
			$this->success('删除成功',U('Knwx/history',array('token'=>$token,'wecha_id'=>$wecha_id)));
			}else{
				$this->error('删除失败',U('Knwx/history',array('token'=>$token,'wecha_id'=>$wecha_id)));
				
				}
		
		
			
			
	}
	//换内容
	public function changnr(){
	
		        $catgroy=$this->_get('catgroy');
				$token=$this->_get('token');
				$wecha_id=$this->_get('wecha_id');
	    		$id=$this->_get('id');
		
		
		if(IS_POST){
			  
			
				$itemid = $this->_post('itemid');
				$contents = $this->_post('content');
				$titles = $this->_post('title');
				$pics = $this->_post('pic');
				//echo "<pre>";
				//dump($itemid);
				//die();
				foreach($itemid as $key=>$val){
					$dataitem['content']	= $this->_post('content'.$val);
					$dataitem['pic']		= $this->_post('pic'.$val);
					$dataitem['title']		= $this->_post('title'.$val);
					$setid = $itemid[$key];
					$res=M('Knwxmy')->where(array('id'=>$setid))->save($dataitem);
				}
				$this->success('成功更新内容',U('Knwx/indexhi',array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy)));
				if($res){
				
					
				        }
				
		}else{
				
			//没有提交修改数据
			 
				$Kndata=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->select();
			
				//dump($Kndata);
				$this->assign('token',$token);
				$this->assign('wecha_id',$wecha_id);
				$this->assign('id',$id);
				$this->assign('Kndata',$Kndata);
				$this->display();
			
			}
	}
	//保存
	public function share(){
		
		  $catgroy=$this->_get('catgroy');
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    		
	$share=M('Knwxmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->setInc('share');
	
	}
	
}



?>