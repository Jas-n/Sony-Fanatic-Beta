<?php class login extends form{
	public function __construct($data=NULL){
		parent::__construct('name='.__CLASS__);
		parent::add_field(array(
			'label'			=>'Email',
			'name'			=>'email',
			'placeholder'	=>'Email Address',
			'required'		=>1,
			'type'			=>'email',
			'value'			=>$data['email']
		));
		parent::add_field(array(
			'label'			=>'Password',
			'name'			=>'password',
			'placeholder'	=>'Password',
			'required'		=>1,
			'type'			=>'password'
		));
		parent::add_html('<p class="text-xs-center actions">');
			parent::add_button(array(
				'class'	=>'btn-primary btn-login',
				'name'	=>'login',
				'type'	=>'submit',
				'value'	=>'Login'
			));
			parent::add_html('<a class="btn btn-sm btn-secondary btn-forgot_password" href="./forgot">Reset Password</a>
		</p>');
	}
	public function process(){
		global $app,$db,$user;
		if($_POST['form_name']==$this->data['name']){
			if($_POST['login_forgot']){
				header('Location: forgot');
				exit;
			}else{
				$results=parent::process();
				$results=parent::unname($results['data']);
				if($password=$db->get_row(
					"SELECT
						`users`.`id`,`users`.`role_id`,`users`.`first_name`,`users`.`last_name`,`users`.`password`,
						`roles`.`role`
					FROM `users`
					LEFT JOIN `roles`
					ON `users`.`role_id`=`roles`.`id`
					WHERE `email`=?",
					$results['email']
				)){
					if(!password_verify($results['password'],$password['password'])){
						$app->log_message(2,'Failed Login','Incorrect password supplied for <strong>'.$password['first_name'].' '.$password['last_name'].'</strong>.');
						$app->set_message('error',"Email and password do not match");
						$this->redirect(false,$results);
					}else{
						$_SESSION['user_id']=$password['id'];
						$updates['last_login']=date('Y-m-d H:i:s');
						if(!$password['role']){
							$app->log_message(1,'Role Reassign','Could not find role so reassigned as "user"');
							$db->query(
								"UPDATE `users`
								SET `role_id`=4
								WHERE `id`=?",
								$password['id']
							);
						}
						$db->query(
							"UPDATE `users`
							SET `last_login`=?
							WHERE `id`=?",
							array(
								DATE_TIME,
								$password['id']
							)
						);
						if($_GET['url']){
							header('Location: '.$_GET['url']);
						}elseif(!$_GET['url']){
							header('Location: users');
						}
						exit;
					}
				}else{
					$app->log_message(2,'Failed Login','Login attempt for unlisted user using email <strong>'.$results['email'].'</strong>.');
					$app->set_message('error',"Username and password do not match");
					$this->redirect(false,$results);
				}
				$this->redirect(false,$results);
			}
		}
	}
}