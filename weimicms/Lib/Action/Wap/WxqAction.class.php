<?php
class WxqAction extends BaseAction{
    public function register(){
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if(!strpos($agent, "MicroMessenger")){
            echo '此功能只能在微信浏览器中使用';
            exit;
        }
        $con = array();
        $con['from_user'] = array('eq', $this -> _get('wecha_id'));
        $con['wxq_id'] = array('eq', $this -> _get('id'));
        if (!empty($_POST['submit'])){
            $data = array('nickname' => $_POST['nickname'],);
            if (empty($data['nickname'])){
                echo '<script>alert("请填写您的昵称！");</script>';
            }
            $data['avatar'] = $_POST['avatar_radio'];
            if(empty($data['avatar'])){
                $data['avatar'] = './tpl/Wap/default/common/images/avatar/noavatar.jpg';
            }
            $data['lastupdate'] = strtotime("now");
            $data['isjoin'] = 1;
            $members = M('wxwall_members') -> where($con) -> save($data);
            if($members){
                $memberData = M('wxwall_members') -> where($con) -> find();
                $valId = $memberData['wxq_id'];
                $wxqData = M('Wxq') -> where("id=$valId") -> find();
                if(S($memberData['from_user'] . 'wxq')){
                    S($memberData['from_user'] . 'wxq', NULL);
                }
                S($memberData['from_user'] . 'wxq', $memberData, $wxqData['timeout']);
                echo '<script>alert("登记成功！现在进入话题发表内容！");</script>';
            }else{
                echo '<script>alert("登记失败！重新登记！");</script>' ;
            }
        }
        $member = M('wxwall_members') -> where($con) -> find();
        $wall = M('Wxq') -> where(array('id' => $this -> _get('id'))) -> find();
        $this -> assign('data', $wall);
        $this -> assign('member', $member);
        $this -> display();
    }
}
?>