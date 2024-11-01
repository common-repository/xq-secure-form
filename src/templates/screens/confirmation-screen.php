<?php
/**
 * confirmation template
 */
?>
<div  style="margin-bottom: 10px;">
	                      <div style=" display: flex;">
							  <span style='
    padding: 6px 21px 10px 23px;
    display: block;
    width: 688px;
    min-width: 709px;
    overflow: hidden;
    resize: none;
    min-height: 12px;
    margin-left: -2px;
    margin-top: -9px;
    border: 1px solid rgb(5, 155, 83);
    background-color: rgb(204, 244, 214);
    font-family: "Avenir Next", "Sans Serif","serif";
    font-size: 16px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: black;
    border-radius: 6px;
    box-shadow: 0 2px 20px 0 rgba(0 0 0 0.06);'
	>Saved! Your site forms will be encrypted and sent to <?php echo esc_html(get_option('xq_message_recipients'));?>
  </span>  
								
                        	</div>
                           <div 
                              style="width: 708px;
                              height: 65px;
                              margin: 29px 2px 10px 1px;
                              font-family: Avenir Next, Sans Serif,serif;
                              font-size: 18px;
                              font-weight: 500;
                              font-stretch: normal;
                              font-style: normal;
                              line-height: normal;
                              letter-spacing: normal;
                              color: #000000;">Your site forms are now protected by XQ. Track all your form submissions in your Workspace.
                           </div>
                           <div style="height: 32px;													
							width: 121px;
							padding: 13px 42px 3px 36px;
							border-radius: 5px;
							border: solid 1px #0082ff;
							background-color: #f0f9ff;">
                              <a href="<?php echo esc_url_raw($manage);?>"
								 target="_blank" 
								 rel="noopener noreferrer"
                                 style="text-decoration: unset;  
                                 height: 41px; 
								 font-family: Avenir Next, Sans Serif, serif;
                                 font-size: 16px;
                                 font-weight: 500; 
                                 font-stretch: normal; 
                                 font-style: normal; 
                                 line-height: normal; 
                                 letter-spacing: normal; 
                                 cursor: pointer; 
                                 color: #1166bb;
                                 background-color: #f0f9ff;">Visit Workspace</a>	  
                           </div>
                           						
                        