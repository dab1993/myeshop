<?php
/**
 * @author dab1117
 *
 */

class SessionDBTool {
	private $db;
	public function sess_open() {
		$this->db = MySQLDBTool::getInstance($GLOBALS['config']['database']);
	}

	public function sess_read($sess_id) {
		$sql = "select `sess_data` from `it_session` where `sess_id`='$sess_id'";
		return $this->db -> fetchColumn($sql);
	}

	public function __construct()
	{
		
		// 这里使用静态的调用方法不行了。必须传入一个对象
		
		ini_set('session.save_handler','user');
		
		session_set_save_handler(
		array($this,'sess_open'),
		array($this,'sess_close'),
		array($this,'sess_read'),
		array($this,'sess_write'),
		array($this,'sess_destroy'),
		array($this,'sess_gc')
		);
		
		@session_start();
		
		
	}
	public function sess_write($sess_id, $sess_data) {
		$expire = time();
		$sql = "insert into `it_session` values ('$sess_id','$sess_data',$expire) on duplicate key update `sess_data`='$sess_data',`expire`=$expire";
		$result = $this->db -> exec($sql);
		//var_dump($result);
		return $result;
	}

	public function sess_close() {
		unset($this->db);
		return true;
	}

	public function sess_destroy($sess_id) {
		$sql = "delete from `it_session` where `sess_id`='$sess_id'";
		$result = $this->db -> query($sql);
		//var_dump($result);
		return $result;
	}

	public function sess_gc($ttl) {
		$now = time();
		$last = $now - $ttl;
		$sql = "delete from `it_session` where `expire`<$last";
		return $this->db -> query($sql);
	}

}
