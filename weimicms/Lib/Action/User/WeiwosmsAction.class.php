<?php
class WeiwosmsAction extends UserAction{
    public $weiwosms_config;
    public function _initialize(){
        parent :: _initialize();
        $this -> weiwosms_config = M('weiwosms');
        if (!$this -> token){
            exit();
        }
    }
    public function index(){
        $config = $this -> weiwosms_config -> where(array('token' => $this -> token)) -> find();
        if(IS_POST){
            $row['phone'] = $this -> _post('phone');
            $row['password'] = $this -> _post('password');
            $row['name'] = $this -> _post('name');
            $row['token'] = $this -> _post('token');
            $row['shangcheng'] = $this -> _post('shangcheng');
            $row['yuyue'] = $this -> _post('yuyue');
            $row['dingdan'] = $this -> _post('dingdan');
            $row['biaodan'] = $this -> _post('biaodan');
            $row['toupiao'] = $this -> _post('toupiao');
            if ($config){
                $where = array('token' => $this -> token);
                $this -> weiwosms_config -> where($where) -> save($row);
            }else{
                $this -> weiwosms_config -> add($row);
            }
            $this -> success('设置成功', U('Weiwosms/index', $where));
        }else{
            $this -> assign('config', $config);
            $this -> display();
        }
    }
}
?>