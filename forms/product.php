<?php class edit_product extends form{
	public function __construct($data=NULL){
		global $products;
		$this->product=new product($_GET['id']);
		$feature_categories=$products->get_feature_categories();
		$feature_categories=array_combine(array_keys($feature_categories),array_column($feature_categories,'name'));
		$brands=$products->get_brands();
		$brands=tree($brands);
		foreach($brands as $brand){
			$select[$brand['id']]=$brand['brand'];
			if($brand['children']){
				foreach($brand['children'] as $child){
					$select[$child['id']]='-&nbsp;&nbsp;'.$child['brand'];
				}
			}
		}
		parent::__construct("name=".__CLASS__);
		parent::add_html('<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#details" role="tab">Details</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#associations" role="tab">Associations</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#media" role="tab">Media</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="details" role="tabpanel">');
				parent::add_select(
					array(
						'label'	=>'Brand',
						'name'	=>'brand',
						'value'	=>$this->product->brand_id
					),
					$select,
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
					)
				));
			parent::add_html('</div>
			<div class="tab-pane" id="associations" role="tabpanel">');
				parent::add_fields(array(
					array(
						'class'			=>'ajax_tags',
						'label'			=>'Tag',
						'name'			=>'Tag',
						'note'			=>'Start typing for list of matching tags.',
						'placeholder'	=>'Tag',
						'type'			=>'text',
						'postfield'		=>"<i class='fa fa-refresh'></i>"
					),
					array(
						'class'		=>'ajax_tag_id',
						'name'		=>'tag_id',
						'type'		=>'hidden'
					),
					array(
						'label'	=>'Tags',
						'name'	=>'existing_tags',
						'type'	=>'static',
						'value'	=>''
					)
				));
				parent::add_html('<table class="table table-sm table-hover table-striped">
					<thead>
						<tr>
							<th>Category</th>
							<th>Feature</th>
							<th>Value</th>
							<th>Delete</th>
						</tr>
					</thead>
					<tbody>');
						if($this->product->features){
							foreach($this->product->features as $feature){
								parent::add_html('<tr>
									<td>'.$feature['category'].'</td>	<td>'.$feature['feature'].'</td>	<td>'.$feature['value'].'</td>
									<td><a class="btn btn-sm btn-danger delete_value" data-id="'.$feature['id'].'"><i class="fa fa-times"></i></a></td>
								</tr>');
							}
						}
						parent::add_html('<tr class="new_row">
							<td>');
								parent::add_select(
									array(
										'name'=>'new_category'
									),
									$feature_categories,
									'Select&hellip;'
								);
							parent::add_html('</td>
							<td>');
								parent::add_select(
									array(
										'name'=>'new_feature'
									),
									array(),
									'Loading&hellip;'
								);
							parent::add_html('</td>
							<td>');
								parent::add_select(
									array(
										'name'=>'new_value'
									),
									array(),
									'Loading&hellip;'
								);
							parent::add_html('</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="media" role="tabpanel">');
				parent::add_field(array(
					'accept'	=>'jpeg,jpg,png',
					'label'		=>'Images',
					'multiple'	=>1,
					'name'		=>'images[]',
					'type'		=>'file'
				));
				if($this->product->images['thumbnail']){
					parent::add_html('<div class="product_images">');
						foreach($this->product->images['thumbnail'] as $thumbnail){
							parent::add_html('<a href="'.$thumbnail.'" target="_blank"><img class="img-thumbnail" src="'.$thumbnail.'"></a>');
						}
					parent::add_html('</div>');
				}
			parent::add_html('</div>
		</div>
		<p class="text-xs-center">');
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
					smart_resize_image($images['tmp_name'][$i],NULL,640,360,0,ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_medium.png',0,'png');
					smart_resize_image($images['tmp_name'][$i],NULL,1920,1080,0,ROOT.'uploads/products/'.$this->product->brand_slug.'/'.$this->product->id.'/'.$j.'_full.png',0,'png');
				}
			}
			$app->log_message(3,'Updated Product','Updated <strong>'.$results['data']['name'].'</strong>.');
			$this->redirect();
		}
	}
}