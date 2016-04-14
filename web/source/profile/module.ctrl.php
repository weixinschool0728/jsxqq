<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

defined('IN_IA') or exit('Access Denied');
$dos = array('display', 'setting', 'shortcut', 'enable', 'form');
$do = !empty($_GPC['do']) ? $_GPC['do'] : 'display';
if($do != 'setting') {
	uni_user_permission_check('profile_module');
}
$modulelist = uni_modules(false);
if(empty($modulelist)) {
	message('没有可用功能.');
}
if($do == 'display') {
	$_W['page']['title'] = '模块列表 - 公众号选项';
	$setting = uni_setting($_W['uniacid'], array('shortcuts'));
	$shortcuts = $setting['shortcuts'];
	if(!empty($modulelist)) {
		foreach($modulelist as $i => &$module) {
			if (!empty($_W['setting']['permurls']['modules']) && !in_array($module['name'], $_W['setting']['permurls']['modules'])) {
				unset($modulelist[$i]);
				continue;
			}
			$module['shortcut'] = !empty($shortcuts[$module['name']]);
			$module['official'] = empty($module['issystem']) && (strexists($module['author'], 'WeEngine Team') || strexists($module['author'], '微擎团队'));
						if($module['issystem']) {
				$path = '../framework/builtin/' . $module['name'];
			} else {
				$path = '../addons/' . $module['name'];
			}
			$preview = $path . '/preview-custom.jpg';
			if(!file_exists($preview)) {
				$preview = $path . '/preview.jpg';
			}
			$module['preview'] = $preview;
		}
		unset($module);
	}
	template('profile/module');
	exit;
}

if($do == 'setting') {
	$name = $_GPC['m'];
	$module = $modulelist[$name];
	if(empty($module)) {
		message('抱歉，你操作的模块不能被访问！');
	}
	if(!uni_user_module_permission_check($name.'_settings', $name)) {
		message('您没有权限进行该操作');
	}
	define('CRUMBS_NAV', 1);
	$ptr_title = '参数设置';
	$module_types = module_types();
	
	$config = $module['config'];
	if ($module['settings'] == 2) {
		if (empty($_W['setting']['site']['key']) || empty($_W['setting']['site']['token'])) {
			message('站点未注册，请先注册站点。', url('cloud/profile'), 'info');
		}
		load()->model('cloud');
		$iframe = cloud_module_setting_prepare($module, 'setting');
		template('profile/module_setting');
		exit();
	}
	$obj = WeUtility::createModule($module['name']);
	$obj->settingsDisplay($config);
	exit();
}

if($do == 'shortcut') {
	$name = $_GPC['m'];
	$module = $modulelist[$name];
	if(empty($module)) {
		message('抱歉，你操作的模块不能被访问！');
	}
	$setting = uni_setting($_W['uniacid'], array('shortcuts'));
	$shortcuts = $setting['shortcuts'];
	if(!is_array($shortcuts)) {
		$shortcuts = array();
	}
	if($_GPC['shortcut'] == '1') {
		$shortcut = array();
		$shortcut['name'] = $module['name'];
		$shortcut['link'] = url("home/welcome/ext", array('m' => $module['name']));;
		$shortcuts[$module['name']] = $shortcut;
	} else {
		unset($shortcuts[$module['name']]);
	}
	$record = array();
	$record['shortcuts'] = iserializer($shortcuts);
	if(pdo_update('uni_settings', $record, array('uniacid' => $_W['uniacid'])) !== false) {
		cache_delete("unisetting:{$_W['uniacid']}");
		message('模块操作成功！', referer(), 'success');
	}
	exit();
}

if($do == 'enable') {
	$name = $_GPC['m'];
	$module = $modulelist[$name];
	if(empty($module)) {
		message('抱歉，你操作的模块不能被访问！');
	}
	pdo_update('uni_account_modules', array(
		'enabled' => empty($_GPC['enabled']) ? 0 : 1,
	), array(
		'module' => $name,
		'uniacid' => $_W['uniacid']
	));
	cache_build_account_modules();
	message('模块操作成功！', referer(), 'success');
}
