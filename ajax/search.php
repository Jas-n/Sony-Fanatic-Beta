<?php if($_GET['method']=='stream'){
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	$stream=1;
}
if(!$_GET['term']){
	echo json_encode(array(
		'success'=>false,
		'results'=>"No search term"
	));
	exit;
}
$app_require=array(
	'php.clients',
	'php.users'
);
include_once('../init.php');
# Clients
if($page->has_feature_permission('clients')){
	if($data=Clients::search($_GET['term'])){
		if($stream){
			echo "data: ".json_encode($data)."\n\n";
			flush();
		}else{
			$dataout[]=$data;
		}
	}
}
# Users
if($page->has_feature_permission('users')){
	if($data=Users::search($_GET['term'])){
		if($stream){
			echo "data: ".json_encode($data)."\n\n";
			flush();
		}else{
			$dataout[]=$data;
		}
	}
}
# Notifications
if($page->has_feature_permission('content')){
	if($data=Notifications::search($_GET['term'])){
		if($stream){
			echo "data: ".json_encode($data)."\n\n";
			flush();
		}else{
			$dataout[]=$data;
		}
	}
}
# Settings
if($page->has_feature_permission('administration')){
	if($settings=$db->query("SELECT * FROM `settings` WHERE `name` LIKE ? ORDER BY `name` ASC","%".$_GET['term']."%")){
		$data=array();
		foreach($settings as $setting){
			$data[]=array(
				'Name'		=>$setting['name'],
				'Value'		=>(is_json($setting['value'])?'<pre>'.print_r(json_decode($setting['value'],1),1).'</pre>':$setting['value']),
				'Actions'	=>'<a class="btn btn-primary btn-sm" href="settings" title="Edit">Edit</a>'
			);
		}
		$data=array(
			'name'	=>'Settings',
			'slug'	=>'settings',
			'count'	=>sizeof($data),
			'data'	=>$data
		);
		if($stream){
			echo "data: ".json_encode($data)."\n\n";
			flush();
		}else{
			$dataout[]=$data;
		}
	}
}
# Output
if($stream){
	echo "data: ".json_encode(array('message'=>'Finished','status'=>'close'))."\n\n";
	flush();
}else{
	echo json_encode($dataout);
}