<?php $app_require=array(
	'form.item_user',
	'js.tinymce'
);
require('../init.php');
$item_user=new item_user($_GET['id']);
$item_user->process();
$usr=$user->get_user($_GET['id']);
$h1=$usr['name'];
$breadcrumb=array(
	'users'=>'Users',
	$usr['name']
);
include('header.php');
$app->get_messages();
$item_user->get_form();
include('footer.php');