<?php class add_article extends form{
	public function __construct($data=NULL){
		global $articles;
		parent::__construct("name=".__CLASS__);
		parent::add_fields(array(
			array(
				'class'			=>'ajax_product',
				'label'			=>'Product',
				'name'			=>'product',
				'note'			=>'Start typing for list of matching products.<br>Products can be added via <a href="products">Products</a> &gt; <a href="add_product">New</a>',
				'placeholder'	=>'Product',
				'type'			=>'text',
				'postfield'		=>"<i class='fa fa-refresh'></i>",
				'value'			=>$data?$data['product']:''
			),
			array(
				'class'		=>'ajax_product_id',
				'name'		=>'product_id',
				'type'		=>'hidden',
				'value'		=>$data?$data['product_id']:''
			)
		));
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
		parent::add_html('<p class="text-center">');
			parent::add_button(array(
				'class' =>'btn-success',
				'name'  =>'add',
				'type'  =>'submit',
				'value' =>'Add'
			));
		parent::add_html('</p>');
	}
	public function process(){
		if($_POST['form_name']==$this->data['name']){
			global $app,$db;
			$results=parent::process();
			$results['data']=parent::unname($results['data']);
			$results['files']=parent::unname($results['files']);
			$id=$db->next_hex_id('products');
			$slug=str_replace('&hellip;','',crop(slug($results['data']['title']),101));
			$db->query(
				"INSERT INTO `articles` (
					`id`,		`type`,		`status`,	`title`,	`slug`,
					`excerpt`,	`content`,	`added`,	`updated`,	`published`
				) VALUES (?,?,?,?,?,	?,?,?,?,?)",
				array(
					$id,
					$results['data']['type'],
					$results['data']['status'],
					$results['data']['title'],
					$slug,
				
					$results['data']['excerpt'],
					$results['data']['content'],
					DATE_TIME,
					DATE_TIME,
					$results['data']['status']==2?DATE_TIME:'0000-00-00 00:00:00'
				),0
			);
			if($results['data']['product_id']){
				$db->query(
					"INSERT INTO `article_products` (
						`article_id`,`product_id`
					) VALUES (?,?)",
					array(
						$id,
						$results['data']['product_id'],
					)
				);
			}
			$app->log_message(3,'Added PArticle','Added <strong>'.$results['data']['title'].'</strong> to articles.');
			if($results['data']['status']==2){
				/*$twitter=new twitter();
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
				);*/
			}
			header('Location: ./article/'.$id);
			exit;
		}
	}
	private function optioner($items,$level=0){
		$list=array();
		if($items){
			foreach($items as $item){
				$list[$item['id']]=implode('',array_pad([],$level,'-&nbsp;&nbsp;')).$item['brand'];
				if($item['children']){
					$list=$list+$this->optioner($item['children'],$level+1);
				}
			}
		}
		return $list;
	}
}