<?php $app_require=array(
	'form.feature_options'
);
require('../init.php');
$feature_options=new feature_options;
$feature_options->process();
$h1='Feature Categories';
$small=$feature_options->feature_category['name'];
$breadcrumb=array(
	'products'=>'Products',
	'feature_categories'=>'Feature Categories',
	$feature_options->feature_category['name']
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-block">
	<?php $feature_options->get_form(); ?>
</div>
<?php require('footer.php');