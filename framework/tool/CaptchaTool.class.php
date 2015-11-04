<?php
class CaptchaTool {
	public function generate() {
		//确定背景图片
		$rand_bg_file = TOOL_PATH . 'captcha' . DS . 'captcha_bg' . mt_rand(1, 5) . '.jpg';

		$img = imagecreatefromjpeg($rand_bg_file);

		$black = imagecolorallocate($img, 0, 0, 0);
		$white = imagecolorallocate($img, 0xff, 0xff, 0xff);
		//绘制边框
		//imagerectangle($img, 0, 0, 144, 19, $white);
		//生成验证码
		$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
		$captcha_str = '';
		for ($i = 0, $strlen = strlen($chars); $i < 4; $i++) {
			$rand_key = mt_rand(0, $strlen - 1);
			$captcha_str .= $chars[$rand_key];
		}

		@session_start();
		$_SESSION['captcha'] = $captcha_str;
		$str_color = mt_rand(0, 1) == 1 ? $black : $white;

		imagestring($img, 5, 53, 2, $captcha_str, $str_color);

		imagejpeg($img);
		imagedestroy($img);
	}

}
