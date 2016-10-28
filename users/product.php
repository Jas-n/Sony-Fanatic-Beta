<?php $app_require=array(
	'form.product',
	'js.lightbox',
	'js.tinymce',
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
if($images=glob(ROOT.'uploads/products/'.$edit_product->product->brand_slug.'/'.$edit_product->product->id.'/*_thumb.png')){?>
	<h2>Media</h2>
	<div class="product_images">
		<?php foreach($images as $image){
			$image=str_replace(ROOT,'',$image);?>
			<a href="/<?=str_replace('_thumb','_full',$image)?>" target="_blank"><img class="img-thumbnail" src="/<?=$image?>"></a>
		<?php }?>
	</div>
<?php }
require('footer.php');