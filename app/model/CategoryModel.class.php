<?php
class CategoryModel extends Model {
	
	protected $table_name='category';
	
	public function getList() {
		$sql = "SELECT * FROM {$this->table()} ORDER BY sort_order DESC,cat_id ASC";

		$list = $this -> db -> fetchAll($sql);
		return $list;
	}

	public function getTreeList($parent_id) {
		$list = $this -> getList();
		return $this -> getTree($list, $parent_id, 0);
	}

	public function delByID($cat_id) {
		if (!$this -> isLeaf($cat_id)) {
			$this -> error_info = '当前分类有子分类。';
			return false;
		} else {
//			$sql = "delete from {$this->table()} where cat_id={$cat_id}";
			$result = $this -> autoDelete($cat_id);
			$this -> error_info = $result ? '删除成功' : '未知的异常';
			return $result;
		}
	}

	public function getByID($id) {
		return $this -> autoSelectRow($id);
	}

	public function insertCat($data) {
		if ($data['cat_name'] == '') {
			$this -> error_info = '分类名不能为空';
			return false;
		}

		$sql = "select count(*) from {$this->table()} where cat_name='{$data['cat_name']}' and parent_id={$data['parent_id']}";
		$exists = $this -> db -> fetchColumn($sql);
		if (!$exists == 0) {
			$this -> error_info = '分类重名';
			return false;
		}
		$result=$this->autoInsert($data);
		$this -> error_info = (!$result ? '插入失败' : '插入成功');
		return $result;
	}

	public function updateCat($data) {
		$childlist = $this -> getTreeList($data['cat_id']);
		$ids[] = $data['cat_id'];
		foreach ($childlist as $value) {
			$ids[] = $value['cat_id'];
		}

		if (in_array($data['parent_id'], $ids)) {
			$this -> error_info = '要修改的父类不能是修改类的子类和父类。';

			return false;
		} else {
			$sql = "select count(*) from {$this->table()} where cat_name='{$data['cat_name']}' and parent_id={$data['parent_id']} and cat_id<>{$data['cat_id']}";
			$exists = $this -> db -> fetchColumn($sql);
			if (!$exists == 0) {
				$this -> error_info = '分类重名';
				return false;
			}
			else
			{
//				$sql="update {$this->table()} set cat_name='{$data['cat_name']}',cat_dsp='{$data['cat_dsp']}',sort_order={$data['sort_order']},parent_id={$data['parent_id']} where cat_id={$data['cat_id']}";
//				var_dump($sql);
				$result= $this->autoUpdate($data);
				$this -> error_info = (!$result ? '更新失败' : '更新成功');
				return $result;
			}
		}
	}

	public function isLeaf($cat_id) {
		$sql = "select count(*) from {$this->table()} where parent_id={$cat_id}";
		return $this -> db -> fetchColumn($sql) == 0;
	}

	private function getTree($arr, $p_id, $deep = 0) {
		static $tree = array();
		foreach ($arr as $row) {
			if ($row['parent_id'] == $p_id) {
				$row['deep'] = $deep;
				$tree[] = $row;
				$this -> getTree($arr, $row['cat_id'], $deep + 1);
			}
		}
		return $tree;
	}

}
