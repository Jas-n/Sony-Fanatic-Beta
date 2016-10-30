<?php class product extends products{
	public function __construct($id){
		if($product=$this->get_product($id)){
			foreach($product as $key=>$value){
				$this->$key=$value;
			}
			$this->get_images();
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
}