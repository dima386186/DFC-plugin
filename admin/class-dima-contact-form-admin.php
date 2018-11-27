<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Dima_Contact_Form_Admin {

	/**
	 * The ID of this plugin.
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

    public function build_menu() {
        add_menu_page(
            'Main settings',
            'dima-contact-form',
            'administrator',
            'dima-contact-form slug',
            [ $this, 'mainSettings' ],
            'dashicons-info'
        );

        add_submenu_page(
            'dima-contact-form slug',
            'Get all contacts',
            'Get all contacts',
            'administrator',
            'get-all-contacts slug',
            [ $this, 'getAllContacts' ]
        );

        add_submenu_page(
            'dima-contact-form slug',
            'Fields settings',
            'Fields settings',
            'administrator',
            'fields-settings slug',
            [ $this, 'DCF_Settings' ]
        );
    }

    public function mainSettings() {
		$this->getAdminController()->getMainSettings();
    }

    public function getAllContacts() {
	    $this->getAdminController()->getAllContacts();
    }

    public function DCF_Settings() {
	    $this->getAdminController()->DCFSettings();
    }



	/**
	 * Register the stylesheets for the admin area.
	 */
	public function enqueue_styles() {

        wp_enqueue_style( 'bootstrap', 'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css', [], $this->version, 'all' );

		wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', [ 'bootstrap' ], $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/dima-contact-form-admin.css', [ 'bootstrap', 'font-awesome' ], $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area and use of other functions with different js.
	 */
	public function enqueue_scripts() {

        wp_enqueue_script( 'bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js', [ 'jquery', 'notify' ], $this->version, false );

        wp_enqueue_script( 'main-settings', plugin_dir_url( __FILE__ ) . 'js/main-settings.js', [ 'jquery', 'notify', 'bootstrap', 'plugin-global' ], $this->version, false );

		wp_enqueue_script( 'field-settings', plugin_dir_url( __FILE__ ) . 'js/field-settings.js', [ 'jquery', 'plugin-global' ], $this->version, false );

		wp_localize_script('main-settings', 'DFCAjax', [ 'security'=>wp_create_nonce(AJAX_SECURITY) ]);

		wp_localize_script('field-settings', 'DFCAjax', [ 'security'=>wp_create_nonce(AJAX_SECURITY) ]);

	}

	public function getAdminController() {
		require DCF_PATH_CONTROLLERS . 'Admin.php';
		$admin = new Admin;
		return $admin;
	}
}
