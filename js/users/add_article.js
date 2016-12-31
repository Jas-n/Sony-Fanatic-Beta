if($('#add_article_product')){
	$('#add_article_product').autocomplete({
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
			$('#add_article_product').val(ui.item.name);
			$('#add_article_product_id').val(ui.item.id);
			$('.content').removeClass('hidden');
			return false;
		},
		source:function(request,response){
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					term:request.term,
					action:'get_product'
				},
				success:function(data){
					response(data.data);
				}
			});
		}
	})
	.autocomplete( "instance" )._renderItem=function(ul,item){
		return $("<li>").append("<a>"+item.brand+' '+item.name+"</a>").appendTo(ul);
	};
}