<?php

require DCF_PATH_SERVICES . "mvc/core/Model.php";

class AdminModel extends Model {

	public function get_all_contacts($paginate, $sort, $order) {
		$table = $this->prefix . "fields_data";
		$data = $this->wpdb->get_results( "SELECT * FROM `$table` ORDER BY $sort $order $paginate" );

		return $data;
	}

	public function get_all_contacts_count() {
		$table = $this->prefix . "fields_data";
		$data = $this->wpdb->get_var( "SELECT COUNT(*) as total FROM  `$table`" );

		return $data;
	}

	public function plugin_main_settings($data) {
		$table = $this->prefix . "main_settings";

		$sqlStr = "UPDATE `$table` SET `".$data['field']."` = '%s' WHERE id = %d";
		$sql = $this->wpdb->prepare($sqlStr, [ $data['value'], $data['id'] ]);

		$this->wpdb->query($sql);
	}

	public function check_name_unique($name) {
		$table = $this->prefix . "fields";

		$sqlStr = "SELECT id FROM `$table` WHERE name = %s";
		$sql = $this->wpdb->prepare($sqlStr, [ $name ]);

		$data = $this->wpdb->get_var($sql);

		print $data;

		return $data;
	}

	public function add_field($data) {
		$table_fields = $this->prefix . "fields";
		$this->wpdb->insert( $table_fields, $data );

		$lastInsertId = $this->wpdb->insert_id;

		if($lastInsertId){
			$table_fields_data = $this->prefix . "fields_data";
			$type = $data['type'];

			if($type == 'textarea') $typeForTable = "TEXT";
			elseif($type == 'checkbox') $typeForTable = "BOOL";
			else $typeForTable = "VARCHAR (255)";

			// $data['name'] = bla_bla after js handler (with _ !!!) - so sql injection not impossible
			$this->wpdb->query("ALTER TABLE $table_fields_data ADD ".$data['name']." $typeForTable");
		}

		return $lastInsertId;
	}

	public function edit_field($data) {
		$table_fields = $this->prefix . "fields";

		$update = $this->wpdb->update($table_fields, $data, ['ID' => $data['id']]);
		return $update;
	}

	public function remove_field($data) {
		$table_fields = $this->prefix . "fields";
		$delete = $this->wpdb->delete( $table_fields, [ 'id'=>$data['id'] ], '%d' );

		if($delete) {
			$table_fields_data = $this->prefix . "fields_data";
			$this->wpdb->query("ALTER TABLE $table_fields_data DROP ".$data['name']);
		}

		return $delete;
	}

	public function get_field_by_id($data) {
		$table = $this->prefix . "fields";
		$sqlStr = "SELECT * FROM `$table` WHERE id = %d";
		$sql = $this->wpdb->prepare($sqlStr, [ $data['id'] ]);

		$data = $this->wpdb->get_results($sql);
		return $data[0];
	}

	public function field_up($data) {
		$table = $this->prefix . "fields";

		$update[0] = $this->wpdb->update($table, ['place'=>$data['upPlace']], ['ID' => $data['id']]);
		$update[1] = $this->wpdb->update($table, ['place'=>$data['place']], ['ID' => $data['upId']]);

		return $update[0]!==false && $update[1]!==false;
	}

	public function field_down($data) {
		$table = $this->prefix . "fields";

		$update[0] = $this->wpdb->update($table, ['place'=>$data['downPlace']], ['ID' => $data['id']]);
		$update[1] = $this->wpdb->update($table, ['place'=>$data['place']], ['ID' => $data['downId']]);

		return $update[0]!==false && $update[1]!==false;
	}
}