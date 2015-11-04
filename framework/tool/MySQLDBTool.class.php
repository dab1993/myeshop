<?php

/**
 * mysql帮助类
 */
final class MySQLDBTool {
	//对象的初始化属性
	private $host;
	private $port;
	private $database;
	private $user;
	private $password;
	private $charset;

	private static $instance;

	//运行时生成的属性
	private $link;

	/**
	 * 构造方法
	 * @access private
	 *
	 * @param $params array 数据库的设置项
	 */
	private function __construct($params = array()) {
		$this -> host = isset($params['host']) ? $params['host'] : 'localhost';
		$this -> port = isset($params['port']) ? $params['port'] : '3306';
		$this -> user = isset($params['user']) ? $params['user'] : 'root';
		$this -> charset = isset($params['charset']) ? $params['charset'] : 'utf8';
		$this -> password = isset($params['password']) ? $params['password'] : '';
		$this -> database = isset($params['database']) ? $params['database'] : '';
		$this -> initMySQL();
	}

	/**
	 * 单例模式的公共接口
	 * @access public
	 *
	 * @param $params 配置参数
	 */
	public static function getInstance($params) {
		if (!(self::$instance instanceof self)) {
			//实例化时，需要将参数传递到构造方法内
			self::$instance = new self($params);
		}
		return self::$instance;
	}

	/**
	 * 负责初始化对象
	 * @access private
	 */
	private function initMySQL() {
		if ($result = $this -> connect()) {
			$this -> setCharset();

			$this -> selectDB();
		} else {

			die('数据库连接异常');
		}
	}

	/**
	 * 连接数据库
	 * @access private
	 */
	private function connect() {
		return $this -> link = mysql_connect("$this->host:$this->port", $this -> user, $this -> password);
	}

	/**
	 * 设置连接编码
	 * @access private
	 */
	private function setCharset() {
		return $this -> query("set names $this->charset");
	}

	/**
	 * 设置默认数据库
	 * @access private
	 */
	private function selectDB() {
		return $this -> database !== '' ? $this -> query("use $this->database") : false;
	}

	/**
	 * 克隆方法
	 * @access private
	 *
	 */
	private function __clone() {
	}

	/**
	 * 执行sql语句
	 * @access public
	 * @return 返回执行结果
	 */
	private function query($sql) {
		if (!$result = mysql_query($sql, $this -> link)) {
			$error_no = mysql_errno();
			$error = mysql_error();
			$this -> error_info = "{$error_no}:{$error},Error command:'{$sql}'";
			return false;
		} else {
			return $result;
		}
	}

	/**
	 * 返回受影响的行数
	 *
	 */
	public function exec($sql) {
		$this -> query($sql);
		return mysql_affected_rows($this -> link);
	}

	/**
	 * 返回最后的自增值
	 */
	public function last_insert_id($sql) {
		if ($this -> query($sql) !== false) {
			return mysql_insert_id($this -> link);
		} else {

			return false;
		}
	}

	/**
	 * 取得所有返回结果
	 * @access public
	 * @param $sql 要执行的sql语句
	 */
	public function fetchAll($sql) {
		if ($result = $this -> query($sql)) {
			while ($row = mysql_fetch_array($result)) {
				$rows[] = $row;
			}
			if (isset($row)) {
				array_walk_recursive($rows, array($this, 'test'));
			}
			mysql_free_result($result);
			return isset($rows) ? $rows : array();
		}
	}

	/**
	 * 还原转义过后的字符串
	 */
	private function test(&$str) {
//		$str = stripslashes($str);
	}

	/**
	 * 返回第一行的数据
	 * @access public
	 * @param $sql 要执行的sql
	 * @return 执行的结果
	 */
	public function fetchRow($sql) {
		if ($result = $this -> query($sql)) {
			if ($result) {
				$row = mysql_fetch_array($result);
				@array_walk_recursive($rows, array($this, 'test'));
			}
			mysql_free_result($result);

			return isset($row) ? $row : false;
		}
	}

	/**
	 * 返回第一行第一列的值
	 * @access public
	 * @param $sql 要执行的sql
	 */
	public function fetchColumn($sql) {
		if ($result = $this -> query($sql)) {

			if ($row = mysql_fetch_array($result)) {
				mysql_free_result($result);
				$result = $row[0];
				if (is_array($result)) {
					array_walk_recursive($result, array($this, 'test'));
				} else {
					$this -> test($result);
				}
				return $result;
			} else {
				return false;
			}
		}
	}

	/**
	 * 在序列化时被调用
	 *
	 * 用于负责指明哪些属性需要被序列化
	 *
	 * @return array
	 */
	public function __sleep() {
		return array('host', 'port', 'user', 'password', 'charset', 'database');
	}

	/**
	 * 在反序列化时调用
	 *
	 * 用于 对对象的属性进行初始化
	 */
	public function __wakeup() {

		$this -> initMySQL();
	}

}
