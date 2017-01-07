<?php $app_require=array('form.list_products');
require('../init.php');
$list_products=new list_products;
$list_products->process();
require('header.php');?>
<h1>Products</h1>
<ol class="breadcrumb">
	<li class="float-right">
		<a class="btn btn-secondary" data-toggle="tooltip" href="tags" title="Tags"><i class="fa fa-fw fa-tags"></i></a>
		<a class="btn btn-success" data-toggle="tooltip" href="add_product" title="Add Product"><i class="fa fa-fw fa-plus"></i></a>
	</li>
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item active">Products</li>
</ol>
<?php $app->get_messages();
$list_products->get_form();
require('footer.php');