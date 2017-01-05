<?php $app_require=array(
	'php.articles'
);
include('init.php');
if($_GET['id']){
	include(ROOT.'news_single.php');
}else{
	include(ROOT.'news_list.php');
}
include('footer.php');