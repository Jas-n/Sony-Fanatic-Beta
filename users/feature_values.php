<?php $app_require=array(
	'form.feature_values'
);
require('../init.php');
$feature_values=new feature_values;
$feature_values->process();
$h1=$feature_values->category['name'];
$small=$feature_values->option['name'];
$breadcrumb=array(
	'products'=>'Products',
	'feature_categories'=>'Feature Categories',
	'feature_options/'.$feature_values->category['id']=>$feature_values->category['name'],
	$feature_values->option['name']
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $feature_values->get_form(); ?>
</div>
<?php require('footer.php');