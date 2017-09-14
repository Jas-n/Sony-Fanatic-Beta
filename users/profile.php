<?php $app_require=array(
	'form.profile'
);
require('../init.php');
$profile=new profile();
$profile->process();
$h1='Profile';
$breadcrumb[]='Profile';
include('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $profile->get_form(); ?>
</div>
<?php include('footer.php');