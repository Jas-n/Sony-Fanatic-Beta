<?php class product extends products{
	public function __construct($id){
		if($product=$this->get_product($id)){
			foreach($product as $key=>$value){
				$this->$key=$value;
			}
			if($this->images && !$this->banner){
				$this->banner=$this->image['full'];
			}
			return $this;
		}
		return false;
	}
}