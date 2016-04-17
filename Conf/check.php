<?php
header("Content-Type: text/html;charset=utf-8");
$dirfile_items = array(
    'conf' => array('type' => 'file', 'path' => './Conf/db.php'),
    'info' => array('type' => 'file', 'path' => './Conf/info.php'),
	
	'L' => array('type' => 'dir', 'path' => './Lib/'),
	
	'Co' => array('type' => 'dir', 'path' => './Common/'),
	
	
   'c' => array('type' => 'dir', 'path' => './Conf/'),
   'cl' => array('type' => 'dir', 'path' => './Conf/logs/'),
   'clC' => array('type' => 'dir', 'path' => './Conf/logs/Cache/'),
   'clT' => array('type' => 'dir', 'path' => './Conf/logs/Temp/'),
   
   'u' => array('type' => 'dir', 'path' => './uploads/'),
   
   'PD' => array('type' => 'dir', 'path' => './weimidata/'),
   'PDd' => array('type' => 'dir', 'path' => './weimidata/database/'),
   
	'PL' => array('type' => 'dir', 'path' => './weimicms/Lib/'),
	
	'PLA' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/'),
	'PLAA' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Agent/'),
	'PLAC' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Chat/'),
	'PLAF' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Fuwu/'),
	'PLAH' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Home/'),
	'PLAO' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Other/'),
	'PLAS' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/System/'),
	'PLAU' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/User/'),
	'PLAW' => array('type' => 'dir', 'path' => './weimicms/Lib/Action/Wap/'),
	
	'PLM' => array('type' => 'dir', 'path' => './weimicms/Lib/Model/'),
	'PLMO' => array('type' => 'dir', 'path' => './weimicms/Lib/Model/Other/'),
	'PLMU' => array('type' => 'dir', 'path' => './weimicms/Lib/Model/User/'),

	'PLO' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/'),
	'PLOae' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/aes/'),
	'PLOAY' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Alipay/'),
	'PLOAP' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Allinpay/'),
	'PLOF' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Fuwu/'),
	'PLOT' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Tenpay/'),
	'PLOTC' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/TenpayComputer/'),
	'PLOW' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/WapAlipay/'),
	'PLOWN' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Weixinnewpay/'),
	'PLOWP' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Weixinpay/'),
	'PLOY' => array('type' => 'dir', 'path' => './weimicms/Lib/ORG/Yeepay/'),
	
	
	't' => array('type' => 'dir', 'path' => './tpl/'),
	'tA' => array('type' => 'dir', 'path' => './tpl/Agent/'),
	
	'tAB' => array('type' => 'dir', 'path' => './tpl/Agent/Basic/'),
	'tAC' => array('type' => 'dir', 'path' => './tpl/Agent/Common/'),
	'tAF' => array('type' => 'dir', 'path' => './tpl/Agent/Frame/'),
	'tAI' => array('type' => 'dir', 'path' => './tpl/Agent/Index/'),
	'tAL' => array('type' => 'dir', 'path' => './tpl/Agent/Login/'),
	'tAS' => array('type' => 'dir', 'path' => './tpl/Agent/Site/'),
	'tAU' => array('type' => 'dir', 'path' => './tpl/Agent/Users/'),
	
	'tC' => array('type' => 'dir', 'path' => './tpl/Chat/'),
	'tCd' => array('type' => 'dir', 'path' => './tpl/Chat/default/'),
	'tCds' => array('type' => 'dir', 'path' => './tpl/Chat/default/style/'),
	
	'tO' => array('type' => 'dir', 'path' => './tpl/Other/'),
	'tOd' => array('type' => 'dir', 'path' => './tpl/Other/default/'),
	'tOs' => array('type' => 'dir', 'path' => './tpl/Other/style/'),
	
	'ts' => array('type' => 'dir', 'path' => './tpl/static/'),
	'tSYS' => array('type' => 'dir', 'path' => './tpl/System/'),
	
	'tU' => array('type' => 'dir', 'path' => './tpl/User/'),
	'tUd' => array('type' => 'dir', 'path' => './tpl/User/default/'),
	'tUdc' => array('type' => 'dir', 'path' => './tpl/User/default/common/'),
	
	'tW' => array('type' => 'dir', 'path' => './tpl/Wap/'),
	'tWd' => array('type' => 'dir', 'path' => './tpl/Wap/default/'),
	'tWdc' => array('type' => 'dir', 'path' => './tpl/Wap/default/common/'),

	
);



define('ROOT_PATH', dirname(__FILE__).'/../');//网站根目录
dirfile_check($dirfile_items);
//文件权限检查
function dirfile_check(&$dirfile_items) {
	foreach($dirfile_items as $key => $item) {
		$item_path = $item['path'];
		if($item['type'] == 'dir') {
			if(!dir_writeable(ROOT_PATH.$item_path)) {
				if(is_dir(ROOT_PATH.$item_path)) {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
					echo $item_path.'&nbsp;&nbsp;&nbsp; <font color="#FF0000 size="-1">可读不可写</font><br><br>';
				} else {
					$dirfile_items[$key]['status'] = -1;
					$dirfile_items[$key]['current'] = 'nodir';
					echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">目录无可读可写权限</font><br><br>';
				}
			} else {
				//echo '<br>3';
				$dirfile_items[$key]['status'] = 1;
				$dirfile_items[$key]['current'] = '+r+w';
				echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
			}
		} else {
			//echo '<br>4';
			if(file_exists(ROOT_PATH.$item_path)) {
				if(is_writable(ROOT_PATH.$item_path)) {
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
					echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
				//	echo '<br>5';
				} else {
					$dirfile_items[$key]['status'] = 0;
					$dirfile_items[$key]['current'] = '+r';
					echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">文件无可写权限</font><br><br>';
					//echo '<br>6';
				}
			} else {
				//echo '<br>7';
				if ($fp = @fopen(ROOT_PATH.$item_path,'wb+')){
					$dirfile_items[$key]['status'] = 1;
					$dirfile_items[$key]['current'] = '+r+w';
					echo $item_path.'&nbsp;&nbsp;&nbsp;<font size="-1">权限通过</font><br><br>';
				}else {
					$dirfile_items[$key]['status'] = -2;
					$dirfile_items[$key]['current'] = 'nofile';
				    echo $item_path.'&nbsp;&nbsp;&nbsp;<font color="#FF0000 size="-1">文件无可读可写权限</font><br><br>';
				}
			}
		}
	}
}
function dir_writeable($dir) {
	//echo $dir;
	$writeable = 0;
	if(!is_dir($dir)) {
		@mkdir($dir, 0755);
		//echo '<br>a';
	}else {
		@chmod($dir,0755);
	//	echo '<br>b';
	}
	if(is_dir($dir)) {
		//echo '<br>c';
		if($fp = @fopen("$dir/test.txt", 'w')) {
		//	echo '<br>d';
			@fclose($fp);
			@unlink("$dir/test.txt");
			$writeable = 1;
		} else {
		//	echo '<br>e';
			$writeable = 0;
		}
	}
	//echo $writeable.'前面有值';
	return $writeable;
}

?>