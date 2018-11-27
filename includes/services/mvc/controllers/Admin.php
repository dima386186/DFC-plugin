<?php

require DCF_PATH_SERVICES . "mvc/core/Controller.php";
require DCF_PATH_SERVICES . "mvc/models/AdminModel.php";

class Admin extends Controller {
	public function __construct() {
		parent::__construct();
		$this->model = new AdminModel;
	}

	public function getMainSettings() {
		$data['model'] = $this->model->get_main_settings();

		$data['title'] = 'Главные настройки';
		$this->view->admin_generate('main-settings.php', 'simple-template.php', $data);
	}

	public function getAllContacts() {

		require DCF_PATH_SERVICES . 'Paginator.php';

		$countContacts = $this->model->get_all_contacts_count();
		$contacts = new Paginator(10,'p');
		$contacts->set_total( $countContacts );
		$paginate = $contacts->get_limit();
		$data['records'] = $this->model->get_all_contacts($paginate, 'time', 'DESC');
		$data['page_links'] = $contacts->page_links('?page=get-all-contacts+slug&');

		$data['title'] = 'Вывод всех контактов';
		$this->view->admin_generate('get-all-contacts.php', 'simple-template.php', $data);
	}

	public function pluginMainSettings($data) {
		if( $data['field'] == 'set_where' || $data['field'] == 'set_from' ) {
			if(!filter_var($data['value'], FILTER_VALIDATE_EMAIL)) {
				wp_send_json_error(['response'=>'Введите корректный email']);
				return;
			}
		} else {
			if( mb_strlen(trim($data['value'])) < 2 ) {
				wp_send_json_error(['response'=>'В этом поле должно быть больше 1 символа']);
				return;
			}
		}

		$this->model->plugin_main_settings($data);

		wp_send_json_success();
	}

	public function DCFSettings() {
		$data['title'] = 'Настройки полей формы';
		$data['model'] = $this->model->get_data_for_short_code();
		$this->view->admin_generate('fields-settings.php', 'simple-template.php', $data);
	}

	public function addField() {
		if($_POST){
			$dataAfterValidator = $this->fieldValidator('create', $_POST);
			if($dataAfterValidator === false) { return; }

			$insertId = $this->model->add_field($dataAfterValidator);
			if($insertId === false) {
				wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']);
				return;
			}

			$data = (object) $dataAfterValidator;
			$data->id = $insertId;

			$this->view->admin_generate('field-block.php', 'without-wrap.php', $data);
		}
	}

	public function editField() {
		if($_POST){
			$dataAfterValidator = $this->fieldValidator('edit', $_POST);
			if($dataAfterValidator === false) { return; }

			$update = $this->model->edit_field($dataAfterValidator);
			if($update === false) {
				wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']);
				return;
			}

			$data = (object) $dataAfterValidator;

			$this->view->admin_generate('field-block.php', 'without-wrap.php', $data);
		}

	}

	public function removeField() {
		if($_POST){
			if(empty($_POST['id']) || empty($_POST['name'])){
				wp_send_json_error(['response'=>'Удаление не возможно']);
				return;
			}

			$delete = $this->model->remove_field($_POST);

			if($delete === 0) { wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']); return; }

			wp_send_json_success();
		}
	}

	public function fieldUp() {
		if($_POST){
			if(empty($_POST['id']) || empty($_POST['upId'])) { wp_send_json_error(['response'=>'Ошибка в запросе']); return; }

			$update = $this->model->field_up($_POST);
			if($update===false) { wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']); return; }
			wp_send_json_success();
		}
	}

	public function fieldDown() {
		if($_POST){
			if(empty($_POST['id']) || empty($_POST['downId'])) { wp_send_json_error(['response'=>'Ошибка в запросе']); return; }

			$update = $this->model->field_down($_POST);
			if($update===false) { wp_send_json_error(['response'=>'Ошибка БД; Данные не зафиксированы в БД']); return; }
			wp_send_json_success();
		}
	}

	public function getFieldById() {
		if($_GET){
			if(empty($_GET['id'])) { wp_send_json_error(['response'=>'Запрос не возможен']); return; }
			$data = $this->model->get_field_by_id($_GET);
			$this->view->admin_generate('update-field.php', 'without-wrap.php', $data);
		}
	}

	public function fieldValidator($action, $data) {
		if($action == 'create'){
			if(empty($data['name'])) { wp_send_json_error(['response'=>'Имя - обязательное поле']); return false; }
			$checkName = $this->model->check_name_unique($data['name']);
			if($checkName) {
				wp_send_json_error(['response'=>'Имя поля должно быть уникальным']);
				return false;
			}
		}

		if($action == 'edit'){
			if(empty($data['id'])) { wp_send_json_error(['response'=>'Не корректный запрос']); return false; }
		}

		if( preg_match('/(,|\.).*/', $data['wrap_classes']) ) {
			wp_send_json_error(['response'=>'В названиях классов для wrapper не должно быть запятых и точек']);
			return false;
		}

		array_splice($data, -4); // delete action and other not need data

		foreach ($data as $key => $value) {
			$data[$key] = trim(htmlspecialchars($data[$key]));
		}

		$data['required'] = $data['required']=='on' ? 1 : 0;
		$data['label'] = $data['label']=='on' ? 1 : 0;

		return $data;
	}
}