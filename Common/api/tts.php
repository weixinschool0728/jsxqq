<?php
header("Content-type: audio/mp3"); 
$name = _get('text');
$status = check_str($name);

if($status == 1){
	$lan = "en";
}else{
	$lan = "zh";
}

$url='http://tts.baidu.com/text2audio?ie=UTF-8&spd=2&lan=' . $lan . '&text=' . $name;
$content = curlGet($url);
echo $content;

/* 
*function：检测字符串是否由纯英文，纯中文，中英文混合组成 
*param string 
*return 1:纯英文;2:纯中文;3:中英文混合 
*/  
function check_str($str=''){  
	if(trim($str)==''){  
		return '';  
	}  
	$m=mb_strlen($str,'utf-8');  
	$s=strlen($str);  
	if($s==$m){  
		return 1;  
	}  
	if($s%$m==0&&$s%3==0){  
		return 2;  
	}  
	return 3;  
}

function curlGet($url){
	$ch = curl_init();
	$header = array("Accept-Charset: utf-8");
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$temp = curl_exec($ch);
	return $temp;
}

function _get($str){
	$val = !empty($_GET[$str]) ? $_GET[$str] : null;
	return $val;
} 
?>