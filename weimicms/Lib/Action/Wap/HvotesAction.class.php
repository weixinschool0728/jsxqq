<?php
class HvotesAction extends WapAction{
	 public function _initialize() {
		parent::_initialize();
		session('wapupload',1);
	}
	public function index(){
		$token=$this->_get('token');
		$id=$this->_get('id');
		$wecha_id=$this->_get('wecha_id');
		$data=M('Hvotes')->where(array('token'=>$token,'id'=>$id))->find();
		$this->assign('vote',$data);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->display();	
	}
	public function tplp(){
		$token=$this->_get('token');
		$id=$this->_get('id');
		$wecha_id=$this->_get('wecha_id');
		$data=M('Hvotes')->where(array('token'=>$token,'id'=>$id))->find();
		$this->assign('vote',$data);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->display();	
	}
	public function hdgg(){
		$token=$this->_get('token');
		$id=$this->_get('id');
		$wecha_id=$this->_get('wecha_id');
		$data=M('Hvotes')->where(array('token'=>$token,'id'=>$id))->find();
		$this->assign('vote',$data);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->display();	
	}
	public function zzxb(){
		$token=$this->_get('token');
		$id=$this->_get('id');
		$wecha_id=$this->_get('wecha_id');
		$data=M('Hvotes')->where(array('token'=>$token,'id'=>$id))->find();
		$this->assign('vote',$data);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->display();	
	}
	public function fhdj(){
		$token=$this->_get('token');
		$id=$this->_get('id');
		$wecha_id=$this->_get('wecha_id');
		$data=M('Hvotes')->where(array('token'=>$token,'id'=>$id))->find();
		$this->assign('vote',$data);
		$this->assign('token',$token);
		$this->assign('wecha_id',$wecha_id);
		$this->assign('id',$id);
		$this->display();	
	}
    public function more(){
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
        $id         = $this->_get('id');
		$page         = $this->_get('page');
		$vote_item = M('Hvotes_item')->where(array('vid'=>$id,'checks'=>1))->order('rank asc')->limit($page*3,3)->select();
		$html="";
		foreach($vote_item as $value){
			$html.='<div class="single_item" style="margin-bottom:10px; width:33%; float:left;" id="more_element_1">
         <div class="con masonry" id="pagebox">              
              <div class="picCon fl masonry-brick">
                   <div class="picCon1"><a href="index.php?g=Wap&m=Hvotes&a=item_view&token='.$token.'&wecha_id='.$wecha_id.'&id='.$id.'&vid='.$value['id'].'"><img src="'.$value['startpicurl'].'"></a></div>  
                   <div class="picCon2 picCon2_3">
                        <ul style=" margin: 0px; padding: 0px; ">
                            <li class="pli1 bianhao"><span class="fense">'.$value['rank'].'</span>号</li>
                            <li class="pli1 name">'.$value['item'].'</li>
                            <li class="pli2">人气：<font class="num"><span class="fense">'.$value['vcount'].'</span></font></li>
                         </ul>
                    </div>
              </div>
            </div>
        </div>';
			}
		if($html==''){
			echo 1;
		}else{ 
			echo $html;
		}	
	}
	public function morepm(){
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
        $id         = $this->_get('id');
		$page         = $this->_get('page');
		$vote_item = M('Hvotes_item')->where(array('vid'=>$id,'checks'=>1))->order('vcount desc')->limit($page*3,3)->select();
		$html="";
		foreach($vote_item as $key=>$value){
			$key=$key+$page*3+1;
			$html.='<div class="single_item" id="more_element_'.$key.'"><div class="bblist"><a href="index.php?g=Wap&m=Hvotes&a=item_view&token='.$token.'&wecha_id='.$wecha_id.'&id='.$value['id'].'&vid='.$id.'"><li><div class="bs-docs-grid" style="margin:0px;"><div class="row show-grid" style="margin:0"><div class="col-xs-1"><span><b>'.$key.'</b></span></div><div class="col-xs-9"><div class="row" style="margin:0"><div class="col-xs-3" style="padding:0;"><img alt="'.$value['item'].'" width="45" height="45" title="'.$value['item'].'" src="'.$value['startpicurl'].'" style="float:left; " class="img-rounded"></div><div class="col-xs-9" style="padding:0 0 0px 10px"><span style="margin-top:2px; float:left;"><b>'.$value['item'].'</b></span><div style="clear:both"></div><div style="margin-top:2px; float:left;"><span style="color:#999;">编号:</span><span style="color:#e48632;"><b>'.$value['rank'].'</b></span>&nbsp;&nbsp;<span style="color:#999;">人气:</span><span style="color:#EA57AA;"><b>'.$value['vcount'].'</b></span></div></div></div></div></div></div></li></a></div>
        </div>';
			}
		if($html==''){
			echo 1;
		}else{ 
			echo $html;
		}	
	}
	public function toupiao(){
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $this->assign('id',$id);
		$t_vote		= M('Hvotes');
        $t_record  = M('Hvotes_record');
		$vote 	= $t_vote->where(array('token'=>$token,'id'=>$id))->find();
        if(empty($vote)){
            exit('非法操作');
        }
		/**添加搜索功能**/
		$key=$_POST['key'];
		if($key!=''){
			if(eregi('^[0-9]+$',$key)){
			$key=(int)$key;
    		$condition['rank']= array('like',$key.'%');	
			}else{
		 		$condition['item']= array('like',$key.'%');
			}
		 $condition['vid'] = $vote['id'];
		 $condition['checks'] = 1;
		 $vote_item = M('Hvotes_item')->where($condition)->order('rank asc')->select(); 
		}else  $vote_item = M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->order('rank asc')->limit(0,3)->select(); 
		$vcount =  M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->sum("vcount");
        $this->assign('count',$vcount);
		$total =  M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->count();
        $this->assign('total',$total);
        if($key!=''){
		 $condition['vid'] =$id;
		 $condition['checks'] =1;
		 $condition['item']= array('like',$key.'%');
		 $item_count = M('Hvotes_item')->where($condition)->order('vcount DESC')->select(); 
		}else $item_count = M('Hvotes_item')->where(array('vid'=>$id,'checks'=>1))->order('vcount DESC')->select();
        $vote['info']=html_entity_decode($vote['info']);
        $this->assign('total',$total);
        $this->assign('vote_item', $vote_item);
        $this->assign('vote',$vote);
		$this->display();
	}
	public function pm(){
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
        $id         = $this->_get('id');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $this->assign('id',$id);
		$t_vote		= M('Hvotes');
        $t_record  = M('Hvotes_record');
		$vote 	= $t_vote->where(array('token'=>$token,'id'=>$id))->find();
        if(empty($vote)){
            exit('非法操作');
        }
		/**添加搜索功能**/
		$key=$_POST['key'];
		if($key!=''){
			if(eregi('^[0-9]+$',$key)){
			$key=(int)$key;
    		$condition['rank']= array('like',$key.'%');	
			}else{
		 		$condition['item']= array('like',$key.'%');
			}
		 $condition['vid'] = $vote['id'];
		 $condition['checks'] = 1;
		 $vote_item = M('Hvotes_item')->where($condition)->order('vcount desc')->select(); 
		}else  $vote_item = M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->order('vcount desc')->limit(0,3)->select(); 
		$vcount =  M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->sum("vcount");
        $this->assign('count',$vcount);
		$total =  M('Hvotes_item')->where(array('vid'=>$vote['id'],'checks'=>1))->count();
        $this->assign('total',$total);
        if($key!=''){
		 $condition['vid'] =$id;
		 $condition['checks'] =1;
		 $condition['item']= array('like',$key.'%');
		 $item_count = M('Hvotes_item')->where($condition)->order('vcount DESC')->select(); 
		}else $item_count = M('Hvotes_item')->where(array('vid'=>$id,'checks'=>1))->order('vcount DESC')->select();
        $vote['info']=html_entity_decode($vote['info']);
        $this->assign('total',$total);
        $this->assign('vote_item', $vote_item);
        $this->assign('vote',$vote);
		$this->display();
	}
	/***
	选手详细页面
	**/
	public function item_view(){
	    //$agent = $_SERVER['HTTP_USER_AGENT']; 
		//if(!strpos($agent,"icroMessenger")) {
		//	echo '此功能只能在微信浏览器中使用';exit;
		//}
		$token		= $this->_get('token');
		$wecha_id	= $this->_get('wecha_id');
        $id         = $this->_get('id');
		$vid         = $this->_get('vid');
        $this->assign('token',$token);
        $this->assign('wecha_id',$wecha_id);
        $this->assign('id',$id);
		$this->assign('vid',$vid);
		$t_vote		= M('Hvotes');
		$t_item     =M('Hvotes_item'); 
        $t_record  = M('Hvotes_record');
		$wuser=M('Wxuser')->where(array('token'=>$token))->find();
		$where 		= array('token'=>$token,'id'=>$id);
		$vote 	= $t_vote->where($where)->find();
		$zzs=M('Hvotes_zzs')->where('vid='.$id)->select();
		$this->assign('zzs',$zzs);
        if(empty($vote)){
            exit('非法操作');
        }
		$item 	= $t_item->where(array('id'=>$vid))->find();
		$item['content']=html_entity_decode($item['content']);
        $vote_item = M('Hvotes_item')->where(array('vid'=>$id))->order('vcount DESC')->select();
		foreach($vote_item as $k=>$value){
			if($vote_item[$k]['id']==$vid){
				$vpm=$k+1;
			}
		}
	    $vote['zzs']=html_entity_decode($vote['zzs']);
		$this->assign('vpm',$vpm);
        $this->assign('item',$item);
	    $this->assign('vote',$vote);
		$this->assign('wuser',$wuser);
		$this->display();
		}
    //美女报名入口
	public function baoming(){
	//$agent = $_SERVER['HTTP_USER_AGENT']; 
		//if(!strpos($agent,"icroMessenger")) {
			//echo '此功能只能在微信浏览器中使用';exit;
	//}
			/**
			美女投票报名入口
			*/
			if(IS_POST){
				$Hvotes_t=M("Hvotes_item");
				$data['vid']=$this->_post('mid');
				$data['token']=$this->_post('token');
				$data['item']=$this->_post('name');
				$data['phone']=$this->_post('phone');
				$data['startpicurl']=$this->_post('pics1');
				$data['pics2']=$this->_post('pics2');
				$data['pics3']=$this->_post('pics3');
				$data['pics4']=$this->_post('pics4');
				$data['pics5']=$this->_post('pics5');
				$data['age']=$this->_post('age');
				$in=$Hvotes_t->where(array('token'=>$data['token'],'vid'=>$data['vid']))->find();
				if(empty($in)){
				$data['rank']=1;	
					}else{
				$ins=$Hvotes_t->where(array('token'=>$data['token'],'vid'=>$data['vid']))->order('rank desc')->limit(1)->find();		
					$data['rank']=$ins['rank']+1;	
						}
				if($data['startpicurl']){
				   // $str=""
					$data['content'].="<p><img src=".$data['startpicurl']." width=80% ></p>";
				}
				if($data['pics2']){
				   // $str=""
					$data['content'].="<p><img src=".$data['pics2']." width=80% ></p>";
				}
				if($data['pics3']){
				   // $str=""
					$data['content'].="<p><img src=".$data['pics3']." width=80% ></p>";
				}
				if($data['pics4']){
				   // $str=""
					$data['content'].="<p><img src=".$data['pics4']." width=80% ></p>";
				}
				if($data['pics5']){
				   // $str=""
					$data['content'].="<p><img src=".$data['pics5']." width=80% ></p>";
				}
				$data['content'].=$this->_post('content');
				$check=$Hvotes_t->where(array('vid'=>$data['vid'],'phone'=>$data['phone']))->find();
				if($check){
					$this->error('亲，您已经报名啦！',U('Hvotes/baoming',array('id'=>$data['vid'])));
				}
				if($data['item']=='' || $data['phone']=='' || $data['content']==''){
					$this->error('请完善信息！',U('Hvotes/baoming',array('id'=>$data['vid'])));
				}
				if($data['startpicurl']==''){
					$this->error('至少上传一张图片！',U('Hvotes/baoming',array('id'=>$data['vid'])));
				}
				$res=$Hvotes_t->add($data);
				if($res){
					$this->success('报名成功，请继续关注我们的信息！',U('Hvotes/index',array('token'=>$data['token'],'id'=>$data['vid'])));
				}else{
					$this->error('请完善信息！',U('Hvotes/baoming',array('id'=>$data['vid'])));
				}
			}else{
				$id=$this->_get('id');
				$token=$this->_get('token');
				$data=M('Hvotes')->where('id='.$id)->find();
				if($data['zxbm']=0){
					$this->error('在线报名已经关闭！',U('Hvotes/index',array('token'=>$data['token'],'id'=>$data['id'])));
				}
				$data['zzs']=html_entity_decode($data['zzs']);
				$this->assign('data',$data);
				$this->assign('token',$token);
				$this->assign('id',$id);
				$this->display();
			}
		}
}?>