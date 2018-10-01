var $ = jQuery.noConflict();
$( function() {
	$( "#tabs" ).tabs();
} );

$(document).ready(function(){
	$('#saveApiUrl').click(function(){
		apiURL = $('#aSettingsURL').val();
		$.ajax({
            method:'POST',
            url:ajax_object.ajax_url,
            data:{
                'action' :'saveAPIURL',
                'apiURL' : apiURL,
            },
            success:function(data){
            	console.log(data);
            },
            error:function (errorThrown) {
                console.log(errorThrown)
            }
      });  
	});
});
