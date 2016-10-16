$('.notification .close').click(function(e){
	e.stopPropagation();
	$.ajax({
		context	:this,
		data	:{
			id:this.parentNode.dataset.id,
			user_id:user_id,
			what:'notification_user'
		},
		dataType:'json',
		method	:'POST',
		url		:"../ajax/delete.php",
		success	:function(data){
			if(data.status){
				$(this.parentNode).alert('close');
			}else{
				console.log(data);
			}
		},
		error	:function(data){
			console.log(data);
		}
	});
});