<?php class products{
	private $feature_categories;
	private $feature_options;
	public function generate_menus(){
		global $app;
		$this->update_brand_counts();
		if($brands=$this->get_brand_tree()){
			$html='';
			foreach($brands as $brand){
				$html.=$this->generate_child_menu($brand,'brand','b');
			}
			if($html){
				$html='<li class="mega"><a>Brands</a><ul>'.$html.'</ul></li>';
			}
			file_put_contents(ROOT.'brands.html',$html);
			$app->set_message('success','Regenerated brand tree');
		}
		$this->update_category_counts();
		if($categories=$this->get_category_tree()){
			$html='';
			foreach($categories as $category){
				$html.=$this->generate_child_menu($category,'name','c');
			}
			if($html){
				$html='<li class="mega"><a>Categories</a><ul>'.$html.'</ul></li>';
			}
			file_put_contents(ROOT.'categories.html',$html);
			$app->set_message('success','Regenerated category tree');
		}
	}
	public function get_brand($id){
		global $db;
		$brand=$db->get_row("SELECT *,`products` as `product_count` FROM `brands` WHERE `id`=?",$id);
		$brand['children']=$db->query("SELECT * FROM `brands` WHERE `parent_id`=? AND `products`>0 ORDER BY `brand` ASC",$id);
		$brand['products']=$this->get_brand_products($id);
		return $brand;
	}
	public function get_brand_products($brand){
		global $db;
		if($products=$db->query(
			"SELECT
				*,
				'' as `description`
			FROM `products`
			WHERE
				`status`=1 AND
				`brand_id`=?".
			SQL_LIMIT,
			$brand
		)){
			foreach($products as &$product){
				unset($product['description']);
			}
			$products=$this->_process_products($products);
		}
		return $products;
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
	public function get_brand_tree(){
		global $db;
		if($brands=$this->get_brands()){
			$brands=array_combine(array_column($brands,'id'),$brands);
			return tree($brands);
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
		if($category=$db->get_row("SELECT * FROM `categories` WHERE `id`=?",$id)){
			$category['children']=$this->get_child_categories($id);
			$category['products']=$this->get_category_products($id);
		}
		return $category;
	}
	public function get_category_products($category){
		global $db;
		if($products=$db->query(
			"SELECT
				*,
				'' as `description`
			FROM `products`
			WHERE `id` IN(
				SELECT `product`
				FROM `product_categories`
				WHERE
					`status`=1 AND
					`category`=?
			)".
			SQL_LIMIT,
			$category
		)){
			foreach($products as &$product){
				unset($product['description']);
			}
			$products=$this->_process_products($products);
		}
		return array(
			'count'		=>$db->result_count("FROM `products` WHERE `id` IN(SELECT `product` FROM `product_categories` WHERE `category`=?)",$category),
			'products'	=>$products
		);
	}
	public function get_category_tree($parent=0){
		global $db;
		if($categories=$db->query("SELECT * FROM `categories` ORDER BY `parent_id` ASC, `name` ASC")){
			$categories=array_combine(array_column($categories,'id'),$categories);
			return tree($categories);
		}
		return false;
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
			$product['categories']=$db->query(
				"SELECT
					`categories`.*
				FROM `product_categories`
				INNER JOIN `categories`
				ON `product_categories`.`category`=`categories`.`id`
				WHERE `product`=?",$id
			);
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
			return $this->_process_product($product);
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
				`brands`.`brand` ASC,
				`products`.`name` ASC,
				`products`.`published` DESC".
			$limit,
			$options
		)){
			return array(
				'count'	=>$db->result_count("FROM `products`"),
				'data'	=>$this->_process_products($products)
			);
		}
		return false;
	}
	public function statistics(){
		
	}
	public function update_brand_counts(){
		if($brands=$this->get_brand_tree()){
			$brands=array(array(
				'children'=>$brands
			));
			$this->_update_brand_count($brands);
		}
	}
	public function update_category_counts(){
		if($categories=$this->get_category_tree()){
			$categories=array(array(
				'children'=>$categories
			));
			$this->_update_product_count($categories);
		}
	}
	public function update_completions($product_id=NULL){
		global $db;
		if($product_id){
			$where=" WHERE `id`=?";
			$values=$product_id;
		}
		$values=$db->result_count("FROM feature_values");
		if($products=$db->query(
			"SELECT
				`id`,
				(SELECT COUNT(`id`) FROM `product_values` WHERE `product`=`products`.`id`) as `options`
			FROM `products`".
			$where,
			$options
		)){
			foreach($products as $product){
				$db->query(
					"UPDATE `products`
					SET `completion`=?
					WHERE `id`=?",
					array(
						number_format($product['options']/$values*100,2),
						$product['id']
					)
				);
			}
		}
	}
	
	private function generate_child_menu($menu_item,$key,$dir,$level=0){
		if($menu_item['products']){
			$html='<li'.($level==0?' class="col-md-4"':'').'>
				<a href="/'.$dir.'/'.$menu_item['id'].'-'.slug($menu_item[$key]).'">'.$menu_item[$key].'</a>';
				if($menu_item['children']){
					$html.='<ul>';
						foreach($menu_item['children'] as $child){
							$html.=$this->generate_child_menu($child,$key,$dir,$level+1);
						}
					$html.='</ul>';
				}
			$html.='</li>';
			return $html;
		}
	}
	private function get_child_categories($parent=0){
		global $db;
		if($categories=$db->query("SELECT * FROM `categories` WHERE `parent_id`=? AND `products`>0 ORDER BY `parent_id` ASC, `name` ASC",$parent)){
			return array_combine(array_column($categories,'id'),$this->_process_categories($categories));
		}
		return false;
	}
	private function _process_categories($categories){
		if($categories){
			foreach($categories as &$category){
				$category['slug']=slug($category['name']);
				$category['url']='/c/'.$category['id'].'-'.$category['slug'];
			}
		}
		return $categories;
	}
	private function _process_product($product){
		global $db;
		$product['dir']='/uploads/p/'.implode('/',str_split($product['id'],1));
		foreach(array('full','medium','thumb') as $size){
			if($product['images'][$size]=glob(ROOT.$product['dir'].'/*_'.$size.'.png')){
				$product['images'][$size]=array_map(function($img){
					return str_replace(ROOT.'/','/',$img);
				},$product['images'][$size]);
			}
		}
		if(is_file($product['dir'].'/banner.png')){
			$product['images']['banner']=$product['dir'].'/banner.png';
		}else{
			$product['images']['banner']=$product['images']['full'][0];
		}
		$product['dir']=$product['dir'].'/';
		$product['url']='/p/'.$product['id'].'-'.$product['slug'];
		if($product['categories']){
			foreach($product['categories'] as &$cat){
				$cat['slug']=$cat['id'].'-'.slug($cat['name']);
			};
			$product['categories']=array_combine(array_column($product['categories'],'id'),$product['categories']);
		}
		$product['catalogue']=array(
			'had'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,-1)),
			'got'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,0)),
			'want'	=>$db->result_count("FROM `product_catalogue` WHERE `product`=? AND `status`=?",array($id,1))
		);
		return $product;
	}
	private function _process_products($products){
		if($products){
			foreach($products as &$product){
				$product=$this->_process_product($product);
			}
		}
		return $products;
	}
	private function _update_brand_count($brands){
		global $db;
		foreach($brands as $id=>$brand){
			$count=$db->result_count("FROM `products` WHERE `brand_id`=? AND `status`=1",$id);
			if($brand['children']){
				$count+=$this->_update_brand_count($brand['children']);
			}
			$db->query("UPDATE `brands` SET `products`=? WHERE `id`=?",array($count,$id));
		}
		return $count;
	}
	private function _update_product_count($categories){
		global $db;
		foreach($categories as $id=>$category){
			$count=$db->result_count("FROM `product_categories` LEFT JOIN `products` ON `product_categories`.`product`=`products`.`id` WHERE `product_categories`.`category`=? AND `products`.`status`=1",$id);
			if($category['children']){
				$count+=$this->_update_product_count($category['children']);
			}
			$db->query("UPDATE `categories` SET `products`=? WHERE `id`=?",array($count,$id));
		}
		return $count;
	}
}