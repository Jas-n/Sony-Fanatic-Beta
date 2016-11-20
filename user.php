<?php $app_require=array(
	'js.tooltip'
);
include('init.php');
$usr=$user->get_user($_GET['username']);
if(!$usr){
	header('Location: /');
	exit;
}
include('header.php');?>
<h1 class="mb-0"><?=$usr['username']?></h1>
<?php //print_pre($usr);
include('footer.php');