<?php class products{
	public function get_brands(){
		global $db;
		if($brands=$db->query(
			"SELECT *
			FROM `brands`
			ORDER BY `brand` ASC"
		)){
			return array_combine(array_column($brands,'id'),$brands);
		}
		return false;
	}
	public function get_product($id){
		global $db;
		if($product=$db->get_row(
			"SELECT
				`brands`.`brand`,
				`brands`.`slug` as `brand_slug`,
				`products`.*
			FROM `products`
			INNER JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			WHERE `products`.`id`=?",
			$id
		)){
			return $product;
		}
	}
	public function get_products(){
		global $db;
		if($products=$db->query(
			"SELECT
				`brands`.`brand`,
				`products`.*
			FROM `products`
			INNER JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			ORDER BY `brands`.`brand` ASC, `products`.`model` ASC".
			SQL_LIMIT
		)){
			return array_combine(array_column($products,'id'),$products);
		}
		return false;
	}
}