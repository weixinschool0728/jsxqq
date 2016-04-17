<?php
class XinniannqAction extends UserAction{

    public function index(){
		$this->canUseFunction('Xinniannq');
		
		$where['token'] = session('token');
		
		
		$Data = M('Xinniannq');
		$count = $Data->where($where)->count();
		$page = new Page($count,25);
		
		$info = $Data->where(array('token'=>session('token')))->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
		
	}
	public function replay(){
	
		$where['token'] = session('token');
		$Cdata = M('Xinniannqreplay');
		$info = $Cdata->where($where)->find();
		$this->info = $info;
		if(IS_POST){
			$where['token'] = session('token');
			$data['pic'] = strip_tags($_POST['pic']);
			$data['title'] = strip_tags($_POST['title']);
			$data['keyword'] = strip_tags($_POST['keyword']);
			$data['jianjie'] = strip_tags($_POST['jianjie']);
			$data['lj'] = strip_tags($_POST['lj']);
			$data['sm'] = strip_tags($_POST['sm']);
				
			
			$res=$Cdata->where($where)->find();
			        $data1['pid']=$res['id'];
                    $data1['module']='Xinniannq';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
					$where['module']='Xinniannq';
			if($info){
				$result = M('Xinniannqreplay')->where($where)->save($data);
				if($result){
					$res=M('Xinniannqreplay')->where($where)->find();
				    
					$re=M('Keyword')->where(array('module'=>'Xinniannq','token'=>session('token')))->find();
					if($re){
                    M('keyword')->where($where)->save($data1);
					}else ;
					$this->success('回复信息更新成功!');
				}else{
					$this->error('服务器繁忙 更新失败!');
				}
			}else{
				$data['token'] = session('token');
				$insert = M('Xinniannqreplay')->add($data);
				$res=$Cdata->where($where)->find();
			        $data1['pid']=$res['id'];
                    $data1['module']='Xinniannq';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
		
					$where['module']='Xinniannq';
				$insert1 =M('keyword')->add($data1);
				if($insert > 0){
					$this->success('回复信息添加成功!');
				}else{
					$this->error('回复信息添加失败!');
				}
			}
		}else{
			$this->display();
		}
	
	}
	 public function add(){
	 
	 	
		$t_Xinniannq=M('Xinniannq');
		if(IS_POST){
		    $data['token']=session('token');
			$data['nq']=strip_tags($_POST['nq']);
				//dump($data['nq']);exit;
			$res=$t_Xinniannq->add($data);
			if($res){
			$this->success('添加成功',U('Xinniannq/index',array('token'=>$this->token)));
				
			}else{
				$this->error('年签添加失败!',U('Xinniannq/index',array('token'=>$this->token)));
			}
		
		}else $this->display();
	}
	
	public function edit(){
		$id = $this->_get('id');
		$where['id'] = $id;
		$where['token'] = session('token');
		if(IS_POST){
			$data['nq']	= strip_tags($_POST['nq']);
		
			if(empty($data['nq'])) $this->error('年签不能够为空!');
			$up = M('Xinniannq')->where($where)->save($data);
			if($up){
				$this->success('年签更新成功!');
			}else{
				$this->error('年签更新失败!');
			}
		}else{
			$info = M('Xinniannq')->where($where)->find();
			$this->info = $info;
			$this->display();
		}
	} 
	public function delete(){
		$where['id'] = $this->_get('id');
		$where['token'] = session('token');
		$info = M('Xinniannq')->where($where)->delete();
		if($info){
			$this->success('删除成功!');
		}else{
			$this->error('删除失败!');
		}
	}
	
	
}



?>