<?php
class View extends Smarty {
	public function __construct() {
		parent::__construct();
		$this -> template_dir = CURRENT_VIEW_PATH . CONTROLLER . DS;
		$this -> compile_dir = COMPILE_PATH;
	}

}
