<?php $app_require=array(
	'form.add_product',
	'js.tinymce'
);
require('../init.php');
$add_product=new add_product();
$add_product->process();
$h1='Add Product';
$breadcrumb=array(
	'products'=>'Products',
	'Add Product'
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $add_product->get_form(); ?>
</div>
<?php require('footer.php');