<?php class articles{
	public function get_article($id){
		# Get articles
		global $db,$products,$user;
		if($article=$db->get_row(
			"SELECT *
			FROM `articles`
			WHERE `id`=?",
			$id
		)){
			$article['author']=$user->get_user($article['author']);
			$article['products']=$db->query(
				"SELECT `id`,`name`,`slug`
				FROM `products` WHERE `id` IN(
					SELECT `product_id`
					FROM `article_products`
					WHERE `article_id`=?
				)",
				$id
			);
			$article['status_id']=$article['status'];
			$article['status']=$this->statuses($article['status']);
			return $article;
		}
	}
	public function get_articles($status=2,$type=-1,$ids=NULL){
		# Get articles
		global $db;
		if($status!==-1){
			$where[]='`status`=?';
			$options[]=$status;
		}
		if($type!==-1){
			$where[]='`type`=?';
			$options[]=$type;
		}
		if($ids!==NULL){
			if(!is_array($ids)){
				$ids=(array) $ids;
			}
			$where[]="`id` IN(".implode(',',array_pad([],sizeof($ids),'?')).")";
			foreach($ids as $id){
				$options[]=$id;
			}
		}else{
			$limit=SQL_LIMIT;
		}
		if($where){
			$where=" WHERE (".implode(") AND (",$where).")";
		}
		if($datas=$db->query(
			"SELECT
				*,
				(SELECT COUNT(`id`) FROM `article_products` WHERE `article_id`=`articles`.`id`) as `products`
			FROM `articles`".
			$where.
			"ORDER BY
				`status` ASC,
				`published` DESC,
				`updated` DESC".
			$limit,
			$options
		)){
			foreach($datas as &$data){
				$data['author']=$db->get_row("SELECT `id`,`username` FROM `users` WHERE `id`=?",$data['author']);
				$data['slug']=$data['id'].'-'.$data['slug'];
				$data['status']=$this->statuses($data['status']);
			}
			return array(
				'count'	=>$db->result_count(
					"FROM `articles`".
					$where,
					$options
				),
				'data'	=>$datas
			);
		}
	}
	public function get_latest($status=2,$type=-1,$count=NULL){
		global $db;
		if($status!==-1){
			$where[]='`articles`.`status`=?';
			$options[]=$status;
		}
		if($type!==-1){
			$where[]='`articles`.`type`=?';
			$options[]=$type;
		}
		if(!is_numeric($count)){
			$count=ITEMS_PER_PAGE;
		}
		if($where){
			$where="WHERE (".implode(") AND (",$where).")";
		}
		if($articles=$db->query(
			"SELECT `articles`.`id`
			FROM `articles`
			".$where."
			ORDER BY `published` DESC
			LIMIT ".$count,
			$options
		)){
			return $this->get_articles($status,$type,array_column($articles,'id'));
		}
		return false;
	}
	public function statuses($id=NULL){
		$statuses=array(
			1=>'Draft',
			2=>'Published',
			3=>'Deleted'
		);
		if($id===NULL){
			return $statuses;
		}elseif($statuses[$id]){
			return $statuses[$id];
		}
		return false;
	}
	public function types($id=NULL){
		$statuses=array(
			1=>'News',
			2=>'Review'
		);
		if($id===NULL){
			return $statuses;
		}elseif($statuses[$id]){
			return $statuses[$id];
		}
		return false;
	}
}