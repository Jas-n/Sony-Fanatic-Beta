var form_validation_error_id;
$(document).ready(function(e){
	var isChromium = window.chrome,
    vendorName = window.navigator.vendor;
	if(isChromium === null || isChromium === undefined || vendorName !== "Google Inc.") {
		$("[type='date']").datepicker({format:'yyyy-mm-dd'});
	}
	$('form').each(function(i1,e1){
		var form_name=$(e1).find('.form_name').val();
		$(e1).submit(function(e){
			if(validate_form(e1)){
				$('#loading').removeClass('hidden');
			}else{
				console.log("Form is not valid so wasn't submitted.");
				e.stopPropagation();
				return false;
			}
		});
		var company=$(e1).find('.get_company')[0];
		if(company){
			$(company).autocomplete({
				appendTo:company.id,
				minLength:3,
				source:"../ajax/companies.php?file="+enc_filename,
				response:function(event,ui){
					$(event.target.parentNode).find('.fa-refresh').removeClass('fa-spin');
				},
				search:function(event,ui){
					$(event.target.parentNode).find('.fa-refresh').addClass('fa-spin');
				},
				select: function( event, ui ) {
					$('#'+form_name+'_company').val(ui.item.name);
					$('#'+form_name+'_company_id').val(ui.item.id);
					return false;
				}
			})
			.data("ui-autocomplete")._renderItem=function(ul,item){
				return $("<li>").append("<a>"+item.name+"</a>").appendTo(ul);
			};
		}
		// Check all
		$('.check_all').change(function(e2){
			var to_check;
			// Check all x
			if(e2.target.id.indexOf('check_all_')!=-1){
				to_check=e2.target.id.substr(e2.target.id.indexOf('check_all_')+10);
			}
			// Check all
			else{
				to_check='check';
			}
			$('.'+to_check).each(function(i4,e4){
				e4.checked=e2.target.checked;
			});
		});
	});
	
});
// Validates the form and restyles based on Bootstrap 4
// 2016-06-10 16:08:00
function validate_form(form){
	$('#'+form_validation_error_id).remove();
	form_validation_error_id='m'+Math.floor((Math.random()*9999)+1);
	var requireds=$(form).find('input[required],select[required],textarea[required]');
	var errors=[];
	for(var i=0;i<requireds.length;i++){
		if(!requireds[i].value){
			$(requireds[i].parentNode.parentNode).removeClass('has-warning').addClass('has-danger');
			requireds[i].className+=' form-control-danger';
			var label=$('label[for="'+requireds[i].id+'"]')[0].innerHTML.replace('*','');
			if(!label){
				label=requireds[i].placeholder;
			}
			errors[errors.length]='<strong>'+label+'</strong> is required but was not submitted.';
		}else{
			$(requireds[i].parentNode.parentNode).removeClass('has-warning has-danger').addClass('has-success');
		}
	}
	if(errors.length){
		$(form).prepend(error(errors.join('<br>'),form_validation_error_id));
		
		var error_loc = $('#'+form_validation_error_id).offset().top;
		$('html,body').scrollTop(error_loc);
		
		return false;
	}else{
		return true;
	}
}