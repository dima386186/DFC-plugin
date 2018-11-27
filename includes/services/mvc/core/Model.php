<?php

class Model {
	public $wpdb;
	public $prefix;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = $this->wpdb->prefix . UNIQUE_DB_PREFIX;
	}

	public function get_main_settings() {
		$table = $this->prefix . "main_settings";
		$data = $this->wpdb->get_results( "SELECT * FROM `$table`" );

		return $data[0];
	}

	public function get_data_for_short_code() {
		$table = $this->prefix . "fields";
		$data = $this->wpdb->get_results( "SELECT * FROM `$table` ORDER BY place" );

		return $data;
	}
}