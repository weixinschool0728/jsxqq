<?php
bpBase::loadAppClass('front','front',0);
class sitePage extends front {
	function index(){
		if($_GET){
			foreach($_GET as $g=>$v){
				//$token=$g;
			}
		}
		if (!$token){
			$token=TOKEN;
		}

		//$token=mysql_real_escape_string(stripslashes($token));
		$token = htmlspecialchars($token);
		$this->dish_order_model = M('dish_order');
		//$where='token=\''.$token.'\' AND printed=0 AND diningtype>0';
		$where='token=\''.$token.'\' AND printed=0';
		$count      = $this->dish_order_model->count($where);
		$orders=$this->dish_order_model->get_one($where, $data = '*', $order = 'time ASC');
		$now=time();
		if ($orders){
			$thisOrder=$orders;
			switch ($thisOrder['takeaway']){
				default:
					$orderType='Ԥ������';
					break;
				case 1:
					$orderType='����';
					break;
				case 2:
					$orderType='���';
					break;
//				case 3:
//					$orderType='Ԥ������';
//					break;
			}

			//������Ϣ
			$diningtable_model = M('dining_table');
			if ($thisOrder['tableid']) {
				$thisTable = $diningtable_model->get_one(array('id'=>$thisOrder['tableid']),'*');
				$thisOrder['tableName'] = biconv($thisTable['name']);
			}else{
				$thisOrder['tableName']='';
			}
			$str="�������ͣ�".$orderType."\r\n������ţ�".$thisOrder['id']."\r\n������".biconv($thisOrder['name'])."\r\n�绰��".$thisOrder['tel']."\r\n��ַ��".biconv($thisOrder['address'])."\r\n��̨��".$thisOrder['tableName']."\r\n�µ�ʱ�䣺".date('Y-m-d H:i:s',$thisOrder['time'])."\r\nԤ��ʱ�䣺".date('Y-m-d H:i:s',$thisOrder['reservetime'])."\r\n��ӡʱ�䣺".date('Y-m-d H:i:s',$now)."\r\n--------------------------------\r\n";
			$str .= "��ע��" . biconv($thisOrder['des']) . "\r\n--------------------------------\r\n";
			//
			$carts = unserialize($thisOrder['info']);
			foreach ($carts['list'] as $p){
				$str.=biconv($p['name'])."  ".$p['num']."��  ���ۣ�".$p['price']."Ԫ\r\n";
				$i++;
			}
			if ($thisOrder['takeaway'] == 1) {
				$str .= biconv('�Ͳͷ�:') . "  " . $thisOrder['takeAwayPrice'] . "Ԫ\r\n";
			}
			$str.="\r\n--------------------------------\r\n�ϼƣ�" . $thisOrder['price'] . "Ԫ\r\n     лл�ݹˣ���ӭ�´ι���\r\n";
			//������Ϣ
			$thisCompany=M('company')->get_one(array('token' => $this->token, 'id' => $thisOrder['cid']));
			$str.="     " . biconv($thisCompany['name']) ."\r\n";
			//
			//$str=iconv('utf-8','gbk',$str);
			//����Ϊ��ӡ����
			$this->dish_order_model->update(array('printed'=>1),array('id'=>$thisOrder['id']));
			echo "CMD=01	FLAG=0	MESSAGE=success	DATETIME=".date('YmdHis',$now)."	ORDERCOUNT=".$count."	ORDERID=".$thisOrder['id']."	PRINT=".$str;
		}else {
			echo "CMD=01	FLAG=1	MESSAGE=no order now ".$token."	DATETIME=".date('YmdHis',time())."\r\n";
		}
	}
	function hotel(){
		if (!$token){
			$token = TOKEN;
		}
		$token = htmlspecialchars($token);
		$this->hotels_order_model = M('hotels_order');
		$where='token=\''.$token.'\' AND printed=0';
		//$count      = $this->hotels_order_model->count($where);
		$orders = $this->hotels_order_model->get_one($where, $data = '*', $order = 'time ASC');
		$now = time();
		if ($orders) {
			$thisOrder = $orders;
			$sort = M('hotels_house_sort')->get_one(array('id' => $thisOrder['sid']),'*');
			$thisCompany = M('company')->get_one(array('token' => $token, 'id' => $thisOrder['cid']));
			$thisOrder['houseName'] = biconv($sort['name']);
			$str = "������ţ�".$thisOrder['id']."\r\n";
			$str .= "������".biconv($thisOrder['name'])."\r\n";
			$str .= "�绰��".$thisOrder['tel']."\r\n";
			$str .= "��סʱ�䣺".date('Y-m-d', strtotime($thisOrder['startdate']))."\r\n";
			$str .= "�˷�ʱ�䣺".date('Y-m-d', strtotime($thisOrder['enddate']))."\r\n";
			
			$days = (strtotime($thisOrder['enddate']) - strtotime($thisOrder['startdate'])) / 86400;
			$str .= "��ס������". $days . "��\r\n";
			
			$str .= "�µ�ʱ�䣺" . date('Y-m-d H:i:s', $thisOrder['time'])."\r\n";
			$str .= "��ӡʱ�䣺".date('Y-m-d H:i:s', $now)."\r\n";
			$str .= "\r\n--------------------------------\r\n";
			$str .= "��������: " . $thisOrder['houseName'] . "\r\n";
			$str .= "��������" . $thisOrder['nums'] . "\r\n";
			$str .= "��ͨ���ۣ�" . $sort['price'] . "\r\n";
			$str .= "��Ա���ۣ�" . $sort['vprice'] . "\r\n";
			$str.="\r\n--------------------------------\r\n";
			$str .= "�ϼƣ�" . $thisOrder['price'] . "Ԫ\r\n";
			$str .= "     лл�ݹˣ���ӭ�´ι���\r\n\r\n";
			$str .= "     " . biconv($thisCompany['name']) ."\r\n";
			
			$this->hotels_order_model->update(array('printed'=>1),array('id'=>$thisOrder['id']));
			echo "CMD=01	FLAG=0	MESSAGE=success	DATETIME=".date('YmdHis',$now)."	ORDERCOUNT=".$count."	ORDERID=".$thisOrder['id']."	PRINT=".nl2br($str);
		} else {
			echo "CMD=01	FLAG=1	MESSAGE=no order now ".$token."	DATETIME=".date('YmdHis',time())."\r\n";
		}
	}
}
function biconv($str){
	return iconv('utf-8','gbk',$str);
}