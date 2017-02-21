<?php $app_require=array(
	'form.product',
	'js.lightbox',
	'js.tinymce',
	'lib.twitter',
	'php.product'
);
require('../init.php');
$edit_product=new edit_product();
$edit_product->process();
$h1=$edit_product->product->name;
$breadcrumb=array(
	'products'=>'Products',
	$edit_product->product->name
);
$buttons=array(
	array(
		'icon'	=>'eye',
		'link'	=>'../p/'.$edit_product->product->id.'-'.$edit_product->product->slug,
		'target'=>'blank',
		'title'	=>'View Product'
	)
);
require('header.php');?>
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
<?php $app->get_messages(); ?>
<div class="card card-block">
	<?php $edit_product->get_form(); ?>
</div>
<?php require('footer.php');