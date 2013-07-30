jQuery(document).ready(function() {

	jQuery('.savepdf').click(function() {

		var html = "<html>" + jQuery('html').html() + "</html>";

		// var html = "<html>" + jQuery("html").html() + "</html>";

		// var html = jQuery("#wrapper").html();
		var stylesheet = pdfstyle;

		jQuery.post(
    
		    CaAjax.ajaxurl,
		    { 
		        action : 'client_access_ajax_make_pdf',
		        source: html,
		        stylesheet: stylesheet
		    },
		    function( response ) {
		    	
		    	window.open(response[0]);
		       // window.location = response[0];

		       setTimeout(function() {
		       		// deleteFile(response[1]);
		       }, 10000);
		       // alert(response);
		    }
	    );

	});


	function deleteFile(file) {
		jQuery.post(
    
		    CaAjax.ajaxurl,
		    { 
		        action : 'client_access_ajax_delete_pdf',
		        target: file
		    },
		    function( response ) {
		    	alert(response);
		    }
	    );
	}


});