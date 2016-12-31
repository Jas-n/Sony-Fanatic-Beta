<?php class product extends products{
	public function __construct($id){
		if($product=$this->get_product($id)){
			foreach($product as $key=>$value){
				$this->$key=$value;
			}
			if($this->images && !$this->banner){
				$this->banner=$this->images['full'][0];
			}
			if($GLOBALS['articles']){
				$this->articles=$this->get_articles();
			}
		}
		return $this;
	}
	private function get_articles(){
		global $db;
		if($articles=$db->query(
			"SELECT *
			FROM `articles`
			WHERE
				`product_id`=? AND
				`status`=?".
			SQL_LIMIT,
			array(
				$this->id,
				2
			)
		)){
			$authors=$db->query(
				"SELECT `id`,`username`
				FROM `users`
				WHERE `id` IN(".implode(',',array_column($articles,'author')).")"
			);
			$authors=array_combine(array_column($authors,'id'),array_column($authors,'username'));
			foreach($articles as &$article){
				$article['author_username']=$authors[$article['author']];
			}
		}
		return array(
			'count'	=>$db->result_count('FROM `articles` WHERE `product_id`=?',$this->id),
			'data'	=>$articles
		);
	}
}