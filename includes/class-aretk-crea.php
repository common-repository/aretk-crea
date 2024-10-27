<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.aretk.com
 * @since      1.0.0
 *
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Aretk_Crea
 * @subpackage Aretk_Crea/includes
 * @author     ARETK <inquiry@aretk.com>
 */
 
class Aretk_Crea {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Aretk_Crea_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->plugin_name = 'aretk-crea';
		$this->version = '1.20.10.29.01';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Aretk_Crea_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Aretk_Crea_Loader. Orchestrates the hooks of the plugin.
	 * - Aretk_Crea_i18n. Defines internationalization functionality.
	 * - Aretk_Crea_Admin. Defines all hooks for the admin area.
	 * - Aretk_Crea_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aretk-crea-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-aretk-crea-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-aretk-crea-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-aretk-crea-public.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/aretk-crea-admin-display.php';
		$this->loader = new Aretk_Crea_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Aretk_Crea_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Aretk_Crea_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Aretk_Crea_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'aretkcrea_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'aretkcrea_enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'aretkcrea_crea_custom_menu' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'aretkcrea_remove_custom_post_lead_from_admin_menu' );
		$this->loader->add_action( 'admin_head', $plugin_admin, 'aretkcrea_current_menu' );
		//$this->loader->add_action( 'admin_head', $plugin_admin, 'aretk_custom_admin_permalink_css' );

		//Remove functionlity from Lead
		$this->loader->add_action( 'page_row_actions', $plugin_admin, 'aretkcrea_post_row_actions_custom', 10, 2 );

		//Register custom post type for Showcase and Register Showcase Taxonomy
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_custom_post_type_showcase', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_listing_showcase_taxonomy', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_create_listing_showcase_category', 0 );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'aretkcrea_restrict_post_deletion', 10, 1 );
		$this->loader->add_action( 'before_delete_post', $plugin_admin, 'aretkcrea_restrict_post_deletion', 10, 1 );

		//Register custom post type for listing and Register listing Taxonomy
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_custom_post_type_listing', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_listing_taxonomy', 0 );
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_create_new_lead_taxonomy', 0 );

		//Register custom post type for Lead section
		$this->loader->add_action( 'init', $plugin_admin, 'aretkcrea_register_custom_post_type_leads', 0 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'aretkcrea_create_listing_detail_page' );
		//$this->loader->add_action( 'admin_init', $plugin_admin, 'register_save_api_data' );
		$this->loader->add_action( 'wp_ajax_aretk_crea_disclaimer_update', $plugin_admin, 'aretk_crea_disclaimer_update' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretk_crea_disclaimer_update', $plugin_admin, 'aretk_crea_disclaimer_update' );
		$this->loader->add_action( 'wp_ajax_aretk_crea_add_new_agents', $plugin_admin, 'aretk_crea_add_new_agents' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretk_crea_add_new_agents', $plugin_admin, 'aretk_crea_add_new_agents' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_delete_selected_agent_records', $plugin_admin, 'aretkcrea_delete_selected_agent_records' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_delete_selected_agent_records', $plugin_admin, 'aretkcrea_delete_selected_agent_records' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_update_crea_agents_records', $plugin_admin, 'aretkcrea_update_crea_agents_records' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_update_crea_agents_records', $plugin_admin, 'aretkcrea_update_crea_agents_records' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_get_google_map_address_lat_long', $plugin_admin, 'aretkcrea_get_google_map_address_lat_long' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_get_google_map_address_lat_long', $plugin_admin, 'aretkcrea_get_google_map_address_lat_long' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_check_subscription_key_valid_ajax', $plugin_admin, 'aretkcrea_check_subscription_key_valid_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_check_subscription_key_valid_ajax', $plugin_admin, 'aretkcrea_check_subscription_key_valid_ajax' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_save_plugin_settings_tab_data_ajax', $plugin_admin, 'aretkcrea_save_plugin_settings_tab_data_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_save_plugin_settings_tab_data_ajax', $plugin_admin, 'aretkcrea_save_plugin_settings_tab_data_ajax' );
		$this->loader->add_action( 'wp_ajax_new_aretkcrea_fetch_total_records_of_username_ajax',  $plugin_admin, 'aretkcrea_new_aretkcrea_fetch_total_records_of_username_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_new_aretkcrea_fetch_total_records_of_username_ajax',  $plugin_admin, 'aretkcrea_new_aretkcrea_fetch_total_records_of_username_ajax' );
		$this->loader->add_action( 'admin_post_submit-form', $plugin_admin, 'aretkcrea_handle_create_listing_form_action' );
		$this->loader->add_action( 'admin_post_nopriv_submit-form', $plugin_admin, 'aretkcrea_handle_create_listing_form_action' );
		$this->loader->add_action( 'admin_post_lead-form', $plugin_admin, 'aretkcrea_handle_create_lead_form_action' );
		$this->loader->add_action( 'admin_post_nopriv_lead-form', $plugin_admin, 'aretkcrea_handle_create_lead_form_action' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_update_crea_listing_images_order', $plugin_admin, 'aretkcrea_update_crea_listing_images_order' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_update_crea_listing_images_order', $plugin_admin, 'aretkcrea_update_crea_listing_images_order' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_update_crea_listing_images_order_with_upload', $plugin_admin, 'aretkcrea_update_crea_listing_images_order_with_upload' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_update_crea_listing_images_order_with_upload', $plugin_admin, 'aretkcrea_update_crea_listing_images_order_with_upload' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_delete_listing_image_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_delete_listing_image_edit_page_from_listing_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_delete_listing_image_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_delete_listing_image_edit_page_from_listing_ajax' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_delete_listing_document_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_delete_listing_document_edit_page_from_listing_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_delete_listing_document_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_delete_listing_document_edit_page_from_listing_ajax' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_listing_filter_based_on_agent_or_mlsid', $plugin_admin, 'aretkcrea_listing_filter_based_on_agent_or_mlsid' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_listing_filter_based_on_agent_or_mlsid', $plugin_admin, 'aretkcrea_listing_filter_based_on_agent_or_mlsid' );
		$this->loader->add_filter( 'views_edit-aretk_lead', $plugin_admin, 'aretkcrea_custom_button_for_lead_list' );
		//CREA Lead Add Dropdown and remove default search filter
		$this->loader->add_filter( 'disable_months_dropdown', $plugin_admin, 'aretkcrea_filter_disable_months_dropdown_custom', 10, 2 );
		//add an action for ajax call send email functionality
		$this->loader->add_action( 'wp_ajax_aretkcrea_lead_email_send', $plugin_admin, 'aretkcrea_lead_email_send' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_lead_email_send', $plugin_admin, 'aretkcrea_lead_email_send' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_add_listing_showcase_changes', $plugin_admin, 'aretkcrea_add_listing_showcase_changes' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_add_listing_showcase_changes', $plugin_admin, 'aretkcrea_add_listing_showcase_changes' );
		$this->loader->add_action( 'get_edit_post_link', $plugin_admin, 'aretkcrea_edit_aretk_post_link', 10, 3 );
		$this->loader->add_action( 'wp_ajax_aretkcrea_add_new_correspondence_content', $plugin_admin, 'aretkcrea_add_new_correspondence_content' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_add_new_correspondence_content', $plugin_admin, 'aretkcrea_add_new_correspondence_content' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_remove_correspondence_content', $plugin_admin, 'aretkcrea_remove_correspondence_content' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_remove_correspondence_content', $plugin_admin, 'aretkcrea_remove_correspondence_content' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_new_import_lead_user', $plugin_admin, 'aretkcrea_new_import_lead_user' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_new_import_lead_user', $plugin_admin, 'aretkcrea_new_import_lead_user' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_map_listing', $plugin_admin, 'aretkcrea_map_listing' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_map_listing', $plugin_admin, 'aretkcrea_map_listing' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_add_search_listing_detail_showcase_changes', $plugin_admin, 'aretkcrea_add_search_listing_detail_showcase_changes' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_add_search_listing_detail_showcase_changes', $plugin_admin, 'aretkcrea_add_search_listing_detail_showcase_changes' );
		$this->loader->add_action( 'admin_post_showcase-form', $plugin_admin, 'aretkcrea_handle_create_new_showcase_form_action' ); // If the user is logged in
		$this->loader->add_action( 'admin_post_nopriv_showcase-form', $plugin_admin, 'aretkcrea_handle_create_new_showcase_form_action' ); // If the user is logged in
		$this->loader->add_action( 'wp_ajax_aretkcrea_delete_excusive_listing', $plugin_admin, 'aretkcrea_delete_excusive_listing' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_delete_excusive_listing', $plugin_admin, 'aretkcrea_delete_excusive_listing' );
		//custom post type custom listing hook
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'aretkcrea_restrict_manage_posts_custom_for_lead' );
		$this->loader->add_action( 'pre_get_posts', $plugin_admin, 'aretkcrea_pre_get_posts_custom_for_lead' );
		$this->loader->add_action( 'bulk_actions-edit-aretk_lead', $plugin_admin, 'aretkcrea_custom_bulk_edit_action_for_aretk_lead' );
		$this->loader->add_action( 'admin_footer-edit.php', $plugin_admin, 'aretkcrea_custom_lead_admin_footer' );
		$this->loader->add_filter( 'pre_get_posts', $plugin_admin, 'aretkcrea_lead_post_type_ordering', 10, 2 );
		//custom bulk action
		$this->loader->add_action( 'load-edit.php', $plugin_admin, 'aretkcrea_custom_email_bulk_action' );
		//custom lead post action
		$this->loader->add_filter( 'manage_aretk_lead_posts_columns', $plugin_admin, 'aretkcrea_set_custom_edit_aretk_lead_columns', 10, 1 );
		$this->loader->add_filter( 'manage_edit-aretk_lead_sortable_columns', $plugin_admin, 'aretkcrea_custom_aretk_lead_sortable' );
		$this->loader->add_action( 'manage_aretk_lead_posts_custom_column', $plugin_admin, 'aretkcrea_set_custom_edit_aretk_lead_content', 10, 2 );
		//$this->loader->add_action( 'request' , $plugin_admin,'request_custom');
		//Emport Lead download ajax call
		$this->loader->add_action( 'wp_ajax_aretkcrea_emport_lead_download', $plugin_admin, 'aretkcrea_emport_lead_download' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_emport_lead_download', $plugin_admin, 'aretkcrea_emport_lead_download' );
		// delete showcase post
		$this->loader->add_action( 'wp_ajax_aretkcrea_delete_showcase_custom_post_records', $plugin_admin, 'aretkcrea_delete_showcase_custom_post_records' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_delete_showcase_custom_post_records', $plugin_admin, 'aretkcrea_delete_showcase_custom_post_records' );
		//Unlink file document
		$this->loader->add_action( 'wp_ajax_aretkcrea_unlink_listing_document_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_unlink_listing_document_edit_page_from_listing_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_unlink_listing_document_edit_page_from_listing_ajax', $plugin_admin, 'aretkcrea_unlink_listing_document_edit_page_from_listing_ajax' );
		//add lead reminder
		$this->loader->add_action( 'wp_ajax_aretkcrea_add_new_lead_reminder', $plugin_admin, 'aretkcrea_add_new_lead_reminder' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_add_new_lead_reminder', $plugin_admin, 'aretkcrea_add_new_lead_reminder' );
		//Remove lead reminder
		$this->loader->add_action( 'wp_ajax_aretkcrea_remove_crea_lead_reminder', $plugin_admin, 'aretkcrea_remove_crea_lead_reminder' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_remove_crea_lead_reminder', $plugin_admin, 'aretkcrea_remove_crea_lead_reminder' );
		//update lead reminder
		$this->loader->add_action( 'wp_ajax_aretkcrea_update_crea_lead_reminder', $plugin_admin, 'aretkcrea_update_crea_lead_reminder' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_update_crea_lead_reminder', $plugin_admin, 'aretkcrea_update_crea_lead_reminder' );
		//ajax call for get board name and id by api
		$this->loader->add_action( 'wp_ajax_aretkcrea_get_the_select_board_name', $plugin_admin, 'aretkcrea_get_the_select_board_name' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_get_the_select_board_name', $plugin_admin, 'aretkcrea_get_the_select_board_name' );
		//ajax call for get the selected office by api
		$this->loader->add_action( 'wp_ajax_aretkcrea_get_the_select_board_office', $plugin_admin, 'aretkcrea_get_the_select_board_office' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_get_the_select_board_office', $plugin_admin, 'aretkcrea_get_the_select_board_office' );
		//ajax call for get the selected agents by api
		$this->loader->add_action( 'wp_ajax_aretkcrea_get_the_select_board_agent_name', $plugin_admin, 'aretkcrea_get_the_select_board_agent_name' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_get_the_select_board_agent_name', $plugin_admin, 'aretkcrea_get_the_select_board_agent_name' );
		$this->loader->add_action( 'wp_ajax_aretkcrea_add_default_listing_setting', $plugin_admin, 'aretkcrea_add_default_listing_setting' );
		$this->loader->add_action( 'wp_ajax_nopriv_aretkcrea_add_default_listing_setting', $plugin_admin, 'aretkcrea_add_default_listing_setting' );
		$this->loader->add_action( 'manage_edit-lead-category_columns', $plugin_admin, 'aretkcrea_remove_lead_category_post_count' );
		$this->loader->add_filter( 'admin_body_class', $plugin_admin, 'aretkcrea_admin_body_classes' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {
		global $post;
		$plugin_public = new Aretk_Crea_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'aretkcrea_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'aretkcrea_enqueue_scripts' );
		$this->loader->add_action( 'wp_head', $plugin_public, 'wp_head_custom' );
		$this->loader->add_action( 'ARTEK-CF', $plugin_public, 'crea_genaral_contactform' );	
		add_shortcode( 'ARTEK-CF', array( $plugin_public, 'crea_genaral_contactform' ) );
		$this->loader->add_action( 'ARTEK-BF', $plugin_public, 'crea_aretk_bfform' );
		add_shortcode( 'ARTEK-BF', array( $plugin_public, 'crea_aretk_bfform' ) );
		$this->loader->add_action( 'ARTEK-SF', $plugin_public, 'crea_aretk_sfform' );
		add_shortcode( 'ARTEK-SF', array( $plugin_public, 'crea_aretk_sfform' ) );
		add_shortcode( 'ARTEK-DLS', array( $plugin_public, 'create_artekdls_shortcode' ) );
		add_shortcode( 'ARETK-DSS', array( $plugin_public, 'create_artekdss_shortcode' ) );
		add_shortcode( 'ARETK-LDS', array( $plugin_public, 'create_arteklds_shortcode' ) );
		$this->loader->add_filter( 'query_vars', $plugin_public, 'add_query_vars' );
		$this->loader->add_filter( 'rewrite_rules_array', $plugin_public, 'aretkcrea_add_rewrite_rules' );
		if ( function_exists( '_wp_render_title_tag' ) ) {
			$this->loader->add_action( 'pre_get_document_title', $plugin_public, 'assignPageTitle', 99, 2 );
		}
		$this->loader->add_action( 'wp_title', $plugin_public, 'assignPageTitle', 99, 2 );
		$this->loader->add_filter( 'the_title', $plugin_public, 'replace_title_propertydetails', 10, 2 );

		//Reminder email send every miniute
		$this->loader->add_filter( 'cron_schedules', $plugin_public, 'reminder_cron_schedule', 10, 2 );
		$this->loader->add_action( 'content_scheduler_reminder_every_minute', $plugin_public, 'content_scheduler_reminder_minute_send_email' );

		// cron schedules hooks for subscription
		$this->loader->add_action( 'content_scheduler_subscription', $plugin_public, 'aretk_subscription_cron_function_to_run' );
		// add an action hook for expiration check and notification check for api
		$this->loader->add_action( 'content_scheduler_expiration_event', $plugin_public, 'aretkcrea_answer_expiration_event' );
		$this->loader->add_action( 'wp_ajax_buyer_lead_submit_form_front_end', $plugin_public, 'buyer_lead_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_nopriv_buyer_lead_submit_form_front_end', $plugin_public, 'buyer_lead_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_seller_lead_submit_form_front_end', $plugin_public, 'seller_lead_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_nopriv_seller_lead_submit_form_front_end', $plugin_public, 'seller_lead_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_conatact_submit_form_front_end', $plugin_public, 'conatact_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_nopriv_conatact_submit_form_front_end', $plugin_public, 'conatact_submit_form_front_end' );
		$this->loader->add_action( 'wp_ajax_property_listing_contact_form', $plugin_public, 'property_listing_contact_form' );
		$this->loader->add_action( 'wp_ajax_nopriv_property_listing_contact_form', $plugin_public, 'property_listing_contact_form' );
		$this->loader->add_action( 'wp_ajax_showcase_property_search_custom', $plugin_public, 'showcase_property_search_custom' );
		$this->loader->add_action( 'wp_ajax_nopriv_showcase_property_search_custom', $plugin_public, 'showcase_property_search_custom' );
		$this->loader->add_action( 'wp_ajax_grid_view_ajax_pagination', $plugin_public, 'grid_view_ajax_pagination' );
		$this->loader->add_action( 'wp_ajax_nopriv_grid_view_ajax_pagination', $plugin_public, 'grid_view_ajax_pagination' );
		$this->loader->add_action( 'wp_ajax_custom_ajax_for_map_view_infobox', $plugin_public, 'custom_ajax_for_map_view_infobox' );
		$this->loader->add_action( 'wp_ajax_nopriv_custom_ajax_for_map_view_infobox', $plugin_public, 'custom_ajax_for_map_view_infobox' );
		$this->loader->add_action( 'wp_ajax_custom_ajax_for_map_view_dragend', $plugin_public, 'custom_ajax_for_map_view_dragend' );
		$this->loader->add_action( 'wp_ajax_nopriv_custom_ajax_for_map_view_dragend', $plugin_public, 'custom_ajax_for_map_view_dragend' );
		$this->loader->add_action( 'wp_ajax_check_terms_and_condition_accept', $plugin_public, 'check_terms_and_condition_accept' );
		$this->loader->add_action( 'wp_ajax_nopriv_check_terms_and_condition_accept', $plugin_public, 'check_terms_and_condition_accept' );
		$this->loader->add_action( 'wp_ajax_check_terms_and_condition_decline', $plugin_public, 'check_terms_and_condition_decline' );
		$this->loader->add_action( 'wp_ajax_nopriv_check_terms_and_condition_decline', $plugin_public, 'check_terms_and_condition_decline' );

		//Call Curl in init
		$this->loader->add_action( 'init', $plugin_public, 'call_wp_schedule_event' );
		$this->loader->add_filter( 'body_class', $plugin_public, 'aretk_body_classes' );
		$get_all_aretk_showcase = new WP_Query( array(
			'post_type'      => 'aretk_showcase',
			'post_status'    => 'publish',
			'posts_per_page' => - 1
		) );
		if ( isset( $get_all_aretk_showcase->posts ) && ! empty( $get_all_aretk_showcase->posts ) ) {
			foreach ( $get_all_aretk_showcase->posts as $get_all_aretk_showcase ) {
				add_shortcode( 'ARETK-LS-' . $get_all_aretk_showcase->ID, array(
					$plugin_public,
					'aretk_showcase_listing'
				) );
			}
		}
	}
}