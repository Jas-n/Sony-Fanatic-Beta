<?php $article=$articles->get_article($_GET['id']);
if(!$article){
	header('Location: /news');
	exit;
}
if($article['featured_image']){
	$hero['image']=$article['featured_image'];
	if($article['excerpt']){
		$hero['content']='<p><em>'.$article['excerpt'].'</em></p>';
	}
}
include('header.php');?>
<h1 class="mb-0"><?=$article['title']?></h1>
<div class="btn-toolbar text-center interactions" role="toolbar" aria-label="Interactions">
	<div class="btn-group social" role="group" aria-label="Social">
		<button type="button" class="btn btn-sm btn-secondary facebook" data-toggle="tooltip" data-placement="top" title="<?=$article['facebooks']?> Shares"><span class="fa fa-fw fa-facebook"></span></button>
		<button type="button" class="btn btn-sm btn-secondary twitter" data-toggle="tooltip" data-placement="top" title="<?=$article['twitters']?> Tweets"><span class="fa fa-fw fa-twitter"></span></button>
		<button type="button" class="btn btn-sm btn-secondary email" data-toggle="tooltip" data-placement="top" title="<?=$article['emails']?> Emails"><span class="fa fa-fw fa-envelope"></span></button>
		<button type="button" class="btn btn-sm btn-secondary print" data-toggle="tooltip" data-placement="top" title="<?=$article['prints']?> Prints"><span class="fa fa-fw fa-print"></span></button>
	</div>
</div>
<?=$article['content']?>
<hr>
<div class="article-details">
	<p><strong class="tab-10">Product</strong><a href="/p/<?=$article['product']['id']?>-<?=$article['product']['username']?>" title="<?=$article['product']['name']?>"><?=$article['product']['name']?></a></p>
	<p><strong class="tab-10">Published</strong><?=sql_datetime($article['published'])?></p>
</div>
<div class="media author-details bg-primary">
	<a class="media-left" href="/u/<?=$article['author']['username']?>" title="<?=$article['author']['username']?>">
		<img class="media-object" src="<?=$user->get_avatar()?>" alt="<?=$article['author']['username']?>">
	</a>
	<div class="media-body">
		<h4 class="h6 media-heading"><a href="/u/<?=$article['author']['username']?>" title="<?=$article['author']['username']?>">The Author</a></h4>
		<p><span class="tab-10">Name</span><a href="/u/<?=$article['author']['username']?>" title="<?=$article['author']['username']?>"><?=$article['author']['username']?></a></p>
		<p><span class="tab-10">Member Since</span><?=sql_date($article['author']['registered'])?></p>
		<?php if($article['author']['bio']){ ?>
			<p><span class="tab-10">Bio</span><?=$article['author']['bio']?></p>
		<?php } ?>
	</div>
</div>