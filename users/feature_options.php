<?php $app_require=array(
	'form.feature_options',
	'php.products'
);
require('../init.php');
$feature_options=new feature_options;
$feature_options->process();
require('header.php');?>
<h1>Feature Options</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Products</li>
	<li class="breadcrumb-item active">Feature Options</li>
</ol>
<?php $app->get_messages();
$feature_options->get_form();
require('footer.php');