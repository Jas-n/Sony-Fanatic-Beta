<?php $app_require=array(
	'form.list_users',
	'php.users'
);
require('../init.php');
$html_help='<p>Users shows a list of all users currently registered to the system. Here you are able to go through and view or email any one of those users.</p>
<p>When viewing you are taken through to that users profile which holds information such as their Number, Address, and Email address.</p>';
require('header.php');
$list_users=new list_users();
$list_users->process();?>
<a class="btn btn-success pull-xs-right" href="./add_user" title="Add User"><span class="fa fa-plus"></span> Add</a>
<h1>Users <small class="text-muted"><?=$list_users->count?></small></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item active">Users</li>
</ol>
<?php $app->get_messages();
$list_users->get_form();
require('footer.php');