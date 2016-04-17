<?php
class StorenewAction extends UserAction{
	public $token;
	public $product_model;
	public $product_cat_model;
	
	public $_cid = 0;
	
	public function _initialize() 
	{
		parent::_initialize();
		$this->canUseFunction('shop');
		$this->server_url='http://v.012wz.com/api.php';
		$version = './weimicms/Lib/Action/User/Storenewversion.php';
        $ver = include($version);
        $release = include($version);
        $vername = include($version);
        $ver = $ver['ver'];    
		$this->_cid = session('companyid');
		if (empty($this->token)) {
			$this->error('不合法的操作', U('Index/index'));
		}
		if (empty($this->_cid))  {
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			if ($company) {
				$this->_cid = $company['id'];
				session('companyid', $this->_cid);
				session('companyk', md5($this->_cid . session('uname')));
				D("New_product_cat")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("Attribute")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("New_product")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("New_product_cart")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("New_product_cart_list")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("New_product_comment")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
				D("New_product_setting")->where(array('token' => $this->token, 'cid' => 0))->save(array('cid' => $this->_cid));
			} else {
				$this->error('您还没有添加您的商家信息',U('Company/index',array('token' => $this->token)));
			}
		} else {
			$k = session('companyk');
			$company = M('Company')->where(array('token' => $this->token, 'id' => $this->_cid))->find();
			if (empty($company)) {
				$this->error('非法操作', U('Storenew/index',array('token' => $this->token)));
			} else {
				$username = $company['isbranch'] ? $company['username'] : session('uname');
				if (md5($this->_cid . $username) != $k) {
					$this->error('非法操作', U('Storenew/index',array('token' => $this->token)));
				}
			}
		}
		
		//分支店铺
		$ischild = session('companyLogin');
		$this->assign('ischild', $ischild);
		$this->assign('cid', $this->_cid);
		$release = $release['release'];
        $vername = $vername['vername'];
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = $this -> server_url;
        $updatehosturl = $updatehost . '?a=client&v=' . $ver . '&u=' . $hosturl;
		$domain_time = file_get_contents($updatehosturl);
		$this->assign('isDining', 0);
		
		//商品分组权限
		$isgroup = 0;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$setting = M("New_product_setting")->where(array('token' => $this->token, 'cid' => $company['id']))->find();
			$isgroup = $setting['isgroup'];
		}
		$this->assign('isgroup', $isgroup);
		if ($ischild && $isgroup) {
			if (!in_array(ACTION_NAME, array('orders', 'orderInfo', 'deleteOrder', 'setting', 'comment', 'commentdel', 'flash', 'flashadd', 'flashdel'))) {
				$this->redirect(U('Storenew/orders',array('token' => $this->token)));
			}
		}
	}
	
	/**
	 * 分类列表
	 */
	public function index() 
	{
		$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
		$data = M('New_product_cat');
		$where = array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid);
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|des'] = array('like',"%$key%"); 
            $list = $data->where($map)->order("sort ASC, id DESC")->select(); 
            $count      = $data->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("sort ASC, id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		if ($parentid){
			$parentCat = $data->where(array('id'=>$parentid))->find();
		}
		
		$this->assign('parentCat',$parentCat);
		$this->assign('parentid',$parentid);
		$this->display();		
	}
	
	/**
	 * 创建分类
	 */
	public function catAdd()
	{
		$parentid = isset($_REQUEST['parentid']) ? intval($_REQUEST['parentid']) : 0;
		if ($parentid) {
			$checkdata = M('New_product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
			$this->assign('parentname', $checkdata['name']);
		}
		if (IS_POST) {
			if($_POST['pc_show']){
				$database_pc_product_category = D('Pc_product_category');
				$data_pc_product_category['cat_name'] = $_POST['name'];
				$data_pc_product_category['token'] = session('token');
				$_POST['pc_cat_id'] = $database_pc_product_category->data($data_pc_product_category)->add();
			}
			
			$_POST['isfinal'] = 0;
			$_POST['time'] = time();
			$_POST['token'] = session('token');
			if (D('New_product_cat')->add($_POST)) {
				D('New_product_cat')->where(array('id' => $_POST['parentid']))->save(array('isfinal' => 2));
				$this->success('修改成功', U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $parentid)));
			}
		} else {
			$parentid = isset($_GET['parentid']) ? intval($_GET['parentid']) : 0;
			if ($parentid) {
				$checkdata = M('New_product_cat')->where(array('id' => $parentid, 'cid' => $this->_cid))->find();
				$this->assign('parentname', $checkdata['name']);
			}
			$this->assign('parentid', $parentid);
			
			
			$queryname=M('token_open')->where(array('token'=>$this->token))->getField('queryname');
			if(strpos(strtolower($queryname),strtolower('website')) !== false){
				$this->assign('has_website',true);
			}
			
			$this->display('catSet');
		}
	}
	
	/**
	 * 分类修改
	 */
	public function catSet()
	{
        $id = $this->_get('id'); 
		$checkdata = M('New_product_cat')->where(array('id' => $id, 'cid' => $this->_cid))->find();
		if(empty($checkdata)){
            $this->error("没有相应记录.您现在可以添加.", U('Storenew/catAdd', array('token' => session('token'), 'cid' => $this->_cid)));
        }
		if (IS_POST) {
			if($_POST['pc_show']){
				$_POST['pc_cat_id'] = 0;
			}
            $data = D('New_product_cat');
			if ($data->create()) {
				if ($data->where($where)->save($_POST)) {
					$this->success('修改成功', U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid, 'parentid' => $this->_post('parentid'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$this->error($data->getError());
			}
		} else {
			if ($checkdata['parentid']) {
				$father = M('New_product_cat')->where(array('id' => $checkdata['parentid'], 'cid' => $this->_cid))->find();
				$this->assign('parentname', $father['name']);
			}
			$this->assign('parentid', $checkdata['parentid']);
			$this->assign('set', $checkdata);
			$this->display();	
		
		}
	}
	
	/**
	 * 删除分类
	 */
	public function catDel() 
	{
		if ($this->_get('token') != session('token')) {$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('id' => $id,'token' => session('token'),'cid' => $this->_cid);
            $data = M('New_product_cat');
            $check = $data->where($where)->find();
            if ($check == false) $this->error('非法操作');
            
            $product_model = M('New_product');
            $count = $product_model->where(array('catid' => $id))->count();
            if ($count){
            	$this->error('本分类下有商品，请删除商品后再删除分类', U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid)));
            	exit();
            }
            
            $catcount = $data->where(array('parentid' => $id))->count();
            if ($catcount){
            	$this->error('本分类下有子分类，请先删除子分类后再删除该分类', U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid)));
            	exit();
            }
            
            $back = $data->where($where)->delete();
            if ($back == true) {
				$this->success('操作成功', U('Storenew/index', array('token' => session('token'),'parentid' => $check['parentid'], 'cid' => $this->_cid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid)));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function norms() 
	{
		$type = isset($_GET['type']) ? intval($_GET['type']) : 0;
		$catid = intval($_GET['catid']);
		if($checkdata = M('New_product_cat')->where(array('id' => $catid, 'token' => session('token'), 'cid' => $this->_cid))->find()){
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid)));
        }
        
		$data = M('New_product_norms');
		$where = array('catid' => $catid, 'type' => $type);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->assign('type', $type);
		$this->display();		
	}
	
	/**
	 * 分类规格的操作
	 */
	public function normsAdd() 
	{
		$type = intval($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		if($data = M('New_product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token'), 'cid' => $this->_cid))->find()){
			$this->assign('catData', $data);
        } else {
        	$this->error("没有选择相应的分类.", U('Storenew/index', array('token' => session('token'), 'cid' => $this->_cid)));
        }
		if (IS_POST) { 
            $data = D('New_product_norms');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'type' => $type, 'catid' => $this->_get('catid'));
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Storenew/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Storenew/norms',array('token' => session('token'), 'catid' => $this->_post('catid'), 'type' => $type)));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('New_product_norms')->where(array('id' => $this->_get('id'), 'type' => $type, 'catid' => $this->_get('catid')))->find();
			//print_r($data);die;
			$this->assign('catid', $this->_get('catid'));
			$this->assign('type', $type);
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function normsDel() 
	{
		if ($this->_get('token') != session('token')) {$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        $type = intval($this->_get('type'));
        if (IS_GET) {                              
            $where = array('id' => $id, 'type' => $type, 'catid' => $catid);
            $data = M('New_product_norms');
            $check = $data->where($where)->find();
            if ($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功', U('Storenew/norms', array('type' => $type, 'catid' => $check['catid'])));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/norms', array('type' => $type, 'catid' => $check['catid'])));
            }
        }        
	}
	
	/**
	 * 分类属性列表
	 */
	public function attributes()
	{
		$catid = intval($_GET['catid']);
		if ($checkdata = M('New_product_cat')->where(array('id' => $catid, 'token' => session('token'), 'cid' => $this->_cid))->find()) {
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Storenew/index'));
        }
		$data = M('Attribute');
		$where = array('catid' => $catid, 'token' => session('token'), 'cid' => $this->_cid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $data->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page', $show);		
		$this->assign('list', $list);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 分类属性的操作
	 */
	public function attributeAdd()
	{
		if ($checkdata = M('New_product_cat')->where(array('id' => $this->_get('catid'), 'token' => session('token'), 'cid' => $this->_cid))->find()) {
			$this->assign('catData', $checkdata);
        } else {
        	$this->error("没有选择相应的分类.", U('Storenew/index'));
        }
		if (IS_POST) { 
            $data = D('Attribute');
            $id = intval($this->_post('id'));
            $catid = intval($this->_post('catid'));
            if ($id) {
	            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid, 'cid' => $this->_cid);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->where($where)->save($_POST)) {
						$this->success('修改成功', U('Storenew/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add($_POST)) {
						$this->success('添加成功', U('Storenew/attributes',array('token' => session('token'), 'catid' => $this->_post('catid'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else { 
			$data = M('Attribute')->where(array('id' => $this->_get('id'), 'token' => session('token'), 'cid' => $this->_cid, 'catid' => $this->_get('catid')))->find();
			$this->assign('catid', $this->_get('catid'));
			$this->assign('token', session('token'));
			$this->assign('set', $data);
			$this->display();	
		}
	}
	
	/**
	 *属性的删除 
	 */
	public function attributeDel()
	{
		if($this->_get('token') != session('token')){$this->error('非法操作');}
        $id = intval($this->_get('id'));
        $catid = intval($this->_get('catid'));
        if(IS_GET){                              
            $where = array('id' => $id, 'token' => session('token'), 'catid' => $catid, 'cid' => $this->_cid);
            $data = M('Attribute');
            $check = $data->where($where)->find();
            if($check == false) $this->error('非法操作');
            if ($back = $data->where($where)->delete()) {
            	$this->success('操作成功',U('Storenew/attributes', array('token' => session('token'), 'catid' => $catid)));
            } else {
				$this->error('服务器繁忙,请稍后再试',U('Storenew/attributes', array('token' => session('token'), 'catid' => $catid)));
            }
        }        
	}
	
	/**
	 * 商品列表
	 */
	public function product() 
	{		
		$catid = intval($_GET['catid']);
		$product_model = M('New_product');
		$product_cat_model = M('New_product_cat');
		$where = array('token' => session('token'), 'groupon' => 0, 'dining' => 0, 'cid' => $this->_cid);
		if ($catid){
			$where['catid'] = $catid;
		}
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key=>$val) {
				$cat = $product_cat_model->where(array('token' => $this->token, 'cid' => $this->_cid, 'id'=>$val['catid']))->find();
				$list[$key]['catname'] = $cat['name'];
			}
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 商品列表
	 */
	public function productgroup() 
	{		
		$gid = isset($_GET['gid']) ? intval($_GET['gid']) : 0;
		$product_model = M('New_product');
		$where = array('token' => session('token'), 'groupon' => 0, 'dining' => 0);
		if ($gid){
			$where['gid'] = $gid;
		}
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$group = M("New_product_group")->where(array('token' => $this->token))->select();
		$glist = array();
		foreach ($group as $g) {
			$glist[$g['id']] = $g;
		}
		
		$cat = M("New_product_cat")->where(array('token' => $this->token))->select();
		$catlist = array();
		foreach ($cat as $c) {
			$catlist[$c['id']] = $c;
		}
		
		foreach ($list as &$row) {
			$row['gname'] = isset($glist[$row['gid']]) ? $glist[$row['gid']]['name'] : '';
			$row['cname'] = isset($catlist[$row['catid']]) ? $catlist[$row['catid']]['name'] : '';
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	
	public function changegroup()
	{
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		if (IS_POST) {
			D('Product')->where(array('id' => $id, 'token' => $this->token))->save(array('gid' => $_POST['gid']));
			$this->success("修改成功", U('Storenew/productgroup', array('token' => session('token'))));
		} else {
			if ($id && ($product = M('New_product')->where(array('token' => session('token'), 'id' => $id))->find())) {
				$this->assign("set", $product);
	        
				$groups =  M('New_product_group')->where(array('token' => session('token')))->select();
				$this->assign('groups', $groups);
				$this->display();
			} else {
				$this->error("参数错误!");
			}
		}
	}
	
	/**
	 * 添加商品
	 */
	public function addNew() 
	{
		$catid = intval($_GET['catid']);
		$id = intval($_GET['id']);
		if($productCatData = M('New_product_cat')->where(array('id' => $catid, 'token' => session('token')))->find()){
			$this->assign('catData', $productCatData);
        } else {
        	$this->error("没有选择相应的分类.", U('Storenew/index'));
        }
		//商品类别
		$productcat = M('New_product_cat')->where(array('token' => session('token')))->select();
		$this->assign('cat', $productcat);
		
        //产品的规格
        $normsData = M('New_product_norms')->where(array('catid' => $catid))->select();
        $colorData = $formatData = array();
        foreach ($normsData as $row) {
        	if ($row['type']) {
        		$colorData[] = $row;
        	} else {
        		$formatData[] = $row;
        	}
        	$normsList[$row['id']] = $row['value'];
        }
        $attributeData = array();
        if ($id && ($product = M('New_product')->where(array('catid' => $catid, 'token' => session('token'), 'id' => $id))->find())) {
        	$attributeData = M("New_product_attribute")->where(array('pid' => $id))->select();
        	$productDetailData = M("New_product_detail")->where(array('pid' => $id))->select();
        	$productimage = M("New_product_image")->where(array('pid' => $id))->select();
        	$colorList = $formatList = $pData = array();
        	foreach ($productDetailData as $p) {
        		$p['formatName'] = $normsList[$p['format']];
        		$p['colorName'] = $normsList[$p['color']];
        		$formatList[] = $p['format'];
        		$colorList[] = $p['color'];
        		$pData[] = $p;
        	}
        	$this->assign('set', $product);
        	$this->assign('formatList', $formatList);
        	$this->assign('colorList', $colorList);
        	$this->assign('imageList', $productimage);
        	//print_r($productimage);die;
        }// else {
	        //分类产品的属性
//	        $data = M("Attribute")->where(array('catid' => $catid))->select();
//	        $temp = array();
//	        foreach ($data as $row) {
//	        	$row['aid'] = $row['id'];
//	        	$row['id'] = 0;
//	        	$temp[$row['id']] = $row;
//	        }
        //}
        $array = array();
        if ($attributeData) {
	        foreach ($attributeData as $row) {
	        	$array[$row['aid']] = $row;
	        }
        }
		$data = M("Attribute")->where(array('catid' => $catid))->select();
        $attributeData = array();
        foreach ($data as $row) {
        	if (isset($array[$row['id']])) {
        		$attributeData[] = $array[$row['id']];
        		unset($array[$row['id']]);
        	} else {
	        	$row['aid'] = $row['id'];
	        	$row['id'] = 0;
	        	$attributeData[] = $row;
        	}
        }
        if ($array) {
        	$ids = array();
        	foreach ($array as $v) {
        		$ids[] = $v['id'];
        	}
        	M('New_product_attribute')->where(array('id' => array('in', $ids)))->delete();
        }
        
		$groups =  M('New_product_group')->where(array('token' => session('token')))->select();
		$this->assign('groups', $groups);
        
		$this->assign('color', $this->color);
		$this->assign('attributeData', $attributeData);
		$this->assign('normsData', $normsData);
		$this->assign('colorData', $colorData);
		$this->assign('formatData', $formatData);
		$this->assign('productCatData', $productCatData);
		$this->assign('productDetailData', $pData);
		$this->assign('catid', $catid);
		$this->display('set_new');
	}
	
	/**
	 * 增加商品
	 */
	public function productSave() {
		$now = time();
		$token = isset($_POST['token']) ? htmlspecialchars($_POST['token']) : '';
		$catid = isset($_POST['catid']) ? intval($_POST['catid']) : 0;
		$num = isset($_POST['num']) ? intval($_POST['num']) : 0;
		$gid = isset($_POST['gid']) ? intval($_POST['gid']) : 0;
		$status = isset($_POST['status']) ? intval($_POST['status']) : 0;
		$fakemembercount = isset($_POST['fakemembercount']) ? intval($_POST['fakemembercount']) : 0;
		$pid = isset($_POST['pid']) ? intval($_POST['pid']) : 0;
		$name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
		$keyword = isset($_POST['keyword']) ? htmlspecialchars($_POST['keyword']) : '';
		$pic = isset($_POST['pic']) ? htmlspecialchars($_POST['pic']) : '';
		$price = isset($_POST['price']) ? htmlspecialchars($_POST['price']) : '';
		$vprice = isset($_POST['vprice']) ? htmlspecialchars($_POST['vprice']) : '';
		$oprice = isset($_POST['oprice']) ? htmlspecialchars($_POST['oprice']) : '';
		$mailprice = isset($_POST['mailprice']) ? htmlspecialchars($_POST['mailprice']) : '';
		$intro = isset($_POST['intro']) ? $_POST['intro'] : '';
		$xianshi = isset($_POST['xianshi']) ? $_POST['xianshi'] : '';
		$xinpin = isset($_POST['xinpin']) ? $_POST['xinpin'] : '';
		$tuijian = isset($_POST['tuijian']) ? $_POST['tuijian'] : '';
		$is_tg = isset($_POST['is_tg']) ? $_POST['is_tg'] : '';
		$tgprice = isset($_POST['tgprice']) ? $_POST['tgprice'] : '';
		$tgnum = isset($_POST['tgnum']) ? $_POST['tgnum'] : '';
		$tgend = isset($_POST['tgend']) ? $_POST['tgend'] : '';
		$attribute = isset($_POST['attribute']) ? htmlspecialchars_decode($_POST['attribute']) : '';
		$norms = isset($_POST['norms']) ? htmlspecialchars_decode($_POST['norms']) : '';
		$images = isset($_POST['images']) ? htmlspecialchars_decode($_POST['images']) : '';
		$sort = isset($_POST['sort']) ? intval($_POST['sort']) : 100;
        $allow_distribution = isset($_POST['allow_distribution']) ? intval($_POST['allow_distribution']) : 0; //是否允许分销
        $commission_type = isset($_POST['commission_type']) ? trim($_POST['commission_type']) : 0;
        $commission = isset($_POST['commission']) ? floatval(trim($_POST['commission'])) : 0;
		$addcommission_type = isset($_POST['addcommission_type']) ? trim($_POST['addcommission_type']) : 0;
        $addcommission = isset($_POST['addcommission']) ? floatval(trim($_POST['addcommission'])) : 0;
		
		//dump($_POST);
		//die;
		if ($token != session('token')) {
			exit(json_encode(array('error_code' => true, 'msg' => '不合法的数据')));
		}
		if (empty($name)) {
			exit(json_encode(array('error_code' => true, 'msg' => '商品不能为空')));
		}
		if (empty($price)) {
			exit(json_encode(array('error_code' => true, 'msg' => '价格不能为空')));
		}
		if (empty($vprice)) {
			exit(json_encode(array('error_code' => true, 'msg' => '会员价不能为空')));
		}
		if (empty($oprice)) {
			exit(json_encode(array('error_code' => true, 'msg' => '原始价格不能为空')));
		}
		if (empty($keyword)) {
			exit(json_encode(array('error_code' => true, 'msg' => '关键词不能为空')));
		}
		if (empty($catid)) {
			exit(json_encode(array('error_code' => true, 'msg' => '商品分类不能为空')));
		}
		if ($objCat = M("New_product_cat")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $catid))->find()) {
			if ($objCat['isfinal'] == 2) {
				exit(json_encode(array('error_code' => true, 'msg' => '该分类下有子分类，不可直接添加商品')));
			} else {
				D("New_product_cat")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $catid))->save(array('isfinal' => 1));
			}
		} else {
			exit(json_encode(array('error_code' => true, 'msg' => '商品分类不存在')));
		}
		$data = array('token' => $token, 'gid' => $gid, 'status' => $status, 'cid' => $this->_cid, 'num' => $num, 'fakemembercount' => $fakemembercount, 'sort' => $sort, 'catid' => $catid, 'name' => $name, 'price' => $price, 'mailprice' => $mailprice, 'vprice' => $vprice,'xinpin' => $xinpin,'tuijian' => $tuijian,'xianshi' => $xianshi, 'oprice' => $oprice, 'intro' => $intro, 'logourl' => $pic, 'keyword' => $keyword, 'time' => time(), 'allow_distribution' => $allow_distribution, 'commission_type' => $commission_type, 'commission' => $commission, 'addcommission_type' => $addcommission_type, 'addcommission' => $addcommission, 'tgprice'=>$tgprice, 'is_tg'=>$is_tg, 'tgnum'=>$tgnum, 'tgend'=>$tgend);
		$data['discount'] = number_format($price / $oprice, 2, '.', '') * 10;
		$product = M('New_product');
		
		if ($pid && $obj = $product->where(array('id' => $pid, 'token' => $token, 'cid' => $this->_cid))->find()) {
			$product->where(array('id' => $pid, 'token' => $token))->save($data);
		} else {
			$pid = $product->add($data);
		}
		if (empty($pid)) {
			exit(json_encode(array('error_code' => true, 'msg' => '商品添加出错了')));
		}
		
		if($_POST['pc_cat_id'] && $_POST['pc_show'] && empty($_POST['pid'])){
			$database_pc_product_category = D('Pc_product_category');
			$condition_pc_product_category['cat_id'] = $_POST['pc_cat_id'];
			$condition_pc_product_category['token'] = session('token');
			$now_category = $database_pc_product_category->field(true)->where($condition_pc_product_category)->find();
			if(empty($now_category)){
				exit(json_encode(array('error_code' => true, 'msg' => '检测到与该分类的电脑网站产品分类不存在！请您编辑该分类解绑电脑网站产品分类后再重试。')));
			}
			$database_pc_product = D('Pc_product');
			$data_pc_product['cat_id'] = $_POST['pc_cat_id'];
			$data_pc_product['price'] = $_POST['price'];
			$data_pc_product['pic'] = $_POST['pic'];
			$data_pc_product['token'] = session('token');
			$data_pc_product['title'] = $this->_post('name');
			$data_pc_product['content'] = $this->_post('intro','stripslashes,htmlspecialchars_decode');
			$data_pc_product['time'] = $_SERVER['REQUEST_TIME'];
			
			$database_pc_product->data($data_pc_product)->add();
		}
		
		if ($keys = M('Keyword')->where(array('pid' => $pid, 'token' => $token, 'module' => 'Storenew'))->find()) {
			M('Keyword')->where(array('pid' => $pid, 'token' => $token, 'id' => $keys['id']))->save(array('keyword' => $keyword));
		} else {
			M('Keyword')->add(array('token' => $token, 'pid' => $pid, 'keyword' => $keyword, 'module' => 'Storenew'));
		}
		if (!empty($attribute)) {
			$product_attribute = M('New_product_attribute');
			$attribute = json_decode($attribute, true);
			foreach ($attribute as $row) {
				$data_a = array('pid' => $pid,'token' => $token, 'cid' => $this->_cid, 'aid' => $row['aid'], 'name' => $row['name'], 'value' => $row['value']);
				if ($row['id']) {
					$product_attribute->where(array('id' => $row['id'], 'cid' => $this->_cid, 'pid' => $pid,'token' => $token))->save($data_a);
				} else {
					$product_attribute->add($data_a);
				}
			}
		}
		
		if (!empty($norms)) {
			$product_detail = M('New_product_detail');
			$norms = json_decode($norms, true);
			$detailList = $product_detail->field('id')->where(array('pid' => $pid, 'cid' => $this->_cid,'token' => $token))->select();
			$oldDetailId = array();
			foreach ($detailList as $val) {
				$oldDetailId[$val['id']] = $val['id'];
			}
			foreach ($norms as $row) {
				$data_d = array('pid' => $pid, 'cid' => $this->_cid, 'format' => $row['format'],'token' => $token, 'color' => $row['color'], 'num' => $row['num'], 'price' => $row['price'], 'vprice' => $row['vprice']);
				if ($row['id']) {
					unset($oldDetailId[$row['id']]);
					$product_detail->where(array('id' => $row['id'], 'cid' => $this->_cid, 'pid' => $pid,'token' => $token))->save($data_d);
				} else {
					$product_detail->add($data_d);
				}
			}
			//删除上次剩余的库存
			foreach ($oldDetailId as $id) {
				$product_detail->where(array('id' => $id, 'cid' => $this->_cid, 'pid' => $pid,'token' => $token))->delete();
			}
		}
		if (!empty($images)) {
			$product_image = M('New_product_image');
			$images = json_decode($images, true);
			$iamgelist = $product_image->field('id')->where(array('pid' => $pid, 'cid' => $this->_cid,'token' => $token))->select();
			$oldImageId = array();
			foreach ($iamgelist as $val) {
				$oldImageId[$val['id']] = $val['id'];
			}
			foreach ($images as $row) {
				if (empty($row['image'])) continue;
				$data_d = array('pid' => $pid, 'cid' => $this->_cid, 'image' => $row['image'],'token' => $token);
				if ($row['id']) {
					unset($oldImageId[$row['id']]);
					$product_image->where(array('id' => $row['id'], 'cid' => $this->_cid, 'pid' => $pid,'token' => $token))->save($data_d);
				} else {
					$product_image->add($data_d);
				}
			}
			//删除上次剩余的库存
			foreach ($oldImageId as $id) {
				$product_image->where(array('id' => $id, 'cid' => $this->_cid, 'pid' => $pid,'token' => $token))->delete();
			}
		}
		exit(json_encode(array('error_code' => false, 'msg' => '商品操作成功')));
	}
	
	/**
	 * 删除商品
	 */
	public function del()
	{
		$product_model = M('New_product');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('id'=>$id,'token'=>session('token'), 'cid' => $this->_cid);
            $check = $product_model->where($where)->find();
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$keyword_model = M('Keyword');
            	$keyword_model->where(array('token' => session('token'), 'pid' => $id, 'module' => 'Storenew'))->delete();
            	$count = $product_model->where(array('catid' => $check['catid']))->count();
            	if (empty($count)) {
            		D("New_product_cat")->where(array('id' => $check['catid'], 'token' => session('token')))->save(array('isfinal' => 0));
            	}
                $this->success('操作成功', U('Storenew/product', array('token' => session('token'), 'dining' => $this->isDining)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/product', array('token'=>session('token'))));
            }
        }        
	}
	
	public function orders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('New_product_cart');
		$where = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 0, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Storenew/orders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}

		$sent = isset($_GET['sent']) ? $_GET['sent'] : '2';
		$paid =  isset($_GET['paid']) ? $_GET['paid'] : '2';
		if($paid == 2 && $sent == 1 || $paid == 2 && $sent == 0) {
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 0, 'cid' => $cid, 'sent'=>$sent);
		}else if($sent == 2 && $paid == 1  || $sent == 2 && $paid == 0){
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 0, 'cid' => $cid, 'paid'=>$paid);
		}else{
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 0, 'cid' => $cid);
		}
			
		$count      = $product_cart_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders = $product_cart_model->where($where2)->order("time desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		

		$where2['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where2)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function orderInfo()
	{
		$this->product_model = M('New_product');
		$this->product_cat_model = M('New_product_cat');
		$product_cart_model = M('New_product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id']), 'token' => $this->token))->find();
		//检查权限
		if (strtolower($thisOrder['token'])!=strtolower($this->_session('token'))){
			exit();
		}
		//$a = 'http://'.$_SERVER['HTTP_HOST'].'/index.php?g=Wap&m=Storenew&a=product&token='.$thisOrder['token'].'&id='.$thisOrder['id'].'&wecha_id='.$thisOrder['wecha_id'].'&cid='.$thisOrder['cid'].'';
		//dump($a);
		//die;
		
		if (IS_POST){
			if (intval($_POST['sent'])){
				$_POST['handled']=1;
			}
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			$save = array('sent'=>intval($_POST['sent']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid'],'handled'=>1);
			if ($company['id'] != $this->_cid) {
				empty($thisOrder['paid']) && $save['paid'] = intval($_POST['paid']);
			}
			$product_cart_model->where(array('id'=>$thisOrder['id']))->save($save);
			//TODO 发货的短信提醒
			$company = D('Company')->where(array('token' => $thisOrder['token'], 'id' => $thisOrder['cid']))->find();
			if ($_POST['sent']==1) {
				$userInfo = D('Userinfo')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				//Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $userInfo['tel']);
				
				$myhost = $_SERVER['HTTP_HOST'];
				$model = new templateNews();
				$model->sendTempMsg('OPENTM200565259', array('href' => 'http://'.$myhost.'/index.php?g=Wap&m=Storenew&a=myDetail&token='.$thisOrder['token'].'&cartid='.$thisOrder['id'].'&wecha_id='.$thisOrder['wecha_id'].'&cid='.$thisOrder['cid'].'', 'wecha_id' => $thisOrder['wecha_id'], 'first' => '订单发货提醒', 'keyword1' => ''.$thisOrder['orderid'].'', 'keyword2' => ''.$_POST['logistics'].'', 'keyword3' => ''.$_POST['logisticsid'].'', 'remark' => '你好，'.$thisOrder['truename'].'，你购买的商品已经发货，点击查看完整的物流信息 。如有问题请致电'.$company['tel'].'，'.$company['name'].'将在第一时间为您服务！'));
			}
			//给分佣者增加分佣记录
/* 			if (intval($_POST['paid']) && empty($thisOrder['paid']) && $thisOrder['twid']) {
// 				$this->savelog(3, $thisOrder['twid'], $this->token, $thisOrder['cid'], $thisOrder['totalprice']);
				if ($set = M("New_twitter_set")->where(array('token' => $this->token, 'cid' => $thisOrder['cid']))->find()) {
					$db = D("New_twitter_log");
					$price = $set['percent'] * 0.01 * $thisOrder['totalprice'];
					$db->add(array('token' => $this->token, 'cid' => $thisOrder['cid'], 'twid' => $thisOrder['twid'], 'type' => 3, 'dateline' => time(), 'param' => $thisOrder['totalprice'], 'price' => $price));
					if ($count = M("New_twitter_count")->where(array('token' => $this->token, 'cid' => $thisOrder['cid'], 'twid' => $thisOrder['twid']))->find()) {
						D("New_twitter_count")->where(array('id' => $count['id']))->setInc('total', $price);
					} else {
						D("New_twitter_count")->add(array('token' => $this->token, 'cid' => $thisOrder['cid'], 'twid' => $thisOrder['twid'], 'total' => $price, 'remove' => 0));
					}
				}
			
			/*******************销量的增加******************************/
			if (intval($_POST['paid']) && empty($thisOrder['paid']) ) {
				foreach ($carts as $k => $c) {
					$this->product_model->where(array('id'=>$k))->setInc('salecount', $tdata[1][$k]['total']);
				}
			}
			/*******************销量的增加******************************/
			
			$this->success('修改成功',U('Storenew/orderInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
		}else {
			//订餐信息
			$product_diningtable_model = M('New_product_diningtable');
			if ($thisOrder['tableid']) {
				$thisTable=$product_diningtable_model->where(array('id'=>$thisOrder['tableid']))->find();
				$thisOrder['tableName']=$thisTable['name'];
			}
			$this->assign('thisOrder',$thisOrder);
			$carts = unserialize($thisOrder['info']);
			$totalFee=0;
			$totalCount=0;
			
			$data = $this->getCat($carts);
			if (isset($data[1])) {
				foreach ($data[1] as $pid => $row) {
					$totalCount += $row['total'];
					$totalFee += $row['totalPrice'];
					$listNum[$pid] = $row['total'];
				}
			}
			
			$list = $data[0];
			$this->assign('products', $list);
			$this->assign('totalFee',$totalFee);
			$this->assign('totalCount',$totalCount);
			$this->assign('mailprice',$data[2]);			
			$this->display();
		}
	}
	
	/**
	 * 计算一次购物的总的价格与数量
	 * @param array $carts
	 */
	public function getCat($carts)
	{
		if (empty($carts)) {
			return array();
		}
		//邮费
		$mailPrice = 0;
		//商品的IDS
		$pids = array_keys($carts);
		
		//商品分类IDS
		$productList = $cartIds = array();
		if (empty($pids)) {
			return array(array(), array(), array());
		}
		
		$productdata = $this->product_model->where(array('id'=> array('in', $pids)))->select();
		foreach ($productdata as $p) {
			if (!in_array($p['catid'], $cartIds)) {
				$cartIds[] = $p['catid'];
			}
			$mailPrice = max($mailPrice, $p['mailprice']);
			$productList[$p['id']] = $p;
		}
	
		//商品规格参数值
		$catlist = $norms = array();
		if ($cartIds) {
			$normsdata = M('New_product_norms')->where(array('catid' => array('in', $cartIds)))->select();
			foreach ($normsdata as $r) {
				$norms[$r['id']] = $r['value'];
			}
			//商品分类
			$catdata = M('New_product_cat')-> where(array('id' => array('in', $cartIds)))->select();
			foreach ($catdata as $cat) {
				$catlist[$cat['id']] = $cat;
			}
		}
		$dids = array();
		foreach ($carts as $pid => $rowset) {
			if (is_array($rowset)) {
				$dids = array_merge($dids, array_keys($rowset));
			}
		}
		
		//商品的详细
		$totalprice = 0;		
		$data = array();
		if ($dids) {
			$dids = array_unique($dids);
			$detail = M('New_product_detail')->where(array('id'=> array('in', $dids)))->select();
			foreach ($detail as $row) {
				$row['colorName'] = isset($norms[$row['color']]) ? $norms[$row['color']] : '';
				$row['formatName'] = isset($norms[$row['format']]) ? $norms[$row['format']] : '';
				$row['count'] = isset($carts[$row['pid']][$row['id']]['count']) ? $carts[$row['pid']][$row['id']]['count'] : 0;
				$row['price'] = isset($carts[$row['pid']][$row['id']]['price']) ? $carts[$row['pid']][$row['id']]['price'] : $row['price'];
				$productList[$row['pid']]['detail'][] = $row;
				$data[$row['pid']]['total'] = isset($data[$row['pid']]['total']) ? intval($data[$row['pid']]['total'] + $row['count']) : $row['count'];
				$data[$row['pid']]['totalPrice'] = isset($data[$row['pid']]['totalPrice']) ? intval($data[$row['pid']]['totalPrice'] + $row['count'] * $row['price']) : $row['count'] * $row['price'];//array('total' => $totalCount, 'totalPrice' => $totalFee);
				$totalprice += $data[$row['pid']]['totalPrice'];			}
		}
		//商品的详细列表
		$list = array();
		foreach ($productList as $pid => $row) {
			if (!isset($data[$pid]['total'])) {
				$count = $price = 0;
				if (isset($carts[$pid]) && is_array($carts[$pid])) {
					$a = explode("|", $carts[$pid]['count']);
					$count = isset($carts[$pid]['count']) ? $carts[$pid]['count'] : 0;
					$price = isset($carts[$pid]['price']) ? $carts[$pid]['price'] : 0;
				} else {
					$a = explode("|", $carts[$pid]);
					$count = isset($a[0]) ? $a[0] : 0;
					$price = isset($a[1]) ? $a[1] : 0;
				}
				$data[$pid] = array();
				$row['price'] = $price ? $price : $row['price'];
				$row['count'] = $data[$pid]['total'] = $count;
				$data[$pid]['totalPrice'] = $data[$pid]['total'] * $row['price'];
				$totalprice += $data[$pid]['totalPrice'];			
			}
			$row['formatTitle'] =  isset($catlist[$row['catid']]['norms']) ? $catlist[$row['catid']]['norms'] : '';
			$row['colorTitle'] =  isset($catlist[$row['catid']]['color']) ? $catlist[$row['catid']]['color'] : '';
			$list[] = $row;
		}
			if ($obj = M('New_product_setting')->where(array('token' => $this->token, 'cid' => $this->_cid))->find()) {
			if ($totalprice >= $obj['price']) $mailPrice = 0;
		}		return array($list, $data, $mailPrice);
	}
	
	public function deleteOrder()
	{
		$product_model = M('New_product');
		$product_cart_model = M('New_product_cart');
		$product_cart_list_model = M('New_product_cart_list');
		$thisOrder = $product_cart_model->where(array('id' => intval($_GET['id']), 'cid' => $this->_cid))->find();
		//检查权限
		$id = $thisOrder['id'];
		if ($thisOrder['token'] != $this->_session('token')){
			exit();
		}
		//
		//删除订单和订单列表
		$product_cart_model->where(array('id' => $id, 'cid' => $this->_cid))->delete();
		$product_cart_list_model->where(array('cartid' => $id, 'cid' => $this->_cid))->delete();
		
		//商品销量做相应的减少
		if (empty($thisOrder['paid'])) {
			$carts = unserialize($thisOrder['info']);
			//还原库存
			foreach ($carts as $pid => $rowset) {
				$total = 0;
				if (is_array($rowset)) {
					foreach ($rowset as $did => $row) {
						M('New_product_detail')->where(array('id' => $did, 'pid' => $pid))->setInc('num', $row['count']);
						$total += $row['count'];
					}
				} else {
					if (strstr($rowset, '|')) {
						$a = explode("|", $rowset);
						$total = $a[0];
					} else {
						$total = $rowset;
					}
				}
				$product_model->where(array('id' => $pid))->setInc('num', $total);
				//$product_model->where(array('id' => $pid))->setDec('salecount', $total);
			}
//			foreach ($carts as $k => $c){
//				if (is_array($c)){
//					$productid=$k;
//					$price=$c['price'];
//					$count=$c['count'];
//					$product_model->where(array('id'=>$k))->setDec('salecount',$c['count']);
//				}
//			}
		}
		$this->success('操作成功',U('Storenew/orders', array('token' => session('token'))));
		//$this->success('操作成功',$_SERVER['HTTP_REFERER']);
	}
	
	
	/**
	 * 商城设置
	 */
	public function setting()
	{
		$setting = M('New_product_setting');
		$obj = $setting->where(array('token' => session('token'), 'cid' => $this->_cid))->find();
		if (IS_POST) {
			if ($obj) {
				unset($_POST['id']);
				$t = $setting->where(array('token' => session('token'), 'cid' => $this->_cid, 'id' => $obj['id']))->save($_POST);
				if ($t) {
					$this->success('修改成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$pid = $setting->add($_POST);
				if ($pid) {
					$this->success('增加成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			}
		} else {
			$showGroup = C('zhongshuai') ? 1 : 0;
			
			include('./weimicms/Lib/ORG/index.Tpl.php');
			include('./weimicms/Lib/ORG/cont.Tpl.php');
			
			$this->assign('showgroup', $showGroup);
			$this->assign('tpl', $tpl);
			$this->assign('contTpl', $contTpl);
			$this->assign('setting', $obj);
			$this->display();	
		}
	}
	
	public function comment()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		$product_model = M('New_product_comment');
		
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		
		$where = array('token' => $this->token, 'cid' => $cid, 'pid' => $pid, 'isdelete' => 0);
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('pid',$pid);
		$this->assign('catid', $catid);
		$this->display();	
		
	}
	
	/**
	 * 删除商品
	 */
	public function commentdel()
	{
		$catid = intval($_GET['catid']);
		$pid = intval($_GET['pid']);
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {
        	$cid = $this->_cid;
			if (C('zhongshuai')) {
				$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
				$cid = $company['id'];
			}
        	M('New_product_comment')->where(array('id' => $id,'token' => session('token'),'cid' => $cid))->save(array('isdelete' => 1));
			$this->success('操作成功', U('Storenew/comment',array('token'=>session('token'),'catid' => $catid,'pid' => $pid)));
        }        
	}
	
	public function group()
	{
		$data = M('New_product_group');
		$where = array('token' => session('token'));
        $count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow.','.$Page->listRows)->select();
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();		
	}
	
	public function groupAdd()
	{
		if (IS_POST) { 
            $data = D('New_product_group');
            $id = intval($this->_post('id'));
			$cid = intval($this->_post('cid'));
			$token = $this->token;
			
            if ($id) {
	            $where = array('id' => $id, 'token' => $this->token);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->save()) {
						$this->success('修改成功', U('Storenew/group',array('token' => $this->token)));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add()) {
						$this->success('添加成功', U('Storenew/group',array('token' => $this->token)));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else {
			$token = $this->token;
			$cid = $this->_cid;
	        $id = $this->_get('id');
			$group = M('New_product_group')->where(array('id' => $id, 'cid' => $cid ,'token' => $token))->find();

			$this->assign("set", $group);
			$this->display();
		}
	}
	
	/**
	 * 删除分组
	 */
	public function groupDel()
	{
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {
        	M('New_product_group')->where(array('id' => $id,'token' => session('token')))->delete();
			$this->success('操作成功', U('Storenew/group',array('token'=>session('token'))));
        }        
	}
	
	/**
	 * 组别分配到店
	 */
	public function groupSet()
	{
		if (IS_POST) {
			$gid = intval($_REQUEST['gid']);
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'gid' => $gid))->select();
			$cids = array();
			foreach ($relation as $r) {
				$cids[] = $r['cid'];
			}
			$companys = $_REQUEST['company'];
			foreach ($companys as $cid) {
				if (!in_array($cid, $cids)) {
					D("New_product_relation")->add(array('cid' => $cid, 'gid' => $gid, 'token' => $this->token));
				} else {
					$cids = array_diff($cids, array($cid));
				}
			}
			if ($cids) {
				D("New_product_relation")->where(array('cid' => array('in', $cids), 'token' => $this->token))->delete();
			}
			$this->success("分配成功", U('Storenew/group',array('token'=>session('token'))));
		} else {
	        $gid = $this->_get('gid'); 
			$group = M('New_product_group')->where(array('id' => $gid, 'token' => $this->token))->find();
			if (empty($group)) {
				$this->error("参数错误", U('Storenew/group', array('token' => session('token'))));
			}
			$this->assign("group", $group);
			
			$company = M('Company')->where("`token`='{$this->token}' AND ((`isbranch`=1 AND `display`=1) OR `isbranch`=0)")->select();
			$this->assign("company", $company);
			
			$relation = M("New_product_relation")->where(array('token' => $this->token, 'gid' => $gid))->select();
			$cids = array();
			foreach ($relation as $r) {
				$cids[] = $r['cid'];
			}
			$this->assign("relation", $cids);
			
			$this->display();
		}
	}
	
	public function flash()
	{
		$flash = M("New_store_flash")->where(array('token' => $this->token, 'cid' => $this->_cid))->select();
		$this->assign('flash', $flash);
		$this->display();
	}
	
	public function flashadd()
	{
		$type = isset($_REQUEST['type']) ? intval($_REQUEST['type']) : 0;
		$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
		
		if (IS_POST) { 
            $data = D('New_store_flash');
            $id = intval($this->_post('id'));
            if ($id) {
	            $where = array('id' => $id, 'token' => $this->token, 'cid' => $this->_cid);
				$check = $data->where($where)->find();
				if ($check == false) $this->error('非法操作');
            }
			if ($data->create()) {
				if ($id) {
					if ($data->save()) {
						$this->success('修改成功', U('Storenew/flash',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				} else {
					if ($data->add()) {
						$this->success('添加成功', U('Storenew/flash',array('token' => session('token'))));
					} else {
						$this->error('操作失败');
					}
				}
			} else {
				$this->error($data->getError());
			}
		} else {
			$flash = M("New_store_flash")->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $id))->find();
			$type = isset($flash['type']) ? $flash['type'] : $type;
			$this->assign('flash', $flash);
			$this->assign('type', $type);
			$this->display();
		}
	}
	
	public function flashdel()
	{
		$where = array();
		$where['id']=$this->_get('id','intval');
		$where['token']=$this->token;
		$where['cid']=$this->_cid;
		if(D("New_store_flash")->where($where)->delete()){
			$this->success('操作成功',U('Storenew/flash',array('token' => session('token'))));
		}else{
			$this->error('操作失败',U('Storenew/flash',array('token' => session('token'))));
		}
	}
	public function twitter()
		{
		$data = M('New_twitter_count');
		$where = array('token' => session('token'), 'cid' => $this->_cid);
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $where['twid'] = array('like',"%$key%"); 
            $list = $data->where($where)->order("id DESC")->select();
			foreach ($list as $row=>$val) {
				$user = D('Userinfo')->where(array('twid' => $val['twid']))->find();
				$list[$row]['truename'] = $user['truename'];
				$fromcount = D('Userinfo')->where(array('fromtwid' => $val['twid']))->count();
				$list[$row]['fromcount'] = $fromcount;
				$addcount = D('Userinfo')->where(array('addtwid' => $val['twid']))->count();
				$list[$row]['addcount'] = $addcount;
			}
			//dump($list);
            $count      = $data->where($where)->count();       
            $Page       = new Page($count, 20);
        	$show       = $Page->show();
        	$this->assign('key', $key);
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count, 20);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($list as $row=>$val) {
				$user = D('Userinfo')->where(array('twid' => $val['twid']))->find();
				$list[$row]['truename'] = $user['truename'];
				$fromcount = D('Userinfo')->where(array('fromtwid' => $val['twid']))->count();
				$list[$row]['fromcount'] = $fromcount;
				$addcount = D('Userinfo')->where(array('addtwid' => $val['twid']))->count();
				$list[$row]['addcount'] = $addcount;
				$list[$row]['allcount'] = $addcount + $fromcount;
				$ordercount = D('New_product_cart')->where(array('twid' => $val['twid'],'paid'=>'1'))->count();
				$list[$row]['ordercount'] = $ordercount;
			}
			//dump($list);
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function twitterlist()
	{
		$data = M('Userinfo');
		$where = array('token' => session('token'), 'twid'=>array('neq',''));
        if (IS_POST) {
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $where['twid'] = array('like',"%$key%"); 
            $list = $data->where($where)->order("id ASC")->select();
			foreach ($list as $row=>$val) {
				$fromcount = D('Userinfo')->where(array('fromtwid' => $val['twid']))->count();
				$list[$row]['fromcount'] = $fromcount;
				$addcount = D('Userinfo')->where(array('addtwid' => $val['twid']))->count();
				$list[$row]['addcount'] = $addcount;
				$list[$row]['allcount'] = $addcount + $fromcount;
				$ordercount = D('New_product_cart')->where(array('twid' => $val['twid'],'paid'=>'1'))->count();
				$list[$row]['ordercount'] = $ordercount;
			}
			
            $count      = $data->where($where)->count();       
            $Page       = new Page($count, 30);
        	$show       = $Page->show();
        	$this->assign('key', $key);
        } else {
        	$count      = $data->where($where)->count();
        	$Page       = new Page($count, 30);
        	$show       = $Page->show();
        	$list = $data->where($where)->order("id ASC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($list as $row=>$val) {
				//查询分销员的1级会员数量
				$fromcount = D('Userinfo')->where(array('fromtwid' => $val['twid']))->count();
				$list[$row]['fromcount'] = $fromcount;
				//查询分销员的2级会员数量
				$addcount = D('Userinfo')->where(array('addtwid' => $val['twid']))->count();
				$list[$row]['addcount'] = $addcount;
				$list[$row]['allcount'] = $addcount + $fromcount;
				//查询分销员的推广订单已付款总数量
				$ordercount = D('New_product_cart')->where(array('twid' => $val['twid'],'paid'=>'1'))->count();
				$list[$row]['ordercount'] = $ordercount;
				//查询分销员佣金
				$userlog = D('New_twitter_count')->where(array('twid' => $val['twid']))->find();
				$list[$row]['usertotal'] = $userlog['total']-$userlog['remove'];
				//查询分销员的推荐人
				if($list[$row]['fromtwid']){
					$fromuser = D('Userinfo')->where(array('twid' => $val['fromtwid']))->find();
					$list[$row]['fromuser'] = $fromuser['truename'];
					$list[$row]['fromtel'] = $fromuser['tel'];
				}
				
			}
			//dump($list);
			//die;
        }
		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function twitterset()
	{
		$twitter = M('New_twitter_set')->where(array('token' => $this->token, 'cid' => $this->_cid))->find();
		if (IS_POST) {
			$_POST['token'] = $this->token;
			$_POST['cid'] = $this->_cid;
			unset($_POST['id']);
			if ($twitter) {
				$t = D('New_twitter_set')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id' => $twitter['id']))->save($_POST);
				if ($t) {
					$this->success('修改成功');
				} else {
					$this->error('操作失败');
				}
			} else {
				$tid = D('New_twitter_set')->add($_POST);
				if ($tid) {
					$this->success('增加成功');
				} else {
					$this->error('操作失败');
				}
			}
		} else {
			$this->assign('set', $twitter);
			$this->display();
		}
	}
	
	public function detail()
	{
		$data = M('New_twitter_log');
		$twid = isset($_GET['twid']) ? htmlspecialchars($_GET['twid']) : '';
		if (empty($twid)) exit();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $twid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $row=>$val) {
			$twuser = D('Userinfo')->where(array('twid' => $val['twid']))->find();
			$list[$row]['truename'] = $twuser['truename'];
			$order = D('New_product_cart')->where(array('wecha_id' => $val['wecha_id'],'paid'=>'1','orderid'=>$val['orderid']))->find();
			$list[$row]['totalprice'] = $order['totalprice'];
			$list[$row]['orderid'] = $order['orderid'];
			$order = D('Userinfo')->where(array('wecha_id' => $val['wecha_id']))->find();
			$list[$row]['ordername'] = $order['truename'];
			
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function detailinfo()
	{
		$data = M('New_twitter_log');
		$twid = isset($_GET['twid']) ? htmlspecialchars($_GET['twid']) : '';
		//if (empty($twid)) exit();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'price' => array('gt','0'));
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $row=>$val) {
			$twuser = D('Userinfo')->where(array('twid' => $val['twid']))->find();
			$list[$row]['truename'] = $twuser['truename'];
			$order = D('New_product_cart')->where(array('wecha_id' => $val['wecha_id'],'paid'=>'1','orderid'=>$val['orderid']))->find();
			$list[$row]['totalprice'] = $order['totalprice'];
			$list[$row]['orderid'] = $order['orderid'];
			$order = D('Userinfo')->where(array('wecha_id' => $val['wecha_id']))->find();
			$list[$row]['ordername'] = $order['truename'];
			
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	public function remove()
	{
		$data = M('New_twitter_remove');
		$twid = isset($_GET['twid']) ? htmlspecialchars($_GET['twid']) : '';
		if (empty($twid)) exit();
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'twid' => $twid);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $row=>$val) {
			$twuser = D('Userinfo')->where(array('twid' => $val['twid']))->find();
			$list[$row]['truename'] = $twuser['truename'];
			
		}
		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
		
	}
	
	public function removesearch()
	{
		$data = M('New_twitter_remove');
		$where = array('token' => $this->token, 'cid' => $this->_cid, 'status' => 0);
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $row=>$val) {
			$twuser = D('Userinfo')->where(array('twid' => $val['twid']))->find();
			$list[$row]['truename'] = $twuser['truename'];
			
		}
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	/**
	 * 删除分组
	 */
	public function changestatus()
	{
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        $twid = $this->_get('twid');
        if (IS_GET) {
        	if ($remove = M('New_twitter_remove')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->find()) {
	        	D('New_twitter_remove')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->save(array('status' => 1));
	        	D('New_twitter_count')->where(array('token' => session('token'),'cid' => $this->_cid,'twid' => $twid))->setInc('remove', $remove['price']);
				$this->success('操作成功', U('Storenew/removesearch',array('token' => session('token'), 'cid' => $this->_cid)));
        	}
        }        
	}
	
	/**
	 * 竞拍商品列表
	 */
	public function jingpai() 
	{		
		$cid = $this->_cid;
		$product_model = M('New_product_jingpai');
		$where = array('token' => session('token'), 'cid' => $this->_cid);
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('endtime desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach($list as $key=>$val){
				$jingpaiuser = M('New_product_jingpai_user')->where(array('token' => session('token'), 'cid' => $this->_cid , 'pid'=>$val['id'] ,'is_jingpai'=>'1'))->order('id desc')->find();
				$list[$key]['allcount'] = M('New_product_jingpai_user')->where(array('token' => session('token'), 'cid' => $val['cid'] , 'pid'=>$val['id']))->count();
				$list[$key]['lasttime'] = $jingpaiuser['dateline'];
				$user = M('Userinfo')->where(array('token' => session('token'), 'wecha_id'=>$jingpaiuser['wecha_id']))->find();
				$list[$key]['truename'] = $user['truename'] ? $user['truename'] : '匿名';
			}
			//dump($list);
			//die;
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('cid', $cid);
		$this->display();		
	}

	public function jingpaiset(){
		$cid 		= $this->_get('cid','intval');
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$Product_jingpai   = M('New_product_jingpai')->where($where)->find();

		if(IS_POST){
			
			$_POST['token'] 	= $this->token;
			$_POST['starttime'] 	= strtotime($this->_post('starttime','trim'));
			$_POST['endtime'] 		= strtotime($this->_post('endtime','trim'));
			$_POST['time'] 	= time(); 
			$_POST['num'] 		= 1;
			$images 	= $_REQUEST['imageList'];
			//dump($_POST);
			//die;
			if(D('New_product_jingpai')->create()){
				if($Product_jingpai){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('New_product_jingpai')->where($up_where)->save($_POST);
					//图片保存
					$this->jpimages_set($this->_post('id','intval'),$images,'save');
					//
					$this->handleKeyword($this->_post('id','intval'),'Storenewjingpai',$this->_post('keyword','trim'));
					$this->success('修改成功',U('Storenew/jingpai',array('token'=>$this->token,'cid'=>$cid)));
				}else{
					$id     = D('New_product_jingpai')->add($_POST);
					if($id){
						//图片保存
						$this->jpimages_set($id,$images,'add');
						//
						$this->handleKeyword($id,'Storenewjingpai',$this->_post('keyword','trim'));
						$this->success('添加成功',U('Storenew/jingpai',array('token'=>$this->token,'cid'=>$cid)));
					}
				}	
				
			}else{			
                $this->error(D('New_product_jingpai')->getError());
            }
			
		}else{
			$images 	= M('New_product_jingpai_image')->where(array('token'=>$this->token,'pid'=>$id,'cid'=>$this->_cid))->order('id asc')->select();
			$this->assign('imageList',$images);
			$this->assign('statdate',$statdate);
			$this->assign('enddate',$enddate);
			$this->assign('set',$Product_jingpai);
			$this->display(jingpai_set);
		}
	}
	
	public function jpimages_set($pid,$data,$type='add'){
		
		foreach($data as $key=>$val){
			$val['token'] 	= $this->token;
			$val['pid'] 	= $pid;
			$val['cid'] 	= $this->_cid;

			if($type == 'add'){
				M('New_product_jingpai_image')->add($val);
			}else if($type == 'save'){
				$where 	= array('id'=>$val['id'],'token'=>$this->token,'pid'=>$pid,'cid'=>$this->_cid);
				M('New_product_jingpai_image')->where($where)->save($val);
			}

		}

	}
	
	/**
	 * 删除商品
	 */
	public function deljingpai()
	{
		$product_model = M('New_product_jingpai');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('id'=>$id,'token'=>session('token'), 'cid' => $this->_cid);
            $check = $product_model->where($where)->find();
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$keyword_model = M('Keyword');
            	$keyword_model->where(array('token' => session('token'), 'pid' => $id, 'module' => 'Storenewjingpai'))->delete();
            	$count = $product_model->where(array('cid' => $check['cid']))->count();
                $this->success('操作成功', U('Storenew/jingpai', array('token' => session('token'), 'cid'=>$cid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/jingpai', array('token'=>session('token'),'cid'=>$cid)));
            }
        }        
	}
	
	//竞拍记录
	
	public function jingpairule() 
	{		
		$cid = $this->_cid;
		$pid = $this->_get('pid');
		$product_model = M('New_product_jingpai_user');
		$where = array('token' => session('token'), 'cid' => $this->_cid , 'pid'=>$pid);
		$count      = $product_model->where($where)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
		foreach($list as $key=>$val){
			$jingpai = M('New_product_jingpai')->where(array('token' => session('token'), 'cid' => $this->_cid , 'id'=>$val['pid']))->find();
			$user = M('Userinfo')->where(array('token' => session('token'), 'wecha_id'=>$list[$key]['wecha_id']))->find();
			$list[$key]['truename'] = $user['truename'] ? $user['truename'] : '匿名';
			$list[$key]['tel'] = $user['tel'] ? $user['tel'] : '无';
			if($list[$key]['price'] == $jingpai['price']){
				$list[$key]['is_frist'] = '1';
			}else{
				$list[$key]['is_frist'] = '2';
			}
			$issub = M('Wechat_group_list')->where(array('token'=>$this->token,'openid'=>$val['wecha_id']))->find();
			if($issub){
				$list[$key]['sub'] 		= '1';
			}
		}
		//dump($list);
		//die;

		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('count', $count);
		$this->assign('pid', $pid);
		$this->assign('cid', $cid);
		$this->display();		
	}
	/**
	 * 删竞拍记录
	 */
	public function deljingpaiuser()
	{
		$product_model = M('New_product_jingpai_user');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
        $pid = $this->_get('pid');
		$id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('pid'=>$pid,'token'=>session('token'), 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			//dump($check);
			//die;
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$count = $product_model->where(array('cid' => $check['cid']))->count();
                $this->success('操作成功', U('Storenew/jingpairule', array('token' => session('token'), 'cid'=>$cid, 'pid'=>$pid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/jingpai', array('token'=>session('token'),'cid'=>$cid, 'pid'=>$pid)));
            }
        }        
	}
	/**
	 * 生成竞拍订单给前台会员
	 */
	public function addjingpaiorder()
	{
		$product_user_model = M('New_product_jingpai_user');
		$product_model = M('New_product_jingpai');
		$Product_cart = M('New_product_cart');
		$Product_cart_list = M('New_product_cart_list');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$id = $this->_get('id');
		$now = time();
        if (IS_GET) {                              
            $where = array('token'=>session('token'), 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			$whereuser = array('token'=>session('token'), 'cid' => $this->_cid , 'pid'=>$id);
			$checkuser = $product_user_model->where($whereuser)->order('id desc')->find();
			
			$order['token'] = $check['token'];
			$order['cid'] = $check['cid'];
			$order['paymode'] = 1;
			$order['price'] = $check['price'];
			$order['oprice'] = $check['price'];
			$order['vprice'] = $check['price'];
			$order['totalprice'] = $check['price'];
			$order['wecha_id'] = $checkuser['wecha_id'];
			$order['total'] = 1;
			$order['pid'] = $check['id'];
			$order['time'] = $now;
			$order['orderid'] = $orderid = date("YmdHis") . rand(100000, 999999);
			$order['jingpai'] = 1;
			
			//写入订单测试
			$orderinfo = array($order['pid']=>array(''.$check['id'].''=> '1|'.$check['price'].''));
			$order['info'] = serialize($orderinfo);
			
			//生成订单判断
			if ($check['is_order'] == '1'){
				$this->error('已经生成，无需再次生成');
			}else{
				D("New_product_jingpai")->where(array('token' => $this->token, 'cid' => $order['cid'], 'id'=>$id))->save(array('is_order' => '1'));
			}
			
            if ($check == false) $this->error('错误操作，无信息');
			//写入订单
            $cart = $Product_cart->add($order);

            if ($cart == true) {
            	//$count = $product_model->where(array('cid' => $check['cid']))->count();
				
				//发送订单状态更新的模版消息
				$myhost = $_SERVER['HTTP_HOST'];
				$model = new templateNews();
				$model->sendTempMsg('TM00017', array('href' => 'http://'.$myhost.'/index.php?g=Wap&m=Storenew&a=myjingpaiDetail&token='.$order['token'].'&cartid='.$cart.'&wecha_id='.$order['wecha_id'].'&cid='.$order['cid'].'', 'wecha_id' => $order['wecha_id'], 'first' => '您好！你参与的竞拍成功胜出', 'OrderSn' => $order['orderid'], 'OrderStatus' =>'未付款' , 'remark' => '本次订单金额：'.$order['price'].'元，请及时付款，点击查看详情！'));
				//写入购物车列表
				$order['cartid'] = $cart;
				$order['productid'] = $check['id'];
            	$cart_list = $Product_cart_list->add($order);
                $this->success('操作成功', U('Storenew/jingpai', array('token' => session('token'), 'cid'=>$cid, 'pid'=>$pid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/jingpai', array('token'=>session('token'),'cid'=>$cid, 'pid'=>$pid)));
            }
        }
	}
	
	//商城帮助介绍
	public function help()
	{
		$setting = M('New_twitter_help');
		$obj = $setting->where(array('token' => session('token'), 'cid' => $this->_cid))->find();
		if (IS_POST) {
			if ($obj) {
				unset($_POST['id']);
				$t = $setting->where(array('token' => session('token'), 'cid' => $this->_cid, 'id' => $obj['id']))->save($_POST);
				if ($t) {
					$this->success('修改成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$pid = $setting->add($_POST);
				if ($pid) {
					$this->success('增加成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			}
			
		} else {
			$this->assign('setting', $obj);
			$this->display();	
		}
	}
	
	
	public function set_reply()
	{
		$setting = M('New_product_set_reply');
		$obj = $setting->where(array('token' => session('token'), 'cid' => $this->_cid))->find();
		if (IS_POST) {
			$_POST['wecha_id'] = htmlspecialchars($_POST['openid']);
			if ($obj) {
				unset($_POST['id']);
				$t = $setting->where(array('token' => session('token'), 'cid' => $this->_cid, 'id' => $obj['id']))->save($_POST);
				$this->handleKeyword($obj['id'],'Storenewindex',$this->_post('keyword','trim'));
				if ($t) {
					$this->success('修改成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			} else {
				$pid = $setting->add($_POST);
				$this->handleKeyword($obj['id'],'Storenewindex',$this->_post('keyword','trim'));
				if ($pid) {
					$this->success('增加成功',U('Storenew/index',array('token' => session('token'))));
				} else {
					$this->error('操作失败');
				}
			}
			
		} else {
			$showGroup = C('zhongshuai') ? 1 : 0;
			
			
			$this->assign('showgroup', $showGroup);
			$this->assign('contTpl', $contTpl);
			$this->assign('setting', $obj);
			$this->display();	
		}
	}
	
	public function jingpaiorders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('New_product_cart');
		$where = array('token' => $this->_session('token'), 'jingpai' => 1, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Storenew/orders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		
		$sent = isset($_GET['sent']) ? $_GET['sent'] : '2';
		$paid =  isset($_GET['paid']) ? $_GET['paid'] : '2';
		if($paid == 2 && $sent == 1 || $paid == 2 && $sent == 0) {
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 1, 'cid' => $cid, 'sent'=>$sent);
		}else if($sent == 2 && $paid == 1  || $sent == 2 && $paid == 0){
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 1, 'cid' => $cid, 'paid'=>$paid);
		}else{
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'jingpai' => 1, 'cid' => $cid);
		}
			
		$count      = $product_cart_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where2)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$orders[$k]['name'] = $userinfo['wechaname'];
			}

		$where2['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where2)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	public function jingpaiorderInfo()
	{
		$this->product_model = M('New_product');
		$this->product_cat_model = M('New_product_cat');
		$product_cart_model = M('New_product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id']),'jingpai'=>'1','token' => $this->token))->find();
		//检查权限
		if (strtolower($thisOrder['token'])!=strtolower($this->_session('token'))){
			exit();
		}
		if (IS_POST){
			$now = time();
			if (intval($_POST['sent'])){
				$_POST['handled'] = 1;
				$_POST['handledtime'] = $now;
			}
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			$save = array('sent'=>intval($_POST['sent']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid'],'handled'=>1,'handledtime'=>$now);
			//var_dump($_POST);
			//die;
			if ($company['id'] != $this->_cid) {
				empty($thisOrder['paid']) && $save['paid'] = intval($_POST['paid']);
			}
			$product_cart_model->where(array('id'=>$thisOrder['id']))->save($save);
			
			//TODO 发货的短信提醒
			$company = D('Company')->where(array('token' => $thisOrder['token'], 'id' => $thisOrder['cid']))->find();
			if ($_POST['sent']==1) {
				$userInfo = D('Userinfo')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				//Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $userInfo['tel']);
				$myhost = $_SERVER['HTTP_HOST'];
				$model = new templateNews();
				$model->sendTempMsg('OPENTM200565259', array('href' => 'http://'.$myhost.'/index.php?g=Wap&m=Storenew&a=myjingpaiDetail&token='.$thisOrder['token'].'&cartid='.$thisOrder['id'].'&wecha_id='.$thisOrder['wecha_id'].'&cid='.$thisOrder['cid'].'', 'wecha_id' => $thisOrder['wecha_id'], 'first' => '订单发货提醒', 'keyword1' => ''.$thisOrder['orderid'].'', 'keyword2' => ''.$_POST['logistics'].'', 'keyword3' => ''.$_POST['logisticsid'].'', 'remark' => '你好，'.$thisOrder['truename'].'，你购买的商品已经发货，点击查看完整的物流信息 。如有问题请致电'.$company['tel'].'，'.$company['name'].'将在第一时间为您服务！'));
			}

			$this->success('修改成功',U('Storenew/orderInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
		}else {

			$product_jingpai_model = M('New_product_cart_list');
			$thisTable=$product_jingpai_model->where(array('cartid'=>$thisOrder['id']))->find();

			$this->assign('thisOrder',$thisOrder);

			$list = M('New_product_jingpai')->where(array('id'=>$thisTable['productid'],'token'=>session('token'),'cid'=>$thisTable['cid']))->find();
			//dump($list);
			//die;
			$this->assign('o', $list);
			$this->assign('totalFee',$totalFee);
			$this->assign('totalCount',$totalCount);
			$this->assign('mailprice',$data[2]);			
			$this->display();
		}
	}
	
	public function newsset(){
		$cid 		= $this->_cid;
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$Product_news   = M('New_product_news')->where($where)->find();

		if(IS_POST){
			$_POST['token'] 	= $this->token;
			$_POST['add_time'] 	= time(); 
			//dump($_POST);
			//die;
			if(D('New_product_news')->create()){
				if($Product_news){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('New_product_news')->where($up_where)->save($_POST);
					$this->success('修改成功',U('Storenew/news',array('token'=>$this->token,'cid'=>$cid)));
				}else{
					$id     = D('New_product_news')->add($_POST);
					//dump($_POST);
					//die;
					if($id){
						$this->success('添加成功',U('Storenew/news',array('token'=>$this->token,'cid'=>$cid)));
					}
				}	
				
			}else{			
                $this->error(D('New_product_news')->getError());
            }
			
		}else{
			$this->assign('add_time',$add_time);
			$this->assign('set',$Product_news);
			$this->display(news_set);
		}
	}
	
	/**
	 * 文章列表
	 */
	public function news(){
		$cid = $this->_cid;
		$product_model = M('New_product_news');
		$where = array('token' => session('token'), 'cid' => $this->_cid);
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }
            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			//dump($list);
			//die;
        }
		
		$this->assign('page',$show);		
		$this->assign('news',$list);
		$this->assign('cid', $cid);
		$this->display();
	}
	
	/**
	 * 删竞文章
	 */
	public function delnews(){
		$product_model = M('New_product_news');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('token'=>session('token'), 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			//dump($check);
			//die;
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$count = $product_model->where(array('cid' => $check['cid']))->count();
                $this->success('操作成功', U('Storenew/news', array('token' => session('token'), 'cid'=>$cid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/news', array('token'=>session('token'),'cid'=>$cid)));
            }
        }        
	}
	
	/**
	 * 在线支付对账订单
	 */
	public function onlineorders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('New_product_cart');
		$where = array('token' => $this->_session('token'), 'jingpai' => 1, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Storenew/onlineorders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		
		$sent = isset($_GET['sent']) ? $_GET['sent'] : '2';
		if($sent == 1 || $sent == 0) {
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'paid' => 1, 'cid' => $cid, 'sent'=>$sent, 'paytype'=>(array('neq','CardPay')));
		}else{
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'paid' => 1, 'cid' => $cid, 'paytype'=>(array('neq','CardPay')));
		}
			
		$count      = $product_cart_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where2)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$orders[$k]['name'] = $userinfo['wechaname'];
			}
		//dump($orders);
		//die;

		$where2['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where2)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	/**
	 * 会员卡支付对账订单
	 */
	public function cardpayorders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('New_product_cart');
		$where = array('token' => $this->_session('token'), 'jingpai' => 1, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Storenew/cardpayorders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		
		$sent = isset($_GET['sent']) ? $_GET['sent'] : '2';
		if($sent == 1 || $sent == 0) {
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'paid' => 1, 'cid' => $cid, 'sent'=>$sent, 'paytype'=>(array('eq','CardPay')));
		}else{
			$where2 = array('token' => $this->_session('token'), 'groupon' => 0, 'dining' => 0, 'paid' => 1, 'cid' => $cid, 'paytype'=>(array('eq','CardPay')));
		}
			
		$count      = $product_cart_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where2)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$orders[$k]['name'] = $userinfo['wechaname'];
			}
		//dump($orders);
		//die;

		$where2['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where2)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	/**
	 * 团购开团列表
	 */
	public function groupon()
	{
		$now = time();
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_groupon_model = M('New_product_groupon');
		$product_model = M('New_product');

		//$paid = isset($_GET['paid']) ? $_GET['paid'] : '0';
		//if($paid == 1) {
		//	$where2 = array('token' => $this->_session('token'), 'paid' => $paid, 'cid' => $cid);
		//}else{
			$where2 = array('token' => $this->_session('token'), 'cid' => $cid);
		//}
			
		$count      = $product_groupon_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_groupon_model->where($where2)->group('code')->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$product= $product_model->where(array('token' => session('token'),'cid'=>$var['cid'],'id'=>$var['pid']))->find();
				$tgcount = $product_groupon_model->where(array('token' => session('token'),'cid'=>$var['cid'],'paid'=>'1','code'=>$var['code']))->count();
				$untgcount = $product_groupon_model->where(array('token' => session('token'),'cid'=>$var['cid'],'paid'=>'0','code'=>$var['code']))->count();
				$tgs = $product_groupon_model->where(array('token' => session('token'),'cid'=>$var['cid'],'code'=>$var['code']))->count();
				$orders[$k]['name'] = $userinfo['wechaname'];
				$orders[$k]['title'] = $product['name'];
				$orders[$k]['tgnum'] = $product['tgnum'];
				$orders[$k]['tgend'] = $product['tgend'];
				$orders[$k]['paidnum'] = $tgcount;
				$orders[$k]['unpaidnum'] = $untgcount;
				$orders[$k]['tgs'] = $tgs;
				$product['tend'] = $now-$var['addtime'];
				$orders[$k]['tend'] = round($product['tend']/86400,2);
				$orders[$k]['endtime'] = $var['addtime']+($product['tgend']*86400);
			}
		//dump($count);
		//die;
		$this->assign('orders', $orders);
		$this->assign('count', $count);
		$this->assign('page', $show);
		$this->display();
	}
	
	/**
	 *商城团购订单
	 */
	public function grouponorders()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$product_cart_model = M('New_product_cart');
		$where = array('token' => $this->_session('token'), 'groupon' => 1, 'cid' => $cid);
		if (IS_POST) {
			if ($_POST['token'] != $this->_session('token')) {
				exit();
			}
			$key = $this->_post('searchkey');
			if ($key) {
				$where['truename|tel|orderid'] = array('like', "%$key%");
			} else {
				for ($i=0;$i<40;$i++){
					if (isset($_POST['id_'.$i])){
						$thiCartInfo=$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->find();
						if ($thiCartInfo['handled']){
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>0));
						} else {
							$product_cart_model->where(array('id'=>intval($_POST['id_'.$i])))->save(array('handled'=>1));
						}
					}
				}
				$this->success('操作成功',U('Storenew/grouponorders',array('token' => session('token'))));
				die;
			}
		}
		if (isset($_GET['handled'])) {
			$where['handled'] = intval($_GET['handled']);
		}
		
		$where2 = array('token' => $this->_session('token'), 'groupon' => 1, 'dining' => 0, 'cid' => $cid);
		$sent = isset($_GET['sent']) ? $_GET['sent'] : '0';
		$paid = isset($_GET['paid']) ? $_GET['paid'] : '0';
		if($sent){
			$where2['sent'] = $sent;
		}
		if($paid){
			$where2['paid'] = $paid;
		}
		$count      = $product_cart_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_cart_model->where($where2)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$orders[$k]['name'] = $userinfo['wechaname'];
			}
		//dump($where2);
		//die;

		$where2['handled'] = 0;
		$unHandledCount = $product_cart_model->where($where2)->count();
		$this->assign('unhandledCount', $unHandledCount);
		$this->assign('orders', $orders);
		$this->assign('page', $show);
		$this->display();
	}
	
	/**
	 *商城团购参团详情
	 */
	public function grouponrule()
	{
		$cid = $this->_cid;
		if (C('zhongshuai')) {
			$company = M('Company')->where("`token`='{$this->token}' AND `isbranch`=0")->find();
			$cid = $company['id'];
		}
		$now=time();
		$codeid = $_GET['codeid'];
		$product_model = M('New_product');
		$product_groupon_model = M('New_product_groupon');
		$where2 = array('token' => $this->_session('token'), 'cid' => $cid, 'code' => $codeid);

		$count      = $product_groupon_model->where($where2)->count();
		$Page       = new Page($count,20);
		$show       = $Page->show();
		$orders		= $product_groupon_model->where($where2)->order('addtime ASC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
			foreach ($orders as $k => $var) {
				$userinfo= M('Userinfo')->where(array('token' => session('token'),'wecha_id'=>$var['wecha_id']))->field('wechaname')->find();
				$orders[$k]['name'] = $userinfo['wechaname'];
				$product= $product_model->where(array('token' => session('token'),'cid'=>$var['cid'],'id'=>$var['pid']))->find();
				$orders[$k]['title'] = $product['name'];
				$orders[$k]['tgnum'] = $product['tgnum'];
				$orders[$k]['tgend'] = $product['tgend'];
				$product['tend'] = $now-$var['addtime'];
				$orders[$k]['tend'] = round($product['tend']/86400,2);
				$orders[$k]['endtime'] = $var['addtime']+($product['tgend']*86400);
			}
		//dump($orders);
		//die;
		$tgcount = $product_groupon_model->where(array('token' => session('token'),'cid'=>$var['cid'],'paid'=>'1','code'=>$codeid))->count();
		$this->assign('orders', $orders);
		$this->assign('tgcount', $tgcount);
		$this->assign('page', $show);
		$this->display();
	}
	
	/**
	 * 团购商品列表
	 */
	public function grouponproduct() 
	{		
		$catid = intval($_GET['catid']);
		$product_model = M('New_product');
		$product_cat_model = M('New_product_cat');
		$where = array('token' => session('token'), 'is_tg' => 1, 'dining' => 0, 'cid' => $this->_cid);
		if ($catid){
			$where['catid'] = $catid;
		}
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }

            $map['token'] = $this->get('token'); 
            $map['name|intro|keyword'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
			foreach ($list as $key=>$val) {
				$cat = $product_cat_model->where(array('token' => $this->token, 'cid' => $this->_cid, 'id'=>$val['catid']))->find();
				$list[$key]['catname'] = $cat['name'];
			}
        }
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('isProductPage',1);
		$this->assign('catid', $catid);
		$this->display();		
	}
	
	/**
	 * 团购商品订单详情
	 */
	public function grouponordersInfo()
	{
		$this->product_model = M('New_product');
		$this->product_cat_model = M('New_product_cat');
		$product_cart_model = M('New_product_cart');
		$thisOrder = $product_cart_model->where(array('id'=>intval($_GET['id']),'groupon'=>'1','cid'=>$this->_cid,'token' => $this->token))->find();
		//检查权限
		if (strtolower($thisOrder['token'])!=strtolower($this->_session('token'))){
			exit();
		}
		if (IS_POST){
			$now = time();
			if (intval($_POST['sent'])){
				$_POST['handled'] = 1;
				$_POST['handledtime'] = $now;
			}
			$company = M('Company')->where(array('token' => $this->token, 'isbranch' => 0))->find();
			$save = array('sent'=>intval($_POST['sent']),'logistics'=>$_POST['logistics'],'logisticsid'=>$_POST['logisticsid'],'handled'=>1,'handledtime'=>$now);
			//var_dump($_POST);
			//die;
			if ($company['id'] != $this->_cid) {
				empty($thisOrder['paid']) && $save['paid'] = intval($_POST['paid']);
			}
			$product_cart_model->where(array('id'=>$thisOrder['id'],'groupon'=>'1','cid'=>$this->_cid,'token' => $this->token))->save($save);
			
			//TODO 发货的短信提醒
			$company = D('Company')->where(array('token' => $thisOrder['token'], 'id' => $thisOrder['cid']))->find();
			if ($_POST['sent']==1) {
				$userInfo = D('Userinfo')->where(array('token' => $thisOrder['token'], 'wecha_id' => $thisOrder['wecha_id']))->find();
				//Sms::sendSms($this->token, "您在{$company['name']}商城购买的商品，商家已经给您发货了，请您注意查收", $userInfo['tel']);
				$myhost = $_SERVER['HTTP_HOST'];
				$model = new templateNews();
				$model->sendTempMsg('OPENTM200565259', array('href' => 'http://'.$myhost.'/index.php?g=Wap&m=Storenew&a=myjingpaiDetail&token='.$thisOrder['token'].'&cartid='.$thisOrder['id'].'&wecha_id='.$thisOrder['wecha_id'].'&cid='.$thisOrder['cid'].'', 'wecha_id' => $thisOrder['wecha_id'], 'first' => '订单发货提醒', 'keyword1' => ''.$thisOrder['orderid'].'', 'keyword2' => ''.$_POST['logistics'].'', 'keyword3' => ''.$_POST['logisticsid'].'', 'remark' => '你好，'.$thisOrder['truename'].'，你参与团购的商品已经发货，点击查看完整的物流信息 。如有问题请致电'.$company['tel'].'，'.$company['name'].'将在第一时间为您服务！'));
			}

			$this->success('修改成功',U('Storenew/grouponordersInfo',array('token'=>session('token'),'id'=>$thisOrder['id'])));
		}else {

			$product_cart_model = M('New_product_cart_list');
			$thisTable=$product_cart_model->where(array('cartid'=>$thisOrder['id']))->find();

			$this->assign('thisOrder',$thisOrder);

			$list = M('New_product')->where(array('id'=>$thisTable['productid'],'token'=>session('token'),'cid'=>$thisTable['cid']))->find();
			//dump($list);
			//die;
			$this->assign('o', $list);
			$this->assign('totalFee',$totalFee);
			$this->assign('totalCount',$totalCount);
			$this->assign('mailprice',$data[2]);			
			$this->display();
		}
	}
	
	/**
	 * 团购失败退款申请
	 */
	public function groupontuikuan()
	{
		$tuikuan = intval($_GET['tuikuan']);
		$data = M('New_product_groupon');
		$where = array('token' => $this->token, 'cid' => $this->_cid);
		if($tuikuan == 2){
			$where['tuikuan'] = 2;
		}else{
			$where['tuikuan'] = 1;
		}
		
		$count      = $data->where($where)->count();
		$Page       = new Page($count, 20);
		$show       = $Page->show();
		$list = $data->where($where)->order("id DESC")->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $row=>$val) {
			$product = M('New_product')->where(array('id'=>$val['pid'],'token'=>session('token'),'cid'=>$this->_cid))->find();
			$twuser = D('Userinfo')->where(array('wecha_id' => $val['wecha_id']))->find();
			$list[$row]['truename'] = $twuser['truename'];
			$list[$row]['name'] = $product['name'];
			
		}
		//dump($where);
		//die;
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->display();
	}
	
	
	/**
	 * 团购失败退款申请，确认
	 */
	public function groupontuikuanstatus()
	{
		if($this->_get('token')!= session('token')){$this->error('非法操作');}
        $id = $this->_get('id');
        $codeid = $this->_get('codeid');
        if (IS_GET) {
        	if ($remove = M('New_product_groupon')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'code' => $codeid,'status'=>2,'tuikuan' => 1))->find()) {
				$wecha = M('Userinfo')->where(array('token' => session('token'),'wecha_id' => $remove['wecha_id']))->find();
				if(empty($wecha)){
					$this->error('系统出错，请联系管理员');
				}
				if($wecha['balance'] < $remove['price']){
					$this->error('用户余额不足'.$remove['price'].'元，余额只有'.$wecha['balance'].'元');
				}
				D('New_product_groupon')->where(array('id' => $id,'token' => session('token'),'cid' => $this->_cid,'code' => $codeid,'status'=>2,'tuikuan' => 1))->save(array('tuikuan' => 2));
				D('Userinfo')->where(array('token' => session('token'),'wecha_id' => $remove['wecha_id']))->setDec('balance',$remove['price']);
				$this->success('操作成功', U('Storenew/groupontuikuan',array('token' => session('token'), 'cid' => $this->_cid)));
        	}
        }
	}
	
	/*
	测试用的
	*/
	public function ceshi()
	{
		$order = M('New_product_cart')->where(array('token' => $this->token, 'cid' => $this->_cid, 'id'=>156))->find();
		//$userInfo = D('Userinfo')->where(array('token' => $order['token'], 'wecha_id' => $order['wecha_id']))->find();
		
		//$order['info'] = '22|1|1';
		//$carts = unserialize($order['info']);
		$carts = serialize(array('22'=> '1|1000'));
		
		
		//dump($order['info']);
		dump($order['info']);
		dump($carts);
		die;
			
		
		$this->display();

	}
	
	/*
	物流公司查询(wecha_id)
	*/
	function Getwuliu($wuliu){
		//加载快递公司
		$typeCom = $wuliu["logistics"];
		$typeNu = $wuliu["logisticsid"];
		include_once("ickd_companies.php");
		//include 'ickd_companies.php';
		$id='111059';//请将123456替换成您在http://www.ickd.cn/api/reg.html申请到的id
		$secret='96d81aef3d6a7919d18b24cea85f78e1';//您在http://www.ickd.cn/api/reg.html申请到的secret
		$url ='http://api.ickd.cn/?id='.$id.'&secret='.$secret.'&com='.$typeCom.'&nu='.$typeNu.'&type=json&ord=desc&ver=2';
		
		//查询开始
		//优先使用curl模式发送数据
		if (function_exists('curl_init') == 1){
		  $curl = curl_init();
		  curl_setopt ($curl, CURLOPT_URL, $url);
		  curl_setopt ($curl, CURLOPT_HEADER,0);
		  curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
		  curl_setopt ($curl, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		  curl_setopt ($curl, CURLOPT_TIMEOUT,5);
		  $content = curl_exec($curl);
		  curl_close ($curl);
		}else{
		  include("snoopy.php");
		  $snoopy = new snoopy();
		  $snoopy->referer = $_SERVER['HTTP_REFERER'];
		  $snoopy->fetch($url);
		  $content = $snoopy->results;
		}
		$content=mb_convert_encoding($content,'utf8','gbk');
		$stat=json_decode($content,true);
		$wuliustat = $stat['status'];
		//dump($content);
		//die;
		return $wuliustat;
	}
	
	public function getOpenid(){
		$name 	= $this->_get('name','trim');
		$where 	= array('token'=>$this->token,'wechaname'=>$name);
		$openid = M('Userinfo')->where($where)->getField('wecha_id');

		if($openid){
			echo json_encode(array('error'=>0,'openid'=>$openid));
		}else{
			echo json_encode(array('error'=>1,'info'=>'没有找到粉丝'));
		}
	}
	
	/**
	 * 黑名单列表
	 */
	public function lockuser(){
		$cid = $this->_cid;
		$product_model = M('New_product_lockuser');
		$where = array('token' => session('token'), 'cid' => $this->_cid);
        if(IS_POST){
            $key = $this->_post('searchkey');
            if(empty($key)){
                $this->error("关键词不能为空");
            }
            $map['token'] = $this->get('token'); 
            $map['wecha_id|note'] = array('like',"%$key%"); 
            $list = $product_model->where($map)->select(); 
            $count      = $product_model->where($map)->count();       
            $Page       = new Page($count,20);
        	$show       = $Page->show();
        } else {
        	$count      = $product_model->where($where)->count();
        	$Page       = new Page($count,20);
        	$show       = $Page->show();
        	$list = $product_model->where($where)->order('id desc')->limit($Page->firstRow.','.$Page->listRows)->select();
				foreach($list as $key=>$val){
				$user = M('Userinfo')->where(array('token' => session('token'), 'wecha_id'=>$val['wecha_id']))->find();
				$list[$key]['truename'] = $user['truename'] ? $user['truename'] : '匿名';
				$list[$key]['tel'] = $user['tel'] ? $user['tel'] : '无';
				$list[$key]['twid'] = $user['twid'];
				}
        }
		
		$this->assign('page',$show);		
		$this->assign('list',$list);
		$this->assign('cid', $cid);
		$this->display();
	}
	
	/**
	 * 添加黑名单
	 */
	public function lockset(){
		$cid 		= $this->_cid;
		$id 		= $this->_get('id','intval');
		$where 		= array('token'=>$this->token,'id'=>$id);
		$product_lockuser   = M('New_product_lockuser')->where($where)->find();

		if(IS_POST){
			$_POST['token'] 	= $this->token;
			$_POST['time'] 	= time(); 
			//dump($_POST);
			//die;
			if(D('New_product_lockuser')->create()){
				if($product_lockuser){
					$up_where   = array('token'=>$this->token,'id'=>$this->_post('id','intval'));
					D('New_product_lockuser')->where($up_where)->save($_POST);
					$this->success('修改成功',U('Storenew/news',array('token'=>$this->token,'cid'=>$cid)));
				}else{
					$id     = D('New_product_lockuser')->add($_POST);
					//dump($_POST);
					//die;
					if($id){
						$this->success('添加成功',U('Storenew/lockuser',array('token'=>$this->token,'cid'=>$cid)));
					}
				}	
				
			}else{			
                $this->error(D('New_product_lockuser')->getError());
            }
			
		}else{
			$this->assign('set',$product_lockuser);
			$this->display();
		}
	}
	
	/**
	 * 删竞黑名单
	 */
	public function dellockuser(){
		$product_model = M('New_product_lockuser');
		if($this->_get('token')!=session('token')){$this->error('非法操作');}
		$id = $this->_get('id');
        if (IS_GET) {                              
            $where = array('token'=>session('token'), 'cid' => $this->_cid , 'id'=>$id);
            $check = $product_model->where($where)->find();
			//dump($check);
			//die;
            if ($check == false) $this->error('非法操作');
            $back = $product_model->where($where)->delete();
            if ($back == true) {
            	$count = $product_model->where(array('cid' => $check['cid']))->count();
                $this->success('操作成功', U('Storenew/lockuser', array('token' => session('token'), 'cid'=>$cid)));
            } else {
				$this->error('服务器繁忙,请稍后再试', U('Storenew/lockuser', array('token'=>session('token'),'cid'=>$cid)));
            }
        }        
	}
	
	/*
	导出在线消费订单数据
	*/
	public function export(){
		set_time_limit(300); 
		header("Content-Type: text/html; charset=utf-8");
		header("Content-type:application/vnd.ms-execl");
		header("Content-Disposition:filename=ordersRecord.xls");
		
		$arr = array(
			array('en'=>'orderid','cn'=>'订单号'),
			array('en'=>'transactionid','cn'=>'第三方订单号'),
			array('en'=>'paytype','cn'=>'支付类型'),
			array('en'=>'time','cn'=>'订单创建时间'),
			array('en'=>'price','cn'=>'金额'),
			array('en'=>'paytime','cn'=>'支付时间'),
			array('en'=>'paid','cn'=>'支付状态'),
			array('en'=>'truename','cn'=>'付款人'),
			array('en'=>'tel','cn'=>'手机号'),
		);
		
		$i = 0;
		$fieldCount = count($arr);
		$s = 0;
		//thead
		foreach ($arr as $f){
			if ($s<$fieldCount-1){
				echo iconv('utf-8','gbk',$f['cn'])."\t";
			}else {
				echo iconv('utf-8','gbk',$f['cn'])."\n";
			}
			$s++;
		}
		//data
		$db = M('New_product_cart');

		$cid 	= $this->_get('cid','intval');
		$token 		= $this->_get('token','trim');
		$orderid 	= $this->_get('orderid','trim');
		$sent 	= $this->_get('sent','trim');
		$paid 	= $this->_get('paid','trim');
		$paytype 	= $this->_get('paytype','trim');
		
		$where = array('token'=>$this->token);
		
		if($cid){
			$where['cid'] 	= $cid;
		}

		if($orderid){
			$where['orderid'] 		= $orderid;
		}
		
		if($paid){
			$where['paid'] 		= $paid;
		}
		
		if($sent){
			$where['sent'] 		= $sent;
		}
		
		if($paytype){
			$where['paytype'] 		= $paytype;
		}


		$count		= $db->where($where)->count();
		$page_size 	= 500;
		$page_num 	= ceil($count / $page_size);
		
		for($i=0; $i<$page_num; $i++){
		    $start 		= $i*$page_size;
		    $limit 		= $start.','.$page_size;
		    $sns 		= $db->where($where)->order('id DESC')->limit($limit)->select();
			//dump($sns);
			//die;
			if($sns){
				foreach ($sns as $sn){
					$number 	= M('Userinfo')->where(array('token'=>$this->token,'wecha_id'=>$sn['wecha_id']))->getField('truename');
					$sn['truename']	= $number;
					$j = 0;
					foreach ($arr as $field){
						$fieldValue = $sn[$field['en']];
						switch($field['en']){
							case 'orderid':
								$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
								break;
								
							case 'transactionid':
								if($fieldValue != ''){
									$fieldValue = iconv('utf-8','gbk',"单号".$fieldValue);
								}
								
								break;
								
							case 'time':
								if ($fieldValue){
									$fieldValue=iconv('utf-8','gbk',date('Y年m月d日 H时i分s秒',$fieldValue));
									
								}else {
									$fieldValue='';
								}
								break;
							case 'paytype':
								switch($fieldValue){
									case 'CardPay':
										$fieldValue = iconv('utf-8','gbk','会员卡');
										break;
									case 'weixin':
										$fieldValue = iconv('utf-8','gbk','微信支付');
										break;	
									case 'alipay':
										$fieldValue = iconv('utf-8','gbk','支付宝');
										break;
									case 'bankchina':
										$fieldValue = iconv('utf-8','gbk','网银在线');
										break;
								}
								break;

							case 'paid':
								if($fieldValue == 1){
									$fieldValue = iconv('utf-8','gbk','交易成功');
								}else{
									$fieldValue = iconv('utf-8','gbk','未付款');
								}
								break;
							case 'truename':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;
							case 'tel':
								$fieldValue = iconv('utf-8','gbk',$fieldValue);
								break;
				
							default:
								break;
						}
						
						if ($j<$fieldCount-1){
							echo $fieldValue."\t";
						}else {
							echo $fieldValue."\n";
						}
						$j++;
					}
					$i++;
				}
					
			}
			usleep(100); //导出停顿
		}
		exit();
	}
	
}
?>