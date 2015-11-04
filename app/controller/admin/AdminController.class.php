<?php
class AdminController extends AdminPlatformController {
	public function loginAction() {
		if ($this -> isLogin()) {
			$this -> redirect('index.php?act=index');
		} else {
			$this->view->display('login.tpl');
		}
	}

	public function signinAction() {
		$model_admin = new AdminModel;
		$captcha = $_POST['captcha'];
		if (strtolower($captcha) === strtolower($_SESSION['captcha'])) {
			if ($admin_info=$model_admin -> checkAccunt($_POST['username'], $_POST['password'])) {

				$_SESSION['uname'] = $_POST['username'];
				$_SESSION['is_login'] = 'yes';
				if(isset($_POST['remember'])&&$_POST['remember']=='1')
				{
					setcookie('admin_id',$admin_info['admin_id'],PHP_INT_MAX);
					setcookie('admin_pass',md5('qin'.$admin_info['admin_pass']),PHP_INT_MAX);
				}
				$this -> redirect('index.php?act=index');
				
			} else {
				$this -> redirect('pre', '用户名或密码错误', 3);
			}
		}
		else
		{
			$this -> redirect('pre', '验证码错误，请认真填写', 10);
		}

	}

	public function captchaAction() {
		$captch = new CaptchaTool;
		$captch -> generate();
	}

	public function logoutAction() {
		unset($_SESSION['uname']);
		$this -> redirect('index.php?act=login');
	}

	public function indexAction() {
		
		//var_dump($this->view);
		$this->view->display('index.tpl');

	}
	
	public function dragAction()
	{
		$this->view->display('drag.tpl');
	}

	public function topAction() {
		$this->view->display('top.tpl');
	}

	public function menuAction() {
		$this->view->display('menu.tpl');
	}

	public function mainAction() {
		$this->view->display('main.tpl');
	}

	public function testAction() {
		require ROOT_PATH . DS . 'test.php';
	}

}
