<?php require('../init.php');
$h1='Logs';
$breadcrumb=array(
	'Management',
	'Logs'
);
$small=$db->result_count("FROM `logs`");
require('header.php');
$logs=$db->query(
	'SELECT
		`logs`.*,
		CONCAT(`users`.`first_name`," ",`users`.`last_name`) as `name`
	FROM `logs`
	LEFT JOIN `users`
	ON `logs`.`user_id`=`users`.`id`
	ORDER BY `date` DESC'.
	SQL_LIMIT
);?>
<div class="card card-body">
	<table class="<?=$bootstrap->table->classes->table?>">
		<thead>
			<tr class="table-inverse">
				<th class="mw-5">Date</th>
				<th class="mw-10">Title</th>
				<th>Message</th>
				<th class="mw-5">User</th>
				<th>Trace</th>
			</tr>
		</thead>
		<tbody>
			<?php if($logs){
				foreach($logs as $log){
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
	<?php pagination($count); ?>
</div>
<?php require('footer.php');