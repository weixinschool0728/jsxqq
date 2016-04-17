<?php
header("Content-type: text/html; charset=utf-8");

if (get_magic_quotes_gpc()) {
 function stripslashes_deep($value){
  $value = is_array($value) ?
  array_map('stripslashes_deep', $value) :
  stripslashes($value);
  return $value;
 }
 $_POST = array_map('stripslashes_deep', $_POST);
 $_GET = array_map('stripslashes_deep', $_GET);
 $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
define('APP_NAME', 'cms');
define('CONF_PATH','./../weimidata/conf/');
define('TMPL_PATH','./../tpl/');
$GLOBALS['_beginTime'] = microtime(TRUE);
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));
define('CORE','./../weimicms/_Core');
if(MEMORY_LIMIT_ON) $GLOBALS['_startUseMems'] = memory_get_usage();
define('APP_PATH','./../weimicms/');
defined('APP_PATH') 	or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']).'/');
define('RUNTIME_PATH','./../weimidata/logs/');
defined('RUNTIME_PATH') or define('RUNTIME_PATH',APP_PATH.'Runtime/');
define('APP_DEBUG',1);
defined('APP_DEBUG') 	or define('APP_DEBUG',false);
$runtime = defined('MODE_NAME')?'~'.strtolower(MODE_NAME).'_runtime.php':'~runtime.php';
require(CORE.'/weimicms.php');
?>
