<?php $app_require=array(
	'form.add_news',
	'js.tinymce',
	'php.products',
	'php.news'
);
require('../init.php');
$add_news=new add_news();
$add_news->process();
require('header.php');?>
<h1>Add News</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="news">News</a></li>
	<li class="breadcrumb-item active">Add</li>
</ol>
<?php $app->get_messages();
$add_news->get_form();
require('footer.php');