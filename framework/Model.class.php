<?php
abstract class Model {
	protected $db;
	protected $prefix;
	protected $fileds;

	public function __construct() {
		$this -> initLink();
	}

	protected function initLink() {

		$option = $GLOBALS['config']['database'];

		$this -> db = MySQLDBTool::getInstance($option);

		$this -> prefix = $option['prefix'];

		$this -> getFileds();
	}

	protected function table() {
		return '`' . $this -> prefix . $this -> table_name . '`';
	}

	public function autoDelete($pk_value) {
		$sql = "delete from {$this->table()} where `{$this->fileds['pk']}`='{$pk_value}'";

		return $this -> db -> query($sql);
	}

	public function autoSelectRow($pk_value) {
		$sql = "select * from {$this->table()} where `{$this->fileds['pk']}`='{$pk_value}'";
		return $this -> db -> fetchRow($sql);
	}

	public function getFileds() {
		$sql = "desc {$this->table()}";

		$fileds_rows = $this -> db -> fetchAll($sql);
		foreach ($fileds_rows as $row) {
			$this -> fileds[] =$row['Field'];
			//			var_dump($row);
			if ($row['Key'] == 'PRI') {
				$this -> fileds['pk'] = $row['Field'];
			}
		}
	}

	public function autoInsert($data) {
		$sql = "insert into {$this->table()}( ";
		$fileds = array_keys($data);

		$arr_temp = array_map(function($v) {
			return '`' . $v . '`';
		}, $fileds);
		$keys = implode(',', $arr_temp);
		$sql .= $keys . ') values(';

		$values = array_map(function($v) {
			return "'$v'";
		}, $data);
		$sql .= implode(',', $values) . ')';
		$result=$this -> db ->last_insert_id($sql);
		return $result;
	}
	public function autoTotalCount()
	{
		$sql="select count(*) from {$this->table()}";
		return $this->db->fetchColumn($sql);
	}
	
	/**
	 * 更新数据并返回受影响的行数
	 */
	public function autoUpdate($data) {
		$sql = "update {$this->table()} set ";
		foreach ($data as $key => $value) {
			if ($key != $this -> fileds['pk'])
				$tmp_arr[] = "`{$key}`='{$value}'";
		}
		$sql.=implode(',',$tmp_arr)." where `{$this->fileds['pk']}`='{$data[$this->fileds['pk']]}'";
		
		return $this->db->exec($sql);
	}

}
