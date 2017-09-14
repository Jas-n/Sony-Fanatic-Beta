<?php $app_require=array(
	'form.list_users',
	'php.users'
);
require('../init.php');
$h1='Users';
$breadcrumb[]='Users';
$buttons=array(
	array(
		'icon'=>'plus',
		'link'=>'add_user',
		'title'=>'Add User'
	)
);
require('header.php');
$list_users=new list_users();
$list_users->process();
$app->get_messages(); ?>
<div class="card card-body">
	<?php $list_users->get_form(); ?>
</div>
<?php require('footer.php');