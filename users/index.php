<?php $permissions='*';
require('../init.php');
require('header.php');?>
<h1>Dashboard</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item active">Dashboard</li>
</ol>
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
	}?>
	<div class="cols-md-6 cols-lg-4">
		<h2 class="mb-0">Trello Updates</h2>
		<?php if($cards){ ?>
			<p class="mb-05"><strong>Updated</strong> <em><?=sql_datetime(date('Y-m-d H:i:s',strtotime(max(array_column($cards,'dateLastActivity')))))?></em></p>
		<?php }
		foreach($lists as $list){
			#												   On List/ Complete
			if(!$list['closed'] && !in_array($list['id'],array('585a3fac12bf55eb77b860cb'))){
				if($list['cards']){?>
					<div data-list="<?=$list['id']?>">
						<h3 class="mb-0"><?=$list['name']?></h3>
						<p><strong>Updated</strong> <em><?=sql_datetime(date('Y-m-d H:i:s',strtotime(max(array_column($list['cards'],'dateLastActivity')))))?></em></p>
						<ul class="list-group mb-1" style="font-size:14px">
							<?php foreach($list['cards'] as $card){?>
								<li class="list-group-item" style="padding:7px;break-inside:avoid-column">
									<span class="h6"><a href="<?=$card['shortUrl']?>" target="_blank"><?=htmlspecialchars($card['name'])?></a></span><br>
									<?=$card['desc']?nl2br($card['desc']).'<br>':''?>
									<?php if($card['badges']['attachments'] || $card['badges']['comments'] || $card['badges']['description']){
										if($card['badges']['attachments']){ ?>
											<i class="fa fa-paperclip"></i>
										<?php }
										if($card['badges']['comments']){ ?>
											<i class="fa fa-comments"></i>
										<?php }
										if($card['badges']['checkItems']){?>
											<progress class="progress progress-success mb-0" style="bottom:0;height:3px;left:0;position:absolute" max="<?=$card['badges']['checkItems']?>" value="<?=$card['badges']['checkItemsChecked']?>"></progress>
										<?php }
									} ?>
								</li>
							<?php }?>
						</ul>
					</div>
				<?php }
			}
		} ?>
		<h3 class="mb-0">Completed</h3>
		<?php $list=$lists['585a3fac12bf55eb77b860cb'];
		if($list['cards']){
			$list['cards']=array_slice($list['cards'],0,25);?>
			<p class="mb-05"><strong>Updated</strong> <em><?=sql_datetime(date('Y-m-d H:i:s',strtotime(max(array_column($list['cards'],'dateLastActivity')))))?></em></p>
			<ul class="mb-0">
				<?php foreach($list['cards'] as $card){ ?>
					<li style="break-inside:avoid-column"><a href="<?=$card['shortUrl']?>" target="_blank"><?=htmlspecialchars($card['name'])?></a></li>
				<?php } ?>
			</ul>
		<?php } ?>
	</div>
<?php }
require('footer.php');