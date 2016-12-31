<?php $app_require=array(
	'form.tags',
	'php.users'
);
require('../init.php');
require('header.php');
$tags=new tags();
$tags->process();?>
<h1>Tags</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="products">Products</a></li>
	<li class="breadcrumb-item active">Tags</li>
</ol>
<?php $app->get_messages();
$tags->get_form();
require('footer.php');