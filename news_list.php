<?php $news=$articles->get_articles();
include('header.php');
if($news['count']){
	foreach($news['data'] as $i=>$article){
		if($i%3==0){?>
			<div class="card-deck">
		<?php } ?>
			<div class="card card-body article">
				<h2 class="card-title h4"><a href="/n/<?=$article['slug']?>"><?=$article['title']?></a></h2>
				<p><?=$article['excerpt']?></p>
				<p class="small text-right"><?=sql_datetime($article['published'])?> by <a href="/u/<?=$article['author']['username']?>"><?=$article['author']['username']?></a></p>
			</div>
		<?php if($i%3==2){?>
			</div>
		<?php }
	}
}
pagination($news['count']);