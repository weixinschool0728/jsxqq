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

		if (!(strpos($name, '����') === false)) {
			return '�ȿȣ�����ֻ��΢�Ż�����';
		}

		if (($name == '���ʲô') || ($name == '����˭')) {
			return '�ȿȣ����Ǵ������ǻ۲������Ů,�˼Ҹս�������,�㲻��׷����';
		}
		else if ($name == '����') {
			$name = 'Ц��';
		}

		//$str = 'http://liaotian.404.cn/pgicms_api/api.php?key=free&server_key=' . base64_encode(C('server_key')) . '&server_topdomain=' . C('server_topdomain') . '&appid=0&msg=' . urlencode($name);
        $str = "http://www.tuling123.com/openapi/api?key={$this->tkey}&info=".$name;

        $json = Http::fsockopenDownload($str);

		if ($json == false) {
			$json = file_get_contents($str);
		}

		$json = json_decode($json, true);
		//$str = str_replace('�Ʒ�', $this->my, str_replace('��ʾ��', $this->my . '������:', str_replace('{br}', "\n", $json['content'])));
		//return $str . '' . "\n" . '[�������������--' . $this->my . ']';
        return $json['text'];


    }
}

echo "\r\n";

?>
