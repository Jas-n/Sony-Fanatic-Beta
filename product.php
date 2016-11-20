<?php $app_require=array(
	'js.tooltip',
	'php.articles',
	'php.product'
);
include('init.php');
$product=new product($_GET['id']);
if(!$product->id){
	header('Location: /');
	exit;
}
if($product->banner){
	$hero['image']=$product->banner;
	if($product->slogan){
		$hero['content']='<p><em>'.$product->slogan.'</em></p>';
	}
}
$page_nav=array(
	array(
		'link'=>'#about',
		'name'=>'About'
	),
	array(
		'link'=>'#features',
		'name'=>'Features'
	)
);
if($product->images){
	$page_nav[]=array(
		'link'=>'#media',
		'name'=>'Media'
	);
}
$page_nav=array_merge($page_nav,array(
	array(
		'link'=>'#news-reviews',
		'name'=>'News/Reviews',
		'count'=>$product->articles['count']
	),
	array(
		'link'=>'#discuss',
		'name'=>'Discuss',
		'count'=>mt_rand(1,100)
	)
));
include('header.php');
#print_pre($product);?>
<h1 class="mb-0"><?=$product->name?></h1>
<div class="btn-toolbar text-xs-center interactions" role="toolbar" aria-label="Interactions">
	<div class="btn-group catalog" role="group" aria-label="My Catalog">
		<button type="button" class="btn btn-sm btn-secondary catalog_had" data-toggle="tooltip" data-placement="top" title="<?=$product->catalog['had']?> Others">Had It</button>
		<button type="button" class="btn btn-sm btn-secondary catalog_got true" data-toggle="tooltip" data-placement="top" title="You and <?=$product->catalog['got']?> Others">Got It</button>
		<button type="button" class="btn btn-sm btn-secondary catalog_want" data-toggle="tooltip" data-placement="top" title="<?=$product->catalog['want']?> Others">Want It</button>
	</div>
	<div class="btn-group social" role="group" aria-label="Social">
		<button type="button" class="btn btn-sm btn-secondary facebook" data-toggle="tooltip" data-placement="top" title="<?=$product->facebooks?> Shares"><span class="fa fa-fw fa-facebook"></span></button>
		<button type="button" class="btn btn-sm btn-secondary twitter" data-toggle="tooltip" data-placement="top" title="<?=$product->twitters?> Tweets"><span class="fa fa-fw fa-twitter"></span></button>
		<button type="button" class="btn btn-sm btn-secondary email" data-toggle="tooltip" data-placement="top" title="<?=$product->emails?> Emails"><span class="fa fa-fw fa-envelope"></span></button>
		<button type="button" class="btn btn-sm btn-secondary print" data-toggle="tooltip" data-placement="top" title="<?=$product->prints?> Prints"><span class="fa fa-fw fa-print"></span></button>
	</div>
</div>
<section id="about">
	<?=$product->description?>
</section>
<section class="hidden" id="features">
	// Features and specifications
</section>
<?php if($product->images){ ?>
	<section class="hidden" id="media">
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
	</section>
<?php } ?>
<section class="hidden" id="news-reviews">
	<?php if($product->articles['count']){
		foreach($product->articles['data'] as $i=>$article){ ?>
			<div class="article media">
				<?php if($article['featured_image']){ ?>
					<a class="media-left" href="/a/<?=$article['id']?>-<?=slug($article['title'])?>" title="<?=$article['title']?>">
						<img class="media-object" src="..." alt="<?=$article['title']?>">
					</a>
				<?php } ?>
				<div class="media-body">
					<h4 class="media-heading"><a href="/a/<?=$article['id']?>-<?=slug($article['title'])?>" title="<?=$article['title']?>"><?=$article['title']?></a></h4>
					<?=$article['excerpt']?>
					<p><em>Published: <?=sql_datetime($article['published'])?> by <a href="/u/<?=$article['author_username']?>"><?=$article['author_username']?></a></em></p>
				</div>
			</div>
		<?php }
	} ?>
</section>
<section class="hidden" id="discuss">
	// Comments
</section>
<?php include('footer.php');