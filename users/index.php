<?php require('../init.php');
$html_help='<p>The Dashboard is the central hub for Glowt.</p>
<p>At the top you will see your notifications. To close notifications, click dismiss to the right of the notification. (please note: only applicable to those that are dismissable)</p>';
require('header.php');?>
<div class="page-header">
	<h1>Dashboard</h1>
	<ol class="breadcrumb">
		<li class="active">Dashboard</li>
	</ol>
</div>
<?php if($notifications_=$notifications->get('user')){
	foreach($notifications_ as $notification){?>
		<div class="alert alert-<?=$notification['type']?><?=$notification['dismissable']?' alert-dismissible fade in':''?> notification" data-id="<?=$notification['id']?>" role="alert">
			<?php if($notification['dismissable']){ ?>
				<button type="button" class="close" data-dismiss="alert" aria-label="Close">
					<span>Dismiss</span>
				</button>
			<?php } ?>
			<strong><?=$notification['title']?>:</strong> <?=$notification['message']?>
		</div>
	<?php }
} ?>
<div class="cols-md-6 cols-lg-4">
	<ol>
		<li>Finish menu tree</li>
		<li>Replace font awesome icons with icons from Metro Studio</li>
		<li>Tidy up un-needed files</li>
		<li>Hide siblings in menu (E.g. the plus under <strong>Products > Sorting</strong>)</li>
	</ol>
</div>
<?php require('footer.php');