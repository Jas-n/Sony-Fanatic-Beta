<?php $app_require[]='php.products';
include('init.php');
include('header.php');
$latest=$products->get_latest(9);
$latest_rows=array_chunk($latest['data'],3);?>
<div class="container-fluid">
	<div class="row">
		<div id="latest_banner" class="carousel slide" data-ride="carousel">
			<ol class="carousel-indicators">
				<li data-target="#latest_banner" data-slide-to="0" class="active"></li>
				<li data-target="#latest_banner" data-slide-to="1"></li>
				<li data-target="#latest_banner" data-slide-to="2"></li>
				<li data-target="#latest_banner" data-slide-to="3"></li>
			</ol>
			<div class="carousel-inner" role="listbox">
				<div class="carousel-item active">
					<img src="http://placehold.it/1920x500">
					<div class="carousel-caption">
						<h3>Latest News Title</h3>
						<p>This is the news</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="http://placehold.it/1920x500">
					<div class="carousel-caption">
						<h3>Latest Review<sup>SF</sup></h3>
						<p>It's Fab</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="http://placehold.it/1920x500">
					<div class="carousel-caption">
						<h3>Latest Comments</h3>
						<p>Sweet!</p>
					</div>
				</div>
				<div class="carousel-item">
					<img src="http://placehold.it/1920x500">
						<div class="carousel-caption">
						<h3>Forum Replies</h3>
						<p>Here's what I think!</p>
					</div>
				</div>
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
		<?php foreach($latest_rows as $latest_row){
			foreach($latest_row as $latest_product){
				$link='/p/'.$latest_product['id'].'-'.$latest_product['slug'];?>
				<div class="col-xs-12 col-sm-6 col-md-4 home_product">
					<div class="meta">
						<h2><a href="<?=$link?>"><?=$latest_product['name']?></a></h2>
						<p class="excerpt"><a href="<?=$link?>"><?=$latest_product['excerpt']?></a></p>
					</div>
				</div>
			<?php }
		} ?>
	</div>
</div>
<?php include('footer.php');