<?php
	// Get Settings
	$settings = get_option('vippy_settings');
	
	if ($settings) :
	    //Your Vippy API key provided to you by Vippy, this can also be found in your account settings when you log in to Vippy, keep this information safe!
	    define("VPY_API_KEY", $settings['vippy_api_key']);
	
	    //Your Vippy API secret key provided to you by Vippy, this can also be found in your account settings when you log in to Vippy, keep this information safe!
	    define("VPY_API_SECRET_KEY", $settings['vippy_secret_key']);//
	
	    //The host/url which the API sends requests to, there is no need to change this value
	    define("VPY_HOST", "http://rest.vippy.co/");
	
	    //Which format you would like your responses in
	    define("VPY_FORMAT", "json"); //or json, serialize. Other formats in beta, feel free to try, no guarantee that it works.
	    
	    //Other formats:
	    /*
			'xml' : 'application/xml',
			'rawxml' : 'application/xml',
			'json' : 'application/json',
			'jsonp' : 'application/javascript',
			'serialize' : 'application/vnd.php.serialized',
			'php' : 'text/plain',
			'html' : 'text/html',
			'csv' : 'application/csv'    
	    */
	
	    //API version, there is no need to change this value
	    define("VPY_VERSION", "1.1");		
	endif;


?>