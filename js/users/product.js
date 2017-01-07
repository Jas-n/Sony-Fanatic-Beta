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
			var feature_select='<option>Select…</option>';
			for(var i=0;i<features.length;i++){
				feature_select+='<option value="'+i+'">'+features[i].name+'</option>';
			}
			$('#edit_product_new_feature').html(feature_select);
		}
	},
	render_tag:function(tag){
		$('#product_tags').append('<span>'+tag.tag+' <a class="badge badge-danger delete delete_tag" data-id="'+tag.link_id+'"><i class="fa fa-fw fa-times"></i></a></span>');
	},
	render_values:function(values){
		if(values){
			var value_select='<option>Select…</option>';
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
					product.category=0;
					product.feature	=0;
					product.value	=0;
				}
				$('#edit_product_new_category').val('');
				$('#edit_product_new_feature').html('<option>Loading…</option>');
				$('#edit_product_new_value').html('<option>Loading…</option>');
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
$('#edit_product_tag').autocomplete({
	position:{
		my:"left bottom",
		at:"left top",
	},
	response:function(event,ui){
		$(event.target.parentNode).find('.fa-refresh').removeClass('fa-spin');
	},
	search:function(event,ui){
		$(event.target.parentNode).find('.fa-refresh').addClass('fa-spin');
	},
	select: function(event,ui){
		if(ui.item.id==-1){
			// Add new
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					product:_GET.id,
					tag:event.target.value,
					action:'add_tag'
				},
				success:function(data){
					$(event.target).val('');
					product.render_tag(data.tag);
				},
				error:function(data){
					console.log(data);
				}
			});
		}else{
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					product:_GET.id,
					tag:ui.item.id,
					action:'assign_tag'
				},
				success:function(data){
					$(event.target).val('');
					product.render_tag(data.tag);
				},
				error:function(data){
					console.log(data);
				}
			});
		}
		return false;
	},
	source:function(request,response){
		$.ajax({
			dataType:'json',
			type:"POST",
			url:'/ajax/product.php',
			data:{
				term:request.term,
				action:'get_tags'
			},
			success:function(data){
				console.log(data);
				if(data.status && !data.data.length){
					data.data.push({
						id:-1,
						tag:'Add "'+request.term+'"'
					});
				}
				response(data.data);
			},
			error:function(data){
				console.log(data);
			}
		});
	}
})
.autocomplete( "instance" )._renderItem=function(ul,item){
	return $("<li>").append("<a>"+item.tag+"</a>").appendTo(ul);
};
$(document).on('delete_confirm',function(e){
	if(e.status){
		if($(e.target).hasClass('delete_link')){
			$.ajax({
				context:e.target,
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					action:	'delete_link',
					link:	e.target.dataset.id
				},
				success:function(data){
					if(data){
						$(this.parentNode.parentNode).remove();
					}
				}
			});
		}
		else if($(e.target).hasClass('delete_tag')){
			$.ajax({
				context:e.target,
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					action:	'delete_tag',
					tag:	e.target.dataset.id
				},
				success:function(data){
					if(data){
						$(this.parentNode).remove();
					}
				}
			});
		}
		else if($(e.target).hasClass('delete_value')){
			$.ajax({
				context:e.target,
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					action:	'delete_value',
					value:	e.target.dataset.id
				},
				success:function(data){
					if(data){
						$(this.parentNode.parentNode).remove();
					}
				}
			});
		}
	}
});