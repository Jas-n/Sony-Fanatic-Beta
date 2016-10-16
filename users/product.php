<?php $app_require=array(
	'form.product',
	'php.product',
	'php.products'
);
require('../init.php');
$edit_product=new edit_product();
$edit_product->process();
require('header.php');?>
<a class="btn btn-info pull-right" href="../p/<?=$edit_product->product->id?>-<?=$edit_product->product->slug?>">View</a>
<h1><?=$edit_product->product->model?></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="products">Products</a></li>
	<li class="breadcrumb-item active"><?=$edit_product->product->model?></li>
</ol>
<?php $app->get_messages();
$edit_product->get_form();
require('footer.php');