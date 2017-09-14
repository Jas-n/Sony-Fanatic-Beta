<?php $app_require=array('form.list_products');
require('../init.php');
$list_products=new list_products;
$list_products->process();
$h1='Products';
$breadcrumb=array(
	'Products'
);
$buttons=array(
	array(
		'icon'=>'tags',
		'link'=>'tags',
		'title'=>'Tags'
	),
	array(
		'icon'=>'plus',
		'link'=>'add_product',
		'title'=>'Add Product'
	)
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $list_products->get_form(); ?>
</div>
<?php require('footer.php');