<?php

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

class Opengraphiq_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $opengraphiq    The ID of this plugin.
	 */
	private $opengraphiq;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $opengraphiq       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */

	private $option_name = 'opengraphiq_setting';
	private $plugin_name = 'opengraphiq'; 

	public function __construct( $opengraphiq, $version ) {

		$this->opengraphiq = $opengraphiq;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook_suffix ) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Opengraphiq_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Opengraphiq_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$cpt = 'opengraphiqtemplates';

		global $wp_scripts;
		// Create a handle for the jquery-ui-core css.
		$handleui = 'jquery-ui';
		// Path to stylesheet, based on the jquery-ui-core version used in core.
		$srcui = plugin_dir_url( __FILE__ ) . "css/" . $handleui. ".css";
		// Required dependencies
		$depsui = array();
		// Add stylesheet version.
		$verui = $wp_scripts->registered['jquery-ui-core']->ver;
		// Register the stylesheet handle.
		wp_register_style( $handleui, $srcui, $depsui, $verui );

    	if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        	$screen = get_current_screen();

        	if( is_object( $screen ) && $cpt == $screen->post_type ){

				wp_enqueue_style( 'jquery-ui' );
				wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-admin.css', array( 'jquery-ui' ), $this->version, 'all' );

        	} else {
				if( is_object( $screen ) ){
					wp_enqueue_style( 'jquery-ui' );
					wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-admin.css', array('jquery-ui'), $this->version, 'all' );
					add_thickbox();
				}
			}
    	}

		// post lists in wp-admin
		if( in_array($hook_suffix, array('edit.php') ) ){
			wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-admin.css', array(), $this->version, 'all' );
			add_thickbox();
		}

		if( in_array($hook_suffix, array('opengraphiqtemplates_page_opengraphiq_bulk') ) ){
			wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-admin.css', array(), $this->version, 'all' );
		}

		if( in_array($hook_suffix, array('opengraphiqtemplates_page_opengraphiq_settings') ) ){
			wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-admin.css', array(), $this->version, 'all' );
		}
		
		wp_enqueue_style( $this->opengraphiq, plugin_dir_url( __FILE__ ) . 'css/opengraphiq-icon.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts($hook_suffix) {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Opengraphiq_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Opengraphiq_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		$cpt = 'opengraphiqtemplates';

    	if( in_array($hook_suffix, array('post.php', 'post-new.php') ) ){
        	$screen = get_current_screen();

        	if( is_object( $screen ) && $cpt == $screen->post_type ){

				wp_enqueue_script( $this->opengraphiq . 'cp', plugin_dir_url( __FILE__ ) . 'js/opengraphiq-admin.js', array( 'jquery', 'html2canvas' ), $this->version, false );
				wp_enqueue_script( 'html2canvas', plugin_dir_url( __FILE__ ) . 'js/html2canvas.min.js', array( 'jquery' ), $this->version, false );
				wp_enqueue_script( 'jquery-ui-resizable' );
				wp_enqueue_script( 'jquery-ui-sortable' );
				wp_enqueue_script( 'jquery-ui-draggable' );
				wp_enqueue_script( 'jquery-ui-selectable' );
				wp_enqueue_script( 'jquery-ui-tabs' );

				wp_enqueue_media();

				if($this->is_woocommerce_activated()) {
					$woo_comm = 'true';
				} else {
					$woo_comm = 'false';
				}

				wp_localize_script( $this->opengraphiq . 'cp', 'opengraphiqJS', array( 'pluginfolder' => plugin_dir_url( __FILE__ ) . 'resources/'));
				wp_localize_script( $this->opengraphiq . 'cp', 'opengraphiqAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));  
				
				wp_localize_script( $this->opengraphiq . 'cp', 'adminJStranslations', array( 
					'skipped' => esc_html__('Skipped', 'opengraphiq'),
					'finished' => esc_html__('Finished', 'opengraphiq'),
					'working' => esc_html__('Working', 'opengraphiq'),
					'nonxisting' => esc_html__('Non existing post', 'opengraphiq'),
					'woo_comm' => $woo_comm,
				));

				//spisak svih vec postojecih scriptova - https://developer.wordpress.org/reference/functions/wp_enqueue_script/
        	} else {
				if( is_object( $screen ) ){
					wp_enqueue_script( 'html2canvas', plugin_dir_url( __FILE__ ) . 'js/html2canvas.min.js', array( 'jquery' ), $this->version, false );
					wp_enqueue_script( $this->opengraphiq . 'single-post', plugin_dir_url( __FILE__ ) . 'js/opengraphiq-single-post.js', array( 'jquery', 'html2canvas' ), $this->version, false );

					wp_localize_script( $this->opengraphiq . 'single-post', 'opengraphiqAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
					wp_localize_script( $this->opengraphiq . 'single-post', 'adminJStranslations', array( 
						'skipped' => esc_html__('Skipped', 'opengraphiq'),
						'finished' => esc_html__('Finished', 'opengraphiq'),
						'working' => esc_html__('Working', 'opengraphiq'),
					));
				}
			}
    	}

		if( in_array($hook_suffix, array('edit.php') ) ){
			wp_enqueue_script( $this->opengraphiq . 'cp', plugin_dir_url( __FILE__ ) . 'js/opengraphiq-post-list.js', array( 'jquery'), $this->version, false );
		}

		if( in_array($hook_suffix, array('opengraphiqtemplates_page_opengraphiq_bulk') ) ){
			wp_enqueue_script( $this->opengraphiq . 'bulk', plugin_dir_url( __FILE__ ) . 'js/bulkgenerate.js', array( 'jquery', 'html2canvas' ), $this->version, false );
			wp_enqueue_script( 'html2canvas', plugin_dir_url( __FILE__ ) . 'js/html2canvas.min.js', array( 'jquery' ), $this->version, false );

			wp_localize_script( $this->opengraphiq . 'bulk', 'opengraphiqAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
			wp_localize_script( $this->opengraphiq . 'bulk', 'adminJStranslations', array( 
				'skipped' => esc_html__('Skipped', 'opengraphiq'),
				'finished' => esc_html__('Finished', 'opengraphiq'),
				'working' => esc_html__('Working', 'opengraphiq'),
			));
		}

		if( in_array($hook_suffix, array('opengraphiqtemplates_page_opengraphiq_settings') ) ){
			wp_enqueue_script( $this->opengraphiq . 'settings', plugin_dir_url( __FILE__ ) . 'js/opengraphiq-admin-settings.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_media(); 
		}

	}

	/**
	 * Create Templates Custom Post Type
	 *
	 * @since     1.0.0
	 * @access   private
	 */

	public function opengraphiq_template_custom_post_type() {

		$labels = array(
			'name'                => esc_html__( 'OG Templates', 'opengraphiq' ),
			'singular_name'       => esc_html__( 'OG Template',  'opengraphiq' ),
			'menu_name'           => esc_html__( 'OpenGraphiq', 'opengraphiq' ),
			'all_items'           => esc_html__( 'All OG Templates', 'opengraphiq' ),
			'view_item'           => esc_html__( 'View OG Template', 'opengraphiq' ),
			'add_new_item'        => esc_html__( 'Add New OG Template', 'opengraphiq' ),
			'add_new'             => esc_html__( 'Add New', 'opengraphiq' ),
			'edit_item'           => esc_html__( 'Edit OG Template', 'opengraphiq' ),
			'update_item'         => esc_html__( 'Update OG Template', 'opengraphiq' ),
			'search_items'        => esc_html__( 'Search OG Templates', 'opengraphiq' ),
			'not_found'           => esc_html__( 'Not Found', 'opengraphiq' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'opengraphiq' ),
		);
	
		$args = array(
			'label'               => esc_html__( 'templates', 'opengraphiq' ),
			'description'         => esc_html__( 'OpenGraphiq templates', 'opengraphiq' ),
			'labels'              => $labels,  
			'supports'            => array( 'title', 'editor', 'author', 'thumbnail', 'revisions', ),     
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => true,
			'show_in_admin_bar'   => true,
			'menu_position'       => 5,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'show_in_rest'	=> false, 
		);
		
		register_post_type( 'opengraphiqtemplates', $args );
	}

	private function is_woocommerce_activated() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}

	private function get_all_posttypes(){
		$args = array(
			'public' => true,
			'publicly_queryable' => true,
			'show_ui' => true,
		);
		$post_types = get_post_types($args);
		$post_types['page'] = 'page';
		unset($post_types['attachment']);
		return $post_types;
	}

	public function opengraphiq_get_posttypes(){
		$optionval = get_option( 'opengraphiq_setting_default_template');
		$finalresult = [];
		if( $optionval == false ) {
			return $finalresult;
		} else {
			$finalresult = ['post'];
		}
		return $finalresult;
	}

	public function opengraphiq_admin_init_filters(){

		$post_types = $this->opengraphiq_get_posttypes();

		add_filter( 'wp_import_posts', array($this, 'opengraphiq_import_filter'), 10, 1 );

		foreach ( $post_types as $post_type ){
			add_filter( 'bulk_actions-edit-' . $post_type, array($this,'opegraphiq_addbulkaction') );
			add_filter( 'handle_bulk_actions-edit-'. $post_type, array($this, 'opengraphiq_bulk_redirect'), 10, 3 );

			add_filter( 'manage_' . $post_type . '_posts_columns', function($columns){
				return array_merge( $columns, ['ogimage' => esc_html__('OG image', 'opengraphiq'), 'ogtemplate' => esc_html__('OG template', 'opengraphiq')] );
			});

			add_action( 'manage_' . $post_type . '_posts_custom_column', function($column_key, $post_id){

				if($column_key == 'ogimage' ){
					$template = $this -> opengraphiq_get_template_by_id( null, $post_id);
					if ($template != '') {
						$imageurl = get_post_meta( $post_id, 'opengraphiqtemplates_image', true );
						$imagedate = get_post_meta( $post_id, 'opengraphiqtemplates_time', true );
						if ($imageurl != ''){
							echo '<div class="list-og-image">
							<a href="'. esc_attr($imageurl) .'?ts=' . esc_attr(strtotime($imagedate)) . '" title="" class="thickbox" rel="og-gallery"><img src="'. esc_attr($imageurl) .'?ts=' . esc_attr(strtotime($imagedate)) . '" /></a></div>';
						} else {
							echo '<div class="list-og-image"><span>' . esc_html__('None', 'opengraphiq') . '</span></div>';
						}
					} else {
						echo '<div class="list-og-image"><span>' . esc_html__('OG disabled', 'opengraphiq') . '</span></div>';
					}
				}

				if($column_key == 'ogtemplate' ){
					$template = $this -> opengraphiq_get_template_by_id( null, $post_id);
					if ( $template != '' ){
						reset($template);
						$current_meta_value = get_post_meta($post_id, $this->option_name . '_post_meta', true );
						
						$currenttemplateid = key($template);
						$result = get_post($currenttemplateid) -> post_title;
						if ($result){
							echo '<div data-templateid = "' . esc_attr($current_meta_value) . '">' . esc_html($result) . '</div>';
						} else {
							echo '<div data-templateid = "-1">' . esc_html__('None', 'opengraphiq') . '</div>';
						}
					} else {
						echo '<div data-templateid = "0">' . esc_html__('None', 'opengraphiq') . '</div>';
					}
				}

			}, 10, 2);

		}
		

		add_filter( 'manage_opengraphiqtemplates_posts_columns', function($columns){
			return array_merge( $columns, ['ogimage' => esc_html__('OG image', 'opengraphiq')] );
		});

		add_action( 'manage_opengraphiqtemplates_posts_custom_column', function($column_key, $post_id){

			if($column_key == 'ogimage' ){
				$imageurl = get_post_meta( $post_id, 'opengraphiqtemplates_image', true );
				$imagedate = get_post_meta( $post_id, 'opengraphiqtemplates_time', true );
				if ($imageurl != ''){
					echo '<div class="list-og-image">
							<a href="'. esc_attr($imageurl) .'?ts=' . esc_attr(strtotime($imagedate)) . '" title="" class="thickbox" rel="og-gallery"><img src="'. esc_attr($imageurl) .'?ts=' . esc_attr(strtotime($imagedate)) . '" alt="OpenGraphiq Image" /></a></div>';
				} else {
					echo '<div class="list-og-image"><span>' . esc_html__('None', 'opengraphiq') . '</span></div>';
				}
			}

		}, 10, 2);
	}

	public function opengraphiq_import_filter($posts) {
		$btopengraphfolderurl = plugin_dir_url( __FILE__ );
		$btopengraphfolderurl = str_replace('admin/', 'demo/assets' , $btopengraphfolderurl);
		$btopengraphassetsfolderurl = plugin_dir_url( __FILE__ );
		$btopengraphassetsfolderurl = str_replace('admin/', 'admin/resources' , $btopengraphassetsfolderurl);

		foreach ( $posts as &$post ) {
			$post['post_content'] = str_replace('bt-opengraph-folder-url', $btopengraphfolderurl , $post['post_content']);
			$post['post_content'] = str_replace('bt-opengraph-assets-folder-url', $btopengraphassetsfolderurl, $post['post_content']);
			foreach ( $post['postmeta'] as &$metaitem ) {
				$metaitem['value'] = str_replace('bt-opengraph-folder-url', $btopengraphfolderurl , $metaitem['value']);
				$metaitem['value'] = str_replace('bt-opengraph-assets-folder-url', $btopengraphassetsfolderurl, $metaitem['value']);
			}
			
		}
		return($posts);
	}

	public function opengraphiq_remove_featured_image() {
		
		$cpt = 'opengraphiqtemplates';
		remove_meta_box( 'postimagediv', $cpt, 'side' );
	}

	public function opengraphiq_quick_edit_fields( $column_name, $post_type ){

		if( $column_name == 'ogtemplate' ){

			$args = array(
				'post_type' => 'opengraphiqtemplates',
				'posts_per_page' => -1,
				'post_status' => 'publish'
			);
	
			$posts = get_posts( $args );
	
			$templateselect = [];
			foreach ( $posts as $posts) {
				$templateselect[ $posts->ID ] = $posts->post_title;
			}	
			
			echo '<fieldset class="inline-edit-col-right">
				<div class="inline-edit-col">
					<div class="inline-edit-group wp-clearfix">';

			wp_nonce_field( basename(__FILE__), 'og_template_nonce' );

			echo '<label class="alignleft">
					<span class="title">' . esc_html__('OG Template', 'opengraphiq') . ' </span>
					<select name="' . esc_attr($this->option_name) . '_post_meta' . '" id="' . esc_attr($this->option_name) . '_post_meta' . '">';
			echo '<option value="-1">' . esc_html__( ' Inherit from Settings ', 'opengraphiq' ) . '</option>';
			echo '<option value="0">' . esc_html__( ' No image by OpenGraphiq ', 'opengraphiq' ) . '</option>';
			foreach ( $templateselect as $key => $val ){
				echo '<option value="' . esc_attr($key) . '">' . esc_attr($val) . '</option>';
			} 
			echo	'</select></label></div></div></fieldset>';
		}

	}

	public function opengraphiq_restrict_manage_posts(){
		global $wpdb, $table_prefix;
        $post_type = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : 'post';

		$post_types = $this->opengraphiq_get_posttypes();

		if (in_array($post_type, $post_types)){
			echo('<select name="opnegraphiq_filter_image">');
			echo('<option value="">' . esc_html__('Filter by OG image', 'opengraphiq') . '</option>');
			echo('<option value="image">' . esc_html__('With OG image', 'opengraphiq') . '</option>');
			echo('<option value="noimage">' . esc_html__('Without OG image', 'opengraphiq') . '</option>');
			echo('</select>');
		}
	}

	public function opengraphiq_parse_posts_query( $query ){
		global $pagenow;
        $post_type = (isset($_GET['post_type'])) ? sanitize_text_field($_GET['post_type']) : 'post';

		$post_types = $this->opengraphiq_get_posttypes();

		if ( in_array( $post_type, $post_types ) && $pagenow == 'edit.php' && isset( $_GET[ 'opnegraphiq_filter_image' ] ) && ! empty( $_GET [ 'opnegraphiq_filter_image' ] )){

			$filter_val = sanitize_text_field($_GET['opnegraphiq_filter_image']);
			
			if ($filter_val == 'image') 
				$filter_compare = 'EXISTS';
			else
				$filter_compare = 'NOT EXISTS';
			
			$query->query_vars['meta_query'] = array(
				'relation' => 'AND',
				array(
					'key'     => 'opengraphiqtemplates_image',
					'compare' => $filter_compare,
				),
			);
		}
	}
	
	public function opengraphiq_on_trash_template($post_id){
		if ( get_post_type($post_id) == 'opengraphiqtemplates' ){
			
			$rd_args = array(
				'meta_query' => array(
					array(
						'key' => $this->option_name . '_post_meta',
						'value' => $post_id
					)
				)
			);
			 
			$posts = get_posts( $rd_args );

			foreach( $posts as $post ) { 
				delete_post_meta( $post -> ID, $this->option_name . '_post_meta');
			}

			$pages = get_pages( $rd_args );

			foreach( $pages as $page ) { 
				delete_post_meta( $page -> ID, $this->option_name . '_post_meta');
			}

			if ( get_option( 'opengraphiq_setting_default_template' ) == $post_id) {
				delete_option ( 'opengraphiq_setting_default_template' );
			}

			$optionval = get_option( $this->option_name . '_cp_template' );

			if($optionval){

				foreach ($optionval as &$value) {
					if ( $value == $post_id ) $value = '-1';
				}
				update_option( $this->option_name . '_cp_template', $optionval );
			}
		}
	}

	public function opengraphiq_plugin_setup_menu(){
		add_submenu_page('edit.php?post_type=opengraphiqtemplates', 'OpenGraphiq Settings',  'Settings', 'manage_options', 'opengraphiq_settings', array($this, 'opengraphiq_init'));
		add_submenu_page('edit.php?post_type=opengraphiqtemplates', 'Bulk Edit Posts',  'Bulk Edit', 'edit_posts', 'opengraphiq_bulk', array($this, 'opengraphiq_bulk_init'));
		remove_submenu_page( 'edit.php?post_type=opengraphiqtemplates', 'opengraphiq_bulk' );
	} 

	public function opengraphiq_post_metabox(){
		$post_types = $this -> opengraphiq_get_posttypes();
		foreach ( $post_types as $post_type ) {
			add_meta_box(
				'opengraphiq_post_metabox',
				esc_html__( 'OpenGraphiq Settings', 'opengaphiq' ),
				array($this, 'opengraphiq_post_metabox_callback'),
				$post_type,
				'side',
			);
		}
	}

	public function opengraphiq_post_metabox_callback($post){
		

		$nonce_value = wp_create_nonce( basename(__FILE__));
		$img_url = get_post_meta( $post->ID, 'opengraphiqtemplates_image', true );

		include OPENGRAPHIQ_PATH . 'admin/partials/opengraphiq-admin-meta-box.php' ;
	}

	public function disable_visual_editor() {
		global $post;

		$post_type = get_post_type($post);
		if ( $post_type == 'opengraphiqtemplates' ) {
			return false;
		} else {
			return true;
		}
	}

	public function add_VueApp($post){  
		if($post->post_type == 'opengraphiqtemplates'){
			echo '<div id="opengraphiqpostcanvas" class="og-post-canvas"></div>';
			$meta = get_post_meta( $post->ID, 'opengraphiqtemplates_image', true );
			$meta_selected = get_post_meta( $post->ID, 'opengraphiqtemplates_selected', true );
			$meta_testwith = get_post_meta( $post->ID, 'opengraphiqtemplates_testwith', true );
			if ( ! $meta ) $meta = '{}';
			echo '<div class="og-post-canvas">';
				echo '<div class="og-buttons">';
					echo '<input type="hidden" name="opengraphiqtemplates_nonce" value="' . wp_create_nonce( basename(__FILE__)) . '">';
					echo '<br>';
					echo '<input type="hidden" name="opengraphiqtemplates_image" id="opengraphiqtemplates_image" value="' . esc_attr($meta) . '">';
					echo '<input type="hidden" name="opengraphiqtemplates_selected" id="opengraphiqtemplates_selected" value="' . esc_attr($meta_selected) . '">';
					echo '<input type="hidden" name="opengraphiqtemplates_data" id="opengraphiqtemplates_data" value="">';
					echo '<input type="hidden" name="og_test_template_nonce" id="og_test_template_nonce" value="' . wp_create_nonce( basename(__FILE__)) . '">';
					echo '<label for="og_test_id">' . esc_html__('Enter post id and test this template: ', 'opengraphiq'). '';
					echo '<input type="text" name="og_test_id" id="og_test_id" value="' . esc_attr($meta_testwith) . '">';
					echo '</label>';
					echo '<div id="og_test_button" class="button">' . esc_html__('Test the template', 'opengraphiq'). '</div>';
				echo '</div>';
				echo '<div id="test-canvasImg"></div>';
				echo '<div id="opengraphiqtestcanvas" class="og-post-canvas og-post-canvas-clean"></div>';
				echo '<div class="og-overlay">' . esc_html__('Saving template', 'opengraphiq'). '</div>';
			echo '</div>';

		}
	}

	public function fn_get_upload_dir_var( $param, $subfolder = '' ) {
		$upload_dir = wp_upload_dir();
		$url = $upload_dir[ $param ];
	 
		if ( $param === 'baseurl' && is_ssl() ) {
			$url = str_replace( 'http://', 'https://', $url );
		}

		$url = str_replace( get_site_url(), '', $url);
	 
		return $url . $subfolder;
	}

	public function opengraphiq_save_templates_field_meta( $post_id ) {

    	// verify nonce
		
		if ( get_post_type($post_id) == 'opengraphiqtemplates' && isset($_POST['opengraphiqtemplates_nonce'])){
			if ( !wp_verify_nonce( $_POST['opengraphiqtemplates_nonce'], basename(__FILE__) ) ) {
					return $post_id;
			}
		} else {
			return $post_id;
		}
			
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check permissions
		if ( 'page' === $_POST['post_type'] ) {
			if ( !current_user_can( 'edit_page', $post_id ) ) {
				return $post_id;
			} elseif ( !current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		$element_selected = (int) $_POST['opengraphiqtemplates_selected'];
		$id_testwith = (int) $_POST['og_test_id'];
		
		$upload_dir   = wp_upload_dir();
 
		if ( ! empty( $upload_dir['basedir'] ) ) {
			$dirname = $upload_dir['basedir'].'/opengraphiq';
			if ( ! file_exists( $dirname ) ) {
				wp_mkdir_p( $dirname );
			}
		}

		$img = sanitize_text_field($_POST['opengraphiqtemplates_data']);
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);

		//saving
		$fileName = $dirname . '/' . $post_id . '.jpg';
		file_put_contents($fileName, $fileData);
	

		$old = get_post_meta( $post_id, 'opengraphiqtemplates_image', true );
		$new = $this->fn_get_upload_dir_var( 'baseurl', '/opengraphiq/' ) . $post_id . '.jpg';
		
		update_post_meta( $post_id, 'opengraphiqtemplates_time', date("Y-m-d H:i:s") );
		update_post_meta( $post_id, 'opengraphiqtemplates_selected', $element_selected );
		update_post_meta( $post_id, 'opengraphiqtemplates_testwith', $id_testwith );

		if ( $new && $new !== $old ) {
			update_post_meta( $post_id, 'opengraphiqtemplates_image', $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, 'opengraphiqtemplates_image' );
			delete_post_meta($post_id, 'opengraphiqtemplates_time');
		}
		
		return $post_id;
    }

	public function save_singlepost_field_meta( $post_id ) {

    	// verify nonce
		
		if ( isset($_POST[$this->option_name . '_post_meta']) && isset($_POST['og_template_nonce'])){
			if ( !wp_verify_nonce( $_POST['og_template_nonce'], basename(__FILE__) ) ) {
					return $post_id;
			}
		} else {
			return $post_id;
		}
			
		// check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// check permissions
		if (!current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
		
		
		$old = get_post_meta( $post_id, $this->option_name . '_post_meta', true );

		if (sanitize_text_field($_POST[$this->option_name . '_post_meta']) == '-1' ){
			$new = '';
		} else {
			$new = sanitize_text_field( $_POST[$this->option_name . '_post_meta'] );
		}

		if ( $new !== $old ) {
			if( $new == '' ){
				delete_post_meta( $post_id, $this->option_name . '_post_meta' );
			} else {
				update_post_meta( $post_id, $this->option_name . '_post_meta', $new );
			}
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, $this->option_name . '_post_meta' );
		}
		
		return $post_id;
    }

	public function ajax_save_singlepost_field_meta()  {

    	// verify nonce
		
		if ( isset($_POST[$this->option_name . '_post_meta']) && isset($_POST['og_template_nonce']) && isset($_POST['id'])){
			$post_id = sanitize_text_field($_POST['id']);
			if ( !wp_verify_nonce( $_POST['og_template_nonce'], basename(__FILE__) ) ) {
				die();
			}
		} else {
			die();
		}

		// check permissions
		if (!current_user_can( 'edit_post', $post_id ) ) {
			die();
		}
		
		
		$old = get_post_meta( $post_id, $this->option_name . '_post_meta', true );

		if (sanitize_text_field($_POST[$this->option_name . '_post_meta']) == '-1' ){
			$new = '';
		} else {
			$new = sanitize_text_field( $_POST[$this->option_name . '_post_meta'] );
		}

		if ( $new !== $old ) {
			if( $new == '' ){
				delete_post_meta( $post_id, $this->option_name . '_post_meta' );
			} else {
				update_post_meta( $post_id, $this->option_name . '_post_meta', $new );
			}
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $post_id, $this->option_name . '_post_meta' );
		}
		
		echo 'ok';
		die();
    }
	
	public function opengraphiq_setting_default_template_cb() {
		$val = get_option( $this->option_name . '_default_template' );

		$args = array(
			'post_type' => 'opengraphiqtemplates',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);

		$query = new WP_Query($args);

		if ($query->have_posts() ) {
			echo '<select name="' . esc_attr($this->option_name) . '_default_template' . '" id="' . esc_attr($this->option_name) . '_default_template' . '" value="' . esc_attr($val) . '">';
			echo '<option value="-1">' . esc_html__('Choose the default template', 'opengraphiq') . '</option>';
			while ( $query->have_posts() ) : $query->the_post();
				$pid = get_the_ID();
				$pid == $val ? $sel = 'selected="selected"' : $sel='';
				echo '<option value="' . esc_attr(get_the_ID()) . '" ' . esc_attr($sel) . '>' . esc_html(get_the_title()) . '</option>';
			endwhile;
		echo '</select>';
		} else {
			echo '<strong style="color:#f00">' . esc_html__('There are no templates defined. Please import premade templates or create a new one', 'opengraphiq') . '</strong>';
		}
		wp_reset_postdata();
	}
	
	public function opengraphiq_setting_debug_cb() {
		$val = get_option( $this->option_name . '_debug_mode' );
		$valptr = '';
		if( $val == 'on'){
			$valptr = 'checked';
		}

		echo '<tr><th scope="row"><label for="debug">Debug mode</label></th>';
		echo '<td><input type="checkbox" id="' . esc_attr($this->option_name) . '_debug_mode" name="' . esc_attr($this->option_name) . '_debug_mode" ' . esc_attr($valptr) . '></td></tr>';
	}

	public function opengraphiq_setting_posttypes_template_cb() {

		$optionval = get_option( $this->option_name . '_cp_template' );

		$post_types = $this -> get_all_posttypes();

		$args = array(
			'post_type' => 'opengraphiqtemplates',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);
		
		$posts = get_posts( $args );

		$templateselect = [];
		foreach ( $posts as $post) {
			$templateselect[ $post->ID ] = $post->post_title;
		} 

		foreach ( $post_types as $post_type ){
			echo '<tr><th scope="row"><label for="' . esc_attr($this->option_name) . '_cp_template[' . esc_attr($post_type) . ']">' . esc_html($post_type) . '</label></th>';
			echo '<td>';
			echo '<select name="' . esc_attr($this->option_name) . '_cp_template[' . esc_attr($post_type) . ']" id="' . esc_attr($this->option_name) . '_cp_template[' . esc_attr($post_type) . ']" >';
			echo '<option value="-1">' . esc_html__( ' General default template ', 'opengraphiq' ) . '</option>';
			$sel = '';
			if( $optionval ){
				if( array_key_exists($post_type, $optionval) ) 
					if( $optionval[$post_type] ==  '0'){
						$sel = 'selected = "selected"';
					}
				echo '<option value="0"' . esc_attr($sel) . '>' . esc_html__( ' No images by OpenGraphiq ', 'opengraphiq' ) . '</option>';
				$sel = '';
				foreach ( $templateselect as $key => $val ){
					if( array_key_exists($post_type, $optionval) )
						if( $optionval[$post_type] ==  $key){
							$sel = 'selected = "selected"';
						}

					echo '<option value="' . esc_attr($key) . '" ' . esc_attr($sel) . '>' . esc_html($val) . '</option>';
					$sel = '';
				}
			} else {
				echo '<option value="0">' . esc_html__( ' No images by OpenGraphiq ', 'opengraphiq' ) . '</option>';
				foreach ( $templateselect as $key => $val ){
					echo '<option value="' . esc_attr($key) . '">' . esc_html($val) . '</option>';
				}
			}

			echo '</select></td></tr>';
		}
	} 

	public function opengraphiq_meta_box_template_cb($post) {

		$optionval = get_option( $this->option_name . '_cp_template' );

		$args = array(
			'post_type' => 'opengraphiqtemplates',
			'posts_per_page' => -1,
			'post_status' => 'publish'
		);

		$posts = get_posts( $args );

		$templateselect = [];
		foreach ( $posts as $posts) {
			$templateselect[ $posts->ID ] = $posts->post_title;
		}

		$post_type = $post -> post_type;
		$value = get_post_meta( $post->ID, $this->option_name . '_post_meta', true );

		echo '<select name="' . esc_attr($this->option_name) . '_post_meta" id="' . esc_attr($this->option_name) . '_post_meta" >';
		echo '<option value="-1">' . esc_html__( ' Inherit from Settings ', 'opengraphiq' ) . '</option>';
		
		$sel = ''; 
		if( $value ==  '0'){
			$sel = 'selected = "selected"';
		}
		echo '<option value="0" ' . esc_attr($sel) . '>' . esc_html__( ' No image by OpenGraphiq ', 'opengraphiq' ) . '</option>';
		$sel = '';
		foreach ( $templateselect as $key => $val ){
			if( $value ==  $key){
				$sel = 'selected = "selected"';
			}

			echo '<option value="' . esc_attr($key) . '" ' . esc_attr($sel) . '>' . esc_html($val) . '</option>';
			$sel = '';
		}
		echo '</select>';
	} 

	public function opengraphiq_init(){

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if (isset($_POST[ $this->option_name . '_default_template' ])) {

			if ( !wp_verify_nonce( $_POST['og_template_nonce'], basename(__FILE__) )) {
				return;
			}
			
			if ( $_POST[ $this->option_name . '_default_template' ] != '-1') {
				update_option( $this->option_name . '_default_template', sanitize_text_field($_POST[ $this->option_name . '_default_template' ]));
			}

			if( isset($_POST[ $this->option_name . '_debug_mode' ]) ){
				update_option( $this->option_name . '_debug_mode', sanitize_text_field($_POST[ $this->option_name . '_debug_mode' ]));
			} else {
				delete_option( $this->option_name . '_debug_mode');
			}
			

		}
		
		$nonce_value = wp_create_nonce( basename(__FILE__));

		include OPENGRAPHIQ_PATH . 'admin/partials/opengraphiq-admin-display.php' ;
	}
	
	public function opegraphiq_addbulkaction($bulk_actions){
		$bulk_actions['opengraphiq-bulk'] = esc_html__('Generate Open Graph Images', 'opengraphiq');
		return $bulk_actions;
	}

	public function opengraphiq_get_template_by_id( $post = null, $post_id = 0){

		if ($post_id != 0) {
			$meta = get_post_meta( $post_id, $this->option_name . '_post_meta', true );
			$post_type = get_post($post_id) -> post_type;
		} else {
			$meta = get_post_meta( $post -> ID, $this->option_name . '_post_meta', true );
			$post_type = $post -> post_type;
		}
		
		
		if ( $meta == '0' ){
			return '';
		}

		if ( $meta != '-1' && $meta != ''){
			$ret_val[$meta] = get_post($meta) -> post_title;
			return $ret_val;	
		}	

		$optionval = get_option( $this->option_name . '_cp_template' );
		$needsfiltering = true;
		if($optionval != '') {
			if( array_key_exists($post_type, $optionval) ) {
				if( $optionval[$post_type] !=  '-1'  && $meta == '' ){
					if( $optionval[$post_type] == '0' ){
						return '';
					}
					$ret_val[$optionval[$post_type]] = get_post($optionval[$post_type]) -> post_title;
					return $ret_val;
				} else {
					$val = get_option( $this->option_name . '_default_template' );
					if( $val  == '0' || $val == ''){
						return '';
					}
					$ret_val[$val] = get_post($val) -> post_title;
					return( $ret_val );
				}
			}
		}
		$val = get_option( $this->option_name . '_default_template' );
		$ret_val[$val] = get_post($val) -> post_title;
		return( $ret_val );
		
	}

	public function opengraphiq_bulk_init(){

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$post_ids = get_option( 'opengraphiq_bulk_post_ids' );
		$redirect_url = get_option( 'opengraphiq_bulk_redirect_url' );
		$redirect_url = add_query_arg('og-generated', count($post_ids), $redirect_url);

		$nonce = wp_create_nonce( basename(__FILE__) );

		$post_types = $this -> get_all_posttypes();

		$query = new WP_Query( array(
			'post_type' => $post_types, 
			'post__in' => $post_ids, 
			'nopaging' => 'true'
		));

		include OPENGRAPHIQ_PATH . 'admin/partials/opengraphiq-bulk.php' ;
	}

	public function opengraphiq_bulk_redirect($redirect_url, $action, $post_ids) {
		update_option( 'opengraphiq_bulk_redirect_url', $redirect_url, false );
		update_option( 'opengraphiq_bulk_post_ids', $post_ids, false );
		
		if ($action == 'opengraphiq-bulk') {
			wp_safe_redirect('edit.php?post_type=opengraphiqtemplates&page=opengraphiq_bulk');
			exit();
		}
		
		return $redirect_url;
	}

	public function opengraphiq_notices(){
		if (!empty($_REQUEST['og-generated'])) {
			$num_changed = (int) $_REQUEST['og-generated'];
			printf('<div id="message" class="updated notice is-dismissable"><p>' . esc_html__('Generated OG images for %d posts.', 'opengraphiq') . '</p></div>', $num_changed);
		}
		if (!empty($_POST[ $this->option_name . '_default_template' ])) {
			printf('<div id="message" class="updated notice is-dismissable"><p>' . esc_html__('Settings saved!', 'opengraphiq') . '</p></div>');
		}
	}

	private function isexternal($url) {
		$urlparts = parse_url(home_url());
		$domain = $urlparts['host'];
		$components = parse_url($url);    
		return !empty($components['host']) && strcasecmp($components['host'], $domain); 
	}

	public function opengraphiq_bulk_ajax(){
		
		if ( !wp_verify_nonce( $_REQUEST['og_template_nonce'], basename(__FILE__) )) {
			return;
		} 

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$postid = (int)$_REQUEST['id'];
		$currentpost = get_post($postid);

		$currenttemplate = $this -> opengraphiq_get_template_by_id($currentpost);
		if ( $currenttemplate != '' ){
			reset($currenttemplate);
			$currenttemplateid = key($currenttemplate);

			$result = get_post($currenttemplateid) -> post_content;
			$resarray = array_values(json_decode($result, true));
			foreach($resarray as &$i_value) {

				if($i_value["type"] == 'dyntext'){

					if ($i_value["dyncaption"] == 'title'){
						$i_value["caption"] = $currentpost->post_title;
					}

					if ($i_value["dyncaption"] == 'excerpt' || $i_value["dyncaption"] == 'pdescription'){

						$i_value["caption"] = $currentpost->post_excerpt;
					}

					if ($i_value["dyncaption"] == 'author'){

						$author_id = $currentpost->post_author;
						$author_name = get_author_name( intval($author_id) );
						$i_value["caption"] = $author_name;
					}

					if ($i_value["dyncaption"] == 'date'){
						
						$formatted_date = get_the_modified_date( '', $currentpost );
						$i_value["caption"] = $formatted_date;
					}

					if ($i_value["dyncaption"] == 'regularprice'){

						$price = '';

						if($currentpost->post_type == 'product'){

							$product = wc_get_product( $currentpost->ID );

							if ($product->is_type('simple')){
								
								if ( $product->is_on_sale() ) {
									$price = wc_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) );
								}
							
							} elseif ($product->is_type('variable')){

								$prices = $product->get_variation_prices( true );

								if ( !empty( $prices['price'] ) ) {
									$min_price     = current( $prices['price'] );
									$max_price     = end( $prices['price'] );
									$min_reg_price = current( $prices['regular_price'] );
									$max_reg_price = end( $prices['regular_price'] );

									if ( $product->is_on_sale() && $min_reg_price === $max_reg_price ){
										$price = wc_price( $max_reg_price );
									}
								}

							} 
						}
						
						$i_value["caption"] = $price;
					}

					if($i_value["dyncaption"] == 'displayprice'){

						$price = '';

						if($currentpost->post_type == 'product'){

							$product = wc_get_product( $currentpost->ID );

							if ($product->is_type('simple')){

								if ( '' === $product->get_price()) {
									$price = apply_filters( 'woocommerce_empty_price_html', '', $product );
								} else {
									$price = wc_price( wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
								}

							} elseif ($product->is_type('variable')){

								$prices = $product->get_variation_prices( true );

								if ( empty( $prices['price'] ) ) {
									$price = apply_filters( 'woocommerce_variable_empty_price_html', '', $product );
								} else {
									$min_price     = current( $prices['price'] );
									$max_price     = end( $prices['price'] );
									$min_reg_price = current( $prices['regular_price'] );
									$max_reg_price = end( $prices['regular_price'] );

									if ( $min_price !== $max_price ) {
										$price = wc_format_price_range( $min_price, $max_price );
									} else {
										$price = wc_price( $min_price );
									}
						
									$price = apply_filters( 'woocommerce_variable_price_html', $price . $product->get_price_suffix(), $product );
								}

							} else {
								$price = $product->get_price_html();
							}
						}
			
						$i_value["caption"] = $price;
					}
				}

				if($i_value["type"] == 'taxonomy'){
					if($i_value["dyncaption"] == 'categories'){
						$catarr = wp_get_post_categories( $currentpost->ID, array( 'fields' => 'names' ) );
						$i_value["dynarray"] = $catarr;
					}

					if($i_value["dyncaption"] == 'tags'){
						$tagarr = array();
						$posttags = get_the_tags( $currentpost->ID );
						if ($posttags) {
							foreach($posttags as $tag) {
								$tagarr[] = $tag->name; 
							}
						}
						$i_value["dynarray"] = $tagarr;
					}
					if($i_value["dyncaption"] == 'productcategories'){
						$tagarr = array();
						$terms = get_the_terms( $currentpost->ID, 'product_cat' );
						foreach ($terms as $term) {
							$tagarr[] = $term->name;
						}
						$i_value["dynarray"] = $tagarr;
					}
					if($i_value["dyncaption"] == 'producttags'){
						$tagarr = array();
						$terms = get_the_terms( $currentpost->ID, 'product_tag' );
						foreach ($terms as $term) {
							$tagarr[] = $term->name;
						}
						$i_value["dynarray"] = $tagarr;
					}
				}

				if($i_value["type"] == 'dynimage'){
					if($i_value["dynsrc"] == 'featuredimage'){
						if (has_post_thumbnail( $currentpost->ID ) )
						{
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $currentpost->ID ), 'large' )[0];
							$i_value["backgroundimage"] = $image;
						} 
					}
					if($i_value["dynsrc"] == 'authorimage'){
						$author_id = $currentpost->post_author;
						$output_img = get_avatar_url($author_id, ['size' => '400']);
						if ( $output_img != '' )
						{
							$i_value["backgroundimage"] = $output_img;	
						} 
					}
				}

				if($i_value["type"] == 'image'){
					$imagesrc = $i_value["src"];
					$i_value["src"] = $imagesrc;
				}

			}
			echo json_encode($resarray, JSON_UNESCAPED_SLASHES);
		} else {
			echo '{}';
		}
		die();
	}

	public function opengraphiq_test_ajax(){
		
		if ( !wp_verify_nonce( $_REQUEST['og_test_template_nonce'], basename(__FILE__) )) {
			return;
		} 

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$postid = (int)$_REQUEST['id'];
		$currentpost = get_post($postid);

		if( !is_object( $currentpost )) {
			echo('false');
			die();
		} else {
			if ( $currentpost->post_status != 'publish' ){
				echo('false');
				die();
			}
		}

		$currenttemplate = sanitize_text_field($_REQUEST['template_data']);

		if ( $currenttemplate != '' ){
			
			$resarray = array_values(json_decode(stripslashes($currenttemplate), true));
			
			foreach($resarray as &$i_value) {

				if($i_value["type"] == 'dyntext'){

					if ($i_value["dyncaption"] == 'title'){
						$i_value["caption"] = $currentpost->post_title;
					}

					if ($i_value["dyncaption"] == 'excerpt' || $i_value["dyncaption"] == 'pdescription'){

						$i_value["caption"] = $currentpost->post_excerpt;
					}

					if ($i_value["dyncaption"] == 'author'){

						$author_id = $currentpost->post_author;
						$author_name = get_author_name( intval($author_id) );
						$i_value["caption"] = $author_name;
					}

					if ($i_value["dyncaption"] == 'date'){
						
						$formatted_date = get_the_modified_date( '', $currentpost );
						$i_value["caption"] = $formatted_date;
					}

					if ($i_value["dyncaption"] == 'regularprice'){

						$price = '';

						if($currentpost->post_type == 'product'){

							$product = wc_get_product( $currentpost->ID );

							if ($product->is_type('simple')){
								
								if ( $product->is_on_sale() ) {
									$price = wc_price( wc_get_price_to_display( $product, array( 'price' => $product->get_regular_price() ) ) );
								}
							
							} elseif ($product->is_type('variable')){

								$prices = $product->get_variation_prices( true );

								if ( !empty( $prices['price'] ) ) {
									$min_price     = current( $prices['price'] );
									$max_price     = end( $prices['price'] );
									$min_reg_price = current( $prices['regular_price'] );
									$max_reg_price = end( $prices['regular_price'] );

									if ( $product->is_on_sale() && $min_reg_price === $max_reg_price ){
										$price = wc_price( $max_reg_price );
									}
								}

							} 
						}
						
						$i_value["caption"] = $price;
					}

					if($i_value["dyncaption"] == 'displayprice'){

						$price = '';

						if($currentpost->post_type == 'product'){

							$product = wc_get_product( $currentpost->ID );

							if ($product->is_type('simple')){

								if ( '' === $product->get_price()) {
									$price = apply_filters( 'woocommerce_empty_price_html', '', $product );
								} else {
									$price = wc_price( wc_get_price_to_display( $product ) ) . $product->get_price_suffix();
								}

							} elseif ($product->is_type('variable')){

								$prices = $product->get_variation_prices( true );

								if ( empty( $prices['price'] ) ) {
									$price = apply_filters( 'woocommerce_variable_empty_price_html', '', $product );
								} else {
									$min_price     = current( $prices['price'] );
									$max_price     = end( $prices['price'] );
									$min_reg_price = current( $prices['regular_price'] );
									$max_reg_price = end( $prices['regular_price'] );

									if ( $min_price !== $max_price ) {
										$price = wc_format_price_range( $min_price, $max_price );
									} else {
										$price = wc_price( $min_price );
									}
						
									$price = apply_filters( 'woocommerce_variable_price_html', $price . $product->get_price_suffix(), $product );
								}

							} else {
								$price = $product->get_price_html();
							}
						}
			
						$i_value["caption"] = $price;
					}
				}

				if($i_value["type"] == 'taxonomy'){
					if($i_value["dyncaption"] == 'categories'){
						$catarr = wp_get_post_categories( $currentpost->ID, array( 'fields' => 'names' ) );
						$i_value["dynarray"] = $catarr;
					}

					if($i_value["dyncaption"] == 'tags'){
						$tagarr = array();
						$posttags = get_the_tags( $currentpost->ID );
						if ($posttags) {
							foreach($posttags as $tag) {
								$tagarr[] = $tag->name; 
							}
						}
						$i_value["dynarray"] = $tagarr;
					}
					if($i_value["dyncaption"] == 'productcategories'){
						$tagarr = array();
						$terms = get_the_terms( $currentpost->ID, 'product_cat' );
						foreach ($terms as $term) {
							$tagarr[] = $term->name;
						}
						$i_value["dynarray"] = $tagarr;
					}
					if($i_value["dyncaption"] == 'producttags'){
						$tagarr = array();
						$terms = get_the_terms( $currentpost->ID, 'product_tag' );
						foreach ($terms as $term) {
							$tagarr[] = $term->name;
						}
						$i_value["dynarray"] = $tagarr;
					}
				}

				if($i_value["type"] == 'dynimage'){
					if($i_value["dynsrc"] == 'featuredimage'){
						if (has_post_thumbnail( $currentpost->ID ) )
						{
							$image = wp_get_attachment_image_src( get_post_thumbnail_id( $currentpost->ID ), 'large' )[0];
							$i_value["backgroundimage"] = $image;
						} 

					}
					if($i_value["dynsrc"] == 'authorimage'){
						$author_id = $currentpost->post_author;
						$output_img = get_avatar_url($author_id, ['size' => '400']);
						if ( $output_img != '' )
						{
							$i_value["backgroundimage"] = $output_img;	
						} 
						
					}
				}

				if($i_value["type"] == 'image'){
					$imagesrc = $i_value["src"];
					$i_value["src"] = $imagesrc;
				}

			}
			echo json_encode($resarray, JSON_UNESCAPED_SLASHES);
		} else {
			echo '{}';
		}
		die();
	}

	public function opengraphiq_bulk_ajax_create_photo(){
		
		if ( !wp_verify_nonce( $_REQUEST['og_template_nonce'], basename(__FILE__) )) {
			return;
		} 

		if ( ! current_user_can( 'edit_posts' ) ) {
			return;
		}

		$upload_dir   = wp_upload_dir();
 
		if ( ! empty( $upload_dir['basedir'] ) ) {
			$dirname = $upload_dir['basedir'].'/opengraphiq';
			if ( ! file_exists( $dirname ) ) {
				wp_mkdir_p( $dirname );
			}
		}

		$postid = (int)$_REQUEST['id'];

		$img = $_POST['photodata'];
		$img = str_replace('data:image/jpeg;base64,', '', $img);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);

		//saving
		$fileName = $dirname . '/' . $postid . '.jpg';
		file_put_contents($fileName, $fileData);

		$old = get_post_meta( $postid, 'opengraphiqtemplates_image', true );
		$new = $this->fn_get_upload_dir_var( 'baseurl', '/opengraphiq/' ) . $postid . '.jpg';

		$currenttemplateid = 0;
		$currenttemplate = $this -> opengraphiq_get_template_by_id(null, $postid);
		if ( $currenttemplate != '' ){
			reset($currenttemplate);
			$currenttemplateid = key($currenttemplate);
		}

		update_post_meta( $postid, 'opengraphiqtemplates_time', date("Y-m-d H:i:s"));
		update_post_meta( $postid, 'opengraphiqtemplates_template', $currenttemplateid);


		if ( $new && $new !== $old ) {
			update_post_meta( $postid, 'opengraphiqtemplates_image', $new );
		} elseif ( '' === $new && $old ) {
			delete_post_meta( $postid, 'opengraphiqtemplates_image' );
			delete_post_meta( $postid, 'opengraphiqtemplates_time' );
			delete_post_meta( $postid, 'opengraphiqtemplates_template' );
		}

		echo( '{"ogurl":"' . $new .'?rnd=' . rand(1,100000) . '"}' );
		die();
	}
}
