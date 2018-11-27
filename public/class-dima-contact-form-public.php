<?php

/**
 * The public-facing functionality of the plugin.
 */
class Dima_Contact_Form_Public {

	/**
	 * The ID of this plugin
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site and use of other functions with different js..
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dima-contact-form-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( 'DCF-handler', plugin_dir_url( __FILE__ ) . 'js/DCF-handler.js', [ 'jquery', 'notify', 'plugin-global' ], $this->version, false );

		wp_localize_script('DCF-handler', 'DFCAjax', [ 'security'=>wp_create_nonce(AJAX_SECURITY), 'ajax_url' => admin_url( 'admin-ajax.php' ) ]);
	}

	public function get_short_code_DCF() {
		require DCF_PATH_CONTROLLERS . 'Simple.php';
		$handler = new Simple;
		return $handler->getShortCodeDCF();
	}
}
