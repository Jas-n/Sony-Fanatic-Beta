<?php $app_require=array('php.clients');
include_once('../init.php');
if(
	!$_POST || !$_POST['action']
	|| !in_array($_POST['action'],array('get_product'))
	|| ($_POST['action']=='get_product' && (!$_POST['brand'] || !$_POST['term']))
){
	echo json_encode(array(
		'status'	=>false,
		'message'	=>'Invalid details'
	));
	exit;
}
# Get client
if($_POST['action']=='get_product'){
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