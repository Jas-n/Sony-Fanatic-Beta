<?php class tags extends form{
	public $count=0;
	public function __construct(){
		global $bootstrap,$db;
		$tags=$db->query(
			"SELECT
				*,
				(SELECT COUNT(`id`) FROM `product_tags` WHERE `tag`=`tags`.`id`) as `products`
			FROM `tags`
			ORDER BY `tag` ASC".
			SQL_LIMIT
		);
		$tag_count=$db->result_count('FROM `tags`');
		parent::__construct("name=".__CLASS__);
		parent::add_html('<table class="'.$bootstrap->table->classes->table.' table-fixed">
			<thead>
				<tr>
					<th>');
						parent::add_field(array(
							'class'	=>'check_all',
							'name'	=>'check_all',
							'type'	=>'checkbox',
							'value'	=>1
						));
					parent::add_html('</th>
					<th>Tag</th>
					<th>Products</th>
				</tr>
			</thead>
			<tbody>');
				if($tags){
					foreach($tags as $i=>$tag){
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'class'	=>'check',
									'name'	=>'check[]',
									'type'	=>'checkbox',
									'value'	=>$tag['id']
								));
							parent::add_html('</td>
							<td>'.$tag['tag'].'</td>
							<td>'.$tag['products'].'</td>
						</tr>');
					}
				}
			parent::add_html('</tbody>
		</table>'.
		pagination($tag_count,0).
		'<p class="text-center">');
			parent::add_button(array(
				'class'	=>'btn-danger delete',
				'name'	=>'delete',
				'type'	=>'submit',
				'value'	=>'Delete'
			));
		parent::add_html('</p>');
	}
	public function process(){
		global $app,$db,$user;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=parent::unname($results['data']);
			if($results['delete']){
				if(is_array($results['check'])){
					$placeholder_string=implode(',',array_pad([],sizeof($results['check']),'?'));
					$db->query("DELETE FROM `tags` WHERE `id` IN (".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_tags` WHERE `tag` IN (".$placeholder_string.")",$results['check']);
				}else{
					$app->set_message('error','No tags were selected for deletion.');
					$this->redirect(false,$results);
				}
			}
			$this->redirect();
		}
	}
}