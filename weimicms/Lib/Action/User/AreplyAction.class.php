<?php
/**
 *关注回复
**/
class AreplyAction extends UserAction{
	public function index(){
		$db=D('Areply');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		$this->assign('areply',$res);
		$this->display();
	}
	public function insert(){
		C('TOKEN_ON',false);
		$db=D('Areply');
		$where['uid']=$_SESSION['uid'];
		$where['token']=$_SESSION['token'];
		$res=$db->where($where)->find();
		if($res==false){
			$where['content']=html_entity_decode($this->_post('content'));
			if(empty($_POST['keyword'])){	
				if($where['content']==false){$this->error('内容必须填写');}
			}else{
				$where['keyword']=$this->_post('keyword');
			}			
			
			$where['createtime']=time();
			$id=$db->data($where)->add();
			if($id){
				$this->success('发布成功',U('Areply/index'));
			}else{
				$this->error('发布失败',U('Areply/index'));
			}
		}else{
			$where['id']=$res['id'];
			$where['content']=html_entity_decode($this->_post('content'));
			$where['home']=intval($this->_post('home'));
			$where['updatetime']=time();
			$where['keyword']=strval($this->_post('keyword'));
			if(empty($_POST['keyword'])){
				if($where['content']==false){$this->error('内容必须填写');}
			}else{
				$where['keyword']=$this->_post('keyword');
			}		
			if($db->save($where)){
				$this->success('更新成功',U('Areply/index'));
			}else{
				$this->error('更新失败',U('Areply/index'));
			}
		}
	}
	public function advanceindex()
	{
		$where = array("token" => $this->token);
		$count = M("subscribe_reply")->where($where)->count();
		$page = new Page($count, 10);
		$list = M("subscribe_reply")->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();

		foreach ($list as $key => $value ) {
			if (($value["reply_type"] == 5) && ($value["relevance_id"] == "")) {
				$list[$key]["status"] = 1;
			}
			else {
				if (($value["reply_type"] == 5) && ($value["relevance_id"] != "")) {
					$list[$key]["lows"] = substr_count($value["relevance_id"], ",");
				}
			}
		}

		$this->assign("list", $list);
		$this->assign("page", $page->show());
		$this->display();
	}

	public function advanceadd()
	{
		if (IS_POST) {
			if ((count($_POST["start_time"]) == 1) && (count($_POST["end_time"]) == 1)) {
				$data["start_time"] = $_POST["start_time"][0];
				$data["end_time"] = $_POST["end_time"][0];
			}
			else {
				if ((1 < count($_POST["start_time"])) && (1 < count($_POST["end_time"]))) {
					$data["start_time"] = $_POST["start_time"];
					$data["end_time"] = $_POST["end_time"];
				}
			}

			$data["reply_type"] = (int) $_POST["reply_type"];
			$data["id"] = (int) $_POST["id"];
			$data["original_id"] = (int) $_POST["original_id"];
			$data["imgids"] = trim($_POST["imgids"], ",");
			$data["r_type"] = trim($_POST["r_type"]);

			switch ($data["reply_type"]) {
			case $data["reply_type"]:
				$this->AddimgReply($data);
				break;

			case $data["reply_type"]:
				$this->AddtitleReply($data);
				break;

			case $data["reply_type"]:
				$this->AddcardReply($data);
				break;

			case $data["reply_type"]:
			case $data["reply_type"]:
				$this->AddhbReply($data);
				break;

			case $data["reply_type"]:
				$this->AddmultiimgReply($data);
				break;

			case $data["reply_type"]:
				$this->AddImgAction($data);
				break;

			default:
				break;
			}
		}

		if ($_GET["id"] != "") {
			$set = M("subscribe_reply")->where(array("id" => (int) $_GET["id"]))->find();

			if (5 == $set["reply_type"]) {
				if (strpos($set["relevance_id"], ",") !== false) {
					$set["relevance_id"] = trim($set["relevance_id"], ",");
					$mlist = M("img")->where(array(
	"id" => array("in", $set["relevance_id"])
	))->field("id,text,pic,info,url,title")->order("field(id," . $set["relevance_id"] . ")")->select();
					$this->assign("end_img", $mlist[0]);
					array_shift($mlist);
					$this->assign("mlist", $mlist);
				}
			}
			else {
				if ((1 == $set["reply_type"]) || (7 == $set["reply_type"])) {
					$img = M("img")->where(array("id" => $set["relevance_id"]))->field("id,text,pic,url,info,title")->find();
					$this->assign("end_img", $img);
				}
			}

			$set["id"] = (int) $_GET["id"];
			$this->assign("set", $set);
		}
		else {
			$this->assign("reply_type", $_GET["reply_type"]);
		}

		$r_type = ($_GET["r_type"] ? trim($_GET["r_type"]) : "add");
		$this->assign("r_type", $r_type);
		$this->assign("winxintype", $this->wxuser["winxintype"]);
		$this->assign("token", $this->token);
		$this->display();
	}

	public function AddimgReply($data)
	{
		if ($data["r_type"] == "select") {
			$data["title"] = trim($_POST["imgtitle"], ",");

			if ($data["id"] == "") {
				if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
					$data["relevance_id"] = $data["imgids"];
					$data["token"] = $this->token;
					$data["add_time"] = $_SERVER["REQUEST_TIME"];
					$data["update_time"] = 0;
					$insert = M("subscribe_reply")->add($data);
				}
				else {
					if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
						foreach ($data["start_time"] as $kk => $vv ) {
							if (($vv != "") && ($data["end_time"][$kk] != "")) {
								$dataAll[$kk]["start_time"] = $vv;
								$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
								$dataAll[$kk]["reply_type"] = $data["reply_type"];
								$dataAll[$kk]["r_type"] = $data["r_type"];
								$dataAll[$kk]["relevance_id"] = $data["imgids"];
								$dataAll[$kk]["title"] = $data["title"];
								$dataAll[$kk]["token"] = $this->token;
								$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
								$dataAll[$kk]["update_time"] = 0;
							}
						}

						$insert = M("subscribe_reply")->addAll($dataAll);
					}
					else {
						$this->error("参数错误");
					}
				}

				if ($insert) {
					if ($data["original_id"] != "") {
						M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
					}

					$this->success("单图文消息添加成功", U("Areply/advanceindex", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("单图文消息添加失败");
				}
			}
			else {
				$data["relevance_id"] = $data["imgids"];
				$data["update_time"] = $_SERVER["REQUEST_TIME"];
				$update = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);

				if ($update) {
					$this->success("单图文消息编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("单图文消息编辑失败");
				}
			}
		}
		else {
			if (trim($_POST["img_title"]) == "") {
				$this->error("回复标题不能为空");
			}

			if (trim($_POST["reply_pic"]) == "") {
				$this->error("回复图片不能为空");
			}

			$data["title"] = trim($_POST["img_title"]);
			$img = array();
			$img["title"] = trim($_POST["img_title"]);
			$img["text"] = trim($_POST["reply_desc"]);

			if ($data["reply_type"] == 1) {
				$img["info"] = trim($_POST["reply_content"]);
			}

			$img["pic"] = trim($_POST["reply_pic"]);
			$img["showpic"] = 1;
			$img["uid"] = session("uid");
			$img["uname"] = session("uname");

			if (strpos($img["pic"], "http") === false) {
				$img["pic"] = $this->siteUrl . $img["pic"];
			}

			if ($data["id"] == "") {
				$lastid = M("Img")->where(array("token" => $this->token))->order("usort DESC")->limit(1)->getField("usort");
				$img["usort"] = $lastid + 1;
				$img["is_focus"] = 0;
				$img["createtime"] = $_SERVER["REQUEST_TIME"];
				$img["updatetime"] = 0;
				$img["token"] = $this->token;
				$addImgId = M("img")->add($img);

				if ($addImgId) {
					if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
						$data["relevance_id"] = $addImgId;
						$data["add_time"] = $_SERVER["REQUEST_TIME"];
						$data["update_time"] = 0;
						$data["token"] = $this->token;
						$insert = M("subscribe_reply")->add($data);
					}
					else {
						if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
							foreach ($data["start_time"] as $kk => $vv ) {
								if (($vv != "") && ($data["end_time"][$kk] != "")) {
									$dataAll[$kk]["start_time"] = $vv;
									$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
									$dataAll[$kk]["reply_type"] = $data["reply_type"];
									$dataAll[$kk]["r_type"] = $data["r_type"];
									$dataAll[$kk]["relevance_id"] = $addImgId;
									$dataAll[$kk]["title"] = $img["title"];
									$dataAll[$kk]["token"] = $this->token;
									$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
									$dataAll[$kk]["update_time"] = 0;
								}
							}

							$insert = M("subscribe_reply")->addAll($dataAll);
						}
					}

					if ($insert) {
						if ($data["original_id"] != "") {
							M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
						}

						$this->success("单图文消息添加成功", U("Areply/advanceindex", array("token" => $this->token)));
						exit();
					}
					else {
						$this->error("单图文消息添加失败");
					}
				}
				else {
					$this->error("单图文回复添加失败");
				}
			}
			else {
				$data["update_time"] = $_SERVER["REQUEST_TIME"];
				$img["updatetime"] = $_SERVER["REQUEST_TIME"];
				$updateImg = M("img")->where(array("id" => (int) $_POST["relevance_id"]))->save($img);
				$updateReply = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);
				if (($updateImg !== false) && ($updateReply !== false)) {
					$this->success("单图文编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("单图文编辑失败");
				}
			}
		}
	}

	private function AddImgAction($data)
	{
		if (trim($_POST["img_title_action"]) == "") {
			$this->error("回复标题不能为空");
		}

		if (trim($_POST["reply_pic_action"]) == "") {
			$this->error("回复图片不能为空");
		}

		if (trim($_POST["jump_url"]) == "") {
			$this->error("图文外链地址不能为空");
		}

		$data["title"] = trim($_POST["img_title_action"]);
		$img = array();
		$img["title"] = trim($_POST["img_title_action"]);
		$img["text"] = trim($_POST["reply_desc_action"]);

		if ($data["reply_type"] == 1) {
			$img["info"] = trim($_POST["reply_content_action"]);
		}

		$img["pic"] = trim($_POST["reply_pic_action"]);
		$img["showpic"] = 1;
		$img["uid"] = session("uid");
		$img["uname"] = session("uname");

		if (strpos($img["pic"], "http") === false) {
			$img["pic"] = $this->siteUrl . $img["pic"];
		}

		$img["url"] = trim($_POST["jump_url"]);

		if (strpos($img["url"], "{wechat_id}") !== false) {
			$img["url"] = str_replace("{wechat_id}", "", $img["url"]);
		}

		if (strpos($img["url"], "{siteUrl}") !== false) {
			$img["url"] = str_replace("{siteUrl}", $this->siteUrl, $img["url"]);
		}

		if (strpos($img["url"], "{changjingUrl}") !== false) {
			$img["url"] = str_replace("{changjingUrl}", "http://www.meihua.com", $img["url"]);
		}

		$url = str_replace(array("{siteUrl}", "{changjingUrl}"), $this->siteUrl, $img["url"]);
		$img["url"] = htmlspecialchars_decode($url);

		if ($data["id"] == "") {
			$lastid = M("Img")->where(array("token" => $this->token))->order("usort DESC")->limit(1)->getField("usort");
			$img["usort"] = $lastid + 1;
			$img["is_focus"] = 0;
			$img["createtime"] = $_SERVER["REQUEST_TIME"];
			$img["updatetime"] = 0;
			$img["token"] = $this->token;
			$addImgId = M("img")->add($img);

			if ($addImgId) {
				if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
					$data["relevance_id"] = $addImgId;
					$data["add_time"] = $_SERVER["REQUEST_TIME"];
					$data["update_time"] = 0;
					$data["token"] = $this->token;
					$insert = M("subscribe_reply")->add($data);
				}
				else {
					if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
						foreach ($data["start_time"] as $kk => $vv ) {
							if (($vv != "") && ($data["end_time"][$kk] != "")) {
								$dataAll[$kk]["start_time"] = $vv;
								$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
								$dataAll[$kk]["reply_type"] = $data["reply_type"];
								$dataAll[$kk]["r_type"] = $data["r_type"];
								$dataAll[$kk]["relevance_id"] = $addImgId;
								$dataAll[$kk]["title"] = $img["title"];
								$dataAll[$kk]["token"] = $this->token;
								$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
								$dataAll[$kk]["update_time"] = 0;
							}
						}

						$insert = M("subscribe_reply")->addAll($dataAll);
					}
				}

				if ($insert) {
					if ($data["original_id"] != "") {
						M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
					}

					$this->success("外链图文消息添加成功", U("Areply/advanceindex", array("token" => $this->token)));
					exit();
				}
				else {
					$this->error("外链图文消息添加失败");
				}
			}
			else {
				$this->error("外链图文回复添加失败");
			}
		}
		else {
			$data["update_time"] = $_SERVER["REQUEST_TIME"];
			$img["updatetime"] = $_SERVER["REQUEST_TIME"];
			$updateImg = M("img")->where(array("id" => (int) $_POST["relevance_id"]))->save($img);
			$updateReply = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);
			if (($updateImg !== false) && ($updateReply !== false)) {
				$this->success("外链图文消息编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("外链图文消息编辑失败");
			}
		}
	}

	private function AddmultiimgReply($data)
	{
		if (trim($_POST["imgids"]) == "") {
			$this->error("请先选择单图文");
		}

		$data["relevance_id"] = trim($_POST["imgids"], ",") . ",";
		$data["title"] = trim($_POST["imgtitle"], ",");

		if ($data["id"] == "") {
			if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
				$data["add_time"] = $_SERVER["REQUEST_TIME"];
				$data["update_time"] = 0;
				$data["token"] = $this->token;
				$insert = M("subscribe_reply")->add($data);
			}
			else {
				if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
					foreach ($data["start_time"] as $kk => $vv ) {
						if (($vv != "") && ($data["end_time"][$kk] != "")) {
							$dataAll[$kk]["start_time"] = $vv;
							$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
							$dataAll[$kk]["reply_type"] = $data["reply_type"];
							$dataAll[$kk]["r_type"] = $data["r_type"];
							$dataAll[$kk]["relevance_id"] = $data["relevance_id"];
							$dataAll[$kk]["title"] = "";
							$dataAll[$kk]["token"] = $this->token;
							$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
							$dataAll[$kk]["update_time"] = 0;
						}
					}

					$insert = M("subscribe_reply")->addAll($dataAll);
				}
				else {
					$this->error("多图文回复添加失败");
				}
			}

			if ($insert) {
				if ($data["original_id"] != "") {
					M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
				}

				$this->success("多图文添加成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("多图文添加失败");
			}
		}
		else {
			$data["update_time"] = $_SERVER["REQUEST_TIME"];
			$update = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);

			if ($update) {
				$this->success("多图文编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("多图文编辑失败");
			}
		}
	}

	public function AddcardReply($data)
	{
		if ($_POST["relevance_id_card"] == "") {
			$this->error("请选择卡券");
		}

		if (((int) $_POST["isrepeat_card"] == 1) && ($_POST["times_card"] == "")) {
			$this->error("每天领取次数不能为空");
		}

		$data["relevance_id"] = (int) $_POST["relevance_id_card"];
		$data["relevance_name"] = (string) $_POST["relevance_name_card"];
		$data["isrepeat"] = (int) $_POST["isrepeat_card"];
		$data["times"] = (int) $_POST["times_card"];

		if ($data["id"] == "") {
			if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
				$data["token"] = $this->token;
				$data["add_time"] = $_SERVER["REQUEST_TIME"];
				$data["update_time"] = 0;
				$insert = M("subscribe_reply")->add($data);
			}
			else {
				if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
					foreach ($data["start_time"] as $kk => $vv ) {
						if (($vv != "") && ($data["end_time"][$kk] != "")) {
							$dataAll[$kk]["start_time"] = $vv;
							$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
							$dataAll[$kk]["reply_type"] = $data["reply_type"];
							$dataAll[$kk]["r_type"] = $data["r_type"];
							$dataAll[$kk]["relevance_id"] = $data["relevance_id"];
							$dataAll[$kk]["relevance_name"] = $data["relevance_name"];
							$dataAll[$kk]["title"] = "";
							$dataAll[$kk]["token"] = $this->token;
							$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
							$dataAll[$kk]["update_time"] = 0;
						}
					}

					$insert = M("subscribe_reply")->addAll($dataAll);
				}
				else {
					$this->error("回复卡券添加失败");
				}
			}

			if ($insert) {
				if ($data["original_id"] != "") {
					M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
				}

				$this->success("回复卡券添加成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("回复卡券添加失败");
			}
		}
		else {
			$data["update_time"] = $_SERVER["REQUEST_TIME"];
			$update = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);

			if ($update) {
				$this->success("回复卡券编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("回复卡券编辑失败");
			}
		}
	}

	public function AddhbReply($data)
	{
		if ($data["reply_type"] == 4) {
			if ($_POST["relevance_id_hongbao_pt"] == "") {
				$this->error("请选择普通红包");
			}

			if (((int) $_POST["isrepeat_pt"] == 1) && ($_POST["times_pt"] == "")) {
				$this->error("每天领取次数不能为空");
			}

			$data["relevance_id"] = (int) $_POST["relevance_id_hongbao_pt"];
			$data["relevance_name"] = (string) $_POST["relevance_name_hongbao_pt"];
			$data["isrepeat"] = (int) $_POST["isrepeat_pt"];
			$data["times"] = (int) $_POST["times_pt"];
		}
		else if ($data["reply_type"] == 6) {
			if ($_POST["relevance_id_hongbao_lb"] == "") {
				$this->error("请选择普通红包");
			}

			if (((int) $_POST["isrepeat_lb"] == 1) && ($_POST["times_lb"] == "")) {
				$this->error("每天领取次数不能为空");
			}

			$data["relevance_id"] = (int) $_POST["relevance_id_hongbao_lb"];
			$data["relevance_name"] = (string) $_POST["relevance_name_hongbao_lb"];
			$data["isrepeat"] = (int) $_POST["isrepeat_lb"];
			$data["times"] = (int) $_POST["times_lb"];
		}

		if ($data["id"] == "") {
			if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
				$data["token"] = $this->token;
				$data["add_time"] = $_SERVER["REQUEST_TIME"];
				$data["update_time"] = 0;
				$insert = M("subscribe_reply")->add($data);
			}
			else {
				if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
					foreach ($data["start_time"] as $kk => $vv ) {
						if (($vv != "") && ($data["end_time"][$kk] != "")) {
							$dataAll[$kk]["start_time"] = $vv;
							$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
							$dataAll[$kk]["reply_type"] = $data["reply_type"];
							$dataAll[$kk]["r_type"] = $data["r_type"];
							$dataAll[$kk]["relevance_id"] = $data["relevance_id"];
							$dataAll[$kk]["relevance_name"] = $data["relevance_name"];
							$dataAll[$kk]["title"] = "";
							$dataAll[$kk]["token"] = $this->token;
							$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
							$dataAll[$kk]["update_time"] = 0;
						}
					}

					$insert = M("subscribe_reply")->addAll($dataAll);
				}
				else {
					$this->error("回复红包添加失败");
				}
			}

			if ($insert) {
				if ($data["original_id"] != "") {
					M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
				}

				$this->success("回复红包添加成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("回复红包添加失败");
			}
		}
		else {
			$data["update_time"] = $_SERVER["REQUEST_TIME"];
			$update = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);

			if ($update) {
				$this->success("回复红包编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("回复红包编辑失败");
			}
		}
	}

	private function AddtitleReply($data)
	{
		if (trim($_POST["text_title"]) == "") {
			$this->error("文本回复时,回复标题不能为空");
		}

		$data["title"] = preg_replace("/(\r\n)|(\r)|(\n)/", "\n", $_POST["text_title"]);
		$data["add_time"] = $_SERVER["REQUEST_TIME"];

		if ($data["id"] == "") {
			if ((count($data["start_time"]) == 1) && (count($data["end_time"]) == 1)) {
				$data["token"] = $this->token;
				$data["add_time"] = $_SERVER["REQUEST_TIME"];
				$data["update_time"] = 0;
				$insert = M("subscribe_reply")->add($data);
			}
			else {
				if ((1 < count($data["start_time"])) && (1 < count($data["end_time"]))) {
					foreach ($data["start_time"] as $kk => $vv ) {
						if (($vv != "") && ($data["end_time"][$kk] != "")) {
							$dataAll[$kk]["start_time"] = $vv;
							$dataAll[$kk]["end_time"] = $data["end_time"][$kk];
							$dataAll[$kk]["reply_type"] = $data["reply_type"];
							$dataAll[$kk]["r_type"] = $data["r_type"];
							$dataAll[$kk]["relevance_id"] = "";
							$dataAll[$kk]["relevance_name"] = "";
							$dataAll[$kk]["title"] = $data["title"];
							$dataAll[$kk]["token"] = $this->token;
							$dataAll[$kk]["add_time"] = $_SERVER["REQUEST_TIME"];
							$dataAll[$kk]["update_time"] = 0;
						}
					}

					$insert = M("subscribe_reply")->addAll($dataAll);
				}
				else {
					$this->error("回复红包添加失败");
				}
			}

			if ($insert) {
				if ($data["original_id"] != "") {
					M("subscribe_reply")->where(array("id" => $data["original_id"]))->delete();
				}

				$this->success("文本回复添加成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("文本回复添加失败");
			}
		}
		else {
			$data["update_time"] = $_SERVER["REQUEST_TIME"];
			$update = M("subscribe_reply")->where(array("id" => $data["id"]))->save($data);

			if ($update) {
				$this->success("文本回复编辑成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
			else {
				$this->error("文本回复编辑失败");
			}
		}
	}

	public function select_hongbao()
	{
		C("TOKEN_ON", false);
		$name = $this->_request("name", "trim");
		$where = array("token" => $this->token, "send_type" => 4, "hb_type" => (int) $_GET["hb_type"]);

		if ($name) {
			$where["act_name"] = array("like", "%" . $name . "%");
		}

		$count = M("directhongbao")->where($where)->count();
		$page = new Page($count, 10);
		$list = M("directhongbao")->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("hblist", $list);
		$this->assign("name", $name);
		$this->assign("hb_type", (int) $_GET["hb_type"]);
		$this->assign("page", $page->show());
		$this->display();
	}

	public function select_multiReply()
	{
		C("TOKEN_ON", false);
		$name = $this->_request("name", "trim");
		$where = array("token" => $this->token);

		if ($name) {
			$where["title"] = array("like", "%" . $name . "%");
		}

		$count = M("Img")->where($where)->count();
		$page = new Page($count, 10);
		$list = M("img")->where($where)->order("usort desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("list", $list);
		$this->assign("page", $page->show());
		$this->assign("token", $this->token);
		$this->assign("reply_type", (int) $_GET["reply_type"]);
		$this->display();
	}

	public function select_card()
	{
		C("TOKEN_ON", false);
		$name = $this->_request("name", "trim");
		$where = array(
			"token"     => $this->token,
			"is_weixin" => 1,
			"is_check"  => 1,
			"is_delete" => 0,
			"total"     => array("gt", 0)
			);

		if ($name) {
			$where["title"] = array("like", "%" . $name . "%");
		}

		$count = M("member_card_coupon")->where($where)->count();
		$page = new Page($count, 10);
		$list = M("member_card_coupon")->where($where)->order("id desc")->limit($page->firstRow . "," . $page->listRows)->select();
		$this->assign("cardlist", $list);
		$this->assign("name", $name);
		$this->assign("page", $page->show());
		$this->display();
	}

	public function del()
	{
		$id = (int) $_GET["id"];
		$exists = M("subscribe_reply")->where(array("id" => $id))->find();

		if (!empty($exists)) {
			$del = M("subscribe_reply")->where(array("id" => $id))->delete();

			if ($del) {
				$this->success("删除成功", U("Areply/advanceindex", array("token" => $this->token)));
				exit();
			}
		}
		else {
			$this->error("不存在该删除项");
			exit();
		}
	}

	public function Timevalid()
	{
		if ($_POST["start_time"] == "") {
			exit("start_time_error");
		}

		if ($_POST["end_time"] == "") {
			exit("end_time_error");
		}

		$type = (string) $_POST["type"];
		$id = (int) $_POST["id"];
		list($start_hour, $start_minute) = explode(":", $_POST["start_time"]);
		list($end_hour, $end_minute) = explode(":", $_POST["end_time"]);
		$start_timestamp = mktime($start_hour, $start_minute, 0, date("m"), date("d"), date("y"));
		$end_timestamp = mktime($end_hour, $end_minute, 0, date("m"), date("d"), date("y"));

		if ($end_timestamp <= $start_timestamp) {
			exit("start_gt_end");
		}

		if (!empty($_POST["start_time_arr"]) && !empty($_POST["end_time_arr"])) {
			$start_time_arr = explode(",", $_POST["start_time_arr"]);
			$end_time_arr = explode(",", $_POST["end_time_arr"]);

			foreach ($start_time_arr as $k => $v ) {
				list($start_hour_pre, $start_minute_pre) = explode(":", $v);
				list($end_hour_pre, $end_minute_pre) = explode(":", $end_time_arr[$k]);
				$start_timestamp_pre = mktime($start_hour_pre, $start_minute_pre, 0, date("m"), date("d"), date("y"));
				$end_timestamp_pre = mktime($end_hour_pre, $end_minute_pre, 0, date("m"), date("d"), date("y"));
				if ((($start_timestamp_pre <= $start_timestamp) && ($start_timestamp < $end_timestamp_pre)) || (($start_timestamp_pre < $end_timestamp) && ($end_timestamp <= $end_timestamp_pre)) || (($start_timestamp < $start_timestamp_pre) && ($end_timestamp_pre < $end_timestamp))) {
					exit("time_post_fail");
				}
			}
		}

		$data = array();
		$last_data = M("subscribe_reply")->where(array("token" => $this->token))->field("start_time,end_time")->select();

		if (!empty($last_data)) {
			if (($id != "") && ($type == "edit")) {
				$update_data = M("subscribe_reply")->where(array("id" => $id))->find();
				if (($update_data["start_time"] != $_POST["start_time"]) || ($update_data["end_time"] != $_POST["end_time"])) {
					foreach ($last_data as $key => $value ) {
						if (($value["start_time"] != $update_data["start_time"]) && ($value["end_time"] != $update_data["end_time"])) {
							list($vs_hour, $vs_minute) = explode(":", $value["start_time"]);
							list($ve_hour, $ve_minute) = explode(":", $value["end_time"]);
							$vs_timestamp = mktime($vs_hour, $vs_minute, 0, date("m"), date("d"), date("y"));
							$ve_timestamp = mktime($ve_hour, $ve_minute, 0, date("m"), date("d"), date("y"));
							if ((($vs_timestamp <= $start_timestamp) && ($start_timestamp < $ve_timestamp)) || (($vs_timestamp < $end_timestamp) && ($end_timestamp <= $ve_timestamp)) || (($start_timestamp < $vs_timestamp) && ($ve_timestamp < $end_timestamp))) {
								exit("time_fail");
							}
							else {
								continue;
							}
						}
					}
				}
			}
			else {
				foreach ($last_data as $key => $value ) {
					list($vs_hour, $vs_minute) = explode(":", $value["start_time"]);
					list($ve_hour, $ve_minute) = explode(":", $value["end_time"]);
					$vs_timestamp = mktime($vs_hour, $vs_minute, 0, date("m"), date("d"), date("y"));
					$ve_timestamp = mktime($ve_hour, $ve_minute, 0, date("m"), date("d"), date("y"));
					if ((($vs_timestamp <= $start_timestamp) && ($start_timestamp < $ve_timestamp)) || (($vs_timestamp < $end_timestamp) && ($end_timestamp <= $ve_timestamp)) || (($start_timestamp < $vs_timestamp) && ($ve_timestamp < $end_timestamp))) {
						exit("time_fail");
					}
					else {
						continue;
					}
				}
			}
		}
		else {
			exit("done");
		}
	}

	public function multititle()
	{
		$ids = trim($_GET["ids"], ",");
		$imgs = M("img")->where(array(
	"id" => array("in", $ids)
	))->field("id,title,pic")->select();
		$this->assign("list", $imgs);
		$this->display();
	}

	public function test1()
	{
		$img_list = M("subscribe_reply")->where(array(
	C("DB_PREFIX") . "subscribe_reply.id" => array(
		"in",
		array(3, 2, 1)
		)
	))->join(C("DB_PREFIX") . "subscribe_img on " . C("DB_PREFIX") . "subscribe_reply.id = " . C("DB_PREFIX") . "subscribe_img.rid")->field("title,reply_content,reply_pic,jump_url")->select();
		$multi = array();

		foreach ($img_list as $key => $value ) {
			array_push($multi, $value);
		}

		$data = array(
			array($multi),
			"news"
			);
	}

	public function test()
	{
		$subscribe = new subscribe($this->token, "BOBO", $data, $this->siteUrl, $xml);
		$reply = $subscribe->advanceReply();
		dump($reply);
	}
}
?>