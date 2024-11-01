<?php 
/**
* @package XQ_Secure_Form

 Plugin Name: XQ Secure Form
 Description: A Plugin to automatically encrypt your form data.
 Version: 1.1.3
 Author: XQ Message.com
 Author URI: https://xqmsg.co/
 Text Domain: xq-secure-form

 This program is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 3 of the License, or
 (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program.  If not, see <https://www.gnu.org/licenses/>.
 
*/


defined ('ABSPATH') or die('You cannot access this file');

class XQSecureForm {
	
   /**
	* Constructor
	*/
	public function __construct() {
		$file_data = get_file_data( __FILE__, array( 'Version' => 'Version' ) );

		// Plugin Details
		$this->plugin                           = new stdClass;
		$this->plugin->name                     = 'xq-secure-form'; // Plugin Folder
		$this->plugin->displayName              = 'XQ Secure Form'; // Plugin Name
		$this->plugin->version                  = $file_data['Version'];
		$this->plugin->folder                   = plugin_dir_path( __FILE__ );
		$this->plugin->url                      = plugin_dir_url( __FILE__ );
		$this->plugin->db_welcome_dismissed_key = $this->plugin->name . '_welcome_dismissed_key';

		// Hooks
		add_action( 'admin_init', array( &$this, 'registerOptions' ) );
		add_action( 'admin_menu', array( &$this, 'addSecureFormsSettingsPanel' ) );
        add_action( 'admin_footer', array( &$this, 'adjustScrolling' ) );

		// add header and footer snippets
		add_action('wp_footer', array( &$this, 'xqSecureFormsHandler' ));


	}	
	/**
	* Register Options
	* In case of xq_redirect, xq_message_recipients, dashboard_access_token & dashboard_user check the db for existing records first.
	* If there are any overwrite them
	*/
	function registerOptions() {
		
		$current_user = wp_get_current_user();

		add_option( 'current_screen', 1);
		get_option( 'xq_redirect', add_option( 'xq_redirect', null));
		get_option( 'xq_api_key', add_option( 'xq_api_key', null));
		get_option( 'xq_action', add_option( 'xq_action', true));
		get_option( 'xq_encoding', add_option( 'xq_encoding', 'html'));
		get_option( 'previous_blogname', add_option('previous_blogname', get_option('blogname')));
		add_option( 'xq_temporary_access_token', null);
		add_option( 'xq_access_token', null);
		get_option( 'xq_message_recipients', add_option( 'xq_message_recipients', null));
		get_option( 'dashboard_access_token', add_option( 'dashboard_access_token', null));
		get_option( 'dashboard_user', add_option( 'dashboard_user', $current_user->user_email ));
		

	}

	/**
	* Register the plugin settings panel
	*/
	function addSecureFormsSettingsPanel() {

		add_submenu_page( 'options-general.php', 
						   $this->plugin->displayName, 
						   $this->plugin->displayName, 
						  'manage_options', 
						   $this->plugin->name, 
						   array( &$this, 'secureFormsSettingsPanel' ) );
	}

	/**
	* Show the XQ Secure Forms Settings Pannel
	*/
	function secureFormsSettingsPanel() {

		 $activities = array('pages', 'next', 'reset');
         $activity  = isset($_POST['submit'])?sanitize_key( $_POST['submit'] ):'';
         $INITIALIZED = in_array( $activity, $activities, true )?1:0;

		if ( $activity == 'reset') {

			        $current_user = wp_get_current_user();

					update_option('current_screen', 1, true);
			        update_option('xq_redirect', null, true);
	                update_option('xq_action', true, true);
	                update_option('xq_encoding', 'html', true);
	                update_option('xq_temporary_access_token', '', true);
	                update_option('xq_access_token', null, true);
                    update_option('dashboard_access_token', null, true);
	                update_option('dashboard_user', $current_user->user_email, true);
	                update_option('xq_message_recipients', null, true);
			       		      		
					$_POST['submit'] = null;
 					$_POST['pin'] = null;
					$_POST['next-screen'] = null;
				    $INITIALIZED=0;
   								
		 }
		
		if (!get_option('current_screen') || !$INITIALIZED) {
			$_POST['next-screen'] = null;
			if( empty(get_option('dashboard_access_token')) ){
				update_option('current_screen', 1, true);
			}else{
				update_option('current_screen', 2, true);
			}
	    }
		        
		//echo '<br> $INITIALIZED ->'. ($INITIALIZED? 'Y' : ' N') . ': current_screen ' . get_option('current_screen');
	    //Now, load the XQSFController to render the current screen, i.e.login, validation, etc.
		include_once( $this->plugin->folder . '/src/types/XQSFController.php' );
		

	}

	/**
	* Outputs script to the frontend footer
	*/
	function xqSecureFormsHandler() {
				
		
		$apiKey = get_option( 'xq_api_key' );
				
		if( !empty($apiKey) ){
			?>
			<script type="module" 
					 src="https://secureform.xqmsg.com/xq.js"
					 data-xq-forms="inclusive"
					 data-xq-enc="<?php echo esc_attr(get_option('xq_encoding'));?>"
					 data-xq-format="raw"         
					<?php if( !empty(get_option('xq_action')) ){ ?> 					
					 data-xq-action="<?php echo esc_attr(get_option('xq_action'));?>"
					<?php }?>
					 data-xq-api="<?php echo esc_attr($apiKey);?>"
					>				   
			</script>
	        <script type="application/javascript">
				    console.info("Loading success listener");
                       document.addEventListener("xq_success", function () {
                       window.location.href="<?php echo esc_url_raw(get_option('xq_redirect'),array('https', 'http'));?>";
                    });
			</script>
			<?php
		}
	}
	
	
	function adjustScrolling(){
		?>
			<script type="application/javascript">
			 jQuery(document)
				 .ready(function(){
				      jQuery(this).scrollTop(0);
			       });
			 </script>

        <?php
	}



}

$xqSecureForms = new XQSecureForm();
