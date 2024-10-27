<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 * @author     ARETK <inquiry@aretk.com>
 */
class Aretk_Crea_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function aretkcrea_deactivate() {
		wp_clear_scheduled_hook( 'cron_schedules' );
		wp_clear_scheduled_hook( 'content_scheduler_subscription' );
		wp_clear_scheduled_hook( 'content_scheduler_expiration_event' );
		wp_clear_scheduled_hook( 'content_scheduler_reminder_every_minute' );
		flush_rewrite_rules();
	}
}