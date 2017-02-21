<?php class list_products extends form{
	public function __construct(){
		global $bootstrap,$products;
		$product_list=$products->get_products();
		parent::__construct("name=users");
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
					<th>Brand</th>
					<th>Name</th>
					<th>Completion</th>
					<th>Updated</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($product_list['count']){
					foreach($product_list['data'] as $product){
						parent::add_html('<tr'.(!$product['status']?' class="table-danger"':'').'>
							<td>');
								parent::add_field(array(
									'class'	=>'check',
									'name'	=>'check[]',
									'type'	=>'checkbox',
									'value'	=>$product['id']
								));
							parent::add_html('</td>
							<td>'.$product['brand'].'</td>
							<td>'.$product['name'].'</td>
							<td>'.$product['completion'].'%</td>
							<td>'.sql_datetime($product['updated']).'</td>
							<td>
								<a class="btn btn-sm btn-primary" href="product/'.$product['id'].'">Edit</a>
								<a class="btn btn-sm btn-secondary" href="../p/'.$product['id'].'-'.$product['slug'].'" target="_blank">View</a>
							</td>
						</tr>');
					}
				}
			parent::add_html('</tbody>
		</table>
		<div class="d-flex justify-content-around">'.
			pagination($product_list['count'],0).
			'<div>');
				parent::add_button(array(
					'class'	=>'btn-success',
					'name'	=>'enable',
					'type'	=>'submit',
					'value'	=>'Enable'
				));
				parent::add_button(array(
					'class'	=>'btn-warning',
					'name'	=>'disable',
					'type'	=>'submit',
					'value'	=>'Disable'
				));
				parent::add_button(array(
					'class'	=>'btn-danger delete',
					'name'	=>'delete',
					'type'	=>'submit',
					'value'	=>'Delete'
				));
			parent::add_html('</div>
		</div>');
	}
	public function process(){
		global $app,$db;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=parent::unname($results['data']);
			$placeholder_string=implode(',',array_pad([],sizeof($results['check']),'?'));
			# If enable is clicked
			if($results['enable']){
				if(is_array($results['check'])){
					$db->query(
						"UPDATE `products`
						SET
							`status`=?,
							`updated`=?
						WHERE `id` IN(".$placeholder_string.")",
						array_merge(
							array(
								1,
								DATE_TIME
							),
							$results['check']
						)
					);
					$updated=$db->rows_updated();
					$app->set_message('success',$updated.' products were marked as active');
					$app->log_message(3,'Users Enabled',$updated.' products were marked as active');
				}else{
					$app->set_message('error','No products were selected for password enabling.');
					$this->redirect(false,$results);
				}
			}
			# If disable is clicked
			elseif($results['disable']){
				if(is_array($results['check'])){
					$db->query(
						"UPDATE `products`
						SET
							`status`=?,
							`updated`=?
						WHERE `id` IN(".$placeholder_string.")",
						array_merge(
							array(
								0,
								DATE_TIME
							),
							$results['check']
						)
					);
					$updated=$db->rows_updated();
					$app->log_message(2,'Products Disabled',$updated.' products were marked as inactive');
					$app->set_message('success',$updated.' products were marked as active');
				}else{
					$app->set_message('error','No products were selected for product disabling.');
					$this->redirect(false,$results);
				}
			}
			# If delete is clicked
			elseif($results['delete']){
				if(is_array($results['check'])){
					# Get Product Names
					$names=$db->query("SELECT `name` FROM `products` WHERE `id` IN(".$placeholder_string.") ORDER BY `name` ASC",$results['check']);
					$names=array_column($names,'name');
					$db->query("DELETE FROM `products` WHERE `id` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `article_products` WHERE `product_id` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_catalogue` WHERE `product` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_categories` WHERE `product` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_links` WHERE `product_id` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_tags` WHERE `product` IN(".$placeholder_string.")",$results['check']);
					$db->query("DELETE FROM `product_values` WHERE `product` IN(".$placeholder_string.")",$results['check']);
					$app->log_message(1,'Delete products','Deleted <strong>'.implode('</strong>, <strong>',$names).'</strong> from products.');
					$app->set_message('success','Deleted <strong>'.implode('</strong>, <strong>',$names).'</strong> from products.');
				}else{
					$app->set_message('error','No products were selected for deletion.');
					$this->redirect(false,$results);
				}
			}
			$this->redirect();
		}
	}
}