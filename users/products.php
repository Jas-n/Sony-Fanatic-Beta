<?php $app_require[]='php.products';
require('../init.php');
require('header.php');
$products=$products->get_products();?>
<a class="btn btn-success pull-right" href="add_product">Add</a>
<h1>Products</h1>
<ol class="breadcrumb">
	<li class="breadcrumb-item"><a href="../">Home</a></li>
	<li class="breadcrumb-item"><a href="./">Dashboard</a></li>
	<li class="breadcrumb-item active">Products</li>
</ol>
<table class="table table-sm table-hover table-stiped">
	<thead>
		<tr>
			<th>Brand</th>
			<th>Model</th>
			<th>Actions</th>
		</tr>
	</thead>
	<tbody>
		<?php if($products['count']){
			foreach($products['data'] as $product){?>
				<tr>
					<td><?=$product['brand']?></td>
					<td><?=$product['model']?></td>
					<td>
						<a class="btn btn-sm btn-primary" href="product/<?=$product['id']?>">Edit</a>
						<a class="btn btn-sm btn-info" href="../p/<?=$product['id']?>-<?=$product['slug']?>">View</a>
					</td>
				</tr>
			<?php }
		} ?>
	</tbody>
</table>
<?php pagination($products['count']);
require('footer.php');