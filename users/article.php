<?php $app_require=array(
	'form.item_article',
	'js.lightbox',
	'js.tinymce',
	'php.articles'
);
require('../init.php');
$article=$articles->get_article($_GET['id']);
$item_article=new item_article();
$item_article->process();
$h1=$article['title'];
$breadcrumb=array(
	'articles'=>'Articles',
	$article['title']
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $item_article->get_form(); ?>
</div>
<?php require('footer.php');