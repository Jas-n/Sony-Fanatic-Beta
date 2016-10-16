<?php $app_require=array(
	'php.users',
	'form.forgot'
);
require('init.php');
if(is_logged_in()){
	header('Location: '.USER_DIRECTORY);
	exit;
}
$forgot=new forgot();
$forgot->process();
if(is_file(ROOT.'images/email_logo.png')){
	$src='images/email_logo.png';
	$img=@getimagesize(ROOT.$src);
	$logos['email']=array(
		'dim'	=>$img[3],
		'height'=>$img[1],
		'uri'	=>$src,
		'width'=>$img[0]
	);
}
if(is_file(ROOT.'images/logo.png')){
	$src='images/logo.png';
	$img=@getimagesize(ROOT.$src);
	$logos['site']=array(
		'dim'	=>$img[3],
		'height'=>$img[1],
		'uri'	=>$src,
		'width'=>$img[0]
	);
}
include('header.php'); ?>
<div class="card">
	<div class="card-block">
		<h1 class="h2">Reset Password</h1>
		<?php $app->get_messages();
		$forgot->get_form();?>
	</div>
</div>
<?php include('footer.php');