<?php
class GoodsController extends AdminPlatformController {
	public function addAction() {
		$model_category = new CategoryModel;
		$category_list = $model_category -> getTreeList(0);
		$this->view->assign('category_list',$category_list);
		$this->view->display('add.tpl');
	}

	public function insertAction() {
		$data['goods_name'] = $_POST['goods_name'];
		$data['goods_sn'] = $_POST['goods_sn'];
		$data['cat_id'] = $_POST['cat_id'];
		$data['shop_price'] = $_POST['shop_price'];
		$data['market_price'] = $_POST['market_price'];

		$data['goods_desc'] = $_POST['goods_desc'];
		$data['goods_number'] = $_POST['goods_number'];

		$isbest = Framework::getNullRequrest('is_best', 0, 'post');
		$isnew = Framework::getNullRequrest('is_new', 0, 'post');
		$ishot = Framework::getNullRequrest('is_hot', 0, 'post');

		$data['goods_status'] = 0 | $isbest | $isnew | $ishot;
		$data['is_on_sale'] = Framework::getNullRequrest('is_on_sale', 0,'post');
		
		if($_FILES['goods_image']['error']!=4)
		{
			$upload_tool = new UploadTool(GOODS_IMAGE_PATH, 20480000);
			$result = $upload_tool -> upload($_FILES['goods_image']);
			if (!$result) {
				$this -> redirect('pre', $upload_tool -> error_info);
			}
			$data['image_ori'] = $result[0];
			$data['image_thumb']=$result[1];
		}
		$goods_model = new GoodsModel;
		$result = $goods_model -> insertGoods($data);
		if (!$result) {
			if(isset($data['image_ori']))
			{
				
				unlink(GOODS_IMAGE_PATH.str_replace('/', DS, $data['image_ori']));
				unlink(GOODS_IMAGE_PATH.str_replace('/', DS, $data['image_thumb']));
				
			}
			$this -> redirect('pre', $goods_model -> error_info, 30);
		} else {
			$this -> redirect('pre', '添加成功！', 2);
		}
	}

	public function listAction() {
		$page = Framework::getNullRequrest('page', 1);
		$pagesize = Framework::getNullRequrest('pagesize', 10);

		$goods_model = new GoodsModel;
		$list = $goods_model -> getList($page, $pagesize);
		$this->view->assign('list',$list);
		$page_html = PageTool::show($page, $pagesize, $goods_model -> autoTotalCount());
		$this->view->assign('page_html',$page_html);
		$this->view->display('list.tpl');
	}

}
