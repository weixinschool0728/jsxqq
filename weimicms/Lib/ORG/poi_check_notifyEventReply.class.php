<?php

/**
 * 门店审核事件推送
 * */
class poi_check_notifyEventReply {

    public $token;
    public $wecha_id;
    public $data;

    public function __construct($token, $wecha_id, $data, $siteurl) {
        $this->token = $token;
        $this->wecha_id = $wecha_id;
        $this->data = $data;
    }

    public function index() {
        $where = array('token' => $this->token, 'sid' => $this->data['UniqId']);
        if ($this->data['result'] != 'fail') {//门店审核通过
            M('company')->where($where)->save(array('available_state' => 3, 'location_id' => $this->data['PoiId']));
        } else {
            M('company')->where($where)->save(array('available_state' => 4));
        }
        /*         * *******收银台门店事件处理*********** */
        if (strpos($this->data['UniqId'], '_')) {
            $UniqIdArr = explode('_' . $this->data['UniqId']);
            $mid = intval($UniqIdArr['0']);
            $id = intval($UniqIdArr['1']);
            if (($id > 0) && ($mid > 0)) {
                $whereArr = array('mid' => $mid, 'id' => $id);
                if ($this->data['result'] != 'fail') {
                    M('cashier_stores')->where($whereArr)->save(array('available_state' => 3, 'poi_id' => $this->data['PoiId']));
                } else {
                    M('cashier_stores')->where($whereArr)->save(array('available_state' => 4));
                }
            }
        }
        return "";
    }

}
