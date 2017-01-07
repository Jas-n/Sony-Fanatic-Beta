<?php class forgot extends form{
	public function __construct($data=NULL){
		parent::__construct('name=forgot_password');
		parent::add_field(array(
			'label'			=>'Registered Email',
			'name'			=>'email',
			'placeholder'	=>'Email Address',
			'required'		=>1,
			'type'			=>'email',
			'value'			=>$data['email']
		));
		parent::add_html('<p class="text-center">');
			parent::add_button(array(
				'class'	=>'btn-primary',
				'name'	=>'login',
				'type'	=>'submit',
				'value'	=>'Reset'
			));
			parent::add_html('<a class="btn btn-sm btn-secondary" href="/login">Login</a>
		</p>');
	}
	public function process(){
		global $app,$db,$users;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			$results=$this->unname($results['data']);
			if($usr=$db->get_row(
				"SELECT `id`,`first_name`,`last_name`,`email`
				FROM `users`
				WHERE `email`=? AND `id` <> ? AND `can_access`=1",
				array(
					$results['email'],
					0
				)
			)){
				$users->reset_password($usr['id']);
				$app->log_message(3,'Forgot Password','Forgot Password requested and sent by \''.$usr['first_name'].' '.$usr['last_name'].'\'');
				header('Location: login?reset=1');
				exit;
			}else{
				$app->log_message(2,'Forgot Password','Password request for unlisted user.');
				$app->set_message('error',"There are no users matching your details.");
				$this->redirect(false,$results);
			}
		}
	}
}