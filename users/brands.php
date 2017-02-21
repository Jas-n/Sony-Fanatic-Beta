<?php $app_require=array(
	'form.brands'
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
$h1=$brand?$brand['brand']:'Brands';
$breadcrumb=array(
	'products'=>'Products',
);
if($brand){
	$breadcrumb['brands']='Brands';
	$breadcrumb[]=$brand['brand'];
}else{
	$breadcrumb[]='Brands';
}
require('header.php');
$app->get_messages(); ?>
<div class="card card-block">
	<?php $brands->get_form(); ?>
</div>
<?php require('footer.php');