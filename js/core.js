var core={
	init:function(){
		this.responsive_tables();
	},
	responsive_tables:function(){
		$('table').each(function(index,element){
			var table = $(this);
			if(!table.parent().hasClass('table-responsive')){
				table.wrap('<div class="table-responsive"></div>');
			}
		});
	}
};
core.init();
// Give a warning for all delete items
$(document).on('click','[class*="delete"]',function(e){
	var delete_message;
	if(this.dataset.delete_prompt){
		delete_message=this.dataset.delete_prompt;
	}else{
		delete_message='Are you sure you want to delete the selected item(s)?';
	}
	if(window.confirm(delete_message)!==false){
		$.event.trigger({
			target:this,
			type:'delete_confirm'
		});
		return true;
	}
	e.stopPropagation();
	return false;
});
// Remove item from array
Array.prototype.remove = function(from, to) {
  var rest = this.slice((to || from) + 1 || this.length);
  this.length = from < 0 ? this.length + from : from;
  return this.push.apply(this, rest);
};
// Split string into lengths
function str_split(string,size) {
    return [].concat.apply([],
        string.split('').map(function(x,i){ return i%size ? [] : string.slice(i,i+size) }, string)
    )
}