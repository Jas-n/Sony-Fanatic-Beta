<?php $app_require=array(
	'form.profile'
);
require('../init.php');
$profile=new profile();
$profile->process();
include('header.php');?>
<div class="page-header">
	<h1>Profile</h1>
	<ol class="breadcrumb">
		<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
		<li class="breadcrumb-item active">Profile</li>
	</ol>
</div>
<?php $app->get_messages();
$profile->get_form();
include('footer.php');