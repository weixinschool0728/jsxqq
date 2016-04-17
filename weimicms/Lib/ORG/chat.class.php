<?php
class chat
{
	public $keyword;
	public $my;
    public $tkey;

	public function __construct($keyword)
	{
		$this->keyword = $keyword;
		$this->my = C('site_my');
        $this->tkey = C('jy_tuling') ? C('jy_tuling'):'9314a0781c15cf07fac082630b2db7ee';
	}

	public function index()
	{
		$name = $this->keyword;

		if (!(strpos($name, '你是') === false)) {
			return '咳咳，我是只能微信机器人';
		}

		if (($name == '你叫什么') || ($name == '你是谁')) {
			return '咳咳，我是聪明与智慧并存的美女,人家刚交男朋友,你不可追我啦';
		}
		else if ($name == '糗事') {
			$name = '笑话';
		}

		//$str = 'http://liaotian.404.cn/pgicms_api/api.php?key=free&server_key=' . base64_encode(C('server_key')) . '&server_topdomain=' . C('server_topdomain') . '&appid=0&msg=' . urlencode($name);
        $str = "http://www.tuling123.com/openapi/api?key={$this->tkey}&info=".$name;

        $json = Http::fsockopenDownload($str);

		if ($json == false) {
			$json = file_get_contents($str);
		}

		$json = json_decode($json, true);
		//$str = str_replace('菲菲', $this->my, str_replace('提示：', $this->my . '提醒您:', str_replace('{br}', "\n", $json['content'])));
		//return $str . '' . "\n" . '[我是聊天机器人--' . $this->my . ']';
        return $json['text'];


    }
}

echo "\r\n";

?>
