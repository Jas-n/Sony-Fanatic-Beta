<?php class articles{
	public function get_article($id){
		# Get articles
		global $db;
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
			$article['status']=$this->statuses($article['status']);
			return $article;
		}
	}
	public function get_articles(){
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
				`updated` DESC".
			SQL_LIMIT
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
	public function get_product_articles($product,$limit=NULL){
		# Return number and results
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