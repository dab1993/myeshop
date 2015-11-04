<?php
class Controller {
	protected $view;
	public function __construct()
	{
		$this->view =new View;
		$this->view->tpl_dir=CURRENT_VIEW_PATH.CONTROLLER.DS;
		$this->view->tplc_dir=CURRENT_VIEW_PATH.CONTROLLER.DS;
		$this->view->left_de='<{';
		$this->view->right_de='{>';
	}
	/**
	 * 跳转到新页面
	 * 
	 * @param $url string 要跳转的页面  如果是'pre' 则跳转到上一个链接
	 * @param $message string 提示信息
	 * @param $times int 跳转间隔时间
	 */
	protected function redirect($url,$message = '', $times = 3) {
		$url=$url=='pre'?'javascript:history.go(-1)':$url;
		if ($message === '') {
			header("Location:$url");
		} else {
			if (file_exists(TEMPLATE_VIEW_PATH . "jump.html")) {
				require TEMPLATE_VIEW_PATH  . 'jump.html';
			} else {
				echo <<<HTML
<html>
<head>
	<title>提示信息</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf8">
	<meta http-equiv="Refresh" content="$times,$url">
</head>
<body>
$message
</body>
</html>
HTML;
			}
		}
		die ;
	}

}
