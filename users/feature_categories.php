<?php $app_require=array(
	'form.feature_categories'
);
require('../init.php');
$feature_categories=new feature_categories;
$feature_categories->process();
$h1='Feature Categories';
$breadcrumb=array(
	'products'=>'Products',
	'Feature Categories'
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-block">
	<?php $feature_categories->get_form(); ?>
</div>
<?php require('footer.php');