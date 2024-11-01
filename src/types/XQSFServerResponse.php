<?php 

require_once('XQSFStatusCodes.php');

/**
 * Class ServerResponse
 * This class encapsulates all responses received from the XQ servers.
 */
class XQSFServerResponse {

    private int $responseCode;

    /**
     * @var string|array|object
     */
    private $payload;

    /**
     * ServerResponse constructor.
     * @param int $responseCode
     * @param string|array|object $payload
     */
    public function __construct($responseCode, $payload )
    {
        $this->responseCode = (int)$responseCode;
        $this->payload = $payload;
		
    }

    /**
     * @return bool
     */
    public function succeeded() : bool {
		
		$code = 0 !== $this->responseCode && 399 > $this->responseCode;
		
		$value=!empty($code)?$code:0;
		
		//HTTPClient::display($this->responseCode .' success '.$value);
	  
		return $value;
    }

    /**
     * @return int
     */
    public function responseCode() : int {
        return $this->responseCode;
    }

    
	/**
     * @return Array
     */
    public function json() : Array {
        if (is_string($this->payload)) {
			 return json_decode($this->payload, true, 512, JSON_OBJECT_AS_ARRAY);
        } else {
            return (Array) $this->payload;
        }
    }

    /**
     * @return string
     */
    public function payload() : string {
        if (is_string($this->payload)) {
            return $this->payload;
        } else {
            return json_encode($this->payload, false);
        }
    }

    /**
     * @return string
     */
    public function status(bool $withCode=true) : string {

        if ($this->succeeded()) return "OK";

        if (is_string($this->payload)) {
            $result = json_decode($this->payload, false);
            if (!$result || !isset($result->status)){
                return $withCode? XQSFStatusCodes::getMessageForCode( $this->responseCode()) . " - " . $result->payload :  $result->payload;	
            }
			return $withCode? XQSFStatusCodes::getMessageForCode( $this->responseCode()) . " - " . $result->status :  $result->status;			
        }
        else {
            $result = $this->payload;
            if (!$result || !isset($result->status)){
                return XQSFStatusCodes::getMessageForCode( $this->responseCode());
            }
            return $withCode? XQSFStatusCodes::getMessageForCode( $this->responseCode()) . " - " . $result->status : $result->status;
        }


    }

    /**
     * @param string $reason
     * @return ServerResponse
     */
    public static function error(int $code,  $reason) : XQSFServerResponse {
        return new XQSFServerResponse($code,  $reason );
    }

    /**
     * @param string|array|object $result
     * @return ServerResponse
     */
    public static function ok( $result ) : XQSFServerResponse {
        return new XQSFServerResponse(XQSFStatusCodes::HTTP_OK, $result );
    }
}