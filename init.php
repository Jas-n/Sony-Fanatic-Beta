<?php # Version 16
$render_start=microtime(true);
if(basename($_SERVER['PHP_SELF'])==basename(__FILE__)){
	header('Location: /');
	exit;
}
# If the site is not in a root directory
$document_root=__DIR__;
define("ROOT",$document_root.'/');
# Set PHP Variables
ini_set('memory_limit','256M');
ini_set('error_log',ROOT.'error_log.txt');
session_start();
if(isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)){
	session_unset();
	session_destroy();
}
$_SESSION['last_activity']=time();
/* Append new query string to _GET */
$_get=explode('?',$_SERVER['REQUEST_URI']);
parse_str($_get[1],$_get);
$_GET=array_merge($_GET,$_get);
if(!in_array(basename(__FILE__,'.php'),array('notifications'))){
	$_SESSION['history'][]=$_SERVER['SCRIPT_FILENAME'].'?'.http_build_query($_GET);
	$_SESSION['history']=array_slice($_SESSION['history'],-10,10,true);
}
/* Global */
include_once(ROOT.'functions/core.php');
if(is_file(ROOT.'m.txt') && !in_array(basename($_SERVER['PHP_SELF'],'.php'),array('doupdate','run','finalise_updates'))){
	header('Location: /users/maintenance');
	exit;
}
$dir=get_dir();
if($dir && $dir!='ajax' && $dir!='api' && $dir!='CRONS' && !is_logged_in()){
	header('Location: /login?url='.urlencode($_SERVER['REQUEST_URI']));
	exit;
}
define('DATE',date('Y-m-d'));
define('TIME',date('H:i:s'));
define('DATE_TIME',DATE.' '.TIME);
# PHP compatibility functions
foreach(scandir(ROOT.'functions') as $dir){
	if(!in_array($dir,array('.','..')) && is_dir(ROOT.'functions/'.$dir)){
		foreach(scandir(ROOT.'functions/'.$dir) as $file){
			if(!in_array($file,array('.','..','index.php'))){
				include(ROOT.'functions/'.$dir.'/'.$file);
			}
		}
	}
}
# Auto-load Classes
spl_autoload_register(function($class){
	if(is_file(ROOT.'classes/'.$class.'.php')){
		require_once(ROOT.'classes/'.$class.'.php');
	}
});
$db=new database();
# Define Settings
foreach($db->query("SELECT `name`,`value` FROM `settings`") as $setting){
	define(strtoupper($setting['name']),nl2br($setting['value']));
}
define('SQL_LIMIT',' LIMIT '.(($_GET['page']?(($_GET['page']-1)*ITEMS_PER_PAGE):0).','.ITEMS_PER_PAGE).' ');
$app=new app;
$encryption=new encryption();
$user=new user();
$page=new page();
$products=new products;
if($app_require){
	$app->require=$app_require;
	$require=array_map('strtolower',$app->require);
	$form_included=false;
	foreach($app->require as $app_require){
		if(strpos($app_require,'lib.')===0){
			$name=substr($app_require,4);
			if(is_file(ROOT.'libraries/'.$name.'.php')){
				include_once(ROOT.'libraries/'.$name.'.php');
			}elseif(is_file(ROOT.'libraries/'.$name.'/'.$name.'.php')){
				include_once(ROOT.'libraries/'.$name.'/'.$name.'.php');
			}
		}elseif(strpos($app_require,'php.')===0){
			$name=substr($app_require,4);
			# If class exists		&& we don't want it auto-creating
			if(class_exists($name)	&& !in_array($name,array('form','product'))){
				$$name=new $name;
			}
		}elseif(strpos($app_require,'form.')===0){
			$name=substr($app_require,5);
			if(!$form_included){
				include_once(ROOT.'classes/form.php');
				$form_included=1;
			}
			include_once(ROOT.'forms/'.$name.'.php');
		}elseif(strpos($app_require,'db.')===0){
			foreach($db->query("SELECT `name`,`value` FROM `settings` WHERE `name` LIKE ?",substr($app_require,3).'%') as $setting){
				define(strtoupper($setting['name']),nl2br($setting['value']));
			}
		}
	}
}