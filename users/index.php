<?php $permissions='*';
require('../init.php');
$h1='Dashboard';
require('header.php');?>
<div class="cols-md-3">
	<?php if($user->role_id==1){
		$board=curl('https://api.trello.com/1/boards/585a3f0e6c3d230192cd9048?key=b3c2759fa13a510063e97bafcb8a1c42');
		$board=json_decode($board,1);
		$lists=curl('https://api.trello.com/1/boards/585a3f0e6c3d230192cd9048/lists?key=b3c2759fa13a510063e97bafcb8a1c42');
		$lists=json_decode($lists,1);
		$lists=array_combine(array_column($lists,'id'),$lists);
		$cards=curl('https://api.trello.com/1/boards/585a3f0e6c3d230192cd9048/cards?key=b3c2759fa13a510063e97bafcb8a1c42');
		$cards=json_decode($cards,1);
		if($cards){
			foreach($cards as $card){
				# If not closed
				if(!$card['closed']){
					$lists[$card['idList']]['cards'][]=$card;
				}
			}
		}
		foreach($lists as $list){
			#												   Complete
			if(!$list['closed'] && !in_array($list['id'],array('585a3fac12bf55eb77b860cb'))){
				if($list['cards']){?>
					<div class="card card-body" data-list="<?=$list['id']?>" style="font-size:14px">
						<h3 class="mb-0"><?=$list['name']?></h3>
						<p><strong>Updated</strong> <em><?=sql_datetime(date('Y-m-d H:i:s',strtotime(max(array_column($list['cards'],'dateLastActivity')))))?></em></p>
						<?php foreach($list['cards'] as $card){?>
							<hr class="my-2">
							<h6 class="mb-0"><a href="<?=$card['shortUrl']?>" target="_blank"><?=htmlspecialchars($card['name'])?></a></h6>
							<?=$card['desc']?nl2br($card['desc']).'<br>':''?>
							<?php if($card['badges']['attachments'] || $card['badges']['comments'] || $card['badges']['description']){
								if($card['badges']['attachments']){ ?>
									<i class="fa fa-paperclip"></i>
								<?php }
								if($card['badges']['comments']){ ?>
									<i class="fa fa-comments"></i>
								<?php }
							}
							if($card['badges']['checkItems']){
								$bootstrap->progress($card['badges']['checkItemsChecked'],$card['badges']['checkItems'],$card['badges']['checkItemsChecked'].'/'.$card['badges']['checkItems']);
							}
						}?>
					</div>
				<?php }
			}
		}
		$list=$lists['585a3fac12bf55eb77b860cb'];
		if($list['cards']){
			$list['cards']=array_slice($list['cards'],0,20);?>
			<div class="card card-body" data-list="<?=$list['id']?>" style="font-size:14px">
				<h3 class="mb-0"><?=$list['name']?></h3>
				<p><strong>Updated</strong> <em><?=sql_datetime(date('Y-m-d H:i:s',strtotime(max(array_column($list['cards'],'dateLastActivity')))))?></em></p>
				<hr class="my-2">
				<ol reversed>
					<?php foreach($list['cards'] as $card){?>
						<li><a href="<?=$card['shortUrl']?>" target="_blank"><?=htmlspecialchars($card['name'])?></a></li>
					<?php } ?>
				</ol>
			</div>
		<?php }
	} ?>
</div>
<?php require('footer.php');