<?php
class Vippy_core{

    protected $url;  
    protected $verb;  
    protected $requestBody;  
    protected $requestLength;  
    protected $username;  
    protected $password;  
    protected $acceptType;  
    protected $responseBody;  
    protected $responseInfo;
    protected $responseStatus;       			      	      	     	
  	
    public function __construct()  
    {  
        $this->url               = VPY_HOST;  
        $this->verb              = 'GET';  
        $this->requestBody       = null;  
        $this->requestLength     = 0;  
        $this->username          = null;  
        $this->password          = null;  
        $this->acceptType        = $this->get_format(VPY_FORMAT);  
        $this->responseBody      = null;  
        $this->responseInfo      = null;  
  		$this->headers 			 = array();
  		$this->responseStatus    = null;       		
  		
        if ($this->requestBody !== null)  
        {  
            $this->buildPostBody();  
        }
    }  
  	
    public function flush ()  
    {  
        $this->requestBody       = null;  
        $this->requestLength     = 0;  
        $this->verb              = 'GET';  
        $this->responseBody      = null;  
        $this->responseInfo      = null; 
        $this->responseStatus    = null; 
    }  
  
    public function execute($action = null, $verb = 'GET', $requestBody = null)  
    {  
    	$this->curl_url = $this->url.$action;
    	$this->verb = $verb;

    	$this->requestBody = $requestBody;
    	
	    $ch = curl_init();  
	    $this->setAuth($ch);  		  	
	    try  
	    {  
	        switch (strtoupper($this->verb))  
	        {  
	            case 'GET':  
	                return $this->executeGet($ch);  
	                break;  
	            case 'POST':
	                return $this->executePost($ch);  
	                break;  
	            case 'PUT':  
	                return $this->executePut($ch);  
	                break;  
	            case 'DELETE':  
	                return $this->executeDelete($ch);  
	                break;  
	            default:  
	                throw new InvalidArgumentException('Current verb (' . $this->verb . ') is an invalid REST verb.');  
	        }  
	    }  
	    catch (InvalidArgumentException $e)  
	    {  
	        curl_close($ch);  
	        throw $e;  
	    }  
	    catch (Exception $e)  
	    {  
	        curl_close($ch);  
	        throw $e;  
	    }       
    }  
  
    public function buildPostBody ($data = null)  
    {  
		$data = ($data !== null) ? $data : $this->requestBody;  
		
		if (!is_array($data))  
		{  
		    throw new InvalidArgumentException('Invalid data input for postBody.  Array expected');  
		} 
		
		$data = http_build_query($data, '', '&');  
		$this->requestBody = $data;       
    }  
  
    protected function executeGet ($ch)  
    {         
  		return $this->doExecute($ch);
    }  
  
    protected function executePost ($ch)  
    {  
	    if (!is_string($this->requestBody))  
	    {  
	        $this->buildPostBody();  
	    } 
	    
	    $this->add_header('Content-Type', 'application/x-www-form-urlencoded'); 

	    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody);  
	    curl_setopt($ch, CURLOPT_POST, 1);  
	  
	    return $this->doExecute($ch);      
    }  
  	
    protected function executePut ($ch)  
    {      
		// Handle meta tags. Can also be passed as an HTTP header.
		if (isset($this->requestBody))
		{
        	if(is_array($this->requestBody))
        	{
        		if(isset($this->requestBody['video']))
        		{
        			$inputFilename = $this->requestBody['video'];
        			
					$extension = explode('.', $inputFilename);
					$extension = array_pop($extension);
					$mime_type = VippyMimeTypes::get_mimetype($extension);
					$this->add_header('Content-Type', $mime_type);
					$this->add_header('Expect', '');
					$this->add_header('Transfer-Encoding', '');											
        		}

				foreach ($this->requestBody as $meta_key => $meta_value)
				{
					$this->add_header('x-vpy-'.strtolower(str_replace(' ', '-', $meta_key)), $meta_value);
				}
        	}
		}
		else
		{
			return false;
		}	
		
		unset($this->requestBody);
	    
        $firstFour = substr($inputFilename, 0, 4);
        if(strtolower($firstFour) == "http")
        {
            $fSize = $this->remote_filesize($inputFilename);                       
	        if($fSize == false){
	        	throw new Exception("Input file is not a resource");	
	        }
        }
        else
        {
			if(!file_exists($inputFilename)){
				throw new Exception('Input file is not a resource');
			}			
        	$fSize = filesize($inputFilename);        	 
        }
        
        $hash = md5_file($inputFilename);
		$this->add_header('Content-MD5', $hash);
		
		$this->requestLength = $fSize;
		$this->add_header('Content-Length', (int)$this->requestLength);
		
		$fh = fopen($inputFilename, 'rb');
											
	    curl_setopt($ch, CURLOPT_INFILE, $fh);  
	    curl_setopt($ch, CURLOPT_INFILESIZE, $this->requestLength);
	    curl_setopt($ch, CURLOPT_PUT, true);
	    $ret = $this->doExecute($ch);  

	    fclose($fh);
	    
	    return $ret;      
    }  
  
    protected function executeDelete ($ch)  
    {  
	    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	    
	    if (!is_string($this->requestBody))  
	    {  
	        $this->buildPostBody();  
	    }  
	    
	    $this->add_header('Content-Type', 'application/x-www-form-urlencoded');

	    curl_setopt($ch, CURLOPT_POSTFIELDS, $this->requestBody); 
	    unset($this->requestBody);
	  
	    return $this->doExecute($ch);        
    }  
  
    protected function doExecute ($curlHandle)  
    {  
    	$this->signRequest();
    	
	    $this->setCurlOpts($curlHandle);		    		    	    
	    set_time_limit(0);
	    $this->responseBody = curl_exec($curlHandle);  
	    $this->responseInfo  = curl_getinfo($curlHandle);
		$this->responseStatus = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);			     		  		    
	     
	    curl_close($curlHandle);
	    return $this;
    }  
	
	protected function get_format($format='xml')        
    {        	
		$_supported_formats = array(
			'xml' => 'application/xml',
			'rawxml' => 'application/xml',
			'json' => 'application/json',
			'jsonp' => 'application/javascript',
			'serialize' => 'application/vnd.php.serialized',
			'php' => 'text/plain',
			'html' => 'text/html',
			'csv' => 'application/csv'
		);
		return $_supported_formats[$format];        
    }		
	
	protected function add_header($key, $value)
	{
		$this->headers[$key] = $value;	
	}		
	
	protected function remove_header($key)
	{
		if(isset($this->headers[$key])){
			unset($this->headers[$key]);
		}
	}		
	
	protected function signRequest()
	{
		$date = gmdate("D, d M Y H:i:s O", time());					
  		
  		$this->add_header('x-vpy-version', VPY_VERSION);			
		$this->add_header('Date', $date);
		
        $string_to_sign = '';
        
        // Sort headers
		uksort($this->headers, 'strnatcasecmp');
         			        	
    	foreach($this->headers as $header_key => $header_value)
    	{

			// Generate the string to sign
			if(
				strtolower($header_key) === 'content-md5' ||
				strtolower($header_key) === 'content-type' ||
				strtolower($header_key) === 'date'
			)
			{
				$string_to_sign .= $header_value;
			}
			elseif (substr(strtolower($header_key), 0, 6) === 'x-vpy-')
			{
				$string_to_sign .= $header_value;
			}
    	}		

    	if(is_array($string_to_sign)){
    		$string_to_sign = implode('', $string_to_sign);	
    	}
    	$string_to_sign = str_replace(' ', '', $string_to_sign);        	
    	$string_to_sign = strtolower($string_to_sign);
    	$signature = base64_encode(hash_hmac('sha1', $string_to_sign, VPY_API_SECRET_KEY, true));
		$this->add_header('Authorization', 'Vippy '.VPY_API_KEY.':'.$signature);
	}
	
    protected function setHeaders ($curlHandle)  
    {          
    	$this->add_header('Accept', $this->acceptType);   	

		$temp_headers = array();

		foreach($this->headers as $k => $v)
		{			
			$v = str_replace(array("\r", "\n"), '', $v);	
			$temp_headers[] = $k . ': ' . $v;
		} 			

	    curl_setopt($curlHandle, CURLOPT_HTTPHEADER, $temp_headers); 		   
    }
  
    protected function setCurlOpts ($curlHandle)  
    {  
    	if($this->verb == 'PUT'){        		
    		$this->remove_header('Content-Length');
    	}else{
    		curl_setopt($curlHandle, CURLOPT_TIMEOUT, 10);
    	}	
    	
	    curl_setopt($curlHandle, CURLOPT_URL, $this->curl_url);  
	    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);	    
	    
		$this->setHeaders($curlHandle);       
    }  
  
    protected function setAuth ($curlHandle)  
    {  
	    if ($this->username !== null && $this->password !== null)  
	    {  
	        curl_setopt($curlHandle, CURLOPT_HTTPAUTH, CURLAUTH_DIGEST);  
	        curl_setopt($curlHandle, CURLOPT_USERPWD, $this->username . ':' . $this->password);  
	    }      
    } 
    
    protected function remote_filesize($url, $user = "", $pw = "")
    {
    	ob_start();
    	$ch = curl_init($url);
    	curl_setopt($ch, CURLOPT_HEADER, 1);
    	curl_setopt($ch, CURLOPT_NOBODY, 1);
		
		    	
    	if(!empty($user) && !empty($pw))
    	{
    		$headers = array('Authorization: Basic ' .  base64_encode("$user:$pw"));
    		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    	}
    
    	$ok = curl_exec($ch);
    	$retcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    	curl_close($ch);
    	$head = ob_get_contents();
    	ob_end_clean();
    	$regex = '/Content-Length:\s([0-9].+?)\s/';
    	$count = preg_match($regex, $head, $matches);
    
    	$size = isset($matches[1]) ? $matches[1] : false;
    	//echo 'retcode: '.$retcode;die();
    	if($size && $retcode == 200){
    		return $size;	
    	}else{
    		return false;
    	}
    }                
	
	public function parse_callback($response)
	{
        if(VPY_FORMAT == "json"){
            try{
                $r = $response->responseBody;
                $r = json_decode($response->responseBody);
                if ($r === null) {
                    throw new Exception("Failed to decode the response as json");
                }   
            }catch (Exception $e){
                var_dump($e->getMessage());
            } 
        }elseif(VPY_FORMAT == "xml" || VPY_FORMAT == "rawxml"){
            try{
                $r = simplexml_load_string($response->responseBody, null, LIBXML_NOCDATA); //LIBXML_NOCDATA
                if ($r === null){
                    throw new Exception("Failed to decode the response as xml");
                }  
            }catch (Exception $e){
                var_dump($e->getMessage());
            }     
        }elseif(VPY_FORMAT == "serialize"){
        	$r = unserialize($response->responseBody);
        }
        else{
        	$r = $response->responseBody;	
        }

		return $r;
	}
	
	public function buildGetQuery($opt)
	{
		$str = http_build_query($opt, '', '&');		
		//$str = str_replace('&', '/', str_replace('=', '/', $str));		
		$action = '/?'.$str;
		return $action;	
	}

}