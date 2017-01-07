<?php class brands extends form{
	public function __construct($data=NULL){
		global $bootstrap,$products;
		$brands=$products->get_child_brands($_GET['id']);
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
					<th>Slug</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($brands){
					foreach($brands as $id=>$brand){
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'class'	=>'check',
									'name'	=>'check[]',
									'type'	=>'checkbox',
									'value'	=>$id
								));
							parent::add_html('</td>
							<td>'.$brand['brand'].'</td>
							<td>'.$brand['slug'].'</td>
							<td>'.(!$_GET['id']?'<a class="btn btn-sm btn-primary" href="brands/'.$id.'">Sub-brands</a>':'').'</td>
						</tr>');
					}
				}
				parent::add_html('<tr>
					<th class="text-center" colspan="4">Add Brand</th>
				</tr>
				<tr>
					<td></td>
					<td>');
						parent::add_field(array(
							'name'			=>'new_brand',
							'placeholder'	=>'Brand',
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
				if($results['new_brand']){
					$db->query(
						"INSERT INTO `brands` (
							`parent_id`,`brand`,`slug`
						) VALUES (?,?,?)",
						array(
							$_GET['id'],
							$results['new_brand'],
							slug($results['new_brand'])
						)
					);
					$app->set_message('success','Added '.$results['new_brand'].' to brands');
					$app->log_message(3,'New Brand','Added '.$results['new_brand'].' to brands');
				}
			}elseif($results['delete'] && $results['check']){
				$db->query("DELETE FROM `brands` WHERE `id` IN(".implode(',',$results['check']).")");
				$app->set_message('success','Deleted '.$db->rows_updated().' from brands');
				$app->log_message(2,'Deleted Brands','Deleted '.$db->rows_updated().' from brands');
			}
			$this->redirect();
		}
	}
}