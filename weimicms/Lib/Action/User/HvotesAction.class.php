<?php
class HvotesAction extends UserAction{

    public function index(){
        
		$this->canUseFunction('Hvotes');
        $list=M('Hvotes')->where(array('token'=>session('token')))->order('id DESC')->select();
        $count = M('Hvotes')->where(array('token'=>session('token')))->count();
        $this->assign('count',$count);
        $this->assign('list',$list);
        $this->display();
    
	}
	
	
	 public function add(){
     
        if(IS_POST){
            $data=D('Hvotes');
            $_POST['token']=session('token');
            $_POST['statdate']=strtotime($this->_post('statdate'));
            $_POST['enddate']=strtotime($this->_post('enddate'));
            if($_POST['enddate']<$_POST['statdate']){
                $this->error('结束时间不能小于开始时间!');
                exit;
            }
            if($data->create()!=false){
                if($id=$data->add()){
                    $data1['pid']=$id;
                    $data1['module']='Hvotes';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
                    M('keyword')->add($data1);
                    $this->success('添加成功',U('Hvotes/index',array('token'=>session('token'))));
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

public function edit(){
       if(IS_POST){
            $data=D('Hvotes');
            $_POST['id']= (int)$this->_post('id');
            $_POST['token']=session('token');
            $_POST['statdate']=strtotime($this->_post('statdate'));
            $_POST['enddate']=strtotime($this->_post('enddate'));
             if($_POST['enddate']<$_POST['statdate']){
                $this->error('结束时间不能小于开始时间!');
                exit;
            }
            $where=array('id'=>$_POST['id'],'token'=>session('token'));
            $check=$data->where($where)->find();
            if($check==NULL) exit($this->error('非法操作'));
            if($data->create()){
                if($data->where($where)->save($_POST)){
                    $data1['pid']=$_POST['id'];
                    $data1['module']='Hvotes';
                    $data1['token']=session('token');
                    $da['keyword']=trim($_POST['keyword']);
                    $ok = M('keyword')->where($data1)->save($da);
                    $this->success('修改成功!',U('Hvotes/index',array('token'=>session('token'))));exit;
                }else{
                    $this->success('修改成功',U('Hvotes/index',array('token'=>session('token'))));exit;
                }
            }else{
                $this->error($data->getError());
            }
        }else {
		
		    $id=(int)$this->_get('id');
            $where=array('id'=>$id,'token'=>session('token'));
            $data=M('Hvotes');
            $check=$data->where($where)->find();
            if($check==NULL)$this->error('非法操作');
            $vo=$data->where($where)->find();
            $this->assign('vo',$vo);
            $this->display('add');
		
		}
   
   }
  
  public function del(){
        $id = $this->_get('id');
        $vote = M('Hvotes');
        $find = array('id'=>$id);
        $result = $vote->where($find)->find();
         if($result){
            $vote->where('id='.$result['id'])->delete();
            M('Hvotes_item')->where('vid='.$result['id'])->delete();
            M('Hvotes_record')->where('vid='.$result['id'])->delete();
            $where = array('pid'=>$result['id'],'module'=>'Hvotes','token'=>session('token'));
            M('Keyword')->where($where)->delete();
            $this->success('删除成功',U('Hvotes/index',array('token'=>session('token'))));
         }else{
            $this->error('非法操作！');
         }
    } 
	
	public function shenhe(){
	
	    $vid=$this->_get('vid');
		$id=$this->_get('id');
		$item=M('Hvotes_item');
		if(IS_POST){
			$allid=$this->_post('allid');
			$style=$this->_post('style');
			if(!empty($allid)&& !empty($style)){
				
				if($style==1){
				    $map['id']  = array('in',$allid);
					$re=$item->where($map)->setField('checks',1);
				    $this->success('审核成功！','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
					exit;
				}else if($style==2){
				    $map['id']  = array('in',$allid);
				    $re=$item->where($map)->setField('checks',0);
				    $this->success('取消审核！','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
					exit;
				}else if($style==3){
					
					$map['id']  = array('in',$allid);
				    $re=$item->where($map)->delete();
				    $this->success('删除成功！','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
					exit;
					
					}
				
			}
		}
		
		$re=$item->where('id='.$id)->setField('checks',1);
		if($re){
			$this->success('审核成功！','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
		}
	}
	 
	public function item(){
		
		$token=session('token');
		$vid=$this->_get('id');
		$t_item=M('Hvotes_item');
		$order="rank desc,id desc";
		if (IS_POST) {
				$key = $this->_post('keyword');
				$px  = $this->_post('px');	
				if($key){
					$where['item']=array('like',"%$key%");
				}
				if($px==1){
					$order="vcount desc";
				}else if($px==2){
					$order="vcount asc";
				}
		}
		//echo $order."+++".$px;
		$where['vid']=$vid;
		$where['token']=$token;		
		$count=$t_item->where(array("vid"=>$vid,'token'=>$token))->count();
		$counts = $t_item->where($where)->count();
	    $Page  = new Page($counts,20);
		$show  = $Page->show();
		$item=$t_item->where($where)->order($order)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);
		$this->assign('vid',$vid);
		$this->assign('count',$count);
		$this->assign('item',$item);
        $this->display();
		}
	public function add_item(){
		$vid=$this->_get('vid');
		$t_vote=M('Hvotes');
		$res=$t_vote->where('id='.$vid)->find();
		if($res==false){
			echo "非法操作";
			exit;
		}
		$this->assign('vid',$vid);
		$this->display('edite_item');
	}
	public function edite_item(){
	
			$vid=$this->_get('vid');
			$id=$this->_get('id');
			$t_item=M('Hvotes_item');
			//$v_zs=M('Vote_z');
		    //$v_z=$v_zs->where('v_id='.$vid)->select();
			//dump($v_z);
			$item=$t_item->where('id='.$id)->find();
			if(empty($item)){
				$this->error('非法操作！','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
			}
			$this->assign("item",$item);
			$this->display();
			
	}
	public function delete_item(){
		    
			$vid=$this->_get('vid');
			$id=$this->_get('id');
			$t_item=M('Hvotes_item');
			$t_item->where('id='.$id)->delete();
			
				$this->success('删除成功','/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
			
			
	}
	public function save_item(){
	
	
	
			$vid=$this->_get('vid');
			$id=$this->_get('id');
			$t_item=M('Hvotes_item');
			
			$data['item']=$this->_post('item');
			$data['vcount']=$this->_post('vcount');
			$data['phone']=$this->_post('phone');
			$data['content']=$this->_post('content');
			$data['startpicurl']=$this->_post('startpicurl');
			$data['movie_link']=$this->_post('movie_link');
			$data['rank']=$this->_post('rank');
			
			if(!empty($vid) && empty($id)){
			    $data['vid']=$vid;
				$result=$t_item->add($data);
				if($result){
			
				$this->success("添加成功",'/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$vid);
			
				}
			
			}else if(empty($vid) && !empty($id)){
				$res=M('Hvotes_item')->where('id='.$id)->find();
				if($res==false){
					echo "非法操作";
					exit;
				}
				$result=$t_item->where('id='.$id)->save($data);
				
				if($result){
					$this->success("修改成功",'/index.php?g=User&m=Hvotes&a=item&token='.$this->token.'&id='.$res['vid']);
				}
			
			}
			
			
			

			
			
			
			
	}
	
	
   }



?>