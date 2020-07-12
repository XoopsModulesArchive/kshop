<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

class pluginManager
{
	var $pluginType;
	var $plugin;

	function CheckPlugins($type){
		global $xoopsDB;

		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins')." WHERE type='$type' ");
		$plugs = $xoopsDB->fetchArray($query);
		if (!$plugs){
			return false;
		} else {
			return true;
		}
	}

	function CheckAvailPlug($type){
		global $xoopsDB,$xoopsModule;

		$inst_plug=array();
		$i=0;
		$pyes=0;
		//Get all installed plugins
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins')." WHERE type='$type' ");
		while ($row = $xoopsDB->fetchArray($query))
		{
			$dir = $row['dir'];
			//Place all plugins name's in array
			$inst_plug[$i]=$dir;
			$i++;
		}
		//read all available plugins in specified directory
		$dir= opendir(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type);
		$loader= array();
		$q=1;
		while (false !== ($file = readdir($dir))) {
			if ($file!="." && $file!=".." && $file!="index.html"){
				//load lang file
				$langfile=checkLang($type,$file);
				include_once($langfile);
				include XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$file."/plugin_version.php";

				// Chech array if current plugin is in it.
				if (in_array($plugin['dirname'],$inst_plug)) continue;
				$loader[$q]['name']= $plugin['name'];
				$loader[$q]['version']= $plugin['version'];
				$loader[$q]['description']= $plugin['description'];
				$loader[$q]['click']= "sss";
				$loader[$q]['dirname']= $plugin['dirname'];
				$loader[$q]['type']= $type;
				$q++;
			}
		}
		closedir($dir);
		return $loader;
	}

	function plugTloader($type){
		global $xoopsTpl,$xoopsModule;

		$plugs=$this->InstalledPlugs($type);

		$q=1;
		$tem=array();
		foreach ($plugs as $plug){
			include XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug['dir'].'/process.php';
			$func="chkout_".$plug['dir'];

			$ldplug=$func();
			$tem[$q]['template'] = $xoopsTpl->fetch(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug['dir'].'/templates/'.$plug['checkout_template']);
			$q++;
		}
		return $tem;
	}

	function InstalledPlugs($type){
		global $xoopsDB,$xoopsTpl,$xoopsModule;


		$loader= array();
		$q=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins')." WHERE type='$type' ");
		while ($row = $xoopsDB->fetchArray($query))
		{
			if (empty($row)){
				$loader[$q]['name']=KS_NNINSTLD;
				return $loader;
			}
			$loader[$q]['id']=$row['id'];
			$loader[$q]['dir']=$row['dir'];
			$loader[$q]['type']=$row['type'];

			//load lang file
			$langfile=checkLang($type,$row['dir']);
			include_once($langfile);

			//Load plugin_version file
			include XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$row['dir'].'/plugin_version.php';

			$loader[$q]['name']=$plugin['name'];
			$loader[$q]['version']=$plugin['version'];
			$loader[$q]['description']=$plugin['description'];
			if (isset($plugin['checkout_template'])){
				$loader[$q]['checkout_template']=$plugin['checkout_template'];
			}
			$q++;
		}

		return $loader;
	}


	function UninstPlugin($plugid){
		global $xoopsDB,$xoopsModule;

		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins')." WHERE id='$plugid' ");
		$row = $xoopsDB->fetchArray($query);
		$plug_dir = $row['dir'];
		$type = $row['type'];

		$plug_ver_path= XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug_dir."/plugin_version.php";

		//load lang file
		$langfile=checkLang($type,$plug_dir);
		include_once($langfile);

		if (file_exists($plug_ver_path)){
			include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug_dir."/plugin_version.php";
		} else {
			echo "error";
		}


		$query = "DELETE FROM ".$xoopsDB->prefix("kshop_plugins")." WHERE id='$plugid' ";
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			$this->setErrors(KS_P_ERROR4);
			return;
		}


		for ($i=0;;$i++){
			if (!isset($plugin['tables'][$i])) break;
			$table=$plugin['tables'][$i];
			$result = $xoopsDB->queryF("DROP TABLE ".$xoopsDB->prefix($table)." ");
			if(!$result) {
				echo "error: ". $result;
			}
		}

	}



	function pluginInstaller($type,$plug_name) {
		global $xoopsDB,$xoopsModule;

		//load lang file
		$langfile=checkLang($type,$plug_name);
		include_once($langfile);


		$plug_ver_path= XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug_name."/plugin_version.php";

		if (file_exists($plug_ver_path)){
			include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug_name."/plugin_version.php";
		} else {
			return;
		}

		$sqlfile =$plugin['sqlfile'];
		$sqlfile_path=XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/'.$type.'/'.$plug_name."/".$sqlfile;
		$fileread=file_get_contents($sqlfile_path);
		$filesplit=split(";",$fileread);

		$count=count($filesplit);
		$count=$count-1;

		for ($i=0; $i<$count; $i++)
		{
			$query_split=$filesplit[$i];
			$query=ereg_replace('kshop',$xoopsDB->prefix('kshop'),$query_split);
			$res=$xoopsDB->queryF($query);
			if(!$res) {
				echo $query;
			}
		}

		//Sanitize plugin name and description before inserting into DB.
		$myts = myTextSanitizer::getInstance();
		$plug_name = $myts->addslashes($plugin['name']);
		$plug_desc = $myts->addslashes($plugin['description']);

		$query = "Insert into ".$xoopsDB->prefix("kshop_plugins")." (dir, type) values ('{$plugin['dirname']}','{$plugin['type']}')";
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo $query;
		}
	}

}

?>