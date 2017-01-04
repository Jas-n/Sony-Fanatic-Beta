<?php $app_require=array(
	'php.articles',
	'php.products'
);
include('init.php');
include('header.php');
$latest			=$products->get_latest(9);
$latest_rows	=array_chunk($latest['data'],3);
$latest_news	=$articles->get_latest(2,1)['data'][0];
$latest_review	=$articles->get_latest(2,2)['data'][0];
if($latest_review){
	$slider[]=array(
		'title'	=>$latest_review['title'],
		'excerpt'=>$latest_review['excerpt'],
		'link'	=>'/n/'.$latest_review['slug']
	);
}
if($latest_news){
	$slider[]=array(
		'title'	=>$latest_news['title'],
		'excerpt'=>$latest_news['excerpt'],
		'link'	=>'/n/'.$latest_news['slug']
	);
}
/*$slider[]=array(
	'title'	=>$latest_comment['title'],
	'excerpt'=>$latest_comment['excerpt'],
	'link'	=>'/n/'.$latest_comment['slug']
);*/?>
<div class="container-fluid">
	<div class="row">
		<div id="latest_banner" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<?php foreach($slider as $i=>$slide){ ?>
					<li data-target="#latest_banner" data-slide-to="<?=$i?>"<?=$i==0?' class="active"':''?>></li>
				<?php } ?>
			</ol>
			<div class="carousel-inner" role="listbox">
				<?php foreach($slider as $i=>$slide){?>
					<div class="carousel-item<?=$i==0?' active':''?>">
						<a href="<?=$slide['link']?>"><img src="http://placehold.it/1920x500"></a>
						<div class="carousel-caption">
							<h3><a href="<?=$slide['link']?>"><?=$slide['title']?></a></h3>
							<p class="text-truncate"><?=crop($slide['excerpt'],50)?></p>
						</div>
					</div>
				<?php } ?>
			</div>
			<a class="left carousel-control" href="#latest_banner" role="button" data-slide="prev">
				<span class="icon-prev" aria-hidden="true"></span>
				<span class="sr-only">Previous</span>
			</a>
			<a class="right carousel-control" href="#latest_banner" role="button" data-slide="next">
				<span class="icon-next" aria-hidden="true"></span>
				<span class="sr-only">Next</span>
			</a>
		</div>
	</div>
	<div class="row">
		<?php if($latest_rows){
			foreach($latest_rows as $latest_row){
				foreach($latest_row as $latest_product){
					$link='/p/'.$latest_product['id'].'-'.$latest_product['slug'];?>
					<div class="col-xs-12 col-sm-6 col-md-4 home_product" style="background-image:url(<?=$latest_product['images']['medium'][0]?>)">
						<a class="meta" href="<?=$link?>">
							<h2><?=$latest_product['brand'].' '.$latest_product['name']?></h2>
						</a>
					</div>
				<?php }
			}
		} ?>
	</div>
</div>
<?php include('footer.php');