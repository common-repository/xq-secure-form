<?php
 /**
  * login template
  */
?>


<!-- SCREENS -->
<div style="padding: 20px 90px 99px 77px;" id="xq-secure-form-screen">	
   <div id="poststuff" style="display: flex; flex-wrap: wrap;">
		  <div id="post-body" >
			  <div style="padding-bottom:10px;">
				   <span style="position:relative; width:156px; left: -4px;">
						<?php include_once( $this->plugin->folder . '/assets/svg/logo.svg' ); ?>
				   </span>	
				   <div style='font-family: Avenir Next, Sans Serif, serif;
					  font-size: 22px;
					  font-weight: 500;
					  font-stretch: normal;
					  font-style: normal;
					  line-height: normal;
					  letter-spacing: normal;
					  color: #0082ff;'>
					 <?php echo esc_attr($this->plugin->displayName);?>
				   </div>
				  <div style="text-align: left; position: relative;top:-5px;">
				  <span>			
				   <div style="white-space: nowrap;
                               font-family: Avenir Next, Sans Serif, serif;
                               font-size: 22px;
                               font-weight: 500;
                               font-stretch: normal;
                               font-style: normal;
                               line-height: normal;
                               letter-spacing: normal;
                               color: #0082ff;
                               position: relative;
							   top: 12px;"><?php echo esc_attr($submissions);?>
					</div>
				  </span>
			   </div>
			 </div>
			 <div id="post-body-content">
				<div id="" class="meta-box-sortables ui-sortable">
				   <div class="inside">                                  
						  <form id="xq-secure-form-plugin" 
								action="<?php echo esc_url( add_query_arg( 'page',  $this->plugin->name,   network_admin_url( 'options-general.php' ) ) ); ?>"
								method="post">
							   <fieldset>
								 <input type="hidden" name="next-screen" value="<?php echo esc_attr($nextScreen);?>"/>
							  </fieldset>
							  <fieldset <?php echo ($currentScreen == CONFIRMATION) ? 'disabled=disabled':''; ?> >
								<?php if( $currentScreen == LOGIN ){
								   include_once( $this->plugin->folder . '/src/templates/screens/login-screen.php' );
								 } else if( $currentScreen == VALIDATE ){
								   include_once( $this->plugin->folder . '/src/templates/screens/validation-screen.php' );                        
								} else if( $currentScreen == CONFIRMATION ) {
									include_once($this->plugin->folder . '/src/templates/screens/confirmation-screen.php');
								} else if($currentScreen == PARTNERS ){
									  include_once( $this->plugin->folder . '/src/templates/screens/vendors-screen.php' );
								} ?>
							 </fieldset>

							 <div style="display:flex;<?php echo ($currentScreen == CONFIRMATION) ? 'padding-top: 100px;':'';?>">
								  <div style="width: 150px;										  
									text-align:center;
									display:<?php echo ($currentScreen == PARTNERS) ? 'none':'block';?>;
									margin-right:15px;		  
									height: 43px;							
									padding-top:5px;
									border-radius: 5px;
									border: solid 1px #0082ff;
									background-color: #f0f9ff;">							   
										   <button name="submit" type="submit" value="next" 
											  style="position:relative; 											
											  top:5px; 
											  border: none; 
											  background-color: #f0f9ff;
											  font-family: Avenir Next, Sans Serif,serif;
											  font-size: 16px;
											  font-weight: 500;
											  font-stretch: normal;
											  font-style: normal;
											  line-height: normal;
											  letter-spacing: normal;										  	 
											  cursor: pointer;
											  color: #1166bb;" class="xq-secure-form-next">Next                                                                                                 
										  </button>	
									 </div>						
									<div style="width: 150px;
												visibility: <?php echo ($currentScreen == LOGIN) ? 'hidden' : 'visible';?>;
												text-align:center;
												height: 43px;							
												padding-top:5px;
												border-radius: 5px;
												border: solid 1px #0082ff;
												background-color: #f0f9ff;">							   
													   <button name="submit" type="submit" value="reset" style="position:relative; 												
														  top:5px; 
														  border: none; 
														  background-color: #f0f9ff;&quot;
														  height: 41px;
														  font-family: Avenir Next, Sans Serif,serif;
														  font-size: 16px;
														  font-weight: 500;
														  font-stretch: normal;
														  font-style: normal;
														  line-height: normal;
														  letter-spacing: normal;
														  cursor: pointer;
														  color: #1166bb;" 
														  class="xq-secure-form-reset">Reset                                                                                                     											         </button>	
									 </div>
									 <div id="loading-gif" style="visibility:hidden;">
										 <?php include_once( $this->plugin->folder . '/assets/svg/loading.svg' ); ?>
									 </div>
							   </div>
						  </form>                 
				   </div>
				   <!-- /postbox -->
				</div>
				<!-- /normal-sortables -->
			 </div>
		  </div>
		  <div>			 
			   <div style="padding: 35px;
							background-color: #f0f9ff;
							border: 1px solid #1166bb;
							border-radius: 6px;
							visibility:<?php echo ($currentScreen == LOGIN)?'visible':'hidden';?>">	  
							<label style="width: 497px;
							   height: 175px;
							   font-family: Avenir Next, Sans Serif, serif;
							   font-size: 18px;
							   font-weight: 5000;
							   font-stretch: normal;
							   font-style: normal;
							   line-height: normal;
							   letter-spacing: normal;
							   color: #1166bb;">	XQ Secure Form will automatically find all the forms on <br>
							your Wordpress site and encrypt user data before it is submitted.<br><br><br>
							The form data will be emailed to you and can then be decrypted <br>
							only by the owners of the  registered email addresses.</label>		
				</div>
		   </div>
   </div>
   <div style="padding-top:25px;">	
	  <?php include_once( $this->plugin->folder . '/assets/svg/step-'.$currentScreen.'.svg' ); ?>	 
   </div>
</div>