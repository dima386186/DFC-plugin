<?php

/**
 * The core plugin class.
 */
class Dima_Contact_Form {

	/**
	 * Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		if ( defined( 'DIMA_CONTACT_FORM_VERSION' ) ) {
			$this->version = DIMA_CONTACT_FORM_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'dima-contact-form';

		$this->load_dependencies();
		$this->set_locale();
		$this->global_js_scripts();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->common_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dima-contact-form-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-dima-contact-form-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-dima-contact-form-admin.php';


		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-dima-contact-form-public.php';

		$this->loader = new Dima_Contact_Form_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 */
	private function set_locale() {

		$plugin_i18n = new Dima_Contact_Form_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 */
	private function define_admin_hooks() {

		$admin_component = new Dima_Contact_Form_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $admin_component, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin_component, 'enqueue_scripts' );

        $this->loader->add_action( 'admin_menu', $admin_component, 'build_menu' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 */
	private function define_public_hooks() {

		$public_component = new Dima_Contact_Form_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_component, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $public_component, 'enqueue_scripts' );

		$this->loader->add_short_code( 'DCF', $public_component, 'get_short_code_DCF');
	}

	/**
	 * Register common hooks.
	 */
	private function common_hooks() {
		if( wp_doing_ajax() ){
			add_action( 'wp_ajax_route', [$this, 'mvc_form_handler'] );
			add_action( 'wp_ajax_nopriv_route', [$this, 'mvc_form_handler'] );
		}
	}

	/**
	 * Handler form in mvc pattern.
	 */
	public function mvc_form_handler() {
		if( check_ajax_referer(AJAX_SECURITY, 'security') ) {

			$controllerName = $_REQUEST['controller'];
			require DCF_PATH_CONTROLLERS . "{$controllerName}.php";

			$methodName = $_REQUEST['method'];

			$controller = new $controllerName;
			$controller->$methodName($_REQUEST['main_data']);

			wp_die();
		}
	}

	private function global_js_scripts() {
		wp_enqueue_script( 'notify', 'https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js', [ 'jquery' ], $this->version, false );

		wp_enqueue_script( 'plugin-global', GLOBAL_SCRIPTS . 'global-variables-and-functions.js', [ 'jquery', 'notify' ], $this->version, false );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

