<?php class page{
	public $dir;
	public $file;
	public $path;
	public $slug;
	public function get_page($page_id,$content=0){
		global $db;
		if($page=$db->query("SELECT * FROM `pages` WHERE `id`=? OR `path`=?",array($page_id,$page_id))){
			$page=$page[0];
			if($contents=$db->query("SELECT * FROM `page_content` WHERE `page_id`=?",$page['id'])){
				$page['contents']=$contents;
			}
			return $page;
		}
		return false;
	}
	public function get_pages($limit=0,$ajax=true){
		global $db;
		if($limit==-1){
			$limit='';
		}elseif($limit==0){
			$limit="LIMIT ".($_GET['page']?(($_GET['page']-1)*ITEMS_PER_PAGE):0).','.ITEMS_PER_PAGE;
		}else{
			$limit="LIMIT ".($_GET['page']?(($_GET['page']-1)*$limit):0).','.$limit;
		}
		if($ajax==false){
			$where[]='`path` NOT LIKE ?';
			$where[]='`path` NOT LIKE ?';
			$options[]='%ajax%';
			$options[]='%api%';
		}
		if($where){
			$where='WHERE '.implode(' AND ',$where);
		}
		if($pages=$db->query(
			"SELECT *
			FROM `pages`
			".$where."
			ORDER BY
				`path` ASC,
				`theme_file` ASC,
				`title` ASC
			".$limit,
			$options
		)){
			foreach($pages as &$page){
				$page['content_count']=$db->result_count("FROM `page_content` WHERE `page_id`=?",$page['id']);
				$a_cols=$db->get_columns('page_access');
				unset(
					$a_cols['id'],
					$a_cols['page_id']
				);
				if($access=$db->query("SELECT `".implode('`,`',array_keys($a_cols))."` FROM `page_access` WHERE `page_id`=? LIMIT 1",$page['id'])){
					foreach($access[0] as $key=>$a){
						$page['permissions'][substr($key,strlen('role_'))]=json_decode($a,1);
					}
				}
			}
		}
		return array(
			'count'	=>$db->result_count('FROM `pages` '.$where,$options),
			'pages'	=>$pages
		);
	}
	public function get_permissions($pages){
		if(!$pages){
			return false;
		}
		global $db,$user;
		if(!is_array($pages)){
			$pages=array($pages);
		}
		foreach($pages as &$page){
			if(strpos($page,'.php')===false){
				$page.='.php';
			}
		}
		$a_cols=$db->get_columns('page_access');
		if($perms=$db->query(
			"SELECT
				`pages`.`path`,
				`page_access`.`role_".$user->role_id."`
			FROM `pages`
			INNER JOIN `page_access`
			ON `page_access`.`page_id`=`pages`.`id`
			WHERE
				`pages`.`id` IN('".implode("','",$pages)."') OR
				`pages`.`path` IN('".implode("','",$pages)."')
			ORDER BY `path` ASC"
		)){
			foreach($perms as $perm){
				$perm['path']=substr($perm['path'],0,strpos($perm['path'],'.php'));
				$this->permissions_list[$perm['path']]=json_decode($perm['role_'.$user->role_id],1);
			}
			return $this->permissions_list;
		}
		return false;
	}
	public function has_permission($page,$for='view'){
		return $this->permissions_list[$page][$for];
	}
	public function is_page($pages){
		if(!is_array($pages)){
			$pages=array($pages);
		}
		if(in_array($this->id,$pages) || in_array($this->file,$pages) || in_array($this->title,array_map('strtolower',$pages)) || in_array($this->dir.'.'.$this->file,$pages)){
			return true;
		}
		return false;
	}
	public function update($page_id,$array_values,$access=NULL){
		global $app,$db;
		unset($array_values['added']);
		if(!$array_values['fixed_access']){
			$array_values['fixed_access']=0;
		}
		$db->query(
			"UPDATE `pages`
			SET `".implode("`=?, `",array_keys($array_values))."`=?, `updated`=?
			WHERE `id`=?",
			array_merge(
				$array_values,
				array(
					DATE_TIME,
					$page_id
				)
			)
		);
		$roles=array_keys($access);
		foreach($roles as &$role){
			$role='`role_'.$role.'`=?';
		}
		foreach($access as &$a){
			$a=json_encode($a);
		}
		if($db->get_value("SELECT `id` FROM `page_access` WHERE `page_id`=?",$page_id)){
			$db->query(
				"UPDATE `page_access`
				SET ".implode(',',$roles)."
				WHERE `page_id`=?",
				array_merge(
					$access,
					array($page_id)
				)
			);
		}else{
			$db->query(
				"INSERT INTO `page_access` (
					`page_id`,	`role_1`,`role_2`,`role_3`,`role_4`,
					`role_5`,	`role_6`
				) VALUES (?,?,?,?,?,	?,?)",
				array_merge(
					array($page_id),
					$access
				)
			);
		}
	}
}