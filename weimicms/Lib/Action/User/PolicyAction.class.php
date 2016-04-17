<?php
class PolicyAction extends UserAction {
	public function _initialize() {
		parent::_initialize();
		$this->canUseFunction('wifi');
	}
	public function index() {
		$apModel = M ( 'ApPolicy' );
		
		$data ['uid'] = session ( 'uid' );
		$data ['token'] = session ( 'token' );
		
		$info = $apModel->where ( $data )->find ();
		
		
		$this->assign ( 'info', $info );
		
		$this->display ();
	
	}
	
	/**
	 * 保存配置
	 * @see BaseAction::save()
	 */
	public function save() {
		if ($_POST) {
			$apModel = M ( 'ApPolicy' );
			$id= $_POST['id'];
			if($id){
				#update
				
			}else{
				#add
				
			}
		}
			
			
	}

}

?>