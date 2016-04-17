<?php
class WmpAction extends UserAction{
    public function _initialize(){
        parent :: _initialize();
    }
    public function index(){
        $wxuser = M('wxuser');
        $where['token'] = session('token');
        $wxid = $wxuser -> where($where) -> getField('wxid');
        $yml_config = M('yml_config');
        $where['token'] = session('token');
        $yml_data = $yml_config -> where($where) -> find();
        if ($yml_data == null){
            $yml_data = array();
            $yml_data['username'] = '';
            $yml_data['secret'] = '';
            if(IS_POST){
                $_POST['token'] = session('token');
                $yml_config -> add($_POST);
                $this -> success('更新成功', U('Wmp/index'));
                die;
            }
        }else{
            if(IS_POST){
                $yml_config -> where($where) -> save($_POST);
                $this -> success('更新成功', U('Wmp/index'));
                die;
            }
        }
        import("@.ORG.weimeipai");
        $api = new yinmeili($yml_data['username'], $yml_data['secret'], $wxid);
        $list = $api -> getMachineList();
        $this -> assign('yml', $yml_data);
        $this -> assign('list', $list);
        $this -> display();
    }
    public function config(){
        $mid = $this -> _get('mid');
        $print_limit = $this -> _post('print_limit');
        $small_banner = $_REQUEST['small_banner'];
        $banner = $_REQUEST['banner'];
        $footer_url = $this -> _post('footer_url');
        $qrcode = $this -> _post('qrcode');
        $small_banner = urlencode(implode(',', $small_banner));
        $banner = urlencode(implode(',', $banner));
        $footer_url = urlencode($footer_url);
        $qrcode = urlencode($qrcode);
        $wxuser = M('wxuser');
        $where['token'] = session('token');
        $wxid = $wxuser -> where($where) -> getField('wxid');
        $yml_config = M('yml_config');
        $where['token'] = session('token');
        $yml_data = $yml_config -> where($where) -> find();
        if ($yml_data == null){
            $yml_data = array();
            $yml_data['username'] = '';
            $yml_data['secret'] = '';
        }
        import("@.ORG.weimeipai");
        $api = new yinmeili($yml_data['username'], $yml_data['secret'], $wxid);
        if (IS_POST){
            $result = $api -> updatePrintConfig($mid, $print_limit, $small_banner, $banner, $footer_url, $qrcode);
            if ($result != 'ok'){
                $this -> error($result, U('Wmp/index'));
                die;
            }else{
                $this -> success('微美拍打印配置信息保存成功!', U('Wmp/index'));
                die;
            }
        }
        $res = $api -> getPrintConfig($mid);
        if ($res === false){
            $this -> error('无权获取打印配置', U('Wmp/index'));
            die;
        }
        $arr = array();
        if ($res['small_banner'] != ''){
            $arr = json_decode($res['small_banner'], true);
        }
        $res['small_banner'] = array();
        for ($i = 0; $i < 5; $i++){
            $res['small_banner'][$i] = '';
            if (isset($arr[$i])){
                $res['small_banner'][$i] = $arr[$i];
            }
        }
        $arr = array();
        if ($res['banner'] != ''){
            $arr = json_decode($res['banner'], true);
        }
        $res['banner'] = array();
        for ($i = 0; $i < 5; $i++){
            $res['banner'][$i] = '';
            if (isset($arr[$i])){
                $res['banner'][$i] = $arr[$i];
            }
        }
        $this -> assign('yml', $res);
        $this -> display('config');
    }
}
?>