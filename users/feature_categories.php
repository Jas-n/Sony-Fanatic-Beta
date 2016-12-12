<?php $app_require=array(
	'form.feature_categories',
	'php.products'
);
require('../init.php');
$feature_categories=new feature_categories;
$feature_categories->process();
require('header.php');?>
<h1>Feature Categories</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Products</li>
	<li class="breadcrumb-item active">Feature Categories</li>
</ol>
<?php $app->get_messages();
$feature_categories->get_form();
require('footer.php');