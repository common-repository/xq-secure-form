<?php 
/**
 * Notices template
 */

?>
<div style="position:absolute; top:5px;z-index:1000;">
	<div class="<?php echo esc_attr($this->plugin->name.'-xq-notice');?>"
		 onclick="event.preventDefault();this.remove();"
		 style="width: 1206px;
				overflow: auto;
				position: relative;
				margin-left: 75px;
				padding: 7px 6px 11px 1px;
				border: solid 1px #059b53;
				background-color: #ccf4d6;
				font-family: Avenir Next, Sans Serif, serif;
				font-size: 16px;
				font-weight: 500;
				font-stretch: normal;
				font-style: normal;
				line-height: normal;
				letter-spacing: normal;
				color: red;">
		<div style="margin:5px 0 0 10px">
			<div style="float:left;width:800px; ">				
            <?php foreach (array_map('trim', explode("::", $this->errorMessage)) as $errorMessage) {?>
			   <div><?php echo esc_html($errorMessage);?></div>
			<?php }?>
			</div>
			<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
		</div>
	</div>
</div>


