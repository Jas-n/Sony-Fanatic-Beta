<?php class edit_product extends form{
	public function __construct($data=NULL){
		global $products;
		$this->product=new product($_GET['id']);
		parent::__construct("name=".__CLASS__);
		parent::add_select(
			array(
				'label'	=>'Brand',
				'name'	=>'brand',
				'value'	=>$this->product->brand_id
			),
			$products->get_brands(),
			'Select&hellip;'
		);
		parent::add_fields(array(
			array(
				'label'	=>'Model',
				'name'	=>'model',
				'type'	=>'text',
				'value'	=>$this->product->model
			)
		));
		parent::add_html(print_pre($this->product,1).
			'<p class="text-xs-center">');
			parent::add_button(array(
				'class' =>'btn-primary',
				'name'  =>'update',
				'type'  =>'submit',
				'value' =>'Update'
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
				
				$app->log_message(3,'Updated Product','Updated <strong>'.$results['data']['model'].'</strong>.');
				$this->reload();
			}
		}
	}
}