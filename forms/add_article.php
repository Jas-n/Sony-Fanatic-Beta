<?php class add_article extends form{
	public function __construct($data=NULL){
		global $articles,$products;
		parent::__construct("name=".__CLASS__);
		$brands=$products->get_brands();
		$brands=array_combine(array_column($brands,'id'),array_column($brands,'brand'));
		parent::add_select(
			array(
				'label'		=>'Brand',
				'name'		=>'brand',
				'required'	=>1
			),
			$brands,
			'Select&hellip;'
		);
		parent::add_fields(array(
			array(
				'class'			=>'ajax_product',
				'label'			=>'Product',
				'name'			=>'product',
				'note'			=>'Start typing for list of matching products.<br>Products can be added via <a href="products">Products</a> &gt; <a href="add_product">New</a>',
				'placeholder'	=>'Product',
				'required'		=>2,
				'type'			=>'text',
				'postfield'		=>"<i class='fa fa-refresh'></i>",
				'value'			=>$data?$data['product']:'',
				'wrapclass'		=>'hidden'
			),
			array(
				'class'		=>'ajax_product_id',
				'name'		=>'product_id',
				'type'		=>'hidden',
				'value'		=>$data?$data['product_id']:''
			)
		));
		parent::add_html('<div class="content hidden">');
			parent::add_select(
				array(
					'label'		=>'Type',
					'name'		=>'type',
					'required'	=>1
				),
				$articles->types(),'Select&hellip;'
			);
			parent::add_select(
				array(
					'label'	=>'Status',
					'name'	=>'status'
				),
				$articles->statuses()
			);
			parent::add_fields(array(
				array(
					'label'		=>'Title',
					'maxlength'	=>70,
					'name'		=>'title',
					'placeholder'=>'Title',
					'required'	=>1,
					'rows'		=>3,
					'type'		=>'text'
				),
				array(
					'label'		=>'Excerpt',
					'maxlength'	=>160,
					'name'		=>'excerpt',
					'placeholder'=>'Excerpt',
					'required'	=>1,
					'rows'		=>3,
					'type'		=>'textarea'
				),
				array(
					'class'		=>'tinymce',
					'label'		=>'Content',
					'name'		=>'content',
					'required'	=>1,
					'type'		=>'textarea'
				)
			));
			parent::add_html('<p class="text-xs-center">');
				parent::add_button(array(
					'class' =>'btn-success',
					'name'  =>'add',
					'type'  =>'submit',
					'value' =>'Add'
				));
			parent::add_html('</p>
		</div>');
	}
	public function process(){
		if($_POST['form_name']==$this->data['name']){
			global $app,$db;
			$results=parent::process();
			if($results['status']!='error'){
				$results['data']=parent::unname($results['data']);
				$results['files']=parent::unname($results['files']);
				print_pre($results);
				$id=$db->next_hex_id('products');
				$db->query(
					"INSERT INTO `news` (
						`id`,		`product_id`,	`type`,	`status`,	`title`,
						`excerpt`,	`content`,		`added`,`updated`,	`published`
					) VALUES (?,?,?,?,?,	?,?,?,?)",
					array(
						$id,
						$results['data']['product_id'],
						$results['data']['type'],
						$results['data']['status'],
						$results['data']['title'],
					
						$results['data']['excerpt'],
						$results['data']['content'],
						DATE_TIME,
						DATE_TIME,
						$results['data']['status']==2?DATE_TIME:'0000-00-00 00:00:00'
					),0
				);
				$app->log_message(3,'Added Product','Added <strong>'.$results['data']['name'].'</strong> to products.');
				header('Location: ./product/'.$id);
				exit;
			}
		}
	}
}