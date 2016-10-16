<?php class user extends users{
	public $id;
	# Construct
	public function __construct($user_id=NULL,array $loaders=array()){
		global $app,$can_access,$db;
		if($user_id!=NULL){
			$this->id=$user_id;
		}elseif($_SESSION['user_id']){
			$this->id=$_SESSION['user_id'];
		}else{
			$this->id=0;
		}
		if($temp=$this->get_user($this->id,$loaders)){
			foreach($temp as $name=>$val){
				$this->{$name}=$val;
			}
			$this->full_name="{$temp['first_name']} {$temp['last_name']}";
			unset($temp);
		}
		if($can_access && !$this->is_role($can_access)){
			$app->log_message(2,'Unauthorised access',$this->full_name.' tried accessing '.get_dir().'/'.basename($_SERVER['PHP_SELF']).' without the required permissions.',$_GET);
			header('Location: ../');
			exit;
		}
	}
	# User Access
		# is_role - string or array of role names and/or ids
		public function is_role($roles){
			if(!is_array($roles)){
				$vars=func_get_args();
				if(sizeof($vars)>1){
					$roles=$vars;
				}else{
					$roles=array($roles);
				}
			}
			$roles[]=1;
			if(in_array($this->role_id,$roles)){
				return true;
			}
			return false;
		}
}