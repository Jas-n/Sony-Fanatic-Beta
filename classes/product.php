<?php class product extends products{
	public function __construct($id){
		if($product=$this->get_product($id)){
			foreach($product as $key=>$value){
				$this->$key=$value;
			}
			$this->get_images();
			if($this->images && !$this->banner){
				$this->banner=$this->images['full'][0];
			}
			if($GLOBALS['articles']){
				$this->articles=$this->get_articles();
			}
		}
		return $this;
	}
	private function get_images(){
		if($fulls=glob(ROOT.'uploads/products/'.$this->brand_slug.'/'.$_GET['id'].'/*_full.png')){
			foreach($fulls as $full){
				$this->images['full'][]=str_replace(ROOT,'/',$full);
			}
		}
		if($mediums=glob(ROOT.'uploads/products/'.$this->brand_slug.'/'.$_GET['id'].'/*_medium.png')){
			foreach($mediums as $medium){
				$this->images['medium'][]=str_replace(ROOT,'/',$medium);
			}
		}
		if($thumbs=glob(ROOT.'uploads/products/'.$this->brand_slug.'/'.$_GET['id'].'/*_thumb.png')){
			foreach($thumbs as $thumb){
				$this->images['thumbnail'][]=str_replace(ROOT,'/',$thumb);
			}
		}
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