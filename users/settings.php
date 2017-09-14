<?php $app_require=array(
	'form.settings'
);
require('../init.php');
$settings=new settings();
$settings->process();
$h1='Settings';
$breadcrumb=array(
	'Admin',
	'Settings'
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $settings->get_form(); ?>
</div>
<?php require('footer.php');