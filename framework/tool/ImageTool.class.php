<?php
/**
 * 图片处理类
 *
 * 生成缩略图，打水印
 *
 * @author dab1117@163.com
 */
class ImageTool {
	private $fullname;
	private $image;
	private $info;
	private $width;
	private $height;
	private $mime;
	private $ext_name;
	private $save_path;
	private $file_name_without_ext;
	private $error_info;
	private $save_func;
	private $open_func;

	private $water_info;
	/**
	 * @param $filename string 待图片的完整路径
	 * @param $save_path string 处理后图片的路径
	 */
	public function __construct($filename, $save_path = '') {
		$this -> fullname = $filename;
		$this -> initlizeImage($filename, $save_path);
	}

	/**
	 * 初始化图片信息
	 */
	private function initlizeImage($filename, $save_path) {
		$this -> info = getimagesize($filename);
		$this -> save_path = !empty($save_path) ? $save_path : (dirname($filename) . DIRECTORY_SEPARATOR);

		$this -> mime = $this -> info['mime'];
		$this -> width = $this -> info[0];
		$this -> height = $this -> info[1];
		$type = strrchr($this -> mime, '/');
		$type = substr($type, 1);
		$this -> open_func = 'imagecreatefrom' . $type;
		$this -> save_func = 'image' . $type;
		$this -> ext_name = strrchr($filename, '.');
		$this -> file_name_without_ext = str_replace($this -> ext_name, '', basename($filename));
	}

	/**
	 * 创建缩略图
	 *
	 * @param $width int 缩略图宽度
	 * @param $width int 缩略图宽度
	 * @param $mod int 缩略图缩放模式，1：裁切，2：等比例留白 ，3：拉伸
	 */
	public function makeThumb($width = 230, $height = 230, $mod = 1, $new_name = '') {

		$open_func = $this -> open_func;
		$save_func = $this -> save_func;

		$this -> image = $open_func($this -> fullname);
		//定义缩略图的宽高
		$max_w = $width;
		$max_h = $height;

		//原图 x和y值
		$src_x = 0;
		$src_y = 0;

		//目标图的x和y值
		$dst_x = 0;
		$dst_y = 0;

		if ($mod == 1) {
			// 裁剪缩放
			if ($this -> width < $this -> height) {
				$src_w = $this -> width;
				$src_h = ($this -> width * $max_h) / $max_w;

				$src_x = 0;
				$src_y = ($this -> height - $src_h) / 2;
			} else {
				$src_h = $this -> height;
				$src_w = ($this -> height * $max_w) / $max_h;

				$src_y = 0;
				$src_x = ($this -> width - $src_w) / 2;
			}
			$dst_w = $max_w;
			$dst_h = $max_h;
		} else if ($mod == 2) {
			//等比例缩放
			if ($this -> width < $this -> height) {

				$dst_h = $max_h;
				$dst_w = ($this -> width * $max_h) / $this -> height;

				$dst_x = ($max_w - $dst_w) / 2;
			} else {
				$dst_w = $max_w;
				$dst_h = ($this -> height * $max_w) / $this -> width;

				$dst_y = ($max_h - $dst_h) / 2;
			}

			$src_w = $this -> width;
			$src_h = $this -> height;
		} else {
			//拉伸缩放
			$dst_w = $max_w;
			$dst_h = $max_h;

			$src_w = $this -> width;
			$src_h = $this -> height;
		}
		$dst_image = imagecreatetruecolor($max_w, $max_h);

		//防止黑边
		$color = imagecolorallocatealpha($dst_image, 0, 0, 0, 127);
		imagealphablending($dst_image, false);
		imagefill($dst_image, 0, 0, $color);

		$dst_file_name = empty($new_name) ? ($this -> file_name_without_ext . '_thumb' . $this -> ext_name) : $new_name;

		imagecopyresampled($dst_image, $this -> image, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);
		imagesavealpha($dst_image, true);
		$result = $save_func($dst_image, $this -> save_path . $dst_file_name);

		imagedestroy($dst_image);
		imagedestroy($this -> image);
		if ($result) {
			return $dst_file_name;
		} else {
			$this -> error_info = '处理图片出现异常！';
			return false;
		}
	}

	/**
	 * 添加水印
	 */
	public function waterMark($water_image, $pos=9,$new_name='') {

		$open_func = $this -> open_func;
		$save_func = $this -> save_func;

		$this -> image = $open_func($this -> fullname);

		$image_tool = new ImageTool($water_image);
		$this -> water_info['stamp_image'] = $image_tool;

		$this -> waterpos($pos);
		$open_func_stamp = $image_tool -> open_func;
		$save_func_stamp = $image_tool -> save_func;

		$stamp_image = $open_func_stamp($water_image);

		if (($this -> width <= $image_tool -> width) || ($this -> height <= $image_tool -> height)) {
			$this -> error_info = '水印图片过大！';
			return false;
		}
		$cut = imagecreatetruecolor($image_tool -> width, $image_tool -> height);
		imagecopy($cut, $this -> image, 0, 0, $this -> water_info['pos']['x'], $this -> water_info['pos']['y'], $image_tool -> width, $image_tool -> height);
		$pct = 100;
		imagecopy($cut, $stamp_image, 0, 0, 0, 0, $image_tool -> width, $image_tool -> height);
		imagecopymerge($this -> image, $cut, $this -> water_info['pos']['x'], $this -> water_info['pos']['y'], 0, 0, $image_tool -> width, $image_tool -> height, $pct);
		imagesavealpha($this -> image, true);
		$new_name=empty($new_name)?basename($this->fullname):$new_name;
		$save_func($this -> image,$this->save_path.$new_name);
		imagedestroy($this->image);
		imagedestroy($stamp_image);
		return $new_name;
	}

	private function waterpos($pos) {//水印位置算法
	
//var_dump($this -> water_info['stamp_image']);
		switch ($pos) {
			case 0 :
				//随机位置
				$this -> water_info['pos']['x'] = rand(0, $this -> width - $this -> water_info['stamp_image'] -> width);
				$this -> water_info['pos']['y'] = rand(0, $this -> height - $this -> water_info['stamp_image'] -> height);
				break 1;
			case 1 :
				//上左
				$this -> water_info['pos']['x'] = 0;
				$this -> water_info['pos']['y'] = 0;
				break 1;
			case 2 :
				//上中
				$this -> water_info['pos']['x'] = ($this -> width - $this -> water_info['stamp_image'] -> width) / 2;
				$this -> water_info['pos']['y'] = 0;
				break 1;
			case 3 :
				//上右
				$this -> water_info['pos']['x'] = $this -> width - $this -> water_info['stamp_image'] -> width;
				$this -> water_info['pos']['y'] = 0;
				break 1;
			case 4 :
				//中左
				$this -> water_info['pos']['x'] = 0;
				$this -> water_info['pos']['y'] = ($this -> height - $this -> water_info['stamp_image'] -> height) / 2;
				break 1;
			case 5 :
				//中中
				$this -> water_info['pos']['x'] = ($this -> width - $this -> water_info['stamp_image'] -> width) / 2;
				$this -> water_info['pos']['y'] = ($this -> height - $this -> water_info['stamp_image'] -> height) / 2;
				break 1;
			case 6 :
				//中右
				$this -> water_info['pos']['x'] = $this -> width - $this -> water_info['stamp_image'] -> width;
				$this -> water_info['pos']['y'] = ($this -> height - $this -> water_info['stamp_image'] -> height) / 2;
				break 1;
			case 7 :
				//下左
				$this -> water_info['pos']['x'] = 0;
				$this -> water_info['pos']['y'] = $this -> height - $this -> water_info['stamp_image'] -> height;				
				break 1;
			case 8 :
				//下中
				$this -> water_info['pos']['x'] = ($this -> width - $this -> water_info['stamp_image'] -> width) / 2;
				$this -> water_info['pos']['y'] = $this -> height - $this -> water_info['stamp_image'] -> height;
				break 1;
			default :
				//下右
				$this -> water_info['pos']['x'] = $this -> width - $this -> water_info['stamp_image'] -> width;
				$this -> water_info['pos']['y'] = $this -> height - $this -> water_info['stamp_image'] -> height;
				break 1;
		}
//var_dump($this -> water_info['pos']);
	}

	/**
	 * 属性拦截器
	 */
	public function __get($p_name) {
		return $this -> $p_name;
	}

	/**
	 * 设置拦截器，只允许修改savepath
	 */
	public function __set($p_name, $p_value) {
		if (in_array($p_name, array('save_path'))) {
			$this -> $p_name = $p_value;
		} else {
			return false;
		}
	}

}
