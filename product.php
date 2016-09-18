<?php include('init.php');
$product=array(
	'name'		=>'PlayStation 4 Pro',
	'slogan'	=>'The super-charged PS4',
	'description'=>'PS4™ Pro gets you closer to your game. Heighten your experiences. Enrich your adventures. Let the super-charged PS4™ Pro lead the way.',
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
);
include('header.php'); ?>
<div class="jumbotron jumbotron-fluid" style="background-image:url('/images/p/original.jpg')">
	<div class="container">
		<div class="jumbotron-data">
			<h1 class="m-b-0"><?=$product['name']?></h1>
			<p><em><?=$product['slogan']?></em></p>
			<div class="iteractions">
				<div class="btn-group catalog" role="group" aria-label="My Catalog">
					<button type="button" class="btn btn-sm btn-secondary catalog_had" data-toggle="tooltip" data-placement="top" title="<?=$product['catalog']['had']?> Others">Had It</button>
					<button type="button" class="btn btn-sm btn-secondary catalog_got true" data-toggle="tooltip" data-placement="top" title="You and <?=$product['catalog']['got']?> Others">Got It</button>
					<button type="button" class="btn btn-sm btn-secondary catalog_want" data-toggle="tooltip" data-placement="top" title="<?=$product['catalog']['want']?> Others">Want It</button>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-6">
			<p><?=$product['description']?></p>
			<div class="text-xs-center iteractions">
				<div class="btn-group social" role="group" aria-label="Social">
					<button type="button" class="btn btn-sm btn-secondary facebook" data-toggle="tooltip" data-placement="top" title="<?=$product['social']['facebook']?>"><span class="fa fa-fw fa-facebook"></span></button>
					<button type="button" class="btn btn-sm btn-secondary twitter" data-toggle="tooltip" data-placement="top" title="<?=$product['social']['twitter']?>"><span class="fa fa-fw fa-twitter"></span></button>
					<button type="button" class="btn btn-sm btn-secondary email" data-toggle="tooltip" data-placement="top" title="<?=$product['social']['email']?>"><span class="fa fa-fw fa-envelope"></span></button>
					<button type="button" class="btn btn-sm btn-secondary print" data-toggle="tooltip" data-placement="top" title="<?=$product['social']['print']?>"><span class="fa fa-fw fa-print"></span></button>
				</div>
			</div>
		</div>
		<div class="col-md-6">
			<div id="latest_banner" class="carousel slide" data-ride="carousel">
				<div class="carousel-inner" role="listbox">
					<div class="carousel-item active">
						<img src="http://placehold.it/1920x1080?text=Slide+1">
					</div>
					<div class="carousel-item">
						<img src="http://placehold.it/1920x1080?text=Slide+2">
					</div>
					<div class="carousel-item">
						<img src="http://placehold.it/1920x1080?text=Slide+3">
					</div>
					<div class="carousel-item">
						<img src="http://placehold.it/1920x1080?text=Slide+4">
					</div>
				</div>
				<div class="carousel-thumbnails">
					<div data-target="#latest_banner" data-slide-to="0" class="active">
						<img src="http://placehold.it/1920x1080?text=Slide+1" width="80" height="45">
					</div>
					<div data-target="#latest_banner" data-slide-to="1">
						<img src="http://placehold.it/1920x1080?text=Slide+2" width="80" height="45">
					</div>
					<div data-target="#latest_banner" data-slide-to="2">
						<img src="http://placehold.it/1920x1080?text=Slide+3" width="80" height="45">
					</div>
					<div data-target="#latest_banner" data-slide-to="3">
						<img src="http://placehold.it/1920x1080?text=Slide+4" width="80" height="45">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include('footer.php');