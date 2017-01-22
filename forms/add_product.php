<?php class add_product extends form{
	public function __construct($data=NULL){
		global $products;
		parent::__construct("name=".__CLASS__);
		$brands=$products->get_brands();
		$brands=$this->optioner(tree($brands),'brand');
		$categories=$this->optioner($products->get_category_tree(),'name');
		parent::add_select(
			array(
				'label'		=>'Brand',
				'name'		=>'brand',
				'required'	=>1
			),
			$brands,
			'Select&hellip;'
		);
		parent::add_select(
			array(
				'label'		=>'Category',
				'name'		=>'categories[]',
				'multiple'	=>1,
				'required'	=>1
			),
			$categories
		);
		parent::add_fields(array(
			array(
				'label'			=>'Name',
				'name'			=>'name',
				'placeholder'	=>'Name',
				'required'		=>1,
				'type'			=>'text'
			),
			array(
				'label'		=>'Excerpt',
				'maxlength'	=>160,
				'name'		=>'excerpt',
				'type'		=>'textarea'
			),
			array(
				'class'	=>'tinymce',
				'label'	=>'Description',
				'name'	=>'description',
				'type'	=>'textarea'
			)
		));
		parent::add_html('<p class="text-center">');
			parent::add_button(array(
				'class' =>'btn-success',
				'name'  =>'add',
				'type'  =>'submit',
				'value' =>'Add'
			));
		parent::add_html("</p>");
	}
	public function process(){
		if($_POST['form_name']==$this->data['name']){
			global $app,$db;
			$results=parent::process();
			$results['data']=parent::unname($results['data']);
			$results['files']=parent::unname($results['files']);
			$id=$db->next_hex_id('products','id');
			$db->query(
				"INSERT INTO `products` (
					`id`,			`brand_id`,	`name`,`slug`,`excerpt`,
					`description`,	`added`,	`updated`
				) VALUES (?,?,?,?,?,	?,?,?)",
				array(
					$id,
					$results['data']['brand'],
					$results['data']['name'],
					slug($results['data']['name']),
					$results['data']['excerpt'],

					$results['data']['description'],
					DATE_TIME,
					DATE_TIME
				),0
			);
			foreach($results['data']['categories'] as $category){
				$db->query(
					"INSERT INTO `product_categories` (
						`product`,`category`
					) VALUES (?,?)",
					array(
						$id,
						$category
					)
				);
			}
			$app->log_message(3,'Added Product','Added <strong>'.$results['data']['name'].'</strong> to products.');
			$products->update_category_counts();
			header('Location: ./products');
			exit;
		}
	}
	private function optioner($items,$key,$level=0){
		$list=array();
		if($items){
			foreach($items as $item){
				$list[$item['id']]=implode('',array_pad([],$level,'-&nbsp;&nbsp;')).$item[$key];
				if($item['children']){
					$list=$list+$this->optioner($item['children'],$key,$level+1);
				}
			}
		}
		return $list;
	}
}