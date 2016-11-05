<?php $app_require=array(
	'form.login'
);
require('init.php');
if(is_logged_in()){
	header('Location: /users');
	exit;
}
$login=new login();
$login->process();
if($_GET['reset']){
	$app->set_message('success','Your new password has been emailed to you.');
}elseif($_GET['registered']){
	$app->set_message('success','You have been successfully registered. Your login details have been sent to the supplied email address.');
}
require('header.php');?>
<div class="container">
	<div class="row">
		<div class="col-md-6 offset-md-3">
			<div class="card card-block">
				<h1 class="h2">Login</h1>
				<?php $app->get_messages();
				$login->get_form();?>
			</div>
		</div>
	</div>
</div>
<?php require('footer.php');