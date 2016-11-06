<?php $app_require[]='php.product';
include('init.php');
$product=new product($_GET['id']);
/*$product=array(
	'catalog'	=>array(
		'had'		=>mt_rand(1,100),
		'got'		=>mt_rand(1,100),
		'want'		=>mt_rand(1,100)
	),
	'social'	=>array(
		'email'		=>mt_rand(1,100),
		'facebook'	=>mt_rand(1,100),
		'print'		=>mt_rand(1,100),
		'twitter'	=>mt_rand(1,100)
	)
);*/
include('header.php');
#print_pre($product);?>
<div class="product_banner"<?=$product->banner?' style="background-image:url('.$product->banner.')"':''?>>
</div>
<div class="container product_content py-1">
	<div class="interactions catalog">
		<div class="btn-group" role="group" aria-label="My Catalog">
			<button type="button" class="btn btn-sm btn-secondary catalog_had" data-toggle="tooltip" data-placement="top" title="<?=$product->catalog['had']?> Others">Had It</button>
			<button type="button" class="btn btn-sm btn-secondary catalog_got true" data-toggle="tooltip" data-placement="top" title="You and <?=$product->catalog['got']?> Others">Got It</button>
			<button type="button" class="btn btn-sm btn-secondary catalog_want" data-toggle="tooltip" data-placement="top" title="<?=$product->catalog['want']?> Others">Want It</button>
		</div>
	</div>
	<h1 class="mb-0"><?=$product->name?></h1>
	<?php if($product->slogan){ ?>
		<p><em><?=$product->slogan?></em></p>
	<?php } ?>
	<div class="product_description">
		<?=$product->description?>
	</div>
</div>
<div class="container product_content">
	<div class="bg-primary row">
		<div class="interactions social col-md-12">
			<div class="btn-group" role="group" aria-label="Social">
				<button type="button" class="btn btn-sm btn-secondary facebook" data-toggle="tooltip" data-placement="top" title="<?=$product->social['facebook']?>"><span class="fa fa-fw fa-facebook"></span></button>
				<button type="button" class="btn btn-sm btn-secondary twitter" data-toggle="tooltip" data-placement="top" title="<?=$product->social['twitter']?>"><span class="fa fa-fw fa-twitter"></span></button>
				<button type="button" class="btn btn-sm btn-secondary email" data-toggle="tooltip" data-placement="top" title="<?=$product->social['email']?>"><span class="fa fa-fw fa-envelope"></span></button>
				<button type="button" class="btn btn-sm btn-secondary print" data-toggle="tooltip" data-placement="top" title="<?=$product->social['print']?>"><span class="fa fa-fw fa-print"></span></button>
			</div>
		</div>
		<div class="col-md-4 product_reviews">
			<h3 class="m-b-0">Reviews</h3>
			<ul class="list-unstyled m-b-1">
				<li>Cras justo odio<sup>SF</sup></li>
				<li>Dapibus ac facilisis in</li>
				<li>Vestibulum at eros</li>
			</ul>
		</div>
		<div class="col-md-4 product_comments">
			<h3 class="m-b-0">Comments</h3>
			<ul class="list-unstyled m-b-1">
				<li>Cras justo odio</li>
				<li>Dapibus ac facilisis in</li>
				<li>Vestibulum at eros</li>
			</ul>
		</div>
		<div class="col-md-4 product_replies">
			<h3 class="m-b-0">Forum Replies</h3>
			<ul class="list-unstyled m-b-1">
				<li>Cras justo odio</li>
				<li>Dapibus ac facilisis in</li>
				<li>Vestibulum at eros</li>
			</ul>
		</div>
	</div>
</div>
<div class="container product_content py-1">
	<?php if($product->images){ ?>
		<div id="latest_banner" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<?php foreach($product->images['full'] as $i=>$image){ ?>
					<div class="carousel-item<?=$i==0?' active':''?>">
						<img src="<?=$image?>">
					</div>
				<?php } ?>
			</div>
			<div class="carousel-thumbnails">
				<?php foreach($product->images['thumbnail'] as $i=>$image){ ?>
					<div data-target="#latest_banner" data-slide-to="<?=$i?>"<?=$i==0?'class="active"':''?>>
						<img src="<?=$image?>" width="75">
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>
</div>
<div class="container product_content px-0">
	<ul class="nav nav-tabs" role="tablist">
		<li class="nav-item">
			<a class="nav-link active" data-toggle="tab" href="#comments" role="tab">Comments</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" data-toggle="tab" href="#forum" role="tab">Forum</a>
		</li>
	</ul>
	<div class="tab-content">
		<div class="tab-pane active" id="comments" role="tabpanel">
			I made a comment
		</div>
		<div class="tab-pane" id="forum" role="tabpanel">
			IN conclusion
		</div>
	</div>
</div>
<?php include('footer.php');