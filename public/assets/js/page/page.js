(function($){
	
	$.fn.buttonLoader = function(state){
		return this.each(function(){
			var loadingText = $(this).attr('data-loading-text') ? $(this).attr('data-loading-text') : "<i class='fas fa-circle-notch fa-spin'></i> loading...";
				if(state == "loading") {
					$(this).data("original-text",$(this).html());
					$(this).html(loadingText).attr('disabled','disabled');
				}
				if(state == "reset"){
					$(this).html($(this).data('original-text')).removeAttr('disabled');
				}
				
		});
	}
}(jQuery));


$(document).ajaxStart(function(){

	$(".loader").LoadingOverlay("show");
	});
	$(document).ajaxStop(function(){
	$(".loader").LoadingOverlay("hide");
	});
	$( document ).ajaxError(function( event, request, settings ) {
	switch(request.status) {
		case 401: 
			swal({
				title: "Warning",
				text : request.responseJSON.message,
				icon : 'warning',
				dangerMode: true
			});
			break;
	}
});


$(document).on('click','button',function(){
	$('[data-toggle="tooltip"]').tooltip('hide');
})
$(document).on('click','a',function(){
	$('[data-toggle="tooltip"]').tooltip('hide');
})

