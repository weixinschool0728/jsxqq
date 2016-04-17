<?php
$name = _get('key');
$count = _get('count');
if($count == null){
	$count = 1;
}

$url='http://www.xiami.com/web/search-songs?key=' . $name;
$content = curlGet($url);
$array = json_decode($content);

if($array != null){
	$array = array_slice($array,0,$count);
	$array = json_encode($array);
	echo $array;
}else{
	echo $content;
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