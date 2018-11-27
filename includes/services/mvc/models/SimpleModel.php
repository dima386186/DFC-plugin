<?php

require DCF_PATH_SERVICES . "mvc/core/Model.php";

class SimpleModel extends Model{

	public function check_required($name) {
		$table = $this->prefix . "fields";

		$sqlStr = "SELECT required FROM `$table` WHERE name = %s";
		$sql = $this->wpdb->prepare($sqlStr, [ $name ]);
		$data = $this->wpdb->get_var( $sql );

		return $data;
	}

	public function insert_DCF_data($data) {
		$table = $this->prefix . "fields_data";

		$arr = [];
		for($i=0; $i<count($data); $i++) {
			$arr[] = [
				$data[$i]['name'] => $data[$i]['value']
			];
		}

		$insertDB = call_user_func_array('array_merge', $arr);

		$bool = $this->wpdb->insert( $table, $insertDB );

		return $bool;
	}
}