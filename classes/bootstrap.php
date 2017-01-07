<?php class bootstrap{
	public $colours;
	public $table;
	# 14/12/2016 10:04
	public function __construct(){
		$this->colours=(object) array(
			'colours'=>(object) array(
				'primary'	=>'#0c84b5',
				'success'	=>'#5cb85c',
				'info'		=>'#5bc0de',
				'warning'	=>'#f0ad4e',
				'danger'	=>'#d9534f',
				'inverse'	=>'#373a3c'
			),
			'greys'=>(object) array(
				'dark'		=>'#373a3c',
				'grey'		=>'#55595c',
				'light'		=>'#818a91',
				'lighter'	=>'#eceeef',
				'lightest'	=>'#f7f7f9'
			)
		);
		$this->table=(object) array(
			'classes'=>(object) array(
				'header'=>'bg-primary table-inverse',
				'table'	=>'table table-hover table-sm table-striped'
			)
		);
	}
	# 16/12/2016 14:43
	public static function btn($type='primary',$size='sm'){
		if(!in_array($type,array('danger','info','link','primary','secondary','success','warning'))){
			$type='primary';
		}
		if(!in_array($size,array('xs','sm','lg'))){
			unset($size);
		}
		return 'btn btn-'.$type.($size?' btn-'.$size:'');
	}
	# 14/12/2016 10:04
	public static function list_group(array $items,$args=NULL,$return=false){
		if($return){
			ob_start();
		}?>
		<div class="list-group<?=$args['wrapclass']?>"<?=$args['id']?' id="'.$args['id'].'"':''?>>
			<?php foreach($items as $item){ ?>
				<div class="list-group-item<?=(is_array($item)?' '.$item['class']:'').($args['itemclass']?' '.$args['itemclass']:'')?>">
					<?php if(is_array($item)){
						echo $item['content'];
					}else{
						echo $item;
					} ?>
				</div>
			<?php } ?>
		</div>
		<?php if($return){
			return ob_get_clean();
		}
	}
}