<?php
class AdminPlatformController extends Controller {
	protected function isLogin()
	{
		if (isset($_SESSION['uname']) and $_SESSION['is_login'] == 'yes') {
				return true;
			} 
	}
	protected function checkLogin() {
		$allow_action=array('login','signin','captcha','test');
		if (CONTROLLER == 'Admin' && (in_array(ACTION,$allow_action))) {
				
		} else {
			if (isset($_SESSION['uname']) and $_SESSION['is_login'] == 'yes') {
				return true;
			} else {
				$admin_model=new AdminModel;
				if($admin_info=$admin_model->checkCookie())
				{
					$_SESSION['uname']=$admin_info['admin_name'];
					$_SESSION['is_login']='yes';
				}
				else{
					$this -> redirect('index.php?act=login');
				}
			}
		}
	}

	public function __construct() {
		parent::__construct();
		$this -> initSession();
		
		$this -> checkLogin();
	}
	
	protected function initSession()
	{
		new SessionDBTool;
	}

}
