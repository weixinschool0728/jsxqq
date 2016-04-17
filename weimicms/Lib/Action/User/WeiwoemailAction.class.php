<?php
class WeiwoemailAction extends UserAction{
    public $weiwoemail_config;
    public function _initialize(){
        parent :: _initialize();
        $this -> weiwoemail_config = M('weiwoemail');
        if (!$this -> token){
            exit();
        }
    }
    public function index(){
        $config = $this -> weiwoemail_config -> where(array('token' => $this -> token)) -> find();
        if(IS_POST){
            $row['token'] = $this -> token;
            $row['type'] = $this -> _post('type');
            $row['smtpserver'] = $this -> _post('smtpserver');
            $row['port'] = $this -> _post('port');
            $row['name'] = $this -> _post('name');
            $row['password'] = $this -> _post('password');
            $row['receive'] = $this -> _post('receive');
            $row['shangcheng'] = $this -> _post('shangcheng');
            $row['yuyue'] = $this -> _post('yuyue');
            $row['dingdan'] = $this -> _post('dingdan');
            $row['biaodan'] = $this -> _post('biaodan');
            $row['toupiao'] = $this -> _post('toupiao');
            if ($config){
                $where = array('token' => $this -> token);
                $this -> weiwoemail_config -> where($where) -> save($row);
            }else{
                $this -> weiwoemail_config -> add($row);
            }
            $this -> success('设置成功', U('Weiwoemail/index', $where));
        }else{
            $this -> assign('config', $config);
            $this -> display();
        }
    }
}
?>