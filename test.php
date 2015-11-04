<?php
header('Content-Type: text/html;charset=gbk');
echo <<<STYLE
	<style type="text/css">
		*{
			line-height:25px;
			color:#00f;
			font-family:Microsoft Yahei;
		}
	</style>
STYLE;
//$hehe='dsadsadsad4545dsa';
//$file='./index.php';
//var_dump(filesize($file));

//class resultmodel extends Model
//{
//	protected $table_name='result';
//}
//$obj=new resultmodel;
//$data=array('student'=>'张飞','subject'=>'语文','results'=>'50');
//$result=$obj->autoInsert($data);
//
//
//
//	$result=$obj->autoInsert($data);
//
//
//var_dump($result);
echo '<hr>';
//class MyException extends Exception
//{
//
//}
//
//try
//{
//
//	throw new Exception('触发异常');
//	//throw new Exception('触发异常');
//
//}
//catch(MyException $e)
//{
//	echo $e->getMessage();
//}
//catch(Exception $e)
//{
//	echo $e->getMessage();
////}
//if ($handle = opendir('../')) {
//  echo "Directory handle: $handle\n";
//  echo "Files:\n";
//
//  /* 这是正确地遍历目录方法 */
////  while (false !== ($file = readdir($handle))) {
////      var_dump($file);
////  }
//
//  /* 这是错误地遍历目录的方法 */
//  while (($file = readdir($handle))!==false) {
//      echo "$file\n";
//  }
//
//  closedir($handle);
//}

//function deepDir($dir, $deep) {
//	echo '<div style="margin:5px; border:1px solid #888;">';
//	$dir_array = array();
//	if ($handle = opendir($dir)) {
//		while (($file = readdir($handle)) !== false) {
//			if ($file == '.' || $file == '..'||strpos($file,'.')==0) {
//
//			} else {
//				echo str_repeat('&nbsp;', $deep * 4), $file, '<br>';
//				if (is_dir($dir . DIRECTORY_SEPARATOR . $file) && $file != '.' && $file != '..') {
//					deepDir($dir . DIRECTORY_SEPARATOR . $file, $deep + 1);
//				}
//			}
//
//		}
//		closedir($handle);
//	}
//	echo '</div>';
//	return true;
//}
//
//deepDir('D:\MyGitHub\linux', 0);


//$openfile=fopen('./test.txt','r');
//
//var_dump($openfile);
//while(!feof($openfile))
//{
//	echo fgets($openfile); 
//}
//fclose($openfile);

//function buketSort($arr)
//{
//	$max=max($arr);
//	$min=min($arr);
//	
//	
//	$buket=array_fill($min,($max-$min)+1,0);
//	
//	foreach($arr as $value)
//	{
//		$buket[$value]++;
//	}
//	$new_arr=array();
//	foreach($buket as $key=>$value)
//	{
//		for($i=0;$i<$value;$i++)
//		{
//			$new_arr[]=$key;
//		}
//	}
//	return $new_arr;
//}
//
//$array=array(100,200,12,15,88,54,6,525,51,35,15,152,5);
//
//$new=buketSort($array);
//var_dump($new);

//echo stripslashes($_GET['str']);

$dsn='mysql:dbname=myeshop;host=127.0.0.1;port=3306;charset=utf8';
$pdo=new PDO($dsn,$user,$password);
