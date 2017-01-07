<?php class list_users extends form{
	public $count=0;
	public function __construct($ids=NULL){
		global $bootstrap,$page,$user;
		$this->ids=$ids;
		$users=$user->get_users(0,0,0,$this->ids);
		$this->count=$users['count'];
		parent::__construct("name=users");
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
					<th>Avatar</th>
					<th>Name</th>
					<th>Role</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>');
				if($users['count']){
					foreach($users['users'] as $i=>$usr){
						parent::add_html('<tr'.($usr['can_access']?'':' class="table-danger"').'>
							<td>');
								if($usr['id']!=$user->id && $usr['id']!=0){
									parent::add_field(array(
										'class'	=>'check',
										'name'	=>'check[]',
										'type'	=>'checkbox',
										'value'	=>$usr['id']
									));
								}
							parent::add_html('</td>
							<td><img class="avatar" src="'.$user->get_avatar($usr['id'],50).'" height="50"></td>
							<td>'.$usr['title']." ".$usr['first_name']." ".$usr['initials']." ".$usr['last_name'].'</td>
							<td>'.implode(',<br>',$usr['roles']).'</td>
							<td>');
								if($usr['id']!=0){
									parent::add_html('<a class="btn btn-primary btn-sm" href="user/'.$usr['id'].'">View</a> <a class="btn btn-secondary btn-sm" href="mailto:'.$usr['email'].'" title="Email '.$usr['first_name'].' '.$usr['last_name'].'">Email</a>');
								}
							parent::add_html('</td>
						</tr>');
					}
				}
			parent::add_html('</tbody>
		</table>'.
		pagination($users['count'],0).
		'<p class="text-center">');
			parent::add_button(array(
				'class'	=>'btn-info',
				'name'	=>'reset',
				'type'	=>'submit',
				'value'	=>'Reset Password'
			));
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
		parent::add_html('</p>');
	}
	public function process(){
		global $app,$db,$user;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=parent::unname($results['data']);
			$placeholder_string=implode(',',array_pad([],sizeof($results['check']),'?'));
			# If reset is clicked
			if($results['reset']){
				if(is_array($results['check'])){
					$user->reset_password($results['check']);
				}else{
					$app->set_message('error','No users were selected for password resetting.');
					$this->redirect(false,$results);
				}
			}
			# If enable is clicked
			elseif($results['enable']){
				if(is_array($results['check'])){
					$db->query(
						"UPDATE `users`
						SET
							`can_access`=?,
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
					$app->set_message('success',$updated.' users were marked as active');
					$app->log_message(3,'Users Enabled',$updated.' users were marked as active');
				}else{
					$app->set_message('error','No users were selected for user enabling.');
					$this->redirect(false,$results);
				}
			}
			# If disable is clicked
			elseif($results['disable']){
				if(is_array($results['check'])){
					$db->query(
						"UPDATE `users`
						SET
							`can_access`=?,
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
					$app->log_message(2,'Users Disabled',$updated.' users were marked as inactive');
					$app->set_message('success',$updated.' users were marked as active');
				}else{
					$app->set_message('error','No users were selected for password disabling.');
					$this->redirect(false,$results);
				}
			}
			# If delete is clicked
			elseif($results['delete']){
				if(is_array($results['check'])){
					$user->delete_users($results['check']);
				}else{
					$app->set_message('error','No users were selected for deletion.');
					$this->redirect(false,$results);
				}
			}
			$this->redirect();
		}
	}
}