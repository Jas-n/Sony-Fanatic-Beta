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
include('header.php');
$ps=$statistics->products();?>
<div class="cols-md-2">
	<div class="card">
		<div class="card-header">Products<a class="btn btn-primary btn-sm float-right" href="products" title="View Products">View</a></div>
		<table class="<?=$bootstrap->table->classes->table?> table-fixed">
			<thead>
				<tr>
					<th>Stat</th>
					<th class="text-right">Value</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th>Total Products</th>
					<td class="text-right"><?=number_format($ps['total'])?></td>
				</tr>
				<tr>
					<th>Enabled Products</th>
					<td class="text-right"><?=number_format($ps['enabled'])?> (<?=number_format($ps['enabled']/$ps['total']*100,2)?>%)</td>
				</tr>
				<tr>
					<th>Products per Day <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" title="Since <?=sql_date('2017-01-01')?>"></i></th>
					<td class="text-right"><?=number_format($ps['ppd'],2)?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<div class="card">
		<div class="card-header">Users<a class="btn btn-primary btn-sm float-right" href="./users" title="View Users">View</a></div>
		<table class="<?=$bootstrap->table->classes->table?> table-fixed">
			<thead>
				<tr>
					<th></th>
					<th class="text-right">Count</th>
					<th class="text-right">Can Access</th>
					<th class="text-right">Active <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" title="Logged in within the last <?=MONTH_LENGTH?> days"></i></th>
					<th class="text-right">Live <i class="fa fa-fw fa-info-circle" data-toggle="tooltip" title="Logged in within the last 24 hours"></i></th>
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
						<td class="text-right"><?=number_format($data['total'])?></td>
						<td class="text-right"><?=number_format($data['access'])?></td>
						<td class="text-right"><?=number_format($data['active'])?></td>
						<td class="text-right"><?=number_format($data['live'])?></td>
					</tr>
				<?php } ?>
			</tbody>
			<tfoot>
				<tr>
					<th>Total</th>
					<th class="text-right"><?=number_format($total)?></th>
					<th class="text-right"><?=number_format($access)?></th>
					<th class="text-right"><?=number_format($active)?></th>
					<th class="text-right"><?=number_format($live)?></th>
				</tr>
			</tfoot>
		</table>
	</div>
</div>
<?php include('footer.php');