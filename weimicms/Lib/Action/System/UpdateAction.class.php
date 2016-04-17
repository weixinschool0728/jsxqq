<?php
class UpdateAction extends BackAction{
    public function index(){
        $version = './Conf/version.php';
        $ver = include($version);
        $ver = $ver['ver'];
        //$ver = substr($ver,-3);
        $updatehost = 'http://update.weimicms.com/updateeweweimi11.php';
        $lastver = file_get_contents(($updatehost . '?a=check&v=') . $ver);
        if($lastver !== $ver){
            $updateinfo = ('<p class="red">最新版本为：微米cms微信营销系统v ' . $lastver) . '</p><span>
		   <a href="javascript:if(confirm(\'升级前,请确认已经做好数据库和程序备份!\'))location=\'./index.php?g=System&m=Update&a=updatenow\'">点击这里在线升级</a>
           </span>';
            $chanageinfo = file_get_contents(($updatehost . '?a=chanage&v=') . $lastver);
        }else{
            $updateinfo = ('<p class="red">最新版本为：微米cms微信营销系统v ' . $lastver) . '</p><span>已经是最新系统 不需要升级</span>';
        }
        $this -> assign('updateinfo', $updateinfo);
        $this -> assign('chanageinfo', $chanageinfo);
        $this -> display();
    }
    public function updatenow(){
        include('Update.class.php');
        $version = './Conf/version.php';
        $ver = include($version);
        $ver = $ver['ver'];
        //$ver = substr($ver,-3);
        $hosturl = urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
        $updatehost = 'http://update.weimicms.com/updateeweweimi11.php';
        $updatehosturl = $updatehost . '?a=update&v=' . $ver . '&u=' . $hosturl;
        $updatenowinfo = getremotecontent($updatehosturl);
		//$updatenowinfo = file_get_contents($updatehosturl);
        if (strstr($updatenowinfo, 'zip')){
            $pathurl = $updatehost . '?a=down&f=' . $updatenowinfo;
            $updatedir = './Conf/logs/Temp/update';
            delDirAndFile($updatedir);
			mkdirs($updatedir);
			//下载补丁
            //if(getremotefile($pathurl,$updatenowinfo,$updatedir)){	
			$isgot = get_file($pathurl, $updatenowinfo, $updatedir);
			if($isgot){
				$updatezip = $updatedir . '/' . $updatenowinfo;
				//require 'pclzip.class.php'; 
				$thisfolder = new PclZip($updatezip);
				$isextract = $thisfolder->extract(PCLZIP_OPT_PATH, $updatedir);
				if ($isextract == 0) {  
					$updatenowinfo = "<font color=\"red\">解压更新包失败</font>";
					} 
				$archive = new PclZip($updatezip);
					$list = $archive->extract(PCLZIP_OPT_PATH, './', PCLZIP_OPT_REPLACE_NEWER); 
					
				if ($list == 0) {  
					$updatenowinfo = "<font color=\"red\">远程升级文件不存在.升级失败</font>";
					} 
				if(file_exists('./update/update.sql')) {					
					$sqlfile = './update/update.sql';
					$sql = file_get_contents($sqlfile);
					if($sql){
						$sql = str_replace("tp_", C('DB_PREFIX'), $sql);
						$Model = new Model();
						error_reporting(0);
						foreach(split(";[\r\n]+", $sql) as $v){
							@mysql_query($v);
						}              
					}
					@unlink('./update/update.sql');
					deldir($updatedir);		
					$updatenowinfo = "<font color=red>升级完成 {$sqlinfo}</font><span><a href=./index.php?g=System&m=Update>点击这里 查看是否还有升级包</a></span>";
				}
				$updatenowinfo = "<font color=red>升级完成 {$sqlinfo}</font><span><a href=./index.php?g=System&m=Update>点击这里 查看是否还有升级包</a></span>";
			}
			else{
				$updatenowinfo='<font color="red">查找不到更新包，请稍后再试！</font>';
			}
        }else{
			$updatenowinfo='<font color="red">'.$updatenowinfo.'-不完全更新</font>';
		}
        delDirAndFile($updatedir);
        $this -> assign('updatenowinfo', $updatenowinfo);
        $this -> display();
    }
}
?>