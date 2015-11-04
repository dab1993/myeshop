<?php
class GoodsModel extends Model {
	
	protected $table_name='goods';
	
	public function getList($page,$pagesize) {
		$offset=($page-1)*$pagesize;
		
		$sql = "SELECT * FROM {$this->table()} limit $offset,$pagesize";
		$list = $this -> db -> fetchAll($sql);
		return $list;
	}

	public function delByID($id) {

			$result = $this -> autoDelete($id);
			$this -> error_info = $result ? '删除成功' : '未知的异常';
			return $result;
	}

	public function getByID($id) {
		return $this -> autoSelectRow($id);
	}

	public function insertGoods($data) {
		if ($data['cat_id'] == '0') {
			$this -> error_info = '不能为顶级分类';
			return false;
		}
		
		$result=$this->autoInsert($data);
		$this -> error_info = (!$result ? $this->db->error_info : '插入成功');
		return $result;
	}

}
