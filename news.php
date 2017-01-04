<?php $app_require=array(
	'js.tooltip',
	'php.articles',
	'php.products'
);
include('init.php');
if($_GET['id']){
	include(ROOT.'news_single.php');
}else{
	include(ROOT.'news_list.php');
}
include('footer.php');