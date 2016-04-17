<?php
class AloChat
{	
	public $keyword;
	public $my;
	public function __construct($keyword,$user)
	{
		$this->keyword=$keyword;
		$this->wecha_id=$user;
		$this->key=C('chat_key');
	}
	public function index(){
		$name=$this->keyword;
		$rt=array();
		$type='news';
		if ($name == '你父母是谁' || $name == '你爸爸是谁' || $name == '你妈妈是谁') {
			$rt = '主人,' . C('site_my') . '是微米微信创造的,所以他们是我的父母,不过主人我属于你的';
			$type='text';
		} elseif ($name == '网站' || $name == '官网' || $name == '网址' || $name == '3g网址') {
			$rt =  '【' . C ( 'site_name' ) . '】\n' . C ( 'site_name' ) . '\n【' . C ( 'site_name' ) 
				. '服务宗旨】\n化繁为简,让菜鸟也能使用强大的系统!';
			$type='text';
		} else{
			if($name=='糗事'){
				$name='笑话';
			}
			$str='http://www.tuling123.com/openapi/api?key='.$this->key.'&userid='.$this->wecha_id;
			$str.='&info='.urlencode($name);
			$json = file_get_contents($str);
			$json=json_decode($json,true);
			$code=$json['code'];
			switch($code){
				case 100000://文本类数据
					$rt=$json['text'];
					$type='text';
					break;
				case 200000://网址类数据
					$rt='<a href="'.$json['url'].'">点击这里访问</a>';
					$type='text';
					break;
				case 302000://新闻
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['article'],
							'来自：'.$item[$i]['source'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 304000://应用软件下载
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['name'],
							'已下载：'.$item[$i]['count'].'次',
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 305000://列车
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['start'].' - '.$item['terminal'].' , '.$item[$i]['trainnum'],
							$item[$i]['starttime'].' , '.$item[$i]['endtime'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 306000://航班
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['flight'].' , '.$item['route'].' , '.$item[$i]['state'],
							$item[$i]['starttime'].' , '.$item[$i]['endtime'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 308000://菜谱、视频、小说
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['name'],
							$item[$i]['info'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 309000://酒店
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['name'],
							$item[$i]['price'].' , '.$item[$i]['satisfaction'].' , '.$item[$i]['count'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 311000://价格
					$item=$json['list'];
					$icount=count($item);
					for($i=0;$i<$icount;$i++){
						if(count($rt)==9) break;
						if($item[$i]['icon']=='') continue;
						array_push($rt,array(
							$item[$i]['name'],
							'售价：'.$item[$i]['price'],
							$item[$i]['icon'],
							$item[$i]['detailurl']
						));
					}
					break;
				case 40001://key的长度错误(32位)
					$rt='key的长度错误(32位)';
					$type='text';
					break;
				case 40002://请求内容为空
					$rt='请求内容为空';
					$type='text';
					break;
				case 40003://key错误或帐号未激活
					$rt='key错误或帐号未激活';
					$type='text';
					break;
				case 40004://当天请求次数已用完
					$rt='当天请求次数已用完';
					$type='text';
					break;
				case 40005://暂不支持该功能
					$rt='暂不支持该功能';
					$type='text';
					break;
				case 40006://服务器升级中
					$rt='服务器升级中';
					$type='text';
					break;
				case 40007://服务器数据格式异常
					$rt='服务器数据格式异常';
					$type='text';
					break;
				default:
					$rt='微米君也不知道该发生什么啦~~~';
					$type='text';
					break;
			}
		}
		return array(
			$rt,
			$type
		);
	}
}
?>

