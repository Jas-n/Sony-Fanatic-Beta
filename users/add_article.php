<?php $app_require=array(
	'form.add_article',
	'js.tinymce',
	'lib.twitter',
	'php.articles'
);
require('../init.php');
$add_article=new add_article();
$add_article->process();
$h1='Add Article';
$breadcrumb=array(
	'articles'=>'Articles',
	'Add'
);
require('header.php');
$app->get_messages(); ?>
<div class="card card-block">
	<?php $add_article->get_form(); ?>
</div>
<?php require('footer.php');