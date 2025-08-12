<?php
// We need the ABSPATH
if (!defined('ABSPATH')) exit;

// Add setting tabs
add_filter('gosmtp_settings_tabs_nav', 'gosmtp_pro_settings_tabs_nav');
function gosmtp_pro_settings_tabs_nav($navs){
	
	$offset = 1;
	$_navs = array(
		'logs-settings' => __('Logs Settings'),
		'gosmtp-connections-settings' => __('Additional Connections')
	);
	
	// Add the $_navs array in 1 position of $navs;
	$navs = array_slice( $navs, 0, $offset, true ) + $_navs  + array_slice( $navs, $offset, null, true );
	
	return $navs;
}

// Add settings tab panel
add_action('gosmtp_after_settings_tab_panel', 'gosmtp_pro_after_settings_tab_panel');
function gosmtp_pro_after_settings_tab_panel(){
	$smtp_options = get_option('gosmtp_options', array());
	
	$mailer_count = !empty($smtp_options['mailer']) ? count($smtp_options['mailer']) : 0;

	// Default mailer set mail
	if(!isset($smtp_options['mailer']) || !is_array($smtp_options['mailer']) || empty($smtp_options['mailer'][0])){
		$smtp_options['mailer'] = [];
		$smtp_options['mailer'][0]['mail_type'] = 'mail';
	}
?>

	<div class="gosmtp-tab-panel" id="logs-settings" style="display:none">
		<form class="gosmtp-logs-settings" name="logs-settings" method="post" action="">
			<?php wp_nonce_field('gosmtp-settings'); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Enable Logs'); ?></th>
					<td>
						<input id="enable_logs" name="enable_logs" type="checkbox" <?php if(!empty($smtp_options['logs']['enable_logs'])){
							echo "checked";
						}?>>
						<label for="enable_logs"><?php _e('Keep a logs of all emails sent');?></label>
						<p class="description" id="tagline-description"><?php _e( 'This will allow you to store a log and view all information about all emails sent.' ); ?></p>
					</td>
				</tr>
				<tr class="gosmtp-logs-options <?php echo empty($smtp_options['logs']['enable_logs']) ? 'gosmtp-hide' : '' ?>">
					<th scope="row"><?php _e('Save Attachments'); ?></th>
					<td>
						<input id="log_attachments" name="log_attachments" type="checkbox" <?php if(!empty($smtp_options['logs']['log_attachments'])){
							echo "checked";
						}?>>
						<label for="log_attachments"><?php _e('Save the sent attachments. ');?></label>
						<p class="description" id="tagline-description"><?php _e( 'This will allow to save all sent attachments to the logs.' ); ?></p>
						<p class="description" id="tagline-description"><i><?php _e( 'Please note, all sent attachments will be stored to your uploads folder. This could potentially cause some disk space issue.' ); ?></i></p>
					</td>
				</tr>
				<tr class="gosmtp-logs-options <?php echo empty($smtp_options['logs']['enable_logs']) ? 'gosmtp-hide' : '' ?>">
					<th scope="row"><?php _e('Log Columns'); ?></th>
					<td>
						<?php
							$logs_cols = !empty($smtp_options['logs']['log_columns']) ? maybe_unserialize($smtp_options['logs']['log_columns']) : '';
						?>
						<input name="log_columns[from]" type="checkbox" <?php if((!empty($logs_cols['from']) && $logs_cols['from']=='on') || empty($logs_cols)){
							echo "checked";
						}?>>
						<label><?php _e('Show From');?></label>
						<br>
						<input name="log_columns[to]" type="checkbox" <?php if((!empty($logs_cols['to']) && $logs_cols['to']=='on' ) || empty($logs_cols)){
							echo "checked";
						}?>>
						<label><?php _e('Show To');?></label>
						<br>
						<input name="log_columns[source]" type="checkbox" <?php if((!empty($logs_cols['source']) && $logs_cols['source']=='on' ) || empty($logs_cols)){
							echo "checked";
						}?>>
						<label><?php _e('Show Source');?></label>
							<br>
						<input name="log_columns[provider]" type="checkbox" <?php if((!empty($logs_cols['provider']) && $logs_cols['provider']=='on' ) || empty($logs_cols)){
							echo "checked";
						}?>>
						<label><?php _e('Show Provider');?></label>
						<p class="description" id="tagline-description"><?php _e( 'By using this you can show and hide above field from email logs table.' ); ?></p>
					</td>
				</tr>
				<tr class="gosmtp-logs-options <?php echo empty($smtp_options['logs']['enable_logs']) ? 'gosmtp-hide' : '' ?>">
					<th scope="row"><?php _e('Log Retention Period'); ?></th>
					<td>
						<?php
							$list_key = empty($smtp_options['logs']['retention_period']) ? '' : $smtp_options['logs']['retention_period'];
						?>
						<select name="retention_period">
							<option value="" <?php selected($list_key, '', true) ?>><?php _e('Forever'); ?></option>
							<option value="86400" <?php selected($list_key, '86400', true) ?>><?php _e('1 Day'); ?></option>
							<option value="604800" <?php selected($list_key, '604800', true) ?>><?php _e('1 Week'); ?></option>
							<option value="2628000" <?php selected($list_key, '2628000', true) ?>><?php _e('1 Month'); ?></option>
							<option value="15770000" <?php selected($list_key, '15770000', true) ?>><?php _e('6 Months'); ?></option>
							<option value="31540000" <?php selected($list_key, '31540000', true) ?>><?php _e('1 Year'); ?></option>
						</select>
						<p class="description" id="tagline-description"><?php _e( 'Email logs will be permanently deleted once they are older than the selected period.' ); ?></p>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Enable Weekly Reports'); ?></th>
					<td>
						<input id="enable_weekly_reports" name="enable_weekly_reports" type="checkbox" <?php if(!empty($smtp_options['weekly_reports']['enable_weekly_reports'])){
							echo "checked";
						}?>>
						<label for="enable_weekly_reports"><?php _e('Get weekly reports');?></label>
						<p class="description" id="tagline-description"><?php _e( 'check and get weekly email reports.' ); ?></p>
					</td>
				</tr>
				<tr id="gosmtp-week-list">
					<th scope="row"><?php _e('Email Reports Weekday'); ?></th>
					<td>
						<?php
							$list_key = empty($smtp_options['weekly_reports']['weekday']) ? '' : $smtp_options['weekly_reports']['weekday'];
							$week = array(
								'monday' => __('Monday'),
								'tuesday' => __('Tuesday'),
								'wednesday' => __('Wednesday'),
								'thursday' => __('Thursday'),
								'friday' => __('Friday'),
								'saturday' => __('Saturday'),
								'sunday' => __('Sunday'),
							);
						?>
						<select name="weekday">
							<?php foreach($week as $week_key => $week_val){
								echo "<option value='".$week_key."' ".selected($list_key, $week_key, true).">".$week_val."</option>";
							}?>
						</select>
						<a title="preview" href="<?php echo admin_url().'admin.php?page=weekly_email_reports'?>" class="gosmtp_preview"><span class="dashicons dashicons-visibility"></span></a>
						<p class="description" id="tagline-description"><?php _e( 'Select which day you want email reports delivered.' ); ?></p>
					</td>
				</tr>
				<!-- <tr>
					<th scope="row"><?php _e('Clear Logs'); ?></th>
					<td>
						<button type="submit"><?php _e('Clear Logs'); ?></button>
					</td>
				</tr> -->
			</table>
			
			<p>
				<input type="submit" name="save_settings" class="button button-primary" value="Save Changes">
			</p>
		</form>	
	</div>
	<?php

	$conn_data = [];

	$conn_id = gosmtp_optget('conn_id');
	$conn_type = gosmtp_optget('type');
	$is_visible = (!empty($conn_type) && ($conn_type == 'edit')) ? true : false;

	if($is_visible && !empty($conn_id) && isset($smtp_options['mailer'][$conn_id])){
		$conn_data = $smtp_options['mailer'][$conn_id];
		$conn_data['conn_id'] = $conn_id;
	}
	?>
		
	<div class="gosmtp-tab-panel <?php echo $is_visible ? 'gosmtp-edit-conn-open' : ''; ?>" id="gosmtp-connections-settings" style="display:none">
		<div class="gosmtp-row gosmtp-conn-title-wrap">
			<div class="gosmtp-conn-left">
				<button title="Go To Existing Connections" id="gosmtp-back-trigger"><span class="dashicons dashicons-arrow-left-alt2"></span></button>
				<h1 class="gosmtp-conn-title-existing"><?php echo __('Existing Connections'); ?></h1>
				<h1 class="gosmtp-conn-title-edit"><?php echo __('Edit Connection'); ?></h1>
				<h1 class="gosmtp-conn-title-new"><?php echo __('New Connection'); ?></h1>
			</div>
			<?php if($mailer_count > 1){ ?>
			<div class="gosmtp-conn-right">
				<button id="gosmtp-new-conn" type="button"><i class="dashicons dashicons-plus-alt"></i><span><?php echo __('Add New Connection'); ?></span></button>
			</div>
			<?php } ?>
		</div>
			
		<div class="gosmtp-row gosmtp-existing-conn-wrap">
			<form class="gosmtp-smtp-conn" name="smtp-manage-connections" method="post" action="">	
			<?php 
			
			wp_nonce_field('gosmtp-options');
			if($mailer_count == 0){
				echo "<div class='gosmtp-conn-empty-text'>".__("It appears that you haven't yet set up the primary connection! ").'<a href="#smtpsetting">'.__('click here').'</a>'.__(' to setup primary connection.').'</div>';
			}

			if($mailer_count == 1){
				echo '<div class="gosmtp-conn-empty-text">'.__('Connections not found, ').'<span id="gosmtp-new-conn-link">'.__('create one?').'</div>';
			}

			if($mailer_count > 1){

				foreach($smtp_options['mailer'] as $key => $mailer){

					if($key === 0){
						continue;
					}

					$_class = '';
					if(!empty($smtp_options['mailer'][0]['backup_connection']) && $smtp_options['mailer'][0]['backup_connection'] == $key){
						$_class = 'gosmtp-active-conn';
					}

					$icon = GOSMTP_URL .'/images/'.$mailer['mail_type'].'.svg';
					
					?>
					<div class="gosmtp-col-4">
						<div class="gosmtp-conn-item <?= $_class ?>">
							<div class="gosmtp-conn-icon">
								<img src="<?php echo $icon; ?>" class="mailer<?php echo $mailer['mail_type'] == 'postmark' || $mailer['mail_type'] == 'smtpcom' ? ' gosmtp-sm-img' : '' ?>">
							</div>
							<div class="gosmtp-conn-content">
								<span><?php echo !empty($mailer['nickname']) ? $mailer['nickname'] : __('No Name'); ?></span>
								<span><?php echo !empty($mailer['from_email']) ? __('From:'). $mailer['from_email'] : ''; ?></span>
							</div>
							<div class="gosmtp-conn-actions">
								<?php
								echo empty($_class) ? '<button title="Set As Backup Connection" class="gosmtp-backup-conn" name="make_backup_connection" type="submit" value="'.$key.'"><span class="dashicons dashicons-admin-post"></span></button>' : '<button class="gosmtp-backup-conn-clear" title="Reset Backup Connection" name="clear_backup_connection" type="submit" value="'.$key.'"><span class="dashicons dashicons-editor-unlink"></span></button>';
								?>										
								<a title="Edit Connection" class="gosmtp-edit-conn" href="<?php echo admin_url('admin.php?page=gosmtp&type=edit&conn_id='.$key.'#gosmtp-connections-settings'); ?>"><span class="dashicons dashicons-edit"></span></a>
								<button title="Delete Connection" class="gosmtp-delete-conn" name="delete_connection" type="submit" value="<?php echo $key; ?>"><span class="dashicons dashicons-trash"></span></button>
							</div>
						</div>
					</div>
					<?php
				}
			}
			?>
			</form>
		</div>

		<div class="gosmtp-row gosmtp-new-conn-wrap">
			<form class="gosmtp-smtp-conn" name="smtp-connections" method="post" action="">
				<?php  
					gosmtp_mailer_settings($conn_data, true);
				?>
			</form>
		</div>
	</div>
	<?php
}