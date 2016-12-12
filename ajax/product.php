<?php $app_require=array('php.products');
include_once('../init.php');
if(
	!$_POST || !$_POST['action']
	|| !in_array($_POST['action'],array('delete_value','get_features','get_product','get_values','save_value'))
	|| ($_POST['action']=='delete_value' && !$_POST['value'])
	|| ($_POST['action']=='get_features' && !$_POST['category'])
	|| ($_POST['action']=='get_product' && (!$_POST['brand'] || !$_POST['term']))
	|| ($_POST['action']=='get_values' && !$_POST['feature'])
	|| ($_POST['action']=='save_value' && (!$_POST['product'] || !$_POST['value']))
){
	echo json_encode(array(
		'status'	=>false,
		'message'	=>'Invalid details'
	));
	exit;
}
elseif($_POST['action']=='delete_value'){
	$db->query("DELETE FROM `product_value` WHERE `id`=?",$_POST['value']);
	echo json_encode(true);
	exit;
}
elseif($_POST['action']=='get_features'){
	echo json_encode($products->get_feature_category_options($_POST['category']));
}
# Get product
elseif($_POST['action']=='get_product'){
	echo json_encode(array(
		'status'=>true,
		'data'	=>$db->query(
			"SELECT `id`,`name`
			FROM `products`
			WHERE
				`brand_id`=? AND
				(
					`id` LIKE ? OR
					`name` LIKE ?
				)
			ORDER BY `name` ASC",
			array(
				$_POST['brand'],
				'%'.$_POST['term'].'%',
				'%'.$_POST['term'].'%'
			)
		)
	));
	exit;
}
elseif($_POST['action']=='get_values'){
	echo json_encode($products->get_feature_values($_POST['feature']));
}
elseif($_POST['action']=='save_value'){
	if(!$db->get_value(
		"SELECT `id`
		FROM `product_value`
		WHERE
			`product`=? AND
			`feature_value`=?",
		array(
			$_POST['product'],
			$_POST['value']
		)
	)){
		$db->query(
			"INSERT INTO `product_value` (
				`product`,`feature_value`
			) VALUES (?,?)",
			array(
				$_POST['product'],
				$_POST['value']
			)
		);
		echo json_encode($db->insert_id());
	}else{
		echo json_encode(false);
	}
	exit;
}