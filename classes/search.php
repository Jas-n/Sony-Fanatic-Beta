<?php class search extends form{
	public function __construct(){
		parent::__construct("name=search&class=form-inline");
		parent::add_field(array(
			'name'			=>'term',
			'placeholder'	=>'Search&hellip;',
			'type'			=>'search',
			'value'			=>$_GET['term']
		));
	}
	public function process(){
		global $app,$page;
		if($_POST['form_name']==$this->data['name']){
			$results=parent::process();
			if($results['status']!='error'){
				$results=$this->unname($results['data']);
				if($page->module){
					$tab='#tab='.$app->modules[$page->module]['slug'];
				}
				header('Location: /users/search?term='.urlencode($results['term']).$tab);
				exit;
			}
		}
	}
}