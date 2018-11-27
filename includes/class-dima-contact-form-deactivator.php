<?php

/**
 * Fired during plugin deactivation.
 */
class Dima_Contact_Form_Deactivator {

	public static function deactivate() {
        require_once 'services/MainDatabaseService.php';
        MainDatabaseService::plugin_deactivate();
	}

}
