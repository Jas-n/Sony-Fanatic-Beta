<?php require('../init.php');
require('header.php');
$logs=$db->get_logs();?>
<h1>Logs <small class="text-muted"><?=number_format($logs['count'])?></small></h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item">Management</li>
	<li class="breadcrumb-item active">Logs</li>
</ol>
<table class="table table-hover table-sm table-striped">
	<thead>
		<tr>
			<th class="mw-5">Date</th>
			<th class="mw-10">Title</th>
			<th>Message</th>
			<th class="mw-5">User</th>
			<th>Trace</th>
		</tr>
	</thead>
	<tbody>
		<?php if($logs['logs']){
			foreach($logs['logs'] as $log){
				if($log['user_id']!==-1){
					$name='<a href="user/'.$log['user_id'].'">'.$log['name'].'</a>';
				}?>
				<tr class="<?=($log['level']==2?'table-warning':($log['level']==1?'table-danger':'table-info'))?>"  data-id="<?=$log['id']?>">
					<td><?=sql_datetime($log['date'])?></td>
					<td><?=$log['title']?></td>
					<td><?=$log['message']?></td>
					<td><?=$name?></td>
					<td><?=$log['data']?></td>
				</tr>
			<?php }
		}else{?>
			<tr class="danger"><td colspan="7">No Logs found</td></tr>
		<?php }?>
	</tbody>
</table>
<?php pagination($logs['count']);
require('footer.php');