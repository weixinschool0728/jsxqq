<?php
class XiaoshouAction extends UserAction{
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('Xiaoshou');
	}
	public function index(){
		$where['token'] = session('token');
		$Data = M('XiaoshouList');
		$count = $Data->where($where)->count();
		$page = new Page($count,25);
		$info = $Data->where($where)->order('sort DESC')->limit($page->firstRow.','.$page->listRows)->select();
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
	}
	
	public function add(){
		if(IS_POST){
			$data['name']	= strip_tags($_POST['name']);
			$data['banner']	= strip_tags($_POST['banner']);
			$data['mb'] 	= strip_tags($_POST['mb']);
			$data['token'] 	= session('token');
			$data['weixin'] 	= strip_tags($_POST['weixin']);
			$data['tel'] 	= strip_tags($_POST['tel']);
			$data['email'] 	= strip_tags($_POST['email']);
			$data['weibo'] 	= strip_tags($_POST['weibo']);
			$data['ad'] 	= strip_tags($_POST['ad']);
			$data['sort'] 	= strip_tags($_POST['sort']);
			if(empty($data['name'])) $this->error('姓名不能够为空!');
			if(empty($data['banner'])) $this->error('头像不能够为空!');
			if(empty($data['mb'])) $this->error('手机不能够为空!');
			$insert = M('XiaoshouList')->add($data);
			if($insert > 0){
				$this->success('添加成功!');
			}else{
				$this->error('添加失败!');
			}
		}else{
			$this->display();
		}
	}
	
	public function edit(){
		$id = $this->_get('id');
		$where['id'] = $id;
		$where['token'] = session('token');
		if(IS_POST){
			$data['name']	= strip_tags($_POST['name']);
			$data['banner']	= strip_tags($_POST['banner']);
			$data['mb'] 	= strip_tags($_POST['mb']);
			$data['token'] 	= session('token');
			$data['weixin'] 	= strip_tags($_POST['weixin']);
			$data['tel'] 	= strip_tags($_POST['tel']);
			$data['email'] 	= strip_tags($_POST['email']);
			$data['weibo'] 	= strip_tags($_POST['weibo']);
			$data['ad'] 	= strip_tags($_POST['ad']);
			$data['sort'] 	= strip_tags($_POST['sort']);
			if(empty($data['name'])) $this->error('姓名不能够为空!');
			if(empty($data['banner'])) $this->error('头像不能够为空!');
			if(empty($data['mb'])) $this->error('手机不能够为空!');
			$up = M('XiaoshouList')->where($where)->save($data);
			if($up){
				$this->success('更新成功!');
			}else{
				$this->error('更新失败!');
			}
		}else{
			$info = M('XiaoshouList')->where($where)->find();
			$this->info = $info;
			$this->display();
		}
	}
	
	public function delete(){
		$where['id'] = $this->_get('id');
		$where['token'] = session('token');
		$info = M('XiaoshouList')->where($where)->delete();
		if($info){
			$this->success('主题活动删除成功!');
		}else{
			$this->error('主题活动删除失败!');
		}
	}
	public function infos(){

		

		$pid=$this->_get('pid');
		$where= array('token'=> $this->token,'pid'=>$pid);
		$count = M('xiaoshou_order')->where($where)->count();
	
		$Page = new Page($count,20);

		$show = $Page->show();

		$data = M('xiaoshou_order')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();


		$this->assign('page',$show);

		$this->assign('data', $data);
		$this->assign('pid', $pid);

		

		$this->display();
	

	}
	public function delinfos(){

		if($this->_get('token')!=$this->token){$this->error('非法操作');}

        $id = intval($this->_get('id'));

        if(IS_GET){                              

            $where=array('id'=>$id,'token'=>$this->token);

            $check=M('xiaoshou_order')->where($where)->find();

            if($check==false)   $this->error('非法操作');

            $back=M('xiaoshou_order')->where($where)->delete();

            if($back==true){

                $this->success('操作成功',U('Xiaoshou/infos',array('token'=>$this->token,'pid'=>$check['pid'])));

            }else{

                 $this->error('服务器繁忙,请稍后再试',U('Xiaoshou/infos',array('token'=>$this->token,'pid'=>$check['pid'])));

            }

        }        

	}
	public function company(){
		$where['token'] = session('token');
		$Cdata = M('Xiaoshou');
		$info = $Cdata->where($where)->find();
		$this->info = $info;
		if(IS_POST){
			$where['token'] = session('token');
			$data['company'] = strip_tags($_POST['company']);
			$data['logo'] = strip_tags($_POST['logo']);
			$data['title'] = strip_tags($_POST['title']);
			$data['jianjie'] = strip_tags($_POST['jianjie']);
			$data['tp'] = strip_tags($_POST['tp']);
			
			$data['info'] = strip_tags($_POST['info']);
			
			//$res = M('Vcard')->where($where)->find();
			if($info){
				$result = M('Xiaoshou')->where($where)->save($data);
				if($result){
					$this->success('回复信息更新成功!');
				}else{
					$this->error('服务器繁忙 更新失败!');
				}
			}else{
				$data['token'] = session('token');
				$insert = M('Xiaoshou')->add($data);
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
}