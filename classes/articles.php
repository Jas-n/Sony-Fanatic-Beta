<?php class articles{
	public function get_article($id){
		# Get articles
		global $db,$products,$user;
		if($article=$db->get_row(
			"SELECT
				`articles`.*,
				`products`.`name` as `product`
			FROM `articles`
			LEFT JOIN `products`
			ON `articles`.`product_id`=`products`.`id`
			WHERE `articles`.`id`=?",
			$id
		)){
			$article['author']=$user->get_user($article['author']);
			$article['product']=$products->get_product($article['product_id']);
			$article['status_id']=$article['status'];
			$article['status']=$this->statuses($article['status']);
			return $article;
		}
	}
	public function get_articles($status=2){
		# Get articles
		global $db;
		if($datas=$db->query(
			"SELECT
				`articles`.*,
				`products`.`name` as `product`
			FROM `articles`
			LEFT JOIN `products`
			ON `articles`.`product_id`=`products`.`id`
			ORDER BY
				`status` ASC,
				`published` DESC,
				`updated` DESC
			WHERE `status`=?".
			SQL_LIMIT,
			$status
		)){
			foreach($datas as &$data){
				$data['status']=$this->statuses($data['status']);
			}
			return array(
				'count'	=>$db->result_count("FROM `articles`"),
				'data'	=>$datas
			);
		}
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