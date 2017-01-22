if($('#item_article_product')){
	$('#item_article_product').autocomplete({
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
			// Ajax save then render
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					term:request.term,
					action:'save_article_product'
				},
				success:function(data){
					$(event.target).val('');
				}
			});
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