<?php
    /**
    * Core dependencies, make sure you go through the config.inc.php file and set it up correctly
    **/
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.inc.php')){
		include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.inc.php';
	}
	
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mimetypes.class.php')){
		include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mimetypes.class.php';
	}
	
	if (file_exists(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'core.class.php')){
		include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'core.class.php';
	}			
	
    class Vippy extends Vippy_core{  
    
		public function __construct()
		{
			parent::__construct();
		}    
    
        /**
         * Upload a video file
         * 
         * Function to upload a new video, can take up 
         * to 7 parameters, which of 3 are mandatory. 
         * 
         * The different parameters are:
         *  
         * video: mandatory, url to the video you want to upload.
         * title: mandatory, the title of your video
         * description: optional, description of your video
         * tags: optional, tags for your video, commaseparated
         * quality: the quality you want your video to be encoded to, 
         *          can be either higher, normal or lower
         * notify: url to script which we will notify when the encoding process is done, optional
         * uploadedby: name of the person who uploaded the video, optional
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * $opt = array(
         *      'video'        => 'fourseasons.mp4',
         *      'title'        => 'Four seasons',
         *      'description'  => 'Just another videofile', 
         *      'tags'         => 'summer, winter, autumn, spring', 
         *      'quality'      => 'normal', 
         *      'uploadedby'   => 'yourname', 
         *      'notify'       => 'http://yourdomain.com/notify.php'
         * );
         * 
         * $request = $vippy -> upload_video($opt);
         * 
         * Returns an array with either errormessage, or successmessages
         * 
        */
        public function upload_video($opt=Array()){           
        	$method = 'PUT';
        	$action = 'video';        	
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;            
        }				
		
        /**
         * List all your video files
         * 
         * Function to get all your videos, takes multiple parameters 
         * 
         * The different parameters are:
         *  
         * statistics: optional, set this to 1 if you want some 
         *             simple video statistics included with the response
         * orderby: can be 1 or 2. 1 = order your videos by file name, 2 = order your video files by upload date
         * sortby: can be 1 or 2. 1 = sorting by A-Z, 2 = Z-A
         * search: can be one or more words that will be used to search trough file names
         * tags: an array with tagId's. See get_archive_tags() for more info.
         * complete: 1 or 0. tell the API to only include videos that are complete or not. defaults to 0(not complete)
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * $opt = array(
         *      'statistics' => 1
         * );
         * 
         * $request = $vippy -> get_videos($opt);
         * 
         * Returns an array with all your videos and different 
         * useful values for each video inlcuding the videoId's'
         * 
        */
        public function get_videos($opt=Array())
        {
        	$method = 'GET';
        	$action = 'videos';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);//, $opt
            
            $data = $this->parse_callback($data);
            return $data;
        }

        /**
         * Get all your video players
         * 
         * Function to get all your videoplayers, takes no parameters 
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $request = $vippy->get_players($opt);
         * 
         * Returns an array with all your videoplayers and 
         * different useful values for each videoplayer 
         * 
        */       
        public function get_players($opt=Array())
        {
        	$method = 'GET';
        	$action = 'players';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;            
        }
        
        /**
         *             
         * Get the embedcode of a video
         * 
         * Function to get the embedcode to a certain video, remember 
         * that you will also need to put in some code in the HEAD 
         * section of your HTML document to get this to work properly, 
         * can take up to 8 parameters which of 1 is mandatory 
         * 
         * The different parameters are:
         *  
         * videoId: mandatory, the videoId of the specific video
         * playerId: optional, if no playerId is specified, your default 
         *           player will be used
         * size: optional, if no size is specified, the dimensions of your 
         *       embedded video will be taken from the player(default player 
         *       if no player is specified)
         * embedcode: optional, to specify  if you want to enable the function for sharing the embedcode
         * facebook: optional, to specify if you want to enable the share on facebook function
         * twitter: optional, to specify if you want to enable the share on twitter function
         * linkedin: optional, to specify if you want to enable the share on linkedIn function
         * logo: optional, to specify a logo you want to use on your embedded player, takes a logoId as value
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * $opt = array(
         *      'videoId'      => 1205,
         *      'playerId'     => 45,
         *      'size'         => "690x480",
         *   	'embedcode'    => 0,
         *   	'facebook'     => 1, 
         *      'twitter'      => 1, 
         *		'linkedin'     => 1, 
         *      'logo'         => 256
         * );
         *
         * $request = $vippy->get_embedcode($opt);
         * 
         * Returns flash embedcode
         * 
        */
        public function get_embedcode($opt=Array())
        {
        	$method = 'GET';
        	$action = 'embedvideo';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data; 
        }
        
        /**
         *             
         * Delete a video file
         * 
         * Function to delete an existing video file, takes 1 parameter 
         * 
         * The different parameters are:
         *  
         * videoId: mandatory, the videoId of the video you want to delete
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * $opt = array(
         *      'videoId'      => 123
         * );
         *
         * $request = $vippy->delete_video($opt);
         *                                                        
         * Returns either error or boolean true if successful
         * 
        */
        public function delete_video($opt=Array())
        {
        	$method = 'DELETE';
        	$action = 'video';        	        	
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data; 
        } 
        
        /**
         *             
         * Update an existing video file
         * 
         * Function to get update a video file and set new title, description and tags 
         * 
         * The different parameters are:
         *  
         * videoId: mandatory, the videoId of the video you want to update
         * title: optional, the title of your video
         * description: optional, description of your video
         * tags: optional, tags for your video, commaseparated
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * $opt = array(
         *      'videoId'      => 123,
         *      'title'        => 'This i a new title',
         *      'description'  => 'This is a new description',
         *   	'tags'         => 'new tag 1, new tag 2'
         * );
         *
         * $request = $vippy->update_video($opt);
         * 
         * Returns either error or success messages
         * 
        */
        public function update_video($opt=Array())
        {
        	$method = 'POST';
        	$action = 'video';        	        	
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data; 
        }
        
        /**
         * Get a spesific video
         * 
         * Function to get one spesific video, takes 2 parameters
         * 
         * The different parameters are:
         *  
         * videoId: mandatory, the videoId of the video you want to update
         * statistics: optional, set this to 1 if you want some simple video 
         *             statistics included with the response
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array(
         *      'videoId'      => 123,
         *      'statistics'   => 1
         * );
         * 
         * $request = $vippy->get_video($opt);
         * 
        */
        public function get_video($opt=Array())
        {
        	$method = 'GET';
        	$action = 'video';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }   
        
        /**
         * Get logo(s)
         * 
         * Function to get a list of your uploaded logos, takes no parameters
         * 
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $request = $vippy->get_logos();
         * 
         * Returns an array with useful information about your logos 
         * 
        */
        public function get_logos($opt=Array())
        {
        	$method = 'GET';
        	$action = 'logos';        	        	        	
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }
        
        /**
         * Get logo
         * 
         * Function to get one spesific logo, takes 1 parameter
         * 
         * The different parameters are:
         *  
         * logoId: mandatory, which logo you want to retrieve
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array(
         *      'logoId'      => 542
         * );
         * 
         * $request = $vippy->get_logo($opt);
         * 
         * Returns an array with useful information about your logo 
         * 
        */
        public function get_logo($opt=Array())
        {
        	$method = 'GET';
        	$action = 'logo';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }
        
        /**
         * Get usage statistics
         * 
         * Function to get some simple statistics about the current usage, takes 2 parameters
         * 
         * The different parameters are:
         *  
         * month: mandatory, the current month you want usage statistics for
         * year: mandatory, the current year you want usage statistics for
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array(
         *      'month'      => 10, 
         *      'year'       => 2010
         * );
         * 
         * $request = $vippy->get_usage($opt);
         * 
         * Return the current usage in the spesified month in bytes 
         * 
        */
        public function get_usage($opt=Array())
        {
        	$method = 'GET';
        	$action = 'usage';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }                                                 

        /**
         * get an array of thumbs for videos
         * 
         * Function to get thumbnails for videos, returns an array with videoId and thumbnail location, takes one parameter
         * 
         * The different parameters are:
         *  
         * an array of videoId's
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array(
         *      'videoId'    => Array(34, 343, 87, 45, etc)
         * );
         * 
         * $request = $vippy->get_video_thumbs($opt);
         * 
         * Returns an array of thumbsnails and videoId's
         * 
        */
        public function get_videothumbnails($opt=Array())
        {
        	$method = 'GET';
        	$action = 'videothumbnails';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }

        /**
         * List all video archive tags
         * 
         * Function to get all tags that you have set to be included in your video archive, takes one parameter 
         * 
		 * The different parameters are:
         *  
         * an archive number from Vippy associated with your client
         *
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array("archive" => 123);
         *
         * $request = $vippy->get_archive_tags($opt);
         * 
         * Returns an array with all your tags that you have set in Vippy to be included here 
         * 
        */
        public function get_archivetags($opt=Array())
        {
        	$method = 'GET';
        	$action = 'archivetags';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }                

        /**
         * Get all your video presentations
         * 
         * Function to get all your video presentations
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $request = $vippy->get_presentations($opt);
         * 
         * Returns an array with all your video presentations 
         * 
        */
        function get_presentations($opt=Array())
        {
        	$method = 'GET';
        	$action = 'presentations';        	        	
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        }
        
        /**
         * List all your video presentations
         * 
         * Function to get all your video presentations
         * 
         * Typical usage: 
         * 
         * include_once("vippy.class.php");
         * $vippy = new Vippy;
         * 
         * $opt = array("presentation" => 123)
         *
         * $request = $vippy->list_presentations($opt);
         * 
         * Returns an array with all your video presentations 
         * 
        */
        function get_presentation($opt=Array())
        {
        	$method = 'GET';
        	$action = 'presentation';        	        	
        	$action .= $this->buildGetQuery($opt);
       
            $data = $this->execute($action, $method, $opt);
            
            $data = $this->parse_callback($data);
            return $data;
        } 
		
    } 