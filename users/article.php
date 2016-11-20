<?php $app_require=array(
	'form.item_article',
	'js.lightbox',
	'js.tinymce',
	'php.articles',
	'php.products'
);
require('../init.php');
$item_article=new item_article();
$item_article->process();
require('header.php');
$article=$articles->get_article($_GET['id']);?>
<h1><?=$article['title']?></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="articles">Articles</a></li>
	<li class="breadcrumb-item active"><?=$article['title']?></li>
</ol>
<?php print_pre($article);
$app->get_messages();
$item_article->get_form();
require('footer.php');