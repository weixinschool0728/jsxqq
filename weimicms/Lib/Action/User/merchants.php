<?php
	define('ABS_PATH', dirname(__FILE__).DIRECTORY_SEPARATOR.'Cashier'.DIRECTORY_SEPARATOR);
	define('weimicms_CORE_PATH','./weimicms/');
	define('weimicms_CORE_PATH_FOLDER','./Cashier/weimicms/');
	
	define('weimicms_TPL_PATH','./weimicms_tpl/');
	define('weimicms_TPL_PATH_FOLDER','./Cashier/weimicms_tpl/');

	define('weimicms_STATIC_PATH','./weimicms_static/');
	define('weimicms_STATIC_PATH_FOLDER','./Cashier/weimicms_static/');
	define('ABS_UPLOAD_PATH','/Cashier');
	define('APP_NAME','Merchants');
	define('DEBUG',true);
	define('GZIP',true);
	include ABS_PATH.'config'.DIRECTORY_SEPARATOR.'config.inc.php';
	include ABS_PATH.weimicms_CORE_PATH.'base.php';
	bpBase::creatApp();
?>