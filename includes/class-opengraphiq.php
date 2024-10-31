<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Opengraphiq {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Opengraphiq_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $opengraphiq    The string used to uniquely identify this plugin.
	 */
	protected $opengraphiq;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
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
		if ( defined( 'OPENGRAPHIQ_VERSION' ) ) {
			$this->version = OPENGRAPHIQ_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->opengraphiq = 'opengraphiq';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Opengraphiq_Loader. Orchestrates the hooks of the plugin.
	 * - Opengraphiq_i18n. Defines internationalization functionality.
	 * - Opengraphiq_Admin. Defines all hooks for the admin area.
	 * - Opengraphiq_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-opengraphiq-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-opengraphiq-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-opengraphiq-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-opengraphiq-public.php';

		$this->loader = new Opengraphiq_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Opengraphiq_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Opengraphiq_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Opengraphiq_Admin( $this->opengraphiq_get(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		//create custom post type templates

		$this->loader->add_action( 'init', $plugin_admin, 'opengraphiq_template_custom_post_type' );
		$this->loader->add_action( 'edit_form_after_title', $plugin_admin, 'add_VueApp' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'opengraphiq_save_templates_field_meta' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'opengraphiq_plugin_setup_menu'); 
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'opengraphiq_notices');
		$this->loader->add_action( 'wp_ajax_bulk_ajax', $plugin_admin, 'opengraphiq_bulk_ajax');
		$this->loader->add_action( 'wp_ajax_bulk_ajax_create_photo', $plugin_admin, 'opengraphiq_bulk_ajax_create_photo');
		$this->loader->add_action( 'wp_ajax_test_ajax', $plugin_admin, 'opengraphiq_test_ajax'); 
		$this->loader->add_action( 'wp_ajax_save_single_post_meta', $plugin_admin, 'ajax_save_singlepost_field_meta'); 
		$this->loader->add_action( 'admin_init', $plugin_admin, 'opengraphiq_admin_init_filters' );
		$this->loader->add_action( 'add_meta_boxes', $plugin_admin, 'opengraphiq_post_metabox' );
		$this->loader->add_action( 'save_post', $plugin_admin, 'save_singlepost_field_meta' );
		$this->loader->add_action( 'restrict_manage_posts', $plugin_admin, 'opengraphiq_restrict_manage_posts' );
		$this->loader->add_action( 'quick_edit_custom_box', $plugin_admin, 'opengraphiq_quick_edit_fields', 10, 2 );
		$this->loader->add_action( 'wp_trash_post', $plugin_admin, 'opengraphiq_on_trash_template' );
		$this->loader->add_action( 'do_meta_boxes', $plugin_admin, 'opengraphiq_remove_featured_image');
		
		$this->loader->add_filter( 'user_can_richedit', $plugin_admin, 'disable_visual_editor' );
		$this->loader->add_filter( 'parse_query', $plugin_admin, 'opengraphiq_parse_posts_query' );

		//$this->loader->add_filter( 'redirect_post_location', $plugin_admin, 'redirect_post_location', 10, 2 );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Opengraphiq_Public( $this->opengraphiq_get(), $this->get_version() );

		$this->loader->add_action( 'wp_head', $plugin_public, 'start_head_buffer', PHP_INT_MIN );
		$this->loader->add_action( 'wp_head', $plugin_public, 'end_head_buffer', PHP_INT_MAX );
		$this->loader->add_action( 'wp_head', $plugin_public, 'opengraphiq_insert_tags', 1 );

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
	public function opengraphiq_get() {
		return $this->opengraphiq;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Opengraphiq_Loader    Orchestrates the hooks of the plugin.
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

}
