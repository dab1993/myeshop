<?php
	class FrontController extends Controller
	{
	 	public function indexAction()
		{
			//require CURRENT_VIEW_PATH.DS.'index.htm';
			$this->view->display('index.html');
		}
	}
