<?php $app_require=array(
	'form.categories'
);
require('../init.php');
$categories=new categories;
$categories->process();
require('header.php');?>
<h1>Categories</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Products</li>
	<li class="breadcrumb-item active">Categories</li>
</ol>
<?php $app->get_messages();
$categories->get_form();
require('footer.php');