<?php $app_require=array(
	'php.users',
	'php.statistics'
);
require('../init.php');
$h1='Statistics';
$breadcrumb=array(
	'Management',
	'Statistics'
);
include('header.php');?>
<div class="row">
	<div class="col-md-6">
		<div class="card">
			<div class="card-header">Users<a class="btn btn-primary btn-sm float-right" href="./users" title="View Users">View</a></div>
			<table class="<?=$bootstrap->table->classes->table?> table-fixed">
				<thead>
					<tr>
						<th></th>
						<th>Count</th>
						<th>Can Access</th>
						<th>Active <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" title="Logged in within the last <?=MONTH_LENGTH?> days"></i></th>
						<th>Live <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" title="Logged in within the last 24 hours"></i></th>
					</tr>
				</thead>
				<tbody>
					<?php $users=$statistics->users();
					foreach($users as $role=>$data){
						$total	+=$data['total'];
						$access	+=$data['access'];
						$active	+=$data['active'];
						$live	+=$data['live']; ?>
						<tr>
							<th><?=$data['role']?></th>
							<td><?=number_format($data['total'])?></td>
							<td><?=number_format($data['access'])?></td>
							<td><?=number_format($data['active'])?></td>
							<td><?=number_format($data['live'])?></td>
						</tr>
					<?php } ?>
				</tbody>
				<tfoot>
					<tr>
						<th>Total</th>
						<th><?=number_format($total)?></th>
						<th><?=number_format($access)?></th>
						<th><?=number_format($active)?></th>
						<th><?=number_format($live)?></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php include('footer.php');