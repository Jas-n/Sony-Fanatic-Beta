<?php class feature_values extends form{
	public $option;
	public $values;
	public function __construct($data=NULL){
		global $bootstrap,$products;
		$fv=$products->get_feature_values($_GET['id']);
		$this->category=$fv['category'];
		$this->option=	$fv['option'];
		$this->values=	$fv['values'];
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
				</tr>
			</thead>
			<tbody>');
				if($this->values){
					foreach($this->values as $id=>$value){
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
									'name'			=>'values['.$id.']',
									'placeholder'	=>'Value',
									'required'		=>2,
									'type'			=>'text',
									'value'			=>$value['value']
								));
							parent::add_html('</td>
						</tr>');
					}
				}
				parent::add_html('<tr class="bg-primary text-white">
					<th class="text-center" colspan="2">Add Feature Category</th>
				</tr>
				<tr>
					<td></td>
					<td>');
						parent::add_field(array(
							'name'			=>'new_value',
							'placeholder'	=>'Value',
							'required'		=>2,
							'type'			=>'text'
						));
					parent::add_html('</td>
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
				if($results['new_value']){
					$db->query(
						"INSERT INTO `feature_values` (
							`option_id`,`value`,`added`
						) VALUES (?,?,?)",
						array(
							$_GET['id'],
							$results['new_value'],
							DATE_TIME
						)
					);
					$app->set_message('success','Added '.$results['new_value'].' to feature values');
					$app->log_message(3,'New Feature Values','Added '.$results['new_value'].' to feature values');
				}
				if($results['values']){
					foreach($results['values'] as $id=>$value){
						$db->query(
							"Update `feature_values`
							SET `value`=?
							WHERE `id`=?",
							array(
								$value,
								$id
							)
						);
					}
				}
			}elseif($results['delete'] && $results['check']){
				$db->query("DELETE FROM `feature_values` WHERE `id` IN(".implode(',',$results['check']).")");
				$app->set_message('success','Deleted '.$db->rows_updated().' from feature values');
				$app->log_message(2,'Deleted Feature Values','Deleted '.$db->rows_updated().' from feature values');
			}
			$products->update_completions();
			$this->redirect();
		}
	}
}