
<!-- $Id: start.htm 17216 2011-01-19 06:03:12Z liubo $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心</title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>
<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id" class="action-span1"></span>
<div style="clear:both"></div>
</h1>
<!-- directory install start -->
<ul id="cloud_list" style="padding:0; margin: 0; list-style-type:none; color: #CC0000;">
 
</ul>
<script type="Text/Javascript" language="JavaScript">
    function cloud_api(result)
    {
      //alert(result.content);
      if(result.content=='0')
      {
        document.getElementById("cloud_list").style.display ='none';
      }
      else
       {
         document.getElementById("cloud_list").innerHTML =result.content;
      }
    } 
   function cloud_close(id)
    {
      Ajax.call('cloud.php?is_ajax=1&act=close_remind&remind_id='+id,'', cloud_api, 'GET', 'JSON');
    }
 </script> 
<!-- start system information -->
<div class="list-div">
<table cellspacing='1' cellpadding='3'>
  <tr>
    <th colspan="4" class="group-title">系统信息</th>
  </tr>
  <tr>
    <td width="20%">服务器操作系统:</td>
    <td width="30%">WINNT (127.0.0.1)</td>
    <td width="20%">Web 服务器:</td>
    <td width="30%">Apache/2.2.21 (Win32) PHP/5.3.10</td>
  </tr>
  <tr>
    <td>PHP 版本:</td>
    <td>5.3.10</td>
    <td>MySQL 版本:</td>
    <td>5.5.20-log</td>
  </tr>
  <tr>
    <td>安全模式:</td>
    <td>否</td>
    <td>安全模式GID:</td>
    <td>否</td>
  </tr>
  <tr>
    <td>Socket 支持:</td>
    <td>是</td>
    <td>时区设置:</td>
    <td>UTC</td>
  </tr>
  <tr>
    <td>GD 版本:</td>
    <td>GD2 ( JPEG GIF PNG)</td>
    <td>Zlib 支持:</td>
    <td>是</td>
  </tr>
  <tr>
    <td>IP 库版本:</td>
    <td>20071024</td>
    <td>文件上传的最大大小:</td>
    <td>2M</td>
  </tr>
  <tr>
    <td>ECShop 版本:</td>
    <td>v2.7.3 RELEASE 20121106</td>
    <td>安装日期:</td>
    <td>2015-10-26</td>
  </tr>
  <tr>
    <td>编码:</td>
    <td>UTF-8</td>
    <td></td>
    <td></td>
  </tr>
</table>
</div>
<?php require TEMPLATE_VIEW_PATH.'footer.html'; ?>

</body>
</html>