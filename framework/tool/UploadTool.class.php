<?php
class UploadTool {
	private $max_size;
	private $upload_dir;
	private $allow_types;
	private $is_sub;
	private $error_info;

	/**
	 * 实例化上传工具类
	 *
	 * @param $dir string 上传主目录
	 * @param $size 文件最大限制（字节）默认当前目录
	 * @param $is_sub boolean 是否需要递归创建默认为true
	 * @param $types array 允许的上传类型，默认为png和jpeg的图片
	 */
	public function __construct($dir = '', $size = 2000000, $is_sub = true, $types = array()) {
		$this -> upload_dir = $dir;
		$this -> max_size = $size;
		$this -> is_sub = $is_sub;
		$this -> allow_types = empty($types) ? array('image/jpeg', 'image/png') : $types;
	}

	public function __set($p_name, $p_value) {
		if (in_array($p_name, array('upload_dir', 'max_size', 'allow_types'))) {
			$this -> $p_name = $p_value;
		}
	}

	public function __get($p_name) {
		if ($p_name == 'error_info') {
			return $this -> $p_name;
		}
	}

	/**
	 * 上传文件
	 * @param $file array 文件上传信息
	 * @return 返回上传信息 错误代码 0:上传成功 ，1:文件太大，超出php.ini的限制，2：文件太大，超出MAX_FILE_SIZE的限制，3：文件没有上传完，4：没有上传文件，6&7:临时文件夹错误
	 */
	public function upload($file) {
		if ($file['error'] == 0) {
			if (!in_array($file['type'], $this -> allow_types)) {
				$this -> error_info = '文件类型错误！';
				return false;
			} else if ($file['size'] > $this -> max_size) {
				$this -> error_info = '文件太大！';
				return false;
			} else if (!is_uploaded_file($file['tmp_name'])) {
				$this -> error_info = '文件错误！';
				return false;
			} else {
				$new_name = uniqid('goods_') . strrchr($file['name'], '.');
				$save_path = $this -> upload_dir;
				$subdir_str = date('Ymd');
				if (!is_dir($this -> upload_dir)) {
					mkdir($this -> upload_dir, true);
				}
				if ($this -> is_sub) {
					$subdir_name = $this -> upload_dir . $subdir_str . DIRECTORY_SEPARATOR;

					if (!is_dir($subdir_name)) {
						mkdir($subdir_name, true);
					}
					$save_path = $subdir_name;
				}
				move_uploaded_file($file['tmp_name'], $save_path . $new_name);
				$image_tool = new ImageTool($save_path.$new_name);
				$thumb_name=$image_tool->makeThumb(100,100);
				$image_tool->waterMark(UPLOAD_PATH.'yimolife.png');
				return array(($this -> is_sub ? $subdir_str . '/' : '') . $new_name,($this -> is_sub ? $subdir_str . '/' : '').$thumb_name);
			}
		} else {
			$this -> error_info = '文件上传失败，可能的原因：文件过大。错误代码100' . $file['error'];
			return false;
		}
	}

}
