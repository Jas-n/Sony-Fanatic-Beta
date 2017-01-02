<?php class page{
	public $slug;
	private $all_permissions=array(
		# Page				Roles
		'users/'					=>'*', # Dashboard
		'users/add_article'			=>[1,2,3],
		'users/add_product'			=>[1,2,3],
		'users/article'				=>[1,2,3],
		'users/articles'			=>[1,2,3],
		'users/brands'				=>[1,2],
		'users/feature_categories'	=>[1,2],
		'users/feature_options'		=>[1,2],
		'users/feature_values'		=>[1,2],
		'users/logs'				=>[1,2],
		'users/product'				=>[1,2,3],
		'users/products'			=>[1,2,3],
		'users/profile'				=>'*',
		'users/settings'			=>1,
		'users/statistics'			=>[1,2],
		'users/tags'				=>[1,2],
		'users/test'				=>1,
		'users/user'				=>[1,2],
		'users/users'				=>[1,2],
	);
	public $permissions;
	public function __construct(){
		global $app,$user;
		$slug=explode('?',$_SERVER['REQUEST_URI'])[0];
		$slug=substr($slug,1);
		$this->slug=$slug;
		# Access to all
		$dir=get_dir();
		if($dir && !in_array($dir,array('ajax','api','CRONS'))){
			if($this->all_permissions[$slug]){
				$this->permissions=$this->all_permissions[$slug];
				if(((is_array($this->permissions) && !in_array($user->role_id,$this->permissions)) || (!is_array($this->permissions) && $this->permissions!='*' && $this->permissions!=$user->role_id))){
					header('Location: ./');
					exit;
				}
			}else{
				$app->log_message(2,'Page Permissions','No page permissions have been set for <strong>'.$slug.'</strong>');
				header('Location: ./');
				exit;
			}
		}
	}
}