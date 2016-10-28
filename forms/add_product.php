<?php class add_product extends form{
	public function __construct($data=NULL){
		global $products;
		parent::__construct("name=".__CLASS__);
		parent::add_select(
			array(
				'label'	=>'Brand',
				'name'	=>'brand'
			),
			$products->get_brands(),
			'Select&hellip;'
		);
		parent::add_fields(array(
			array(
				'label'			=>'Model ID',
				'name'			=>'model',
				'placeholder'	=>'Model ID',
				'type'			=>'text'
			),
			array(
				'class'	=>'tinymce',
				'label'	=>'Description',
				'name'	=>'description',
				'type'	=>'textarea'
			)
		));
		parent::add_html('<p class="text-xs-center">');
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
			if($results['status']!='error'){
				$results['data']=parent::unname($results['data']);
				$results['files']=parent::unname($results['files']);
				$db->query(
					"INSERT INTO `products` (
						`id`,`brand_id`,`model`,`slug`,`description`,
						`added`,`updated`
					) VALUES (?,?,?,?,?,	?,?)",
					array(
						$db->next_hex_id('products','id'),
						$results['data']['brand'],
						$results['data']['model'],
						slug($results['data']['model']),
						$results['data']['description'],
					
						DATE_TIME,
						DATE_TIME
					),0
				);
				$app->log_message(3,'Added Product','Added <strong>'.$results['data']['model'].'</strong> to products.');
				header('Location: ./products');
				exit;
			}
		}
	}
}