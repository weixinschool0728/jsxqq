<?php
class AuthcodeAction extends UserAction {
	// 公众帐号列表
	public function index() {
		
		if ($_POST) {
			$this->save ( $_POST );
		}
		$authcode = M ( 'ApAuthcode' );
		$uid = session ( 'uid' );
		$code = $authcode->where ( "uid=". $uid )->find ();
		// dump($code);
		$this->assign ( 'authcode', $code ['authcode'] );
		$this->display ();
	
	}
	
	function save($data) {
		
		$authcode = M ( 'ApAuthcode' );
		$uid = session ( 'uid' );
		
		$data ['uid'] = $uid;
		$data ['authcode'] = $data ['authcode'];
		
		$code = $authcode->where ( 'uid=' . uid )->find ();
		if ($code) {
			$data['modification_date']=date('Y-m-d H:i:s',time());
			$authcode->where ( 'uid=' . $uid )->save ( $data );
		} else {
			$data ['uid'] = $uid;
			$data ['type'] = 0;
			$authcode->add ( $data );
		}
	}

}
?>