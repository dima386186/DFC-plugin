<?php

require DCF_PATH_SERVICES . "mvc/core/Controller.php";
require DCF_PATH_SERVICES . "mvc/models/SimpleModel.php";
require DCF_PATH_SERVICES . 'Upload.php';

class Simple extends Controller {
	public function __construct() {
		parent::__construct();
		$this->model = new SimpleModel;
	}

	public function getShortCodeDCF() {
		$data['model'] = $this->model->get_data_for_short_code();
		$this->view->public_generate('DCF.php', 'without-wrap.php', $data);
	}

	public function mainHandler() {
		if($_POST){

			$postData = $this->DCF_POST_Validator($_POST);

			if($postData[0] === false) {
				wp_send_json_error(['response'=>$postData[1]]);
				return;
			}

			$fileData = [];
			if($_FILES){
				$fileData = $this->DCF_FILE_Work($_FILES);

				if($fileData[0] === false) {
					wp_send_json_error(['response'=>$fileData[1]]);
					return;
				}
			}

			$data = array_merge($postData, $fileData);

			$insert = $this->model->insert_DCF_data($data);

			if($insert === false) { wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']); return; }
			else {
				$this->Send_Email();
			}
		}
	}

	public function DCF_POST_Validator($data) {

		$normalData = [];
		foreach ($data as $key => $value) {
			$arr = explode(',', $value);

			if($arr[1]){

				$arr[0] = trim(htmlspecialchars($arr[0]));

				$require = $this->model->check_required($key);

				if($require && !$arr[0]){
					$error = 'Заполните все необходимые поля';
					return [ false, $error ];
				}

				if($arr[1] == 'email' && $arr[0]){
					if( !filter_var( $arr[0], FILTER_VALIDATE_EMAIL ) ) {
						$error = 'Введите корректный email';
						return [ false, $error ];
					}
				}

				if($arr[1] == 'number' && $arr[0]){
					if( !filter_var( $arr[0], FILTER_VALIDATE_INT ) ) {
						$error = 'В числовое поле вводятся только числа';
						return [ false, $error ];
					}
				}

				if($arr[1] == 'checkbox') {
					$arr[0] = $arr[0]=='on' ? 1 : 0;
				}

				$normalData[] = [
					'name' => $key,
					'value' => $arr[0]
				];
			}
		}

		return $normalData;
	}

	public function DCF_FILE_Work($data) {
		$settings = [
			'dir' => DCF_PATH_IMAGES,
			'size' => 8, // Mb
			'types' => ['jpg','jpeg','png','gif'],
			'width' => 320,
			'height' => 240
		];

		$fileUploadService = new Upload;
		$check = $fileUploadService->work($data, $settings);

		return $check;
	}

	public function Send_Email() {
		$mailData = $this->model->get_main_settings();
		$headers = [
			"From: {$mailData->set_from}",
			'content-type: text/plain'
		];
		$mail = wp_mail( $mailData->set_where, $mailData->subject, $mailData->message, $headers );

		if($mail===false) { wp_send_json_error(['response'=>'Письмо не отправлено']); return false; }

		wp_send_json_success();
	}
}