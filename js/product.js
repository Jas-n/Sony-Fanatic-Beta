var product={
	update_catalog:function(status){
		$.ajax({
			dataType:'json',
			type:"POST",
			url:'/ajax/product.php',
			data:{
				action:	'update_catalogue',
				product:_GET.id,
				status:	status,
				user:	user_id
			},
			success:function(data){
				if(data===true){
					$('.catalogue_had,.catalogue_got,.catalogue_want').removeClass('true');
					if(status==-1){
						$('.catalogue_had').addClass('true');
					}else if(status==1){
						$('.catalogue_want').addClass('true');
					}else{
						$('.catalogue_got').addClass('true');
					}
				}
			}
		});
	}
};
if(window.location.hash){
	if($(window.location.hash)){
		var target=window.location.hash.split('#')[1];
		$('main section').addClass('hidden');
		$('main section#'+target).removeClass('hidden');
		$('.cd-secondary-nav a').removeClass('active');
		$('[href="#'+target+'"]').addClass('active');
	}
}
$('.cd-secondary-nav').click(function(e){
	'use strict';
	var target=e.target.href.split('#')[1];
	$('main section').addClass('hidden');
	$('main section#'+target).removeClass('hidden');
	$('.cd-secondary-nav a').removeClass('active');
	$(e.target).addClass('active');
});
$('.social> *').click(function(e){
	console.log(e);
});
$('.catalogue_had').click(function(){
	product.update_catalog(-1);
});
$('.catalogue_got').click(function(){
	product.update_catalog(0);
});
$('.catalogue_want').click(function(){
	product.update_catalog(1);
});