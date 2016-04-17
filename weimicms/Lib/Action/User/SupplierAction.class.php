<?php
class SupplierAction extends UserAction
{
	public function _initialize() 
	{
		parent::_initialize();
	}
	public function project()
	{
		$Data = M('Supplier_project');
		$where['token'] = session('token');
		$count = $Data->where($where)->count();
		$page = new Page($count,25);
		$info = $Data->where($where)->order('projectid')->limit($page->firstRow.','.$page->listRows)->select();
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
	}
	public function projectadd()
	{
		if(IS_POST)
		{
			$data['projectname'] = strip_tags($_POST['projectname']);
			$data['publishTime'] = $_POST['publishTime'];
			$data['token'] = session('token');
			$data['address'] = $_POST['address'];
			$data['sort']= $_POST['sort'];
			$data['faceImg']= $_POST['faceImg'];
			$data['tel']= $_POST['tel'];
			$data['lng']= $_POST['lng'];
			$data['lat']= $_POST['lat'];
			$data['traffic_desc']= htmlspecialchars_decode($_POST['traffic_desc']);
			$data['project_brief']= htmlspecialchars_decode($_POST['project_brief']);
			$data['estate_desc']= htmlspecialchars_decode($_POST['estate_desc']);
			$data['video']= $_POST['video'];
			$data['photo_id']= $_POST['photo_id'];
			$data['lp_banner']= $_POST['lp_banner'];
			$data['hx_banner']= $_POST['hx_banner'];
			$data['projuctprice']= $_POST['projuctprice'];
			$insert = M('Supplier_project')->add($data);
			if($insert)
			{
				$this->success('房产项目添加成功!',U('Supplier/project'));
			}
			else
			{
				$this->error('房产项目活添加失败!');
			}
		}
		$po_data=M('Photo');
		$list = $po_data->where(array('token'=>session('token')))->field('id,title')->select();
		$this->assign('photo',$list);
		$this->display();
	}
	public function projectedit()
	{
		$id = $this->_get('id');
		$token = $this->_get('token');
		$where['projectid'] = $id;
		$where['token'] = $token;
		if(IS_POST)
		{
			$data['projectname'] = strip_tags($_POST['projectname']);
			$data['publishTime'] = $_POST['publishTime'];
			$data['token'] = session('token');
			$data['address'] = $_POST['address'];
			$data['sort']= $_POST['sort'];
			$data['faceImg']= $_POST['faceImg'];
			$data['tel']= $_POST['tel'];
			$data['lng']= $_POST['lng'];
			$data['lat']= $_POST['lat'];
			$data['traffic_desc']= htmlspecialchars_decode($_POST['traffic_desc']);
			$data['project_brief']= htmlspecialchars_decode($_POST['project_brief']);
			$data['estate_desc']= htmlspecialchars_decode($_POST['estate_desc']);
			$data['video']= $_POST['video'];
			$data['photo_id']= $_POST['photo_id'];
			$data['lp_banner']= $_POST['lp_banner'];
			$data['hx_banner']= $_POST['hx_banner'];
			$data['projuctprice']= $_POST['projuctprice'];
			$up = M('Supplier_project')->where($where)->save($data);
			if($up)
			{
				$this->success('房产项目更新成功!',U('Supplier/project'));
			}
			else
			{
				$this->error('房产项目更新失败!');
			}
		}
		else
		{
			$info = M('SupplierProject')->where($where)->find();
			$po_data=M('Photo');
			$list = $po_data->where(array('token'=>session('token')))->field('id,title')->select();
			$this->assign('photo',$list);
			$this->assign('info', $info);
			$this->display();
		}
	}
	public function customroll()
	{
		$token=session('token');
		$Data = M('supplier_customroll');
		if(IS_POST && $_POST['search'] != '')
		{
			$search = trim($this->_post('search'));
			$where = "token = '$token' and custom like '%$search%'";
		}
		else
		{
			$where['token'] = session('token');
		}
		$count = $Data->count();
		$page = new Page($count,25);
		$info = $Data->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
	}
	public function trackrecord()
	{
		$Data = M('supplier_trackrecord');
		$where['token'] = session('token');
		$count = $Data->count();
		$page = new Page($count,25);
		$info = $Data->where($where)->order('id')->limit($page->firstRow.','.$page->listRows)->select();
		$this->info = $info;
		$this->page = $page->show();
		$this->display();
	}
	public function extendprice()
	{
		$id = $this->_get('id');
		$pjid = $this->_get('pjid');
		$custom = $this->_get('custom');
		$salerid = $this->_get('salerid');
		$status = $this->_post('status');
		$info = M('SupplierProject')->where("Projectid='".$pjid."' and token='".session('token')."'")->find();
		$userinfo = M('SupplierUser')->where("id='".$salerid."' and token='".session('token')."'")->find();
		if(IS_POST) 
		{
			$where = array( 'id' => $id );
			if($status=="1") 
			{
				$data1['creatTime1']=time();
			}
			elseif( $status=="2") 
			{
				$data1['creatTime2']=time();
			}
			elseif($status=="3") 
			{
				$data1['creatTime3']=time();
			}
			elseif($status=="4") 
			{
				$data1['creatTime4']=time();
			}
			elseif($status=="5") 
			{
				$data1['creatTime5']=time();
			}
			elseif($status=="6") 
			{
				$data1['creatTime6']=time();
			}
			$data1['status']=$status;
			$getdata = M('supplier_customroll')->where($where)->save($data1);
			if($getdata)
			{
				$data['custom']=$custom;
				$data['note']=$_POST['note'];
				$data['supplierid']=$salerid;
				$data['projectid']=$pjid;
				$data['checktime']=time();
				$data['statue']=$status;
				$data['projectname']=$info['projectname'];
				$data['suppliername']=$userinfo['username'];
				$data['customrollid']=$id;
				$data['creatTime']=time();
				$data['token']=session('token');
				$insert = M('SupplierTrackrecord')->add($data);
				if($insert)
				{
					$this->success('修改成功!',U('Supplier/customroll'));
				}
			}
			else
			{
				$this->error('修改失败!');
			}
		}
		$custominfo = M('supplier_customroll')->where("id='".$id."' and token='".session('token')."'")->find();
		$this->assign('custominfo',$custominfo);
		$this->display();
	}
	public function checkbonus()
	{
		$id = $this->_get('id');
		$pjid = $this->_get('pjid');
		$custom = $this->_get('custom');
		$statue = $this->_get('statue');
		$supplierid = $this->_get('supplierid');
		$suppliername = $this->_get('suppliername');
		$customrollid = $this->_get('customrollid');
		$checkstatus = $this->_post('checkstatus');
		$note = $this->_post('note');
		$Projectinfo = M('SupplierProject')->where("Projectid='".$pjid."'  and token='".session('token')."'")->find();
		$priceinfo = M('SupplierPrice')->where("projectid='".$pjid."' and token='".session('token')."'")->find();
		if($priceinfo) 
		{
			if(IS_POST) 
			{
				$where = array( 'id' => $id );
				$getdata = M('supplier_trackrecord')->where($where)->save(array( 'checkstatus' => $checkstatus ));
				$data['projectid']=$pjid;
				$data['userid']=$supplierid;
				$data['trackrecordid']=$id;
				if($statue=="1")
				{
					$data['bonusamount']=$priceinfo['dengji'];
				}
				elseif($statue=="2") 
				{
					$data['bonusamount']=$priceinfo['daofang'];
				}
				elseif($statue=="3") 
				{
					$data['bonusamount']=$priceinfo['renchou'];
				}
				elseif($statue=="4") 
				{
					$data['bonusamount']=$priceinfo['rengou'];
				}
				elseif($statue=="5") 
				{
					$data['bonusamount']=$priceinfo['qianyue'];
				}
				elseif($statue=="6") 
				{
					$data['bonusamount']=$priceinfo['fukuan'];
				}
				$data['status']=$statue;
				$data['username']=$suppliername;
				$data['custom']=$custom;
				$data['customid']=$customrollid;
				$data['username']=$suppliername;
				$data['checktime']=time();
				$data['note']=$note;
				$data['addtime']=time();
				$data['checkstatus']=$checkstatus;
				$data['token']=session('token');
				$data['projectname']=$Projectinfo['projectname'];
				$insert = M('SupplierBonus')->add($data);
				if($insert)
				{
					$price = M('SupplierBonus')->where("id='".$insert."' and token='".session('token')."'")->find();
					$usertoal = M('SupplierUser')->where("id='".$supplierid."'")->find();
					$toal= (floatval($price['bonusamount'])+floatval($usertoal['money']));
					if($statue=="6") 
					{
						M('SupplierUser')->where('id="'.$supplierid.'" and token="'.session('token').'"')->setInc("totalNum");
					}
					$up = M('SupplierUser')->where('id="'.$supplierid.'" and token="'.session('token').'"')->save(array('money'=>$toal));
					if($up)
					{
						$this->success('确认成功!',U('Supplier/bonus'));
					}
				}
				else
				{
					$this->error('确认失败!');
				}
			}
		}
		else 
		{
			$this->error('房产佣金未设置，请联系相关管理员!',U('Supplier/trackrecord'));
		}
		$this->display();
	}
	public function setprice() 
	{
		$id= $this->_get('id');
		$pjname=$this->_get('pjname');
		$where['projectid'] = $id;
		$where['token'] = session('token');
		$Projectinfo = M('SupplierPrice')->where("Projectid='".$id."' and token='".session('token')."'")->find();
		if(IS_POST) 
		{
			$data['dengji']=$_POST['dengji'];
			$data['daofang']=$_POST['daofang'];
			$data['renchou']=$_POST['renchou'];
			$data['rengou']=$_POST['rengou'];
			$data['qianyue']=$_POST['qianyue'];
			$data['fukuan']=$_POST['fukuan'];
			$data['projectid']=$id;
			$data['projectname']=$pjname;
			$data['token'] = session('token');
			if($Projectinfo) 
			{
				$insert = M('SupplierPrice')->where($where)->save($data);
			}
			else 
			{
				$insert = M('SupplierPrice')->where($where)->add($data);
			}
			if($insert)
			{
				$this->success('设置成功!',U('Supplier/project'));
			}
			else
			{
				$this->error('设置失败!',U('Supplier/project'));
			}
		}
		else
		{
			$info = M('SupplierPrice')->where($where)->find();
			$this->assign('info', $info);
		}
		$this->display();
	}
	public function delete()
	{
		$where['projectid'] = $this->_get('projectid');
		$where['token'] = session('token');
		$isdel=M('Supplier_customroll')->where(array('projectid'=>$this->_get('projectid'),'token'=>session('token')))->find();
		if(empty($isdel)) 
		{
			$info = M('Supplier_project')->where($where)->delete();
			if($info)
			{
				$this->success('房产项目删除成功!');
			}
			else
			{
				$this->error('房产项目删除失败!');
			}
		}
		else 
		{
			$this->error('该项目已经有推荐客户不能删除！',U('Supplier/project?token='.$_GET['token'].''));
		}
	}
	public function user()
	{
		$token = $this->_get("token");
		if(IS_POST && $_POST['search'] != '')
		{
			$search = trim($this->_post('search'));
			$where = "token = '".$token."' and username like '%".$search."%'";
		}
		else
		{
			$where = "token = '".$token."'";
		}
		$count = M('SupplierUser')->where("typeid=1 and ".$where)->count();
		$Page = new Page($count,20);
		$show = $Page->show();
		$data = M('SupplierUser')->where("typeid=1 and ".$where)->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}
	public function counselor()
	{
		$where="typeid=2 and token='".$this->_get("token")."'";
		$count = M('SupplierUser')->where($where)->count();
		$Page = new Page($count,10);
		$show = $Page->show();
		$data = M('SupplierUser')->where($where)->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}
	public function counseloradd()
	{
		if(IS_POST)
		{
			$data['username'] = $_POST['username'];
			$data['name']=$_POST['name'];
			$data['phone'] = $_POST['phone'];
			$data['Uid']= $_POST['uid'];
			$data['token']= session('token');
			$data['zhucheshijian'] = time();
			$data['typeid'] = 2;
			$data['status'] = 1;
			$insert = M('SupplierUser')->add($data);
			if($insert)
			{
				$this->success('添加成功!',U('Supplier/counselor'));
			}
			else
			{
				$this->error('添加失败!');
			}
		}
		$this->display();
	}
	public function counseloredit()
	{
		$id = $this->_get('id');
		$token =$this->_get('token');
		$where["id"]=$id;
		if(IS_POST)
		{
			$data['username'] = $_POST['username'];
			$data['name']=$_POST['name'];
			$data['phone'] = $_POST['phone'];
			$data['Uid']= $_POST['uid'];
			$up = M('SupplierUser')->where(array('token'=>$token,'id'=>$id))->save($data);
			if($up)
			{
				$this->success('修改成功!',U('Supplier/counselor'));
			}
			else
			{
				$this->error('修改失败!');
			}
		}
		else
		{
			$info = M('SupplierUser')->where($where)->find();
			$this->assign('info',$info);
			$this->display();
		}
	}
	public function counselordel()
	{
		$memberData=M('SupplierUser');
		$thisMember=$memberData->where(array('id'=>intval($_GET['id']),'token'=>$_GET['token']))->delete();
		if($thisMember)
		{
			$this->success('操作成功！');
		}
		else 
		{
			$this->error('操作失败！');
		}
	}
	public function stauser() 
	{
		$id = intval($this->_get('id'));
		$sta= $this->_get('statue');
		$where = array( 'id' => $id );
		$data = M('SupplierUser')->where($where)->save(array( 'statue' => $sta ));
		if ($data != false) 
		{
			$this->success('更改成功！',U('Supplier/user'));
		}
		else 
		{
			$this->error('服务器繁忙,请稍候再试');
		}
	}
	public function bonus()
	{
		$where['token'] = session('token');
		$count = M('SupplierBonus')->where($where)->count();
		$Page = new Page($count,20);
		$show = $Page->show();
		$data = M('SupplierBonus')->where($where)->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}
	public function withdraw()
	{
		$where['token'] = session('token');
		$count = M('SupplierWithdraw')->where($where)->count();
		$Page = new Page($count,20);
		$show = $Page->show();
		$data = M('SupplierWithdraw')->where($where)->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}
	public function checkwithdraw() 
	{
		$id = $this->_get('id');
		$remarks=$this->_post('remarks');
		$sta= $this->_post('checkStatus');
		if(IS_POST)
		{
			$where['id'] = $id;
			$data['status'] = $sta;
			$data['remarks'] = $remarks;
			$data['checktime'] =time();
			$data['token'] = session('token');
			$up = M('SupplierWithdraw')->where($where)->save($data);
			if($up)
			{
				$this->success('更新成功!',U('Supplier/withdraw'));
			}
			else
			{
				$this->error('更新失败!');
			}
		}
		$this->display();
	}
	public function checkuser()
	{
		$where['checkStatue'] = array('neq',0);
		$where['token'] = session('token');
		$count = M('SupplierUser')->where($where)->count();
		$Page = new Page($count,20);
		$show = $Page->show();
		$data = M('SupplierUser')->where($where)->select();
		$this->assign('page',$show);
		$this->assign('data',$data);
		$this->display();
	}
	public function authen() 
	{
		$id = $this->_get('id');
		$getid=$this->_post('getid');
		$sta= $this->_post('checkStatue');
		if(IS_POST)
		{
			$where['id'] = $getid;
			$where['token'] = session('token');
			$data['checkStatue'] = $sta;
			$up = M('SupplierUser')->where($where)->save($data);
			if($up)
			{
				$this->success('更新成功!',U('Supplier/checkuser'));
			}
			else
			{
				$this->error('更新失败!');
			}
		}
		else
		{
			$userinfo = M('SupplierUser')->where("id='".$id."' and token='".session('token')."'")->find();
			$this->assign('userinfo',$userinfo);
		}
		$this->display();
	}
	public function member_del()
	{
		$memberData=M('SupplierUser');
		$isdel=M('Supplier_customroll')->where(array('salerid'=>intval($_GET['itemid']),'token'=>$_GET['token']))->find();
		if(empty($isdel)) 
		{
			$thisMember=$memberData->where(array('id'=>intval($_GET['itemid']),'token'=>$_GET['token']))->delete();
			if($thisMember)
			{
				$this->success('操作成功！');
			}
			else 
			{
				$this->error('操作失败！');
			}
		}
		else 
		{
			$this->error('该经纪人已经有推荐客户不能删除！',U('Supplier/user?token='.$_GET['token'].''));
		}
	}
	public function welcome()
	{
		$info = M('SupplierWelcome')->where("token='".$this->_get('token')."'" )->find();
		$this->assign('info',$info);
		if(IS_POST)
		{
			$arr['img1']=$this->_post('cover');
			$arr['img2']=$this->_post('banner');
			$arr['img3']=$this->_post('house_banner');
			if($info)
			{
				$where['id'] = $this->_post('id');
				$where['token'] = session('token');
				$result = M('SupplierWelcome')->where($where)->save($arr);
				if($result)
				{
					$this->success('操作成功!');
				}
				else
				{
					$this->error('服务器繁忙 操作失败!');
				}
			}
			else
			{
				$arr['token'] = $this->_get('token');
				$arr['sort'] = '0';
				$insert = M('SupplierWelcome')->add($arr);
				if($insert > 0)
				{
					$this->success('添加成功!');
				}
				else
				{
					$this->error('添加失败!');
				}
			}
		}
		else
		{
			$this->display();
		}
	}
	public function company()
	{
		$where['token'] = session('token');
		$Cdata = M('Supplier');
		$info = $Cdata->where($where)->find();
		$this->info = $info;
		if(IS_POST)
		{
			$where['token'] = session('token');
			$data['company'] = strip_tags($_POST['company']);
			$data['logo'] = strip_tags($_POST['logo']);
			$data['title'] = strip_tags($_POST['title']);
			$data['jianjie'] = strip_tags($_POST['jianjie']);
			$data['tp'] = strip_tags($_POST['tp']);
			$data['copyright'] = $_POST['copyright'];
			$data['regulation'] = $_POST['regulation'];
			$data['info'] = strip_tags($_POST['info']);
			$data['Iswelcome'] = $_POST['Iswelcome'];
			if($info)
			{
				$result = M('Supplier')->where($where)->save($data);
				if($result)
				{
					$this->success('回复信息更新成功!');
				}
				else
				{
					$this->error('服务器繁忙 更新失败!');
				}
			}
			else
			{
				$data['token'] = session('token');
				$insert = M('Supplier')->add($data);
				if($insert > 0)
				{
					$this->success('回复信息添加成功!');
				}
				else
				{
					$this->error('回复信息添加失败!');
				}
			}
		}
		else
		{
			$this->display();
		}
	}
} ?>