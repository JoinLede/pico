<form id="pico-container" action="<?php echo get_site_url(); ?>/wp-admin/admin.php?page=pico-plugin" method="post">
	<?php
        if ( function_exists('wp_nonce_field') )
            wp_nonce_field('pico-disconnect');
    ?>
    <!--Connect form -->
	<div class="form pico">
		<div class="header-form"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/logo.svg'?>" alt="Pico"></div>
		<div class="description-form">
			<p><b>Connect your Pico account.</b><br>Your Account ID and API Key can be found in your Pico dashboard under <a href="https://publisher.pico.tools/settings/integrations" target="blank">Settings ► Website</a>.</p>
		</div>
		<p class="login-label">Account ID</p>
		<div class="input-wrapper">
            <?php $publisher_creds = Pico_Setup::get_publisher_id(true); ?>
			<input type="text" id="publisher_id" name="publisher_id" value="<?php echo $publisher_creds['publisher_id']?>" disabled>
			<img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/ic_selected.svg'?>" class="input-img">
		</div>
		<p class="login-label">API Key</p>
		<div class="input-wrapper">
			<input type="password" id="api_key" name="api_key" value="<?php echo $publisher_creds['api_key']?>" disabled>
			<img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/ic_selected.svg'?>" class="input-img">
		</div>
		<?php $health_status = Pico_Menu::health_check(); ?>
		<?php echo $health_status; ?>
		<input type="hidden" name="action" value="disconnect-pp">
		<input type="button" class="pico-button pico-button-danger open-modal" value="Disconnect">
		<!-- <button>Connect</button> -->
		<div style="clear: both"></div>
	</div>
    <!--Publisher app link -->
	<div class="footer-form">
		<div class="f-left"><p>Manage email registration, revenue, and content access settings for this site in your <a href="https://publisher.pico.tools" target="_blank">Pico dashboard</a>.</p></div>
		<div class="f-right"><a href="https://publisher.pico.tools" target="_blank"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/ic_launch.svg'?>" alt="Launch Pico"></a></div>
	</div>
    <!--Confirm disconnect -->
	<div id="modal-container" class="modal-disconnect">
	  <div class="modal" id="alert">
		<div class="header">
			<div class="close-alert"><img src="<?php echo plugin_dir_url( __DIR__ ) . 'img/ic_close.svg'?>" alt="Close"></div>
			<div class="alert-title"><b>Disconnect Pico?</b></div>
		</div>
		<div class="message">
		    Email registration, revenue, and content access settings for this site will be suspended effective immediately.
		</div>
		<div class="footer">
			<input type="submit" name="submit" id="submit" class="pico-button pico-button-danger close-modal" value="Disconnect">
			<input type="button" name="cancel" id="cancel" class="pico-button pico-button-neutral close-modal" value="Cancel">
		</div>
	  </div>
	</div>
</form>
