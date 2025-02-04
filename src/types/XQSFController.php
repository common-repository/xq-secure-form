<?php
require_once('XQSFHTTPClient.php');

//=====================================================================================================
$dbApiKey = "e99e05e5-f4a5-4806-b603-f2ce2936126c-e19ceae8-c229-4cf1-a972-fd7c02398576";
$xqApiKey = "3ab08102-ae22-45b1-becc-3d6b5edea2eb-2196e2c1-aa63-449e-8526-8cc36a28ecad";

$dashboard = "https://dashboard.xqmsg.net/v2";
$manage = "https://manage.xqmsg.com";
$subscription = "https://subscription.xqmsg.net/v2";
$secureForms = "https://secureform.xqmsg.com/xq.js";
$xq_action_url = "https://dashboard.xqmsg.net/v2/form/encryptedmail";

//=====================================================================================================
$applicationName = "Wordpress Secure Forms";
$secureFormsApiKeyName = get_option('blogname');
$adminUrl = site_url('/wp-admin/', 'http');

const LOGIN = 1;
const VALIDATE = 2;
const CONFIRMATION = 3;
const PARTNERS = 4;

////////////////FUNCTION VARIABLES START////////////////////////////
$handleAuthorization = function($initialized, $subscriptionServerURL, $subscriptionServerApiKey){
   
	$SANITIZED_EMAIL = isset($_POST['email'])?sanitize_text_field($_POST['email']):'';

    if (is_email($SANITIZED_EMAIL)) {
        update_option('dashboard_user', $SANITIZED_EMAIL, true);
        $INITIALIZED = true;
    } else {
        if ($initialized) {
            if (strlen($SANITIZED_EMAIL) == 0) {
               return "An email address is required.";
            } else {
               return  $SANITIZED_EMAIL . ' is not a valid email address.';
            }
        }
    }

    $postBody = ['user' => get_option('dashboard_user'), 'newsletter' => false, 'notifications' => 0, 'quiet' => false, 'codetype' => 'pin'];

    $authResponse = XQSFHTTPClient::call($subscriptionServerURL, "authorize", [], $postBody, 'POST', true, $subscriptionServerApiKey, '', "en_US");

    if ($authResponse->succeeded()) {
        //stick this temporary token into your options
        update_option('xq_temporary_access_token', $authResponse->payload());

    } else {
       return esc_html($authResponse->json()['reason']);
    }
	
	return;
};
$handleSecureFormKey = function ($dashboard, $dbApiKey, $applicationName, $previousApiKeyName, $newApiKeyName, $actionUrl){

    //retrieve existing applications
    $appListingResponse = XQSFHTTPClient::call($dashboard, "devapps", [], [], 'GET', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

    $listingJSON = $appListingResponse->payload();

    $listingObj = json_decode($listingJSON, true);

    $found = array_search($applicationName, array_column($listingObj["apps"], "name"));

    if ($found !== false) {
        //XQSFHTTPClient::display('Re-using existing application: '.$appId);
        $appId = $listingObj["apps"][$found]["id"];
    } else {
		//first time in, create a new application
        //XQSFHTTPClient::display('creating new application');
        $body = ['id' => rand(), 'name' => $applicationName, 'desc' => 'Application for Secure Form API keys. Generated by Wordpress'];
        $newAppResponse = XQSFHTTPClient::call($dashboard, "devapp", [], $body, 'POST', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

        if ($newAppResponse->succeeded()) {

            $newAppJSON = $newAppResponse->payload();
            $newApp = json_decode($newAppJSON, true);

            $appId = $newApp["id"];
			
			echo '$appId' . $newApp["id"];


        } else {
           return esc_html($newAppResponse->json() ['reason']);
        }
    }

    //retrieve existing api keys in application
    $apiKeyListingResponse = XQSFHTTPClient::call($dashboard, "apikey", ["id" => $appId], [], 'GET', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

    $apiKeyListing = $apiKeyListingResponse->json();

    $apiKeyIndex = array_search($previousApiKeyName, array_column($apiKeyListing["keys"], "name"));
	
     //XQSFHTTPClient::display( '$apiKeyIndex: '. $apiKeyIndex . '| $previousApiKeyName:' . $previousApiKeyName);
    //an api key already exists, first delete the existing one
    if ($apiKeyIndex !== false) {
        $apiKeyId = $apiKeyListing["keys"][$apiKeyIndex]["id"];

        //retrieve existing applications
        $apiKeyDeleteResponse = XQSFHTTPClient::call($dashboard, 'apikey/' . $apiKeyId, [], [], 'DELETE', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

        if (!$apiKeyDeleteResponse->succeeded()) {
            XQSFHTTPClient::display( 'Couldn\'t delete old api key, error code: '. $apiKeyDeleteResponse -> responseCode());
            return  esc_html($apiKeyDeleteResponse->json() ['reason']);
         
        }

    }
	 //XQSFHTTPClient::display( '$recipients: '. $recipients);
    //create secure forms api key for your application
    $body = ["app" => $appId, "api" => 3, "name" => $newApiKeyName, "recipients" => array_map('trim', explode(',', get_option('xq_message_recipients')))];
    $apiKeyCreateResponse = XQSFHTTPClient::call($dashboard, "apikey", [], $body, 'POST', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

    if ($apiKeyCreateResponse->succeeded()) {

        $newApiKeyJSON = $apiKeyCreateResponse->payload();
		
		update_option('previous_blogname', $newApiKeyName, true);

        $newApiKey = json_decode($newApiKeyJSON, true);

        //set secure forms key variable
        update_option('xq_api_key', $newApiKey["key"]);

        $SANITIZED_ACTION = sanitize_text_field($_POST['apply_xq_action']);
        update_option('xq_action', !empty($SANITIZED_ACTION) ? $actionUrl : '', true);

        $allowed_encodings = array('html', 'csv', 'json', 'xml');
        $SANITIZED_ENCODING = sanitize_key($_POST['data-xq-enc']);
		
        $xq_encoding = !empty($SANITIZED_ENCODING) && in_array($SANITIZED_ENCODING, $allowed_encodings, true) ? $SANITIZED_ENCODING : 'html';
        update_option('xq_encoding', $xq_encoding, true);
	
    } else {
        //XQSFHTTPClient::display('$apiKeyResponse failed, responseCode: '. $apiKeyResponse -> responseCode());
       return  esc_html($apiKeyCreateResponse->json() ['status']);
			
    }
							 
     return;

};
////////////////FUNCTION VARIABLES END////////////////////////////



//////////////////////////////////////////// FLOW STARTS HERE ////////////////////////////////////////////


$submissions = '';
if(!empty(get_option('dashboard_access_token'))){

	 //log into the dashboard app and send email with a link to "settings/plans"
	$formStatisticsResponse = XQSFHTTPClient::call($dashboard, "form/statistics", [], [], 'GET', true, $dbApiKey, get_option('dashboard_access_token'), "en_US");

	if ($formStatisticsResponse->succeeded()) {		
		$formStatisticsJSON = $formStatisticsResponse->payload();
        $formStatistics = json_decode($formStatisticsJSON, true);
		$submissions = 'Submissions this month: '. $formStatistics["submissions"] . '/' . $formStatistics["limit"];

	} else{
		
		//reset and open login page 
		update_option('current_screen', 1, true);
		update_option('xq_redirect', null, true);
	    update_option('xq_action', true, true);
	    update_option('xq_encoding', 'html', true);
	    update_option('xq_temporary_access_token', '', true);
	    update_option('xq_access_token', null, true);
        update_option('dashboard_access_token', null, true);
	    update_option('dashboard_user', $current_user->user_email, true);
	    update_option('xq_message_recipients', null, true);
		update_option('current_blogname', get_option('blogname'), true);
		update_option('previous_blogname', null, true);		
		$_POST['submit'] = null;
 		$_POST['pin'] = null;
		$_POST['next-screen'] = null;
		$INITIALIZED=0;
		
	}

}

if (get_option('current_screen') == LOGIN) {
	
	if(get_option('blogname') != get_option('current_blogname') ){
			update_option('previous_blogname', get_option('current_blogname'), true);
 	}			

    $authorizationError =  $handleAuthorization($INITIALIZED, $subscription, $xqApiKey);	
	if(!empty($authorizationError)){
		 $this->errorMessage = $authorizationError;
	}

}

if (get_option('current_screen') == VALIDATE) {

     $pinMessages = array();
	 $recipientMessages = array();
	 $redirectMessages = array();
	 $blogNameMessages = array();


    if(empty(get_option('dashboard_access_token'))){
		
        //VALIDATE PIN
        $SANITIZED_PIN = sanitize_text_field($_POST['pin']);
        if (empty($SANITIZED_PIN)) {
            array_push($pinMessages, 'The PIN is required!');
        }
        if (!is_numeric($SANITIZED_PIN)) {
            array_push($pinMessages, 'The PIN must be a numeric value!');
        }
        if (strlen($SANITIZED_PIN) !== 6) {
            array_push($pinMessages, 'The PIN must consist of 6 digits!');
        }
    }

	if($INITIALIZED){
		//VALIDATE RECIPIENTS
		$SANITIZED_RECIPIENTS = array();
		$recipients = array_map('sanitize_email', explode(',', $_POST['recipients'] ));
		foreach ($recipients as $current) {
			$recipient = str_replace(' ', '', $current);
			if (!empty($recipient)) {
				if (is_email($recipient)) {
					array_push($SANITIZED_RECIPIENTS, $recipient);
				} else {
					//echo '$recipient ' . $recipient;
					array_push($recipientMessages, 'Invalid Email: ' . $recipient . '!');
				}
			}
		}
		if (empty($SANITIZED_RECIPIENTS)) {
			array_push($recipientMessages, 'Missing recipients!');
		} else {
			update_option('xq_message_recipients', implode(",", $SANITIZED_RECIPIENTS), true);
		}
		
		//VALIDATE WEBSITE NAME  
        $SANITIZED_BLOG_NAME = sanitize_text_field($_POST['blog_name']);
        if (empty($SANITIZED_BLOG_NAME)) {
            array_push($blogNameMessages, 'A Website Name Is Required!');
        } else {
 			if(get_option('blogname') != get_option('current_blogname') ){
				update_option('previous_blogname', get_option('current_blogname'), true);
 			}		
			update_option('blogname', $SANITIZED_BLOG_NAME, true);
			update_option('current_blogname', get_option('blogname'), true);
		}
		
		//VALIDATE _REDIRECT URL 
		$SANITIZED_REDIRECT_URL = esc_url_raw($_POST['apply_xq_redirect'],array('https', 'http'));
		if (!$SANITIZED_REDIRECT_URL) {		
			array_push($redirectMessages, 'Url is in not valid!');
		} else {
			update_option('xq_redirect', $SANITIZED_REDIRECT_URL, true);
		}

    }
    if (!empty($pinMessages) || !empty($recipientMessages) || !empty($blogNameMessages) || !empty($redirectMessages) ) {
		update_option('blogname', get_option('previous_blogname'), true);
		update_option('current_blogname', get_option('blogname'), true);
        $this->errorMessage = esc_html(implode("::", array_merge($pinMessages, $recipientMessages, $blogNameMessages, $redirectMessages)));
    }
    else {
        if(!empty(get_option('dashboard_access_token'))  ){
            // dashboard access token exists. the user is still signed in.
           
          if( $INITIALIZED){
			 $keyError =  $handleSecureFormKey( $dashboard, $dbApiKey, $applicationName, get_option('previous_blogname'), get_option('current_blogname'), $xq_action_url);
			 if(!empty($keyError)){
			  $this->errorMessage = $keyError;						 
			 }
		  }
			
        }else{

            //returns a 204, no content if ok
            $validationResponse = XQSFHTTPClient::call($subscription, "codevalidation", ['pin' => $SANITIZED_PIN], [], 'GET', true, $xqApiKey, get_option('xq_temporary_access_token'), "en_US");


            if ($validationResponse->succeeded()) {

                //exchange the temporary access token for a real one
                $exchangeResponse = XQSFHTTPClient::call($subscription, "exchange", [], [], 'GET', true, $xqApiKey, get_option('xq_temporary_access_token'), "en_US");

                if ($exchangeResponse->succeeded()) {
                    //stick token into your options
                    update_option('xq_access_token', $exchangeResponse->payload(), true);
                    update_option('xq_temporary_access_token', null, true);

                    //log into the dashboard app and send email with a link to "settings/plans"
                    $loginResponse = XQSFHTTPClient::call($dashboard, "login/verify", ['request' => 'sub', 'link' => 'scf'], [], 'GET', true, $dbApiKey, get_option('xq_access_token'), "en_US");

                    if ($loginResponse->succeeded()) {
                        //stick dashboard token into your options
                        update_option('dashboard_access_token', $loginResponse->payload());

                        $handleSecureFormKey( $dashboard, $dbApiKey, $applicationName, get_option('previous_blogname'), get_option('current_blogname'), $xq_action_url);

                    } else {
                        XQSFHTTPClient::display('$apiKeyResponse failed, responseCode: '. $apiKeyResponse -> responseCode());
                        $this->errorMessage = esc_html('Dashboard Login Error: ' . $loginResponse->json() ['reason']);
                    }

                } else {
                    XQSFHTTPClient::display('exchange failed, responseCode: '. $exchangeResponse -> responseCode());
                    $this->errorMessage = esc_html('Token Exchange Error: ' . $exchangeResponse->json() ['status']);
                }

            } else {
                //PIN is not valid
                 XQSFHTTPClient::display('failed: '. $validationResponse -> payload());
                $this->errorMessage = esc_html(implode("::", array_merge(array($validationResponse->json() ['reason']), $pinMessages, $recipientMessages)));

            }
        }


    }

}

$NEXT_SCREEN = sanitize_text_field($_POST['next-screen']);
if (!isset($this->errorMessage)) {
    if (!empty($NEXT_SCREEN)) {
        $currentScreen = $NEXT_SCREEN;
    } else {
        if( empty(get_option('dashboard_access_token')) ){
            $currentScreen = LOGIN;
        }else{
            $currentScreen = VALIDATE;
        }
    }
} else {
	 if (!empty($NEXT_SCREEN) ) {
       $currentScreen = $NEXT_SCREEN - 1;
    } else {
        if( empty(get_option('dashboard_access_token')) ){
            $currentScreen = LOGIN;
        }else{
            $currentScreen = VALIDATE;
        }
    }
}
$nextScreen = $currentScreen + 1;

update_option('current_screen', $currentScreen, true);


// echo '<br>$currentScreen [' . $currentScreen .']';
// echo '<br>$nextScreen [' . $nextScreen .']';

?>

<!-- SCREENS -->
<?php include_once($this->plugin->folder . '/src/templates/screens/screen-template.php'); ?>

<!-- ERROR MESSAGES RETURNED FROM THE BACK END -->
<?php if (!empty($this->errorMessage)) { ?>
    <?php include_once($this->plugin->folder . '/src/templates/messages/message-box.php'); ?>
<?php } ?>
