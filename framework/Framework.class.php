<?php
class Framework {

	private static $platform;
	private static $controller;
	/**
	 * 初始化请求参数
	 */
	private static function initRequest() {
		/**
		 * 当前平台
		 */
		define('PLATEFORM', strtolower(self::getNullRequrest('p', self::$platform)));
		/**
		 * 控制器
		 */
		define('CONTROLLER', ucfirst(self::getNullRequrest('c', self::$controller)));
		/**
		 * 动作
		 */
		define('ACTION', strtolower(self::getNullRequrest('act', 'index')));
	}

	/**
	 * 初始化路径常量
	 */
	private static function initPath() {

		/**
		 * 目录分隔符
		 */
		define('DS', DIRECTORY_SEPARATOR);
		/**
		 * 根路径
		 */
		define('ROOT_PATH', dirname(dirname(__FILE__)) . DS);
		/**
		 * 控制器路径
		 */
		define('APP_PATH', ROOT_PATH . 'app' . DS);
		/**
		 * 框架路径
		 */
		define('FRAME_PATH', ROOT_PATH . 'framework' . DS);
		/**
		 * 上传文件根目录
		 */
		 define('UPLOAD_PATH',ROOT_PATH.'upload'.DS);
		/**
		 * 控制器路径
		 */
		define('CONTROLLER_PATH', APP_PATH . 'controller' . DS);
		/**
		 * 视图路径
		 */
		define('VIEW_PATH', APP_PATH . 'view' . DS);
		/**
		 * 模型路径
		 */
		define('MODEL_PATH', APP_PATH . 'model' . DS);
		/**
		 * 当前平台
		 */
		define('CURRENT_CONTROLLER_PATH', CONTROLLER_PATH . PLATEFORM . DS);
		/**
		 * 当前视图
		 */
		define('CURRENT_VIEW_PATH', VIEW_PATH . PLATEFORM . DS);
		/**
		 * 公共模板路径
		 */
		define('TEMPLATE_VIEW_PATH',VIEW_PATH.'template'.DS);
		/**
		 * 工具类路径
		 */
		define('TOOL_PATH', FRAME_PATH . 'tool' . DS);
		/**
		 * 样式文件路径
		 */
		define('PUBLIC_PATH', '');
		/**
		 * 配置文件路径
		 */
		define('CONFIG_PATH', ROOT_PATH . 'config' . DS);
		/**
		 * 配置商品默认上传路径
		 */
		define('GOODS_IMAGE_PATH', UPLOAD_PATH.'goods'.DS);
	}

	/**
	 * 请求分发
	 */
	private static function dispatch() {

		$controller_name = CONTROLLER . 'Controller';
		$action_name = ACTION . 'Action';

		$controller = new $controller_name;
		$controller -> $action_name();
	}

	/**
	 * 给get来的空参数赋值
	 * @param $key string 要检测的值
	 * @param $value string 如果为空则返回
	 * @param $method string 传递方法 默认值为空
	 *
	 * 最终结果
	 */
	public static function getNullRequrest($key, $value, $method = 'get') {
		return $method === 'post' ? (isset($_POST[$key]) ? $_POST[$key] : $value) : (isset($_GET[$key]) ? $_GET[$key] : $value);

	}

	/**
	 * 如果是空值则替换
	 */
	public static function getNullReplace($value, $re_value) {
		return isset($value) ? $value : $re_value;
	}

	/**
	 * 执行程序入口
	 *
	 * @param $page array 默认设置，里面传入两个选项 一个 $platform，一个controller，用来控制平台和控制器，默认front
	 */
	public static function run($page = array()) {

		self::$platform = self::getNullReplace($page['platform'], 'front');
		self::$controller = self::getNullReplace($page['controller'], 'front');
		/**
		 * 注册自动加载方法
		 */
		spl_autoload_register(array('Framework', 'autoLoad'));		
		
		self::loadSafeSql();

		self::initRequest();

		self::initPath();

		self::loadConfig();
		
		self::initTimezone();
		
		self::initErrorHandler();

		self::dispatch();
		
	}
	/**
	 * 设置默认时区
	 */
	private static function initTimezone()
	{
		date_default_timezone_set($GLOBALS['config']['app']['timezone']);
//		ini_set('timezone',$GLOBALS['config']['app']['timezone']);
	}
	/**
	 * 自动加载方法
	 */
	public static function autoLoad($class_name) {

		$class = '.class.php';
		$frames = array('Model', 'Controller','View');
		if (in_array($class_name, $frames)) {
			require FRAME_PATH . $class_name . $class;
		} elseif (substr($class_name, -10) === 'Controller') {
			require CURRENT_CONTROLLER_PATH . $class_name . $class;
		} elseif (substr($class_name, -5) === 'Model') {
			require MODEL_PATH . $class_name . $class;
		} else if (substr($class_name, -4) === 'Tool') {
			require TOOL_PATH . $class_name . $class;
		}
	}

	/**
	 * 加载配置文件
	 */
	private static function loadConfig() {
		$GLOBALS['config'] =
		require CONFIG_PATH . 'config.php';
	}
	/**
	 * 防止sql注入
	 */
	private static function addslashes_func(& $str)
	{
		$str=addslashes($str);
	}
	private static function loadSafeSql()
	{
		array_walk_recursive($_POST,'self::addslashes_func');
		array_walk_recursive($_GET,'self::addslashes_func');
	}
	/**
	 * 根据运行状态处理错误提示
	 */
	private static function initErrorHandler()
	{
		if($GLOBALS['config']['app']['run_mode']=='dev')
		{
			ini_set('error_reporting',E_ALL|E_STRICT);
			ini_set('display_errors',1);
			
			ini_set('log_errors',0);
		}
		else if($GLOBALS['config']['app']['run_mode']=='pro')
		{
			ini_set('error_reporting',E_ALL & ~E_NOTICE);
			ini_set('display_errors',0);			
			ini_set('log_errors',1);
			
			$dir_name=APP_PATH.'log'.DS.date('Ymd').DS;
			if(!file_exists($dir_name))
			{
				mkdir($dir_name,true);
			}
			$error_log=$dir_name.'error_'.date('H').'.log';
			//var_dump($error_log);
			ini_set('error_log',$error_log);
		}
	}
}
