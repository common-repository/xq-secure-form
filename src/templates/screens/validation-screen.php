<?php
/**
 * validation template
 */

?>

<?php if(empty(get_option('dashboard_access_token'))){ ?>
                        <div style=" display: flex;">
                           <span 
                              style="width: 542px;
                              height: 25px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #000000;">Enter the validation pin emailed to you by XQ												
                           </span>	
                           <span style="width:0;height:0;position:relative;left:-140px;top:-37px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-2.svg' ); ?>
                           </span>
                        </div>
                        <div>
                           <input type="text" 
                              name="pin" 
							  maxlength=6
                              placeholder="Pin Here"
                              style="width: 517px;
                              font-family: Avenir Next, Sans Serif,serif;
                              height: 50px;
                              margin: 28px 50px 32px 0;
                              padding: 13px 321px 12px 19px;
                              border-radius: 6px;
                              box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
                              background-color: #ffffff;"
                              />												
                        </div>
<?php } ?>
                        <div style=" display: flex; padding-bottom:35px;">
                           <span  style="width: 542px;
                                         height: 25px;
                                         font-family: Avenir Next, Sans Serif,serif;
                                         font-size: 18px;
                                         font-weight: 500;
                                         font-stretch: normal;
                                         font-style: normal;
                                         line-height: normal;
                                         letter-spacing: normal;
                                         color: #000000;">Let XQ send me Emails
										<input type="checkbox"
											   name="apply_xq_action" 
											   style="position:relative; left:7px;"
											   onclick="if(checked){		
													document.getElementById('delivery-format').disabled=true;	
													document.getElementById('delivery-format').options[0].selected = true;
													document.getElementById('data-xq-enc').value='text';
													document.getElementById('delivery-format-container').style.color = '#808080';		
												}else{
												   document.getElementById('delivery-format').disabled=false;	
												   document.getElementById('delivery-format-container').style.color = '#000000';
												}" 		
											   <?php echo !empty(get_option('xq_action')) ?'checked':'';?>
											   >
                             </span>	
						     
                             <span style="width:0;height:0;position:relative;left:-140px;top:-37px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-3.svg' ); ?>
                             </span>
                        </div>   
                               
                         <div style="display:flex; visibility: visible; padding-bottom:35px;">
                           <span id="delivery-format-container" style="width: 542px;
                              height: 25px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #808080;">Delivery Format

							<select id="delivery-format" 
									style="position:relative; left:7px;" 
									onchange="document.getElementById('data-xq-enc').value=this.value;" 
									<?php echo !empty(get_option('xq_action')) ?'disabled':'';?>
									>
							       <option value="html" <?php echo esc_attr(get_option('xq_encoding')) == 'html'?'selected':'';?> >Text</option>
							       <option value="csv"  <?php echo esc_attr(get_option('xq_encoding')) == 'csv'?'selected':'';?> >CSV</option>
							       <option value="json" <?php echo esc_attr(get_option('xq_encoding')) == 'json'?'selected':'';?> >JSON</option>
							       <option value="xml"  <?php echo esc_attr(get_option('xq_encoding')) == 'xml'?'selected':'';?> >XML</option>
							</select>
							<input type="hidden" name="data-xq-enc" id="data-xq-enc" value="html">								  
                           </span>	
                           <span style="width:0;height:0;position:relative;left:-140px;top:-37px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-4.svg' ); ?>
                           </span>                    
                        </div>                             
                        <div style=" display: flex;">
                           <span 
                              style="width: 542px;
                              height: 25px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #000000;">
							   <div>Enter (comma seperated) emails of the users to whom</div>
							   <div>you want to give read access of your forms</div>
						   </span>
                           <span style="width:0;height:0;position:relative;left:-140px;top:-20px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-5.svg' ); ?>
                           </span>
                        </div>
                        <div>
                           <input type="text" 
                              name="recipients"
                              value="<?php echo esc_attr(!get_option('xq_message_recipients')? get_option('dashboard_user'): get_option('xq_message_recipients'));?>"
                              placeholder="Users Here"
                              style="width: 517px;
                                     font-family: Avenir Next, Sans Serif,serif;
                                     height: 50px;
                                     margin: 46px 50px 32px 0;
                                     padding-left: 13px;
                                     border-radius: 6px;
                                     box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
                                     background-color: #ffffff;"
                              />												
                        </div>
                      <div style=" display: flex;">
                           <span 
                              style="width: 542px;
                              height: 25px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #000000;">
							    <div></div>
							    <div>Enter Your Website's Name</div>
						   </span>
                           <span style="width:0;height:0;position:relative;left:-140px;top:-20px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-6.svg' ); ?>
                           </span>
                        </div>
                        <div>							
                           <input type="text" 
                              name="blog_name"
                              value="<?php echo esc_attr(get_option('blogname')? get_option('blogname'): '');?>"
                              placeholder="Your Wesite Name Here"
                              style="width: 517px;
                                     font-family: Avenir Next, Sans Serif,serif;
                                     height: 50px;
                                     margin: 46px 50px 32px 0;
                                     padding-left: 13px;
                                     border-radius: 6px;
                                     box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
                                     background-color: #ffffff;"
                              />												
                        </div>
                       <div style=" display: flex;">
                           <span 
                              style="width: 542px;
                              height: 25px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #000000;">
							   <div>Enter the URL of the page to which you would like to</div>
							   <div>redirect users after succesful form submission</div>
						   </span>
                           <span style="width:0;height:0;position:relative;left:-140px;top:-20px;">
                              	<?php include_once( $this->plugin->folder . '/assets/svg/question-mark-7.svg' ); ?>
                           </span>
                        </div>
                        <div>
                           <input type="text" 
                              name="apply_xq_redirect"
                              value="<?php echo esc_attr(get_option('xq_redirect')? get_option('xq_redirect'): '');?>"
                              placeholder="Redirect URL Here"
                              style="width: 517px;
                                     font-family: Avenir Next, Sans Serif,serif;
                                     height: 50px;
                                     margin: 46px 50px 32px 0;
                                     padding-left: 13px;
                                     border-radius: 6px;
                                     box-shadow: 0 2px 20px 0 rgba(0, 0, 0, 0.06);
                                     background-color: #ffffff;"
                              />												
                        </div>