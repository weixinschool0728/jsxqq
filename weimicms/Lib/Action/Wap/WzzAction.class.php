<?php
class WzzAction extends WapAction{
   
   public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
		if (!$this->wecha_id){
			$this->error('您无权访问','');
		}
	}
    public function index(){
	    $token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$music=$this->_get('music');
		$action=$this->_get('action');
	    $id=$this->_get('id');
		 $catgroy=$this->_get('catgroy');
        $where      = array('token'=>$this->token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy);
        $content    = M('Wzzmy')->where($where)->limit( 2)->select();
		 $pic    = M('Wzzmy')->where($where)->limit( 2)->select();
		  $title    = M('Wzzmy')->where($where)->limit( 1)->find();
		// dump($content);exit;
		$contents    = M('Wzzmy')->where($where)->select();
		$info=M('Wzzreplay')->where(array('token'=>$token))->order('id desc')->find();
	     $info['gzlj']=$info['gzlj'].'#wechat_redirect';
		 $this->assign('info',$info);
$click=M('Wzzmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->setInc('click');
        $this->assign('content',$content);
		
		   $this->assign('contents',$contents);
		   $this->assign('wecha_id',$wecha_id);
		   $this->assign('id',$id);
		   $this->assign('title',$title);
		    $this->assign('music',$music);
			$this->assign('pic',$pic);
			 $this->assign('action',$action);
		    $this->assign('catgroy',$catgroy);
        
        $this->display();
    }
        public function history(){
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
		$Kndata=M('Wzzmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id))->select();
		$date=M('wzzreplay')->where(array('token'=>$token))->find();



		foreach($Kndata as $key=>$val){
			$list[$val['catgroy']][]=$val;
		}
		
		$this->assign('kndata',$list);
		$this->assign('date',$date);
		$this->display();
		
	}
	public function delete(){
		
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    $id=$this->_get('id');
		$tpid=$this->_get('tpid');
		$catgroy=$this->_get('catgroy');
		$Kndata=M('Wzzmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->delete();
		if($Kndata){
			$this->success('删除成功',U('Wzz/history',array('token'=>$token,'wecha_id'=>$wecha_id)));
			}else{
				$this->error('删除失败',U('Wzz/history',array('token'=>$token,'wecha_id'=>$wecha_id)));
				
				}
		
		
			
			
	}
	public function share(){
		
		  $catgroy=$this->_get('catgroy');
		$token=$this->_get('token');
		$wecha_id=$this->_get('wecha_id');
	    		
	$share=M('Wzzmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->setInc('share');
	
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
				$sharenrs = $this->_post('sharenr');
				$pics = $this->_post('pic');
				$ts = $this->_post('t');
				$sharepics = $this->_post('sharepic');
				//echo "<pre>";
				//dump($itemid);
				//die();
				foreach($itemid as $key=>$val){
					$dataitem['content']	= $this->_post('content'.$val);
					$dataitem['pic']		= $this->_post('pic'.$val);
					$dataitem['t']		= $this->_post('t'.$val);
					$dataitem['title']		= $this->_post('title'.$val);
					$dataitem['sharenr']		= $this->_post('sharenr'.$val);
					$dataitem['sharepic']		= $this->_post('sharepic'.$val);
					$setid = $itemid[$key];
					$res=M('Wzzmy')->where(array('id'=>$setid))->save($dataitem);
				}
				$this->success('成功更新内容',U('Wzz/index',array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy)));
				if($res){
				
					
				        }
				
		}else{
				
			//没有提交修改数据
			 
				$Kndata=M('Wzzmy')->where(array('token'=>$token,'wecha_id'=>$wecha_id,'catgroy'=>$catgroy))->select();
			
				//dump($Kndata);
				$this->assign('token',$token);
				$this->assign('wecha_id',$wecha_id);
				$this->assign('id',$id);
				$this->assign('Kndata',$Kndata);
				$this->display();
			
			}
	}
	//保存
    public function get_list(){


        echo '{result:1,msg:"请求成功!!"}';
    }

    public function test(){

        $this->display();
    }

}

?>