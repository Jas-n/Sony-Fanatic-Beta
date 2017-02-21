<?php class item_article extends form{
	public function __construct($data=NULL){
		global $article,$articles,$bootstrap;
		parent::__construct("name=".__CLASS__.'&hide_required_message=1');
		parent::add_html('<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#article" role="tab">Article</a></li>
			<li class="nav-item"><a class="nav-link" data-toggle="tab" href="#products" role="tab">Products</a></li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="article" role="tabpanel">');
				parent::add_select(
					array(
						'label'		=>'Type',
						'name'		=>'type',
						'required'	=>1,
						'value'		=>$article['type']
					),
					$articles->types(),'Select&hellip;'
				);
				parent::add_select(
					array(
						'label'	=>'Status',
						'name'	=>'status',
						'value'	=>$article['status_id']
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
						'type'		=>'text',
						'value'		=>$article['title']
					),
					array(
						'label'		=>'Excerpt',
						'maxlength'	=>160,
						'name'		=>'excerpt',
						'placeholder'=>'Excerpt',
						'required'	=>1,
						'rows'		=>3,
						'type'		=>'textarea',
						'value'		=>$article['excerpt']
					),
					array(
						'class'		=>'tinymce',
						'label'		=>'Content',
						'name'		=>'content',
						'required'	=>1,
						'type'		=>'textarea',
						'value'		=>$article['content']
					)
				));
			parent::add_html('</div>
			<div class="tab-pane" id="products" role="tabpanel">Populate what\'s on line '.__LINE__.$bootstrap->list_group([],array('id'=>'product_list'),true));
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
						'value'			=>$article['product']['name']
					),
					array(
						'class'		=>'ajax_product_id',
						'name'		=>'product_id',
						'type'		=>'hidden',
						'value'		=>$article['product']['id']
					)
				));
			parent::add_html('</div>
		</div>
		<p class="text-center">');
			parent::add_button(array(
				'class' =>'btn-primary',
				'name'  =>'save',
				'type'  =>'submit',
				'value' =>'Save'
			));
		parent::add_html('</p>');
	}
	public function process(){
		if($_POST['form_name']==$this->data['name']){
			global $article,$app,$db;
			$results=parent::process();
			$results['data']=parent::unname($results['data']);
			$results['files']=parent::unname($results['files']);
			$sets=array(
				'product_id',
				'type',
				'status',
				'title',
				'excerpt',

				'content',
				'updated'
			);
			$options=array(
				$results['data']['product_id'],
				$results['data']['type'],
				$results['data']['status'],
				$results['data']['title'],
				$results['data']['excerpt'],

				$results['data']['content'],
				DATE_TIME
			);
			if($results['data']['status']==2 && $article['published']=='0000-00-00 00:00:00'){
				$sets[]='published';
				$options[]=DATE_TIME;
				$twitter=new twitter();
				$twitter->tweet('New Article: '.$results['data']['title'].'. Read now: '.SERVER_NAME.'n/'.$id.'-'.$slug);
				$db->query(
					"UPDATE `articles`
					SET
						`tweeted`=?,
						`updated`=?
					WHERE `id`=?",
					array(
						1,
						DATE_TIME,
						$id
					)
				);
			}
			$options[]=$_GET['id'];
			$db->query(
				"UPDATE `articles`
				SET `".implode("`=?,`",$sets)."`=?
				WHERE `id`=?",
				$options,
				0
			);
			$app->set_message('success','Successfully updated article.');
			$app->log_message(3,'Article Updated','Updated <strong>'.$results['data']['title'].'</strong>.');
			$this->redirect();
		}
	}
}