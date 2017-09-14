<?php class edit_product extends form{
	private $brands;
	public $product;
	public function __construct($data=NULL){
		global $bootstrap,$products;
		$this->product=new product($_GET['id']);
		$this->brands=$products->get_brands();
		$brands=$this->brands;
		$brands=$this->optioner(tree($brands),'brand');
		$categories=$this->optioner($products->get_category_tree(),'name');
		parent::__construct("name=".__CLASS__.'&hide_required_message=1');
		parent::add_html('<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#details" role="tab">Details</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#associations" role="tab">Associations</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#media" role="tab">Media</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#purchasing" role="tab">Puchacing</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#articles" role="tab">Articles</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="details" role="tabpanel">');
				parent::add_select(
					array(
						'label'	=>'Status',
						'name'	=>'status',
						'value'	=>$this->product->status
					),
					array(
						0=>'Disabled',
						1=>'Enabled'
					)
				);
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
					)
				));
			parent::add_html('</div>
			<div class="tab-pane" id="associations" role="tabpanel">
				<div class="row">
					<div class="col-md-6">');
						parent::add_select(
							array(
								'label'		=>'Category',
								'name'		=>'categories[]',
								'multiple'	=>1,
								'required'	=>1,
								'value'		=>array_keys($this->product->categories)
							),
							$categories
						);
					parent::add_html('</div>
					<div class="col-md-6">');
						parent::add_fields(array(
							array(
								'class'			=>'ajax_tags',
								'label'			=>'Tag',
								'name'			=>'tag',
								'note'			=>'Start typing for list of matching tags.',
								'placeholder'	=>'Tag',
								'type'			=>'text',
								'postfield'		=>'<i class="fa fa-refresh"></i>'
							),
							array(
								'class'		=>'ajax_tag_id',
								'name'		=>'tag_id',
								'type'		=>'hidden'
							)
						));
						parent::add_html('<p id="product_tags">Existing Tags<br>');
							if($this->product->tags){
								foreach($this->product->tags as $tag){
									parent::add_html('<span>'.$tag['tag'].' <a class="badge badge-danger delete delete_tag" data-id="'.$tag['link_id'].'"><i class="fa fa-fw fa-times"></i></a></span>');
								}
							}
						parent::add_html('</p>
					</div>
				</div>
				<table class="'.$bootstrap->table->classes->table.'">
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
									<td>'.$feature['category'].'</td>
									<td>'.$feature['feature'].'</td>
									<td>'.$feature['value'].'</td>
									<td><a class="btn btn-sm btn-danger delete delete_value" data-id="'.$feature['id'].'"><i class="fa fa-times"></i></a></td>
								</tr>');
							}
						}
						parent::add_html('<tr class="new_row">
							<td>');
								parent::add_field(array(
									'name'			=>'cfv_category',
									'note'			=>'Start typing for list of matching categories.',
									'placeholder'	=>'Category',
									'type'			=>'text',
									'postfield'		=>'<i class="fa fa-refresh"></i>'
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'			=>'cfv_feature',
									'note'			=>'Start typing for list of matching features.',
									'placeholder'	=>'Feature',
									'type'			=>'text',
									'postfield'		=>'<i class="fa fa-refresh"></i>'
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'			=>'cfv_value',
									'note'			=>'Start typing for list of matching values.',
									'placeholder'	=>'Value',
									'type'			=>'text',
									'postfield'		=>'<i class="fa fa-refresh"></i>'
								));
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
				if($this->product->images['thumb']){
					parent::add_html('<div class="product_images row">');
						foreach($this->product->images['thumb'] as $i=>$thumbnail){
							parent::add_html('<a class="card card-body" href="'.$this->product->images['full'][$i].'" target="_blank"><img class="img-thumbnail" src="'.$thumbnail.'"></a>');
						}
					parent::add_html('</div>');
				}
			parent::add_html('</div>
			<div class="tab-pane" id="purchasing" role="tabpanel">
				<table class="'.$bootstrap->table->classes->table.'">
					<thead>
						<tr>
							<th>Title</th>
							<th>Link</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>');
						if($this->product->links){
							foreach($this->product->links as $link){
								parent::add_html('<tr>
									<td>'.$link['title'].'</td>
									<td>'.$link['link'].'</td>
									<td><a class="btn btn-sm btn-danger delete delete_link" data-id="'.$link['id'].'"><i class="fa fa-times"></i></a></td>
								</tr>');
							}
						}
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'name'		=>'new_title',
									'placeholder'=>'Link Title',
									'type'		=>'text'
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'		=>'new_link',
									'placeholder'=>'Link URL',
									'type'		=>'url'
								));
							parent::add_html('</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="tab-pane" id="articles" role="tabpanel">
				<table class="'.$bootstrap->table->classes->table.'">
					<thead>
						<tr>
							<th>Title</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>');
						if($this->product->articles['count']){
							foreach($this->product->articles['data'] as $article){
								parent::add_html('<tr>
									<td>'.$article['title'].'</td>
									<td>
										<a class="btn btn-sm btn-primary" href="article/'.$article['id'].'">View</a></td>
								</tr>');
							}
						}
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'name'		=>'new_title',
									'placeholder'=>'Link Title',
									'type'		=>'text'
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'		=>'new_link',
									'placeholder'=>'Link URL',
									'type'		=>'url'
								));
							parent::add_html('</td>
							<td></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<p class="text-center">');
			parent::add_button(array(
				'class' =>'btn-primary',
				'name'  =>'update',
				'type'  =>'submit',
				'value' =>'Update'
			));
		parent::add_html('</p>');
	}
	public function process(){
		if($_POST['form_name']==$this->data['name']){
			global $app,$db,$products;
			$results=parent::process();
			$results['data']=parent::unname($results['data']);
			if($results['files']){
				$results['files']=parent::unname($results['files']);
			}
			$db->query(
				"UPDATE `products`
				SET
					`brand_id`=?,
					`status`=?,
					`name`=?,
					`excerpt`=?,
					`description`=?,
					
					`published`=?,
					`updated`=?
				WHERE `id`=?",
				array(
					$results['data']['brand'],
					$results['data']['status'],
					$results['data']['name'],
					$results['data']['excerpt'],
					$results['data']['description'],
					DATE_TIME,
					$results['data']['status']==1&&$this->product->status!=1?DATE_TIME:$this->product->published,
					$_GET['id']
				),0
			);
			$db->query("DELETE FROM `product_categories` WHERE `product`=?",$_GET['id']);
			foreach($results['data']['categories'] as $category){
				$db->query(
					"INSERT INTO `product_categories` (
						`product`,`category`
					) VALUES (?,?)",
					array(
						$_GET['id'],
						$category
					)
				);
			}
			if($results['data']['new_link']){
				$db->query(
					"INSERT INTO `product_links` (
						`product_id`,`title`,`link`
					) VALUES (?,?,?)",
					array(
						$_GET['id'],
						$results['data']['new_title'],
						$results['data']['new_link']
					)
				);
			}
			if($results['stats']['uploaded_files']){
				$images=$results['files']['images'];
				if(!is_dir(ROOT.$this->product->dir)){
					mkdir(ROOT.$this->product->dir,0777,1);
				}
				foreach($images['name'] as $i=>$name){
					$j=0;
					list($width,$height)=getimagesize($images['tmp_name'][$i]);
					while(is_file(ROOT.$this->product->dir.$j.'_thumb.png')){
						$j++;
					}
					smart_resize_image($images['tmp_name'][$i],NULL,150,0,1,ROOT.$this->product->dir.$j.'_thumb.png',0,'png');
					smart_resize_image($images['tmp_name'][$i],NULL,640,360,0,ROOT.$this->product->dir.$j.'_medium.png',0,'png');
					smart_resize_image($images['tmp_name'][$i],NULL,1920,1080,0,ROOT.$this->product->dir.$j.'_full.png',0,'png');
				}
			}
			if($results['data']['status']==1 && $this->product->tweeted==0){
				$twitter=new twitter();
				if($media=glob(ROOT.$this->product->dir.'*_medium.png')){
					$media=array_slice($media,0,4);
				}
				$twitter->tweet('New Product: '.$this->brands[$results['data']['brand']]['brand'].' '.$results['data']['name'].'. Compare now: '.SERVER_NAME.'p/'.$_GET['id'].'-'.$this->product->slug,$media);
				$db->query(
					"UPDATE `products`
					SET
						`tweeted`=?,
						`updated`=?
					WHERE `id`=?",
					array(
						1,
						DATE_TIME,
						$_GET['id']
					)
				);
			}
			$app->log_message(3,'Updated Product','Updated <strong>'.$results['data']['name'].'</strong>.');
			$app->set_message('success','Updated <strong>'.$results['data']['name'].'</strong>.');
			$products->generate_menus();
			$products->update_completions($_GET['id']);
			$this->redirect();
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