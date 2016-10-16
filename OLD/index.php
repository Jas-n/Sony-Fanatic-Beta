<?php include('init.php');
include('header.php'); ?>
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
		<?php for($i=0;$i<9;$i++){
			$product=mt_rand(1000,9999)?>
			<div class="col-xs-12 col-sm-6 col-md-4 home_product">
				<div class="meta">
					<h2><a href="/p/<?=$product?>"><?=$product?></a></h2>
					<p class="excerpt"><a href="/p/<?=$product?>">This is the excerpt for this product</a></p>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
<?php include('footer.php');