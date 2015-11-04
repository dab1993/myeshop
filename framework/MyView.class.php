<?php
/**
 * 此模版只是简单演示模版的原理，并不能实现基本功能，注入循环等功能皆不可实现。
 * 所以只是基本演示模版的执行流程。唔，具体的还是使用smarty比较好
 * 
 * @author dab1117@163.com
 */
	class MyView
	{
		private $data=array();
		private $left_de;
		private $right_de;
		private $tpl_dir;
		private $tplc_dir;
		
		/**
		 * 传入变量
		 */
		public function assign($key,$value)
		{
			$this->data[$key]=$value;
		}
		
		/**
		 * 构造方法
		 */
		public function __construct()
		{
			$this->left_de='<{';
			$this->right='}>';
		}
		
		/**
		 * 生成缓存文件
		 */
		public function complie($tpl)
		{
			$tpl=$this->tpl_dir.$tpl.'.tpl';
			$tpl_c=$this->tplc_dir.$tpl.'.php';
			
			if(file_exists($tpl_c)&&(filemtime($tpl)<filemtime($tpl_c)))
			{
				return $tpl_c;
			}
			
			$tpl_content=file_get_contents($tpl);
			$tpl_content=str_replace($this->left_de,'<?php echo $this->tpl["',$tpl_content);
			$tpl_content=str_replace($this->right_de,'"]; ?>',$tpl_content);
			
			file_put_contents($tpl_c,$tpl_content);
			return $tpl_c;
		}
		
		/**
		 * 显示模板
		 */
		public function display($path=ACTION)
		{
			$c_file=$this->complie($path);
			include $c_file;
		}
		
		/**
		 * 属性拦截器
		 */
		public function __set($p_name,$p_value)
		{
			$arr=array('left_de','right_de','tpl_dir','tpl_dir');
			if(in_array($p_name,$arr))
			{
				$this->$p_name=$p_value;
			}
		}
		
		/**
		 * 属性拦截器
		 */
		public function __get($p_name)
		{
			$arr=array('left_de','right_de','tpl_dir','tplc_dir');
			if(in_array($p_name,$arr))
			{
				return $this->$p_name;
			}
		}
	}
