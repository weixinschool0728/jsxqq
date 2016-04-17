<?php
class MusiccarAction extends UserAction{
    public function _initialize() {
		parent::_initialize();
		$function=M('Function')->where(array('funname'=>'Musiccar'))->find();
		$this->canUseFunction('Musiccar');
	}  
	public function index(){
	
		$where['token'] = session('token');
		$Cdata = M('Musiccar');
		$info = $Cdata->where($where)->find();
		$this->info = $info;
		if(IS_POST){
			$where['token'] = session('token');
			$data['pic'] = strip_tags($_POST['pic']);
			$data['title'] = strip_tags($_POST['title']);
			$data['keyword'] = strip_tags($_POST['keyword']);
			$data['jianjie'] = strip_tags($_POST['jianjie']);
			$data['lj'] = strip_tags($_POST['lj']);
			$data['ad'] = strip_tags($_POST['ad']);
			$data['yd'] = strip_tags($_POST['yd']);
$data['banquan'] = strip_tags($_POST['banquan']);
			
			if($info){
				$result = M('Musiccar')->where($where)->save($data);
				if($result){
					$res=M('Musiccar')->where($where)->find();
				    $data1['pid']=$res['id'];
                    $data1['module']='Musiccar';
                    $data1['token']=session('token');
                    $data1['keyword']=$_POST['keyword'];
					$where['module']='Musiccar';
					$res=M('keyword')->where(array('module'=>'Musiccar','token'=>session('token')))->find();
					if($res)
                    M('keyword')->where($where)->save($data1);
					else M('keyword')->add($data1);
					$this->success('回复信息更新成功!');
				}else{
					$this->error('服务器繁忙 更新失败!');
				}
			}else{
				$data['token'] = session('token');
				$insert = M('Musiccar')->add($data);
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



?>