<?php $app_require=array(
	'form.categories'
);
require('../init.php');
$categories=new categories;
$categories->process();
$h1='Categories';
$breadcrumb=array(
	'products'=>'Products'
);
if($category=$products->get_category($_GET['id'])){
	$breadcrumb['categories']='Categories';
	if($category['parent_id']){
		$breadcrumb[]='&hellip;';
	}
	$breadcrumb[]=$category['name'];
}else{
	$breadcrumb[]='Categories';
}
require('header.php');
$app->get_messages(); ?>
<div class="card card-body">
	<?php $categories->get_form(); ?>
</div>
<?php require('footer.php');