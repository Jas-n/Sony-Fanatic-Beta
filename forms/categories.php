<?php class categories extends form{
	public function __construct($data=NULL){
		global $bootstrap,$products;
		$categories=$products->get_categories($_GET['id']);
		parent::__construct("name=".__CLASS__.'&hide_required_message=1');
		parent::add_html('<table class="'.$bootstrap->table->classes->table.'">
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
					<th>Name</th>
					<th>Children</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($categories){
					foreach($categories as $id=>$category){
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'class'	=>'check',
									'name'	=>'check[]',
									'type'	=>'checkbox',
									'value'	=>$id
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'			=>'category['.$id.']',
									'placeholder'	=>'Category',
									'required'		=>1,
									'type'			=>'text',
									'value'			=>$category['name']
								));
							parent::add_html('</td>
							<td>'.$category['children'].'</td>
							<td><a class="btn btn-sm btn-primary" href="categories/'.$category['id'].'">Children</a></td>
						</tr>');
					}
				}
				parent::add_html('<tr class="bg-primary text-white">
					<th class="text-center" colspan="4">Add Category</th>
				</tr>
				<tr>
					<td></td>
					<td>');
						parent::add_field(array(
							'name'			=>'new_category',
							'placeholder'	=>'Category',
							'required'		=>2,
							'type'			=>'text'
						));
					parent::add_html('</td>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>
		<p class="text-center">');
			parent::add_button(array(
				'class'	=>'btn-primary',
				'name'	=>'update',
				'type'	=>'submit',
				'value'	=>'Update'
			));
			parent::add_button(array(
				'class'	=>'btn-danger delete',
				'name'	=>'delete',
				'type'	=>'submit',
				'value'	=>'Delete'
			));
		parent::add_html('</p>');
	}
	public function process(){
		global $app,$db,$products;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=parent::unname($results['data']);
			if($results['update']){
				if($results['new_category']){
					$db->query(
						"INSERT INTO `categories` (
							`parent_id`,`name`
						) VALUES (?,?)",
						array(
							$_GET['id']?$_GET['id']:0,
							$results['new_category']
						)
					);
					$app->set_message('success','Added '.$results['new_category'].' to categories');
					$app->log_message(3,'New Category','Added '.$results['new_category'].' to categories');
				}
				if($results['category']){
					foreach($results['category'] as $id=>$category){
						$db->query(
							"UPDATE `categories`
							SET `name` =?
							WHERE `id`=?",
							array(
								$category,
								$id
							)
						);
					}
					$app->set_message('success','Updated '.sizeof($results['category']).' categories');
					$app->log_message(3,'Updated Categories','Updated '.sizeof($results['category']).' categories');
				}
			}elseif($results['delete'] && $results['check']){
				$db->query("DELETE FROM `categories` WHERE `id` IN(".implode(',',$results['check']).")");
				$app->set_message('success','Deleted '.$db->rows_updated().' from categories');
				$app->log_message(2,'Deleted Categories','Deleted '.$db->rows_updated().' from categories');
			}
			$products->generate_menus();
			$this->redirect();
		}
	}
}