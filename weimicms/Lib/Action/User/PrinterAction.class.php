<?php
class PrinterAction extends UserAction{
	
	public function index(){
		$token = session('token');
		$where = array('token'=>$token);
		$count = M('ext_printer')->where($where)->count();
		$Page = new Page($count);
		$show = $Page->show();
		
		$list = M('ext_printer')->where($where)->limit($Page->firstRow.','.$Page->listRows)->order('id desc')->select();
		
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}
	
	
	public function set() {
		$token = session('token');
		
		if(IS_GET) {
			
			$id = get('id');
			if($id) {
				$printer = M('ext_printer')->where(array('id'=>$id, 'token'=>$token))->find();
			} else {
				$printer['print_count'] = 1;
				// 默认的打印模版
$printer['template'] = <<<EOF
         外卖订餐
流水号：{流水号}
姓名：{姓名}
手机：{手机}
地址：{地址}
下单时间：{下单时间}
-------------------------------
商品         单价   数量   金额
{{序号}.{商品}
           {单价} {数量} {金额}}
-------------------------------
                   合计：{合计}
		
服务电话：88888888
EOF;
			}
			
			$this->assign('printer', $printer);
			$this->display();
			
		} else if(IS_POST) {
			
			if(isset($_POST['preview'])) {
				$ext_printer['sn']=post('sn', false);
				$ext_printer['sn_encrypt'] = ' ';
				echo '<pre>';
				$this->preview($ext_printer, post('template', false));
				echo '</pre>';
				return;
			}
			
			$this->printerid = post('id');
			$data['name'] = post('name');
			$data['sn'] = post('sn', false);
			$data['print_count'] = post('print_count', false);	// 打印联数
			$data['template'] = post('template', false);
			$data['deal_order'] = isset($_POST['deal_order']) ? 1 : 0;	// 是否:打印成功后，自动标记订单已处理
			
			// 检查sn是否重复
			$test = M('ext_printer')->where(array('sn'=>$data['sn']))->select();
			if($test) {
				if( count($test)>1 || ($test[0] && $test[0]['id']!=$this->printerid) ) {
					$this->error('操作失败！系统检查到重复的SN');
				}
			}
			
			if(!$this->printerid) {
				// 增加
				$data['token'] = $token;
				$data['sn_encrypt'] = ' ';
				$data['create_time'] = time();
				$this->printerid = M('ext_printer')->add($data);
				$this->success('操作成功', U('Printer/index'));
			} else {
				// 修改
				$printer = M('ext_printer')->where(array('id'=>$this->printerid))->find();
				if($printer['token']===$token) {
					M('ext_printer')->where(array('id'=>$this->printerid))->save($data);
				}
				$this->success('操作成功');
			}
		}
	}
	
	private function preview($ext_printer,$template) {
		// 预览数据
		header("Content-Type:text/html;charset=gb2312");
		
		$s_pos = strpos($template, '{{');
		$e_pos = strpos($template, '}}');
		$part1 = substr($template, 0, $s_pos);
		$part2 = substr($template, $s_pos+1, $e_pos-$s_pos);
		$part3 = substr($template, $e_pos+2);
		
		$time = time();
		$dateStr = date('Ymd', $time);
		$auto_new_line = strpos($part2, "\n")===false ? true : false;
		$new_line = '';
		if($auto_new_line) {
			if(preg_match('/\}(.*)\{商品\}/', $part2, $mat1)===false) {
				$new_line = "\r\n";
			} else {
				$new_line = "\r\n".str_repeat(' ', zh_strlen($mat1[1]) + 1);
			}
		}
		preg_match('/\{商品\} */', $part2, $matches);
		if($matches) $name_var = $matches[0]; else $name_var = '{商品}';
		$total = 0;
		$print_part2 = '';
		$i = 1;
		
		$name = '商品1';	$price=10.0; $count=1;
		$amount = $price * $count;
		$listVars = get_print_replace_array($ext_printer,$name_var,$name,$auto_new_line,$new_line,$i++,$price,$count,$amount);
		if(strlen($print_part2)>0) $print_part2 .= "\r\n";
		$print_part2 .= str_replace(array_keys($listVars), array_values($listVars), $part2);
		$total += $amount;
		
		$name = '名称有点长的商品2';	$price=30.0; $count=2;
		$amount = $price * $count;
		$listVars = get_print_replace_array($ext_printer,$name_var,$name,$auto_new_line,$new_line,$i++,$price,$count,$amount);
		if(strlen($print_part2)>0) $print_part2 .= "\r\n";
		$print_part2 .= str_replace(array_keys($listVars), array_values($listVars), $part2);
		$total += $amount;
		
		$vars['{流水号}'] = $dateStr.sprintf("%04d", 1);
		$vars['{姓名}'] = '张三';
		$vars['{手机}'] = '13800138000';
		$vars['{地址}'] = '天朝帝都';
		$vars['{下单时间}'] = date('Y-m-d H:i:s', $time);
		$vars['{合计}'] = sprintf("%6s",number_format($total, 2));
		$print_part1 = str_replace(array_keys($vars), array_values($vars), $part1);
		$print_part3 = str_replace(array_keys($vars), array_values($vars), $part3);
		$print_str = iconv('UTF-8','GB2312',$print_part1.$print_part2.$print_part3);
		echo $print_str;
	}
	
	public function del() {
		$id = get('id');
		$token = session('token');
		M('ext_printer')->where(array('id'=>$id, 'token'=>$token))->delete();
		M('ext_printer_2_product')->where(array('ext_printer_id'=>$id))->delete();
		M('ext_printer_2_product_cat')->where(array('ext_printer_id'=>$id))->delete();
		$this->success('操作成功');
	}
	
}