<?php $app_require=array(
	'form.add_article',
	'js.tinymce',
	'php.products',
	'php.articles'
);
require('../init.php');
$add_article=new add_article();
$add_article->process();
require('header.php');?>
<h1>Add Article</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="articles">Articles</a></li>
	<li class="breadcrumb-item active">Add</li>
</ol>
<?php $app->get_messages();
$add_article->get_form();
require('footer.php');