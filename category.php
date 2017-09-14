<?php $app_require=array();
include('init.php');
include('header.php');
$category=$products->get_category($_GET['id']);?>
<h1><?=$category['name']?></h1>
<?php if($category['children']){ ?>
	<h2>Categories</h2>
	<section class="categories row">
		<?php foreach($category['children'] as $child){?>
			<div class="col-12 col-sm-6 col-md-4 category">
				<a class="meta" href="<?=$child['url']?>">
					<h3><?=$child['name']?></h3>
				</a>
			</div>
		<?php } ?>
	</section>
<?php }
if($category['products']['count']){?>
	<h2>Products</h2>
	<section class="products row">
		<?php foreach($category['products']['products'] as $product){?>
			<div class="col-12 col-sm-6 col-md-4 product" style="background-image:url(<?=$product['images']['medium'][0]?>)">
				<a class="meta" href="<?=$product['url']?>">
					<h3><?=$product['brand'].' '.$product['name']?></h3>
				</a>
			</div>
		<?php } ?>
	</section>
<?php }
include('footer.php');