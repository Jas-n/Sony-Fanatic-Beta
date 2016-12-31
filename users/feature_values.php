<?php $app_require=array(
	'form.feature_values',
	'php.products'
);
require('../init.php');
$feature_values=new feature_values;
$feature_values->process();
require('header.php');?>
<h1><?=$feature_values->category['name']?> <small class="text-muted"><?=$feature_values->option['name']?></small></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Products</li>
	<li class="breadcrumb-item"><a href="feature_categories">Feature Categories</a></li>
	<li class="breadcrumb-item"><a href="feature_options/<?=$feature_values->category['id']?>"><?=$feature_values->category['name']?></a></li>
	<li class="breadcrumb-item active"><?=$feature_values->option['name']?></li>
</ol>
<?php $app->get_messages();
$feature_values->get_form();
require('footer.php');