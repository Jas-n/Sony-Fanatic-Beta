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
	public function get_categories($parent=0){
		global $db;
		if($categories=$db->query(
			"SELECT
				*,
				(SELECT COUNT(`id`) FROM `categories` c2 WHERE c2.`parent_id`=`categories`.`id`) as `children`
			FROM `categories`
			WHERE `parent_id`=?
			ORDER BY
				`parent_id` ASC,
				`name` ASC",
			$parent
		)){
			return array_combine(array_column($categories,'id'),$categories);
		}
	}
	public function get_category($id){
		global $db;
		return $db->get_row("SELECT * FROM `categories` WHERE `id`=?",$id);
	}
	public function get_category_tree(){
		global $db;
		$categories=$db->query("SELECT * FROM `categories` ORDER BY `parent_id` ASC, `name` ASC");
		$categories=array_combine(array_column($categories,'id'),$categories);
		return tree($categories);
	}
	public function get_feature_categories(){
		global $db;
		if(!$this->feature_categories){
			if($feature_categories=$db->query(
				"SELECT
					*,
					(SELECT COUNT(`id`) FROM `feature_options` WHERE `category_id`=`feature_categories`.`id`) as `options`
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
	public function get_feature_category($id){
		global $db;
		if($this->feature_categories){
			return $this->feature_categories[$id];
		}else{
			return $db->get_row("SELECT * FROM `feature_categories` WHERE `id`=?",$id);
		}
	}
	public function get_feature_category_options($category){
		global $db;
		if($options=$db->query(
			"SELECT `id`,`name`
			FROM `feature_options`
			WHERE `category_id`=?
			ORDER BY `name` ASC",
			$category
		)){
			return $options;
		}
	}
	public function get_feature_options($category){
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
				WHERE `category_id`=?
				ORDER BY `name` ASC",
				$category
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
			"SELECT `id`
			FROM `products`
			WHERE `status`=1
			ORDER BY `added` DESC
			LIMIT ".$count
		)){
			return $this->get_products(array_column($products,'id'));
		}
		return false;
	}
	public function get_product($id){
		global $db,$user;
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
			if($product['categories']=$db->query("SELECT `category` FROM `product_categories` WHERE `product`=?",$id)){
				$product['categories']=array_column($product['categories'],'category');
			}
			$product['dir']='/uploads/p/'.implode('/',str_split($product['id'],1)).'/';
			foreach(glob(ROOT.$product['dir'].'*_full.png') as $full){
				$product['images']['full'][]=str_replace(ROOT,'',$full);
			}
			foreach(glob(ROOT.$product['dir'].'*_medium.png') as $medium){
				$product['images']['medium'][]=str_replace(ROOT,'',$medium);
			}
			foreach(glob(ROOT.$product['dir'].'*_thumb.png') as $thumb){
				$product['images']['thumb'][]=str_replace(ROOT,'',$thumb);
			}
			$product['articles']['count']=$db->result_count(
				"FROM `article_products`
				INNER JOIN `articles`
				ON `article_products`.`article_id`=`articles`.`id`
				WHERE
					`articles`.`status`=2 AND
					`article_products`.`product_id`=?",
				$id
			);
			$product['articles']['data']=$db->query(
				"SELECT
					`articles`.`id`,`articles`.`title`,`articles`.`slug`,`articles`.`published`,
					`users`.`username` as `author_username`
				FROM `article_products`
				INNER JOIN `articles`
				ON `article_products`.`article_id`=`articles`.`id`
				LEFT JOIN `users`
				ON `articles`.`author`=`users`.`id`
				WHERE
					`articles`.`status`=2 AND
					`article_products`.`product_id`=?
				ORDER BY `articles`.`published` DESC".
				SQL_LIMIT,
				$id
			);
			$product['features']=$db->query(
				"SELECT
					`product_values`.*,
					`feature_categories`.`name` as `category`,
					`feature_options`.`name` as `feature`,
					`feature_values`.`value`
				FROM `product_values`
				INNER JOIN `feature_values`
				ON `product_values`.`feature_value`=`feature_values`.`id`
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
			$product['links']=$db->query(
				"SELECT *
				FROM `product_links`
				WHERE `product_id`=?
				ORDER BY `title` ASC",
				$id
			);
			$product['tags']=$db->query(
				"SELECT
					`tags`.*,
					`product_tags`.`id` as `link_id`
				FROM `tags`
				INNER JOIN `product_tags`
				ON `tags`.`id`=`product_tags`.`tag`
				WHERE `product_tags`.`product`=?
				ORDER BY `tags`.`tag` ASC",
				$id
			);
			$product['catalogue']=array(
				'had'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,-1)),
				'got'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,0)),
				'want'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,1))
			);
			if(!get_dir() && is_logged_in()){
				$product['catalogue']['status']=$db->get_value(
					"SELECT `status`
					FROM `product_catalogue`
					WHERE
						`product`=? AND
						`user`=?",
					array(
						$id,
						$user->id
					)
				);
			}
			return $product;
		}
	}
	public function get_products($ids=NULL){
		global $db;
		if($ids!==NULL){
			if(!is_array($ids)){
				$ids=(array) $ids;
			}
			$where=" WHERE `products`.`id` IN(".implode(',',array_pad([],sizeof($ids),'?')).")";
			$options=$ids;
		}else{
			$limit=SQL_LIMIT;
		}
		if($products=$db->query(
			"SELECT
				`brands`.`brand`,
				`products`.*
			FROM `products`
			LEFT JOIN `brands`
			ON `products`.`brand_id`=`brands`.`id`
			".$where."
			ORDER BY
				`products`.`status` DESC,
				`products`.`added` DESC".
			$limit,
			$options
		)){
			foreach($products as $i=>$product){
				$product['dir']='/uploads/p/'.implode('/',str_split($product['id'],1)).'/';
				foreach(glob(ROOT.$product['dir'].'*_full.png') as $full){
					$product['images']['full'][]=str_replace(ROOT,'',$full);
				}
				foreach(glob(ROOT.$product['dir'].'*_medium.png') as $medium){
					$product['images']['medium'][]=str_replace(ROOT,'',$medium);
				}
				foreach(glob(ROOT.$product['dir'].'*_thumb.png') as $thumb){
					$product['images']['thumb'][]=str_replace(ROOT,'',$thumb);
				}
				$data[$product['id']]=$product;
				unset($product[$i]);
			}
			return array(
				'count'	=>$db->result_count("FROM `products`"),
				'data'	=>$data
			);
		}
		return false;
	}
}