var product={
	categories:	[],
	category:	0,
	features:	[],
	feature:	0,
	values:		[],
	value:		0,
	// Functions
	get_features:function(category){
		product.category=category;
		if(product.features[category]){
			product.render_features(product.features[category]);
		}else{
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					action:'get_features',
					category:category
				},
				success:function(data){
					product.features[category]=data;
					product.render_features(data);
				}
			});
		}
	},
	get_values:function(feature){
		product.feature=feature;
		if(product.values[feature]){
			product.render_values(product.values[feature]);
		}else{
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					action:'get_values',
					feature:product.features[product.category][product.feature].id
				},
				success:function(data){
					var features=[];
					if(data.values){
						for(var id in data.values){
							features.push({
								id:id,
								name:data.values[id].value
							});
							product.values[feature]=features;
						}
						product.render_values(product.values[feature]);
					}
				}
			});
		}
	},
	render_features:function(features){
		if(features){
			var feature_select='<option>Select&hellip;</option>';
			for(var i=0;i<features.length;i++){
				feature_select+='<option value="'+i+'">'+features[i].name+'</option>';
			}
			$('#edit_product_new_feature').html(feature_select);
		}
	},
	render_values:function(values){
		if(values){
			var value_select='<option>Select&hellip;</option>';
			for(var i=0;i<values.length;i++){
				value_select+='<option value="'+i+'">'+values[i].name+'</option>';
			}
			$('#edit_product_new_value').html(value_select);
		}
	},
	save_feature:function(value){
		product.value=value;
		$.ajax({
			dataType:'json',
			type:"POST",
			url:'/ajax/product.php',
			data:{
				action:	'save_value',
				product:_GET.id,
				value:	product.values[product.feature][product.value].id
			},
			success:function(data){
				if(data){
					$('.new_row').before('<tr>'+
						'<td>'+product.categories[product.category]+'</td>'+
						'<td>'+product.features[product.category][product.feature].name+'</td>'+
						'<td>'+product.values[product.feature][product.value].name+'</td>'+
						'<td><a class="btn btn-sm btn-danger delete delete_value" data-id="'+data+'"><i class="fa fa-times"></i></a></td>'+
					'</tr>');
				}
				$('#edit_product_new_category').val('');
				$('#edit_product_new_feature').html('<option>Loading&hellip;</option>');
				$('#edit_product_new_value').html('<option>Loading&hellip;</option>');
			}
		});
	}
};
$('#edit_product_new_category *').each(function(i,e){
	if(e.value){
		product.categories[e.value]=e.text;
	}
});
$('#edit_product_new_category').change(function(e){
	if(e.target.value){
		product.get_features(e.target.value);
	}
});
$('#edit_product_new_feature').change(function(e){
	if(e.target.value){
		product.get_values(e.target.value);
	}
});
$('#edit_product_new_value').change(function(e){
	if(e.target.value){
		product.save_feature(e.target.value);
	}
});
$('.delete_value').on('click',function(){
	$.ajax({
		context:this,
		dataType:'json',
		type:"POST",
		url:'/ajax/product.php',
		data:{
			action:	'delete_value',
			value:	this.dataset.id
		},
		success:function(data){
			if(data){
				$(this.parentNode.parentNode).remove();
			}
		}
	});
});