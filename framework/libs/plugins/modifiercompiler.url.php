<?php
	
	/**
	 * 如果图片是空的则替换成默认嫩的空照片。文件相对路径基于UPLOAD_URL
	 * 
	 * @package Smarty
	 * 
	 * @author dab1117@163.com
	 */
	 function smarty_modifiercompiler_url($params, $compiler)
	{
//		$cont=preg_replace("/'/",'',$params[0]);
		$cont=$params[0];
		$cont=UPLOAD_URL.'".(empty('.$cont.')?"'.preg_replace("/'/",'',$params[1]).'":'.$cont.')."';
		
		return '"<image width=100 height=100 src=\"'.$cont.'\"/>"';
	}
