<?php $app_require=array(
	'form.brands',
	'php.products'
);
require('../init.php');
$brands=new brands;
$brands->process();
if($_GET['id']){
	if(!$brand=$products->get_brand($_GET['id'])){
		header('Location: ../brands');
		exit;
	}
}
require('header.php');?>
<h1><?=$brand?$brand['brand']:'Brands'?></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item"><a href="products">Products</a></li>
	<li class="breadcrumb-item">Sorting</li>
	<li class="breadcrumb-item<?=$brand?' active':''?>"><?=$brand?'<a href="brands">':''?>Brands<?=$brand?'</a>':''?></li>
	<?php if($brand){ ?>
		<li class="breadcrumb-item active"><?=$brand['brand']?></li>
	<?php } ?>
</ol>
<?php $app->get_messages();
$brands->get_form();
require('footer.php');