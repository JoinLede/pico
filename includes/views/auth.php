<div id="pico-container">
    <div class="form pico">
    	<div class="header-form"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/wordmark.svg'?>" alt="Pico"></div>
    	<div class="description-form">
    		<p><span><b>Connect your Pico Publisher account.</b></span><br>Your Publisher ID and API Key can be found under the Integrations tab of your <a href="https://publisher.pico.tools/settings/integrations" target="_blank">Pico settings</a>.</p>
    	</div>
		<form class="login-form" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=pico-plugin" method="post" autocomplete="off">
			<?php
                if ( function_exists('wp_nonce_field') )
                    wp_nonce_field('pico-connect');
            ?>
    		<p class="login-label">Publisher ID</p>
    		<input type="text" id="publisher_id" name="publisher_id">
    		<p class="login-label">API Key</p>
    		<input type="text" id="api_key" name="api_key">
    		<input type="hidden" name="action" value="enter-key">
    		<input type="submit" name="submit" id="submit" class="pico-button pico-button-primary" value="Connect">
    		<!-- <button>Connect</button> -->
    		<div style="clear: both"></div>
    	</form>
    </div>
    <div class="footer-form">
    	<div class="f-left"><p>Manage email registration, revenue, and content access settings for this site in your <a href="https://publisher.pico.tools" target="_blank">Pico dashboard</a>.</p></div>
    	<div class="f-right"><a href="https://publisher.pico.tools" target="_blank"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/ic_launch.svg'?>" alt="Launch Pico"></a></div>
    </div>
</div>
