<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 商品分类 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
</head>
<body>

<h1>
<span class="action-span"><a href="category.php?act=add">添加分类</a></span>
<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id" class="action-span1"> - 商品分类 </span>
<div style="clear:both"></div>
</h1>
<form method="post" action="" name="listForm">
<!-- start ad position list -->
<div class="list-div" id="listDiv">

<table width="100%" cellspacing="1" cellpadding="2" id="list-table">
  <tr>
    <th>分类名称</th>
    <th>商品数量</th>
    <th>排序</th>
    <th>操作</th>
  </tr>	
  	{foreach $list as $row}
  	<tr align="center" class="{$row['parent_id']}" id="0_1">
    <td align="left" class="first-cell" >
            <img src="images/menu_minus.gif" id="icon_0_1" width="9" height="9" border="0" style="margin-left:{$row[
            "deep"]}em" onclick="rowClicked(this)" />
            <span><a href="goods.php?act=list&cat_id=1">{$row["cat_name"]}</a></span>
        </td>
    <td width="10%">0</td>
    <td width="10%" align="right">{$row["sort_order"]}</span></td>
    <td width="24%" align="center">
      <a href="category.php?act=move&cat_id=1">转移商品</a> |
      <a href="category.php?act=edit&amp;cat_id={$row["cat_id"]}">编辑</a> |
      <a href="category.php?act=delete&id={$row["cat_id"]}" onclick="return confirm('确定删除吗');" title="移除">移除</a>
    </td>
  </tr>

	{/foreach}
  </table>
</div>
</form>
{include file=$smarty.const.TEMPLATE_VIEW_PATH|cat:'footer.html' }
</body>
</html>