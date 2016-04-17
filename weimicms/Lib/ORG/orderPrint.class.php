<?php
class orderPrint {
	public $token;
    public $uid = 675;
    public $apikey = '9be9d70bfdadf3f96bd67fab92fd473122f707f7';
	public function __construct($token){

		$this->token=$token;
	}
	public function printit($token, $companyid=0, $ordertype='', $content = '', $paid=0){
		$companyid=intval($companyid);
		$printers=M('Orderprinter')->where(array('token'=>$token))->select();
		/*
		if ($companyid){
			$printers=M('Orderprinter')->where(array('token'=>$token,'companyid'=>$companyid))->select();
		}else {
			$printers=M('Orderprinter')->where(array('token'=>$token))->select();
		}
		*/
		F('1',$printers);
		$usePrinters=array();
		if ($printers){
			foreach ($printers as $p){
				$ms=explode(',',$p['modules']);
				if (in_array($ordertype,$ms)&&(!$companyid||$p['companyid']==$companyid||!$p['companyid'])){
					array_push($usePrinters,$p);
				}
			}
		}
		F('2',$usePrinters);
		if ($usePrinters){
			foreach ($usePrinters as $p){
				if (!$p['paid']||($p['paid']&&$paid)){
                    for($i=1;$i<=$p['count'];$i++){
						if($p['qrcode']) $content .= "\r\n<q>".$p['qrcode']."</q>";
                        $res = $this->sendMsgToElink($content,$this->apikey,$p['mkey'],$this->uid,$p['mcode']);
                    }
                    //echo $content.'==='.$res;
					F('3',$rt);
				}
			}
		}
	}
    function httppost1($params) {
        $url = 'open.10ss.net:8888';
        $p = '';
        foreach ($params as $k => $v) {
            $p .= $k.'='.$v.'&';
        }
        $data = rtrim($p, '&');
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:'));
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_COOKIEFILE, $GLOBALS['cookie_file']);
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $tmpInfo = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);
        }
        curl_close($curl);
        return $tmpInfo;
    }

    //向兄弟联提交信息 返回兄弟联返回标志
    function sendMsgToElink($msg,$apiKey,$mKey,$partner,$machine_code){
        $time = time();
        $params = array(
        'partner'=>$partner,
        'machine_code'=>$machine_code,
        'time'=>$time
        );
        $sign = $this->generateSign($params,$apiKey,$mKey);
        $params['content'] = $msg;
        $params['sign'] = $sign;
        $return = $this->httppost1($params);
        return $return;
    }


    function generateSign($params, $apiKey, $msign)
    {
        ksort($params);
        $stringToBeSigned = $apiKey;
        foreach ($params as $k => $v)
        {
            $stringToBeSigned .= urldecode($k.$v);
        }
        unset($k, $v);
        $stringToBeSigned .= $msign;
        return strtoupper(md5($stringToBeSigned));
    }
}
