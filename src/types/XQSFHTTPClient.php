<?php 

require_once('XQSFServerResponse.php');

/**
 * Class ServerResponse
 * This class encapsulates all responses received from the XQ servers.
 */
class XQSFHTTPClient {


public static function display($result){
    echo esc_html("<br>".$result."<br>");
}

 public static function call( string $host,
                              string $endpoint,
                              array  $params = array(),
                              array  $body = array(),
                              string $method,
							    bool $encode=true,
                              string $apiKey='',
                              string $bearerAuth='',
                              string $language="en_US", 
			                    bool $followLocation=true							    
							):XQSFServerResponse  {
	 
 //XQSFHTTPClient::display( '$params:' . $host.' : '. $endpoint .' : '. sizeOf($params) .' : '. var_dump($body) . ' : ' . $method. ' : ' . $apiKey . ' : ' . $bearerAuth);
        try {

            $url = $host . "/" . $endpoint;	
			
            if (sizeOf($params)>0 ) {				
					 $url .= '?' . urldecode(http_build_query( $params ));
			}else{
                    $url .= '?' . http_build_query( $params );		
            }
			
            $headers = array(); 
                        
	        if ($bearerAuth && $bearerAuth !== '' ) {
                $headers['Authorization'] = 'Bearer'. $bearerAuth;
            }
           
			if ($apiKey && $apiKey !== '') {
                $headers['Api-Key'] = $apiKey;
            }
           
			if ($language && $language !== '') {
                $headers["Accept-Language"] = $language;
            }
								 		
            if ( $method == "POST") {		
               $output =  wp_remote_post($url, array('body' => wp_json_encode($body), 'headers' => $headers, 'timeout' =>  15));			
            }
			else if ($method == "PATCH"){
				$output =  wp_remote_request($url, array('method' => 'PATCH', 'headers' => $headers, 'body' => wp_json_encode($body), 'timeout' =>  15));						
			}
			else if ($method == "DELETE"){
				$output =  wp_remote_request($url, array('method' => 'DELETE', 'headers' => $headers, 'timeout' =>  15));								
			}
			else{   	
               $output =  wp_remote_get($url, array('headers' => $headers, 'timeout' =>  15));				
            }		

     		$responseBody = wp_remote_retrieve_body($output);

			if(!is_wp_error($output) && ( 200 <= $output['response']['code']  &&   299 >= $output['response']['code'])) {
			    //HTTPClient::display( 'Success '. var_dump( $responseBody));
                return new XQSFServerResponse($output['response']['code'], $responseBody );
           }
           else {			   
			    
			    if(json_decode($responseBody) === null){
					//HTTPClient::display( 'Failure '. var_dump( array("reason"=>$responseBody) ));
			    	return new XQSFServerResponse($output['response']['code'], array("reason"=>$responseBody) );
 				}
                else{
					//HTTPClient::display( 'Failure '. var_dump( $responseBody));
			      return new XQSFServerResponse($output['response']['code'], $responseBody);
	           }
           }
 				   
        }
        catch (Exception $e) {
            return new XQSFServerResponse (500,  array( "reason"=>$e->getMessage() ) );
        }
        
    }
    
}