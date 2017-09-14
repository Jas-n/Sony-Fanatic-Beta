<?php include_once('../init.php');
if(
	!$_POST || !$_POST['action']
	|| !in_array($_POST['action'],array(
		'add_tag',		'assign_tag',	'delete_link',	'delete_tag',	'delete_value',
		'get_categories','get_features','get_product',	'get_tags',		'get_values',
		'save_value',	'update_catalogue'
	))
	|| ($_POST['action']=='add_tag' && (!$_POST['product'] || !$_POST['tag']))
	|| ($_POST['action']=='assign_tag' && (!$_POST['product'] || !$_POST['tag']))
	|| ($_POST['action']=='delete_link' && !$_POST['link'])
	|| ($_POST['action']=='delete_tag' && !$_POST['tag'])
	|| ($_POST['action']=='delete_value' && (!$_POST['product'] || !$_POST['value']))
	|| ($_POST['action']=='get_categories' && !$_POST['term'])
	
	|| ($_POST['action']=='get_features' && !$_POST['category'])
	|| ($_POST['action']=='get_product' && !$_POST['term'])
	|| ($_POST['action']=='get_tags' && !$_POST['term'])
	|| ($_POST['action']=='get_values' && !$_POST['feature'])
	|| ($_POST['action']=='save_value' && (!$_POST['product'] || !$_POST['value']))
	|| ($_POST['action']=='update_catalogue' && (!$_POST['product'] || !isset($_POST['status']) || !$_POST['user']))
){
	echo json_encode(array(
		'status'	=>false,
		'message'	=>'Invalid details'
	));
	exit;
}
elseif($_POST['action']=='add_tag'){
	$db->query("INSERT INTO `tags` (`tag`) VALUES (?)",$_POST['tag']);
	$tag_id=$db->insert_id();
	$db->query("INSERT INTO `product_tags` (`product`,`tag`) VALUES (?,?)",array($_POST['product'],$tag_id));
	$tag=$db->get_row(
		"SELECT
			`tags`.*,
			`product_tags`.`id` as `link_id`
		FROM `tags`
		INNER JOIN `product_tags`
		ON `tags`.`id`=`product_tags`.`tag`
		WHERE `tags`.`id`=?",
		$tag_id
	);
	echo json_encode(array(
		'status'=>true,
		'tag'	=>$tag
	));
	exit;
}
elseif($_POST['action']=='assign_tag'){
	$db->query("INSERT INTO `product_tags` (`product`,`tag`) VALUES (?,?)",array($_POST['product'],$_POST['tag']));
	$tag=$db->get_row(
		"SELECT
			`tags`.*,
			`product_tags`.`id` as `link_id`
		FROM `tags`
		INNER JOIN `product_tags`
		ON `tags`.`id`=`product_tags`.`tag`
		WHERE `tags`.`id`=?",
		$_POST['tag']
	);
	echo json_encode(array(
		'status'=>true,
		'tag'	=>$tag
	));
	exit;
}
elseif($_POST['action']=='delete_link'){
	$db->query("DELETE FROM `product_links` WHERE `id`=?",$_POST['link']);
	echo json_encode(true);
	exit;
}
elseif($_POST['action']=='delete_tag'){
	$db->query("DELETE FROM `product_tags` WHERE `id`=?",$_POST['tag']);
	echo json_encode(true);
	exit;
}
elseif($_POST['action']=='delete_value'){
	$db->query("DELETE FROM `product_values` WHERE `id`=?",$_POST['value']);
	$products->update_completions($_POST['product']);
	echo json_encode(true);
	exit;
}
elseif($_POST['action']=='get_categories'){
	echo json_encode($products->get_feature_categories($_POST['term']));
}
elseif($_POST['action']=='get_features'){
	echo json_encode($products->get_feature_category_options($_POST['category']));
}
# Get product
elseif($_POST['action']=='get_product'){
	echo json_encode(array(
		'status'=>true,
		'data'	=>$db->query(
			"SELECT
				`products`.`id`,`products`.`name`,
				`brands`.`brand`
			FROM `products`
			INNER JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			WHERE
				(
					`products`.`id` LIKE ? OR
					`products`.`name` LIKE ?
				)
			ORDER BY `products`.`name` ASC",
			array(
				'%'.$_POST['term'].'%',
				'%'.$_POST['term'].'%'
			)
		)
	));
	exit;
}
elseif($_POST['action']=='get_tags'){
	echo json_encode(array(
		'status'=>true,
		'data'	=>$db->query(
			"SELECT `id`,`tag`
			FROM `tags`
			WHERE
				`tag` LIKE ?
			ORDER BY `tag` ASC",
			array(
				'%'.$_POST['term'].'%'
			)
		)
	));
	exit;
}
elseif($_POST['action']=='get_values'){
	echo json_encode($products->get_feature_values($_POST['feature']));
}
elseif($_POST['action']=='update_catalogue'){
	if(!$db->get_value(
		"SELECT `id`
		FROM `product_catalogue`
		WHERE
			`product`=? AND
			`user`=?",
		array(
			$_POST['product'],
			$_POST['user']
		)
	)){
		$db->query(
			"INSERT INTO `product_catalogue` (
				`product`,`user`,`status`,`updated`
			) VALUES (?,?,?)",
			array(
				$_POST['product'],
				$_POST['user'],
				$_POST['status'],
				DATE_TIME
			)
		);
	}else{
		$db->query(
			"UPDATE `product_catalogue`
			SET
				`status`=?,
				`updated`=?
			WHERE
				`product`=? AND
				`user`=?",
			array(
				$_POST['status'],
				DATE_TIME,
				$_POST['product'],
				$_POST['user']
			)
		);
	}
	echo json_encode(true);
	exit;
}
elseif($_POST['action']=='save_value'){
	if(!$db->get_value(
		"SELECT `id`
		FROM `product_values`
		WHERE
			`product`=? AND
			`feature_value`=?",
		array(
			$_POST['product'],
			$_POST['value']
		)
	)){
		$db->query(
			"INSERT INTO `product_values` (
				`product`,`feature_value`
			) VALUES (?,?)",
			array(
				$_POST['product'],
				$_POST['value']
			)
		);
		echo json_encode($db->insert_id());
		$products->update_completions($_POST['product']);
	}else{
		echo json_encode(false);
	}
	exit;
}