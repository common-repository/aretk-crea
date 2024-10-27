<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/admin
 * @author     Aretk Inc. <inquiry@aretk.com>
 */
class Aretk_Crea_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public static function aretkcrea_get_property_detail_page_result( $userName, $result_type, $property_id ) {
		global $wpdb;
		$user_ID            = get_current_user_id();
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
		$subscriptionKey    = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';
		$result_type        = 'full';
		if ( ! empty( $property_id ) && $property_id != null ) {
			$property_id = '&ids=' . $property_id;
		}
		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}
		
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=listings&exclusive=true&viewcount=true&feed=$userName&result_type=$result_type$property_id" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSet = json_decode( $data, true );
		return $resultSet;
	}

	public static function aretkcrea_answer_expiration_event_admin() {
		global $wpdb;
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
		update_option( 'cron_run', "" );
		update_option( 'cron_run_time', "" );
		update_option( 'cron_run_time', date( "h:i:sa" ) );

		if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
			$allListingArr      = array();
			$getAllAgentIdArray = array();

			$agent_ids    = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'list' );
			$userNameList = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'list' );

			$allListingArr = array();
			if ( ! empty( $userNameList ) && ! empty( $agent_ids ) ) {
				$result_type = 'full';
				$listings    = Aretk_Crea_Admin::aretkcrea_get_listing_records_based_on_agents( $userNameList, $result_type, $agent_ids );
				if ( isset( $listings ) && ! empty( $listings ) ) {
					foreach ( $listings as $listing_key => $listing ) {
						if ( ! isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) {
							$allListingArr[ $listing->mlsID ] = $listing;
						}
					}
				}
			}
			$args         = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array  = (array) get_posts( $args );
			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = array_merge( $allListingArr, $exclusiveArr );
			$data               = json_encode( $allListingFinalArr );
			update_option( 'cron_run', "" );
			update_option( 'cron_run', "$data" );

			$resultUsernameSetArr = explode( ',', $userNameList );
			$firstUserName        = isset( $resultUsernameSetArr[0] ) ? $resultUsernameSetArr[0] : '';
			$secondUserName       = isset( $resultUsernameSetArr[1] ) ? $resultUsernameSetArr[1] : '';
			$thirdUserName        = isset( $resultUsernameSetArr[2] ) ? $resultUsernameSetArr[2] : '';
			$fourthUserName       = isset( $resultUsernameSetArr[3] ) ? $resultUsernameSetArr[3] : '';
			$fifthUserName        = isset( $resultUsernameSetArr[4] ) ? $resultUsernameSetArr[4] : '';

			if ( $firstUserName != '' ) {
				$firstUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $firstUserName );
				update_option( 'firstUserNameresultSet', "" );
				update_option( 'firstUserNameresultSet', "$firstUserNameresultSet" );
			}
			if ( $secondUserName != '' ) {
				$secondUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $secondUserName );
				update_option( 'secondUserNameresultSet', "" );
				update_option( 'secondUserNameresultSet', "$secondUserNameresultSet" );
			}
			if ( $thirdUserName != '' ) {
				$thirdUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $thirdUserName );
				update_option( 'thirdUserNameresultSet', "" );
				update_option( 'thirdUserNameresultSet', "$thirdUserNameresultSet" );
			}
			if ( $fourthUserName != '' ) {
				$fourthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $fourthUserName );
				update_option( 'fourthUserNameresultSet', "" );
				update_option( 'fourthUserNameresultSet', "$fourthUserNameresultSet" );
			}
			if ( $fifthUserName != '' ) {
				$fifthUserNameresultSet = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $fifthUserName );
				update_option( 'fifthUserNameresultSet', "" );
				update_option( 'fifthUserNameresultSet', "$fifthUserNameresultSet" );
			}
		} else {
			$args         = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array  = (array) get_posts( $args );
			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = $exclusiveArr;
			$data               = json_encode( $allListingFinalArr );
			update_option( 'cron_run', "" );
			update_option( 'cron_run', "$data" );
		}
	}

	function aretkcrea_crea_agent_ids( $result_type ) {
		global $wpdb;
		$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$sql_select            = "SELECT `crea_agent_id` FROM `$crea_agent_table_name`";
		$sql_prep              = $wpdb->prepare( $sql_select, null );
		$getAllAgentIds        = $wpdb->get_results( $sql_prep, ARRAY_A );
		$crea_agent_ids        = array();
		if ( isset( $getAllAgentIds ) && ! empty( $getAllAgentIds ) ) {
			foreach ( $getAllAgentIds as $getAllAgentId ) {
				$crea_agent_ids[] = (int) $getAllAgentId['crea_agent_id'];
			}
		}
		switch ( $result_type ) {
			case 'list':
				$crea_agent_ids = implode( ',', $crea_agent_ids );
				break;
		}

		return $crea_agent_ids;
	}

	function aretkcrea_feed_usernames( $result_type ) {
		global $wpdb;
		$crea_user_name_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;
		$sql_select                = "SELECT `username` FROM `$crea_user_name_table_name`";
		$sql_prep                  = $wpdb->prepare( $sql_select, null );
		$getAllUsername            = $wpdb->get_results( $sql_prep );
		$crea_usernames            = array();
		if ( isset( $getAllUsername ) && ! empty( $getAllUsername ) ) {
			foreach ( $getAllUsername as $singleUsername ) {
				$crea_usernames[] = trim( $singleUsername->username );
			}
		}
		switch ( $result_type ) {
			case 'list':
				$crea_usernames = implode( ',', $crea_usernames );
				break;
		}

		return $crea_usernames;
	}

	public static function aretkcrea_get_listing_records_based_on_agents( $userName, $result_type, $agent_ids ) {
		global $wpdb;
		$user_ID            = get_current_user_id();
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
		$subscriptionKey    = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';
		$filter_results     = '';
		if ( ! empty( $agent_ids ) && $agent_ids != null ) {
			$filter_results .= '&agent_ids=' . $agent_ids;
		}
		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=listings&feed=$userName$filter_results&result_type=$result_type&limit=100&viewcount=true" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSet = json_decode( $data );
		$agent_ids = '';

		return $resultSet;
	}

	/**
	 * This function will return the array with the user data
	 *
	 * @param unknown_type $domainName
	 *
	 * @return array
	 * @since Phase 1
	 */
	public static function aretkcrea_get_user_listing_data_by_username( $userName, $user_ID = '' ) {
		global $wpdb;
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );

		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		/*if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}*/

		$ip = $_SERVER['REMOTE_ADDR'];
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}

		$curl_url = ARETKCREA_LISTING_BASEDONSERVER_API . "/?key={$getSubscriptionKey}&request=feeds&feed={$userName}";

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $curl_url );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );

		curl_close( $ch );

		return $data;
		#$resultSet = json_decode($data);
		#return $resultSet;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function aretkcrea_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Aretk_Crea_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Aretk_Crea_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'jquery.bxslider-css', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/jquery.bxslider.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name, esc_url( plugin_dir_url( __FILE__ ) ) . 'css/aretk-crea-admin.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'media-css', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/media.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jQuery-ui-style', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/jquery-ui.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jQuery-imageupload.js', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/imageupload.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery.dataTables', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/jquery.dataTables.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-choosen-css', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/chosen.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'jquery-simple-dtpicker-css', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/jquery.simple-dtpicker.css', array(), 'all' );
		wp_enqueue_style( 'lightcase-css', esc_url( plugin_dir_url( __FILE__ ) ) . 'css/lightcase.css', array(), $this->version, 'all' );
	}

	/**
	 * Register JavaScripts for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function aretkcrea_enqueue_scripts() {
		wp_enqueue_script( 'jquery-ui' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'jquery-ui-tabs' );
		wp_enqueue_script( 'jquery-ui-dialog' );
		wp_enqueue_script( 'jquery-ui-datepicker' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'jquery-ui-accordion' );
		$google_map_api                  = get_option( 'google-map-api-name' );
		$google_map_script_loaded_or_not = get_option( 'crea_google_map_script_load_or_not' );
		$google_map_api_key_pass         = '';
		if ( isset( $google_map_api ) && ! empty( $google_map_api ) ) {
			$google_map_api_results = $google_map_api;
			$google_map_api_key_pass .= "?key=$google_map_api_results";
		}
		wp_enqueue_script( 'jquery.dataTables.min', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( 'bxlsider-public', plugin_dir_url( __FILE__ ) . 'js/jquery.bxslider.js', array( 'jquery' ), $this->version );

		if ( 'Yes' === $google_map_script_loaded_or_not ) {
			wp_enqueue_script( 'google-map-js', "https://maps.googleapis.com/maps/api/js$google_map_api_key_pass", array( 'jquery' ) );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aretk-crea-admin.js', array(
				'jquery',
				'google-map-js'
			), $this->version, false );
		} else {
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/aretk-crea-admin.js', array( 'jquery' ), $this->version, false );
		}
		wp_enqueue_script( 'accordion-aretk', plugin_dir_url( __FILE__ ) . 'js/accordion.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'validate-new', plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js', array( 'jquery' ), $this->version );
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'create_new_leads' ) {
			wp_enqueue_script( 'lead-form', plugin_dir_url( __FILE__ ) . 'js/lead-form.js', array( 'jquery' ), false );
		}
		wp_localize_script( $this->plugin_name, 'cancelicon', array( 'cancelurl' => ARETK_CREA_PLUGIN_URL . 'admin/images/delete-icon.png' ) );
		wp_localize_script( $this->plugin_name, 'adminajaxjs', array( 'adminajaxjsurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_localize_script( $this->plugin_name, 'refreshicon', array( 'refreshurl' => ARETK_CREA_PLUGIN_URL . 'admin/images/refresh-animated.gif' ) );
		wp_localize_script( $this->plugin_name, 'ajaxicon', array( 'loderurl' => ARETK_CREA_PLUGIN_URL . 'admin/images/ajax-loader.gif' ) );
		wp_localize_script( $this->plugin_name, 'refreshimagejs', array( 'refreshimagejsurl' => ARETK_CREA_PLUGIN_URL . 'admin/js/imageupload.js' ) );
		wp_enqueue_script( 'mousewheel', plugin_dir_url( __FILE__ ) . 'js/jquery.mousewheel-3.0.6.pack.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'imageupload', plugin_dir_url( __FILE__ ) . 'js/imageupload.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'colorpicker1', plugin_dir_url( __FILE__ ) . 'js/jscolor.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'colorpicker2', plugin_dir_url( __FILE__ ) . 'js/jscolor.min.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery.dataTables', plugin_dir_url( __FILE__ ) . 'js/jquery.dataTables.min.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'choosen', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'jquery-simple-dtpicker-js', plugin_dir_url( __FILE__ ) . 'js/jquery.simple-dtpicker.js', array( 'jquery' ) );
		wp_enqueue_script( 'copytoclipboard', plugin_dir_url( __FILE__ ) . 'js/clipboard.min.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'lightcase', plugin_dir_url( __FILE__ ) . 'js/lightcase.js', array( 'jquery' ), $this->version );
		wp_enqueue_script( 'loader-js', plugin_dir_url( __FILE__ ) . 'js/modernizr.js', array( 'jquery' ), $this->version );
	}

	/**
	 * create aretk crea admin menu
	 *
	 */
	public function aretkcrea_crea_custom_menu() {
		$new_menu = null;
		if ( ! empty( $_GET['new_menu'] ) && $_GET['new_menu'] === 'true' ) {
			$new_menu = 'true';
		}
		if ( $new_menu !== 'true' ) {
			$aretk_plugin_slug = 'listings_settings';
			add_menu_page( 'crea-plugin', 'Real Estate', 'manage_options', $aretk_plugin_slug, 'aretkcrea_custom_listings_settings_function', ARETK_CREA_PLUGIN_URL . 'admin/images/icon-main-menu.png' );
			add_submenu_page( $aretk_plugin_slug, 'Listings', 'Listings', 'manage_options', $aretk_plugin_slug, 'aretkcrea_custom_listings_settings_function' );
			$listings_settings_form = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( $listings_settings_form === 'listings_settings' || $listings_settings_form === 'create_new_listings' ) {
				add_submenu_page( $aretk_plugin_slug, 'create-new-listing', ' - Add New Listing', 'manage_options', 'create_new_listings', 'aretkcrea_custom_create_new_listings_function' );
			}
			add_submenu_page( $aretk_plugin_slug, 'showcase-settings', 'Showcases', 'manage_options', 'showcase_settings', 'aretkcrea_custom_showcase_settings_function' );
			$showcase_form = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( $showcase_form === 'showcase_settings' || $showcase_form === 'create_new_showcase' || $showcase_form === 'listing_details_settings' || $showcase_form === 'search_listing_settings_showcase' || $showcase_form === 'default_listing_settings_showcase' ) {
				add_submenu_page( $aretk_plugin_slug, 'create-new-showcase', ' - Add New Showcase', 'manage_options', 'create_new_showcase', 'aretkcrea_custom_create_new_showcase_function' );
				add_submenu_page( 'null', 'default-listing-settings-showcase', 'DEFAULT LISTING SETTING SHOWCASE', 'manage_options', 'default_listing_settings_showcase', 'aretkcrea_default_listing_settings_fn' );
				add_submenu_page( 'null', 'search-listing-settings-showcase', 'SEARCH LISTING SETTING SHOWCASE', 'manage_options', 'search_listing_settings_showcase', 'aretkcrea_search_listing_settings_fn' );
				add_submenu_page( 'null', 'listing-details-settings', 'LISTING DETAILS SETTING', 'manage_options', 'listing_details_settings', 'aretkcrea_custom_listing_details_settings' );
			}

			add_submenu_page( $aretk_plugin_slug, 'leads-settings', 'Leads', 'manage_options', 'leads_settings', 'aretkcrea_custom_leads_settings_function' );

			//add submenu for LEADS Settings
			$lead_form                   = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
			$lead_form_page              = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			$lead_form_category_rexonomy = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';

			if ( $lead_form_page === 'leads_form' || $lead_form === 'aretk_lead' || $lead_form_page === 'leads_settings' || $lead_form_page === 'create_new_leads' || $lead_form_page === 'send_email_leads' || $lead_form_page === 'import_leads' || $lead_form_category_rexonomy === 'lead-category' || $lead_form_page === 'create_new_lead_category' ) {
				add_submenu_page( $aretk_plugin_slug, 'create-new-leads', ' - Add New Lead', 'manage_options', 'create_new_leads', 'aretkcrea_custom_create_new_leads_function' );
				add_submenu_page( $aretk_plugin_slug, 'send-email-leads', ' - Send Email', 'manage_options', 'send_email_leads', 'aretkcrea_custom_send_email_leads_function' );
				add_submenu_page( $aretk_plugin_slug, 'create-new-lead-category', ' - Lead Categories', 'manage_options', 'create_new_lead_category', 'aretkcrea_custom_create_new_lead_category_function' );
				add_submenu_page( $aretk_plugin_slug, 'leads-form', ' - Lead Forms', 'manage_options', 'leads_form', 'aretkcrea_custom_leads_form_function' );
				add_submenu_page( $aretk_plugin_slug, 'import-leads', ' - Import Leads', 'manage_options', 'import_leads', 'aretkcrea_custom_import_leads_function' );
			}
			add_submenu_page( $aretk_plugin_slug, 'crea-plugin', 'Datafeed Subscription', 'manage_options', 'crea-plugin', 'aretkcrea_custom_crea_plugins_function' );
			$getSubscriptionStatus = get_option( 'crea_subscription_status', true );
			if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				add_submenu_page( $aretk_plugin_slug, 'crea-settings', 'CREA DDF<sup>&reg;</sup> Settings', 'manage_options', 'crea_settings', 'aretkcrea_custom_crea_settings_function' );
			}
			add_submenu_page( $aretk_plugin_slug, 'plugin-settings', 'Plugin Settings', 'manage_options', 'plugin_settings', 'aretkcrea_custom_plugin_settings_function' );
			add_submenu_page( $aretk_plugin_slug, 'support-settings', 'Support', 'manage_options', 'support_settings', 'aretkcrea_custom_support_settings_function' );
		} else {
			//add admin main Crea plugins menu
			add_menu_page( 'crea-plugin', 'ARETK', 'manage_options', 'crea_plugins', 'aretkcrea_custom_crea_plugins_function', ARETK_CREA_PLUGIN_URL . 'admin/images/icon.png' );

			/**
			 * add admin sub menu in plugins
			 * crea_settings ,listings_settings,showcase_settings,leads_settings,support_settings ,plugin_settings
			 */
			$getSubscriptionStatus = get_option( 'crea_subscription_status', true );
			if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				add_submenu_page( 'crea_plugins', 'crea-settings', 'CREA DDF<sup>&reg;</sup> Settings', 'manage_options', 'crea_settings', 'aretkcrea_custom_crea_settings_function' );
			}
			add_submenu_page( 'crea_plugins', 'listings-settings', 'LISTINGS', 'manage_options', 'listings_settings', 'aretkcrea_custom_listings_settings_function' );
			//add submenu for Listings settings
			$listings_settings_form = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( isset( $listings_settings_form ) && ( $listings_settings_form === 'listings_settings' || $listings_settings_form === 'create_new_listings' ) ) {
				add_submenu_page( 'crea_plugins', 'create-new-listings', 'Add New Listing', 'manage_options', 'create_new_listings', 'aretkcrea_custom_create_new_listings_function' );
			}
			add_submenu_page( 'crea_plugins', 'showcase-settings', 'SHOWCASES', 'manage_options', 'showcase_settings', 'aretkcrea_custom_showcase_settings_function' );
			$showcase_form = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( isset( $showcase_form ) && ( $showcase_form === 'showcase_settings' || $showcase_form === 'create_new_showcase' || $showcase_form === 'listing_details_settings' || $showcase_form === 'search_listing_settings_showcase' || $showcase_form === 'default_listing_settings_showcase' ) ) {
				add_submenu_page( 'crea_plugins', 'create-new-showcase', 'ADD NEW SHOWCASE', 'manage_options', 'create_new_showcase', 'aretkcrea_custom_create_new_showcase_function' );
				add_submenu_page( 'null', 'listing-details-settings', 'LISTING DETAILS SETTING', 'manage_options', 'listing_details_settings', 'aretkcrea_custom_listing_details_settings' );
				add_submenu_page( 'null', 'search-listing-settings-showcase', 'SEARCH LISTING SETTING SHOWCASE', 'manage_options', 'search_listing_settings_showcase', 'aretkcrea_search_listing_settings_fn' );
				add_submenu_page( 'null', 'default-listing-settings-showcase', 'DEFAULT LISTING SETTING SHOWCASE', 'manage_options', 'default_listing_settings_showcase', 'aretkcrea_default_listing_settings_fn' );
			}
			add_submenu_page( 'crea_plugins', 'leads-settings', 'LEADS', 'manage_options', 'leads_settings', 'aretkcrea_custom_leads_settings_function' );

			//add submenu for LEADS Settings
			$lead_form                   = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : '';
			$lead_form_page              = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			$lead_form_category_rexonomy = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : '';
			if ( $lead_form_page === 'leads_form' || $lead_form === 'aretk_lead' || $lead_form_page === 'leads_settings' || $lead_form_page === 'create_new_leads' || $lead_form_page === 'send_email_leads' || $lead_form_page === 'import_leads' || $lead_form_category_rexonomy === 'lead-category' || $lead_form_page === 'create_new_lead_category' ) {
				add_submenu_page( 'crea_plugins', 'leads-form', 'LEADS forms', 'manage_options', 'leads_form', 'aretkcrea_custom_leads_form_function' );
				add_submenu_page( 'crea_plugins', 'create-new-leads', 'ADD New LEAD', 'manage_options', 'create_new_leads', 'aretkcrea_custom_create_new_leads_function' );
				add_submenu_page( 'crea_plugins', 'send-email-leads', 'SEND EMAIL', 'manage_options', 'send_email_leads', 'aretkcrea_custom_send_email_leads_function' );
				add_submenu_page( 'crea_plugins', 'import-leads', 'IMPORT LEADS', 'manage_options', 'import_leads', 'aretkcrea_custom_import_leads_function' );
				add_submenu_page( 'crea_plugins', 'create-new-lead-category', 'ADD NEW LEAD CATEGORY', 'manage_options', 'create_new_lead_category', 'aretkcrea_custom_create_new_lead_category_function' );
			}
			add_submenu_page( 'crea_plugins', 'plugin-settings', 'SETTINGS', 'manage_options', 'plugin_settings', 'aretkcrea_custom_plugin_settings_function' );
			add_submenu_page( 'crea_plugins', 'support-settings', 'SUPPORT', 'manage_options', 'support_settings', 'aretkcrea_custom_support_settings_function' );
		}

		/**
		 * create function for aretkcrea_custom_crea_plugins_function
		 */
		function aretkcrea_custom_crea_plugins_function() {
			esc_html( aretkcrea_custom_crea_plugin_html() );
		}

		/**
		 * create function for aretkcrea_custom_crea_settings_function
		 */
		function aretkcrea_custom_crea_settings_function() {
			esc_html( aretkcrea_custom_crea_settings_html() );
		}

		/**
		 * create function for aretkcrea_custom_listings_settings_function
		 */
		function aretkcrea_custom_listings_settings_function() {
			if ( isset($_GET['id']) && is_numeric( $_GET['id'] ) ) {
				esc_html( aretkcrea_custom_listings_settings_maplisting_html() );
			} else {
				esc_html( aretkcrea_custom_listings_settings_html() );
			}
		}

		function aretkcrea_custom_create_new_listings_function() {
			esc_html( aretkcrea_custom_create_listings_settings_html() );
		}

		/**
		 * create function for aretkcrea_custom_showcase_settings_function
		 */
		function aretkcrea_custom_showcase_settings_function() {
			esc_html( aretkcrea_custom_showcase_settings_html() );
		}

		/**
		 * create function for aretkcrea_custom_create_new_showcase_function
		 */
		function aretkcrea_custom_create_new_showcase_function() {
			esc_html( aretkcrea_custom_new_create_showcase_html() );
		}

		/**
		 * create function for aretkcrea_custom_leads_settings_function
		 */
		function aretkcrea_custom_leads_settings_function() {
			esc_html( aretkcrea_custom_leads_settings_html() );
		}

		/**
		 * create function for custom new lead category
		 */
		function aretkcrea_custom_create_new_lead_category_function() {
			esc_html( aretkcrea_custom_create_new_lead_category_html() );
		}

		/**
		 * create function for aretkcrea_custom_support_settings_function
		 */
		function aretkcrea_custom_support_settings_function() {
			esc_html( aretkcrea_custom_support_settings_html() );
		}

		/**
		 * create function for aretkcrea_search_listing_settings_fn
		 */
		function aretkcrea_search_listing_settings_fn() {
			esc_html( aretkcrea_search_listing_settings_html() );
		}

		/**
		 * create function for aretkcrea_default_listing_settings_fn
		 */
		function aretkcrea_default_listing_settings_fn() {
			esc_html( aretkcrea_default_listing_settings_html() );
		}

		/**
		 * create function for aretkcrea_custom_plugin_settings_function
		 */
		function aretkcrea_custom_plugin_settings_function() {
			esc_html( aretkcrea_custom_plugin_settings_html() );
		}

		/**
		 * create function for aretkcrea_custom_plugin_lead_form_function
		 */
		function aretkcrea_custom_leads_form_function() {
			esc_html( aretkcrea_custom_lead_form_listing() );
		}

		/**
		 * create function for aretkcrea_custom_create_new_leads_function
		 */
		function aretkcrea_custom_create_new_leads_function() {
			esc_html( aretkcrea_custom_create_new_leads_form() );
		}

		/**
		 * create function for aretkcrea_custom_send_email_leads_function
		 */
		function aretkcrea_custom_send_email_leads_function() {
			esc_html( aretkcrea_custom_send_email_leads_form() );
		}

		/**
		 * create function for aretkcrea_custom_listing_details_settings
		 */
		function aretkcrea_custom_listing_details_settings() {
			esc_html( aretkcrea_custom_showcase_listing_detail_settings() );
		}

		/**
		 * create function for aretkcrea_custom_import_leads_function
		 */
		function aretkcrea_custom_import_leads_function() {
			esc_html( aretkcrea_custom_import_leads_html() );
		}
	}

	/**
	 * Remove Custom post type Lead from Admin Menu
	 *
	 * @return Remove custom post type Lead
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_remove_custom_post_lead_from_admin_menu() {
		remove_menu_page( 'edit.php?post_type=aretk_lead' );
		remove_menu_page( 'edit.php?post_type=aretk_listing' );
		remove_menu_page( 'edit.php?post_type=aretk_showcase' );
	}

	/**
	 * Add/Remove appropriate CSS classes to Menu so Submenu displays open and the Menu link is styled appropriately.
	 *
	 * @return Remove custom post type Lead
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_current_menu() {

		$screen = get_current_screen();

		if ( $screen->id == 'edit-lead-category' ) {
			add_action( 'all_admin_notices', 'aretkcrea_action_all_admin_notices', 10, 2 );
			function aretkcrea_action_all_admin_notices() {
				$link_url_leads           = admin_url( 'edit.php?post_type=aretk_lead' );
				$link_url_create_new_lead = admin_url( 'admin.php?page=create_new_leads' );
				$link_url_send_email_lead = admin_url( 'admin.php?page=send_email_leads' );
				$link_url_lead_category   = admin_url( 'edit-tags.php?taxonomy=lead-category' );
				$link_url_lead_forms      = admin_url( 'admin.php?page=leads_form' );
				$link_url_import_leads    = admin_url( 'admin.php?page=import_leads' );
				echo '<div class="create_new_lead_category_button">
						<ul class="leadsbuttons subsubsub">
							<li class="leadforms"><a href="' . $link_url_leads . '" id="leads" class="button button-primary aretk-leads">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_LEADS, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>
							<li class="add-new-lead"><a href="' . $link_url_create_new_lead . '" id="add-new-lead" class="button button-primary aretk-add-new-lead">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>
							<li class="add-end-email"><a href="' . $link_url_send_email_lead . '" id="send-email" class="button button-primary aretk-add-new-lead">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>
							<li class="lead-category activeleadpage"><a href="' . $link_url_lead_category . '"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG ) ) . '"></a></li>
							<li class="leadforms"><a href="' . $link_url_lead_forms . '" id="lead-forms" class="button button-primary aretk-leadforms">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>
							<li class="import-lead"><a href="' . $link_url_import_leads . '" id="import-lead-csv" class="button button-primary aretk-import-lead">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>
							<li class="export-lead"><a href="#"><input type="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG ) ) . '"></a></li>
							<li class="download-lead"><div class="download-export-csv"></div></li>
						</ul>
					</div>';
			}
		}

		if ( $screen->id == 'edit-aretk_lead' ) {
			add_action( 'all_admin_notices', 'aretkcrea_action_lead_post_content_admin_notices', 10, 2 );
			function aretkcrea_action_lead_post_content_admin_notices() {
				echo '<div class="post_lead_content_notice"><p><strong>' . __( 'Manage all your Leads in one place!', 'aretk-crea' ) . '</strong></p></div>';
				echo '<div class="accordion lead_custom_footer">
				<div class="accordion-section lead_custom_footer_inner" id="lead_custom_footer">
					<a class="accordion-section-title" href="#crea-lead-header-admin-and-footer-content" >' . __( 'Features included in this Customer Management System', 'aretk-crea' ) . '</a>
					<div id="crea-lead-header-admin-and-footer-content" class="accordion-section-content open" style="display: none;">
						<div class=footer_post_content>						
						<p><strong>' . __( 'Capturing Leads and all Correspondence', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'This Leads Section will capture all leads submitted through one of the ARETK Lead Forms. Each Lead keeps track of ALL correspondense through the plugin (keeps copies of all inquiries made through the web form as well as all email sent out through the back end of the plugin). Notes can be kept for each lead to remind you of details with that particular person.', 'aretk-crea' ) . '</p>						
						<p><strong>' . __( '3 Real Estate Lead Forms', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'ARETK comes with 3 Real Estate Forms. Click on Lead forms to see the 3 Form`s available, and copy and paste the shortcodes into any page on your WordPress web site.', 'aretk-crea' ) . '</p>						
						<p><strong>' . __( 'Manually Enter Leads', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'You can manually add leads, contact information, personal notes about the lead.', 'aretk-crea' ) . '</p>			
						<p><strong>' . __( 'Import/Export Leads', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'You can import and export your contacts in CVS format.', 'aretk-crea' ) . '</p>						
						<p><strong>' . __( 'Lead Categorization', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'Each lead can be categories into as many categories as you wish (create the categories first by clicking Add New Lead Category). You can then tailor your correspondence with the leads that have the same interests. Eg. Lead Category (WaterFront Properties or Condo`s).', 'aretk-crea' ) . '</p>						
						<p><strong>' . __( 'Send Group Emails', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'Goup emails can be sent to as few or as many of the Leads or by Lead Category. Each email sent out will get captured in that individuals lead correspondence history.', 'aretk-crea' ) . '</p>						
						<p><strong>' . __( 'Lead Reminders', 'aretk-crea' ) . '</strong></p>
						<p>' . __( 'You can set up as many lead reminders and have them re-occuring to remind yourself at a certain date/time to follow up with a lead to ensure nothing gets forgotten.', 'aretk-crea' ) . '</p>						
						</div>						
					</div><!--end .accordion-section-content-->
				</div><!--end .accordion-section-->
				</div>';
			}
		}
	}

	/**
	 * function for remove post count tab in lead category
	 *
	 * @param get lead category table column $columns
	 *
	 * @return return category column list
	 */
	function aretkcrea_remove_lead_category_post_count( $columns ) {
		//unset category post count
		unset( $columns['posts'] );

		//return category column
		return $columns;
	}

	/**
	 * Remove Custom Post type feature like Quick edit, view etc
	 *
	 * @return $actions
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_post_row_actions_custom( $actions, $post ) {
		global $current_screen;
		if ( $current_screen->post_type == 'aretk_lead' ) {
			unset( $actions['view'] );
			unset( $actions['inline hide-if-no-js'] );
		} elseif ( $post->post_title == "Advance Search" || $post->post_title == "Listings Detail" ) {
			unset( $actions['edit'] );
			unset( $actions['view'] );
			unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );
		}

		return $actions;
	}

	/**
	 * Register Custom post type Listing
	 *
	 * @return return showcase custom post type
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_register_custom_post_type_listing() {
		register_post_type( 'aretk_listing',
			array(
				'labels'        => array(
					'name'               => __( 'Listing', ARETKCREA_PLUGIN_SLUG ),
					'singular_name'      => __( 'Listing', ARETKCREA_PLUGIN_SLUG ),
					'add_new'            => __( 'Add New', ARETKCREA_PLUGIN_SLUG ),
					'add_new_item'       => __( 'Add New Listing', ARETKCREA_PLUGIN_SLUG ),
					'edit'               => __( 'Edit', ARETKCREA_PLUGIN_SLUG ),
					'edit_item'          => __( 'Edit Listing', ARETKCREA_PLUGIN_SLUG ),
					'new_item'           => __( 'New Listing', ARETKCREA_PLUGIN_SLUG ),
					'view'               => __( 'View', ARETKCREA_PLUGIN_SLUG ),
					'view_item'          => __( 'View Listing', ARETKCREA_PLUGIN_SLUG ),
					'search_items'       => __( 'Search Listing', ARETKCREA_PLUGIN_SLUG ),
					'not_found'          => __( 'No Listing found', ARETKCREA_PLUGIN_SLUG ),
					'not_found_in_trash' => __( 'No Listing found in Trash', ARETKCREA_PLUGIN_SLUG ),
					'parent'             => __( 'Parent Listing', ARETKCREA_PLUGIN_SLUG )
				),
				'public'        => true,
				'menu_position' => 15,
				'supports'      => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
				'taxonomies'    => array( '' ),
				//'menu_icon' => plugins_url( 'images/image.png', __FILE__ ),
				'has_archive'   => true
			)
		);
	}

	/**
	 * Register Listing taxonomy
	 *
	 * @return return custom taxonomy for showcase
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_register_listing_taxonomy() {
		$labels = array(
			'name'              => __( 'Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'singular_name'     => __( 'Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'search_items'      => __( 'Search Listings Category', ARETKCREA_PLUGIN_SLUG ),
			'all_items'         => __( 'All Listings Category', ARETKCREA_PLUGIN_SLUG ),
			'parent_item'       => __( 'Parent Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'parent_item_colon' => __( 'Parent Listing Category:', ARETKCREA_PLUGIN_SLUG ),
			'edit_item'         => __( 'Edit Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'update_item'       => __( 'Update Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'add_new_item'      => __( 'Add New Listing Category', ARETKCREA_PLUGIN_SLUG ),
			'new_item_name'     => __( 'New Listing Name Category', ARETKCREA_PLUGIN_SLUG ),
			'menu_name'         => __( 'Listing Category', ARETKCREA_PLUGIN_SLUG ),
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'listing' ),
		);
		register_taxonomy( 'listing', array( 'aretk_listing' ), $args );
	}

	/**
	 * Register Custom post type Showcase
	 *
	 * @return return showcase custom post type
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 *
	 * @param null
	 */
	public function aretkcrea_register_custom_post_type_showcase() {
		register_post_type( 'aretk_showcase',
			array(
				'labels'        => array(
					'name'               => __( 'Showcase', ARETKCREA_PLUGIN_SLUG ),
					'singular_name'      => __( 'Showcase', ARETKCREA_PLUGIN_SLUG ),
					'add_new'            => __( 'Add New', ARETKCREA_PLUGIN_SLUG ),
					'add_new_item'       => __( 'Add New Showcase', ARETKCREA_PLUGIN_SLUG ),
					'edit'               => __( 'Edit', ARETKCREA_PLUGIN_SLUG ),
					'edit_item'          => __( 'Edit Showcase', ARETKCREA_PLUGIN_SLUG ),
					'new_item'           => __( 'New Showcase', ARETKCREA_PLUGIN_SLUG ),
					'view'               => __( 'View', ARETKCREA_PLUGIN_SLUG ),
					'view_item'          => __( 'View Showcase', ARETKCREA_PLUGIN_SLUG ),
					'search_items'       => __( 'Search Showcases', ARETKCREA_PLUGIN_SLUG ),
					'not_found'          => __( 'No Showcase found', ARETKCREA_PLUGIN_SLUG ),
					'not_found_in_trash' => __( 'No Showcase found in Trash', ARETKCREA_PLUGIN_SLUG ),
					'parent'             => __( 'Parent Showcase', ARETKCREA_PLUGIN_SLUG )
				),
				'public'        => true,
				'menu_position' => 15,
				'supports'      => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
				'taxonomies'    => array( '' ),
				'has_archive'   => true
			)
		);
	}

	/**
	 * Register Listing showcase
	 *
	 * @return return custom taxonomy for showcase
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 * @author Aretk Inc.
	 *
	 * @param null
	 */
	public function aretkcrea_register_listing_showcase_taxonomy() {
		$labels = array(
			'name'              => __( 'Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'singular_name'     => __( 'Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'search_items'      => __( 'Search Listing showcases', ARETKCREA_PLUGIN_SLUG ),
			'all_items'         => __( 'All Listing showcases', ARETKCREA_PLUGIN_SLUG ),
			'parent_item'       => __( 'Parent Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'parent_item_colon' => __( 'Parent Listing showcase:', ARETKCREA_PLUGIN_SLUG ),
			'edit_item'         => __( 'Edit Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'update_item'       => __( 'Update Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'add_new_item'      => __( 'Add New Listing showcase', ARETKCREA_PLUGIN_SLUG ),
			'new_item_name'     => __( 'New Listing showcase Name', ARETKCREA_PLUGIN_SLUG ),
			'menu_name'         => __( 'Listing showcase', ARETKCREA_PLUGIN_SLUG ),
		);
		$args   = array(
			'hierarchical'      => true,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'listing-showcase' ),
		);
		register_taxonomy( 'listing-showcase', array( 'aretk_showcase' ), $args );
	}

	public function aretkcrea_create_listing_showcase_category() {
		$terms = get_terms( 'listing-showcase', array( 'hide_empty' => false ) );
		if ( empty( $terms ) ) {
			wp_insert_term(
				'Listing Details Showcase',
				'listing-showcase',
				array(
					'slug' => 'listing-details-showcase'
				)
			);
		}
	}

	/**
	 * Register Custom post type Lead
	 *
	 * @return return custom post type lead
	 * @package Phase 1
	 * @package Phase 1
	 * @since Phase 1
	 * @version
	 * @author Aretk Inc.
	 *
	 * @param null
	 */
	public function aretkcrea_register_custom_post_type_leads() {
		register_post_type( 'aretk_lead',
			array(
				'labels'        => array(
					'name'          => __( 'LEADS', ARETKCREA_PLUGIN_SLUG ),
					'singular_name' => __( 'LEADS', ARETKCREA_PLUGIN_SLUG ),
					'edit'          => __( 'Edit', ARETKCREA_PLUGIN_SLUG ),
					'edit_item'     => __( 'Edit Lead', ARETKCREA_PLUGIN_SLUG ),
					'search_items'  => __( 'Search Lead', ARETKCREA_PLUGIN_SLUG ),
					'not_found'     => __( 'No Lead found', ARETKCREA_PLUGIN_SLUG ),
					'parent'        => __( 'Parent Lead', ARETKCREA_PLUGIN_SLUG ),
					'capabilities'  => array(
						'create_posts' => true,
					)
				),
				'public'        => true,
				'menu_position' => 15,
				'hierarchical'  => true,
				'query_var'     => true,
				'taxonomies'    => array( '' ),
				'supports'      => array( 'title', 'custom-fields' ),
				'has_archive'   => true
			)
		);
	}

	function aretkcrea_register_create_new_lead_taxonomy() {
		$labels = array(
			'name'              => __( 'LEAD CATEGORIES', ARETKCREA_PLUGIN_SLUG ),
			'singular_name'     => __( 'LEAD CATEGORY', ARETKCREA_PLUGIN_SLUG ),
			'search_items'      => __( 'Search Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'all_items'         => __( 'All Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'parent_item'       => __( 'Parent Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'parent_item_colon' => __( 'Parent Lead Category:', ARETKCREA_PLUGIN_SLUG ),
			'edit_item'         => __( 'Edit Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'update_item'       => __( 'Update Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'add_new_item'      => __( 'Add Lead Category', ARETKCREA_PLUGIN_SLUG ),
			'new_item_name'     => __( 'New Lead Category Name', ARETKCREA_PLUGIN_SLUG ),
			'menu_name'         => __( 'Create New Lead Category', ARETKCREA_PLUGIN_SLUG ),
		);
		$args   = array(
			'hierarchical'      => false,
			'labels'            => $labels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'lead-category' ),
		);
		register_taxonomy( 'lead-category', array( 'aretk_lead' ), $args );
	}

	/**
	 * This function used for the ajax request to send lead email
	 *
	 * @param null
	 *
	 * @return null
	 * @since Phase 1
	 */
	public function aretkcrea_lead_email_send() {
		global $wpdb;

		$admin_email_address = get_option( 'admin_email' );

		$send_email_subject = isset( $_POST['send_email_subject'] ) ? stripslashes( sanitize_text_field( $_POST['send_email_subject'] ) ) : '';

		# Sanitize send_email_to, can be a comma delimited list of emails
		$send_email_to = isset( $_POST['send_email_to'] ) ? $_POST['send_email_to'] : '';
		if ( ! empty( $send_email_to ) ) {
			$send_email_to = str_replace( ' ', '', $send_email_to );
			$email_arr     = explode( ',', $send_email_to );
			$send_email_to = null;
			foreach ( $email_arr as $email ) {
				$send_email_to .= sanitize_email( $email ) . ',';
			}
			$send_email_to = rtrim( $send_email_to, ',' );
		}

		# Sanitize send_email_cc, can be a comma delimited list of emails
		$send_email_cc = isset( $_POST['send_email_cc'] ) ? $_POST['send_email_cc'] : '';
		if ( ! empty( $send_email_cc ) ) {
			$send_email_cc = str_replace( ' ', '', $send_email_cc );
			$email_arr     = explode( ',', $send_email_cc );
			$send_email_cc = null;
			foreach ( $email_arr as $email ) {
				$send_email_cc .= sanitize_email( $email ) . ',';
			}
			$send_email_cc = rtrim( $send_email_cc, ',' );
		}

		# Sanitize send_email_bcc, can be a comma delimited list of emails
		$send_email_bcc = isset( $_POST['send_email_bcc'] ) ? $_POST['send_email_bcc'] : '';
		if ( ! empty( $send_email_bcc ) ) {
			$send_email_bcc = str_replace( ' ', '', $send_email_bcc );
			$email_arr      = explode( ',', $send_email_bcc );
			$send_email_bcc = null;
			foreach ( $email_arr as $email ) {
				$send_email_bcc .= sanitize_email( $email ) . ',';
			}
			$send_email_bcc = rtrim( $send_email_bcc, ',' );
		}

		# Sanitize send_email_lead_id, integer
		if ( isset( $_POST['send_email_lead_id'] ) && $_POST['send_email_lead_id'] !== 'undefined' && is_numeric( $_POST['send_email_lead_id'] ) ) {
			$send_email_lead_id = (INT) $_POST['send_email_lead_id'];
		} else {
			$send_email_lead_id = null;
		}

		# Sanitize textarea field which contains HTML,
		$send_email_text = isset( $_POST['send_email_text'] ) ? ( $_POST['send_email_text'] ) : '';
		if ( ! empty( $send_email_text ) ) {
			$allowed_html    = Aretk_Crea_Admin::aretkcrea_allowed_html();
			$send_email_text = wp_kses( $send_email_text, $allowed_html );
			$send_email_text = stripslashes( $send_email_text );
			/*$send_email_text = str_replace( '"', '\"', $send_email_text );*/
			$send_email_text = json_encode( $send_email_text );
			$send_email_text = str_replace( '\r\n', '', $send_email_text );
			$send_email_text = str_replace( '\n', '', $send_email_text );
			$send_email_text = json_decode( $send_email_text );
		}

		#-------------------
		# Sanitize Attachments Upload
		$attachement_arr = array();

		# Only allow the following doc types
		$allowedDocsMimes = array(
			'jpg|jpeg|jpe'                             => 'image/jpeg',
			'jpg'                                      => 'image/jpeg',
			'gif'                                      => 'image/gif',
			'png'                                      => 'image/png',
			'bmp'                                      => 'image/bmp',
			'tif|tiff'                                 => 'image/tiff',
			'ico'                                      => 'image/x-icon',
			'txt|asc|c|cc|h'                           => 'text/plain',
			'csv'                                      => 'text/csv',
			'tsv'                                      => 'text/tab-separated-values',
			'rtx'                                      => 'text/richtext',
			'mp3|m4a|m4b'                              => 'audio/mpeg',
			'mp4|m4v'                                  => 'video/mp4',
			'mov|qt'                                   => 'video/quicktime',
			'pdf'                                      => 'application/pdf',
			'doc|docx'                                 => 'application/msword',
			'tar'                                      => 'application/x-tar',
			'zip'                                      => 'application/zip',
			'gz|gzip'                                  => 'application/x-gzip',
			'mp3|m4a|m4b'                              => 'audio/mpeg',
			'mpeg|mpg|mpe'                             => 'video/mpeg',
			'mp4|m4v'                                  => 'video/mp4',
			'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel'
		);

		$sendLeadDocumentArr = isset( $_FILES['file'] ) ? $_FILES['file'] : array();

		if ( /*! empty( $sendLeadDocumentArr ) &&*/ current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			$uploadfile = array(
				'name'     => sanitize_file_name( $sendLeadDocumentArr['name'] ),
				'type'     => $sendLeadDocumentArr['type'],
				'tmp_name' => $sendLeadDocumentArr['tmp_name'],
				'error'    => $sendLeadDocumentArr['error'],
				'size'     => $sendLeadDocumentArr['size']
			);

			/*$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedDocsMimes );*/

			if ( /*! empty( $fileInfo['type'] ) &&*/ $uploadfile['size'] > 0 && $uploadfile['size'] < 5000000 ) {
				$uploadInfo = wp_handle_upload( $uploadfile, array(
					'test_form' => false,
					'mimes'     => $allowedDocsMimes
				) );

				if ( isset( $uploadInfo['file'] ) && ! isset( $uploadInfo['error'] ) ) {
					$attachement_arr[] = $uploadInfo['file'];
				}
			}
		}
		# Attachments Upload END
		#-------------------

		if ( empty( $send_email_to ) ) {
			$send_email_to = $admin_email_address;
		}
		$Mesaage = '<div style="width:100%;"><div style="background: ' . esc_url( ARETKCREA_MAIL_CONTENT_COLOR ) . ';padding: 15px;"><p style="margin: 0px;padding:0px;margin-bottom: 15px;"></p><p style="margin: 0px;padding:0px;">' . str_replace( '\"', '"', $send_email_text ) . '</p></div></div><br />';
		$headers = "From: " . esc_html( get_bloginfo( 'name' ) ) . " <" . esc_html( $admin_email_address ) . "> \r\n";
		$headers .= "bcc: " . $send_email_bcc . "\r\n";
		if ( ! empty( $send_email_cc ) && $send_email_cc != "" ) {
			$headers .= "cc: " . $send_email_cc . "\r\n";
		}
		$headers .= 'MIME-Version: 1.0' . "\n";
		$headers .= 'content-type: text/html; charset=utf-8' . "\r\n";
		$mailResponse = wp_mail( $send_email_to, $send_email_subject, $Mesaage, $headers, $attachement_arr );

		# Mail sent, remove attachments as they are no longer needed
		if ( ! empty( $attachement_arr ) ) {
			foreach ( $attachement_arr as $attachement ) {
				if ( file_exists( $attachement ) && wp_is_writable( $attachement ) ) {
					if ( false === unlink( $attachement ) ) {
						echo __( 'Caught exception: could not remove document, check file permissions', 'aretk-crea' ) . "\n";
						$fileDeleteError = true;
					}
				}
			}
		}

		$bulk_lead_ids    = get_option( 'crea_bulk_email_lead_ids' );
		$explode_lead_ids = json_decode( $bulk_lead_ids );

		if ( empty( $explode_lead_ids ) && isset( $send_email_lead_id ) && is_numeric( $send_email_lead_id ) ) {
			$explode_lead_ids = array( $send_email_lead_id => $send_email_lead_id );
		}

		$current_date = date_i18n( 'Y-m-d H:i' );
		if ( $explode_lead_ids != '' && ! empty( $explode_lead_ids ) ) {
			foreach ( $explode_lead_ids as $explode_lead_ids_key => $explode_lead_ids_value ) {
				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = '';
				$new_corrsponding_array[] = esc_textarea( str_replace( '\\', '\\\\', $send_email_text ) );
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = str_replace( '"', '\"', $send_email_subject );
				$new_corrsponding_array[] = 'emailed';
				update_post_meta( (int) $explode_lead_ids_key, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );
			}
		}
		update_option( 'crea_bulk_email_lead_ids', '' );
		update_option( 'selected_lead_post_email_to_bcc', '' );

		if ( $explode_lead_ids != '' && ! empty( $explode_lead_ids ) && empty( $send_email_lead_id ) && $mailResponse === true ) {
			echo 'sent-leads';
			//$link_url = admin_url('edit.php?post_type=aretk_lead');
			//wp_safe_redirect($link_url);
		} elseif ( ! empty( $send_email_lead_id ) && is_numeric( $send_email_lead_id ) && $mailResponse === true ) {
			echo 'sent-lead';
			//$link_url = admin_url('admin.php?page=create_new_leads&ID='.$send_email_lead_id.'&action=edit');
			//wp_safe_redirect($link_url);
		} elseif ( $mailResponse === true ) {
			echo 'sent-mail';
		} else {
			echo "false";
		}
		die();
	}

	public function aretkcrea_allowed_html() {
		$allowed_tags = array(
			'a'          => array(
				'class' => array(),
				'href'  => array(),
				'rel'   => array(),
				'title' => array(),
			),
			'abbr'       => array(
				'title' => array(),
			),
			'b'          => array(),
			'blockquote' => array(
				'cite' => array(),
			),
			'cite'       => array(
				'title' => array(),
			),
			'code'       => array(),
			'del'        => array(
				'datetime' => array(),
				'title'    => array(),
			),
			'dd'         => array(),
			'div'        => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'dl'         => array(),
			'dt'         => array(),
			'em'         => array(),
			'h1'         => array(),
			'h2'         => array(),
			'h3'         => array(),
			'h4'         => array(),
			'h5'         => array(),
			'h6'         => array(),
			'i'          => array(),
			'img'        => array(
				'alt'    => array(),
				'class'  => array(),
				'height' => array(),
				'src'    => array(),
				'width'  => array(),
			),
			'li'         => array(
				'class' => array(),
			),
			'ol'         => array(
				'class' => array(),
			),
			'p'          => array(
				'class' => array(),
			),
			'q'          => array(
				'cite'  => array(),
				'title' => array(),
			),
			'span'       => array(
				'class' => array(),
				'title' => array(),
				'style' => array(),
			),
			'strike'     => array(),
			'strong'     => array(),
			'ul'         => array(
				'class' => array(),
			),
		);

		return $allowed_tags;
	}

	/**
	 * create function for aretkcrea_new_import_lead_user
	 *
	 * ajax callback function aretkcrea_new_import_lead_user
	 *
	 * @return return crea add import lead
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */

	function aretkcrea_new_import_lead_user() {
		global $wpdb;

		$listingDocumentArr = isset( $_FILES['crea_import_lead'] ) ? $_FILES['crea_import_lead'] : array();

		if ( ! empty( $listingDocumentArr ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;

			$allowedDocsMimes = array(
				'csv' => 'text/csv'
			);

			$uploadfile = array(
				'name'     => sanitize_file_name( $listingDocumentArr['name'] ),
				'type'     => $listingDocumentArr['type'],
				'tmp_name' => $listingDocumentArr['tmp_name'],
				'error'    => $listingDocumentArr['error'],
				'size'     => $listingDocumentArr['size']
			);

			/*$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedDocsMimes );*/

			if ( /*! empty( $fileInfo['type'] ) &&*/ $uploadfile['size'] > 0 && $uploadfile['size'] < 5000000 ) {
				$uploadInfo = wp_handle_upload( $uploadfile, array(
					'test_form' => false,
					'mimes'     => $allowedDocsMimes
				) );

				if ( ! empty( $uploadInfo['file'] ) && ! isset( $uploadInfo['error'] ) && $uploadInfo['type'] === 'text/csv' ) {
					$import_lead_csv = fopen( $uploadInfo['file'], 'r' );

					if ( false !== $import_lead_csv ) {
						$merge_email_array_duplicate = array();

						$post_table      = $wpdb->prefix . 'posts';
						$post_meta_table = $wpdb->prefix . 'postmeta';

						$sql_select             = "SELECT * FROM `$post_meta_table` WHERE `meta_key`='lead_primary_email'";
						$sql_prep               = $wpdb->prepare( $sql_select, null );
						$getAgentidResultsarray = $wpdb->get_results( $sql_prep );

						$sql_select                = "SELECT * FROM `$post_meta_table` WHERE `meta_key`= 'lead_phone_email'";
						$sql_prep                  = $wpdb->prepare( $sql_select, null );
						$getAgentemailResultsarray = $wpdb->get_results( $sql_prep );

						$add_primary_email_array = array();
						foreach ( $getAgentidResultsarray as $getAgentidResultskey => $getAgentidResultsvalue ) {
							if ( $getAgentidResultsvalue->meta_value != '' ) {
								$add_primary_email_array [ $getAgentidResultsvalue->post_id ] = $getAgentidResultsvalue->meta_value;
							}
						}
						$add_non_primary_email_array = array();
						foreach ( $getAgentemailResultsarray as $getAgentemailResultkey => $getAgentemailResultsvalue ) {
							$implode_array     = maybe_unserialize( $getAgentemailResultsvalue->meta_value );
							$unserialize_array = maybe_unserialize( $implode_array );
							$lead_phone_email  = '';
							if ( is_array( $unserialize_array ) ) {
								$lead_phone_email = $unserialize_array[0];
							} else {
								$lead_phone_email = $unserialize_array;
							}
							$add_non_primary_email_array[ $getAgentemailResultsvalue->post_id ] = $lead_phone_email;
						}
						$merge_email_array_duplicate = $add_primary_email_array + $add_non_primary_email_array;

						while ( ( $import_lead_csv_get_data = fgetcsv( $import_lead_csv ) ) !== false ) {
							$existing_import_lead_email    = '';
							$existing_import_lead_phone_no = '';
							$existing_import_lead_comment  = '';

							$import_lead_username = sanitize_text_field( $import_lead_csv_get_data[0] );
							$import_lead_email    = sanitize_email( $import_lead_csv_get_data[1] );
							$import_lead_phone_no = preg_replace( '/[^0-9]/', '', $import_lead_csv_get_data[2] );
							$import_lead_comment  = sanitize_text_field( $import_lead_csv_get_data[3] );

							if ( ! empty( $import_lead_username ) && $import_lead_username != 'Name' ) {
								$existing_import_lead_username = $import_lead_username;
							}
							if ( ! empty( $import_lead_email ) && $import_lead_email != 'Email Address' && $import_lead_email != 'Email' ) {
								$existing_import_lead_email = $import_lead_email;
							}
							if ( ! empty( $import_lead_phone_no ) && $import_lead_phone_no != 'Phone Number' && $import_lead_phone_no != 'Phone' ) {
								$existing_import_lead_phone_no = $import_lead_phone_no;
							}
							if ( ! empty( $import_lead_phone_no ) && $import_lead_comment != 'Comment' && $import_lead_comment != 'Comments' ) {
								$existing_import_lead_comment = $import_lead_comment;
							}

							if ( ! empty( $existing_import_lead_username ) && ! empty( $existing_import_lead_email ) ) {
								if ( ! in_array( $existing_import_lead_email, $merge_email_array_duplicate ) ) {
									$new_lead            = array(
										'post_title'   => $existing_import_lead_username,
										'post_content' => $existing_import_lead_comment,
										'post_status'  => 'publish',
										'post_type'    => 'aretk_lead'
									);
									$import_lead_post_id = wp_insert_post( $new_lead );
									update_post_meta( $import_lead_post_id, 'lead_primary_email', $existing_import_lead_email );
									update_post_meta( $import_lead_post_id, 'lead_phone_email', $existing_import_lead_email );
									update_post_meta( $import_lead_post_id, 'lead_phone_no', $existing_import_lead_phone_no );
								} else {
									echo "duplicate found, skipping\n" . PHP_EOL;
								}
							}
						}
					}
					if ( file_exists( $uploadInfo['file'] ) && wp_is_writable( $uploadInfo['file'] ) ) {
						if ( false === unlink( $uploadInfo['file'] ) ) {
							echo __( 'Caught exception: could not remove document, check file permissions', 'aretk-crea' ) . "\n";
							$fileDeleteError = true;
						}
					}
				}
			}
		}
		die();
	}

	/**
	 * This function will use for the ajax request to check key is valid or not.
	 *
	 * @param null
	 *
	 * @return not-valid / valid
	 * @since Phase 1
	 */
	public function aretkcrea_check_subscription_key_valid_ajax() {
		$pieces          = parse_url( sanitize_text_field( base64_decode( $_REQUEST['domain'] ) ) );
		$domainName      = isset( $pieces['host'] ) ? filter_var( $pieces['host'], FILTER_SANITIZE_URL ) : null;
		$subscriptionKey = isset( $_POST['subscriptionKey'] ) ? preg_replace( "/[^a-z0-9-]+/i", "", base64_decode( $_POST['subscriptionKey'] ) ) : null;
		if ( empty( $domainName ) || empty( $subscriptionKey ) ) {
			die( 'not-valid' );
		}
		echo Aretk_Crea_Admin::aretkcrea_check_subscription_key_valid_api( $subscriptionKey, $domainName );
		die();
	}

	/**
	 * This function will return the array with the subscription is valid or not
	 *
	 * @param unknown_type $subscriptionKey
	 * @param unknown_type $domainName
	 *
	 * @return array
	 * @since Phase 1
	 */
	public static function aretkcrea_check_subscription_key_valid_api( $subscriptionKey, $domainName ) {
		global $wpdb;

		$subscriptionKey = preg_replace( "/[^a-z0-9-]+/i", "", $subscriptionKey );
		$domainName      = filter_var( $domainName, FILTER_SANITIZE_URL );

		if ( empty( $subscriptionKey ) || empty( $domainName ) ) {
			return 'not-valid';
		}

		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_SUBSCRIPTIONENDPOINT . "/?api-key=$subscriptionKey&domain_name=$domainName" );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSetAPI = json_decode( $data );

		if ( isset( $resultSetAPI ) && ! empty( $resultSetAPI ) ) {
			if ( ($resultSetAPI->code === 'success' && $resultSetAPI->subscription_status === 'Active') ) {
				update_option( 'crea_subscription_status', 'valid' );
				update_option( 'crea_subscription_key', "$subscriptionKey" );

				# Check to see if any exclusive listings need to be updated to ARETK
				$post_meta_table           = $wpdb->prefix . "postmeta";
				$sql_select                = "SELECT `post_id` FROM `$post_meta_table` WHERE `meta_key`='crea_listing_subscription_states' AND `meta_value`='not-valid'";
				$sql_prep                  = $wpdb->prepare( $sql_select, null );
				$InactiveStoredDataResults = $wpdb->get_results( $sql_prep );

				$InactiveStoredDataResults_PostId = array();
				foreach ( $InactiveStoredDataResults as $InactiveStoredDataResults_display ) {
					$postId                             = (int) $InactiveStoredDataResults_display->post_id;
					$InactiveStoredDataResults_PostId[] = $postId;
					$get_option                         = json_decode( get_option( 'crea_subscription_active_stored_Id' ) );
					$aretk_listing_id                   = get_post_meta( $postId, 'aretk_server_listing_id', true );
					$action                             = 'add';
					if ( in_array( $postId, $get_option ) && ! empty( $aretk_listing_id ) ) {
						$action = 'edit';
					}

					if ( $action === 'add' || $action === 'edit' ) {
						$listingAgentID             = get_post_meta( $postId, 'listingAgentId', true );
						$listingAddress             = get_post_meta( $postId, 'listingAddress', true );
						$listingcity                = get_post_meta( $postId, 'listingcity', true );
						$listingProvince            = get_post_meta( $postId, 'listingProvince', true );
						$listingMls            		= get_post_meta( $postId, 'listingMls', true );
						$listingAgentStatus         = get_post_meta( $postId, 'listingAgentStatus', true );
						$listingPrice               = get_post_meta( $postId, 'listingPrice', true );
						$listingPropertyType        = get_post_meta( $postId, 'listingPropertyType', true );
						$listingStructureType       = get_post_meta( $postId, 'listingStructureType', true );
						$listingBedRooms            = get_post_meta( $postId, 'listingBedRooms', true );
						$listingBathrooms           = get_post_meta( $postId, 'listingBathrooms', true );
						$listingBathroomsPartial    = get_post_meta( $postId, 'listingBathroomsPartial', true );
						$listingFinishedBasement    = get_post_meta( $postId, 'listingFinishedBasement', true );
						$listingFeatureArr          = get_post_meta( $postId, 'listingFeatureArr', true );
						$listingParkinggarage       = get_post_meta( $postId, 'listingParkinggarage', true );
						$listingParkingSlot         = get_post_meta( $postId, 'listingParkingSlot', true );
						$listingTourUrl             = get_post_meta( $postId, 'listingTourUrl', true );
						$listingUtilityArr          = get_post_meta( $postId, 'listingUtilityArr', true );
						$listingopenhosedatetimeArr = get_post_meta( $postId, 'listingopenhosedatetimeArr', true );
						$listingGoogleMapLatitude   = get_post_meta( $postId, 'crea_google_map_latitude', true );
						$listingGoogleMapLongitude  = get_post_meta( $postId, 'crea_google_map_longitude', true );
						$listing_full_address_path  = '';
						if ( ! empty( $listingAddress ) ) {
							$listing_full_address_path .= sanitize_title( $listingAddress );
						}
						if ( ! empty( $listingcity ) ) {
							$listing_full_address_path .= '-' . sanitize_title( $listingcity );
						}
						if ( ! empty( $listingProvince ) ) {
							$listing_full_address_path .= '-' . sanitize_title( $listingProvince );
						}
						$content_post                = get_post( $postId );
						$content                     = $content_post->post_content;
						$listing_public_remarks      = $content;
						$agentFeaturesDecodeArrValue = '';

						// get agent IDs
						$agentsIDArr     = array();
						$agentsDecodeArr = json_decode( $listingAgentID );
						if ( $agentsDecodeArr != '' && ! empty( $agentsDecodeArr ) ) {
							$agent_id_counter = 1;
							foreach ( $agentsDecodeArr as $agentsDecodekey => $agentsDecodeArrValue ) {
								$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
								if ( ! empty( $agentsDecodeArrValue ) ) {
									$agentsIDArr[] = array(
										"sequence_id" => $agent_id_counter,
										"agent_id"    => $agentsDecodeArrValue
									);
								}
								$agent_id_counter = $agent_id_counter + 1;
							}
						}
						// get agent features
						$agentFeaturesArr       = array();
						$agentFeaturesFinaleArr = '';
						$agentFeaturesDecodeArr = json_decode( $listingFeatureArr );
						if ( $agentFeaturesDecodeArr != '' && ! empty( $agentFeaturesDecodeArr ) ) {
							foreach ( $agentFeaturesDecodeArr as $agentFeaturesDecodeArrValue ) {
								$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
								if ( ! empty( $agentFeaturesDecodeArrValue ) ) {
									$agentFeaturesArr[] = trim( $agentFeaturesDecodeArrValue );
								}
							}
							$agentFeaturesFinaleArr = implode( ",", $agentFeaturesArr );
						}
						// get agents utilities
						$agentUtilitiesArr       = array();
						$agentUtilitiesDecodeArr = json_decode( $listingUtilityArr );
						if ( $agentUtilitiesDecodeArr != '' && ! empty( $agentUtilitiesDecodeArr ) ) {
							$utitlity_counter = 1;
							foreach ( $agentUtilitiesDecodeArr as $agentUtilitiesDecodeArrValue ) {
								$agentUtilitiesDecodeArrValue = trim( $agentUtilitiesDecodeArrValue );
								if ( ! empty( $agentUtilitiesDecodeArrValue ) ) {
									$agentUtilitiesArr[] = array(
										"sequence_id" => $utitlity_counter,
										"type"        => $agentUtilitiesDecodeArrValue
									);
								}
								$utitlity_counter = $utitlity_counter + 1;
							}
						}
						// get agent images
						$agentPhotoArr     = array();
						$photoGelleryTable = $wpdb->prefix . 'crea_listing_images_detail';
						$sql_select        = "SELECT * FROM `$photoGelleryTable` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
						$sql_prep          = $wpdb->prepare( $sql_select, $postId );
						$photoResultsArr   = $wpdb->get_results( $sql_prep );
						if ( $photoResultsArr != '' && ! empty( $photoResultsArr ) ) {
							$photo_counter = 1;
							foreach ( $photoResultsArr as $photoResultsArrValue ) {
								$agentPhotoArr[] = array(
									"sequence_id" => $photo_counter,
									"url"         => $photoResultsArrValue->image_url
								);
								$photo_counter   = $photo_counter + 1;
							}
						}
						// get agent externaldocument
						$agentDocumentArr      = array();
						$agentDocumentFinalArr = '';
						$agentDocumentTable    = $wpdb->prefix . 'crea_listing_document_detail';
						$sql_select            = "SELECT * FROM `$agentDocumentTable` WHERE `unique_id`= %d ORDER BY `id` ASC";
						$sql_prep              = $wpdb->prepare( $sql_select, $postId );
						$agentDocumentResults  = $wpdb->get_results( $sql_prep );

						if ( $agentDocumentResults != '' && ! empty( $agentDocumentResults ) ) {
							foreach ( $agentDocumentResults as $agentDocumentResultsValue ) {
								$agentDocumentArr[] = $agentDocumentResultsValue->document_url;
							}
							$agentDocumentFinalArr = implode( ",", $agentDocumentArr );
						}
						// agent Open House date and time
						$agentOpenHouseArr       = array();
						$agentOpenHouseDecodeArr = json_decode( $listingopenhosedatetimeArr );
						if ( $agentOpenHouseDecodeArr != '' && ! empty( $agentOpenHouseDecodeArr ) ) {
							$openHouse_Counter = 1;
							foreach ( $agentOpenHouseDecodeArr as $agentOpenHouseDecodeArrKey => $agentOpenHouseDecodeArrValue ) {
								$startDate = strtotime( $agentOpenHouseDecodeArrValue->date );
								$startTime = $agentOpenHouseDecodeArrValue->start_time;
								$endTime   = $agentOpenHouseDecodeArrValue->end_time;

								$agentStartDate = date( 'm/d/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $startTime ) );
								$agentEndDate   = date( 'm/d/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $endTime ) );

								$agentFormatStartDate = $agentStartDate;
								$agentFormatEndDate   = $agentEndDate;

								if ( $agentFormatStartDate != "01/01/1970 12:00:00 AM" && $agentFormatEndDate != "01/01/1970 12:00:00 AM" ) {
									$agentOpenHouseArr[] = array(
										"sequence_id" => $openHouse_Counter,
										"start_date"  => $agentFormatStartDate,
										"end_date"    => $agentFormatEndDate
									);
								} else {
									$agentOpenHouseArr = "";
								}
								$openHouse_Counter = $openHouse_Counter + 1;
							}
						}
						if ( $postId != '' ) {
							$add_listing_settinges_array = array(
								"street_address"       => $listingAddress,
								"city"                 => $listingcity,
								"province"             => $listingProvince,
								'mlsID' => $listingMls,
								"transaction_type"     => $listingAgentStatus,
								"price"                => $listingPrice,
								"property_type"        => $listingPropertyType,
								"structure"            => $listingStructureType,
								"bedrooms_total"       => $listingBedRooms,
								"bathroom_total"       => $listingBathrooms,
								"halfbath_total"       => $listingBathroomsPartial,
								"basement_type"        => $listingFinishedBasement,
								"public_remarks"       => $listing_public_remarks,
								"features"             => $agentFeaturesFinaleArr,
								"garage"               => $listingParkinggarage,
								"no_of_parking_spot"   => $listingParkingSlot,
								"moreInformation_link" => $listingTourUrl,
								"utilities"            => $agentUtilitiesArr,
								"photo"                => $agentPhotoArr,
								"external_document"    => $agentDocumentFinalArr,
								"open_house"           => $agentOpenHouseArr,
								"generated_address"    => $listing_full_address_path,
								"geocoded_latitude"    => $listingGoogleMapLatitude,
								"geocoded_longitude"   => $listingGoogleMapLongitude,
							);
							$post_string                 = http_build_query( $add_listing_settinges_array );
							$exclusive_property_id       = array();
							$exclusive_property_id[]     = (int) $postId;

							if ( $action === 'add' ) {
								$addListing = curl_init();
								curl_setopt( $addListing, CURLOPT_HEADER, 0 );
								curl_setopt( $addListing, CURLOPT_VERBOSE, 0 );
								curl_setopt( $addListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=insert_listing" );
								curl_setopt( $addListing, CURLOPT_POST, true );
								curl_setopt( $addListing, CURLOPT_POSTFIELDS, $post_string );
								curl_setopt( $addListing, CURLOPT_RETURNTRANSFER, true );
								curl_setopt( $addListing, CURLOPT_REFERER, $domainName );
								$addListingCurlExecute = curl_exec( $addListing );
								curl_close( $addListing );
								$addListingCurlExecuteResponse = ( $addListingCurlExecute ) . PHP_EOL;
								$responseDecode                = json_decode( $addListingCurlExecuteResponse );
								if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
									if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
										if ( isset( $responseDecode->data->insert_id ) ) {
											update_post_meta( $postId, 'aretk_server_listing_id', (int) $responseDecode->data->insert_id );
										}
									} else {
										$exclusive_old_property_id_array = array();
										$mergerd_property_id_array       = array();
										$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
										if ( ! empty( $exclusive_stored_add_id_result ) ) {
											$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
										}
										$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
										$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
										$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
										update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
									}
								} else {
									$exclusive_old_property_id_array = array();
									$mergerd_property_id_array       = array();
									$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
									if ( ! empty( $exclusive_stored_add_id_result ) ) {
										$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
									}
									$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
									$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
									$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
									update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
								}

							} else if ( $action === 'edit' ) {
								$last_aretk_server_insert_id  = (int) get_post_meta( $postId, 'aretk_server_listing_id', true );
								$edit_listing_settinges_array = array();
								if ( ! empty( $last_aretk_server_insert_id ) ) {
									$edit_listing_settinges_array = array(
										"id"                   => $last_aretk_server_insert_id,
										"agent_id"             => $agentsIDArr,
										"street_address"       => $listingAddress,
										"city"                 => $listingcity,
										"province"             => $listingProvince,
										"mlsID" => $listingMls,
										"transaction_type"     => $listingAgentStatus,
										"price"                => $listingPrice,
										"property_type"        => $listingPropertyType,
										"structure"            => $listingStructureType,
										"bedrooms_total"       => $listingBedRooms,
										"bathroom_total"       => $listingBathrooms,
										"halfbath_total"       => $listingBathroomsPartial,
										"basement_type"        => $listingFinishedBasement,
										"public_remarks"       => $listing_public_remarks,
										"features"             => $agentFeaturesFinaleArr,
										"garage"               => $listingParkinggarage,
										"no_of_parking_spot"   => $listingParkingSlot,
										"moreInformation_link" => $listingTourUrl,
										"utilities"            => $agentUtilitiesArr,
										"photo"                => $agentPhotoArr,
										"external_document"    => $agentDocumentFinalArr,
										"open_house"           => $agentOpenHouseArr,
										"generated_address"    => $listing_full_address_path,
										"geocoded_latitude"    => $listingGoogleMapLatitude,
										"geocoded_longitude"   => $listingGoogleMapLongitude,
									);
									$post_string                  = http_build_query( $edit_listing_settinges_array );
									$editListing                  = curl_init();
									curl_setopt( $editListing, CURLOPT_HEADER, 0 );
									curl_setopt( $editListing, CURLOPT_VERBOSE, 0 );
									curl_setopt( $editListing, CURLOPT_RETURNTRANSFER, true );
									curl_setopt( $editListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=edit_listing" );
									curl_setopt( $editListing, CURLOPT_POST, true );
									curl_setopt( $editListing, CURLOPT_POSTFIELDS, $post_string );
									curl_setopt( $editListing, CURLOPT_REFERER, $domainName );
									$editListingCurlExecute = curl_exec( $editListing );
									curl_close( $editListing );
									$editListingCurlExecuteResponse = ( $editListingCurlExecute ) . PHP_EOL;
									$responseDecode                 = json_decode( $editListingCurlExecuteResponse );
									if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
										if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
											if ( isset( $responseDecode->data->updated_id ) ) {
												update_post_meta( $postId, 'aretk_server_listing_id', (int) $responseDecode->data->updated_id );
											}
										}
									}
								}
							}
						}
					}
				} # End foreach
				$InActive_record_real_results = json_encode( $InactiveStoredDataResults_PostId );
				update_option( "crea_subscription_active_stored_Id", $InActive_record_real_results );

				//Delete Exclusive property from server
				$exclusive_deleted_id_result = get_option( "exclusive_deleted_ids" );

				if ( ! empty( $exclusive_deleted_id_result ) && $exclusive_deleted_id_result != 'null' ) {
					$exclusive_old_property_id_array = json_decode( $exclusive_deleted_id_result );
					if ( isset( $exclusive_old_property_id_array ) && ! empty( $exclusive_old_property_id_array ) ) {
						foreach ( $exclusive_old_property_id_array as $exclusive_old_property_id ) {
							$delete_listing = array(
								"id" => (int) $exclusive_old_property_id
							);
							$post_string    = http_build_query( $delete_listing );
							$deleteListing  = curl_init();
							curl_setopt( $deleteListing, CURLOPT_HEADER, 0 );
							curl_setopt( $deleteListing, CURLOPT_VERBOSE, 0 );
							curl_setopt( $deleteListing, CURLOPT_RETURNTRANSFER, true );
							curl_setopt( $deleteListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=delete_listing" );
							curl_setopt( $deleteListing, CURLOPT_POST, true );
							curl_setopt( $deleteListing, CURLOPT_POSTFIELDS, $post_string );
							curl_setopt( $deleteListing, CURLOPT_REFERER, $domainName );
							$deleteListingCurlExecute = curl_exec( $deleteListing );
							curl_close( $deleteListing );
						}
						$deleted_array = array();
						$deleted_array = json_encode( $deleted_array );
						update_option( "exclusive_deleted_ids", $deleted_array );
					}
				}

				return 'valid';
			} else {
				update_option( 'crea_subscription_status', 'not-valid' );
				update_option( 'crea_subscription_key', "" );

				return 'not-valid';
			}
		} else {
			update_option( 'crea_subscription_status', 'not-valid' );
			update_option( 'crea_subscription_key', "" );

			return 'not-valid';
		}
	}

	/**
	 * This function will use for the ajax request to store the data of plugin settings tab
	 *
	 * @param null
	 *
	 * @return null
	 * @since Phase 1
	 */
	public function aretkcrea_save_plugin_settings_tab_data_ajax() {
		$googleMapApiKey          = sanitize_text_field( base64_decode( $_REQUEST['googleMapApiKey'] ) );
		$googleCaptchaKey_public  = sanitize_text_field( base64_decode( $_REQUEST['googleCaptchaKey_public'] ) );
		$googleCaptchaKey_private = sanitize_text_field( base64_decode( $_REQUEST['googleCaptchaKey_private'] ) );
		$walkScoreApiKey          = sanitize_text_field( base64_decode( $_REQUEST['walkScoreApiKey'] ) );
		$googlemapscriptloadornot = (INT) base64_decode( $_REQUEST['googlemapscriptloadornot'] );
		if ( $googlemapscriptloadornot == 1 ) {
			$googlemapscriptloadornot_results = 'Yes';
		} else {
			$googlemapscriptloadornot_results = 'No';
		}
		update_option( 'crea_google_map_script_load_or_not', $googlemapscriptloadornot_results );
		$googleMapApiKey_results         = str_replace( ' ', '', $googleMapApiKey );
		$googleCaptchaKey_public_result  = str_replace( ' ', '', $googleCaptchaKey_public );
		$googleCaptchaKey_private_result = str_replace( ' ', '', $googleCaptchaKey_private );
		$walkScoreApiKey_results         = str_replace( ' ', '', $walkScoreApiKey );
		update_option( 'walk-score-api-name', "$walkScoreApiKey" );
		update_option( 'google-map-api-name', "$googleMapApiKey" );
		update_option( 'aretk_googleCaptchaKey_public', "$googleCaptchaKey_public_result" );
		update_option( 'aretk_googleCaptchaKey_private', "$googleCaptchaKey_private_result" );
		echo ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_SUCESS;
		die();
	}

	// This function is no longer being used but keeping it here for now in case we decide to re-use it in future.

	/**
	 * This function will use for the ajax request to display updated data of particular username crea settings tab
	 *
	 * @param null
	 *
	 * @return null
	 * @since Phase 1
	 */
	public function aretkcrea_new_aretkcrea_fetch_total_records_of_username_ajax() {

		global $wpdb;

		$crea_user_listing_detail_table_name = $wpdb->prefix . ARETKCREA_USER_LISTING_DETAILS;

		$ddfType_options = array( 'My Listings', 'Office Listings', 'Board Listings', 'National Pool' );

		$db_feed_arr  = array();
		$api_feed_arr = array();

		$usernameAndDdfTypeArr = ! empty( $_REQUEST['usernameAndDdfTypeArr'] ) ? $_REQUEST['usernameAndDdfTypeArr'] : '';

		if ( ! empty( $usernameAndDdfTypeArr ) && is_array( $usernameAndDdfTypeArr ) ) {
			$sql = "TRUNCATE TABLE $crea_user_listing_detail_table_name";
			$wpdb->query( $sql );

			$i_count = 1;
			foreach ( $usernameAndDdfTypeArr as $singleUser ) {
				$Username = sanitize_text_field( $singleUser[0] );
				$ddfType  = sanitize_text_field( $singleUser[1] );

				if ( empty( $Username ) || empty( $ddfType ) ) {
					continue;
				}

				if ( ! in_array( $ddfType, $ddfType_options ) ) {
					continue;
				}

				if ( ! empty( $Username ) ) {
					try {
						$wpdb->insert( "$crea_user_listing_detail_table_name",
							array(
								'user_id'      => $i_count,
								'username'     => "$Username",
								'ddf_type'     => "$ddfType",
								'created_time' => current_time( 'mysql', 1 ),
								'updated_time' => current_time( 'mysql', 1 )
							),
							array( '%d', '%s', '%s', '%s', '%s' )
						);
					} catch ( Exception $e ) {
						echo 'Caught exception: ', $e->getMessage(), "\n";
					}

					$db_feed_arr[ $i_count ] = $Username;
				}

				$i_count ++;
			}

			$UsernamesDB = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'list' );

			$totalRecords_aretk    = 0;
			$recordsReturned_aretk = 0;

			if ( ! empty( $UsernamesDB ) ) {
				$resultSet             = Aretk_Crea_Admin::aretkcrea_get_user_listing_data_by_username( $UsernamesDB );
				$resultSet             = json_decode( $resultSet );
				$totalRecords_aretk    = $resultSet[0]->TotalRecords;
				$recordsReturned_aretk = $resultSet[0]->RecordsReturned;
			}

			if ( $totalRecords_aretk > 0 && $totalRecords_aretk === $recordsReturned_aretk ) {
				$feeds_arr = array();
				for ( $x = 1; $x <= $recordsReturned_aretk + 1; $x ++ ) {
					$feed_LastUpdated = '';

					$feed_name          = $resultSet[ $x ]->Feed;
					$api_feed_arr[ $x ] = $feed_name;

					$key_db = array_search( $feed_name, $db_feed_arr );

					if ( $key_db !== false && $key_db !== null ) {
						switch ( $key_db ) {
							case 1:
								$optionName = 'firstUserNameresultSet';
								break;
							case 2:
								$optionName = 'secondUserNameresultSet';
								break;
							case 3:
								$optionName = 'thirdUserNameresultSet';
								break;
							case 4:
								$optionName = 'fourthUserNameresultSet';
								break;
							case 5:
								$optionName = 'fifthUserNameresultSet';
								break;
						}
						$key_api      = array_search( $feed_name, $api_feed_arr );
						$optionRecord = '[{"TotalRecords":1,"RecordsReturned":1},' . json_encode( $resultSet[ $key_api ] ) . ']';

						update_option( $optionName, "" );
						update_option( $optionName, $optionRecord );
					}
				}
			}
		}

		return 'success';
		die();
	}

	public function aretkcrea_listing_filter_based_on_agent_or_mlsid() {
		global $wpdb;

		$mlsId                 = sanitize_text_field( $_POST['mlsId'] );
		$agentName             = sanitize_text_field( $_POST['agentName'] );
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
		$tempArr               = array();
		$resultArr             = array();
		$getAllListingData     = get_option( 'cron_run' );
		if ( isset( $getAllListingData ) && ! empty( $getAllListingData ) && isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
			$allListingFinalArr = json_decode( $getAllListingData );
		} else {

			if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				$allListingArr = array();
				$agent_ids     = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'list' );
				$userNameList  = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'list' );
				if ( ! empty( $userNameList ) && ! empty( $agent_ids ) ) {
					$result_type = 'full';
					$listings    = Aretk_Crea_Admin::aretkcrea_get_listing_records_based_on_agents( $userNameList, $result_type, $agent_ids );
					if ( isset( $listings ) && ! empty( $listings ) ) {
						foreach ( $listings as $listing_key => $listing ) {
							if ( ! isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) {
								$allListingArr[ $listing->mlsID ] = $listing;
							}
						}
					}
				}

				$args        = array(
					'posts_per_page' => - 1,
					'post_type'      => 'aretk_listing',
					'post_status'    => 'publish'
				);
				$posts_array = (array) get_posts( $args );

				$exclusiveArr = array();
				foreach ( $posts_array as $singlePost ) {
					$singlePost1    = (array) $singlePost;
					$singlePost2    = (object) $singlePost1;
					$exclusiveArr[] = $singlePost2;
				}
				$allListingFinalArr = array_merge( $allListingArr, $exclusiveArr );
			} else {
				$args        = array(
					'posts_per_page' => - 1,
					'post_type'      => 'aretk_listing',
					'post_status'    => 'publish'
				);
				$posts_array = (array) get_posts( $args );

				$exclusiveArr = array();
				foreach ( $posts_array as $singlePost ) {
					$singlePost1    = (array) $singlePost;
					$singlePost2    = (object) $singlePost1;
					$exclusiveArr[] = $singlePost2;
				}
				$allListingFinalArr = $exclusiveArr;
			}
		}
		$tempMlsId = array();
		if ( $agentName != '' && $mlsId != '' ) {
			$temp = 0;
			foreach ( $allListingFinalArr as $singleListing ) {
				$listing_mlsID = isset( $singleListing->mlsID ) ? $singleListing->mlsID : '';
				if ( $listing_mlsID == $mlsId ) {
					foreach ( $singleListing->listing_agents as $agent ) {
						if ( $agent->ID == $agentName ) {
							$tempArr[]   = $singleListing;
							$tempMlsId[] = $singleListing->mlsID;
							break;
						}
					}
				}
			}
		} elseif ( $agentName == '' && $mlsId != '' ) {
			$temp = 0;
			foreach ( $allListingFinalArr as $singleListing ) {
				if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
					continue;
				} else {
					if ( $singleListing->mlsID == $mlsId ) {
						if ( $temp == 0 ) {
							$tempArr[] = $singleListing;
							$temp      = 1;
						}
					}
				}
			}
		} elseif ( $agentName != '' && $mlsId == '' ) {
			$temp = 0;
			foreach ( $allListingFinalArr as $singleListing ) {
				if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
					$agentArrDecoded = get_post_meta( $singleListing->ID, 'listingAgentId', true );
					$agentArr        = json_decode( $agentArrDecoded );
					if ( isset( $agentArr ) && ! empty( $agentArr ) ) {
						if ( in_array( $agentName, $agentArr ) ) {
							$tempArr[] = $singleListing;
						} else {
							continue;
						}
					} else {
						continue;
					}
				} else {
					foreach ( $singleListing->listing_agents as $agent ) {
						if ( $agent->ID == $agentName ) {
							$tempArr[] = $singleListing;
							break;
						}
					}
				}
			}
		} elseif ( $agentName == '' && $mlsId == '' ) {
			if ( (string) $_POST['mlsId'] ) {
				$tempArr = array();
			} else {
				$tempArr = $allListingFinalArr;
			}
		}

		$resultArr = array_merge( $resultArr, $tempArr );
		$html      = '';
		$html .= '<table class="display" id="crea_setting_listting_content" cellpadding="0" cellspacing="0">
			<thead>
				<tr>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG ) . '</th>
					<th>' . __( ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG ) . '</th>
				</tr>
			</thead>
			<tbody>';
		foreach ( $resultArr as $singleListing ) {

			if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
				$ListingAddress = get_post_meta( $singleListing->ID, 'listingAddress', true );
				$ListingCity    = get_post_meta( $singleListing->ID, 'listingcity', true );
				$ListingPrice   = get_post_meta( $singleListing->ID, 'listingPrice', true );
				$date           = date( 'd-m-Y', strtotime( $singleListing->post_date ) );

				$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
				$sql_select                            = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `image_position`=1 AND `unique_id`= %d";
				$sql_prep                              = $wpdb->prepare( $sql_select, $singleListing->ID );
				$resultSet                             = $wpdb->get_results( $sql_prep );

				if ( isset( $resultSet ) && ! empty( $resultSet ) ) {
					$path = $resultSet[0]->image_url;
				} else {
					$path = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
				}
				$agentArrDecoded  = get_post_meta( $singleListing->ID, 'listingAgentId', true );
				$listingpageval   = get_post_meta( $singleListing->ID, 'crea_aretk_db_listing_page_count', true );
				$listingpagecount = 0;
				if ( ! empty( $listingpageval ) && $listingpageval != '' ) {
					$listingpagecount = $listingpageval;
				} else {
					$listingpagecount = 0;
				}
				$agentArr = json_decode( $agentArrDecoded );
				if ( isset( $agentArr ) && ! empty( $agentArr ) ) {
					$htmlAgent             = '';
					$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
					foreach ( $agentArr as $singleAgent ) {
						$sql_select     = "SELECT `crea_agent_name` FROM `$crea_agent_table_name` WHERE `crea_agent_id`= %s";
						$sql_prep       = $wpdb->prepare( $sql_select, $singleAgent );
						$resultAgentArr = $wpdb->get_results( $sql_prep );
						if ( isset( $resultAgentArr ) && ! empty( $resultAgentArr ) ) {
							$htmlAgent .= $resultAgentArr[0]->crea_agent_name . ', ';
						}
					}
				}
				$mls_number = isset($ListingMls) && !empty($ListingMls) ? $ListingMls : 'Exclusive';
				$link_url = admin_url( 'admin.php?page=create_new_listings&id=' . $singleListing->ID );
				$html .= '<tr>
						<td><img style="height:100px;width:100px;" src="' . esc_url( $path ) . '"></td>
						<td>'.$mls_number.'<br><a href="' . esc_url( $link_url ) . '">' . __( 'Edit', 'aretk-crea' ) . '</a> | <a id="' . $singleListing->ID . '" class="trash_listing" href="javascript:void(0);">Trash</a></td>
						<td>' . wp_kses_post( $ListingAddress ) . '</td>
						<td>' . esc_html( $ListingCity ) . '</td>
						<td>$' . esc_html( $ListingPrice ) . '</td>
						<td>' . rtrim( $htmlAgent, ', ' ) . '</td>
						<td>' . esc_html( $listingpagecount ) . '</td>
						<td>' . wp_kses_post( $date ) . '</td>
					</tr>';
			} else {
				$htmlAgent = '';
				foreach ( $singleListing->listing_agents as $singleAgent ) {
					$htmlAgent .= $singleAgent->Name . ', ';
				}
				if ( is_object( $singleListing->listing_photos ) ) {
					if ( $singleListing->listing_photos->URL == '' || $singleListing->listing_photos->URL == null ) {
						$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
					} else {
						$apiListingImageURL = $singleListing->listing_photos->URL;
					}
				} else if ( is_object( $singleListing->listing_photos[0] ) ) {
					if ( $singleListing->listing_photos[0]->URL == '' || $singleListing->listing_photos[0]->URL == null ) {
						$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
					} else {
						$apiListingImageURL = $singleListing->listing_photos[0]->URL;
					}
				} else {
					$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
				}
				$mlsId            = isset( $singleListing->mlsID ) ? $singleListing->mlsID : '-';
				$dates            = isset( $singleListing->ListingContractDate ) ? $singleListing->ListingContractDate : '-';
				$listingpagecount = isset( $singleListing->ViewCount ) ? $singleListing->ViewCount : 0;
				$link_url         = admin_url( 'admin.php?page=listings_settings&id=' . $singleListing->ID );
				$html .= '<tr>
						<td><img style="height:100px;width:100px;" src="' . esc_url( $apiListingImageURL ) . '"></td>
						<td>' . esc_html( $mlsId ) . '<br />
						<a href="' . esc_url( $link_url ) . '">' . __( "Map It", ARETKCREA_PLUGIN_SLUG ) . '</a></td>
						<td>' . wp_kses_post( $singleListing->StreetAddress ) . ' ' . wp_kses_post( $singleListing->StreetNumber ) . '</td>
						<td>' . esc_html( $singleListing->City ) . '</td>
						<td>$' . esc_html( $singleListing->Price ) . '</td>
						<td>' . rtrim( $htmlAgent, ', ' ) . '</td>
						<td>' . esc_html( $listingpagecount ) . '</td>
						<td>' . wp_kses_post( $dates ) . '</td>
					</tr>';
			}
		}
		$html .= '</tbody>
					<tfoot>
						<tr>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG ) . '</th>
						</tr>
					</tfoot>
			</table>';
		echo $html;
		die();
	}

	/**
	 * This function handles the submit for the create listing form data
	 *
	 * @param null
	 *
	 * @return null
	 * @since Phase 1
	 */
	public function aretkcrea_handle_create_listing_form_action() {
		global $wpdb, $wp;

		#-------------------
		# Sanitize Data:

		# Sanitize $_POST['posttype'], can only equal 'list'
		$posttype = null;
		if ( ! empty( $_POST['posttype'] ) && $_POST['posttype'] === 'list' ) {
			$posttype = 'list';
		}

		# Sanitize $_POST['action'], can only equal 'submit-form'
		$action = null;
		if ( ! empty( $_POST['action'] ) && $_POST['action'] === 'submit-form' ) {
			$action = 'submit-form';
		}

		# Sanitize $_POST['action-which'], Can only be 'add' or 'edit'
		$action_which = null;
		if ( ! empty( $_POST['action-which'] ) && $_POST['action-which'] === 'add' ) {
			$action_which = 'add';
		} elseif ( ! empty( $_POST['action-which'] ) && $_POST['action-which'] === 'edit' ) {
			$action_which = 'edit';
		}

		# Sanitize $_POST['listing_view_agent_id'], can be array of integers
		$listingAgentIdArray = isset( $_POST['listing_view_agent_id'] ) ? array_filter( $_POST['listing_view_agent_id'], 'ctype_digit' ) : array();
		$listingAgentIds     = array();
		if ( ! empty( $listingAgentIdArray ) && $listingAgentIdArray != '' ) {
			foreach ( $listingAgentIdArray as $listingAgentIdArraykey ) {
				if ( $listingAgentIdArraykey != '' ) {
					$listingAgentIds[] = (INT) $listingAgentIdArraykey;
				}
			}
		}
		$listingAgentId = json_encode( $listingAgentIds );

		$listingAddress = isset( $_POST['agent_listing_tab_address'] ) ? sanitize_text_field( ltrim( rtrim( $_POST['agent_listing_tab_address'], " " ), " " ) ) : '';

		$listingcity = isset( $_POST['agent_listing_tab_city'] ) ? sanitize_text_field( ltrim( rtrim( $_POST['agent_listing_tab_city'], " " ), " " ) ) : '';

		$listingProvince = isset( $_POST['agent_listing_tab_Province'] ) ? sanitize_text_field( ltrim( rtrim( $_POST['agent_listing_tab_Province'], " " ), " " ) ) : '';
		
		$listingMls = isset( $_POST['agent_listing_tab_mls'] ) ? sanitize_text_field( ltrim( rtrim( $_POST['agent_listing_tab_mls'], " " ), " " ) ) : '';

		$listingAgentStatus = isset( $_POST['listing_view_agent_status'] ) ? sanitize_text_field( $_POST['listing_view_agent_status'] ) : '';

		$listingPrice = isset( $_POST['agent_listing_tab_price'] ) ? sanitize_text_field( ltrim( rtrim( $_POST['agent_listing_tab_price'], " " ), " " ) ) : '';

		$listingPropertyType = isset( $_POST['listing-view-agent-property-type'] ) ? sanitize_text_field( $_POST['listing-view-agent-property-type'] ) : '';

		$listingStructureType = isset( $_POST['listing-view-agent-structure-type'] ) ? sanitize_text_field( $_POST['listing-view-agent-structure-type'] ) : '';

		$listingBedRooms = isset( $_POST['listing-view-agent-bedrooms'] ) ? sanitize_text_field( $_POST['listing-view-agent-bedrooms'] ) : '';

		$listingBathrooms = isset( $_POST['listing-view-agent-bathrooms-full'] ) ? sanitize_text_field( $_POST['listing-view-agent-bathrooms-full'] ) : '';

		$listingBathroomsPartial = isset( $_POST['listing-view-agent-bathrooms-partial'] ) ? sanitize_text_field( $_POST['listing-view-agent-bathrooms-partial'] ) : '';

		$listingFinishedBasement = isset( $_POST['listing-view-agent-finished-basement'] ) ? sanitize_text_field( $_POST['listing-view-agent-finished-basement'] ) : '';

		$listingDescription = isset( $_POST['listing-view-agent-descriptions'] ) ? sanitize_text_field( $_POST['listing-view-agent-descriptions'] ) : '';

		$crea_google_map_latitude = isset( $_POST['crea_google_map_latitude'] ) ? sanitize_text_field( $_POST['crea_google_map_latitude'] ) : '';

		$crea_google_map_longitude = isset( $_POST['crea_google_map_longitude'] ) ? sanitize_text_field( $_POST['crea_google_map_longitude'] ) : '';

		$crea_google_map_geo_address = isset( $_POST['crea_google_map_geo_address'] ) ? sanitize_text_field( $_POST['crea_google_map_geo_address'] ) : '';

		$listingParkingSlot = isset( $_POST['listing-view-pa-ga-parking-slot'] ) ? sanitize_text_field( $_POST['listing-view-pa-ga-parking-slot'] ) : '';

		$listingParkinggarage = isset( $_POST['listing-view-pa-ga-garage'] ) ? sanitize_text_field( $_POST['listing-view-pa-ga-garage'] ) : '';

		$listingTourUrl = isset( $_POST['listing_virtual_tor_add_url'] ) ? esc_url( ltrim( rtrim( $_POST['listing_virtual_tor_add_url'], " " ), " " ) ) : '';

		// Sanitize Utilities
		$listingAgentUtilityarray = array();
		$listingUtilityArray      = isset( $_POST['crea-utilities-input'] ) ? $_POST['crea-utilities-input'] : array();
		if ( ! empty( $listingUtilityArray ) && $listingUtilityArray != '' ) {
			foreach ( $listingUtilityArray as $listingUtilityArray_results ) {
				if ( $listingUtilityArray_results != '' ) {
					$listingAgentUtilityarray[] = sanitize_text_field( ltrim( rtrim( $listingUtilityArray_results, " " ), " " ) );
				}
			}
			$listingUtilityArr = isset( $listingAgentUtilityarray ) ? json_encode( $listingAgentUtilityarray ) : json_encode( array() );
		}

		// Sanitize Features
		$listingagentsFeatureArray = array();
		$listingFeatureArray       = isset( $_POST['crea-features-input'] ) ? $_POST['crea-features-input'] : array();
		if ( ! empty( $listingFeatureArray ) && $listingFeatureArray != '' ) {
			foreach ( $listingFeatureArray as $listingFeatureArray_results ) {
				if ( $listingFeatureArray_results != '' ) {
					$listingagentsFeatureArray[] = sanitize_text_field( ltrim( rtrim( $listingFeatureArray_results, " " ), " " ) );
				}
			}
			$listingFeatureArr = isset( $listingagentsFeatureArray ) ? json_encode( $listingagentsFeatureArray ) : json_encode( array() );
		}

		// Sanitize Open House Date
		$listingopenhosedateArr = array();
		if ( isset( $_POST['crea_home_date_picker'] ) && $_POST['crea_home_date_picker'] != '' ) {
			foreach ( $_POST['crea_home_date_picker'] as $oh_date ) {
				$listingopenhosedateArr[] = sanitize_text_field( $oh_date );
			}
		}

		// Sanitize Open House Start Time
		$listingstarttimeArr = array();
		$OHstarttimeArr      = isset( $_POST['crea-open-house-start-time'] ) ? $_POST['crea-open-house-start-time'] : array();
		if ( ! empty( $OHstarttimeArr ) && $OHstarttimeArr != '' ) {
			foreach ( $OHstarttimeArr as $oh_time_start ) {
				$listingstarttimeArr[] = sanitize_text_field( $oh_time_start );
			}
		}

		// Sanitize Open House End Time
		$listingendtimeArr = array();
		$OHendtimeArr      = isset( $_POST['crea-open-house-end-time'] ) ? $_POST['crea-open-house-end-time'] : array();
		if ( ! empty( $OHendtimeArr ) && $OHendtimeArr != '' ) {
			foreach ( $OHendtimeArr as $oh_time_end ) {
				$listingendtimeArr[] = sanitize_text_field( $oh_time_end );
			}
		}

		$merge_listing_open_house = array();
		$op_counter               = 0;
		foreach ( $listingopenhosedateArr as $listingopenhosedatevalue ) {
			$listing_open_house_start_time = $listingstarttimeArr[ $op_counter ];
			$listing_open_house_end_time   = $listingendtimeArr[ $op_counter ];
			$merge_listing_open_house[]    = array(
				'date'       => $listingopenhosedatevalue,
				'start_time' => $listing_open_house_start_time,
				'end_time'   => $listing_open_house_end_time
			);
			$op_counter ++;
		}

		# End Sanitize Data.  Note, media and attachments sanitized later.
		#-------------------

		$listingPhotosArr = isset( $_FILES['file'] ) ? $_FILES['file'] : null;

		if ( isset( $posttype ) && $posttype == 'list' ) {
			$post_unique_title = rand( 111111, 999999 );

			if ( isset( $_POST ) && $action === 'submit-form' && $action_which === 'add' ) {
				$listingTitle = isset( $post_unique_title ) ? $post_unique_title : '';

				if ( $listingAgentId != '' && ! empty( $listingAgentId ) && ! empty( $listingAddress ) && $listingAddress != '' && ! empty( $listingcity ) && $listingcity != '' && ! empty( $listingProvince ) && $listingProvince != '' && ! empty( $listingAgentStatus ) && $listingAgentStatus != '' && ! empty( $listingPrice ) && $listingPrice != '' && ! empty( $listingPhotosArr ) && $listingPhotosArr != '' ) {
					$guid      = microtime( true );
					$guidd     = preg_replace( "/\./", "", $guid );
					$author_id = (INT) get_current_user_id();
					$new_post  = array(
						'post_title'   => $listingTitle,
						'post_content' => $listingDescription,
						'post_status'  => 'publish',
						'post_type'    => 'aretk_listing'
					);

					try {
						$listingId = wp_insert_post( $new_post );
					} catch ( Exception $e ) {
						echo 'Caught exception: ', $e->getMessage(), "\n";
					}

					update_post_meta( $listingId, 'uniqueId', "$guidd" );
					update_post_meta( $listingId, 'listingAgentId', " $listingAgentId" );
					update_post_meta( $listingId, 'listingAddress', "$listingAddress" );
					update_post_meta( $listingId, 'listingcity', "$listingcity" );
					update_post_meta( $listingId, 'listingProvince', "$listingProvince" );
					update_post_meta( $listingId, 'listingMls', "$listingMls" );
					update_post_meta( $listingId, 'listingPrice', "$listingPrice" );
					update_post_meta( $listingId, 'listingAgentStatus', "$listingAgentStatus" );
					update_post_meta( $listingId, 'listingPropertyType', "$listingPropertyType" );
					update_post_meta( $listingId, 'listingStructureType', "$listingStructureType" );
					update_post_meta( $listingId, 'listingBedRooms', "$listingBedRooms" );
					update_post_meta( $listingId, 'listingBathrooms', "$listingBathrooms" );
					update_post_meta( $listingId, 'listingBathroomsPartial', "$listingBathroomsPartial" );
					update_post_meta( $listingId, 'listingFinishedBasement', "$listingFinishedBasement" );
					update_post_meta( $listingId, 'listingParkingSlot', "$listingParkingSlot" );
					update_post_meta( $listingId, 'listingParkinggarage', "$listingParkinggarage" );
					update_post_meta( $listingId, 'listingTourUrl', "$listingTourUrl" );
					update_post_meta( $listingId, 'listingUtilityArr', "$listingUtilityArr" );
					update_post_meta( $listingId, 'listingFeatureArr', "$listingFeatureArr" );
					update_post_meta( $listingId, 'listingopenhosedatetimeArr', json_encode( $merge_listing_open_house ) );
					update_post_meta( $listingId, 'listing_type', "exclusive" );
					update_post_meta( $listingId, 'crea_google_map_latitude', $crea_google_map_latitude );
					update_post_meta( $listingId, 'crea_google_map_longitude', $crea_google_map_longitude );
					update_post_meta( $listingId, 'crea_google_map_geo_address', $crea_google_map_geo_address );
					$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
					if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
						update_post_meta( $listingId, 'crea_listing_subscription_states', 'valid' );
					} else {
						update_post_meta( $listingId, 'crea_listing_subscription_states', 'not-valid' );
					}

					#-------------------
					# Sanitize Photos upload
					# Only allowing the following image types
					$allowedImageMimes = array(
						'jpg|jpeg|jpe' => 'image/jpeg',
						'gif'          => 'image/gif',
						'png'          => 'image/png',
					);

					if ( ! empty( $listingPhotosArr ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
						$author_id                             = (INT) get_current_user_id();
						$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;

						$counter = 1;
						for ( $j = 0; $j < count( $listingPhotosArr['name'] ); $j ++ ) {
							$uploadfile = array(
								'name'     => sanitize_file_name( $listingPhotosArr['name'][ $j ] ),
								'type'     => $listingPhotosArr['type'][ $j ],
								'tmp_name' => $listingPhotosArr['tmp_name'][ $j ],
								'error'    => $listingPhotosArr['error'][ $j ],
								'size'     => $listingPhotosArr['size'][ $j ]
							);

							$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedImageMimes );

							if ( ! empty( $fileInfo['type'] ) && $uploadfile['size'] > 0 && $uploadfile['size'] < 2000000 ) {
								$uploadInfo = wp_handle_upload( $uploadfile, array(
									'test_form' => false,
									'mimes'     => $allowedImageMimes
								) );

								if ( isset( $uploadInfo['url'] ) && ! isset( $uploadInfo['error'] ) ) {
									try {
										$sqlresult = $wpdb->insert(
											"$crea_listing_images_detail_table_name",
											array(
												'user_id'        => (int) $author_id,
												'unique_id'      => (int) $listingId,
												'image_position' => (int) $counter,
												'image_url'      => esc_url_raw( $uploadInfo['url'] ),
												'created_time'   => current_time( 'mysql', 1 ),
												'updated_time'   => current_time( 'mysql', 1 )
											),
											array( '%d', '%d', '%d', '%s', '%s', '%s' )
										);
										if (false === $sqlresult){
											echo '<div class="listing_image_upload_error" style="border: 2px solid red; padding: 10px; font-size: 14px;">';
											echo "There was an error writing to the database (08734)..\n";
											echo '</div>';
										} else {
											$counter ++;
										}
									} catch ( Exception $e ) {
										echo 'Caught exception: ', $e->getMessage(), "\n";
									}
								}
							}
						}
					}
					# PHOTOS upload END
					#-------------------

					#-------------------
					# Sanitize Attachments Upload
					# Only allowing the following doc types
					$allowedDocsMimes = array(
						'jpg|jpeg|jpe'                             => 'image/jpeg',
						'gif'                                      => 'image/gif',
						'png'                                      => 'image/png',
						'txt|asc|c|cc|h'                           => 'text/plain',
						'csv'                                      => 'text/csv',
						'tsv'                                      => 'text/tab-separated-values',
						'rtx'                                      => 'text/richtext',
						'mp3|m4a|m4b'                              => 'audio/mpeg',
						'mp4|m4v'                                  => 'video/mp4',
						'mov|qt'                                   => 'video/quicktime',
						'pdf'                                      => 'application/pdf',
						'doc|docx'                                 => 'application/msword',
						'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel'
					);

					$listingDocumentArr = isset( $_FILES['extdocfileinput'] ) ? $_FILES['extdocfileinput'] : array();

					if ( ! empty( $listingDocumentArr ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
						$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;

						$counter = 1;
						for ( $k = 0; $k < count( $listingDocumentArr['name'] ); $k ++ ) {
							$uploadfile = array(
								'name'     => sanitize_file_name( $listingDocumentArr['name'][ $k ] ),
								'type'     => $listingDocumentArr['type'][ $k ],
								'tmp_name' => $listingDocumentArr['tmp_name'][ $k ],
								'error'    => $listingDocumentArr['error'][ $k ],
								'size'     => $listingDocumentArr['size'][ $k ]
							);

							$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedDocsMimes );

							if ( ! empty( $fileInfo['type'] ) && $uploadfile['size'] > 0 && $uploadfile['size'] < 5000000 ) {
								$uploadInfo = wp_handle_upload( $uploadfile, array(
									'test_form' => false,
									'mimes'     => $allowedDocsMimes
								) );

								if ( isset( $uploadInfo['url'] ) && ! isset( $uploadInfo['error'] ) ) {
									try {
										$wpdb->insert(
											"$crea_listing_document_detail_table_name",
											array(
												'user_id'       => (int) $author_id,
												'unique_id'     => (int) $listingId,
												'document_url'  => esc_url_raw( $uploadInfo['url'] ),
												'document_name' => $uploadfile['name'],
												'created_time'  => current_time( 'mysql', 1 ),
												'updated_time'  => current_time( 'mysql', 1 )
											),
											array( '%d', '%d', '%s', '%s', '%s', '%s' )
										);
										$counter ++;
									} catch ( Exception $e ) {
										echo 'Caught exception: ', $e->getMessage(), "\n";
									}
								}
							}
						}
					}
					# Attachments Upload END
					#-------------------

					Aretk_Crea_Admin::aretkcrea_listing_update_to_aretk_server( $listingId, 'add' );
				}
			} elseif ( isset( $_POST ) && $action === 'submit-form' && $action_which === 'edit' && is_numeric( $_POST['aretk-listing-id'] ) ) {
				$listingId = isset( $_POST['aretk-listing-id'] ) ? (INT) $_POST['aretk-listing-id'] : '';

				if ( $listingAgentId != '' && ! empty( $listingAgentId ) && ! empty( $listingAddress ) && $listingAddress != '' && ! empty( $listingcity ) && $listingcity != '' && ! empty( $listingProvince ) && $listingProvince != '' && ! empty( $listingAgentStatus ) && $listingAgentStatus != '' && ! empty( $listingPrice ) && $listingPrice != '' && ! empty( $listingPhotosArr ) && $listingPhotosArr != '' ) {
					$listing_post_content = array( 'ID' => $listingId, 'post_content' => $listingDescription );
					wp_update_post( $listing_post_content );
					update_post_meta( $listingId, 'listingAgentId', " $listingAgentId" );
					update_post_meta( $listingId, 'listingAddress', "$listingAddress" );
					update_post_meta( $listingId, 'listingcity', "$listingcity" );
					update_post_meta( $listingId, 'listingProvince', "$listingProvince" );
					update_post_meta( $listingId, 'listingMls', "$listingMls" );
					update_post_meta( $listingId, 'listingPrice', "$listingPrice" );
					update_post_meta( $listingId, 'listingAgentStatus', "$listingAgentStatus" );
					update_post_meta( $listingId, 'listingPropertyType', "$listingPropertyType" );
					update_post_meta( $listingId, 'listingStructureType', "$listingStructureType" );
					update_post_meta( $listingId, 'listingBedRooms', "$listingBedRooms" );
					update_post_meta( $listingId, 'listingBathrooms', "$listingBathrooms" );
					update_post_meta( $listingId, 'listingBathroomsPartial', "$listingBathroomsPartial" );
					update_post_meta( $listingId, 'listingFinishedBasement', "$listingFinishedBasement" );
					update_post_meta( $listingId, 'listingParkingSlot', "$listingParkingSlot" );
					update_post_meta( $listingId, 'listingParkinggarage', "$listingParkinggarage" );
					update_post_meta( $listingId, 'listingTourUrl', "$listingTourUrl" );
					update_post_meta( $listingId, 'listingUtilityArr', "$listingUtilityArr" );
					update_post_meta( $listingId, 'listingFeatureArr', "$listingFeatureArr" );
					update_post_meta( $listingId, 'listingopenhosedatetimeArr', json_encode( $merge_listing_open_house ) );
					update_post_meta( $listingId, 'listing_type', "exclusive" );
					update_post_meta( $listingId, 'crea_google_map_latitude', $crea_google_map_latitude );
					update_post_meta( $listingId, 'crea_google_map_longitude', $crea_google_map_longitude );
					update_post_meta( $listingId, 'crea_google_map_geo_address', $crea_google_map_geo_address );
					$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
					if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
						update_post_meta( $listingId, 'crea_listing_subscription_states', 'valid' );
					} else {
						update_post_meta( $listingId, 'crea_listing_subscription_states', 'not-valid' );
					}

					#-------------------
					# Sanitize Photos upload
					# Only allowing the following image types
					$allowedImageMimes = array(
						'jpg|jpeg|jpe' => 'image/jpeg',
						'gif'          => 'image/gif',
						'png'          => 'image/png',
					);

					if ( ! empty( $listingPhotosArr ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
						$author_id                             = (INT) get_current_user_id();
						$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;

						$counter = 1;
						for ( $j = 0; $j < count( $listingPhotosArr['name'] ); $j ++ ) {
							$uploadfile = array(
								'name'     => sanitize_file_name( $listingPhotosArr['name'][ $j ] ),
								'type'     => $listingPhotosArr['type'][ $j ],
								'tmp_name' => $listingPhotosArr['tmp_name'][ $j ],
								'error'    => $listingPhotosArr['error'][ $j ],
								'size'     => $listingPhotosArr['size'][ $j ]
							);

							$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedImageMimes );

							if ( ! empty( $fileInfo['type'] ) && $uploadfile['size'] > 0 && $uploadfile['size'] < 2000000 ) {
								$uploadInfo = wp_handle_upload( $uploadfile, array(
									'test_form' => false,
									'mimes'     => $allowedImageMimes
								) );

								if ( isset( $uploadInfo['url'] ) && ! isset( $uploadInfo['error'] ) ) {
									$imageUrl = esc_url_raw( $uploadInfo['url'] );
									try {
										$wpdb->insert(
											"$crea_listing_images_detail_table_name",
											array(
												'user_id'        => $author_id,
												'unique_id'      => $listingId,
												'image_position' => $counter,
												'image_url'      => "$imageUrl",
												'created_time'   => current_time( 'mysql', 1 ),
												'updated_time'   => current_time( 'mysql', 1 )
											),
											array( '%d', '%d', '%d', '%s', '%s', '%s' )
										);
										$counter ++;
									} catch ( Exception $e ) {
										echo 'Caught exception: ', $e->getMessage(), "\n";
									}
								}
							}
						}
					}
					# PHOTOS upload END
					#-------------------

					#-------------------
					# Sanitize Attachments Upload
					# Only allowing the following doc types
					$allowedDocsMimes = array(
						'jpg|jpeg|jpe'                             => 'image/jpeg',
						'gif'                                      => 'image/gif',
						'png'                                      => 'image/png',
						'txt|asc|c|cc|h'                           => 'text/plain',
						'csv'                                      => 'text/csv',
						'tsv'                                      => 'text/tab-separated-values',
						'rtx'                                      => 'text/richtext',
						'mp3|m4a|m4b'                              => 'audio/mpeg',
						'mp4|m4v'                                  => 'video/mp4',
						'mov|qt'                                   => 'video/quicktime',
						'pdf'                                      => 'application/pdf',
						'doc|docx'                                 => 'application/msword',
						'xla|xls|xlsx|xlt|xlw|xlam|xlsb|xlsm|xltm' => 'application/vnd.ms-excel'
					);

					$listingDocumentArr = isset( $_FILES['extdocfileinput'] ) ? $_FILES['extdocfileinput'] : array();

					if ( ! empty( $listingDocumentArr ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
						$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;

						$counter = 1;
						for ( $k = 0; $k < count( $listingDocumentArr['name'] ); $k ++ ) {
							$uploadfile = array(
								'name'     => sanitize_file_name( $listingDocumentArr['name'][ $k ] ),
								'type'     => $listingDocumentArr['type'][ $k ],
								'tmp_name' => $listingDocumentArr['tmp_name'][ $k ],
								'error'    => $listingDocumentArr['error'][ $k ],
								'size'     => $listingDocumentArr['size'][ $k ]
							);

							$fileInfo = wp_check_filetype( $uploadfile['name'], $allowedDocsMimes );

							if ( ! empty( $fileInfo['type'] ) && $uploadfile['size'] > 0 && $uploadfile['size'] < 5000000 ) {
								$uploadInfo = wp_handle_upload( $uploadfile, array(
									'test_form' => false,
									'mimes'     => $allowedDocsMimes
								) );

								if ( isset( $uploadInfo['url'] ) && ! isset( $uploadInfo['error'] ) ) {
									$docUrl = esc_url_raw( $uploadInfo['url'] );
									list( $docName, $ext ) = explode( '.', basename( $docUrl ) );
									$docName = str_replace( '-', ' ', $docName );

									try {
										$wpdb->insert(
											"$crea_listing_document_detail_table_name",
											array(
												'user_id'       => $author_id,
												'unique_id'     => $listingId,
												'document_url'  => "$docUrl",
												'document_name' => "$docName",
												'created_time'  => current_time( 'mysql', 1 ),
												'updated_time'  => current_time( 'mysql', 1 )
											),
											array( '%d', '%d', '%s', '%s', '%s', '%s' )
										);
										$counter ++;
									} catch ( Exception $e ) {
										echo 'Caught exception: ', $e->getMessage(), "\n";
									}
								}
							}
						}
					}
					# Attachments Upload END
					#-------------------
				}

				Aretk_Crea_Admin::aretkcrea_listing_update_to_aretk_server( $listingId, 'edit' );
			}

			$allListingArr  = array();
			$agent_ids      = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'list' );
			$getAllUsername = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'array' );
			if ( isset( $getAllUsername ) && ! empty( $getAllUsername ) ) {
				foreach ( $getAllUsername as $userName ) {
					$result_type    = 'full';
					$listing        = Aretk_Crea_Admin::aretkcrea_get_user_listing_records_by_username_by_view_type( $userName, $result_type, $agent_ids );
					$listingRecords = isset( $listing[0]->TotalRecords ) ? $listing[0]->TotalRecords : 0;
					if ( $listingRecords > 0 ) {
						array_shift( $listing );
						$allListingArr = array_merge( $allListingArr, $listing );
					}
				}
			}
			$args         = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array  = (array) get_posts( $args );
			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = array_merge( $allListingArr, $exclusiveArr );
			$data               = json_encode( $allListingFinalArr );
			update_option( 'cron_run', "" );
			update_option( 'cron_run', "$data" );
			$link_url = admin_url( 'admin.php?page=listings_settings' );
			wp_safe_redirect( $link_url );
		}
	}

	/**
	 * This function will add, edit and delete listing into the aretk database
	 *
	 * @param1 unknown_type $postId
	 * @param2 unknown_type $action
	 *
	 * @return array
	 * @since Phase 1
	 */
	public static function aretkcrea_listing_update_to_aretk_server( $postId, $action ) {
		$input_eror = true; #prove otherwise

		$postId = (INT) $postId;
		if ( is_numeric( $postId ) ) {
			$input_eror = false;
		}
		switch ( $action ) {
			case 'add':
			case 'edit':
			case 'delete':
				$input_eror = false;
				break;
		}
		if ( $input_eror === true ) {
			return false;
		}

		global $wpdb;
		$getSubscriptionKey    = get_option( 'crea_subscription_key', '' );
		$subscriptionKey       = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';
		$user_ID               = get_current_user_id();
		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
		$domainName            = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}
		if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}

		if ( $action === 'add' || $action === 'edit' ) {
			/**
			 * get listing post metavalue by listing id
			 */
			$listingAgentID             = get_post_meta( $postId, 'listingAgentId', true );
			$listingAddress             = get_post_meta( $postId, 'listingAddress', true );
			$listingcity                = get_post_meta( $postId, 'listingcity', true );
			$listingProvince            = get_post_meta( $postId, 'listingProvince', true );
			$listingMls            		= get_post_meta( $postId, 'listingMls', true );
			$listingAgentStatus         = get_post_meta( $postId, 'listingAgentStatus', true );
			$listingPrice               = get_post_meta( $postId, 'listingPrice', true );
			$listingPropertyType        = get_post_meta( $postId, 'listingPropertyType', true );
			$listingStructureType       = get_post_meta( $postId, 'listingStructureType', true );
			$listingBedRooms            = get_post_meta( $postId, 'listingBedRooms', true );
			$listingBathrooms           = get_post_meta( $postId, 'listingBathrooms', true );
			$listingBathroomsPartial    = get_post_meta( $postId, 'listingBathroomsPartial', true );
			$listingFinishedBasement    = get_post_meta( $postId, 'listingFinishedBasement', true );
			$listingFeatureArr          = get_post_meta( $postId, 'listingFeatureArr', true );
			$listingParkinggarage       = get_post_meta( $postId, 'listingParkinggarage', true );
			$listingParkingSlot         = get_post_meta( $postId, 'listingParkingSlot', true );
			$listingTourUrl             = get_post_meta( $postId, 'listingTourUrl', true );
			$listingUtilityArr          = get_post_meta( $postId, 'listingUtilityArr', true );
			$listingopenhosedatetimeArr = get_post_meta( $postId, 'listingopenhosedatetimeArr', true );
			$listingGoogleMapLatitude   = get_post_meta( $postId, 'crea_google_map_latitude', true );
			$listingGoogleMapLongitude  = get_post_meta( $postId, 'crea_google_map_longitude', true );
			$listing_full_address_path  = '';
			if ( ! empty( $listingAddress ) ) {
				$listing_full_address_path .= sanitize_title( $listingAddress );
			}
			if ( ! empty( $listingcity ) ) {
				$listing_full_address_path .= '-' . sanitize_title( $listingcity );
			}
			if ( ! empty( $listingProvince ) ) {
				$listing_full_address_path .= '-' . sanitize_title( $listingProvince );
			}
			$content_post                = get_post( $postId );
			$content                     = $content_post->post_content;
			$listing_public_remarks      = $content;
			$agentFeaturesDecodeArrValue = '';
			//get agent ids array
			$agentsIDArr      = array();
			$agentsDecodeArr  = json_decode( $listingAgentID );
			$implode_agent_id = '';

			if ( $agentsDecodeArr != '' && ! empty( $agentsDecodeArr ) ) {
				$agent_id_counter = 1;
				foreach ( $agentsDecodeArr as $agentsDecodekey => $agentsDecodeArrValue ) {
					$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
					if ( ! empty( $agentsDecodeArrValue ) ) {
						$agentsIDArr[] = array(
							"sequence_id" => $agent_id_counter,
							"agent_id"    => $agentsDecodeArrValue
						);
					}
					$agent_id_counter = $agent_id_counter + 1;
				}
			}

			// get agent features
			$agentFeaturesArr       = array();
			$agentFeaturesFinaleArr = '';
			$agentFeaturesDecodeArr = json_decode( $listingFeatureArr );
			if ( $agentFeaturesDecodeArr != '' && ! empty( $agentFeaturesDecodeArr ) ) {
				foreach ( $agentFeaturesDecodeArr as $agentFeaturesDecodeArrValue ) {
					$agentFeaturesDecodeArrValue = trim( $agentFeaturesDecodeArrValue );
					if ( ! empty( $agentFeaturesDecodeArrValue ) ) {
						$agentFeaturesArr[] = trim( $agentFeaturesDecodeArrValue );
					}
				}
				$agentFeaturesFinaleArr = implode( ",", $agentFeaturesArr );
			}

			// get agents utilities
			$agentUtilitiesArr       = array();
			$agentUtilitiesDecodeArr = json_decode( $listingUtilityArr );
			if ( $agentUtilitiesDecodeArr != '' && ! empty( $agentUtilitiesDecodeArr ) ) {
				$utitlity_counter = 1;
				foreach ( $agentUtilitiesDecodeArr as $agentUtilitiesDecodeArrValue ) {
					$agentUtilitiesDecodeArrValue = trim( $agentUtilitiesDecodeArrValue );
					if ( ! empty( $agentUtilitiesDecodeArrValue ) ) {
						$agentUtilitiesArr[] = array(
							"sequence_id" => $utitlity_counter,
							"type"        => $agentUtilitiesDecodeArrValue
						);
					}
					$utitlity_counter = $utitlity_counter + 1;
				}
			}

			// get agent images
			$agentPhotoArr     = array();
			$photoGelleryTable = $wpdb->prefix . 'crea_listing_images_detail';
			$sql_select        = "SELECT * FROM `$photoGelleryTable` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
			$sql_prep          = $wpdb->prepare( $sql_select, $postId );
			$photoResultsArr   = $wpdb->get_results( $sql_prep );

			if ( $photoResultsArr != '' && ! empty( $photoResultsArr ) ) {
				$photo_counter = 1;
				foreach ( $photoResultsArr as $photoResultsArrValue ) {
					$agentPhotoArr[] = array(
						"sequence_id" => $photo_counter,
						"url"         => $photoResultsArrValue->image_url
					);
					$photo_counter   = $photo_counter + 1;
				}
			}

			// get agent external documents 
			$agentDocumentArr      = array();
			$agentDocumentFinalArr = '';
			$agentDocumentTable    = $wpdb->prefix . 'crea_listing_document_detail';
			$sql_select            = "SELECT * FROM `$agentDocumentTable` WHERE `unique_id`= %d ORDER BY `id` ASC";
			$sql_prep              = $wpdb->prepare( $sql_select, $postId );
			$agentDocumentResults  = $wpdb->get_results( $sql_prep );

			if ( $agentDocumentResults != '' && ! empty( $agentDocumentResults ) ) {
				foreach ( $agentDocumentResults as $agentDocumentResultsValue ) {
					$agentDocumentArr[] = $agentDocumentResultsValue->document_url;
				}
				$agentDocumentFinalArr = implode( ",", $agentDocumentArr );
			}

			// agent Open House date and time 
			$agentOpenHouseArr       = array();
			$agentOpenHouseDecodeArr = json_decode( $listingopenhosedatetimeArr );

			if ( $agentOpenHouseDecodeArr != '' && ! empty( $agentOpenHouseDecodeArr ) ) {
				$openHouse_Counter = 1;
				foreach ( $agentOpenHouseDecodeArr as $agentOpenHouseDecodeArrKey => $agentOpenHouseDecodeArrValue ) {
					$startDate            = strtotime( $agentOpenHouseDecodeArrValue->date );
					$startTime            = $agentOpenHouseDecodeArrValue->start_time;
					$endTime              = $agentOpenHouseDecodeArrValue->end_time;
					$agentStartDate       = date( 'd/m/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $startTime ) );
					$agentEndDate         = date( 'd/m/Y', $startDate ) . " " . date( 'h:i:s A', strtotime( $endTime ) );
					$agentFormatStartDate = $agentStartDate;
					$agentFormatEndDate   = $agentEndDate;
					if ( $agentFormatStartDate != "01/01/1970 12:00:00 AM" && $agentFormatEndDate != "01/01/1970 12:00:00 AM" ) {
						$agentOpenHouseArr[] = array(
							"sequence_id" => $openHouse_Counter,
							"start_date"  => $agentFormatStartDate,
							"end_date"    => $agentFormatEndDate
						);
					} else {
						$agentOpenHouseArr = "";
					}
					$openHouse_Counter = $openHouse_Counter + 1;
				}
			}

			if ( $postId != '' ) {
				if ( $listingGoogleMapLatitude == '57.678079218156' ) {
					$listingGoogleMapLatitude = '';
				}
				if ( $listingGoogleMapLongitude == '-101.8051686875' ) {
					$listingGoogleMapLongitude = '';
				}
				$add_listing_settinges_array = array();
				$add_listing_settinges_array = array(
					"agent_id"             => $agentsIDArr,
					"street_address"       => $listingAddress,
					"city"                 => $listingcity,
					"province"             => $listingProvince,
					"mlsID"             => $listingMls,
					"transaction_type"     => $listingAgentStatus,
					"price"                => $listingPrice,
					"property_type"        => $listingPropertyType,
					"structure"            => $listingStructureType,
					"bedrooms_total"       => $listingBedRooms,
					"bathroom_total"       => $listingBathrooms,
					"halfbath_total"       => $listingBathroomsPartial,
					"basement_type"        => $listingFinishedBasement,
					"public_remarks"       => $listing_public_remarks,
					"features"             => $agentFeaturesFinaleArr,
					"garage"               => $listingParkinggarage,
					"no_of_parking_spot"   => $listingParkingSlot,
					"moreInformation_link" => $listingTourUrl,
					"utilities"            => $agentUtilitiesArr,
					"photo"                => $agentPhotoArr,
					"external_document"    => $agentDocumentFinalArr,
					"open_house"           => $agentOpenHouseArr,
					"generated_address"    => $listing_full_address_path,
					"geocoded_latitude"    => $listingGoogleMapLatitude,
					"geocoded_longitude"   => $listingGoogleMapLongitude,
				);
				$post_string                 = http_build_query( $add_listing_settinges_array );
				$exclusive_property_id       = array();
				$exclusive_property_id[]     = (string) $postId;
				// print_r($add_listing_settinges_array);
				// exit(); 
				if ( $action === 'add' ) {
					if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
						$addListing = curl_init();
						curl_setopt( $addListing, CURLOPT_HEADER, 0 );
						curl_setopt( $addListing, CURLOPT_VERBOSE, 0 );
						curl_setopt( $addListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=insert_listing" );
						curl_setopt( $addListing, CURLOPT_POST, true );
						curl_setopt( $addListing, CURLOPT_POSTFIELDS, $post_string );
						curl_setopt( $addListing, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $addListing, CURLOPT_REFERER, $domainName );
						$addListingCurlExecute = curl_exec( $addListing );
						curl_close( $addListing );
						$addListingCurlExecuteResponse = ( $addListingCurlExecute ) . PHP_EOL;
						$responseDecode                = json_decode( $addListingCurlExecuteResponse );
						if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
							if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
								if ( isset( $responseDecode->data->insert_id ) ) {
									update_post_meta( $postId, 'aretk_server_listing_id', (int) $responseDecode->data->insert_id );
								}
							} else {
								$exclusive_old_property_id_array = array();
								$mergerd_property_id_array       = array();
								$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
								if ( ! empty( $exclusive_stored_add_id_result ) ) {
									$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
								}
								$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
								$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
								$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
								update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
							}

						} else {
							$exclusive_old_property_id_array = array();
							$mergerd_property_id_array       = array();
							$exclusive_stored_add_id_result  = get_option( "exclusive_stored_add_id" );
							if ( ! empty( $exclusive_stored_add_id_result ) ) {
								$exclusive_old_property_id_array = json_decode( $exclusive_stored_add_id_result );
							}
							$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusive_property_id );
							$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
							$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
							update_option( "exclusive_stored_add_id", $mergerd_property_id_array );
						}
					}
				} else if ( $action === 'edit' ) {
					if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
						if ( $listingGoogleMapLatitude == '57.678079218156' ) {
							$listingGoogleMapLatitude = '';
						}
						if ( $listingGoogleMapLongitude == '-101.8051686875' ) {
							$listingGoogleMapLongitude = '';
						}
						$last_aretk_server_insert_id  = get_post_meta( $postId, 'aretk_server_listing_id', true );
						$edit_listing_settinges_array = array();
						$edit_listing_settinges_array = array(
							"id"                   => $last_aretk_server_insert_id,
							"agent_id"             => $agentsIDArr,
							"street_address"       => $listingAddress,
							"city"                 => $listingcity,
							"province"             => $listingProvince,
							"mlsID"             => $listingMls,
							"transaction_type"     => $listingAgentStatus,
							"price"                => $listingPrice,
							"property_type"        => $listingPropertyType,
							"structure"            => $listingStructureType,
							"bedrooms_total"       => $listingBedRooms,
							"bathroom_total"       => $listingBathrooms,
							"halfbath_total"       => $listingBathroomsPartial,
							"basement_type"        => $listingFinishedBasement,
							"public_remarks"       => $listing_public_remarks,
							"features"             => $agentFeaturesFinaleArr,
							"garage"               => $listingParkinggarage,
							"no_of_parking_spot"   => $listingParkingSlot,
							"moreInformation_link" => $listingTourUrl,
							"utilities"            => $agentUtilitiesArr,
							"photo"                => $agentPhotoArr,
							"external_document"    => $agentDocumentFinalArr,
							"open_house"           => $agentOpenHouseArr,
							"generated_address"    => $listing_full_address_path,
							"geocoded_latitude"    => $listingGoogleMapLatitude,
							"geocoded_longitude"   => $listingGoogleMapLongitude,
						);
						$post_string                  = http_build_query( $edit_listing_settinges_array );
						$editListing                  = curl_init();
						curl_setopt( $editListing, CURLOPT_HEADER, 0 );
						curl_setopt( $editListing, CURLOPT_VERBOSE, 0 );
						curl_setopt( $editListing, CURLOPT_RETURNTRANSFER, true );
						curl_setopt( $editListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=edit_listing" );
						curl_setopt( $editListing, CURLOPT_POST, true );
						curl_setopt( $editListing, CURLOPT_POSTFIELDS, $post_string );
						curl_setopt( $editListing, CURLOPT_REFERER, $domainName );
						$editListingCurlExecute = curl_exec( $editListing );
						curl_close( $editListing );
						$editListingCurlExecuteResponse = ( $editListingCurlExecute ) . PHP_EOL;
						$responseDecode                 = json_decode( $editListingCurlExecuteResponse );
						if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
							if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
								if ( isset( $responseDecode->data->updated_id ) ) {
									update_post_meta( $postId, 'aretk_server_listing_id', (int) $responseDecode->data->updated_id );
								}
							}
						}
					}
				}
			}

		} elseif ( $action === 'delete' ) {

			if ( isset( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				$last_aretk_server_insert_id = get_post_meta( $postId, 'aretk_server_listing_id', true );
				$delete_listing              = array( "id" => $postId );
				$post_string                 = http_build_query( $delete_listing );
				$deleteListing               = curl_init();
				curl_setopt( $deleteListing, CURLOPT_HEADER, 0 );
				curl_setopt( $deleteListing, CURLOPT_VERBOSE, 0 );
				curl_setopt( $deleteListing, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $deleteListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=delete_listing" );
				curl_setopt( $deleteListing, CURLOPT_POST, true );
				curl_setopt( $deleteListing, CURLOPT_POSTFIELDS, $post_string );
				curl_setopt( $deleteListing, CURLOPT_REFERER, $domainName );
				$deleteListingCurlExecute = curl_exec( $deleteListing );
				curl_close( $deleteListing );

				$deleteListingCurlExecuteResponse = ( $deleteListingCurlExecute ) . PHP_EOL;
				$responseDecode                   = json_decode( $deleteListingCurlExecuteResponse );
			}
		}
	}

	/**
	 * This function will return the array with the user data
	 *
	 * @param unknown_type $domainName
	 *
	 * @return array
	 * @since Phase 1
	 */
	public static function aretkcrea_get_user_listing_records_by_username_by_view_type( $userName, $result_type ) {
		global $wpdb;
		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}
		$user_ID            = get_current_user_id();
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
		$subscriptionKey    = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';
		$ch                 = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=listings&feed=$userName&result_type=$result_type&limit=20&offset=0" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSet = json_decode( $data );
		$agent_ids = '';

		return $resultSet;
	}

	/**
	 * function for aretk_crea_disclaimer_update
	 *
	 * ajax callback function aretk_crea_disclaimer_update
	 * set disclaimer specifics
	 * @return NULL
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */
	function aretk_crea_disclaimer_update() {
		$pluralize   = isset( $_POST['disclaimer_pluralize'] ) ? sanitize_text_field( base64_decode( $_POST['disclaimer_pluralize'] ) ) : 'I am';
		$salestype   = isset( $_POST['disclaimer_salestype'] ) ? sanitize_text_field( base64_decode( $_POST['disclaimer_salestype'] ) ) : 'an agent';
		$licensetype = isset( $_POST['disclaimer_licensetype'] ) ? sanitize_text_field( base64_decode( $_POST['disclaimer_licensetype'] ) ) : 'residential and commercial';
		$province    = isset( $_POST['disclaimer_province'] ) ? sanitize_text_field( base64_decode( $_POST['disclaimer_province'] ) ) : '';
		$disclaimer  = wp_kses_post( $pluralize ) . ' ' . wp_kses_post( $salestype ) . __( 'licensed to trade', 'aretk-crea' ) . wp_kses_post( $licensetype ) . __( 'real estate', 'aretk-crea' );
		if ( ! empty( $province ) ) {
			$disclaimer .= ' in ' . $province;
		}
		$disclaimer .= '. ' . __( 'The out of province listing content on this website is not intended to solicit a trade in real estate.  Any consumers interested in out of province listings must contact a person who is licensed to trade in real estate in that province.', 'aretk-crea' );

		$disclaimer_array = array(
			'disclaimer'  => $disclaimer,
			'pluralize'   => $pluralize,
			'salestype'   => $salestype,
			'licensetype' => $licensetype,
			'province'    => $province
		);
		update_option( 'aretk_crea_disclaimer1', $disclaimer_array );
		echo ARETKCREA_PLUGIN_SETTINGS_PAGE_BTN_SUCESS;
		exit;
	}

	/**
	 * function for aretk_crea_add_new_agents
	 *
	 * ajax callback function aretk_crea_add_new_agents
	 * add new agent id or email
	 * @return return crea agent details
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */
	function aretk_crea_add_new_agents() {
		global $wpdb;
		$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$agent_id_array        = array();
		$valid_agent_id        = isset( $_POST['encoded_agent_id'] ) ? (INT) base64_decode( $_POST['encoded_agent_id'] ) : '';
		$valid_agent_email     = isset( $_POST['encoded_agent_email'] ) ? sanitize_email( base64_decode( $_POST['encoded_agent_email'] ) ) : '';
		$valid_agent_name      = isset( $_POST['encoded_agent_name'] ) ? sanitize_text_field( base64_decode( $_POST['encoded_agent_name'] ) ) : '';
		$agent_id_array        = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'array' );
		if ( ! in_array( $valid_agent_id, $agent_id_array ) ) {
			if ( ! empty( $valid_agent_id ) && $valid_agent_id != '' && ! empty( $valid_agent_email ) && $valid_agent_email != '' ) {
				$wpdb->insert( "$crea_agent_table_name",
					array(
						'crea_agent_name'          => $valid_agent_name,
						'crea_agent_id'            => $valid_agent_id,
						'crea_agent_email'         => $valid_agent_email,
						'crea_agent_created_date'  => current_time( 'mysql', 1 ),
						'crea_agent_modified_date' => current_time( 'mysql', 1 )
					),
					array( '%s', '%s', '%s', '%s', '%s' )
				);

				$sql_select         = "SELECT `crea_id`,`crea_agent_name`,`crea_agent_id`,`crea_agent_email`,`crea_agent_modified_date` FROM `$crea_agent_table_name` ORDER BY `crea_id` ASC";
				$sql_prep           = $wpdb->prepare( $sql_select, null );
				$get_agents_results = $wpdb->get_results( $sql_prep );

				if ( ! empty( $get_agents_results ) && $get_agents_results != '' ) {
					$counter = 0;
					foreach ( $get_agents_results as $get_agents_key => $get_agents_value ) {
						$counter = $counter + 1;
						echo '<tr>';
						echo '<td>' . $counter . '</td>';
						echo '<td><p id="crea_update_agent_name_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_name">' . $get_agents_value->crea_agent_name . '</p><input class="crea_update_name_class" style="display:none" type="text" value="' . $get_agents_value->crea_agent_name . '" id="crea_setting_update_agent_name_' . $get_agents_value->crea_id . '" name="crea_settings_agent_name' . $get_agents_value->crea_id . '"><p class="crea_not_null_agent_name" id="crea_agen_name_not_blank_' . $get_agents_value->crea_id . '" style="display:none;">' . __( ARETKCREA_AGENT_NAME_NOT_NULL, ARETKCREA_PLUGIN_SLUG ) . '</p></td>';
						echo '<td><p id="crea_update_agent_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_id">' . $get_agents_value->crea_agent_id . '</p><input class="crea_update_id_class" style="display:none" type="text" value="' . $get_agents_value->crea_agent_id . '" id="crea_setting_update_agent_id_' . $get_agents_value->crea_id . '" name="crea_settings_agent_ids' . $get_agents_value->crea_id . '"><p class="crea_not_null_agent_id" id="crea_agen_id_not_blank_' . $get_agents_value->crea_id . '" style="display:none;">' . __( ARETKCREA_AGENT_ID_NOT_NULL, ARETKCREA_PLUGIN_SLUG ) . '</p></td>';
						echo '<td><p id="crea_update_agent_email_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_email">' . $get_agents_value->crea_agent_email . '</p><input class="crea_update_email_class" style="display:none" type="text" value="' . $get_agents_value->crea_agent_email . '" id="crea_setting_update_agent_email_' . $get_agents_value->crea_id . '" name="crea_settings_agent_email' . $get_agents_value->crea_id . '"><p id="crea_agen_email_not_blank_' . $get_agents_value->crea_id . '" class="crea_not_null_agent_email" style="display:none;">' . __( ARETKCREA_AGENT_EMAIL_NOT_NULL, ARETKCREA_PLUGIN_SLUG ) . '</p><p id="crea_agent_email_valid_' . $get_agents_value->crea_id . '" class="crea_valid_agent_email" style="display:none;">' . __( ARETKCREA_AGENT_EMAIL_NOT_VALID, ARETKCREA_PLUGIN_SLUG ) . '</p></td>';
						echo '<td><p class="agent_modified_date" id="agent_modified_date_' . $get_agents_value->crea_id . '">' . $get_agents_value->crea_agent_modified_date . '</p></td>';
						echo '<td><a id="crea_agent_edit_' . $get_agents_value->crea_id . '" class="crea_agent_action crea_agent_edit_action" href="javascript:void(0);"><img src="' . ARETK_CREA_PLUGIN_URL . 'admin/images/edit-icon.png' . '" alt="edit" width="20" height="20"></a><a id="crea_agent_delete_' . $get_agents_value->crea_id . '" class="crea_agent_action crea_agent_delete_action" href="javascript:void(0);"><img src="' . ARETK_CREA_PLUGIN_URL . 'admin/images/delete-icon.png' . '" alt="delete" width="20" height="20"></a><input style="display:none;" type="button" id="crea_agent_setting_update_button_' . $get_agents_value->crea_id . '" class="crea_agent_record_update button button-primary" value="' . __( ARETKCREA_SETTING_POPUP_AGENT_DETAILS_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG ) . '"></td>';
						echo '</tr>';
					}
				}
			}
		} else {
			echo 'already_exsits';
		}
		die();
	}

	/**
	 * create function for aretkcrea_delete_selected_agent_records
	 *
	 * ajax callback function aretkcrea_delete_selected_agent_records
	 * Delete selected agent records
	 *
	 * @return return crea delete agent details
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */
	function aretkcrea_delete_selected_agent_records() {
		global $wpdb;
		$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$crea_agent_id         = isset( $_POST['crea_agent_id'] ) ? (INT) base64_decode( $_POST['crea_agent_id'] ) : '';

		if ( ! empty( $crea_agent_id ) && $crea_agent_id != '' ) {
			$sql_select   = "DELETE FROM `$crea_agent_table_name` WHERE `crea_id`= %d";
			$sql_prep     = $wpdb->prepare( $sql_select, $crea_agent_id );
			$delete_recod = $wpdb->query( $sql_prep );

			if ( $delete_recod == 1 ) {
				$sql_select         = "SELECT `crea_id`,`crea_agent_name`,`crea_agent_id`,`crea_agent_email`,`crea_agent_modified_date` FROM `$crea_agent_table_name` ORDER BY `crea_id` ASC";
				$sql_prep           = $wpdb->prepare( $sql_select, null );
				$get_agents_results = $wpdb->get_results( $sql_prep );

				if ( ! empty( $get_agents_results ) && $get_agents_results != '' ) {
					$counter = 0;
					foreach ( $get_agents_results as $get_agents_key => $get_agents_value ) {
						$counter = $counter + 1;
						echo '<tr>';
						echo '<td>' . $counter . '</td>';
						echo '<td><p id="crea_update_agent_name_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_id">' . $get_agents_value->crea_agent_name . '</p></td>';
						echo '<td><p id="crea_update_agent_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_id">' . $get_agents_value->crea_agent_id . '</p><input class="crea_update_id_class" style="display:none" type="text" value="' . $get_agents_value->crea_agent_id . '" id="crea_setting_update_agent_id_' . $get_agents_value->crea_id . '" name="crea_settings_agent_ids' . $get_agents_value->crea_id . '"><p class="crea_not_null_agent_id" id="crea_agen_id_not_blank_' . $get_agents_value->crea_id . '" style="display:none;">' . __( ARETKCREA_AGENT_ID_NOT_NULL, ARETKCREA_PLUGIN_SLUG ) . '</p></td>';
						echo '<td><p id="crea_update_agent_email_p_tag_' . $get_agents_value->crea_id . '" class="crea_agent_email">' . $get_agents_value->crea_agent_email . '</p><input class="crea_update_email_class" style="display:none" type="text" value="' . $get_agents_value->crea_agent_email . '" id="crea_setting_update_agent_email_' . $get_agents_value->crea_id . '" name="crea_settings_agent_email' . $get_agents_value->crea_id . '"><p id="crea_agen_email_not_blank_' . $get_agents_value->crea_id . '" class="crea_not_null_agent_email" style="display:none;">' . __( ARETKCREA_AGENT_EMAIL_NOT_NULL, ARETKCREA_PLUGIN_SLUG ) . '</p><p id="crea_agent_email_valid_' . $get_agents_value->crea_id . '" class="crea_valid_agent_email" style="display:none;">' . __( ARETKCREA_AGENT_EMAIL_NOT_VALID, ARETKCREA_PLUGIN_SLUG ) . '</p></td>';
						echo '<td><p class="agent_modified_date" id="agent_modified_date_' . $get_agents_value->crea_id . '">' . $get_agents_value->crea_agent_modified_date . '</p></td>';
						echo '<td><a id="crea_agent_edit_' . $get_agents_value->crea_id . '" class="crea_agent_action crea_agent_edit_action" href="javascript:void(0);"><img src="' . ARETK_CREA_PLUGIN_URL . 'admin/images/edit-icon.png' . '" alt="edit" width="20" height="20"></a><a id="crea_agent_delete_' . $get_agents_value->crea_id . '" class="crea_agent_action crea_agent_delete_action" href="javascript:void(0);"><img src="' . ARETK_CREA_PLUGIN_URL . 'admin/images/delete-icon.png' . '" alt="delete" width="20" height="20"></a><input style="display:none;" type="button" id="crea_agent_setting_update_button_' . $get_agents_value->crea_id . '" class="crea_agent_record_update button button-primary" value="' . __( ARETKCREA_SETTING_POPUP_AGENT_DETAILS_UPDATE_BTN, ARETKCREA_PLUGIN_SLUG ) . '"></td>';
						echo '</tr>';
					}
				}
			}
		}
		die();
	}

	/**
	 * create function for aretkcrea_get_google_map_address_lat_long
	 *
	 * ajax callback function aretkcrea_get_google_map_address_lat_long
	 * get address lat long
	 *
	 * @return return crea address lat long
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */
	function aretkcrea_get_google_map_address_lat_long() {

		$google_map_address   = sanitize_text_field( base64_decode( $_POST['google_map_address'] ) );
		$geo_location_address = file_get_contents( 'https://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode( $google_map_address ) . '&sensor=false' );
		$geo_location_address = json_decode( $geo_location_address, true );

		if ( $google_map_address != '' && $geo_location_address['status'] === 'OK' ) {
			$latitude             = isset( $geo_location_address['results'][0]['geometry']['location']['lat'] ) ? $geo_location_address['results'][0]['geometry']['location']['lat'] : '';
			$longitude            = isset( $geo_location_address['results'][0]['geometry']['location']['lng'] ) ? $geo_location_address['results'][0]['geometry']['location']['lng'] : '';
			$address              = isset( $geo_location_address['results'][0]['formatted_address'] ) ? $geo_location_address['results'][0]['formatted_address'] : '';
			$sucessfully_callback = base64_encode( 'sucessfully' . '|' . $latitude . '|' . $longitude . '|' . $address );
			echo $sucessfully_callback;
		} else {
			$sucessfully_callback = base64_encode( 'error' . '|' . $geo_location_address['error_message'] );
			echo $sucessfully_callback;
		}
		die();
	}

	function aretkcrea_update_crea_listing_images_order_with_upload() {
		global $wpdb;

		$listingId = (INT) $_POST['listingId'];

		$listingPhotosArr = isset( $_FILES ) ? $_FILES : null;

		if ( ! empty( $listingPhotosArr ) && ! empty( $listingId ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			# Only allow the following image types
			$allowedImageMimes                     = array(
				'jpg|jpeg|jpe' => 'image/jpeg',
				'gif'          => 'image/gif',
				'png'          => 'image/png',
			);
			$author_id                             = (INT) get_current_user_id();
			$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;

			$counter = 1;
			for ( $j = 0; $j < count( $listingPhotosArr ); $j ++ ) {
				$uploadfile = array(
					'name'     => sanitize_file_name( $listingPhotosArr[ $j ]['name'] ),
					'type'     => $listingPhotosArr[ $j ]['type'],
					'tmp_name' => $listingPhotosArr[ $j ]['tmp_name'],
					'error'    => $listingPhotosArr[ $j ]['error'],
					'size'     => $listingPhotosArr[ $j ]['size']
				);
				$fileInfo   = wp_check_filetype( $uploadfile['name'], $allowedImageMimes );

				if ( ! empty( $fileInfo['type'] ) && $uploadfile['size'] > 0 && $uploadfile['size'] < 5000000 ) {
					$uploadInfo = wp_handle_upload( $uploadfile, array(
						'test_form' => false,
						'mimes'     => $allowedImageMimes
					) );

					if ( isset( $uploadInfo['url'] ) && ! isset( $uploadInfo['error'] ) ) {
						try {
							$sqlresult = $wpdb->insert(
								"$crea_listing_images_detail_table_name",
								array(
									'user_id'        => (int) $author_id,
									'unique_id'      => (int) $listingId,
									'image_position' => (int) $counter,
									'image_url'      => esc_url_raw( $uploadInfo['url'] ),
									'created_time'   => current_time( 'mysql', 1 ),
									'updated_time'   => current_time( 'mysql', 1 )
								),
								array( '%d', '%d', '%d', '%s', '%s', '%s' )
							);

							if (false === $sqlresult){
								echo '<div class="listing_image_upload_error" style="border: 2px solid red; padding: 10px; font-size: 14px;">';
								echo "There was an error writing to the database (09845)..\n";
								echo '</div>';
							} else {
								$counter ++;
							}
						} catch ( Exception $e ) {
							echo '<div class="listing_image_upload_error" style="border: 2px solid red; padding: 10px; font-size: 14px;">';
							echo 'Caught exception: ', $e->getMessage(), "\n";
							echo '</div>';
						}
					}
				} else {
					echo '<div class="listing_image_upload_error" style="border: 2px solid red; padding: 10px; font-size: 14px;">';
					if ( count( $listingPhotosArr ) > 1 ) {
						echo '1 or more of the images you attempted to upload were to large.';
					} else {
						echo 'The image you attempted to upload was to large.';
					}
					echo ' The max allowed image size is 5MB.';
					echo '</div>';
				}
			}
		}

		$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
		$sql_select                            = "SELECT * FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
		$sql_prep                              = $wpdb->prepare( $sql_select, $listingId );
		$imageSet                              = $wpdb->get_results( $sql_prep );

		$html = '';
		$html .= '<a href="javascript:void(0);" class="btn outlined mleft_no reorder_link" id="save_reorder">reorder photos</a><div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br>2. Click \'Save Reordering\' when finished.</div><div class="gallery"><ul class="reorder_ul reorder-photos-list">';
		if ( isset( $imageSet ) && ! empty( $imageSet ) ) {
			foreach ( $imageSet as $image ) {
				$html .= '<li class="ui-sortable-handle delete-icon" id="image_li_' . $image->id . '"><div  id="image_li_' . $image->id . '" class = "delete-showcase-photo-listing"></div>
				<a href="javascript:void(0);" id="image_li_' . $image->id . '" style="float:none;" class="image_link "></a><img style="height:100px;width:100px;" src="' . $image->image_url . '" alt=""></li>';
			}
		}
		$html .= '</ul></div>';
		echo $html;
		die();
	}

	function aretkcrea_delete_listing_image_edit_page_from_listing_ajax() {
		global $wpdb;

		$imageID    = (INT) base64_decode( $_POST['id'] );
		$upload_dir = wp_upload_dir();

		if ( ! empty( $imageID ) && ! empty( $upload_dir['basedir'] ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			$fileDeleteError = false;

			$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
			$sql_select                            = "SELECT `unique_id`, `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `id`= %d LIMIT 1";
			$sql_prep                              = $wpdb->prepare( $sql_select, $imageID );
			$resultPostIdArr                       = $wpdb->get_results( $sql_prep );
			$listingID                             = (INT) $resultPostIdArr[0]->unique_id;
			$docURL                                = $resultPostIdArr[0]->image_url;

			if ( ! empty( $docURL ) && ! empty( $listingID ) ) {
				list( $docBase, $docPath ) = explode( 'uploads/', $docURL );

				$docPath = esc_url( $upload_dir['basedir'] . '/' . $docPath );

				if ( file_exists( $docPath ) && wp_is_writable( $docPath ) ) {
					if ( false === unlink( $docPath ) ) {
						echo 'Caught exception: could not remove document, check file permissions' . "\n";
						$fileDeleteError = true;
					}
				}

				if ( true !== $fileDeleteError ) {
					$sql_select = "DELETE FROM `$crea_listing_images_detail_table_name` WHERE `id`= %d AND `unique_id` = %d LIMIT 1";
					$sql_prep   = $wpdb->prepare( $sql_select, $imageID, $listingID );
					try {
						$wpdb->query( $sql_prep );
					} catch ( Exception $e ) {
						echo 'Caught exception: ' . $e->getMessage() . "\n";
					}

					// reset all images position
					$sql_select   = "SELECT * FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
					$sql_prep     = $wpdb->prepare( $sql_select, $listingID );
					$resultSetArr = $wpdb->get_results( $sql_prep );

					$count = 1;
					foreach ( $resultSetArr as $singleImage ) {
						$wpdb->update( "$crea_listing_images_detail_table_name",
							array( 'image_position' => $count ),
							array( 'id' => $singleImage->id ),
							array( '%d' ),
							array( '%d' )
						);
						$count ++;
					}

					// todo					//Aretk_Crea_Admin::aretkcrea_listing_update_to_aretk_server($listingID,'deleteimage');
				}
			}
		}

		$sql_select = "SELECT * FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d ORDER BY `image_position` ASC";
		$sql_prep   = $wpdb->prepare( $sql_select, $listingID );
		$imageSet   = $wpdb->get_results( $sql_prep );

		$html = '';
		$html .= '<a href="javascript:void(0);" class="btn outlined mleft_no reorder_link" id="save_reorder">reorder photos</a><div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br>2. Click \'Save Reordering\' when finished.</div>
		<div class="gallery">
			<ul class="reorder_ul reorder-photos-list">';
		if ( isset( $imageSet ) && ! empty( $imageSet ) ) {
			foreach ( $imageSet as $image ) {
				$html .= '<li class="ui-sortable-handle delete-icon" id="image_li_' . esc_attr( $image->id ) . '"><div  id="image_li_' . esc_attr( $image->id ) . '" class = "delete-showcase-photo-listing"></div><a href="javascript:void(0);" id="image_li_' . esc_attr( $image->id ) . '" style="float:none;" class="image_link "></a><img style="height:100px;width:100px;" src="' . esc_url( $image->image_url ) . '" alt=""></li>';
			}
		}
		$html .= '</ul>
		</div>';
		echo $html;
		die();
	}

	function aretkcrea_delete_listing_document_edit_page_from_listing_ajax() {
		global $wpdb;

		$docID      = (INT) base64_decode( $_POST['documentID'] );
		$upload_dir = wp_upload_dir();

		if ( ! empty( $docID ) && ! empty( $upload_dir['basedir'] ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			$fileDeleteError = false;

			$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;
			$sql_select                              = "SELECT `unique_id`, `document_url` FROM `$crea_listing_document_detail_table_name` WHERE `id`= %d LIMIT 1";
			$sql_prep                                = $wpdb->prepare( $sql_select, $docID );
			$resultPostIdArr                         = $wpdb->get_results( $sql_prep );
			$listingID                               = (INT) $resultPostIdArr[0]->unique_id;
			$docURL                                  = $resultPostIdArr[0]->document_url;

			if ( ! empty( $docURL ) && ! empty( $listingID ) ) {
				list( $docBase, $docPath ) = explode( 'uploads/', $docURL );

				$docPath = esc_url( $upload_dir['basedir'] . '/' . $docPath );

				if ( file_exists( $docPath ) && wp_is_writable( $docPath ) ) {
					if ( false === unlink( $docPath ) ) {
						echo __( 'Caught exception: could not remove document, check file permissions', 'aretk-crea' ) . "\n";
						$fileDeleteError = true;
					}
				}

				if ( true !== $fileDeleteError ) {
					$sql_select = "DELETE FROM `$crea_listing_document_detail_table_name` WHERE `id`= %d AND `unique_id` = %d LIMIT 1";
					$sql_prep   = $wpdb->prepare( $sql_select, $docID, $listingID );
					try {
						$wpdb->query( $sql_prep );
					} catch ( Exception $e ) {
						echo 'Caught exception: ' . $e->getMessage() . "\n";
					}
				}
			}
		}

		$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;
		$sql_select                              = "SELECT * FROM `$crea_listing_document_detail_table_name` WHERE `unique_id`= %d ORDER BY id ASC";
		$sql_prep                                = $wpdb->prepare( $sql_select, $listingID );
		$documentSet                             = $wpdb->get_results( $sql_prep );

		$html = '';
		if ( isset( $documentSet ) && ! empty( $documentSet ) ) {
			foreach ( $documentSet as $documentSetKey => $documentSetValue ) {
				$html .= '<div class="crea_listing_display_select_files">';
				$html .= '<a id="' . esc_attr( $documentSetValue->id ) . '" href="javascript:void(0);" class="crea_delete_documents"><img id="crea_listing_dicument_delte_ids" width="20px" src="' . esc_url( ARETK_CREA_PLUGIN_URL ) . 'admin/images/delete-icon.png" alt="document_icon.png" alt="Delete Document"></a>';
				$html .= '<img class="crea_document_files_img" width="50px" src="' . esc_url( ARETK_CREA_PLUGIN_URL ) . 'admin/images/document_icon.png" alt="Document Icon">';
				$html .= '<p>' . esc_html( $documentSetValue->document_name ) . '</p>';
				$html .= '<input type="hidden" name="crea_listing_multiplefile_document_array[]" value="' . esc_attr( $documentSetValue->document_name ) . '" >';
				$html .= '</div>';
			}
		}
		echo $html;
		die();
	}

	/**
	 * create function for update listing images order
	 *
	 * ajax callback function update_crea_listing_images_order
	 *
	 * @return return crea agent update records
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */

	function aretkcrea_update_crea_listing_images_order() {
		global $wpdb;

		$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
		$listingID                             = (INT) $_POST['pageId']; //117

		$idArray = array();
		if ( ! empty( $_POST['ids'] ) ) {
			$idArray = explode( ",", $_POST['ids'] );
			$count   = 1;
			foreach ( $idArray as $id ) {
				if ( is_numeric( $id ) ) {
					$wpdb->update( $crea_listing_images_detail_table_name, array( 'image_position' => $count ), array( 'id' => (INT) $id ), array( '%d' ), array( '%d' ) );
					$count ++;
				}
			}
		}

		# todo
		Aretk_Crea_Admin::aretkcrea_listing_update_to_aretk_server( $listingID, 'reorder' );

		$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;

		$sql_select = "SELECT * FROM `$crea_listing_images_detail_table_name` WHERE `unique_id` = %d ORDER BY image_position ASC";
		$sql_prep   = $wpdb->prepare( $sql_select, $listingID );
		$imageSet   = $wpdb->get_results( $sql_prep );
		$html       = '';
		$html .= '<a href="javascript:void(0);" class="btn outlined mleft_no reorder_link" id="save_reorder">reorder photos</a><div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br>2. Click \'Save Reordering\' when finished.</div><div class="gallery"><ul class="reorder_ul reorder-photos-list">';
		if ( isset( $imageSet ) && ! empty( $imageSet ) ) {
			foreach ( $imageSet as $image ) {

				$html .= '<li id="image_li_' . $image->id . '" class="ui-sortable-handle delete-icon"><a href="javascript:void(0);" style="float:none;" class="image_link"><img style="height:100px;width:100px;" src="' . $image->image_url . '" alt=""></a></li>';
			}
		}
		$html .= '</ul></div>';
		echo $html;
		die();
	}

	/**
	 * create function for aretkcrea_update_crea_agents_records
	 *
	 * ajax callback function aretkcrea_update_crea_agents_records
	 *
	 * @return return crea agent update records
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 * @author Aretk Inc.
	 *
	 * @param null
	 */
	function aretkcrea_update_crea_agents_records() {
		global $wpdb;

		$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$get_agent_ids         = array();
		$agent_id_array        = array();
		$crea_agent_auto_id    = isset( $_POST['crea_agent_auto_id'] ) ? (INT) base64_decode( $_POST['crea_agent_auto_id'] ) : '';
		$crea_agent_id         = isset( $_POST['crea_agent_id'] ) ? sanitize_text_field( base64_decode( $_POST['crea_agent_id'] ) ) : '';
		$crea_agent_name       = isset( $_POST['crea_agent_name'] ) ? sanitize_text_field( base64_decode( $_POST['crea_agent_name'] ) ) : '';
		$crea_agent_email      = isset( $_POST['crea_agent_email'] ) ? sanitize_email( base64_decode( $_POST['crea_agent_email'] ) ) : '';

		$agent_id_array = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'array' );


		if ( ! in_array( $crea_agent_id, $agent_id_array ) ) {
			$wpdb->update( "$crea_agent_table_name",
				array(
					'crea_agent_name'          => $crea_agent_name,
					'crea_agent_id'            => $crea_agent_id,
					'crea_agent_email'         => $crea_agent_email,
					'crea_agent_modified_date' => current_time( 'mysql', 1 )
				),
				array( 'crea_id' => $crea_agent_auto_id ),
				array( '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);

			$sql_select         = "SELECT `crea_id`, `crea_agent_name`, `crea_agent_id`, `crea_agent_email`, `crea_agent_modified_date` FROM `$crea_agent_table_name` WHERE `crea_id`= %d";
			$sql_prep           = $wpdb->prepare( $sql_select, $crea_agent_auto_id );
			$get_agents_results = $wpdb->get_results( $sql_prep );
			if ( ! empty( $get_agents_results ) && $get_agents_results != '' ) {
				foreach ( $get_agents_results as $get_agents_key => $get_agents_value ) {
					echo $get_agents_value->crea_id . ',' . $get_agents_value->crea_agent_id . ',' . $get_agents_value->crea_agent_email . ',' . $get_agents_value->crea_agent_modified_date . ',' . $get_agents_value->crea_agent_name;
				}
			}
		} else {

			$wpdb->update( "$crea_agent_table_name",
				array(
					'crea_agent_name'          => $crea_agent_name,
					'crea_agent_email'         => $crea_agent_email,
					'crea_agent_modified_date' => current_time( 'mysql', 1 )
				),
				array( 'crea_id' => $crea_agent_auto_id ),
				array( '%s', '%s', '%s' ),
				array( '%d' )
			);

			$sql_select            = "SELECT `crea_agent_id`, `crea_agent_name`, `crea_agent_email`, `crea_agent_modified_date` FROM `$crea_agent_table_name` WHERE `crea_id` = %d";
			$sql_prep              = $wpdb->prepare( $sql_select, $crea_agent_auto_id );
			$get_agents_id_results = $wpdb->get_results( $sql_prep );

			echo $get_agents_id_results[0]->crea_agent_id . ",agent_id_already_exsits," . $crea_agent_auto_id . ',' . $get_agents_id_results[0]->crea_agent_email . ',' . $get_agents_id_results[0]->crea_agent_modified_date . ',' . $get_agents_id_results[0]->crea_agent_name;
		}
		die();
	}

	/**
	 * create function for aretkcrea_add_new_correspondence_content
	 *
	 * ajax callback function aretkcrea_add_new_correspondence_content
	 *
	 * @return return crea agent correspondence content
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */

	function aretkcrea_add_new_correspondence_content() {
		global $wpdb;
		$postsTableName           = $wpdb->prefix . 'postmeta';
		$aretk_lead_id            = ! empty( $_POST['aretk_lead_id'] ) ? (INT) $_POST['aretk_lead_id'] : '';
		$crea_corrspondin_content = ! empty( $_POST['crea_corrsponding_content'] ) ? sanitize_text_field( $_POST['crea_corrsponding_content'] ) : '';

		if ( ! empty( $aretk_lead_id ) && $aretk_lead_id != '' ) {
			if ( ! empty( $crea_corrspondin_content ) && $crea_corrspondin_content != '' ) {
				$crea_corrspondin_content = stripslashes( $crea_corrspondin_content );
				/*$crea_corrspondin_content = str_replace( '"', '\"', $crea_corrspondin_content );*/
				$crea_corrspondin_content = json_encode( $crea_corrspondin_content );
				$crea_corrspondin_content = str_replace( '\r\n', '<br />', $crea_corrspondin_content );
				$crea_corrspondin_content = str_replace( '\n', '<br />', $crea_corrspondin_content );
				$crea_corrspondin_content = json_decode( $crea_corrspondin_content );

				$current_date             = date_i18n( 'Y-m-d H:i' );
				$new_corrsponding_key     = 'crea_lead_corrsponding_text' . rand( 100, 999 );
				$new_corrsponding_array   = array();
				$new_corrsponding_array[] = "";
				$new_corrsponding_array[] = $crea_corrspondin_content;
				$new_corrsponding_array[] = $current_date;
				$new_corrsponding_array[] = '';        # message subject
				$new_corrsponding_array[] = 'note';
				update_post_meta( $aretk_lead_id, $new_corrsponding_key, json_encode( $new_corrsponding_array ) );

				$current_date = date_i18n( 'Y-m-d H:i' );
				update_post_meta( $aretk_lead_id, "lead_contact_date", $current_date );
				$sql_select                      = "SELECT `meta_id`, `meta_key`, `meta_value` FROM `$postsTableName` WHERE `meta_key` LIKE 'crea_lead_corrsponding_text%' AND `post_id`= %d ORDER BY `meta_id` DESC";
				$sql_prep                        = $wpdb->prepare( $sql_select, $aretk_lead_id );
				$get_lead_corrsponding_ids_count = $wpdb->get_results( $sql_prep );

				if ( ! empty( $get_lead_corrsponding_ids_count ) && $get_lead_corrsponding_ids_count != '' ) {
					$html = '';
					$html .= '<div class="crea_all_corresponding_listing_contnent">';
					$html .= '<div class="admin_sub_heading">Correspondence History</div>';
					$html .= '<div class="set_all_corrsponding_result">';
					foreach ( $get_lead_corrsponding_ids_count as $get_lead_corrsponding_ids_key => $get_lead_corrsponding_ids_value ) {
						$corrsponding_messages = json_decode( $get_lead_corrsponding_ids_value->meta_value );
						if ( is_array( $corrsponding_messages ) ) {
							$corrsponding_message = $corrsponding_messages[1];
							$correspondace_date   = $corrsponding_messages[2];
							$corrsponding_subject = $corrsponding_messages[3];
							$corrsponding_type    = $corrsponding_messages[4];
						} else {
							$corrsponding_message = $corrsponding_messages;
							$correspondace_date   = '';
							$corrsponding_subject = '';
							$corrsponding_type    = '';
						}
						$html .= '
						<div class="crea_corrsponding_row">
							<div class="correspondence_genwrap">
								<a id="crea_add_corrsponding_delete' . esc_attr( $get_lead_corrsponding_ids_value->meta_id ) . '" class="crea_add_corrsponding_delete_action" href="javascript:void(0);"><img src="' . esc_url( ARETK_CREA_PLUGIN_URL ) . 'admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
								<div class="display_correpond_current_date"><span>' . esc_html( $correspondace_date ) . '</span></div>
								<div class="correspondence_type test01">' . wp_kses_post( $corrsponding_type ) . '</div>';
						if ( $corrsponding_type !== 'note' ) {
							$html .= '<div class="correspondence_subject"><span>' . __( 'Subject:', 'aretk-crea' ) . '</span>' . esc_html( $corrsponding_subject ) . '</div>
									<div class="correspondence_toggle">[ <a class="correspondence_toggle" href="#">' . __( 'view', 'aretk-crea' ) . '</a> ]</div>';
						}
						$html .= '</div>';
						$html .= '<div class="correspondence_detwrap correspondence_' . str_replace( ' ', '-', $corrsponding_type ) . '">' . wp_kses_post( $corrsponding_message ) . '</div>';
						$html .= '</div>';
					}
					$html .= '</div></div>';
					$crea_agent_id    = get_post_meta( $aretk_lead_id, 'crea_agent_id', true );
					$lead_phone_email = maybe_unserialize( get_post_meta( $aretk_lead_id, 'lead_phone_email', true ) );
					$PrimaryEmail     = get_post_meta( $aretk_lead_id, 'PrimaryEmail', true );
					$from             = '';
					if ( ! empty( $PrimaryEmail ) && isset( $PrimaryEmail ) ) {
						$from = $PrimaryEmail;
					} else {
						$from = $lead_phone_email[0];
					}
					echo $html;
				}
			} else {
				echo __( 'Please add correspondence text', 'aretk-crea' );
			}
		}
		die();
	}

	/**
	 * create function for aretkcrea_crea_remove_correspondence_content
	 *
	 * ajax callback function aretkcrea_crea_remove_correspondence_content
	 *
	 * @return return crea remove correspondence content
	 * @package Phase 1
	 * @since Phase 1
	 * @version 1.0.0
	 *
	 * @param null
	 */
	function aretkcrea_remove_correspondence_content() {
		global $wpdb;
		$remove_id         = isset( $_POST['remove_id'] ) ? (INT) $_POST['remove_id'] : '';
		$crea_lead_post_id = isset( $_POST['crea_lead_post_id'] ) ? (INT) $_POST['crea_lead_post_id'] : '';
		$table_name        = $wpdb->prefix . 'postmeta';

		if ( ! empty( $crea_lead_post_id ) && $crea_lead_post_id != '' ) {
			if ( ! empty( $remove_id ) && $remove_id != '' ) {
				$sql_select = "DELETE FROM `$table_name` WHERE `meta_id`= %d LIMIT 1";
				$sql_prep   = $wpdb->prepare( $sql_select, $remove_id );
				$wpdb->query( $sql_prep );

				$sql_select                      = "SELECT `meta_id`, `meta_key`, `meta_value` FROM `$table_name` WHERE `meta_key` LIKE 'crea_lead_corrsponding_text%' AND `post_id`= %d ORDER BY `meta_id` DESC";
				$sql_prep                        = $wpdb->prepare( $sql_select, $crea_lead_post_id );
				$get_lead_corrsponding_ids_count = $wpdb->get_results( $sql_prep );

				if ( ! empty( $get_lead_corrsponding_ids_count ) && $get_lead_corrsponding_ids_count != '' ) {
					$html = '';
					$html .= '<div class="crea_all_corresponding_listing_contnent">';
					$html .= '<div class="admin_sub_heading">Correspondence History</div>';
					$html .= '<div class="set_all_corrsponding_result">';
					foreach ( $get_lead_corrsponding_ids_count as $get_lead_corrsponding_ids_key => $get_lead_corrsponding_ids_value ) {
						$corrsponding_messages = json_decode( $get_lead_corrsponding_ids_value->meta_value );
						if ( is_array( $corrsponding_messages ) ) {
							$corrsponding_message = $corrsponding_messages[1];
							$correspondace_date   = $corrsponding_messages[2];
							$corrsponding_subject = $corrsponding_messages[3];
							$corrsponding_type    = $corrsponding_messages[4];
						} else {
							$corrsponding_message = $corrsponding_messages;
							$correspondace_date   = '';
							$corrsponding_subject = '';
							$corrsponding_type    = '';
						}
						$html .= '
						<div class="crea_corrsponding_row">
							<div class="correspondence_genwrap">
								<a id="crea_add_corrsponding_delete' . esc_attr( $get_lead_corrsponding_ids_value->meta_id ) . '" class="crea_add_corrsponding_delete_action" href="javascript:void(0);"><img src="' . esc_url( ARETK_CREA_PLUGIN_URL ) . 'admin/images/delete-icon.png" class = "delet_icon" alt="delete" width="20" height="20"></a>
								<div class="display_correpond_current_date"><span>' . esc_html( $correspondace_date ) . '</span></div>
								<div class="correspondence_type test02">' . esc_html( $corrsponding_type ) . '</div>';
						if ( $corrsponding_type !== 'note' ) {
							$html .= '<div class="correspondence_subject"><span>' . __( 'Subject:', 'aretk-crea' ) . '</span>' . esc_html( $corrsponding_subject ) . '</div>
									<div class="correspondence_toggle">[ <a class="correspondence_toggle" href="#">' . __( 'view', 'aretk-crea' ) . '</a> ]</div>';
						}
						$html .= '</div>';
						$html .= '<div class="correspondence_detwrap correspondence_' . str_replace( ' ', '-', $corrsponding_type ) . '">' . wp_kses_post( $corrsponding_message ) . '</div>';
						$html .= '</div>';
					}
					$html .= '</div></div>';
					echo $html;
				}
			}
		}
		die();
	}

	/**
	 * Function for Add button in Listing Lead section
	 *
	 * @param unknown_type $views
	 *
	 * @return unknown
	 */
	function aretkcrea_custom_button_for_lead_list( $views ) {
		$link_url_create_new_lead  = admin_url( 'admin.php?page=create_new_leads' );
		$link_url_send_email_lead  = admin_url( 'admin.php?page=send_email_leads' );
		$link_url_lead_category    = admin_url( 'edit-tags.php?taxonomy=lead-category' );
		$link_url_leads_form       = admin_url( 'admin.php?page=leads_form' );
		$link_url_import_leads     = admin_url( 'admin.php?page=import_leads' );
		$views['add-new-lead']     = '<a href="' . esc_url( $link_url_create_new_lead ) . '" id="add-new-lead" class="button button-primary aretk-add-new-lead">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_ADD_NEW_LEAD, ARETKCREA_PLUGIN_SLUG ) ) . '</a>';
		$views['send-email-leads'] = '<a href="' . esc_url( $link_url_send_email_lead ) . '" id="send-email" class="button button-primary send-email-leads">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_SEND_EMAIL, ARETKCREA_PLUGIN_SLUG ) ) . '</a>';
		$views['lead-category']    = '<a href="' . esc_url( $link_url_lead_category ) . '"><input type="button" id="lead_category" class="button button-primary aretk-lead-catrgory" value="' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_LEAD_CATEGORIES, ARETKCREA_PLUGIN_SLUG ) ) . '"></a>';
		$views['leadforms']        = '<a href="' . esc_url( $link_url_leads_form ) . '" id="lead-forms" class="button button-primary aretk-leadforms">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_LEAD_FORMS, ARETKCREA_PLUGIN_SLUG ) ) . '</a></li>';
		$views['import-lead']      = '<a href="' . esc_url( $link_url_import_leads ) . '" id="import-lead-csv" class="button button-primary aretk-import-lead">' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_IMPORT_LEADS, ARETKCREA_PLUGIN_SLUG ) ) . '</a>';
		$views['export-lead']      = '<a href="#" ><input type ="button" id="export-lead-csv" class="button button-primary aretk-export-lead" value="' . strtoupper( __( ARETKCREA_LEADS_BTN_TXT_EXPORT_LEADS, ARETKCREA_PLUGIN_SLUG ) ) . '" ></a>';
		$views['download-lead']    = '<div class = "download-export-csv" style="display:none;"></div>';

		return $views;
	}

	// Function to create necessary plugin pages

	/**
	 * Change aretk_lead edit link
	 *
	 */
	function aretkcrea_edit_aretk_post_link( $url, $post_id, $context ) {
		$aretk_lead = isset( $_REQUEST['post_type'] ) ? sanitize_text_field( $_REQUEST['post_type'] ) : '';
		$post_id    = (INT) $post_id;
		if ( $aretk_lead == 'aretk_lead' ) {
			$url = admin_url( 'admin.php?page=create_new_leads&ID=' . $post_id . '&action=edit' );
		} elseif ( $aretk_lead == 'aretk_listing' ) {
			$url = admin_url( 'admin.php?page=create_new_listings&id=' . $post_id );
		}

		return $url;
	}

	/**
	 * Function is used for create lead
	 */
	public function aretkcrea_handle_create_lead_form_action() {
		global $wpdb, $wp;
		$lead_id      = ! empty( $_POST['aretk-lead-id'] ) ? (INT) $_POST['aretk-lead-id'] : '';
		$posttype     = ! empty( $_POST['posttype'] ) ? sanitize_text_field( $_POST['posttype'] ) : '';
		$action       = ! empty( $_POST['action'] ) ? sanitize_text_field( $_POST['action'] ) : '';
		$action_which = ! empty( $_POST['action-which'] ) ? sanitize_text_field( $_POST['action-which'] ) : '';
		$lead_name    = ! empty( $_POST['create_lead_name'] ) ? sanitize_text_field( $_POST['create_lead_name'] ) : '';

		$create_lead_email_arr = ! empty( $_POST['create_lead_phone_email'] ) ? (array) $_POST['create_lead_phone_email'] : array();
		$create_lead_email     = array();
		foreach ( $create_lead_email_arr as $email ) {
			$create_lead_email[] = sanitize_email( $email );
		}

		if ( $posttype === 'lead' && $action === 'lead-form' && ! empty( $lead_name ) && ! empty( $create_lead_email ) && ( $action_which === 'add' || $action_which === 'edit' ) ) {
			$crea_agent_id              = ! empty( $_POST['crea_agent_id'] ) ? (INT) $_POST['crea_agent_id'] : '';
			$PrimaryEmail               = ! empty( $_POST['PrimaryEmail'] ) ? sanitize_email( $_POST['PrimaryEmail'] ) : '';
			$lead_company               = ! empty( $_POST['create_lead_company'] ) ? sanitize_text_field( $_POST['create_lead_company'] ) : '';
			$create_lead_address_line1  = ! empty( $_POST['create_lead_address_line1'] ) ? sanitize_text_field( $_POST['create_lead_address_line1'] ) : '';
			$create_lead_province       = ! empty( $_POST['create_lead_province'] ) ? sanitize_text_field( $_POST['create_lead_province'] ) : '';
			$create_lead_city           = ! empty( $_POST['create_lead_city'] ) ? sanitize_text_field( $_POST['create_lead_city'] ) : '';
			$create_lead_country        = ! empty( $_POST['create_lead_country'] ) ? sanitize_text_field( $_POST['create_lead_country'] ) : '';
			$comment                    = ! empty( $_POST['comment'] ) ? sanitize_text_field( $_POST['comment'] ) : '';
			$crea_new_lead_category_arr = ! empty( $_POST['new_lead_category'] ) ? (array) $_POST['new_lead_category'] : array();
			$crea_new_lead_category     = array();
			foreach ( $crea_new_lead_category_arr as $leadCategory ) {
				$crea_new_lead_category[] = sanitize_text_field( $leadCategory );
			}
			$crea_agent_phone_type_arr = ! empty( $_POST['crea_agent_phone_type'] ) ? (array) $_POST['crea_agent_phone_type'] : array();
			$crea_agent_phone_type     = array();
			$phoneTypeOptions          = array( 'Home', 'Mobile', 'Fax' );
			foreach ( $crea_agent_phone_type_arr as $phoneType ) {
				if ( in_array( $phoneType, $phoneTypeOptions ) ) {
					$crea_agent_phone_type[] = $phoneType;
				}
			}
			$create_lead_phone_no_arr = ! empty( $_POST['create_lead_phone_no'] ) ? (array) $_POST['create_lead_phone_no'] : array();
			$create_lead_phone_no     = array();
			foreach ( $create_lead_phone_no_arr as $phoneNum ) {
				$create_lead_phone_no[] = preg_replace( '/[^0-9]/', '', $phoneNum );
			}
			$create_lead_social_url_arr = ! empty( $_POST['create_lead_social_url'] ) ? (array) $_POST['create_lead_social_url'] : array();
			$create_lead_social_url     = array();
			foreach ( $create_lead_social_url_arr as $url_social ) {
				$create_lead_social_url[] = esc_url( $url_social );
			}
			$crea_agent_social_type_arr = ! empty( $_POST['crea_agent_social_type'] ) ? ( $_POST['crea_agent_social_type'] ) : array();
			$crea_agent_social_type     = array();
			$socialTypeOptions          = array(
				'Facebook',
				'Instagram',
				'LinkedIn',
				'Pinterest',
				'Twitter',
				'YouTube'
			);
			foreach ( $crea_agent_social_type_arr as $socialType ) {
				if ( in_array( $socialType, $socialTypeOptions ) ) {
					$crea_agent_social_type[] = $socialType;
				}
			}

			$merge_phonetype = array();
			$op_counter      = 0;
			foreach ( $crea_agent_phone_type as $crea_agent_phone_type_val ) {
				$phonetype         = $crea_agent_phone_type[ $op_counter ];
				$phoneno           = $create_lead_phone_no[ $op_counter ];
				$merge_phonetype[] = array( 'PhoneType' => $phonetype, 'PhoneNo' => $phoneno );
				$op_counter ++;
			}

			$merge_social   = array();
			$op_counter_new = 0;
			foreach ( $crea_agent_social_type as $crea_agent_social_type_val ) {
				$socialtype     = $crea_agent_social_type[ $op_counter_new ];
				$sociallink     = $create_lead_social_url[ $op_counter_new ];
				$merge_social[] = array( 'SocialType' => $socialtype, 'SocialLink' => $sociallink );
				$op_counter_new ++;
			}

			if ( empty( $PrimaryEmail ) ) {
				$PrimaryEmail = $create_lead_email[0];
			}
			if ( $action_which === 'add' && empty( $lead_id ) ) {
				$new_post = array(
					'post_title'   => $lead_name,
					'post_content' => $comment,
					'post_status'  => 'publish',
					'post_type'    => 'aretk_lead'
				);
				$lead_id  = wp_insert_post( $new_post );
			} elseif ( $action_which === 'edit' && ! empty( $lead_id ) ) {
				$update_lead_post_content = array(
					'ID'           => $lead_id,
					'post_title'   => $lead_name,
					'post_content' => $comment
				);
				wp_update_post( $update_lead_post_content );

				$lead_form_type              = get_post_meta( $lead_id, 'lead_form_type', $single = false );
				$current_user_lead_form_type = array();
				foreach ( $lead_form_type as $current_user_lead_form ) {
					$current_user_lead_form_type[] = $current_user_lead_form;
				}
				update_post_meta( $lead_id, 'lead_form_type', $current_user_lead_form_type );
			}

			update_post_meta( $lead_id, 'crea_agent_id', $crea_agent_id );
			update_post_meta( $lead_id, 'lead_phone_no', maybe_serialize( $merge_phonetype ) );
			update_post_meta( $lead_id, 'lead_phone_email', maybe_serialize( $create_lead_email ) );
			update_post_meta( $lead_id, 'create_lead_company', $lead_company );
			update_post_meta( $lead_id, 'lead_address_line', $create_lead_address_line1 );
			update_post_meta( $lead_id, 'lead_province', $create_lead_province );
			update_post_meta( $lead_id, 'lead_city', $create_lead_city );
			update_post_meta( $lead_id, 'create_lead_country', $create_lead_country );
			update_post_meta( $lead_id, 'agent_social_type', maybe_serialize( $merge_social ) );
			update_post_meta( $lead_id, 'lead_form_type', 'admin' );
			update_post_meta( $lead_id, 'lead_primary_email', $PrimaryEmail );
			update_post_meta( $lead_id, 'lead_comment', $comment );
			wp_set_object_terms( $lead_id, $crea_new_lead_category, 'lead-category' );
			$link_url = admin_url( 'admin.php?page=create_new_leads&ID=' . $lead_id . '&action=edit' );
		} else {
			$link_url = admin_url( 'admin.php?page=create_new_leads' );
		}
		wp_safe_redirect( $link_url );
		die();
	}

	// Default Listing Details Settings AJAX

	public function aretkcrea_create_listing_detail_page() {
		$pages               = get_pages();
		$listings_page       = array(
			'slug'    => 'listing-details',
			'title'   => 'Listing Details',
			'content' => '[ARETK-LDS]'
		);
		$search_results_page = array(
			'slug'    => 'search-results',
			'title'   => 'Search Results',
			'content' => '[ARTEK-DLS]'
		);

		// search results page
		foreach ( $pages as $page ) {
			$apage = $page->post_name;
			if ( $apage == 'search-results' ) {
				$search_results_page_found = '1';
				break;
			} else {
				$search_results_page_found = '0';
			}
		}
		if ( ( $search_results_page_found != '1' ) && ( ( !function_exists( 'pll_default_language' ) ) || (function_exists( 'pll_default_language' ) && ( pll_default_language() === pll_current_language() ) ) ) ) {
			$page_id = wp_insert_post( array(
				'post_title'   => $search_results_page['title'],
				'post_type'    => 'page',
				'post_name'    => $search_results_page['slug'],
				'post_content' => $search_results_page['content'],
				'post_status'  => 'publish',
			) );
		}
		// listing detail page
		foreach ( $pages as $page ) {
			$apage = $page->post_name;
			if ( $apage == 'listing-details' ) {
				$listing_detail_found = '1';
				break;
			} else {
				$listing_detail_found = '0';
			}
		}
		if ( ( $listing_detail_found != '1' ) && ( ( !function_exists( 'pll_default_language' ) ) || (function_exists( 'pll_default_language' ) && ( pll_default_language() === pll_current_language() ) ) ) ) {
			$page_id = wp_insert_post( array(
				'post_title'   => $listings_page['title'],
				'post_type'    => 'page',
				'post_name'    => $listings_page['slug'],
				'post_content' => $listings_page['content'],
				'post_status'  => 'publish',
			) );
		}
		$post_id = ! empty( $_GET['post'] ) ? (INT) $_GET['post'] : '';
		if ( ! empty( $post_id ) ) {
			$post = get_post( $post_id );
			$slug = $post->post_name;
			if ( $slug === 'listing-details' || $slug === 'search-results' ) {
				remove_post_type_support( 'page', 'editor' );
				remove_meta_box( 'authordiv', 'page', 'normal' );        // removes comments status
				remove_meta_box( 'categorydiv', 'page', 'normal' );    // removes comments
				remove_meta_box( 'commentstatusdiv', 'page', 'normal' );// removes author
				remove_meta_box( 'commentsdiv', 'page', 'normal' );    // removes Comments metabox
				remove_meta_box( 'postexcerpt', 'page', 'normal' );    // removes Excerpt metabox
				remove_meta_box( 'postimagediv', 'page', 'side' );        // removes Featured image metabox
				remove_meta_box( 'slugdiv', 'page', 'normal' );        // removes Slug metabox
				remove_meta_box( 'trackbacksdiv', 'page', 'normal' );    // removes Trackbacks metabox
			}
		}
	}


	// Default listings Showcase settings AJAX

	public function aretkcrea_restrict_post_deletion( $post_id ) {
		if ( ! empty( $post_id ) && is_int( $post_id ) ) {
			$property_detail      = get_post( $post_id );
			$property_detail_slug = $property_detail->post_name;
			if ( $property_detail_slug == 'listing-details' || $property_detail_slug == 'search-results' ) {
				die( 'The page you were trying to delete is protected.  Click the back button to return to the previous page.' );
			}
		}
	}

	// function for ajax call - map listing

	function aretkcrea_add_listing_showcase_changes() {
		$get_include_information            = $_POST['get_include_information'] === 'Yes' ? 'Yes' : 'No';
		$get_include_contact_form           = $_POST['get_include_contact_form'] === 'Yes' ? 'Yes' : 'No';
		$get_include_map                    = $_POST['get_include_map'] === 'Yes' ? 'Yes' : 'No';
		$get_include_walk_score             = $_POST['get_include_walk_score'] === 'Yes' ? 'Yes' : 'No';
		$get_include_print_button           = $_POST['get_include_print_button'] === 'Yes' ? 'Yes' : 'No';
		$email_address_of_agent             = $_POST['get_include_email_address_of_agent'] === 'Yes' ? 'Yes' : 'No';
		$get_crea_listing_price_color_id    = isset( $_POST['get_crea_listing_price_color_id'] ) ? sanitize_hex_color_no_hash( $_POST['get_crea_listing_price_color_id'] ) : '';
		$get_crea_listing_send_btn_color_id = isset( $_POST['get_crea_listing_send_btn_color_id'] ) ? sanitize_hex_color_no_hash( $_POST['get_crea_listing_send_btn_color_id'] ) : '';
		update_option( 'crea_listing_include_information', $get_include_information );
		update_option( 'crea_listing_include_contact_form', $get_include_contact_form );
		update_option( 'crea_listing_include_map', $get_include_map );
		update_option( 'crea_listing_include_walk_score', $get_include_walk_score );
		update_option( 'crea_listing_include_print_btn', $get_include_print_button );
		update_option( 'crea_listing_include_email_address_of_agent', $email_address_of_agent );
		update_option( 'crea_listing_include_price_color', $get_crea_listing_price_color_id );
		update_option( 'crea_listing_include_send_btn_color', $get_crea_listing_send_btn_color_id );
		die();
	}

	//Default Listings Search Showcase settings

	function aretkcrea_add_default_listing_setting() {
		$DefaultlistingTextColor                        = isset( $_POST['DefaultlistingTextColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingTextColor'] ) : '';
		$DefaultlistingAddressbarColor                  = isset( $_POST['DefaultlistingAddressbarColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingAddressbarColor'] ) : '';
		$DefaultlistingPriceColor                       = isset( $_POST['DefaultlistingPriceColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingPriceColor'] ) : '';
		$DefaultlistingStatusColor                      = isset( $_POST['DefaultlistingStatusColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingStatusColor'] ) : '';
		$DefaultlistingopenhouseColor                   = isset( $_POST['DefaultlistingopenhouseColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingopenhouseColor'] ) : '';
		$DefaultlistingStatusTextColor                  = isset( $_POST['DefaultlistingStatusTextColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingStatusTextColor'] ) : '';
		$DefaultlistingopenhouseTextColor               = isset( $_POST['DefaultlistingopenhouseTextColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingopenhouseTextColor'] ) : '';
		$Defaultlistinglisting_pagination_color_id      = isset( $_POST['DefaultlistingpaginationColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingpaginationColor'] ) : '';
		$Defaultlistinglisting_pagination_text_color_id = isset( $_POST['DefaultlistingpaginationtextColor'] ) ? sanitize_hex_color_no_hash( $_POST['DefaultlistingpaginationtextColor'] ) : '';
		$Defaultlisting_Openhouseyes_or_not             = $_POST['get_openHouse'] === 'yes' ? 'yes' : '';
		$Defaultlistingstatus_yes_or_not                = $_POST['get_status'] === 'yes' ? 'yes' : '';
		$Defaultlistingsearchbar_yes_or_not             = $_POST['get_search'] === 'yes' ? 'yes' : '';
		update_option( 'crea_default_listing_text', $DefaultlistingTextColor );
		update_option( 'crea_default_listing_address_bar_listing_include', $DefaultlistingAddressbarColor );
		update_option( 'crea_default_listing_price_color', $DefaultlistingPriceColor );
		update_option( 'crea_default_listing_status_color', $DefaultlistingStatusColor );
		update_option( 'crea_default_listing_openhouse_color', $DefaultlistingopenhouseColor );
		update_option( 'crea_default_listing_status_text_color', $DefaultlistingStatusTextColor );
		update_option( 'crea_default_listing_openhouse_text_color', $DefaultlistingopenhouseTextColor );
		update_option( 'crea_default_listing_status_yes_or_not', $Defaultlistingstatus_yes_or_not );
		update_option( 'crea_default_listing_openhouse_yes_or_not', $Defaultlisting_Openhouseyes_or_not );
		update_option( 'crea_default_listing_searchbar_yes_or_not', $Defaultlistingsearchbar_yes_or_not );
		update_option( 'crea_default_listing_pagination_color_id_yes_or_not', $Defaultlistinglisting_pagination_color_id );
		update_option( 'crea_default_listing_pagination_text_color_id_yes_or_not', $Defaultlistinglisting_pagination_text_color_id );
		die();
	}

	function aretkcrea_map_listing() {
		global $wpdb;

		$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );

		if ( $getSubscriptionStatus === 'valid' ) {
			$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
			$subscriptionKey    = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';

			$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
			if ( ! empty( $domainName ) ) {
				$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
			} else {
				$domainName = get_site_url();
				$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
			}
			$domainName = str_replace( 'http://', '', $domainName );

			$property_id          = ! empty( $_POST['property_id'] ) ? (INT) base64_decode( $_POST['property_id'] ) : '';
			$property_latitude    = ! empty( $_POST['property_latitude'] ) ? sanitize_text_field( base64_decode( $_POST['property_latitude'] ) ) : '';
			$property_longitude   = ! empty( $_POST['property_longitude'] ) ? sanitize_text_field( base64_decode( $_POST['property_longitude'] ) ) : '';
			$property_latitude    = (float) preg_replace( '/[^\d\-.]+/', '', $property_latitude );
			$property_longitude   = (float) preg_replace( '/[^\d\-.]+/', '', $property_longitude );
			$property_pov_heading = isset( $_POST['property_pov_heading'] ) ? sanitize_text_field( base64_decode( $_POST['property_pov_heading'] ) ) : '';
			$property_pov_heading = (float) preg_replace( '/[^\d\-.]+/', '', $property_pov_heading );
			$property_pov_pitch   = isset( $_POST['property_pov_pitch'] ) ? sanitize_text_field( base64_decode( $_POST['property_pov_pitch'] ) ) : '';
			$property_pov_pitch   = (float) preg_replace( '/[^\d\-.]+/', '', $property_pov_pitch );
			$property_pov_zoom    = isset( $_POST['property_pov_zoom'] ) ? (INT) base64_decode( $_POST['property_pov_zoom'] ) : '';
			$userNameList         = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'list' );
			$agent_id_array       = array();
			$agent_ids_list       = null;
			$agent_ids_list       = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'list' );
			if ( ! empty( $userNameList ) && ! empty( $agent_ids_list ) && is_numeric( $property_latitude ) && is_numeric( $property_longitude ) && is_numeric( $property_pov_heading ) && is_numeric( $property_pov_pitch ) && is_numeric( $property_pov_zoom ) ) {
				$edit_listing_settinges_array = array(
					"id"                   => $property_id,
					"userNameList"         => $userNameList,
					"agent_cid_list"       => $agent_ids_list,
					"geocoded_latitude"    => $property_latitude,
					"geocoded_longitude"   => $property_longitude,
					"geocoded_pov_heading" => $property_pov_heading,
					"geocoded_pov_pitch"   => $property_pov_pitch,
					"geocoded_pov_zoom"    => $property_pov_zoom
				);
				$post_string                  = http_build_query( $edit_listing_settinges_array );
				$editListing                  = curl_init();
				curl_setopt( $editListing, CURLOPT_HEADER, 0 );
				curl_setopt( $editListing, CURLOPT_VERBOSE, 0 );
				curl_setopt( $editListing, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $editListing, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=map_listing" );
				curl_setopt( $editListing, CURLOPT_POST, true );
				curl_setopt( $editListing, CURLOPT_POSTFIELDS, $post_string );
				curl_setopt( $editListing, CURLOPT_REFERER, $domainName );
				$editListingCurlExecute = curl_exec( $editListing );
				curl_close( $editListing );
				$editListingCurlExecuteResponse = ( $editListingCurlExecute ) . PHP_EOL;
				$responseDecode                 = json_decode( $editListingCurlExecuteResponse );
				if ( isset( $responseDecode->code ) && ! empty( $responseDecode->code ) ) {
					if ( $responseDecode->code === 200 && $responseDecode->status === 'success' ) {
						echo __( 'pass', 'aretk-crea' );
					} else {
						echo __( 'fail - Update error', 'aretk-crea' );
					}
				} else {
					echo __( 'fail - Connection Error', 'aretk-crea' );
				}
			} else {
				echo __( 'fail - No CREA feeds found', 'aretk-crea' );
			}
		} else {
			echo __( 'fail - ARETK Subscription API not valid', 'aretk-crea' );
		}
		die();
	}

	function aretkcrea_add_search_listing_detail_showcase_changes() {
		$search_feed_id                                = isset( $_POST['search_feed_id'] ) ? sanitize_text_field( $_POST['search_feed_id'] ) : '';
		$get_crea_search_inc_exc_listing_feed          = $_POST['search_inc_exc_listing_feed'] === 'yes' ? 'yes' : '';
		$get_crea_search_exclude_field_property_type   = $_POST['search_exclude_field_property_type'] === 'Property Type' ? 'Property Type' : '';
		$get_crea_search_exclude_field_ownership_type   = $_POST['search_exclude_field_ownership_type'] === 'Ownership Type' ? 'Ownership Type' : 'false';
		$get_search_exclude_field_status               = $_POST['search_exclude_field_status'] === 'Status' ? 'Status' : '';
		$get_search_exclude_field_bedrooms             = $_POST['search_exclude_field_bedrooms'] === 'Bedrooms' ? 'Bedrooms' : '';
		$get_search_exclude_field_bathrooms_full       = $_POST['search_exclude_field_bathrooms_full'] === 'Bathrooms Full' ? 'Bathrooms Full' : '';
		$get_crea_max_price_range                      = ! empty( $_POST['search_max_price_ranger_value'] ) ? (int) $_POST['search_max_price_ranger_value'] : '';
		$get_crea_search_detail_title_color_id         = isset( $_POST['crea_search_detail_title_color_id'] ) ? sanitize_hex_color_no_hash( $_POST['crea_search_detail_title_color_id'] ) : '';
		$get_crea_search_detail_button_color_id        = isset( $_POST['crea_search_detail_button_color_id'] ) ? sanitize_hex_color_no_hash( $_POST['crea_search_detail_button_color_id'] ) : '';
		$search_list_count                             = isset( $_POST['search_list_count'] ) ? (int) $_POST['search_list_count'] : '';
		$aretkcrea_showcase_search_advancefilterclosed = $_POST['aretkcrea_showcase_search_advancefilterclosed'] === 'yes' ? 'yes' : 'no';
		// Not being used currently
		//$get_search_exclude_field_finished_basement = isset($_POST['search_exclude_field_finished_basement']) ? $_POST['search_exclude_field_finished_basement'] : '';
		//$get_search_exclude_field_select_city = isset($_POST['search_exclude_field_select_city']) ? $_POST['search_exclude_field_select_city'] : '';
		//$get_crea_select_result_layout = isset($_POST['select_result_layout']) ? $_POST['select_result_layout'] : '';
		update_option( 'crea_search_feed_id', $search_feed_id );
		update_option( 'crea_search_inc_exc_listing_feed', $get_crea_search_inc_exc_listing_feed );
		update_option( 'crea_search_exclude_field_property_type', $get_crea_search_exclude_field_property_type );
		update_option( 'crea_search_exclude_field_ownership_type', $get_crea_search_exclude_field_ownership_type );
		update_option( 'crea_search_exclude_field_structure', $get_search_exclude_field_structure );
		update_option( 'crea_search_exclude_field_status', $get_search_exclude_field_status );
		update_option( 'crea_search_exclude_field_bedrooms', $get_search_exclude_field_bedrooms );
		update_option( 'crea_search_exclude_field_bathrooms_full', $get_search_exclude_field_bathrooms_full );
		update_option( 'crea_search_max_price_slider_range', $get_crea_max_price_range );
		update_option( 'crea_search_detail_title_color_id', $get_crea_search_detail_title_color_id );
		update_option( 'crea_search_detail_button_color_id', $get_crea_search_detail_button_color_id );
		update_option( 'select_result_layout_counter', $search_list_count );
		update_option( 'aretkcrea_showcase_search_advancefilterclosed', $aretkcrea_showcase_search_advancefilterclosed );
		// Not being used currently
		//update_option('crea_search_exclude_field_finished_basement', $get_search_exclude_field_finished_basement);
		//update_option('crea_search_exclude_field_select_city',$get_search_exclude_field_select_city);
		//update_option('crea_select_result_layout',$get_crea_select_result_layout);
		die();
	}

	/**
	 * Disable Month Dropdown from Custom Lead
	 *
	 * @param unknown_type $status
	 * @param unknown_type $post_type
	 *
	 * @return unknown
	 */
	public function aretkcrea_filter_disable_months_dropdown_custom( $status, $post_type ) {
		if ( $post_type == 'aretk_lead' ) {
			$status = true;
		} else {
			$status = false;
		}

		return $status;
	}

	/**
	 * Add filter options to the leads page
	 *
	 */
	public function aretkcrea_restrict_manage_posts_custom_for_lead() {
		global $wpdb, $post, $typenow, $wp_query;

		$crea_agent_table_name           = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
		$lead_form_type_general_selected = '';
		$lead_form_type_seller_selected  = '';
		$lead_form_type_buyer_selected   = '';
		$lead_form_type_selected         = '';
		$lead_agent_selected             = '';
		$selected_category_name          = '';

		if ( $typenow == 'aretk_lead' ) {

			if ( ! empty( $_GET['lead_form_type'] ) && isset( $_GET['lead_form_type'] ) ) {
				if ( $_GET['lead_form_type'] == 'general' ) {
					$lead_form_type_general_selected = 'selected';
				} elseif ( $_GET['lead_form_type'] == 'seller' ) {
					$lead_form_type_seller_selected = 'selected';
				} elseif ( $_GET['lead_form_type'] == 'buyer' ) {
					$lead_form_type_buyer_selected = 'selected';
				} else {
					$lead_form_type_selected = 'selected';
				}
			}

			$output = '';
			$output .= '<select name="lead_form_type" id="lead_form_type" class="postform">';
			$output .= '<option ' . esc_attr( $lead_form_type_selected ) . ' value="selectform">' . __( 'Select Form', 'aretk-crea' ) . '</option>';
			$output .= '<option ' . esc_attr( $lead_form_type_buyer_selected ) . ' value="buyer">' . __( 'Buyers Form', 'aretk-crea' ) . '</option>';
			$output .= '<option ' . esc_attr( $lead_form_type_seller_selected ) . ' value="seller">' . __( 'Sellers Form', 'aretk-crea' ) . '</option>';
			$output .= '<option ' . esc_attr( $lead_form_type_general_selected ) . ' value="general">' . __( 'General Contact Form', 'aretk-crea' ) . '</option>';
			$output .= '</select>';

			$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
			if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				$output .= '<select id="lead_agent_name" class="postform" name="lead_agent_name">';
				$output .= '<option value="selectagentid">Select Agent Name</option>';
				$sql_select          = "SELECT * FROM `$crea_agent_table_name`";
				$sql_prep            = $wpdb->prepare( $sql_select, null );
				$get_lead_agent_name = $wpdb->get_results( $sql_prep );

				if ( ! empty( $get_lead_agent_name ) && $get_lead_agent_name != '' ) {
					foreach ( $get_lead_agent_name as $get_lead_agent_name_value ) {
						$lead_agent_selected = '';
						if ( ! empty( $_GET['lead_agent_name'] ) && isset( $_GET['lead_agent_name'] ) ) {
							if ( $_GET['lead_agent_name'] == $get_lead_agent_name_value->crea_agent_id ) {
								$lead_agent_selected = 'selected';
							}
						}
						$output .= '<option ' . esc_attr( $lead_agent_selected ) . ' value="' . esc_attr( $get_lead_agent_name_value->crea_agent_id ) . '">' . esc_html( $get_lead_agent_name_value->crea_agent_name ) . '</option>';
					}
				}
				$output .= '</select>';
			}

			$taxonomy  = 'lead-category';
			$term_args = array(
				'hide_empty' => false,
				'orderby'    => 'name',
				'order'      => 'ASC',
				'post_type'  => 'aretk_lead',
			);
			$tax_terms = get_terms( $taxonomy, $term_args );
			$output .= '<select id="lead_category" class="postform" name="lead_category_name">';
			$output .= '<option value="selectcategoryname">Select Lead Category</option>';
			$category_name_filter_selected = isset( $_GET['lead_category_name'] ) ? sanitize_text_field( $_GET['lead_category_name'] ) : '';
			foreach ( $tax_terms as $lead_category_name ) {
				$selected_category_name = '';
				if ( $category_name_filter_selected == $lead_category_name->name ) {
					$selected_category_name = 'selected';
				}
				$output .= '<option ' . esc_attr( $selected_category_name ) . ' value="' . esc_attr( $lead_category_name->name ) . '">' . esc_html( $lead_category_name->name ) . '</option>';
			}
			$output .= '</select>';
			echo $output;
		}
	}

	/**
	 * Prepare Query based on Select form
	 * This hook is called after the query variable object is created, but before the actual query is run.
	 */
	public function aretkcrea_pre_get_posts_custom_for_lead( $query ) {
		global $post_type, $pagenow;

		//if we are currently on the edit screen of the post type listings
		if ( $pagenow == 'edit.php' && $post_type == 'aretk_lead' ) {
			if ( isset( $_GET['lead_form_type'] ) ) {
				//get the lead form type
				$lead_form_type = ! empty( $_GET['lead_form_type'] ) ? sanitize_text_field( $_GET['lead_form_type'] ) : '';

				//get the agent name
				$lead_agent_name = ! empty( $_GET['lead_agent_name'] ) ? sanitize_text_field( $_GET['lead_agent_name'] ) : '';

				//get the category name
				$lead_category_name = ! empty( $_GET['lead_category_name'] ) ? sanitize_text_field( $_GET['lead_category_name'] ) : '';

				//if the post format is not 0 (which means all)
				if ( $lead_form_type != 'selectform' && $lead_form_type != '' ) {
					$query->query_vars['meta_query'] = array(
						array(
							'key'     => 'lead_form_type',
							'value'   => $lead_form_type,
							'compare' => '='
						)
					);
				}
				if ( $lead_category_name != 'selectcategoryname' && $lead_category_name != '' ) {
					$query->query_vars['tax_query'] = array(
						array(
							'taxonomy' => 'lead-category',
							'field'    => 'name',
							'terms'    => $lead_category_name
						)
					);
				}
				//set meta query for filter agent id
				if ( $lead_agent_name != 'selectagentid' && $lead_agent_name != '' ) {
					$query->query_vars['meta_query'] = array(
						array(
							'key'     => 'crea_agent_id',
							'value'   => $lead_agent_name,
							'compare' => '='
						)
					);
				}
				//filter both query agent and form
				if ( $lead_agent_name != 'selectagentid' && $lead_form_type != 'selectform' && $lead_form_type != '' && $lead_agent_name != '' ) {
					$query->query_vars['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => 'lead_form_type',
							'value'   => $lead_form_type,
							'compare' => '='
						),
						array(
							'key'     => 'crea_agent_id',
							'value'   => $lead_agent_name,
							'compare' => '='
						)
					);
				}
				if ( $lead_agent_name != 'selectagentid' && $lead_category_name != 'selectcategoryname' && $lead_agent_name != '' && $lead_category_name != '' ) {
					$query->query_vars['meta_query'] = array(
						array(
							'key'     => 'crea_agent_id',
							'value'   => $lead_agent_name,
							'compare' => '='
						)
					);
					$query->query_vars['tax_query']  = array(
						array(
							'taxonomy' => 'lead-category',
							'field'    => 'name',
							'terms'    => $lead_category_name
						)
					);
				}
				// two table lead form and category form is selected
				if ( $lead_form_type != 'selectform' && $lead_form_type != '' && $lead_category_name != 'selectcategoryname' && $lead_category_name != '' ) {

					$query->query_vars['meta_query'] = array(
						array(
							'key'     => 'lead_form_type',
							'value'   => $lead_form_type,
							'compare' => '='
						)
					);
					$query->query_vars['tax_query']  = array(
						array(
							'taxonomy' => 'lead-category',
							'field'    => 'name',
							'terms'    => $lead_category_name
						)
					);
				}
				// if three box are selected
				if ( $lead_form_type != 'selectform' && $lead_form_type != '' && $lead_category_name != 'selectcategoryname' && $lead_category_name != '' && $lead_agent_name != 'selectagentid' && $lead_agent_name != '' ) {
					$query->query_vars['meta_query'] = array(
						'relation' => 'AND',
						array(
							'key'     => 'lead_form_type',
							'value'   => $lead_form_type,
							'compare' => '='
						),
						array(
							'key'     => 'crea_agent_id',
							'value'   => $lead_agent_name,
							'compare' => '='
						)
					);
					$query->query_vars['tax_query']  = array(
						array(
							'taxonomy' => 'lead-category',
							'field'    => 'name',
							'terms'    => $lead_category_name
						)
					);
				}
			}
		}
	}

	/**
	 * Bulk Edit action
	 * Creates the bulk options on the leads page
	 */
	public function aretkcrea_custom_bulk_edit_action_for_aretk_lead( $actions ) {
		$actions['email'] = 'Email';
		unset( $actions['edit'] );

		return $actions;
	}

	/**
	 * Custom Lead Admin footer
	 *
	 */
	public function aretkcrea_custom_lead_admin_footer() {
		global $post_type;

		if ( $post_type == 'aretk_lead' ) {
			/*
			// Commented out as this is already set, keeping for now in case scenario found where this is needed..
			?>
			<script type="text/javascript">
			  jQuery(document).ready(function() {
				//jQuery('<option>').val('email').text('<?php _e('Email')?>').appendTo("select[name='action']");
				//jQuery('<option>').val('email').text('<?php _e('Email')?>').appendTo("select[name='action2']");
			  });
			</script><?php
			*/
		}
	}

	/**
	 * Set Lead ordering
	 * hook is called after the query variable object is created, but before the actual query is run.
	 */
	public function aretkcrea_lead_post_type_ordering( $wp_query ) {
		global $wpdb;

		$post_type = isset( $wp_query->query['post_type'] ) ? $wp_query->query['post_type'] : '';

		if ( $post_type == 'aretk_lead' ) {
			if ( ! empty( $_GET['orderby'] ) && ! empty( $_GET['order'] ) ) {
				$wp_query->set( 'orderby', sanitize_text_field( $_GET['orderby'] ) );
				$wp_query->set( 'order', sanitize_text_field( $_GET['order'] ) );
			} else {
				$wp_query->set( 'orderby', 'post_modified' );
				$wp_query->set( 'order', 'DESC' );
			}
		}
	}

	/**
	 * add class(es) into body class
	 *
	 */
	public function aretkcrea_admin_body_classes( $classes ) {
		$classes .= ' aretk';
		$page = ! empty( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		$id   = ! empty( $_GET['id'] ) ? (INT) ( $_GET['id'] ) : '';
		if ( $page === 'listings_settings' && is_numeric( $id ) ) {
			$classes .= ' aretk-geocode-listing';
		}

		return $classes;
	}

	/**
	 * Custom Email Bulk action
	 *
	 */
	public function aretkcrea_custom_email_bulk_action() {
		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$action        = $wp_list_table->current_action();

		if ( isset( $action ) && $action == 'email' ) {
			$all_selected_email = isset( $_REQUEST['post'] ) ? $_REQUEST['post'] : '';

			if ( ! empty( $all_selected_email ) ) {
				$all_bcc_email = array();
				foreach ( $all_selected_email as $all_custom_selected_email ) {
					$all_bcc_email[] = (int) $all_custom_selected_email;
				}
				update_option( 'selected_lead_post_email_to_bcc', $all_bcc_email );
				wp_safe_redirect( admin_url( 'admin.php?page=send_email_leads&email=bcc' ) );
				exit();
			}
		}
	}
	// END Function aretkcrea_handle_create_new_showcase_form_action
	//********************************************************

	/**
	 * Delete Exclusive Listing
	 *
	 */
	public function aretkcrea_delete_excusive_listing() {
		global $wpdb;

		$exclusiveListingId = (INT) $_POST['mlsId'];

		if ( ! empty( $exclusiveListingId ) && is_user_logged_in() && current_user_can( 'upload_files' ) ) {
			$last_aretk_server_insert_id = get_post_meta( $exclusiveListingId, 'aretk_server_listing_id', true );
			if ( ! empty( $last_aretk_server_insert_id ) && $last_aretk_server_insert_id != '' ) {
				$exclusiveListingIdResult        = array();
				$exclusiveListingIdResult[]      = (string) $last_aretk_server_insert_id;
				$exclusive_old_property_id_array = array();
				$mergerd_property_id_array       = array();
				$exclusive_deleted_id_result     = get_option( "exclusive_deleted_ids" );
				if ( ! empty( $exclusive_deleted_id_result ) && $exclusive_deleted_id_result != 'null' ) {
					$exclusive_old_property_id_array = json_decode( $exclusive_deleted_id_result );
				}
				$mergerd_property_id_array = array_merge( $exclusive_old_property_id_array, $exclusiveListingIdResult );
				$mergerd_property_id_array = array_unique( $mergerd_property_id_array );
				$mergerd_property_id_array = json_encode( $mergerd_property_id_array );
				update_option( "exclusive_deleted_ids", $mergerd_property_id_array );
			}

			$upload_dir = wp_upload_dir();
			if ( ! empty( $upload_dir['basedir'] ) ) {
				$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
				$sql_select                            = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d";
				$sql_prep                              = $wpdb->prepare( $sql_select, $exclusiveListingId );
				$resultListingImagesResults            = $wpdb->get_results( $sql_prep );

				foreach ( $resultListingImagesResults as $imageResults ) {
					$image_url = $imageResults->image_url;

					if ( ! empty( $image_url ) ) {
						list( $imageBase, $imagePath ) = explode( 'uploads/', $image_url );

						$imagePath = esc_url( $upload_dir['basedir'] . '/' . $imagePath );

						if ( file_exists( $imagePath ) && wp_is_writable( $imagePath ) ) {
							if ( false === unlink( $imagePath ) ) {
								echo __( 'Caught exception: could not remove document, check file permissions for:', 'aretk-crea' ) . esc_url( $imagePath ) . "\n";
							}
						}
					}
				}

				# Docs
				$crea_listing_document_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_DOCUMENT_HISTORY;
				$sql_select                              = "SELECT `document_url` FROM `$crea_listing_document_detail_table_name` WHERE `unique_id`= %d LIMIT 1";
				$sql_prep                                = $wpdb->prepare( $sql_select, $exclusiveListingId );
				$resultListingDocResults                 = $wpdb->get_results( $sql_prep );

				foreach ( $resultListingImagesResults as $docResults ) {
					$docURL = $docResults->document_url;

					if ( ! empty( $docURL ) ) {
						list( $docBase, $docPath ) = explode( 'uploads/', $docURL );

						$docPath = esc_url( $upload_dir['basedir'] . '/' . $docPath );

						if ( file_exists( $docPath ) && wp_is_writable( $docPath ) ) {
							if ( false === unlink( $docPath ) ) {
								echo __( 'Caught exception: could not remove document, check file permissions', 'aretk-crea' ) . "\n";
								$fileDeleteError = true;
							}
						}

					}
				}
				$sql_select = "DELETE FROM FROM `$crea_listing_images_detail_table_name` WHERE `unique_id`= %d";
				$sql_prep   = $wpdb->prepare( $sql_select, $exclusiveListingId );
				$wpdb->query( $sql_prep );

				$sql_select = "DELETE FROM `$crea_listing_document_detail_table_name` WHERE `unique_id`= %d AND `unique_id` = %d";
				$sql_prep   = $wpdb->prepare( $sql_select, $docID, $listingID );
				$wpdb->query( $sql_prep );
			}

			$getAretkServerListingIdPostMeta = get_post_meta( $exclusiveListingId, 'aretk_server_listing_id', true );
			if ( isset( $getAretkServerListingIdPostMeta ) && ! empty( $getAretkServerListingIdPostMeta ) ) {
				$listingId = (int) $getAretkServerListingIdPostMeta;
				Aretk_Crea_Admin::aretkcrea_listing_update_to_aretk_server( $listingId, 'delete' );
			}

			wp_delete_post( $exclusiveListingId );

			# Return Listings data

			$agent_id = Aretk_Crea_Admin::aretkcrea_crea_agent_ids( 'list' );

			$userNameList = Aretk_Crea_Admin::aretkcrea_feed_usernames( 'list' );

			$allListingArr = array();

			$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
			if ( ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
				if ( ! empty( $agent_id ) && ! empty( $userNameList ) ) {
					$result_type = 'full';
					$listings    = Aretk_Crea_Admin::aretkcrea_get_listing_records_based_on_agents( $userNameList, $result_type, $agent_id );

					if ( isset( $listings ) && ! empty( $listings ) ) {
						foreach ( $listings as $listing_key => $listing ) {
							if ( ! isset( $listing->TotalRecords ) && empty( $listing->TotalRecords ) ) {
								$allListingArr[ $listing->mlsID ] = $listing;
							}
						}
					}
				}
			}

			$args        = array(
				'posts_per_page' => - 1,
				'post_type'      => 'aretk_listing',
				'post_status'    => 'publish'
			);
			$posts_array = (array) get_posts( $args );

			$exclusiveArr = array();
			foreach ( $posts_array as $singlePost ) {
				$singlePost1    = (array) $singlePost;
				$singlePost2    = (object) $singlePost1;
				$exclusiveArr[] = $singlePost2;
			}
			$allListingFinalArr = array();
			$allListingFinalArr = array_merge( $allListingArr, $exclusiveArr );

			if ( ! empty( $allListingFinalArr ) ) {
				$data = json_encode( $allListingFinalArr );
				update_option( 'cron_run', "" );
				update_option( 'cron_run', "$data" );
			}

			$html = '';
			$html .= '<table class="display" id="crea_setting_listting_content" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG ) . '</th>
								<th>' . __( ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG ) . '</th>
							</tr>
						</thead>
						<tbody>';
			if ( isset( $allListingFinalArr ) && ! empty( $allListingFinalArr ) ) {
				foreach ( $allListingFinalArr as $singleListing ) {
					if ( isset( $singleListing->post_author ) && ! empty( $singleListing->post_author ) ) {
						$singleListingID = $singleListing->ID;
						$ListingAddress  = get_post_meta( $singleListingID, 'listingAddress', true );
						$ListingCity     = get_post_meta( $singleListingID, 'listingcity', true );
						$ListingPrice    = get_post_meta( $singleListingID, 'listingPrice', true );
						$date            = date( 'd-m-Y', strtotime( $singleListing->post_date ) );

						$crea_listing_images_detail_table_name = $wpdb->prefix . ARETKCREA_LISTING_IMAGES_HISTORY;
						$sql_select                            = "SELECT `image_url` FROM `$crea_listing_images_detail_table_name` WHERE `image_position`=1 AND `unique_id`= %d";
						$sql_prep                              = $wpdb->prepare( $sql_select, $singleListingID );
						$resultSet                             = $wpdb->get_results( $sql_prep );
						if ( isset( $resultSet ) && ! empty( $resultSet ) ) {
							$path = $resultSet[0]->image_url;
						} else {
							$path = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
						}
						$agentArrDecoded = get_post_meta( $singleListing->ID, 'listingAgentId', true );
						$agentArr        = json_decode( $agentArrDecoded );
						if ( isset( $agentArr ) && ! empty( $agentArr ) ) {
							$htmlAgent             = '';
							$crea_agent_table_name = $wpdb->prefix . ARETKCREA_AGENT_TABLE;
							foreach ( $agentArr as $singleAgent ) {
								$sql_select     = "SELECT `crea_agent_name` FROM `$crea_agent_table_name` WHERE `crea_agent_id`= %d";
								$sql_prep       = $wpdb->prepare( $sql_select, $singleAgent );
								$resultAgentArr = $wpdb->get_results( $sql_prep );
								if ( isset( $resultAgentArr ) && ! empty( $resultAgentArr ) ) {
									$htmlAgent .= $resultAgentArr[0]->crea_agent_name . ', ';
								}
							}
						}
						$listingpagecount      = 0;
						$getSubscriptionStatus = get_option( 'crea_subscription_status', '' );
						if ( isset( $getSubscriptionStatus ) && ! empty( $getSubscriptionStatus ) && $getSubscriptionStatus === 'valid' ) {
							$aretk_server_listing_id = get_post_meta( $singleListing->ID, 'aretk_server_listing_id', true );
							if ( ! empty( $aretk_server_listing_id ) && $aretk_server_listing_id != '' ) {
								$listingpageRecords = Aretk_Crea_Admin::aretkcrea_get_property_detail_page_result_for_exclusive( $aretk_server_listing_id );
								if ( $listingpageRecords[0]->TotalRecords != 0 ) {
									$listingpagecount = isset( $listingpageRecords[1]->ViewCount ) ? $listingpageRecords[1]->ViewCount : 0;
								} else {
									$listingpagecount = 0;
								}
							} else {
								$listingpagecount = 0;
							}
						} else {
							$listingpageval = get_post_meta( $singleListing->ID, 'crea_aretk_db_listing_page_count', true );

							if ( ! empty( $listingpageval ) && $listingpageval != '' ) {
								$listingpagecount = $listingpageval;
							} else {
								$listingpagecount = 0;
							}
						}
						$link_url = admin_url( 'admin.php?page=create_new_listings&id=' . $singleListing->ID );
						$mls_number = isset($ListingMls) && !empty($ListingMls) ? $ListingMls : 'Exclusive';
						$html .= '<tr>
							<td><img style="height:100px;width:100px;" src="' . esc_url( $path ) . '"></td>
							<td>'.$mls_number.'<br><a href="' . esc_url( $link_url ) . '">' . __( 'Edit', 'aretk-crea' ) . '</a> | <a id="' . esc_attr( $singleListing->ID ) . '" class="trash_listing" href="javascript:void(0);">' . __( 'Trash', 'aretk-crea' ) . '</a></td>
							<td>' . wp_kses_post( $ListingAddress ) . '</td>
							<td>' . esc_html( $ListingCity ) . '</td>
							<td>$' . esc_html( $ListingPrice ) . '</td>
							<td>' . rtrim( $htmlAgent, ', ' ) . '</td>
							<td>' . esc_html( $listingpagecount ) . '</td>
							<td>' . wp_kses_post( $date ) . '</td>
						</tr>';
					} else {
						$htmlAgent = '';
						foreach ( $singleListing->listing_agents as $singleAgent ) {
							$htmlAgent .= $singleAgent->Name . ', ';
						}
						if ( is_object( $singleListing->listing_photos ) ) {
							if ( $singleListing->listing_photos->URL == '' || $singleListing->listing_photos->URL == null ) {
								$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
							} else {
								$apiListingImageURL = $singleListing->listing_photos->URL;
							}
						} else if ( is_object( $singleListing->listing_photos[0] ) ) {
							if ( $singleListing->listing_photos[0]->URL == '' || $singleListing->listing_photos[0]->URL == null ) {
								$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
							} else {
								$apiListingImageURL = $singleListing->listing_photos[0]->URL;
							}
						} else {
							$apiListingImageURL = ARETK_CREA_PLUGIN_URL . 'admin/images/dummy_image.png';
						}
						$mlsId            = isset( $singleListing->mlsID ) ? $singleListing->mlsID : '-';
						$dates            = isset( $singleListing->ListingContractDate ) ? $singleListing->ListingContractDate : '-';
						$listingpagecount = isset( $singleListing->ViewCount ) ? $singleListing->ViewCount : 0;
						$link_url         = admin_url( 'admin.php?page=listings_settings&id=' . $singleListing->ID );
						$html .= '<tr>
							<td><img style="height:100px;width:100px;" src="' . esc_url( $apiListingImageURL ) . '"></td>
							<td>' . esc_html( $mlsId ) . '<br />
							<a href="' . esc_url( $link_url ) . '">' . __( "Map It", ARETKCREA_PLUGIN_SLUG ) . '</a></td>
							<td>' . wp_kses_post( $singleListing->StreetAddress ) . ' ' . wp_kses_post( $singleListing->StreetNumber ) . '</td>
							<td>' . esc_html( $singleListing->City ) . '</td>
							<td>$' . esc_html( $singleListing->Price ) . '</td>
							<td>' . rtrim( $htmlAgent, ', ' ) . '</td>
							<td>' . esc_html( $listingpagecount ) . '</td>
							<td>' . wp_kses_post( $dates ) . '</td>
						</tr>';
					}
				}
			}
			$html .= '</tbody>
					<tfoot>
						<tr>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PHOTO, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_MLS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_ADDRESS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_CITY, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_PRICE, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_AGENT_NAME, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_VIEWS, ARETKCREA_PLUGIN_SLUG ) . '</th>
							<th>' . __( ARETKCREA_ADD_LISTING_TABLE_DATE, ARETKCREA_PLUGIN_SLUG ) . '</th>
						</tr>
					</tfoot>
			</table>';
			echo $html;
		}
		die();
	}

	public static function aretkcrea_get_property_detail_page_result_for_exclusive( $property_id, $return_array = false ) {
		global $wpdb;

		$user_ID            = get_current_user_id();
		$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
		$subscriptionKey    = ! empty( $getSubscriptionKey ) ? $getSubscriptionKey : '';
		$result_type        = 'full';
		if ( ! empty( $property_id ) && $property_id != null ) {
			$property_id = '&ids=' . $property_id;
		}
		$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
		if ( ! empty( $domainName ) ) {
			$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
		} else {
			$domainName = get_site_url();
			$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
		}
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=listings&exclusive=true&viewcount=true&result_type=$result_type$property_id" );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_REFERER, $domainName );
		$data = curl_exec( $ch );
		curl_close( $ch );
		$resultSet = json_decode( $data, true );

		return $resultSet;
	}

	/**
	 * This function will save/update the showcase settings
	 *
	 * @param null
	 *
	 * @return null
	 * @since Phase 1
	 */
	public function aretkcrea_handle_create_new_showcase_form_action() {
		global $wpdb, $wp;

		# only proceed if postype is showcase
		if ( isset( $_POST['posttype'] ) && $_POST['posttype'] == 'showcase' ) {
			// Showcase title
			$crea_showcase_title = isset( $_POST['crea_showcase_title'] ) ? sanitize_text_field( $_POST['crea_showcase_title'] ) : '';

			// Showcase Display type
			$crea_showcase_display_theams_option = isset( $_POST['crea_showcase_theams_option'] ) ? sanitize_text_field( $_POST['crea_showcase_theams_option'] ) : 'Listing View';
			$showcase_options                    = array( 'Listing View', 'Grid View', 'Carousel', 'Map', 'Slider' );
			if ( ! in_array( $crea_showcase_display_theams_option, $showcase_options ) ) {
				$crea_showcase_display_theams_option = 'Listing View';
			}

			// showcase CREA feed
			$crea_feed_ddf_type = isset( $_POST['crea_showcase_feed_ddf_option'] ) ? sanitize_text_field( $_POST['crea_showcase_feed_ddf_option'] ) : '';

			// Include exclusive listings
			$crea_feed_include_exclude = ( isset($_POST['crea_showcase_inc_exc_listing_feed']) && $_POST['crea_showcase_inc_exc_listing_feed'] === 'yes') ? 'yes' : 'no';

			// Filter listings by date listed
			$crea_showcase_filter_by_other_day = isset( $_POST['crea_showcase_filter_by_other_days'] ) ? sanitize_text_field( $_POST['crea_showcase_filter_by_other_days'] ) : '';

			// Filter Open houses
			$crea_showcase_filter_inclue_open_house = isset( $_POST['crea_checkbox_open_house_filter'] ) ? sanitize_text_field( $_POST['crea_checkbox_open_house_filter'] ) : '';

			// Filter Price Min
			$showcase_filter_price_min = isset( $_POST['showcase_filter_price_min'] ) ? (int) $_POST['showcase_filter_price_min'] : '';

			// Filter Price Max
			$showcase_filter_price_max = isset( $_POST['showcase_filter_price_max'] ) ? (int) $_POST['showcase_filter_price_max'] : '';

			// Filter Property Types
			$showcase_filter_property_types         = ! empty( $_POST['showcase_filter_property_types'] ) ? (array) $_POST['showcase_filter_property_types'] : array();
			$showcase_filter_property_types_results = sanitize_text_field( implode( ",", $showcase_filter_property_types ) );
			
			// Filter Ownership Types
			$showcase_filter_ownership_types         = ! empty( $_POST['showcase_filter_ownership_types'] ) ? (array) $_POST['showcase_filter_ownership_types'] : array();
			$showcase_filter_ownership_types_results = sanitize_text_field( implode( ",", $showcase_filter_ownership_types ) );

			// Filter Listing Status
			$showcase_filter_property_status         = ! empty( $_POST['showcase_filter_property_status'] ) ? (array) $_POST['showcase_filter_property_status'] : array();
			$showcase_filter_property_status_results = sanitize_text_field( implode( ",", $showcase_filter_property_status ) );

			// Filter Brokerage
			$crea_filter_brokerage         = isset( $_POST['crea_filter_brokerage'] ) ? (array) $_POST['crea_filter_brokerage'] : array();
			$crea_filter_brokerage_results = sanitize_text_field( implode( ",", $crea_filter_brokerage ) );

			// Filter Office
			$crea_filter_office         = isset( $_POST['crea_filter_office'] ) ? (array) $_POST['crea_filter_office'] : array();
			$crea_filter_office_results = sanitize_text_field( implode( ",", $crea_filter_office ) );

			// Filter Agents method 1
			$crea_filter_agent_name         = isset( $_POST['crea_filter_agent_name'] ) ? (array) $_POST['crea_filter_agent_name'] : array();
			$crea_filter_agent_name_results = sanitize_text_field( implode( ",", $crea_filter_agent_name ) );

			// Filter Agents method 2
			$showcase_filter_listing_agent_ids         = ! empty( $_POST['showcase_filter_listing_agent_ids'] ) ? (array) $_POST['showcase_filter_listing_agent_ids'] : array();
			$showcase_filter_listing_agent_ids_results = sanitize_text_field( implode( ",", $showcase_filter_listing_agent_ids ) );

			// Filter listings by Province
			$showcase_filter_listing_province         = ! empty( $_POST['showcase_filter_listing_province'] ) ? (array) $_POST['showcase_filter_listing_province'] : array();
			$showcase_filter_listing_province_results = sanitize_text_field( implode( ",", $showcase_filter_listing_province ) );

			//------------------------
			// Filter map settings
			$crea_showcase_filter_by_map_km = isset( $_POST['crea_filter_by_map_km'] ) ? (float) sanitize_text_field( $_POST['crea_filter_by_map_km'] ) : '';

			if ( isset( $_POST['showcase_filter_google_map_zoom'] ) && is_numeric( $_POST['showcase_filter_google_map_zoom'] ) && $_POST['showcase_filter_google_map_zoom'] !== '0' ) {
				$showcse_crea_filter_google_map_zoom = (int) $_POST['showcase_filter_google_map_zoom'];
			} else {
				$showcse_crea_filter_google_map_zoom = '';
			}

			$crea_filter_google_map_latitude  = ( isset($_POST['crea_filter_google_map_latitude']) && is_numeric($_POST['crea_filter_google_map_latitude']) ) ? (float) sanitize_text_field( $_POST['crea_filter_google_map_latitude'] ) : '';
			$crea_filter_google_map_longitude = ( isset($_POST['crea_filter_google_map_longitude']) && is_numeric($_POST['crea_filter_google_map_longitude']) ) ? (float) sanitize_text_field( $_POST['crea_filter_google_map_longitude'] ) : '';


			// End Map Settings
			//-------------------------------------
			$crea_filter_brokerage_hidden_name          = isset( $_POST['crea_filter_brokerage_hidden_name'] ) ? sanitize_text_field( $_POST['crea_filter_brokerage_hidden_name'] ) : '';
			$crea_showcse_office_filter_hidden_name     = isset( $_POST['crea_showcse_office_filter_hidden_name'] ) ? sanitize_text_field( $_POST['crea_showcse_office_filter_hidden_name'] ) : '';
			$crea_showcse_agent_name_filter_hidden_name = isset( $_POST['crea_showcse_agent_name_filter_hidden_name'] ) ? sanitize_text_field( $_POST['crea_showcse_agent_name_filter_hidden_name'] ) : '';
			$crea_filter_listing                        = isset( $_POST['crea_filter_listing'] ) ? sanitize_text_field( $_POST['crea_filter_listing'] ) : '';
			//-------------------------------------


			//========================================
			// Display Settings

			// create Serializable settings array
			$Serializable_listing_array  = array();
			$Serializable_grid_array     = array();
			$Serializable_carousel_array = array();
			$Serializable_map_array      = array();
			$Serializable_slider_array   = array();

			if ( $crea_showcase_display_theams_option == "Listing View" ) {
				$crea_sorting_showcase_name              = isset( $_POST['crea_sorting_listing_showcase_name'] ) ? sanitize_text_field( $_POST['crea_sorting_listing_showcase_name'] ) : '';
				$listing_view_setiing                    = $_POST['listing_view_setiing'] === 'yes' ? 'yes' : '';
				$listing_search_position                 = isset( $_POST['listing_search_position'] ) ? sanitize_text_field( $_POST['listing_search_position'] ) : '';
				$listing_view_top                        = isset( $_POST['listing_view_top'] ) ? sanitize_text_field( $_POST['listing_view_top'] ) : '';
				$listing_view_right                      = isset( $_POST['listing_view_right'] ) ? sanitize_text_field( $_POST['listing_view_right'] ) : '';
				$Max_listings_on_a_page                  = isset( $_POST['Max_listings_on_a_page'] ) ? (int) $_POST['Max_listings_on_a_page'] : '';
				$Listing_search_simple_enable_or_disable = $_POST['listing_view_setiing_status_of_search'] === 'yes' ? 'yes' : 'no';
				$listing_view_open_house_or_not          = $_POST['listing_view_setiing_open_house'] === 'yes' ? 'yes' : 'no';
				$listing_view_status_or_not              = $_POST['listing_view_setiing_status'] === 'yes' ? 'yes' : 'no';
				$Serializable_listing_array              = array(
					"listingshowcasename"                     => $crea_sorting_showcase_name,
					"listingviewsearchbar"                    => $listing_view_setiing,
					"listingsearchposition"                   => $listing_search_position,
					"listingviewtop"                          => $listing_view_top,
					"listingviewright"                        => $listing_view_right,
					"maxlistingonpage"                        => $Max_listings_on_a_page,
					"listingopenhouse"                        => $listing_view_open_house_or_not,
					"listingstatus"                           => $listing_view_status_or_not,
					"Listing_search_simple_enable_or_disable" => $Listing_search_simple_enable_or_disable
				);
			}

			if ( $crea_showcase_display_theams_option == "Grid View" ) 
			{
				$crea_sorting_showcase_name      = isset( $_POST['crea_sorting_showcase_grid_name'] ) ? sanitize_text_field( $_POST['crea_sorting_showcase_grid_name'] ) : '';
				$listing_view_setiing            = $_POST['grid_search_view_setiing'] === 'yes' ? 'yes' : 'no';
				$listing_search_position         = isset( $_POST['listing_search_position'] ) ? sanitize_text_field( $_POST['listing_search_position'] ) : '';
				$listing_view_top                = isset( $_POST['listing_view_top'] ) ? sanitize_text_field( $_POST['listing_view_top'] ) : '';
				$listing_view_right              = isset( $_POST['listing_view_right'] ) ? sanitize_text_field( $_POST['listing_view_right'] ) : '';
				$crea_max_grid_selected_row      = isset( $_POST['crea_max_grid_selected_row'] ) ? sanitize_text_field( $_POST['crea_max_grid_selected_row'] ) : '';
				$crea_max_grid_selected_column   = isset( $_POST['crea_max_grid_selected_column'] ) ? (int) $_POST['crea_max_grid_selected_column'] : '';
				$Grid_listings_batch_size   = isset( $_POST['Grid_listings_batch_size'] ) ? (int) $_POST['Grid_listings_batch_size'] : 20;
				$grid_view_open_house_or_not     = $_POST['grid_view_setiing_open_house'] === 'yes' ? 'yes' : '';
				$grid_view_status_or_not         = $_POST['grid_view_setiing_status'] === 'yes' ? 'yes' : '';
				$grid_view_setiing_status_search = $_POST['grid_view_setiing_status_search'] === 'yes' ? 'yes' : 'no';
				$Serializable_grid_array         = array(
					"gridviewshowcasename"                             => $crea_sorting_showcase_name,
					"gridviewsearchbar"                                => $listing_view_setiing, # Show search form
					"gridviewsearchpostion"                            => $listing_search_position,
					"gridviewtop"                                      => $listing_view_top,
					"gridviewright"                                    => $listing_view_right,
					"maxgridviewselectedrow"                           => $crea_max_grid_selected_row,
					"maxgridviewselectedcolumn"                        => $crea_max_grid_selected_column,
					"Grid_listings_batch_size"						   => $Grid_listings_batch_size,
					"gridviewopenhouse"                                => $grid_view_open_house_or_not,
					"gridviewstatus"                                   => $grid_view_status_or_not, # Search open/closed
					"grid_view_setiing_status_search_simple_or_datail" => $grid_view_setiing_status_search
				);
			}

			if ( $crea_showcase_display_theams_option == "Carousel" ) {
				$crea_sorting_showcase_name            = ! empty( $_POST['crea_sorting_showcase_carousel_name'] ) ? sanitize_text_field( $_POST['crea_sorting_showcase_carousel_name'] ) : '';
				$listing_carousel_show_price           = $_POST['listing_carousel_show_price'] === 'no' ? 'no' : 'yes';
				$listing_carousel_show_status          = $_POST['listing_carousel_show_status'] === 'no' ? 'no' : 'yes';
				$listing_carousel_show_open_house_info = $_POST['listing_carousel_show_open_house_info'] === 'no' ? 'no' : 'yes';
				$crea_min_of_listing_carousel          = ! empty( $_POST['crea_min_of_listing_carousel'] ) ? (int) $_POST['crea_min_of_listing_carousel'] : 4;
				$Max_of_listings_for_Carousel          = ! empty( $_POST['Max_of_listings_for_Carousel'] ) ? (int) $_POST['Max_of_listings_for_Carousel'] : 20;
				$listing_carousel_display_prevnext     = $_POST['listing_carousel_display_prevnext'] === 'true' ? 'true' : 'false';
				$listing_carousel_scroll_speed         = ! empty( $_POST['listing_carousel_scroll_speed'] ) ? (int) $_POST['listing_carousel_scroll_speed'] : '3000';
				$listing_carousel_pagination_dots      = $_POST['listing_carousel_pagination_dots'] === 'false' ? 'false' : 'true';
				$Serializable_carousel_array           = array(
					"carouselshowcasename"              => $crea_sorting_showcase_name,
					"carouselshowcasenameprice"         => $listing_carousel_show_price,
					"carouselshowcasenamestatus"        => $listing_carousel_show_status,
					"carouselshowcasenameopenhouseinfo" => $listing_carousel_show_open_house_info,
					"minlistingcarouselshowcasename"    => $crea_min_of_listing_carousel,
					"maxlistingcarouselshowcasename"    => $Max_of_listings_for_Carousel,
					"listing_carousel_display_prevnext" => $listing_carousel_display_prevnext,
					"listing_carousel_scroll_speed"     => $listing_carousel_scroll_speed,
					"listing_carousel_pagination_dots"  => $listing_carousel_pagination_dots,
				);
			}

			if ( $crea_showcase_display_theams_option == "Map" ) {
				$map_search_bar_view            = $_POST['map_search_view_setiing'] === 'yes' ? 'yes' : 'no';
				$map_view_setiing_status_search = $_POST['map_view_setiing_status_search'] === 'yes' ? 'yes' : 'no';
				$google_image_zoom              = isset( $_POST['google_image_zoom'] ) ? (int) $_POST['google_image_zoom'] : '6';
				if ( isset( $_POST['crea_showcase_google_map_latitude'] ) && is_numeric( $_POST['crea_showcase_google_map_latitude'] ) ) {
					$crea_showcase_google_map_latitude = floatval( preg_replace( '/[^\d\-.]+/', '', $_POST['crea_showcase_google_map_latitude'] ) );
				} else {
					$crea_showcase_google_map_latitude = '';
				}
				if ( isset( $_POST['crea_showcase_google_map_longitude'] ) && is_numeric( $_POST['crea_showcase_google_map_longitude'] ) ) {
					$crea_showcase_google_map_longitude = floatval( preg_replace( '/[^\d\-.]+/', '', $_POST['crea_showcase_google_map_longitude'] ) );
				} else {
					$crea_showcase_google_map_longitude = '';
				}
				$mapviewopenhouse_yes_or_not = isset( $_POST['map_view_setiing_open_house'] ) ? sanitize_text_field( $_POST['map_view_setiing_open_house'] ) : '';
				$mapviewstatus_yes_or_not    = isset( $_POST['map_view_setiing_status'] ) ? sanitize_text_field( $_POST['map_view_setiing_status'] ) : '';
				$only_map_view_display_hight = isset( $_POST['only_map_view_display_hight'] ) ? (int) $_POST['only_map_view_display_hight'] : '';

				$Serializable_map_array = array(
					"mapfilterlatitude"                     => $crea_showcase_google_map_latitude,
					"mapfilterlongitude"                    => $crea_showcase_google_map_longitude,
					"showcasemapimagezoom"                  => $google_image_zoom,
					"mapopenhouse"                          => $mapviewopenhouse_yes_or_not,
					"mapstatus"                             => $mapviewstatus_yes_or_not,
					"mapviewdisplayhight"                   => $only_map_view_display_hight,
					"mapviewdisplaysearch"                  => $map_search_bar_view,
					"mapviewdisplaysearch_simple_or_detail" => $map_view_setiing_status_search,
				);
			}

			if ( $crea_showcase_display_theams_option == "Slider" ) {
				$crea_sorting_showcase_slider_display_name = isset( $_POST['crea_sorting_showcase_slider_display_name'] ) ? sanitize_text_field( $_POST['crea_sorting_showcase_slider_display_name'] ) : '';
				$listing_slider_show_price                 = $_POST['listing_slider_show_price'] === 'yes' ? 'yes' : 'no';
				$listing_slider_show_status                = $_POST['listing_slider_show_status'] === 'yes' ? 'yes' : 'no';
				$listing_slider_show_open_house_info       = $_POST['listing_slider_show_open_house_info'] === 'yes' ? 'yes' : 'no';
				$min_of_listing_slider                     = isset( $_POST['min_of_listing_slider'] ) ? (int) $_POST['min_of_listing_slider'] : '';
				$max_of_listings_for_slider                = isset( $_POST['max_of_listings_for_slider'] ) ? (int) $_POST['max_of_listings_for_slider'] : '';

				$Serializable_slider_array = array(
					"sildersortingshowcasename"   => $crea_sorting_showcase_slider_display_name,
					"slidershowcaseshowprice"     => $listing_slider_show_price,
					"slidershowcaseshowstatus"    => $listing_slider_show_status,
					"slidershowcaseopenhouseinfo" => $listing_slider_show_open_house_info,
					"minslidershowcaselisting"    => $min_of_listing_slider,
					"maxslidershowcaselisting"    => $max_of_listings_for_slider,
				);
			}
			// End Display Settings
			//========================================

			//========================================
			// Showcase colour settings

			// list view colour
			$crea_listing_showcase_text_color        = '';
			$crea_listing_showcase_address_bar_color = '';
			$crea_listing_showcase_price_color       = '';
			$crea_listing_showcase_status_color      = '';
			$crea_listing_showcase_open_house_color  = '';
			$crea_listing_view_color_array           = array();
			if ( $crea_showcase_display_theams_option == "Listing View" ) {
				$crea_listing_showcase_text_color            = isset( $_POST['crea_listing_showcase_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_text_color'] ) : '';
				$crea_listing_showcase_address_bar_color     = isset( $_POST['crea_listing_showcase_address_bar_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_address_bar_color'] ) : '';
				$crea_listing_showcase_price_color           = isset( $_POST['crea_listing_showcase_price_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_price_color'] ) : '';
				$crea_listing_showcase_status_color          = isset( $_POST['crea_listing_showcase_status_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_status_color'] ) : '';
				$crea_listing_showcase_open_house_color      = isset( $_POST['crea_listing_showcase_open_house_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_open_house_color'] ) : '';
				$crea_listing_showcase_status_text_color     = isset( $_POST['crea_listing_showcase_status_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_status_text_color'] ) : '';
				$crea_listing_showcase_open_house_text_color = isset( $_POST['crea_listing_showcase_open_house_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_open_house_text_color'] ) : '';
				$crea_listing_showcase_pagination_color      = isset( $_POST['crea_listing_showcase_pagination_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_pagination_color'] ) : '';
				$crea_listing_showcase_pagination_text_color = isset( $_POST['crea_listing_showcase_pagination_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_listing_showcase_pagination_text_color'] ) : '';
				$crea_listing_view_color_array               = array(
					"listingShowcaseTextColor"           => $crea_listing_showcase_text_color,
					"listingShowcaseAddressBarColor"     => $crea_listing_showcase_address_bar_color,
					"listingShowcasePriceColor"          => $crea_listing_showcase_price_color,
					"listingShowcaseStatusColor"         => $crea_listing_showcase_status_color,
					"listingShowcaseOpenHouseColor"      => $crea_listing_showcase_open_house_color,
					"listingShowcaseOpenHouseTextColor"  => $crea_listing_showcase_open_house_text_color,
					"listingShowcaseStatusTextColor"     => $crea_listing_showcase_status_text_color,
					"listingShowcasepaginationColor"     => $crea_listing_showcase_pagination_color,
					"listingShowcasepaginationtextColor" => $crea_listing_showcase_pagination_text_color,
				);
			}

			// Grid view color
			$crea_grid_showcase_text_color       = '';
			$crea_grid_showcase_text_bgcolor     = '';
			$crea_grid_showcase_oh_color_txt     = '';
			$crea_grid_showcase_oh_color_bg      = '';
			$crea_showcase_status_box_text_color = '';
			$crea_showcase_status_box_color      = '';
			$crea_showcase_pagination_text_color = '';
			$crea_showcase_pagination_color      = '';
			$crea_grid_view_color_array          = array();
			if ( $crea_showcase_display_theams_option == "Grid View" ) {
				$crea_grid_showcase_text_color       = isset( $_POST['crea_grid_showcase_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_grid_showcase_text_color'] ) : '';
				$crea_grid_showcase_text_bgcolor     = isset( $_POST['crea_grid_showcase_text_bgcolor'] ) ? sanitize_hex_color_no_hash( $_POST['crea_grid_showcase_text_bgcolor'] ) : '';
				$crea_grid_showcase_oh_color_txt     = isset( $_POST['crea_grid_showcase_oh_color_txt'] ) ? sanitize_hex_color_no_hash( $_POST['crea_grid_showcase_oh_color_txt'] ) : '';
				$crea_grid_showcase_oh_color_bg      = isset( $_POST['crea_grid_showcase_oh_color_bg'] ) ? sanitize_hex_color_no_hash( $_POST['crea_grid_showcase_oh_color_bg'] ) : '';
				$crea_showcase_status_box_text_color = isset( $_POST['crea_showcase_status_box_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_showcase_status_box_text_color'] ) : '';
				$crea_showcase_status_box_color      = isset( $_POST['crea_showcase_status_box_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_showcase_status_box_color'] ) : '';
				$crea_showcase_pagination_text_color = isset( $_POST['crea_showcase_status_pagination_gird_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_showcase_status_pagination_gird_text_color'] ) : '';
				$crea_showcase_pagination_color      = isset( $_POST['crea_showcase_status_pagination_gird_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_showcase_status_pagination_gird_color'] ) : '';
				$crea_grid_view_color_array          = array(
					"gridShowcaseTextColor"           => $crea_grid_showcase_text_color,
					"gridShowcase_TextBgColor"        => $crea_grid_showcase_text_bgcolor,
					"gridShowcase_oh_color_txt"       => $crea_grid_showcase_oh_color_txt,
					"gridShowcase_oh_color_bg"        => $crea_grid_showcase_oh_color_bg,
					"gridShowcaseStatusBoxTextColor"  => $crea_showcase_status_box_text_color,
					"gridShowcaseStatusBoxColor"      => $crea_showcase_status_box_color,
					"gridShowcasePaginationColor"     => $crea_showcase_pagination_color,
					"gridShowcasePaginationTextColor" => $crea_showcase_pagination_text_color,
				);
			}


			// Carousel view color
			$crea_carousel_showcase_text_color       = '';
			$crea_carousel_showcase_background_color = '';
			$crea_carousel_showcase_oh_color_txt     = '';
			$crea_carousel_showcase_oh_color_bg      = '';
			$crea_carousel_view_color_array          = array();
			if ( $crea_showcase_display_theams_option == "Carousel" ) {
				$crea_carousel_showcase_text_color       = isset( $_POST['crea_carousel_showcase_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_carousel_showcase_text_color'] ) : '';
				$crea_carousel_showcase_background_color = isset( $_POST['crea_carousel_showcase_background_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_carousel_showcase_background_color'] ) : '';
				$crea_carousel_showcase_oh_color_txt     = isset( $_POST['crea_carousel_showcase_oh_color_txt'] ) ? sanitize_hex_color_no_hash( $_POST['crea_carousel_showcase_oh_color_txt'] ) : '';
				$crea_carousel_showcase_oh_color_bg      = isset( $_POST['crea_carousel_showcase_oh_color_bg'] ) ? sanitize_hex_color_no_hash( $_POST['crea_carousel_showcase_oh_color_bg'] ) : '';
				$crea_carousel_view_color_array          = array(
					"carouselShowcaseTextColor"           => $crea_carousel_showcase_text_color,
					"carouselShowcaseBackgroundColor"     => $crea_carousel_showcase_background_color,
					"crea_carousel_showcase_oh_color_txt" => $crea_carousel_showcase_oh_color_txt,
					"crea_carousel_showcase_oh_color_bg"  => $crea_carousel_showcase_oh_color_bg
				);
			}

			// Map view color
			$crea_map_showcase_text_color          = '';
			$crea_map_showcase_button_color        = '';
			$crea_map_showcase_reset_button_color  = '';
			$crea_map_showcase_listing_hover_color = '';
			$crea_map_showcase_top_picture_color   = '';
			$crea_map_view_color_array             = array();
			if ( $crea_showcase_display_theams_option == "Map" ) {
				$crea_map_showcase_text_color             = isset( $_POST['crea_map_showcase_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_text_color'] ) : '';
				$crea_map_showcase_button_color           = isset( $_POST['crea_map_showcase_button_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_button_color'] ) : '';
				$crea_map_showcase_reset_button_color     = isset( $_POST['crea_map_showcase_reset_button_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_reset_button_color'] ) : '';
				$crea_map_showcase_listing_hover_color    = isset( $_POST['crea_map_showcase_listing_hover_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_listing_hover_color'] ) : '';
				$crea_map_showcase_top_picture_color      = isset( $_POST['crea_map_showcase_top_picture_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_top_picture_color'] ) : '';
				$crea_map_showcase_top_picture_text_color = isset( $_POST['crea_map_showcase_top_picture_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_top_picture_text_color'] ) : '';
				$crea_map_showcase_price_color            = isset( $_POST['crea_map_showcase_price_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_price_color'] ) : '';
				$crea_map_showcase_price_text_color       = isset( $_POST['crea_map_showcase_price_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_map_showcase_price_text_color'] ) : '';
				$crea_map_view_color_array                = array(
					"mapShowcaseTextColor"           => $crea_map_showcase_text_color,
					"mapShowcaseButtonColor"         => $crea_map_showcase_button_color,
					"mapShowcaseResetButtonColor"    => $crea_map_showcase_reset_button_color,
					"mapShowcaseListingHoverColor"   => $crea_map_showcase_listing_hover_color,
					"mapShowcaseTopPictureColor"     => $crea_map_showcase_top_picture_color,
					"mapShowcaseTopPictureTextColor" => $crea_map_showcase_top_picture_text_color,
					"mapShowcasePriceColor"          => $crea_map_showcase_price_color,
					"mapShowcasePriceTextColor"      => $crea_map_showcase_price_text_color
				);
			}

			// crea showcase slider view color
			$crea_slider_showcase_text_color             = '';
			$crea_slider_showcase_tab_button_color       = '';
			$crea_slider_showcase_more_info_button_color = '';
			$crea_slider_view_color_array                = array();

			if ( $crea_showcase_display_theams_option == "Slider" ) {
				$crea_slider_showcase_text_color             = isset( $_POST['crea_slider_showcase_text_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_slider_showcase_text_color'] ) : '';
				$crea_slider_showcase_tab_button_color       = isset( $_POST['crea_slider_showcase_tab_button_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_slider_showcase_tab_button_color'] ) : '';
				$crea_slider_showcase_more_info_button_color = isset( $_POST['crea_slider_showcase_more_info_button_color'] ) ? sanitize_hex_color_no_hash( $_POST['crea_slider_showcase_more_info_button_color'] ) : '';
				$crea_slider_showcase_oh_color_txt           = isset( $_POST['crea_slider_showcase_oh_color_txt'] ) ? sanitize_hex_color_no_hash( $_POST['crea_slider_showcase_oh_color_txt'] ) : '';
				$crea_slider_showcase_oh_color_bg            = isset( $_POST['crea_slider_showcase_oh_color_bg'] ) ? sanitize_hex_color_no_hash( $_POST['crea_slider_showcase_oh_color_bg'] ) : '';
				$crea_slider_view_color_array                = array(
					"sliderShowcaseTextColor"           => $crea_slider_showcase_text_color,
					"sliderShowcaseTabBtnColor"         => $crea_slider_showcase_tab_button_color,
					"sliderShowcaseMoreInfoBtnColor"    => $crea_slider_showcase_more_info_button_color,
					"crea_slider_showcase_oh_color_txt" => $crea_slider_showcase_oh_color_txt,
					"crea_slider_showcase_oh_color_bg"  => $crea_slider_showcase_oh_color_bg
				);
			}
			// END Showcase color settings
			//========================================

			//========================================
			// save new showcase settings
			if ( $_POST['action-which'] == "add" && $_POST['posttype'] == "showcase" ) {
				if ( empty( $crea_showcase_title ) || $crea_showcase_title == '' ) {
					$crea_showcase_title = 'No title [' . sprintf( '%04d', rand( 0, 99999 ) ) . ']';
				}
				$new_showcase_args       = array(
					'post_title'    => $crea_showcase_title,
					'post_content'  => '',
					'category_name' => '',
					'post_status'   => 'publish',
					'post_type'     => 'aretk_showcase'
				);
				$import_showcase_post_id = wp_insert_post( $new_showcase_args );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_feed_ddf_type', maybe_serialize( $crea_feed_ddf_type ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_feed_include_exclude', maybe_serialize( $crea_feed_include_exclude ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_display_theams_option', maybe_serialize( $crea_showcase_display_theams_option ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_brokerage', maybe_serialize( $crea_filter_brokerage_results ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_office', maybe_serialize( $crea_filter_office_results ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_agent_name', maybe_serialize( $crea_filter_agent_name_results ) );
				update_post_meta( $import_showcase_post_id, 'showcase_filter_listing_agent_ids', maybe_serialize( $showcase_filter_listing_agent_ids_results ) );
				update_post_meta( $import_showcase_post_id, 'showcase_filter_listing_province', maybe_serialize( $showcase_filter_listing_province_results ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_brokerage_hidden_name', maybe_serialize( $crea_filter_brokerage_hidden_name ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_office_hidden_name', maybe_serialize( $crea_showcse_office_filter_hidden_name ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_agents_hidden_name', maybe_serialize( $crea_showcse_agent_name_filter_hidden_name ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_listing', maybe_serialize( $crea_filter_listing ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_by_map_km', maybe_serialize( $crea_showcase_filter_by_map_km ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_by_other_day', maybe_serialize( $crea_showcase_filter_by_other_day ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_inclue_open_house', maybe_serialize( $crea_showcase_filter_inclue_open_house ) );
				update_post_meta( $import_showcase_post_id, 'showcse_filter_price_min', maybe_serialize( $showcase_filter_price_min ) );
				update_post_meta( $import_showcase_post_id, 'showcse_filter_price_max', maybe_serialize( $showcase_filter_price_max ) );
				update_post_meta( $import_showcase_post_id, 'showcase_filter_property_types', maybe_serialize( $showcase_filter_property_types_results ) );
				update_post_meta( $import_showcase_post_id, 'showcase_filter_ownership_types', maybe_serialize( $showcase_filter_ownership_types_results ) );
				update_post_meta( $import_showcase_post_id, 'showcase_filter_property_status', maybe_serialize( $showcase_filter_property_status_results ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_google_map_latitude', maybe_serialize( $crea_filter_google_map_latitude ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_google_map_longitude', maybe_serialize( $crea_filter_google_map_longitude ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_filter_google_map_zoom', maybe_serialize( $showcse_crea_filter_google_map_zoom ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_serializable_listing_array', maybe_serialize( $Serializable_listing_array ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_serializable_grid_array', maybe_serialize( $Serializable_grid_array ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_serializable_carousel_array', maybe_serialize( $Serializable_carousel_array ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_serializable_map_array', maybe_serialize( $Serializable_map_array ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_serializable_slider_array', maybe_serialize( $Serializable_slider_array ) );
				update_post_meta( $import_showcase_post_id, 'Showcase_crea_listing_view_color_array', maybe_serialize( $crea_listing_view_color_array ) );
				update_post_meta( $import_showcase_post_id, 'Showcase_crea_grid_view_color_array', maybe_serialize( $crea_grid_view_color_array ) );
				update_post_meta( $import_showcase_post_id, 'Showcase_crea_carousel_view_color_array', maybe_serialize( $crea_carousel_view_color_array ) );
				update_post_meta( $import_showcase_post_id, 'Showcase_crea_map_view_color_array', maybe_serialize( $crea_map_view_color_array ) );
				update_post_meta( $import_showcase_post_id, 'Showcase_crea_slider_view_color_array', maybe_serialize( $crea_slider_view_color_array ) );
				update_post_meta( $import_showcase_post_id, 'showcse_crea_save_short_code', maybe_serialize( "[ARETK-LS-$import_showcase_post_id ls_id=$import_showcase_post_id]" ) );
				wp_set_object_terms( $import_showcase_post_id, 'listing-details-showcase', 'listing-showcase', true );
				$link_url = admin_url( 'admin.php?page=create_new_showcase&showcase_id=' . $import_showcase_post_id . '#crea_showcase_save_tab' );
				wp_safe_redirect( $link_url );
			} elseif ( $_POST['action-which'] == "edit" && $_POST['posttype'] == "showcase" ) {
				$showcase_ids         = isset( $_POST['showcase_ids'] ) ? (INT) $_POST['showcase_ids'] : '';
				$update_showcase_post = array( 'ID' => $showcase_ids, 'post_title' => $crea_showcase_title );
				wp_update_post( $update_showcase_post );
				update_post_meta( $showcase_ids, 'showcse_crea_feed_ddf_type', maybe_serialize( $crea_feed_ddf_type ) );
				update_post_meta( $showcase_ids, 'showcse_crea_feed_include_exclude', maybe_serialize( $crea_feed_include_exclude ) );
				update_post_meta( $showcase_ids, 'showcse_crea_display_theams_option', maybe_serialize( $crea_showcase_display_theams_option ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_brokerage', maybe_serialize( $crea_filter_brokerage_results ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_office', maybe_serialize( $crea_filter_office_results ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_agent_name', maybe_serialize( $crea_filter_agent_name_results ) );
				update_post_meta( $showcase_ids, 'showcase_filter_listing_agent_ids', maybe_serialize( $showcase_filter_listing_agent_ids_results ) );
				update_post_meta( $showcase_ids, 'showcase_filter_listing_province', maybe_serialize( $showcase_filter_listing_province_results ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_brokerage_hidden_name', maybe_serialize( $crea_filter_brokerage_hidden_name ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_office_hidden_name', maybe_serialize( $crea_showcse_office_filter_hidden_name ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_agents_hidden_name', maybe_serialize( $crea_showcse_agent_name_filter_hidden_name ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_listing', maybe_serialize( $crea_filter_listing ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_by_map_km', maybe_serialize( $crea_showcase_filter_by_map_km ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_by_other_day', maybe_serialize( $crea_showcase_filter_by_other_day ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_inclue_open_house', maybe_serialize( $crea_showcase_filter_inclue_open_house ) );
				update_post_meta( $showcase_ids, 'showcse_filter_price_min', maybe_serialize( $showcase_filter_price_min ) );
				update_post_meta( $showcase_ids, 'showcse_filter_price_max', maybe_serialize( $showcase_filter_price_max ) );
				update_post_meta( $showcase_ids, 'showcase_filter_property_types', maybe_serialize( $showcase_filter_property_types_results ) );
				update_post_meta( $showcase_ids, 'showcase_filter_ownership_types', maybe_serialize( $showcase_filter_ownership_types_results ) );
				update_post_meta( $showcase_ids, 'showcase_filter_property_status', maybe_serialize( $showcase_filter_property_status_results ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_google_map_latitude', maybe_serialize( $crea_filter_google_map_latitude ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_google_map_longitude', maybe_serialize( $crea_filter_google_map_longitude ) );
				update_post_meta( $showcase_ids, 'showcse_crea_filter_google_map_zoom', maybe_serialize( $showcse_crea_filter_google_map_zoom ) );
				
				$crea_showcase_google_mapbound_lat_sw = ( isset($crea_showcase_google_mapbound_lat_sw) && !empty($crea_showcase_google_mapbound_lat_sw) ) ? $crea_showcase_google_mapbound_lat_sw : '';
				update_post_meta( $showcase_ids, 'mapfilterlat_sw', maybe_serialize( $crea_showcase_google_mapbound_lat_sw ) );
				
				$crea_showcase_google_mapbound_lng_sw = ( isset($crea_showcase_google_mapbound_lng_sw) && !empty($crea_showcase_google_mapbound_lng_sw) ) ? $crea_showcase_google_mapbound_lng_sw : '';
				update_post_meta( $showcase_ids, 'mapfilterlong_sw', maybe_serialize( $crea_showcase_google_mapbound_lng_sw ) );
				
				$crea_showcase_google_mapbound_lat_ne = ( isset($crea_showcase_google_mapbound_lat_ne) && !empty($crea_showcase_google_mapbound_lat_ne) ) ? $crea_showcase_google_mapbound_lat_ne : '';
				update_post_meta( $showcase_ids, 'mapfilterlat_ne', maybe_serialize( $crea_showcase_google_mapbound_lat_ne ) );
				
				$crea_showcase_google_mapbound_lng_ne = ( isset($crea_showcase_google_mapbound_lng_ne) && !empty($crea_showcase_google_mapbound_lng_ne) ) ? $crea_showcase_google_mapbound_lng_ne : '';
				update_post_meta( $showcase_ids, 'mapfilterlong_ne', maybe_serialize( $crea_showcase_google_mapbound_lng_ne ) );
				
				update_post_meta( $showcase_ids, 'showcse_crea_serializable_listing_array', maybe_serialize( $Serializable_listing_array ) );
				update_post_meta( $showcase_ids, 'showcse_crea_serializable_grid_array', maybe_serialize( $Serializable_grid_array ) );
				update_post_meta( $showcase_ids, 'showcse_crea_serializable_carousel_array', maybe_serialize( $Serializable_carousel_array ) );
				update_post_meta( $showcase_ids, 'showcse_crea_serializable_map_array', maybe_serialize( $Serializable_map_array ) );
				update_post_meta( $showcase_ids, 'showcse_crea_serializable_slider_array', maybe_serialize( $Serializable_slider_array ) );
				update_post_meta( $showcase_ids, 'Showcase_crea_listing_view_color_array', maybe_serialize( $crea_listing_view_color_array ) );
				update_post_meta( $showcase_ids, 'Showcase_crea_grid_view_color_array', maybe_serialize( $crea_grid_view_color_array ) );
				update_post_meta( $showcase_ids, 'Showcase_crea_carousel_view_color_array', maybe_serialize( $crea_carousel_view_color_array ) );
				update_post_meta( $showcase_ids, 'Showcase_crea_map_view_color_array', maybe_serialize( $crea_map_view_color_array ) );
				update_post_meta( $showcase_ids, 'Showcase_crea_slider_view_color_array', maybe_serialize( $crea_slider_view_color_array ) );
				wp_set_object_terms( $showcase_ids, 'listing-details-showcase', 'listing-showcase', true );
				$link_url = admin_url( 'admin.php?page=create_new_showcase&showcase_id=' . $showcase_ids . '#crea_showcase_save_tab' );
				wp_safe_redirect( $link_url );
			}
		}
	}

	/**
	 * Function for aretkcrea_delete_showcase_custom_post_records
	 *
	 * @return return showcase post records
	 *
	 */
	function aretkcrea_delete_showcase_custom_post_records() {
		global $wpdb;
		$showcase_id = isset( $_POST['showcase_id'] ) ? (int) $_POST['showcase_id'] : '';
		if ( $showcase_id != '' && ! empty( $showcase_id ) || $showcase_id != 0 ) {
			$post_table      = $wpdb->prefix . 'posts';
			$post_meta_table = $wpdb->prefix . 'postmeta';
			$sql_select      = "DELETE FROM `$post_table` WHERE `ID`= %d";
			$sql_prep        = $wpdb->prepare( $sql_select, $showcase_id );
			$delete_recod    = $wpdb->query( $sql_prep );
			$sql_select      = "DELETE FROM `$post_meta_table` WHERE `post_id`= %d";
			$sql_prep        = $wpdb->prepare( $sql_select, $showcase_id );
			$delete_recod    = $wpdb->query( $sql_prep );
			echo "sucessfullydelete";
		}
		die();
	}

	/**
	 * Function for create custom post type lead column name
	 *
	 * @param unknown_type $columns
	 * @param unknown_type $defaults
	 *
	 * @return return column name of lead post type
	 *
	 */
	function aretkcrea_set_custom_edit_aretk_lead_columns( $columns ) {
		$getSubscriptionLeadcolumn = get_option( 'crea_subscription_status', true );
		if ( $getSubscriptionLeadcolumn === 'valid' ) {
			$columns = array(
				'cb'                 => '<input type="checkbox" />',
				'title'              => __( 'Name' ),
				'email'              => __( 'Email' ),
				'Phone_no'           => __( 'Phone No' ),
				'Agent Name'         => __( 'Agent Name' ),
				'date'               => __( 'Date' ),
				'post_modified_date' => __( 'Modified Date' ),
			);
		} else {
			$columns = array(
				'cb'                 => '<input type="checkbox" />',
				'title'              => __( 'Name' ),
				'email'              => __( 'Email' ),
				'Phone_no'           => __( 'Phone No' ),
				'date'               => __( 'Date' ),
				'post_modified_date' => __( 'Modified Date' ),
			);
		}

		return $columns;
	}

	/**
	 * Unlink Document
	 *
	 */
	/* Phasing out, but keeping for now in case needed.
	function aretkcrea_unlink_listing_document_edit_page_from_listing_ajax()
	{
		$parent_document = isset( $_POST['inputfilename'] ) ? $_POST['inputfilename'] : '';
		$documentfile = $_FILES;
		$tempLocPath = '';
		foreach ( $documentfile as $documentfileKey=>$documentfileValue ) {
			if( $documentfileValue['name'] == $parent_document  ) {
				$tempLocPath = $documentfileValue['tmp_name'];
			}
		}
		#$imagefile = isset( $_POST['inputfilename'] ) ? $_POST['inputfilename'] : '';
        #$imagepath = "Users/mp4_thumbnails-".$imagefile;
        #unlink($imagepath);

		if( unlink($tempLocPath) ) {
			echo 'Success';
			$fileArr = array();
			$counter = 0;
			foreach ($documentfile as $documentfileKey=>$documentfileValue  ){
				if( $documentfileValue['tmp_name'] != $tempLocPath  ) {
					$fileArr[$doc_counter] = array(
						'error'=>$documentfileValue['error'],
						'name'=>$documentfileValue['name'],
						'size'=>$documentfileValue['size'],
						'tmp_name'=>$documentfileValue['tmp_name'],
						'type'=>$documentfileValue['type'],
					);
					$doc_counter = $doc_counter + 1;
				}
			}
		} else {
			echo 'Fail';
		}
		die();
	}
	*/

	function aretkcrea_custom_aretk_lead_sortable( $columns ) {
		$columns['post_modified_date'] = 'post_modified';

		return $columns;
	}

	/**
	 * Function for create custom post type content
	 *
	 * @param unknown_type $column_name
	 * @param unknown_type $post_ID
	 *
	 * @return return coulum content  of lead post type
	 *
	 */
	function aretkcrea_set_custom_edit_aretk_lead_content( $column_name, $post_ID ) {
		global $wpdb;
		if ( $column_name == 'email' ) {
			$Lead_email_primary = get_post_meta( $post_ID, 'lead_primary_email', $single = false );
			$Email_name         = implode( " ", $Lead_email_primary );
			if ( isset( $Email_name ) && ! empty( $Email_name ) ) {
				echo $Email_name;
			} else {
				$Lead_email = get_post_meta( $post_ID, 'lead_phone_email', $single = false );
				if ( isset( $Lead_email ) && ! empty( $Lead_email ) ) {
					$email_not_primary = implode( " ", $Lead_email );
					$lead_email_title  = maybe_unserialize( $email_not_primary );
					if ( is_array( $lead_email_title ) ) {
						echo $lead_email_title[0];
					} else {
						echo $lead_email_title;
					}
				} else {
					echo ' - ';
				}
			}
		}
		if ( $column_name == 'Phone_no' ) {
			$phone_no = get_post_meta( $post_ID, 'lead_phone_no', $single = false );
			if ( isset( $phone_no ) && ! empty( $phone_no ) ) {
				if ( is_array( $phone_no ) ) {
					$Lead_phone = implode( " ", $phone_no );
					$var1       = maybe_unserialize( $Lead_phone );
					if ( is_array( $var1 ) ) {
						echo $var1[0]['PhoneNo'];
					} else {
						echo $var1;
					}
				}
			} else {
				echo " - ";
			}
		}
		if ( $column_name == 'Agent Name' ) {
			$crea_listingAgentId = get_post_meta( $post_ID, 'crea_agent_id', true );
			$agents_id           = maybe_serialize( $crea_listingAgentId );
			$agents_name         = json_decode( $agents_id, true );
			if ( isset( $agents_name ) && ! empty( $agents_name ) ) {
				$listing_agents_name        = $agents_name;
				$listing_detail_agents_name = '';
				$post_meta_table            = $wpdb->prefix . 'crea_agent';
				if ( isset( $listing_agents_name ) && ! empty( $listing_agents_name ) ) {
					$sql_select    = "SELECT `crea_agent_id`, `crea_agent_name` FROM `$post_meta_table` WHERE `crea_agent_id`= %d";
					$sql_prep      = $wpdb->prepare( $sql_select, $listing_agents_name );
					$get_agent_ids = $wpdb->get_results( $sql_prep );
					foreach ( $get_agent_ids as $listing_get_agents_name ) {
						$listing_detail_agents_name = $listing_get_agents_name->crea_agent_name;
					}
				}
				echo $listing_detail_agents_name;
			} else {
				echo " - ";
			}
		}
		if ( $column_name == 'post_modified_date' ) {
			echo "Modified<p>" . get_the_modified_date( 'Y/m/d ' ) . "</p>";
		}
	}

	/**
	 * Function for create import functionality
	 *
	 * @return return download link message.
	 *
	 */
	function aretkcrea_emport_lead_download() {
		global $wpdb;

		$upload_dir = wp_upload_dir();

		if ( ! empty( $upload_dir['basedir'] ) && current_user_can( 'upload_files' ) && is_user_logged_in() ) {
			$leads_out = $headers = array();

			$post_table = $wpdb->prefix . 'posts';
			$sql_select = "SELECT * FROM `$post_table` WHERE `post_type`= 'aretk_lead'";
			$sql_prep   = $wpdb->prepare( $sql_select, null );
			$leads_db   = $wpdb->get_results( $sql_prep );

			foreach ( $leads_db as $lead ) {
				$id = $lead->ID;

				$headers['name'] = __( 'Name', 'aretkcrea' );
				$lead_name       = $lead->post_title;

				$headers['email']   = __( 'Email', 'aretkcrea' );
				$Lead_email_primary = get_post_meta( $id, 'lead_primary_email', $single = false );
				if ( ! empty( $Lead_email_primary ) ) {
					$Email_name         = implode( " ", $Lead_email_primary );
					$lead_eamil_results = $Email_name;
				} else {
					$Lead_email = get_post_meta( $id, 'lead_phone_email', $single = false );

					if ( isset( $Lead_email ) && ! empty( $Lead_email ) ) {
						$email_not_primary = implode( " ", $Lead_email );
						$lead_email_title  = maybe_unserialize( $email_not_primary );

						if ( is_array( $lead_email_title ) ) {
							$lead_eamil_results = $lead_email_title[0];
						} else {
							$lead_eamil_results = $lead_email_title;
						}
					} else {
						$lead_eamil_results = '';
					}
				}

				$headers['phone'] = __( 'Phone', 'aretkcrea' );
				$phone_no         = get_post_meta( $id, 'lead_phone_no', $single = false );
				if ( isset( $phone_no ) && ! empty( $phone_no ) ) {
					if ( is_array( $phone_no ) ) {
						$Lead_phone = implode( " ", $phone_no );
						$var1       = maybe_unserialize( $Lead_phone );
						if ( is_array( $var1 ) ) {
							$lead_phone_result = $var1[0]['PhoneNo'];
						} else {
							$lead_phone_result = $var1;
						}
					}
				} else {
					$lead_phone_result = " - ";
				}

				$headers['comments'] = __( 'Comments', 'aretkcrea' );
				$lead_comments       = $customer_order->post_content;

				if ( ! empty( $lead_name ) && ! empty( $lead_eamil_results ) ) {
					$leads_out[] = array( $lead_name, $lead_eamil_results, $lead_phone_result, $lead_comments );
				}
			}

			if ( ! empty( $leads_out ) ) {
				$sitename = sanitize_key( get_bloginfo( 'name' ) );

				if ( ! empty( $sitename ) ) {
					$sitename .= '.';
				}

				$filename = "{$sitename}aretk-leads-export." . date( 'Y-m-d-Hi' ) . ".csv";

				$file_path = trailingslashit( $upload_dir['path'] ) . $filename;

				$file_url = trailingslashit( $upload_dir['url'] ) . $filename;

				$file = fopen( $file_path, 'w' );

				$delimiter = ',';

				$headers['name'] = __( 'Name', 'aretkcrea' );

				fputcsv( $file, $headers, $delimiter );

				foreach ( $leads_out as $row ) {
					fputcsv( $file, $row, $delimiter );
				}

				fclose( $file );

				echo "<p><div class='updated fade'><a target='_blank' href='$file_url'>" . __( 'Click here', 'aretk-crea' ) . "</a> " . __( 'to download .csv file', 'aretk-crea' ) . "</div></p>";
			} else {
				echo "<p><div class='updated fade'>" . __( 'No leads found to export', 'aretk-crea' ) . "</div></p>";
			}
		} else {
			echo "<p><div class='updated fade'>" . __( 'Unable to create csv file', 'aretk-crea' ) . "</div></p>";
		}
		die();
	}

	/**
	 * Function for add new lead reminder
	 * @package Aretk
	 * @subpackage Phase 1
	 * @return add lead reminder record
	 */
	function aretkcrea_add_new_lead_reminder() {
		global $wpdb;
		$get_lead_id     = isset( $_POST['get_lead_id'] ) ? (INT) $_POST['get_lead_id'] : '';
		$reminderName    = isset( $_POST['reminderName'] ) ? sanitize_text_field( $_POST['reminderName'] ) : '';
		$reminderSubject = isset( $_POST['reminderSubject'] ) ? sanitize_text_field( $_POST['reminderSubject'] ) : '';
		$reminderEmail   = isset( $_POST['reminderEmail'] ) ? sanitize_email( $_POST['reminderEmail'] ) : '';
		$reminderComment = isset( $_POST['reminderComment'] ) ? sanitize_text_field( $_POST['reminderComment'] ) : '';

		// validate reminderDateTime -> must match: yyyy-mm-dd hh:mm
		$valid_date       = false;
		$reminderDateTime = isset( $_POST['reminderDateTime'] ) ? sanitize_text_field( $_POST['reminderDateTime'] ) : '';
		if ( preg_match( "/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/", $reminderDateTime ) ) {
			$valid_date = true;
		}

		// validate reminderRepeat
		$valid_repeat_option              = false;
		$reminderRepeat                   = isset( $_POST['reminderRepeat'] ) ? sanitize_text_field( $_POST['reminderRepeat'] ) : '';
		$acceptable_reminerRepeat_options = array( 'no-repeat', 'daily', 'Weekly', 'Monthly', 'Yearly' );
		if ( in_array( $reminderRepeat, $acceptable_reminerRepeat_options ) ) {
			$valid_repeat_option = true;
		}

		if ( true === $valid_date && true === $valid_repeat_option && ! empty( $get_lead_id ) ) {
			$reminderTableName = $wpdb->prefix . ARETKCREA_LEAD_REMINDER_HISTORY;
			$wpdb->insert( "$reminderTableName",
				array(
					'reminder_lead_id' => $get_lead_id,
					'reminder_name'    => $reminderName,
					'reminder_subject' => $reminderSubject,
					'reminder_email'   => $reminderEmail,
					'reminder_comment' => $reminderComment,
					'reminder_time'    => $reminderDateTime,
					'reminder_repeat'  => $reminderRepeat,
					'created_time'     => current_time( 'mysql', 1 ),
					'updated_time'     => current_time( 'mysql', 1 )
				),
				array( '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', )
			);
		}
		$sql_select         = "SELECT * FROM `$reminderTableName` WHERE `reminder_lead_id`= %d ORDER BY `id` ASC";
		$sql_prep           = $wpdb->prepare( $sql_select, $get_lead_id );
		$getReminderResults = $wpdb->get_results( $sql_prep );

		$html = '';
		if ( ! empty( $getReminderResults ) && $getReminderResults != '' ) {
			$reminderCounter = 1;
			foreach ( $getReminderResults as $getReminderResultsValues ) {
				$html .= '<div id="addNewReminerMain' . esc_attr( $reminderCounter ) . '" class="crea_reminder_display">';
				$html .= '<table width="100%" class="create-new-lead-table">';
				$html .= '<tbody>';
				$html .= '<tr>';
				$html .= '<td><p class="set_reminder_text reminder_text_email">' . __( 'Email', 'aretk-crea' ) . '<span class="required_fields">' . __( '*', 'aretk-crea' ) . '</span></p></td>';
				$html .= '<td><input class="set_text_fields crea_lead_reminder_email_text" type="text" value="' . esc_attr( $getReminderResultsValues->reminder_email ) . '" name="crea_lead_reminder_email" id="crea_lead_reminder_text_email' . $reminderCounter . '"><p id="crea_reminder_email_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderemailError"></p><p id="crea_reminder_valid_email_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderemailErrorvalid"></p></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td><p class="set_reminder_text reminder_text_subject">' . __( 'Subject', 'aretk-crea' ) . '<span class="required_fields">' . __( '*', 'aretk-crea' ) . '</span></p></td>';
				$html .= '<td><input class="set_text_fields crea_lead_reminder_subject_text" type="text" value="' . stripslashes( esc_attr( $getReminderResultsValues->reminder_subject ) ) . '" name="crea_lead_reminder_subject" id="crea_lead_reminder_text_subject' . esc_attr( $reminderCounter ) . '"><p id="crea_reminder_subject_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderSubjectsError"></p></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td><p class="set_reminder_text reminder_text_comment">' . __( 'Comment', 'aretk-crea' ) . '</p></td>';
				$html .= '<td><textarea class="set_text_fields crea_lead_reminder_comment_text" name="crea_lead_reminder_comment" id="crea_lead_reminder_text_comment' . esc_attr( $reminderCounter ) . '">' . stripslashes( $getReminderResultsValues->reminder_comment ) . '</textarea></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td><p class="set_reminder_text reminder_text_datetime">' . __( 'Date and Time', 'aretk-crea' ) . '<span class="required_fields">' . __( '*', 'aretk-crea' ) . '</span></p></td>';
				$html .= '<td><input class="set_text_fields crea_lead_reminder_datetime_text" type="text" value="' . esc_attr( $getReminderResultsValues->reminder_time ) . '" name="crea_lead_reminder_datetime" id="crea_lead_reminder_text_datetime' . esc_attr( $reminderCounter ) . '"><p id="crea_reminder_datetime_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderdatetimeError"></p></td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td><p class="set_reminder_text reminder_text_repeat">' . __( 'Repeat', 'aretk-crea' ) . '</p></td>';
				$html .= '<td>';
				$norepleatcheckdValues = '';
				if ( $getReminderResultsValues->reminder_repeat == 'no-repeat' ) {
					$norepleatcheckdValues = 'checked';
				} else {
					$norepleatcheckdValues = '';
				}
				$dailycheckdValues = '';
				if ( $getReminderResultsValues->reminder_repeat == 'daily' ) {
					$dailycheckdValues = 'checked';
				} else {
					$dailycheckdValues = '';
				}
				$weeklycheckdValues = '';
				if ( $getReminderResultsValues->reminder_repeat == 'weekly' ) {
					$weeklycheckdValues = 'checked';
				} else {
					$weeklycheckdValues = '';
				}
				$monthlycheckdValues = '';
				if ( $getReminderResultsValues->reminder_repeat == 'monthly' ) {
					$monthlycheckdValues = 'checked';
				} else {
					$monthlycheckdValues = '';
				}
				$yearlycheckdValues = '';
				if ( $getReminderResultsValues->reminder_repeat == 'yearly' ) {
					$yearlycheckdValues = 'checked';
				} else {
					$yearlycheckdValues = '';
				}

				$html .= '<input id="crea_lead_no_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $norepleatcheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_No_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="no-repeat">' . __( 'No Repeat', 'aretk-crea' ) . '<br/>';
				$html .= '<input id="crea_lead_daily_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $dailycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_daily_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="daily">' . __( 'Daily', 'aretk-crea' ) . '<br/>';
				$html .= '<input id="crea_lead_weekly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $weeklycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_weekly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="weekly">' . __( 'Weekly', 'aretk-crea' ) . '<br/>';
				$html .= '<input id="crea_lead_monthly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $monthlycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_monthly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="monthly">' . __( 'Monthly', 'aretk-crea' ) . '<br/>';
				$html .= '<input id="crea_lead_yearly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $yearlycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_yearly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="yearly">' . __( 'Yearly', 'aretk-crea' ) . '<br/>';
				$html .= '</td>';
				$html .= '</tr>';
				$html .= '</tbody>';
				$html .= '</table>';
				$html .= '<div class="submit_block">';
				$html .= '<a href="javascript:void(0);" id="update_lead_reminder_ajax' . esc_attr( $reminderCounter ) . '" class="btn button button-primary crea_lead_update_reminder">' . __( 'Update', 'aretk-crea' ) . '</a>';
				$html .= '<a href="javascript:void(0);" id="remove_lead_reminder' . esc_attr( $reminderCounter ) . '" class="btn button button-primary crea_lead_remove_reminder">' . __( 'Remove Reminder', 'aretk-crea' ) . '</a>';
				$html .= '<input type="hidden" name="crea_lead_reminder_hiiden_id" class="crea_lead_reminder_unique_id" value="' . esc_attr( $getReminderResultsValues->reminder_lead_id ) . '" id="lead_reminder_hidden_id' . esc_attr( $reminderCounter ) . '">';
				$html .= '<input type="hidden" name="crea_lead_reminder_Table_hiiden_id" class="crea_lead_reminder_table_id" value="' . esc_attr( $getReminderResultsValues->id ) . '" id="lead_reminder_table_hidden_id' . esc_attr( $reminderCounter ) . '">';
				$html .= '</div>';
				$html .= '</div>';
				$reminderCounter = $reminderCounter + 1;
			}
		}
		echo $html;
		die();
	}

	/**
	 * Function for update lead reminder
	 * @package Aretk
	 * @subpackage Phase 1
	 * @return update lead reminder record
	 */
	function aretkcrea_update_crea_lead_reminder() {
		global $wpdb;
		$hiddenReminderValue      = isset( $_POST['hiddenReminderValue'] ) ? (INT) $_POST['hiddenReminderValue'] : '';
		$hiddenReminderTableValue = isset( $_POST['hiddenReminderTableValue'] ) ? (INT) $_POST['hiddenReminderTableValue'] : '';
		$reminderName             = isset( $_POST['reminderName'] ) ? sanitize_text_field( $_POST['reminderName'] ) : '';
		$reminderSubject          = isset( $_POST['reminderSubject'] ) ? sanitize_text_field( $_POST['reminderSubject'] ) : '';
		$reminderEmail            = isset( $_POST['reminderEmail'] ) ? sanitize_email( $_POST['reminderEmail'] ) : '';
		$reminderComment          = isset( $_POST['reminderComment'] ) ? sanitize_text_field( $_POST['reminderComment'] ) : '';

		// validate reminderDateTime -> must match: yyyy-mm-dd hh:mm
		$valid_date       = false;
		$reminderDateTime = isset( $_POST['reminderDateTime'] ) ? sanitize_text_field( $_POST['reminderDateTime'] ) : '';
		if ( preg_match( "/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})/", $reminderDateTime ) ) {
			$valid_date = true;
		}

		// validate reminderRepeat
		$valid_repeat_option              = false;
		$reminderRepeat                   = isset( $_POST['reminderRepeat'] ) ? sanitize_text_field( $_POST['reminderRepeat'] ) : '';
		$acceptable_reminerRepeat_options = array( 'no-repeat', 'daily', 'Weekly', 'Monthly', 'Yearly' );
		if ( in_array( $reminderRepeat, $acceptable_reminerRepeat_options ) ) {
			$valid_repeat_option = true;
		}

		if ( true === $valid_date && true === $valid_repeat_option && ! empty( $hiddenReminderTableValue ) ) {
			$reminderTableName = $wpdb->prefix . ARETKCREA_LEAD_REMINDER_HISTORY;
			$wpdb->update( "$reminderTableName",
				array(
					'reminder_name'    => "$reminderName",
					'reminder_subject' => "$reminderSubject",
					'reminder_email'   => "$reminderEmail",
					'reminder_comment' => "$reminderComment",
					'reminder_time'    => "$reminderDateTime",
					'reminder_repeat'  => "$reminderRepeat",
					'updated_time'     => current_time( 'mysql', 1 )
				),
				array( 'id' => $hiddenReminderTableValue ),
				array( '%s', '%s', '%s', '%s', '%s', '%s', '%s' ),
				array( '%d' )
			);
		}
		die();
	}

	/**
	 * Function for Remove lead reminder
	 * @package Aretk
	 * @subpackage Phase 1
	 * @return remove lead reminder record
	 */
	function aretkcrea_remove_crea_lead_reminder() {
		global $wpdb;
		$hiddenLeadID     = isset( $_POST['hiddenLeadID'] ) ? (INT) $_POST['hiddenLeadID'] : '';
		$removeReminderID = isset( $_POST['removeReminderID'] ) ? (INT) $_POST['removeReminderID'] : '';

		if ( ! empty( $hiddenLeadID ) && $hiddenLeadID != '' && ! empty( $removeReminderID ) && $removeReminderID != '' ) {
			$reminderTableName = $wpdb->prefix . ARETKCREA_LEAD_REMINDER_HISTORY;
			$sql_select        = "DELETE FROM `$reminderTableName` WHERE `id`= %d AND `reminder_lead_id`= %d";
			$sql_prep          = $wpdb->prepare( $sql_select, $removeReminderID, $hiddenLeadID );
			$delete_recod      = $wpdb->query( $sql_prep );

			$sql_select         = "SELECT * FROM `$reminderTableName` WHERE `reminder_lead_id`= %d ORDER BY `id` ASC";
			$sql_prep           = $wpdb->prepare( $sql_select, $hiddenLeadID );
			$getReminderResults = $wpdb->get_results( $sql_prep );

			$html = '';
			if ( ! empty( $getReminderResults ) && $getReminderResults != '' ) {
				$reminderCounter = 1;
				foreach ( $getReminderResults as $getReminderResultsValues ) {
					$html .= '<div id="addNewReminerMain' . $reminderCounter . '" class="crea_reminder_display">';
					$html .= '<table width="100%" class="create-new-lead-table">';
					$html .= '<tbody>';
					$html .= '<tr>';
					$html .= '<td><p style="margin-bottom:0;">' . __( 'Email - The address of the person receiving the reminder', 'aretk-crea' ) . '</p><p class="set_reminder_text reminder_text_email">' . __( 'Email', 'aretk-crea' ) . '<span class="required_fields">*</span></p></td>';
					$html .= '<td><input class="set_text_fields crea_lead_reminder_email_text" type="text" value="' . esc_attr( $getReminderResultsValues->reminder_email ) . '" name="crea_lead_reminder_email" id="crea_lead_reminder_text_email' . esc_attr( $reminderCounter ) . '"><p id="crea_reminder_email_error' . esc_attr( $reminderCounter ) . '"   style="display:none;" class="setErrmsg reminderemailError"></p><p id="crea_reminder_valid_email_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderemailErrorvalid"></p></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td><p class="set_reminder_text reminder_text_subject">' . __( 'Subject', 'aretk-crea' ) . '<span class="required_fields">*</span></p></td>';
					$html .= '<td><input class="set_text_fields crea_lead_reminder_subject_text" type="text" value="' . stripslashes( $getReminderResultsValues->reminder_subject ) . '" name="crea_lead_reminder_subject" id="crea_lead_reminder_text_subject' . esc_attr( $reminderCounter ) . '"><p id="crea_reminder_subject_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderSubjectsError"></p></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td><p class="set_reminder_text reminder_text_comment">' . __( 'Comment', 'aretk-crea' ) . '</p></td>';
					$html .= '<td><textarea class="set_text_fields crea_lead_reminder_comment_text" name="crea_lead_reminder_comment" id="crea_lead_reminder_text_comment' . esc_attr( $reminderCounter ) . '">' . stripslashes( $getReminderResultsValues->reminder_comment ) . '</textarea></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td><p class="set_reminder_text reminder_text_datetime">' . __( 'Date and Time', 'aretk-crea' ) . '<span class="required_fields">*</span></p></td>';
					$html .= '<td><input class="set_text_fields crea_lead_reminder_datetime_text" type="text" value="' . esc_attr( $getReminderResultsValues->reminder_time ) . '" name="crea_lead_reminder_datetime" id="crea_lead_reminder_text_datetime' . esc_attr( $reminderCounter ) . '"><p id="crea_reminder_datetime_error' . esc_attr( $reminderCounter ) . '" style="display:none;" class="setErrmsg reminderdatetimeError"></p></td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td><p class="set_reminder_text reminder_text_repeat">' . __( 'Repeat', 'aretk-crea' ) . '</p></td>';
					$html .= '<td>';
					$dailycheckdValues = '';
					if ( $getReminderResultsValues->reminder_repeat == 'daily' ) {
						$dailycheckdValues = 'checked';
					} else {
						$dailycheckdValues = '';
					}
					$weeklycheckdValues = '';
					if ( $getReminderResultsValues->reminder_repeat == 'weekly' ) {
						$weeklycheckdValues = 'checked';
					} else {
						$weeklycheckdValues = '';
					}
					$monthlycheckdValues = '';
					if ( $getReminderResultsValues->reminder_repeat == 'monthly' ) {
						$monthlycheckdValues = 'checked';
					} else {
						$monthlycheckdValues = '';
					}
					$yearlycheckdValues = '';
					if ( $getReminderResultsValues->reminder_repeat == 'yearly' ) {
						$yearlycheckdValues = 'checked';
					} else {
						$yearlycheckdValues = '';
					}
					$html .= '<input id="crea_lead_daily_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $dailycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_daily_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="daily">' . __( 'Daily', 'aretk-crea' ) . '<br/>';
					$html .= '<input id="crea_lead_weekly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $weeklycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_weekly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="weekly">' . __( 'Weekly', 'aretk-crea' ) . '<br/>';
					$html .= '<input id="crea_lead_monthly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $monthlycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_monthly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="monthly">' . __( 'Monthly', 'aretk-crea' ) . '<br/>';
					$html .= '<input id="crea_lead_yearly_repeat_remider_id' . esc_attr( $reminderCounter ) . '" type="radio" ' . esc_attr( $yearlycheckdValues ) . ' class="repeat_reminder_value crea_lead_reminder_yearly_repeat_text" name="crea_lead_reminder_repeat' . esc_attr( $reminderCounter ) . '" value="yearly">' . __( 'Yearly', 'aretk-crea' ) . '<br/>';
					$html .= '</td>';
					$html .= '</tr>';
					$html .= '</tbody>';
					$html .= '</table>';
					$html .= '<div class="submit_block">';
					$html .= '<a href="javascript:void(0);" id="update_lead_reminder_ajax' . esc_html( $reminderCounter ) . '" class="btn button button-primary crea_lead_update_reminder">' . __( 'Update', 'aretk-crea' ) . '</a>';
					$html .= '<a href="javascript:void(0);" id="remove_lead_reminder' . esc_html( $reminderCounter ) . '" class="btn button button-primary crea_lead_remove_reminder">' . __( 'Remove Reminder', 'aretk-crea' ) . '</a>';
					$html .= '<input type="hidden" name="crea_lead_reminder_hiiden_id" class="crea_lead_reminder_unique_id" value="' . esc_attr( $getReminderResultsValues->reminder_lead_id ) . '" id="lead_reminder_hidden_id' . esc_attr( $reminderCounter ) . '">';
					$html .= '<input type="hidden" name="crea_lead_reminder_Table_hiiden_id" class="crea_lead_reminder_table_id" value="' . esc_attr( $getReminderResultsValues->id ) . '" id="lead_reminder_table_hidden_id' . esc_attr( $reminderCounter ) . '">';
					$html .= '</div>';
					$html .= '</div>';
					$reminderCounter = $reminderCounter + 1;
				}
			}
			echo $html;
		}
		die();
	}

	// Return CREA agent IDs

	/**
	 * Ajax Call Function For get select agents name and id for feed section
	 *
	 */
	function aretkcrea_get_the_select_board_name() {
		$html                         = '';
		$getSubscriptionListingFilter = sanitize_text_field( $_POST['getSubscriptionKey'] );
		$getFeed                      = sanitize_text_field( $_POST['getFeed'] );
		if ( isset( $getSubscriptionListingFilter ) && ! empty( $getSubscriptionListingFilter ) && isset( $getFeed ) && ! empty( $getFeed ) ) {
			$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
			$subscriptionKey    = preg_replace( "/[^a-z0-9-]+/i", "", $getSubscriptionKey );
			$subscriptionKey    = ! empty( $subscriptionKey ) ? $subscriptionKey : '';

			$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
			if ( ! empty( $domainName ) ) {
				$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
			} else {
				$domainName = get_site_url();
				$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
			}

			if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
				$ip = $_SERVER['REMOTE_ADDR'];
			}
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=boards&feed=$getFeed" );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
			curl_setopt( $ch, CURLOPT_HEADER, 0 );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_REFERER, $domainName );
			$data = curl_exec( $ch );
			curl_close( $ch );
			$resultSet = json_decode( $data );
			if ( $resultSet != '' && ! empty( $resultSet ) ) {
				foreach ( $resultSet as $resultSetKey => $resultSetValues ) {
					if ( ! isset( $resultSetValues->TotalRecords ) && empty( $resultSetValues->TotalRecords ) ) {
						$html .= '<option value="' . (INT) $resultSetValues->Board_ID . '">' . sanitize_text_field( $resultSetValues->BoardName ) . '</option>';
					}
				}
			}
		}
		echo $html;
		die();
	}

	/**
	 * Ajax call for get the office name in feed
	 *
	 *
	 */
	function aretkcrea_get_the_select_board_office() {
		$getFeed   = sanitize_text_field( $_POST['getFeed'] );
		$board_ids = $_POST['board_id'];
		$board_id  = array();
		$html      = '';
		if ( ! empty( $board_ids ) && ! empty( $getFeed ) ) {
			foreach ( $board_ids as $b_id ) {
				$board_id[] = (INT) $b_id;
			}
			$board_id_results = implode( ",", $board_id );

			$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
			if ( ! empty( $domainName ) ) {
				$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
			} else {
				$domainName = get_site_url();
				$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
			}
			$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
			$subscriptionKey    = preg_replace( "/[^a-z0-9-]+/i", "", $getSubscriptionKey );
			$subscriptionKey    = ! empty( $subscriptionKey ) ? $subscriptionKey : '';

			if ( isset( $subscriptionKey ) && ! empty( $subscriptionKey ) && isset( $getFeed ) && ! empty( $getFeed ) && ! empty( $board_id ) ) {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=offices&feed=$getFeed&board_id=$board_id_results" );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HEADER, 0 );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_REFERER, $domainName );
				$data = curl_exec( $ch );
				curl_close( $ch );
				$resultSet = json_decode( $data );
				if ( $resultSet != '' && ! empty( $resultSet ) ) {
					foreach ( $resultSet as $resultSetKey => $resultSetValues ) {
						if ( ! isset( $resultSetValues->TotalRecords ) && empty( $resultSetValues->TotalRecords ) ) {
							$html .= '<option value="' . (INT) $resultSetValues->OfficeID . '">' . sanitize_text_field( $resultSetValues->Name ) . '</option>';
						}
					}
				}
			}
		}
		echo $html;
		die();
	}

	/**
	 * Ajax call for get the agents name in feed
	 *
	 *
	 */
	function aretkcrea_get_the_select_board_agent_name() {
		$getFeed = sanitize_text_field( $_POST['getFeed'] );

		$board_ids = $_POST['board_id'];
		$board_id  = array();
		foreach ( $board_ids as $b_id ) {
			$board_id[] = (INT) $b_id;
		}
		$board_id_results = implode( ",", $board_id );

		$office_ids = $_POST['office_id'];
		$office_id  = array();
		foreach ( $office_ids as $o_id ) {
			$office_id[] = (INT) $o_id;
		}
		$office_id_results = implode( ",", $office_id );

		$html = '';
		if ( ! empty( $board_id_results ) && ! empty( $office_id_results ) && ! empty( $getFeed ) ) {
			$domainName = isset( $_SERVER['SERVER_NAME'] ) ? $_SERVER['SERVER_NAME'] : '';
			if ( ! empty( $domainName ) ) {
				$domainName = filter_var( $domainName, FILTER_SANITIZE_URL );
			} else {
				$domainName = get_site_url();
				$domainName = esc_url( parse_url( $domainName, PHP_URL_HOST ) );
			}
			$getSubscriptionKey = get_option( 'crea_subscription_key', '' );
			$subscriptionKey    = preg_replace( "/[^a-z0-9-]+/i", "", $getSubscriptionKey );
			$subscriptionKey    = ! empty( $subscriptionKey ) ? $subscriptionKey : '';

			if ( isset( $subscriptionKey ) && ! empty( $subscriptionKey ) ) {
				$ch = curl_init();
				curl_setopt( $ch, CURLOPT_URL, ARETKCREA_LISTING_BASEDONSERVER_API . "/?key=$subscriptionKey&request=agents&feed=$getFeed&office_id=$office_id_results" );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
				curl_setopt( $ch, CURLOPT_HEADER, 0 );
				curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch, CURLOPT_REFERER, $domainName );
				$data = curl_exec( $ch );
				curl_close( $ch );
				$resultSet = json_decode( $data );
				if ( $resultSet != '' && ! empty( $resultSet ) ) {
					foreach ( $resultSet as $resultSetKey => $resultSetValues ) {
						if ( ! isset( $resultSetValues->TotalRecords ) && empty( $resultSetValues->TotalRecords ) ) {
							$html .= '<option value="' . (int) $resultSetValues->AgentID . '">' . sanitize_text_field( $resultSetValues->Name ) . '</option>';
						}
					}
				}
			}
		}
		echo $html;
		die();
	}
}
