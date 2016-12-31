<?php class feature_options extends form{
	public function __construct($data=NULL){
		global $products;
		$this->feature_category=$products->get_feature_category($_GET['id']);
		$feature_options=$products->get_feature_options($_GET['id']);
		parent::__construct("name=".__CLASS__."&class=form-inline");
		parent::add_html('<table class="table table-hover table-striped table-sm">
			<thead>
				<tr class="thead-default">
					<th>');
						parent::add_field(array(
							'class'	=>'check_all',
							'name'	=>'check_all',
							'type'	=>'checkbox',
							'value'	=>1
						));
					parent::add_html('</th>
					<th>Name</th>
					<th>Values</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($feature_options){
					foreach($feature_options as $id=>$feature_option){
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
									'name'		=>'option['.$id.']',
									'placeholder'=>'Option',
									'required'	=>1,
									'type'		=>'text',
									'value'		=>$feature_option['name']
								));
							parent::add_html('</td>
							<td>'.number_format($feature_option['values']).'</td>
							<td><a class="btn btn-sm btn-primary" href="feature_values/'.$feature_option['id'].'">Values</a></td>
						</tr>');
					}
				}
				parent::add_html('<tr class="thead-default">
					<th class="text-xs-center" colspan="4">Add Feature Option</th>
				</tr>
				<tr>
					<td></td>
					<td>');
						parent::add_field(array(
							'name'			=>'new_option',
							'placeholder'	=>'Option',
							'required'		=>2,
							'type'			=>'text'
						));
					parent::add_html('</td>
					<td colspan="2"></td>
				</tr>
			</tbody>
		</table>
		<p class="text-xs-center">');
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
				if($results['new_option']){
					$db->query(
						"INSERT INTO `feature_options` (
							`category_id`,`name`,`added`
						) VALUES (?,?,?)",
						array(
							$_GET['id'],
							$results['new_option'],
							DATE_TIME
						)
					);
					$app->set_message('success','Added '.$results['new_option'].' to feature options');
					$app->log_message(3,'New Feature Option','Added '.$results['new_option'].' to feature options');
				}
				if($results['option']){
					foreach($results['option'] as $id=>$option){
						$db->query(
							"UPDATE `feature_options`
							SET `name`=?
							WHERE `id`=?",
							array(
								$option,
								$id
							)
						);
					}
					$app->set_message('success','Updated <strong>'.sizeof($results['option']).'</strong> feature options');
					$app->log_message(3,'Updated Feature Options','Updated <strong>'.sizeof($results['option']).'</strong> feature options');
				}
			}elseif($results['delete'] && $results['check']){
				$db->query("DELETE FROM `feature_options` WHERE `id` IN(".implode(',',$results['check']).")");
				$app->set_message('success','Deleted '.$db->rows_updated().' from feature options');
				$app->log_message(2,'Deleted Feature Option','Deleted '.$db->rows_updated().' from feature options');
			}
			$this->redirect();
		}
	}
}