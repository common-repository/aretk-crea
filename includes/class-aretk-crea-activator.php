<?php

/**
 * Fired during plugin activation
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 * @author     ARETK <inquiry@aretk.com>
 */
class Aretk_Crea_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 */
	public static function activate() {
		global $wpdb;

		if ( in_array( 'curl', get_loaded_extensions() ) ) {

			$curl_information = curl_version();
			$curl_version     = $curl_information['version'];

			#if (version_compare($curl_version, '7.34') >= 0) { 
			if ( version_compare( $curl_version, '7.29' ) >= 0 ) {

				// for expirations api call
				if ( ! wp_next_scheduled( 'content_scheduler_expiration_event' ) ) {
					wp_schedule_event( time(), 'every_one_hour_expiration_event', 'content_scheduler_expiration_event' );
				}

				// for expirations subscription call
				if ( ! wp_next_scheduled( 'content_scheduler_subscription' ) ) {
					wp_schedule_event( time(), 'every_one_minutes_check_subscription', 'content_scheduler_subscription' );
				}

				// for reminder functionlity
				//for check every minute
				if ( ! wp_next_scheduled( 'content_scheduler_reminder_every_minute' ) ) {
					wp_schedule_event( time(), 'reminder_minute', 'content_scheduler_reminder_every_minute' );
				}

				/**
				 * create table for agent details
				 */
				$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_agent_table_name'" ) != $crea_agent_table_name ) {
					$crea_agent_sql = "CREATE TABLE $crea_agent_table_name (
						crea_id int(10) NOT NULL auto_increment,
						crea_agent_name varchar(255) NOT NULL DEFAULT '',
						crea_agent_id varchar(255) NOT NULL DEFAULT '',
						crea_agent_email varchar(100) NOT NULL DEFAULT '',
						crea_agent_created_date datetime,
						crea_agent_modified_date datetime,
						PRIMARY KEY (crea_id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_agent_sql );
				}

				$crea_api_log_table_name = $wpdb->prefix . ARETKCREA_API_LOG;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_api_log_table_name'" ) != $crea_api_log_table_name ) {
					$crea_api_log_sql = "CREATE TABLE $crea_api_log_table_name (
						id int(11) NOT NULL auto_increment,
						user_id int(11) NOT NULL,
						api_type varchar(50) NOT NULL,
						request_url varchar(500) NOT NULL,
						requested_data text NOT NULL,
						response_data text NOT NULL,
						created_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_api_log_sql );
				}

				$crea_api_log_exclusive_table_name = $wpdb->prefix . ARETKCREA_API_LOG_EXCLUSIVE;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_api_log_exclusive_table_name'" ) != $crea_api_log_exclusive_table_name ) {
					$crea_api_log_exclusive_sql = "CREATE TABLE $crea_api_log_exclusive_table_name (
						id int(11) NOT NULL auto_increment,
						user_id int(11) NOT NULL,
						post_id int(11) NOT NULL,
						api_type varchar(255) NOT NULL,
						request_url varchar(5000) NOT NULL,
						requested_data text NOT NULL,
						response_data varchar(5000) NOT NULL,
						created_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_api_log_exclusive_sql );
				}

				$crea_user_listing_detail_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_user_listing_detail_table_name'" ) != $crea_user_listing_detail_table_name ) {
					$crea_user_listing_detail_sql = "CREATE TABLE $crea_user_listing_detail_table_name (
						id int(11) NOT NULL auto_increment,
						user_id int(11) NOT NULL,
						username varchar(255) NOT NULL,
						ddf_type varchar(255) NOT NULL,
						created_time datetime NOT NULL,
						updated_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";

					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_user_listing_detail_sql );
				}

				//update view count table 
				$crea_listing_detail_count_table_name = $wpdb->prefix . ARETKCREA_LISTING_DETAIL_COUNT;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_listing_detail_count_table_name'" ) != $crea_listing_detail_count_table_name ) {
					$crea_user_listing_document_detail_sql = "CREATE TABLE $crea_listing_detail_count_table_name (
					id int(11) NOT NULL auto_increment,
					property_id int(20) NOT NULL,
					property_type varchar(255) NOT NULL,
					view_count varchar(255) NOT NULL,
					PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_user_listing_document_detail_sql );
				}

				$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_listing_images_detail_table_name'" ) != $crea_listing_images_detail_table_name ) {
					$crea_user_listing_image_detail_sql = "CREATE TABLE $crea_listing_images_detail_table_name (
						id int(11) NOT NULL auto_increment,
						user_id int(11) NOT NULL,
						unique_id bigint(20) NOT NULL,
						image_position int(11) NOT NULL,
						image_url varchar(255) NOT NULL,
						created_time datetime NOT NULL,
						updated_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_user_listing_image_detail_sql );
				}

				$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_listing_document_detail_table_name'" ) != $crea_listing_document_detail_table_name ) {
					$crea_user_listing_document_detail_sql = "CREATE TABLE $crea_listing_document_detail_table_name (
						id int(11) NOT NULL auto_increment,
						user_id int(11) NOT NULL,
						unique_id bigint(20) NOT NULL,
						document_url varchar(255) NOT NULL,
						document_name varchar(255) NOT NULL,
						created_time datetime NOT NULL,
						updated_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_user_listing_document_detail_sql );
				}

				$crea_lead_reminder_detail_table_name = $wpdb->prefix . ARETKCREA_LEAD_REMINDER_HISTORY;
				if ( $wpdb->get_var( "SHOW TABLES LIKE '$crea_lead_reminder_detail_table_name'" ) != $crea_lead_reminder_detail_table_name ) {
					$crea_lead_reminder_detail_sql = "CREATE TABLE $crea_lead_reminder_detail_table_name (
						id int(11) NOT NULL auto_increment,
						reminder_lead_id int(11) NOT NULL,
						reminder_name varchar(255) NOT NULL,
						reminder_subject varchar(255) NOT NULL,
						reminder_email varchar(255) NOT NULL,
						reminder_comment varchar(2000) NOT NULL,
						reminder_time varchar(255) NOT NULL,
						reminder_repeat varchar(255) NOT NULL,
						created_time datetime NOT NULL,
						updated_time datetime NOT NULL,
						PRIMARY KEY (id)
					);";
					require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
					dbDelta( $crea_lead_reminder_detail_sql );
				}
				flush_rewrite_rules();
			} else {
				wp_die( "<strong>cURL version is to low.</strong> Your server is currently using cURL version <strong>" . $curl_version . "</strong>, however this plugin requires cURL version <strong>7.34</strong> or newer. Please contact your server person to upgrade your cURL. " );
			}
		} else {
			wp_die( "<strong>cURL is Disabled.</strong> The aretk-crea requires cURL, please enable cURL on your server. For more information, You need to contact your server person." );
		}
	}
}