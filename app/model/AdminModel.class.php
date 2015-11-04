<?php	
	class AdminModel extends Model
	{
		protected $table_name='admin';
		
		public function checkCookie()
		{
			if(!isset($_COOKIE['admin_id'])||!isset($_COOKIE['admin_pass']))
			{
				return false;
			}
			else
			{
				$sql="select * from {$this->table()} where admin_id={$_COOKIE['admin_id']} and md5(concat('qin',admin_pass))='{$_COOKIE['admin_pass']}'";
				return  $this->db->fetchRow($sql);
			}
		}
		public function checkAccunt($admin_name,$admin_pass)
		{
			$admin_name=mysql_real_escape_string($admin_name);
			$admin_pass=mysql_real_escape_string($admin_pass);
		 	//select * from it_admin where admin_name='dab1117' and admin_pass=md5('') OR 1=1 OR 1=('1') 注入攻击语句 
			$sql="select * from {$this->table()} where admin_name='$admin_name' and admin_pass=md5('$admin_pass')";
			$result=$this->db->fetchRow($sql);			
			
		 	return $result;
		}
		public function adminList()
		{
			$sql='select * from {$this->table()} limit 10';
			return $this->db->fetchAll($sql);
		}
	}
