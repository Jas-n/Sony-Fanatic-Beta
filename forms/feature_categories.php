<?php class feature_categories extends form{
	public function __construct($data=NULL){
		global $bootstrap,$products;
		$feature_categories=$products->get_feature_categories($_GET['id']);
		parent::__construct("name=".__CLASS__);
		parent::add_html('<table class="'.$bootstrap->table->classes->table.'">
			<thead>
				<tr class="'.$bootstrap->table->classes->header.'">
					<th>');
						parent::add_field(array(
							'class'	=>'check_all',
							'name'	=>'check_all',
							'type'	=>'checkbox',
							'value'	=>1
						));
					parent::add_html('</th>
					<th>Name</th>
					<th>Options</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($feature_categories){
					foreach($feature_categories as $id=>$feature_category){
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
									'value'			=>$feature_category['name']
								));
							parent::add_html('</td>
							<td>'.$feature_category['options'].'</td>
							<td><a class="btn btn-sm btn-primary" href="feature_options/'.$feature_category['id'].'">Options</a></td>
						</tr>');
					}
				}
				parent::add_html('<tr class="thead-default">
					<th class="text-center" colspan="4">Add Feature Category</th>
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
		global $app,$db;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=parent::unname($results['data']);
			if($results['update']){
				if($results['new_category']){
					$db->query(
						"INSERT INTO `feature_categories` (
							`name`
						) VALUES (?)",
						$results['new_category']
					);
					$app->set_message('success','Added '.$results['new_category'].' to feature categories');
					$app->log_message(3,'New Feature Categories','Added '.$results['new_category'].' to feature categories');
				}
				if($results['category']){
					foreach($results['category'] as $id=>$category){
						$db->query(
							"UPDATE `feature_categories`
							SET `name` =?
							WHERE `id`=?",
							array(
								$category,
								$id
							)
						);
					}
					$app->set_message('success','Updated '.sizeof($results['category']).' feature categories');
					$app->log_message(3,'Updated Feature Categories','Updated '.sizeof($results['category']).' feature categories');
				}
			}elseif($results['delete'] && $results['check']){
				$db->query("DELETE FROM `feature_categories` WHERE `id` IN(".implode(',',$results['check']).")");
				$app->set_message('success','Deleted '.$db->rows_updated().' from feature categories');
				$app->log_message(2,'Deleted Feature Categories','Deleted '.$db->rows_updated().' from feature categories');
			}
			$this->redirect();
		}
	}
}