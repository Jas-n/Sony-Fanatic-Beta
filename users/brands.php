<?php $app_require=array(
	'form.brands',
	'php.products'
);
require('../init.php');
$brands=new brands;
$brands->process();
require('header.php');?>
<h1>Brands</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="products">Products</a></li>
	<li class="breadcrumb-item">Sorting</li>
	<li class="breadcrumb-item active">Brands</li>
</ol>
<?php $app->get_messages();
$brands->get_form();
require('footer.php');