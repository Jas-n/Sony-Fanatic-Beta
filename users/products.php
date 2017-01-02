<?php $app_require[]='php.products';
require('../init.php');
require('header.php');
$products=$products->get_products();?>
<h1>Products</h1>
<ol class="breadcrumb">
	<li class="pull-right">
		<a class="btn btn-secondary" data-toggle="tooltip" href="tags" title="Tags"><i class="fa fa-fw fa-tags"></i></a>
		<a class="btn btn-success" data-toggle="tooltip" href="add_product" title="Add Product"><i class="fa fa-fw fa-plus"></i></a>
	</li>
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item active">Products</li>
</ol>
<table class="table table-sm table-hover table-stiped">
	<thead>
		<tr>
			<th>Brand</th>
			<th>Name</th>
			<th>Updated</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if($products['count']){
			foreach($products['data'] as $product){?>
				<tr<?=!$product['status']?' class="table-danger"':''?>>
					<td><?=$product['brand']?></td>
					<td><?=$product['name']?></td>
					<td><?=sql_datetime($product['updated'])?></td>
					<td>
						<a class="btn btn-sm btn-primary" href="product/<?=$product['id']?>">Edit</a>
						<a class="btn btn-sm btn-secondary" href="../p/<?=$product['id']?>-<?=$product['slug']?>" target="_blank">View</a>
					</td>
				</tr>
			<?php }
		} ?>
	</tbody>
</table>
<?php pagination($products['count']);
require('footer.php');