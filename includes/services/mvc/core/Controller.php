<?php
require 'View.php';

class Controller {
	protected $model;
	protected $view;

	public function __construct() {
		$this->view = new View;
	}
}