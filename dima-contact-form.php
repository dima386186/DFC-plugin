<?php

/**
 * @wordpress-plugin
 * Plugin Name:       Contact Form From Dima386186
 * Description:       Test Contact Form
 * Version:           1.0.0
 * Author:            dima386186
 * Text Domain:       dima-contact-form
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'DIMA_CONTACT_FORM_VERSION', '1.0.0' );

define( 'UNIQUE_DB_PREFIX', 'dcf386186_' );

define( 'GLOBAL_SCRIPTS', plugin_dir_url( __FILE__ ) . 'common/' );
define( 'AJAX_SECURITY', 'dima386186' );

define( 'DCF_PATH_IMAGES',  plugin_dir_path( __FILE__ ) . 'public/images/');
define( 'DCF_PATH_ADMIN_VIEWS',  plugin_dir_path( __FILE__ ) . 'admin/partials/');
define( 'DCF_PATH_PUBLIC_VIEWS',  plugin_dir_path( __FILE__ ) . 'public/partials/');
define( 'DCF_PATH_SERVICES',  plugin_dir_path( __FILE__ ) . 'includes/services/' );
define( 'DCF_PATH_CONTROLLERS', plugin_dir_path( __FILE__ ) . 'includes/services/mvc/controllers/' );

/**
 * The code that runs during plugin activation.
 */
function activate_dima_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dima-contact-form-activator.php';
	Dima_Contact_Form_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_dima_contact_form() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-dima-contact-form-deactivator.php';
	Dima_Contact_Form_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_dima_contact_form' );
register_deactivation_hook( __FILE__, 'deactivate_dima_contact_form' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-dima-contact-form.php';

/**
 * Begins execution of the plugin.
 */
function run_plugin_name() {

	$plugin = new Dima_Contact_Form();
	$plugin->run();

}
run_plugin_name();
