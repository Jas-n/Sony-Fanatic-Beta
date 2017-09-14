<?php $app_require=array();
include('init.php');
$brand=$products->get_brand($_GET['id']);
$app->page_title=$brand['brand'];
include('header.php');?>
<h1><?=$brand['brand']?></h1>
<?php if($brand['children']){ ?>
	<h2>Brands</h2>
	<section class="brands row">
		<?php foreach($brand['children'] as $child){?>
			<div class="col-12 col-sm-6 col-md-4 product">
				<a class="meta" href="<?=$child['url']?>">
					<h3><?=$child['name']?></h3>
				</a>
			</div>
		<?php } ?>
	</section>
<?php }
if($brand['product_count']){?>
	<h2>Products</h2>
	<section class="products row">
		<?php foreach($brand['products'] as $product){?>
			<div class="col-12 col-sm-6 col-md-4 product" style="background-image:url(<?=$product['images']['medium'][0]?>)">
				<a class="meta" href="<?=$product['url']?>">
					<h3><?=$product['brand'].' '.$product['name']?></h3>
				</a>
			</div>
		<?php } ?>
	</section>
<?php }
include('footer.php');