<?php
class CategoryController extends AdminPlatformController {
	public function listAction() {
		$category_model = new CategoryModel;
		$list = $category_model -> getTreeList(0);
		$this -> view -> assign('list', $list);
		$this -> view -> display('list');
	}

	public function addAction() {
		$category_model = new CategoryModel;
		$list = $category_model -> getTreeList(0);
		$this -> view -> assign('list', $list);
		$this -> view -> display('add');
	}

	public function editAction() {
		$id = $_GET['cat_id'];
		$category_model = new CategoryModel;
		$list = $category_model -> getTreeList(0);
		$category = $category_model -> getByID($id);
		$this -> view -> assign('category', $category);
		
		$this -> view -> assign('list', $list);
		$this -> view -> display('edit');
	}

	public function updateAction() {
		$data['cat_id'] = $_POST['cat_id'];
		$data['cat_name'] = $_POST['cat_name'];
		$data['parent_id'] = $_POST['parent_id'];
		$data['cat_dsp'] = $_POST['cat_dsp'];
		$data['sort_order'] = $_POST['sort_order'];
		$category_model = new CategoryModel;
		$result = $category_model -> updateCat($data);
		if ($result) {$this -> redirect('category.php?act=list');
		} else {$this -> redirect('category.php?act=edit&cat_id=' . $data['cat_id'], $category_model -> error_info, 3);
		}
	}

	public function insertAction() {
		$data['cat_name'] = $_POST['cat_name'];
		$data['parent_id'] = $_POST['parent_id'];
		$data['cat_dsp'] = $_POST['cat_dsp'];
		$data['sort_order'] = $_POST['sort_order'];

		$category_model = new CategoryModel;
		$result = $category_model -> insertCat($data);

		if ($result) {$this -> redirect('category.php?act=list');
		} else {$this -> redirect('pre', $category_model -> error_info, 3);
		}

	}

	public function deleteAction() {
		$category_model = new CategoryModel;
		$result = $category_model -> delByID($_GET['id']);
		if (!$result) {
			$this -> redirect('category.php?act=list', $category_model -> error_info, 3);
		} else {
			$this -> redirect('category.php?act=list');
		}
	}

}
