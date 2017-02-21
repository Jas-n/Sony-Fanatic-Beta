<?php # 1.6.1 - For Boootstrap 4a6
class bootstrap{
	public $colours;
	public $table;
	# 14/12/2016 10:04
	public function __construct(){
		$this->colours=(object) array(
			'colours'=>(object) array(
				'primary'	=>'#0275d8',
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
				'header'=>'thead-default',
				'table'	=>'table table-hover table-striped'
			)
		);
	}
	# 24/01/2016 16:23
	public function alert($content,$args=[],$return=false){
		if(!$args['type'] || !in_array($args['type'],array('danger','info','link','primary','secondary','success','warning'))){
			$args['type']='primary';
		}
		if($return){
			ob_start();
		}
		if($args['data']){
			ksort($args['data']);
			foreach($args['data'] as $k=>$v){
				$data[]='data-'.$k.'="'.$v.'"';
			}
			$args['data']=' '.implode(' ',$data);
		}?>
		<div class="alert alert-<?=$args['type']?> <?=($args['class']?' '.$args['class']:'').($args['dismissible']?' alert-dismissible fade show':'')?>"<?=$args['data']?$args['data']:''?> role="alert">
			<?php if($args['dismissible']){ ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span>Dismiss</span>
				</button>
			<?php }
			echo $content?>
		</div>
		<?php if($return){
			return ob_get_clean();
		}
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
	# 18/01/2017 12:29
	public static function modal($id,$title,$content,$return=false,$save_text='Save',$close_text='Close',$size='md'){
		if($return){
			ob_start();
		} ?>
		<div class="modal fade" id="<?=$id?>" tabindex="-1" role="dialog" aria-labelledby="<?=$id?>_label" aria-hidden="true">
			<div class="modal-dialog<?=$size && in_array($size,array('sm','lg'))?' modal-'.$size:''?>" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title" id="<?=$id?>_label"><?=$title?></h4>
						<button type="button" class="close modal_close" data-dismiss="modal" aria-label="<?=$close_text?>">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<?=$content?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary modal_close" data-dismiss="modal"><?=$close_text?></button>
						<button type="button" class="btn btn-sm btn-primary modal_save"><?=$save_text?></button>
					</div>
				</div>
			</div>
		</div>
		<?php if($return){
			return ob_get_clean();
		}
	}
	# 18/01/2017 11:35
	public function progress($value=0,$max=100,$label='',$style='default',$return=false){
		if($return){
			ob_start();
		}?>
		<div class="progress">
			<div aria-valuenow="<?=$value?>" aria-valuemin="0" aria-valuemax="<?=$max?>" class="progress-bar<?=$style!='default'?' bg-'.$style:''?>" role="progressbar" style="width:<?=number_format($value/$max*100,2)?>%;"><?=$label?></div>
		</div>
		<?php if($return){
			return ob_get_clean();
		}
	}
}