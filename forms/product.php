<?php class edit_product extends form{
	public function __construct($data=NULL){
		global $products;
		$this->product=new product($_GET['id']);
		$brands=$products->get_brands();
		$brands=array_combine(array_keys($brands),array_column($brands,'brand'));
		parent::__construct("name=".__CLASS__);
		parent::add_select(
			array(
				'label'	=>'Brand',
				'name'	=>'brand',
				'value'	=>$this->product->brand_id
			),
			$brands,
			'Select&hellip;'
		);
		parent::add_fields(array(
			array(
				'label'			=>'Name',
				'name'			=>'name',
				'placeholder'	=>'Name',
				'type'			=>'text',
				'value'			=>$this->product->name
			),
			array(
				'label'		=>'Excerpt',
				'maxlength'	=>160,
				'name'		=>'excerpt',
				'type'		=>'textarea',
				'value'		=>$this->product->excerpt
			),
			array(
				'class'	=>'tinymce',
				'label'	=>'Description',
				'name'	=>'description',
				'type'	=>'textarea',
				'value'	=>$this->product->description
			),
			array(
				'accept'	=>'jpeg,jpg,png',
				'label'		=>'Images',
				'multiple'	=>1,
				'name'		=>'images[]',
				'type'		=>'file'
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
				$db->query(
					"UPDATE `products`
					SET
						`brand_id`=?,
						`name`=?,
						`excerpt`=?,
						`description`=?,
						`updated`=?
					WHERE `id`=?",
					array(
						$results['data']['brand'],
						$results['data']['name'],
						$results['data']['excerpt'],
						$results['data']['description'],
						DATE_TIME,
						$_GET['id']
					),0
				);
				if($results['stats']['uploaded_files']){
					$images=$results['files']['images'];
					if(!is_dir(ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id)){
						mkdir(ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id,0777,1);
					}
					foreach($images['name'] as $i=>$name){
						$j=0;
						list($width,$height)=getimagesize($images['tmp_name'][$i]);
						while(is_file(ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_thumb.png')){
							$j++;
						}
						smart_resize_image($images['tmp_name'][$i],NULL,150,0,1,ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_thumb.png',0,'png');
						if($width > 1000 || $height > 1000){
							smart_resize_image($images['tmp_name'][$i],NULL,$width,$height>=1000?1000:$height,1,ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_full.png',0,'png');
						}else{
							smart_resize_image($images['tmp_name'][$i],NULL,$width,$height,1,ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_full.png',0,'png');
						}
					}
				}
				$app->log_message(3,'Updated Product','Updated <strong>'.$results['data']['name'].'</strong>.');
				header('Location: '.$_SERVER['REQUEST_URI']);
				exit;
			}
		}
	}
}