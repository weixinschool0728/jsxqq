<?php
class updateSync extends BackAction
{
	static 	private $functionLibrary_url = "oa/admin.php?m=server&c=sys_file&a=funLib&domain=";
	static 	private $functions_url = "oa/admin.php?m=server&c=sys_file&a=funModules&domain=";
	static 	private $function_version_file = "oa/admin.php?m=server&c=sys_file&a=versionFiles&domain=";
	static 	private $ifWeidian = "oa/admin.php?m=server&c=sys_file&a=haveweidian&domain=";
	static 	private $getcustomer_api = "oa/admin.php?m=server&c=sys_file&a=getCustomer&domain=";
	static 	private $domain = array("weihubao.com", "dazhongbanben.com", "webcms.cn");

	static private function _init()
	{
		if (!self::formart(self::$functionLibrary_url)) {
			return true;
		}

		$server = getUpdateServer();

		if (in_array(C("server_topdomain"), self::$domain)) {
			$url = parse_url(C("site_url"));
			self::$functionLibrary_url = $server . self::$functionLibrary_url . $url["host"];
			self::$functions_url = $server . self::$functions_url . $url["host"];
			self::$function_version_file = $server . self::$function_version_file . $url["host"];
			self::$ifWeidian = $server . self::$ifWeidian . $url["host"];
			self::$getcustomer_api = $server . self::$getcustomer_api . $url["host"];
		}
		else {
			self::$functionLibrary_url = $server . self::$functionLibrary_url . C("server_topdomain");
			self::$functions_url = $server . self::$functions_url . C("server_topdomain");
			self::$function_version_file = $server . self::$function_version_file . C("server_topdomain");
			self::$ifWeidian = $server . self::$ifWeidian . C("server_topdomain");
			self::$getcustomer_api = $server . self::$getcustomer_api . C("server_topdomain");
		}
	}

	static public function getIfWeidian()
	{
		if (S("up_api_ifweidian")) {
			return S("up_api_ifweidian");
		}

		self::_init();
		$return = self::curl_get_data(self::$ifWeidian);
		S("up_api_ifweidian", $return, 0);
		return $return;
	}

	static public function sync_function_library()
	{
		$rt = self::curl_get_data(self::$functionLibrary_url);

		if (preg_match("/^[\w\,\_\-\ ]+$/", $rt)) {
			$rt = array_map("trim", explode(",", $rt));
			file_put_contents(RUNTIME_PATH . "function_library.php", "<?php \nreturn " . stripslashes(var_export($rt, true)) . ";", LOCK_EX);
		}
	}

	static private function sync_function_list()
	{
		if (C("server_topdomain") != "weimicms.cn") {
			$rt = json_decode(self::curl_get_data(self::$functions_url), true);

			if ($rt) {
				$db_model = M("Function");
				$current_functions = $db_model->field("funname")->where("funname != ''")->select();
				$funname_arr = array();
				$current_funname_arr = array();

				foreach ($rt as $value ) {
					if ($value["status"]) {
						$funname_arr[] = $value["funname"];
					}
				}

				if ($current_functions) {
					foreach ($current_functions as $v ) {
						$current_funname_arr[] = $v["funname"];
					}
				}

				$less = array_diff($funname_arr, $current_funname_arr);

				foreach ($rt as $rk => $rv ) {
					if (($rv["status"] == 1) && in_array($rv["funname"], $less)) {
						unset($rt["$rk"]["id"]);
						$db_model->add($rt["$rk"]);
					}
					else if ($rv["status"] == 0) {
						$delete_data = $rt["$rk"]["funname"];
						$db_model->where(array("funname" => $delete_data))->delete();
					}
				}
			}
		}
	}

	static private function sync_function_version_file()
	{
		if (C("server_topdomain") != "weimicms.cn") {
			$versionFile = (array) json_decode(self::curl_get_data(self::$function_version_file), true);

			if ($versionFile) {
				foreach ($versionFile as $file ) {
					$filename = "." . $file;

					if (is_file($filename)) {
						$status = (@unlink($filename) ? "SUCCESS" : "ERROR");
					}
				}
			}
		}
	}

	public function finished_callback()
	{
		if (C("emergent_mode")) {
			return "404";
		}

		if (!C("tp_STAFF")) {
			self::_init();
			self::group_functions_add_Weixin();
			self::sync_function_library();
			self::sync_function_list();
			self::sync_function_version_file();
			NODEset::index();
		}
	}

	public function group_functions_add_Weixin()
	{
		$user_group = M("User_group")->field("id,func")->select();
		$flag = 0;

		if ($user_group) {
			foreach ($user_group as $value ) {
				if (in_array("Weixin", explode(",", $value["func"]))) {
					$flag++;
					break;
				}
			}

			if ($flag == 0) {
				foreach ($user_group as $v ) {
					M("User_group")->where(array("id" => $v["id"]))->setField("func", $v["func"] . ",Weixin");
				}
			}
		}
	}

	static public function version($field = NULL)
	{
		self::_init();

		if (S("tp_customer_info")) {
			$result = S("tp_customer_info");
		}
		else {
			$result = json_decode(self::curl_get_data(self::$getcustomer_api));

			if ($result->success == 1) {
				S("tp_customer_info", $result, 0);
			}
			else {
				return NULL;
			}
		}

		if ($field == NULL) {
			return $result->version;
		}

		return $result->$field;
	}

	static public function uniqueID()
	{
		return self::version("id");
	}

	static public function formart($url)
	{
		if (substr($url, -1) == "=") {
			return true;
		}
		else {
			return false;
		}
	}

	private function curl_get_data($url)
	{
		if (function_exists("curl_init")) {
			$ch = curl_init();
			$timeout = 1;
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
			$return = curl_exec($ch);
			curl_close($ch);
		}
		else if (function_exists("file_get_contents")) {
			$return = file_get_contents($url);
		}
		else {
			$return = false;
		}

		return $return;
	}
}


