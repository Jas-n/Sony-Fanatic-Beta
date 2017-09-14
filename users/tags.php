<?php $app_require=array(
	'form.tags',
	'php.users'
);
require('../init.php');
$tags=new tags();
$tags->process();
$h1='Tags';
$breadcrumb=array(
	'products'=>'Products',
	'Tags'
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $tags->get_form(); ?>
</div>
<?php require('footer.php');