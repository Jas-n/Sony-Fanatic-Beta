<?php class brands extends form{
	public function __construct($data=NULL){
		global $products;
		$brands=$products->get_brands();
		parent::__construct("name=".__CLASS__."&class=form-inline");
		parent::add_html('<table class="table table-hover table-striped table-sm">
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
					<th>Type</th>
				</tr>
			</thead>
			<tbody>');
				if($brands){
					foreach($brands as $id=>$brand){
						parent::add_html('<tr>
							<td>');
								parent::add_field(array(
									'class'		=>'check',
									'name'		=>'check[]',
									'type'		=>'checkbox',
									'value'		=>$id
								));
							parent::add_html('</td>
							<td>');
								parent::add_field(array(
									'name'			=>'brand['.$id.']',
									'placeholder'	=>'Brand',
									'required'		=>1,
									'type'			=>'text',
									'value'			=>$brand
								));
							parent::add_html('</td>
						</tr>');
					}
				}
				parent::add_html('<tr>
					<th class="text-xs-center" colspan="2">Add Brand</th>
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
			if($results['status']!='error'){
				$results=parent::unname($results['data']);
				if($results['update']){
					if($results['brand']){
						foreach($results['brand'] as $id=>$brand){
							$db->query(
								"UPDATE `brands`
								SET `brand`=?
								WHERE `id`=?",
								array(
									$brand,
									$id
								)
							);
						}
						$app->log_message(3,'Updated Brands','Updated brands');
					}
					if($results['new_brand']){
						$db->query("INSERT INTO `brands` (`brand`) VALUES (?)",$results['new_brand']);
						$app->log_message(3,'New Brand','Added '.$results['new_brand'].' to brands');
					}
				}elseif($results['delete']){
					$db->query("DELETE FROM `brands` WHERE `id` IN(".implode(',',$results['check']).")");
					$app->log_message(2,'Deleted Brands','Deleted '.$db->rows_updated().' from brands');
				}
				$this->reload();
			}
		}
	}
}