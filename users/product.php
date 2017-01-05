<?php $app_require=array(
	'form.product',
	'js.lightbox',
	'js.tinymce',
	'php.product'
);
require('../init.php');
$edit_product=new edit_product();
$edit_product->process();
require('header.php');?>
<h1><?=$edit_product->product->name?></h1>
<ol class="breadcrumb">
	<li class="pull-right">
		<a class="btn btn-info" data-toggle="tooltip" href="../p/<?=$edit_product->product->id?>-<?=$edit_product->product->slug?>" target="_blank" title="View Product"><i class="fa fa-fw fa-eye"></i></a>
	</li>
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="products">Products</a></li>
	<li class="breadcrumb-item active"><?=$edit_product->product->name?></li>
</ol>
<div class="card card-block card-details">
	<div class="row">
		<div class="col-md-6">
			<p><strong class="tab-10">Brand</strong><?=$edit_product->product->brand?></p>
		</div>
		<div class="col-md-6">
			<p><strong class="tab-10">Added</strong><?=sql_datetime($edit_product->product->added)?></p>
			<p><strong class="tab-10">Updated</strong><?=sql_datetime($edit_product->product->updated)?></p>
		</div>
	</div>
</div>
<?php $app->get_messages();
$edit_product->get_form();
require('footer.php');