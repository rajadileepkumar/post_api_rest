var $ = jQuery.noConflict();
$( function() {
	$( "#tabs" ).tabs();
});

$(document).ready(function(){
	$('#apiSettings').submit(function(e){
		e.preventDefault();
		apiURL = $('#aSettingsURL').val();
		if(apiURL === "" || apiURL == null){
		 $('.e-message').addClass('notice notice-error is-dismissible');
		 $('.e-message > p').html("All mandotroy fields required");
		}else{
			$.ajax({
		        method:'POST',
		        url:ajax_object.ajax_url,
	            data:{
	                'action' :'saveAPIURL',
	                'apiURL' : apiURL,
	            },
	            success:function(data){
	            	$('.e-message').addClass('notice notice-success is-dismissible');
		 			$('.e-message > p').html(data);
	            },
	            error:function (errorThrown) {
	                console.log(errorThrown)
	            }
		    });	
		}
	});
});
