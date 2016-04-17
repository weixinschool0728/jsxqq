<?php
class TerminalAction extends UserAction {
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('wifi');
	}
	public function index() {
		$apModel = M ( 'ApUsers' );
		
		$data ['uid'] = session ( 'uid' );
		$data ['token'] = session ( 'token' );
		
		$list = $apModel->where ( $data )->select ();
		
		if (IS_POST) {
			
			$key = $this->_post ( 'searchkey' );
			if (empty ( $key )) {
				exit ( "关键词不能为空." );
			}
			// $map['token'] = $this->get('token');
			// $map['tel|wechaname'] = array('like',"%$key%");
			// $list = M('Userinfo')->where($map)->select();
		}
		$this->assign ( 'list', $list );
		
		$this->display ();
	}
 
	public function delete() {
		$ap = M ( 'ApNodes' );
		$where = array (
				'id' => intval ( $_GET ['id'] ) 
		);
		$rt = $ap->where ( $where )->delete ();
		if ($rt == true) {
			$this->success ( '删除成功', U ( 'AP/index' ) );
		} else {
			$this->error ( '服务器繁忙,请稍后再试', U ( 'AP/index' ) );
		}
	}
	public function add() {
		$ap = M ( 'ApNodes' );
		$id = $_GET ['id'];
		if ($_POST) {
			
			// 存
			$data = $_POST;
			
			
			
			$mac = $data ['mac'];
			$data ['province'] = $data ['position'] [0];
			$data ['city'] = $data ['position'] [1];
			$data ['country'] = $data ['position'] [2];
			$data ['deploy_status'] = 0;
			$data ['device_location'] = $data ['device_location'];
			$data ['token'] = $data ['token'];
			$data ['uid'] = session ( 'uid' );
			
			// dump($data);
			if ($id) {
				// ODO mac 不能重复
				// id!=$id mac=$mac
				$apinfo = $ap->where ( "id <>'$id' and mac = '$mac'" )->find ();
				if ($apinfo) {
					$this->error ( 'mac已经存在' );
				}
				
				$data ['id'] = $id;
				$ap->save ( $data );
				
				$where ['gw_id'] = $data ['gw_id'];
				
				/*
				 * $pdata ['gw_id'] = $data ['gw_id']; $pdata ['mac'] = $data ['mac']; $pdata ['node_id'] = $id; $nodes->where ( $where )->save ( $pdata );
				 */
			} else {
				// ODO mac 不能重复
				// mac=$mac
				$apinfo = $ap->where ( "mac = '$mac'" )->find ();
				if ($apinfo) {
					$this->error ( 'mac已经存在' );
				}
				$ap->add ( $data );
				
				/*
				 * $pdata ['gw_id'] = $data ['gw_id']; $pdata ['mac'] = $data ['mac']; $pdata ['creation_date'] = time (); $nodes->add ( $pdata );
				 */
			}
			
			$this->success ( '添加成功', U ( 'AP/index' ) );
		} else {
			// 示
			if ($_GET ['id']) {
				// 辑
				
				$apinfo = $ap->where ( "id = '$id'" )->find ();
				$this->assign ( 'ap', $apinfo );
			}
			$where['uid']=session('uid');
			$wxuser = M('Wxuser');
			$webs= $wxuser->where($where)->select();
			//dump($webs);
			$this->assign ( 'webs', $webs);
			$this->display ();
		}
	}
	public function wxsave() {
		$this->all_save ( 'Wxuser' );
	}
}

?>