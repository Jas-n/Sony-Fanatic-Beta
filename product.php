<?php $app_require=array(
	'js.tooltip',
	'php.articles',
	'php.product'
);
include('init.php');
$product=new product($_GET['id']);
$app->page_title=$product->brand.' '.$product->name;
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
		'count'=>$product->comments['count']
	)
));
include('header.php');?>
<h1 class="mb-0"><?=$product->name?></h1>
<div class="btn-toolbar interactions" role="toolbar" aria-label="Interactions">
	<?php if(is_logged_in()){ ?>
		<div class="btn-group catalogue" role="group" aria-label="My Catalogue">
			<button type="button" class="btn btn-sm btn-secondary catalogue_had<?=isset($product->catalogue['status']) && $product->catalogue['status']==-1?' true':''?>" data-toggle="tooltip" data-placement="top" title="<?=$product->catalogue['had']?> <?=$product->catalogue['had']==1?'Person':'People'?>">Had It</button>
			<button type="button" class="btn btn-sm btn-secondary catalogue_got<?=isset($product->catalogue['status']) && $product->catalogue['status']==0?' true':''?>" data-toggle="tooltip" data-placement="top" title="<?=$product->catalogue['got']?> <?=$product->catalogue['got']==1?'Person':'People'?>">Got It</button>
			<button type="button" class="btn btn-sm btn-secondary catalogue_want<?=isset($product->catalogue['status']) && $product->catalogue['status']==1?' true':''?>" data-toggle="tooltip" data-placement="top" title="<?=$product->catalogue['want']?> <?=$product->catalogue['want']==1?'Person':'People'?>">Want It</button>
		</div>
	<?php } ?>
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
	<?php if($product->features){
		foreach($product->features as $feature){
			$feature_categories[$feature['category']][$feature['feature']][]=$feature['value'];
			ksort($feature_categories);
			ksort($feature_categories[$feature['category']]);
		}?>
		<table class="table table-sm table-fixed">
			<thead>
				<tr>
					<th>Category</th>
					<th>Feature</th>
					<th>Value</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($feature_categories as $feature_category=>$features){?>
					<tr>
						<th rowspan="<?=sizeof($features)?>"><?=$feature_category?></th>
						<?php $first_feature=key($features);
						foreach($features as $feature=>$values){
							if($first_feature!=$feature){?>
								<tr>
							<?php } ?>
							<td><?=$feature?></td>
							<td>
								<?php if($values){?>
									<?=implode('<br>',$values)?>
								<?php } ?>
							</td>
							<?php if($first_feature!=$feature){?>
								</tr>
							<?php }
						}?>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	<?php } ?>
</section>
<?php if($product->images){ ?>
	<section class="hidden" id="media">
		<div id="latest_banner" class="carousel slide" data-ride="carousel">
			<div class="carousel-inner" role="listbox">
				<?php foreach($product->images['full'] as $i=>$image){ ?>
					<div class="carousel-item<?=$i==0?' active':''?>">
						<img class="d-block img-fluid" src="<?=$image?>">
					</div>
				<?php } ?>
			</div>
			<div class="carousel-thumbnails">
				<?php foreach($product->images['thumb'] as $i=>$image){ ?>
					<div data-target="#latest_banner" data-slide-to="<?=$i?>"<?=$i==0?'class="active"':''?>>
						<img class="d-block img-fluid" src="<?=$image?>" width="75">
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
					<a class="media-left" href="/n/<?=$article['id']?>-<?=slug($article['title'])?>" title="<?=$article['title']?>">
						<img class="media-object" src="..." alt="<?=$article['title']?>">
					</a>
				<?php } ?>
				<div class="media-body">
					<h4 class="media-heading"><a href="/n/<?=$article['id']?>-<?=slug($article['title'])?>" title="<?=$article['title']?>"><?=$article['title']?></a></h4>
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