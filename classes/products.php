<?php class products{
	private $feature_categories;
	private $feature_options;
	public function get_brand($id){
		global $db;
		return $db->get_row("SELECT * FROM `brands` WHERE `id`=?",$id);
	}
	public function get_brands(){
		global $db;
		if($brands=$db->query(
			"SELECT *
			FROM `brands`
			ORDER BY
				`parent_id` ASC,
				`brand` ASC",
			$parent
		)){
			return array_combine(array_column($brands,'id'),$brands);
		}
		return false;
	}
	public function get_child_brands($parent=0){
		global $db;
		if($brands=$db->query(
			"SELECT *
			FROM `brands`
			WHERE `parent_id`=?
			ORDER BY `brand` ASC",
			$parent
		)){
			return array_combine(array_column($brands,'id'),$brands);
		}
		return false;
	}
	public function get_feature_categories(){
		global $db;
		if(!$this->feature_categories){
			if($feature_categories=$db->query(
				"SELECT *
				FROM `feature_categories`
				ORDER BY `name` ASC"
			)){
				$this->feature_categories=array_combine(
					array_column($feature_categories,'id'),
					$feature_categories
				);
			}
		}
		return $this->feature_categories;
	}
	public function get_feature_category_options($category){
		global $db;
		if($options=$db->query(
			"SELECT `id`,`name`
			FROM `feature_options`
			WHERE `category_id`=?",
			$category
		)){
			return $options;
		}
	}
	public function get_feature_options(){
		global $db;
		if(!$this->feature_options){
			if($feature_options=$db->query(
				"SELECT
					`feature_options`.*,
					`feature_categories`.`name` as `category`,
					(SELECT COUNT(`id`) FROM `feature_values` WHERE `feature_options`.`id`=`feature_values`.`option_id`) as `values`
				FROM `feature_options`
				LEFT JOIN `feature_categories`
				ON `feature_options`.`category_id`=`feature_categories`.`id`
				ORDER BY `name` ASC"
			)){
				$this->feature_options=array_combine(
					array_column($feature_options,'id'),
					$feature_options
				);
			}
		}
		return $this->feature_options;
	}
	public function get_feature_values($option){
		global $db;
		if($feature_values=$db->query(
			"SELECT *
			FROM `feature_values`
			WHERE `option_id`=?
			ORDER BY `value` ASC",
			$option
		)){
			$feature_values=array_combine(
				array_column($feature_values,'id'),
				$feature_values
			);
		}
		$option=$db->get_row("SELECT * FROM `feature_options` WHERE `id`=?",$option);
		return array(
			'category'	=>$db->get_row("SELECT * FROM `feature_categories` WHERE `id`=?",$option['category_id']),
			'option'	=>$option,
			'values'	=>$feature_values
		);
	}
	public function get_latest($count=NULL){
		global $db;
		if(!is_numeric($count)){
			$count=ITEMS_PER_PAGE;
		}
		if($products=$db->query(
			"SELECT
				`brands`.`brand`,
				`brands`.`slug` as `brand_slug`,
				`products`.*
			FROM `products`
			INNER JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			ORDER BY `added` ASC
			LIMIT ".$count
		)){
			foreach($products as &$product){
				if($mediums=glob(ROOT.'uploads/products/'.$product['brand_slug'].'/'.$product['id'].'/*_medium.png')){
					$product['image']=str_replace(ROOT,'/',$mediums[0]);
				}
			}
			return array(
				'count'	=>$db->result_count("FROM `products`"),
				'data'	=>array_combine(array_column($products,'id'),$products)
			);
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
			$product['features']=$db->query(
				"SELECT
					`product_value`.*,
					`feature_categories`.`name` as `category`,
					`feature_options`.`name` as `feature`,
					`feature_values`.`value`
				FROM `product_value`
				INNER JOIN `feature_values`
				ON `product_value`.`feature_value`=`feature_values`.`id`
				INNER JOIN `feature_options`
				ON `feature_values`.`option_id`=`feature_options`.`id`
				INNER JOIN `feature_categories`
				ON `feature_options`.`category_id`=`feature_categories`.`id`
				WHERE `product`=?
				ORDER BY
					`category`,
					`feature`,
					`value`",
				$id
			);
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
			LEFT JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			ORDER BY `brands`.`brand` ASC, `products`.`name` ASC".
			SQL_LIMIT
		)){
			return array(
				'count'	=>$db->result_count("FROM `products`"),
				'data'	=>array_combine(array_column($products,'id'),$products)
			);
		}
		return false;
	}
}