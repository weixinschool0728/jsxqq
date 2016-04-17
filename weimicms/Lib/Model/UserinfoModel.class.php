<?php
class UserinfoModel extends Model {
		
	public function isSub($token, $openid) {
		$where['token'] = $token;
		$where['wecha_id'] = $openid;
		$issub = $this->where("token='$token' AND (wecha_id='$openid' OR fakeopenid='$openid')")->limit(1)->getField('issub');
		return $issub == '1' ? true : false;
	}
	
	/**
	 * 重置附属表wecha_id
	 * @param Object $model
	 * @param Array $params
	 */
	public function convertFake($model, $params) {
		if (count($params) > 3) {
			exit('param error');
		}
		foreach ($params as $key => $value) {
			if ('token' == $key || 'fakeopenid' == $key) continue;
			$field = $key;
			$wecha_id = $value;			
		}
		$token = $params['token'];
		$fakeopenid = $params['fakeopenid'];
		if ($this->isSub($token, $wecha_id) && $wecha_id != $fakeopenid) {
			if (!empty($fakeopenid)) {
				$model->where(array('token'=>$token, $field=>$fakeopenid))->setField($field, $wecha_id);
			}
		}
	}
	
	
	//更新truename wechaname tel 三个字段为空的问题
	public function updateInfo($uid)
	{
		if ($userinfo = $this->where(array('id' => $uid))->find()) {
			if ($userinfo['tel'] && $userinfo['wechaname'] && $userinfo['truename']) return false;
			$where = array('token' => $userinfo['token']);
			
			$is_working = false;
			//从辅助表中看有没有记录
			$fields = M('Member_card_custom_field')->where($where)->select();
			$data = array();
			foreach ($fields as $field) {
				if (in_array($field['item_name'], array('tel', 'wechaname', 'truename'))) {
					$data[$field['field_id']] = $field['item_name'];
				}
			}

			$attachs = M('Userinfo_attach')->where(array('uid' => $uid))->select();
			$temp = array();
			foreach ($attachs as $a) {
				if (isset($data[$a['field_id']])) {
					$temp[$data[$a['field_id']]] = $a['field_value'];
				}
			}
			
			if ($temp) {
				if ($this->where(array('id' => $uid))->save($temp)) {
					return $temp;
				} else {
					$is_working = true;
				}
			} else {
				$is_working = true;
			}
			
			//辅助表中没有记录或者是更新失败的时候之间通过接口抓取信息
			if ($is_working) return $this->synchronousFansInfo($userinfo['wecha_id'], $userinfo['token']);
		}
		return false;
	}
	
	//从微信中同步更新userinfo 中的数据
	public function synchronousFansInfo($wecha_id, $token)
	{
		$where = array('wecha_id' => $wecha_id, 'token' => $token);
		if ($userinfo = $this->where($where)->find()) {
			$wxuser = D('Wxuser')->where(array('token' => $token))->find();
			if (empty($wxuser)) return false;
			
			$apiOauth = new apiOauth();
			$access_token = $apiOauth->update_authorizer_access_token($wxuser['appid']);
			if ($access_token) {
				$jsonui = $apiOauth->get_fans_info($access_token, $userinfo['wecha_id']);
				if (isset($jsonui['openid']) && $jsonui['openid']) {
					$datainfo['wechaname'] = str_replace(array("'","\\"), array(''), $jsonui['nickname']);
					$datainfo['sex']       = $jsonui['sex'];
					$datainfo['portrait']  = $jsonui['headimgurl'];
					$datainfo['token']     = $userinfo['token'];
					$datainfo['wecha_id']  = $jsonui['openid'] ;
					$datainfo['city']      = $jsonui['city'] ;
					$datainfo['province']  = $jsonui['province'];
					$datainfo['truename']  = $datainfo['wechaname'] ;
					$datainfo['tel']  = $userinfo['tel'] ;
					//update
					if ($this->where($where)->save($datainfo)) {
						return $datainfo;
					}
				}
			}
		}
		return false;
	}
}