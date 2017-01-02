<?php class statistics{
	public function users(){
		global $db,$user;
		$active	=date('Y-m-d H:i:s',strtotime('-'.MONTH_LENGTH.' days'));
		$live	=date('Y-m-d H:i:s',strtotime('-1 day'));
		$roles	=$user->get_roles();
		foreach($roles['roles'] as $role){
			$slug=slug($role['role']);
			$rls[$slug]=array(
				'role'	=>$role['role'],
				'access'=>$db->result_count("FROM `users` WHERE `role_id`=? AND `can_access`=1",$role['id']),
				'live'	=>$db->result_count("FROM `users` WHERE `role_id`=? AND `last_login`>?",array($role['id'],$live)),
				'active'=>$db->result_count("FROM `users` WHERE `role_id`=? AND `last_login`>?",array($role['id'],$active)),
				'total'	=>$db->result_count("FROM `users` WHERE `role_id`=?",$role['id'])
			);
		}
		return $rls;
	}
}