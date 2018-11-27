<?php

/**
 * Fired during plugin activation.
 */
class Dima_Contact_Form_Activator {

	public static function activate() {
        require_once 'services/MainDatabaseService.php';
        MainDatabaseService::plugin_activate();
	}

}
