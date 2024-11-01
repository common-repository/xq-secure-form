<?php
	/**
	 * login template
	 */
?>


<div style=" display: flex;">
                           <span 
                              style="position: relative; 
									 left:0;
									 width: 542px;
                                     height: 25px;
                                     font-family: Avenir Next, Sans Serif, serif;
                                     font-size: 18px;
                                     font-weight: 500;
                                     font-stretch: normal;
                                     font-style: normal;
                                     line-height: normal;
                                     letter-spacing: normal;
                                     color: #000000;">Please enter your email to validate your account													
                           </span>	
                           <span style="width:0;height:0;position:relative;left:-140px;top:-37px;">
                                   	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-1.svg' ); ?>
                           </span>
                        </div>
                        <div>
                           <input type="text" 
                              name="email"
                              value = "<?php echo esc_attr(get_option('dashboard_user', ''));?>"
                              placeholder="Email Here"
                              style='position: relative; 
									 left:0;
									 width: 392px;
                                     font-family: Avenir Next, Sans Serif, serif;
                                     height: 50px;
                                     margin: 28px 50px 32px 0;
                                     padding-left: 13px;
                                     border-radius: 6px;
                                     box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
                                     background-color: #ffffff;'
                              />
                        </div>
  