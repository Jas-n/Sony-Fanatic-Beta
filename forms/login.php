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
					"SELECT `users`.`id`,`users`.`role_id`,`users`.`first_name`,`users`.`last_name`,`users`.`password`
					FROM `users`
					WHERE `email`=?",
					$results['email']
				)){
					if(!password_verify($results['password'],$password['password'])){
						$app->log_message(2,'Failed Login','Incorrect password supplied for '.$password['first_name'].' '.$password['last_name'].'.');
						$app->set_message('error',"Email and password do not match");
						$this->redirect(false,$results);
					}else{
						$_SESSION['user_id']=$password['id'];
						$updates['last_login']=date('Y-m-d H:i:s');
						$user->update_user($updates,$password['id']);
						if($_GET['url']){
							header('Location: '.$_GET['url']);
						}elseif(!$_GET['url']){
							header('Location: users');
						}
						exit;
					}
				}else{
					$app->log_message(2,'Failed Login','Login attempt for unlisted user.');
					$app->set_message('error',"Username and password do not match");
					$this->redirect(false,$results);
				}
				$this->redirect();
			}
		}
	}
}