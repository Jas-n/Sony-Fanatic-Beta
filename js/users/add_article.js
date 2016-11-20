var add_news={
	brand:0
};
$('#add_article_brand').change(function(e){
	'use strict';
	if(e.target.value){
		add_news.brand=e.target.value;
		$('.add_article_product_outer').removeClass('hidden');
	}else{
		$('.add_article_product_outer').addClass('hidden');
		add_news.brand=0;
	}
});
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
			$.event.trigger({
				client:ui.item,
				type:'client_selected'
			});
			$('.content').removeClass('hidden');
			return false;
		},
		source:function(request,response){
			$.ajax({
				dataType:'json',
				type:"POST",
				url:'/ajax/product.php',
				data:{
					brand:add_news.brand,
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
		return $("<li>").append("<a>"+item.name+"</a>").appendTo(ul);
	};
}