<?php
/**
 * session入库工具类
 */
class MySessionDBTool {
	public static function sess_open() {
		$GLOBALS['db'] = MySQLDBTool::getInstance($GLOBALS['config']['database']);
	}

	public static function sess_read($sess_id) {
		$sql = "select `sess_data` from `it_session` where `sess_id`='$sess_id'";
		return $GLOBALS['db'] -> fetchColumn($sql);
	}

	public static function sess_write($sess_id, $sess_data) {
		$expire = time();
		$sql = "insert into `it_session` values ('$sess_id','$sess_data',$expire) on duplicate key update `sess_data`='$sess_data',`expire`=$expire";
		$result = $GLOBALS['db'] -> query($sql);
		//var_dump($result);
		return $result;
	}

	public static function sess_close() {
		unset($GLOBALS['db']);
	}

	public static function sess_destroy($sess_id) {
		$sql = "delete from `it_session` where `sess_id`='$sess_id'";
		$result = $GLOBALS['db'] -> query($sql);
		//var_dump($result);
		return $result;
	}

	public static function sess_gc($ttl) {
		$now = time();
		$last = $now - $ttl;
		$sql = "delete from `it_session` where `expire`<$last";
		return $GLOBALS['db'] -> query($sql);
	}
	
	/**
	 * 注册session存储方法 放在页收
	 */
	public static function init()
	{
//		session_set_save_handler('MySession::sess_open', 'MySession::sess_close', 'MySession::sess_read', 'MySession::sess_write', 'MySession::sess_destroy', 'MySession::sess_gc'); --这种方式调用静态可以。

/**
 * 这种方式调用静态也可以。
 */
session_set_save_handler(array('SessionDBTool','sess_open'),array('SessionDBTool','sess_close'),array('SessionDBTool','sess_read'),array('SessionDBTool','sess_write'),array('SessionDBTool','sess_destroy'),array('SessionDBTool','sess_gc'));
		@session_start();
	}

}
