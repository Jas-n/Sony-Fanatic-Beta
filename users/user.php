<?php $app_require=array(
	'form.item_user',
	'js.tinymce'
);
require('../init.php');
$item_user=new item_user($_GET['id']);
$item_user->process();
$usr=$user->get_user($_GET['id']);
include('header.php');?>
<div class="page-header">
	<h1><?=$usr['first_name']?> <?=$usr['last_name']?></h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
		<li class="breadcrumb-item"><a href="./users">Users</a></li>
		<li class="breadcrumb-item active"><?=$usr['name']?></li>
	</ol>
</div>
<?php $app->get_messages();
$item_user->get_form();
include('footer.php');