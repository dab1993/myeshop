<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加分类 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>

<h1>
<span class="action-span"><a href="category.php?act=list">商品分类</a></span>
<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加分类 </span>
<div style="clear:both"></div>
</h1>
<!-- start add new category form -->
<div class="main-div">
  <form action="category.php?act=insert" method="post" name="theForm" enctype="multipart/form-data">
  <table width="100%" id="general-table">
      <tr>
        <td class="label">分类名称:</td>
        <td>
          <input type='text' name='cat_name' maxlength="20" value='' size='27' /> <font color="red">*</font>
        </td>
      </tr>
      <tr>
        <td class="label">上级分类:</td>
        <td>
          <select name="parent_id">
            <option value="0">顶级分类</option>
         {foreach $list as $row}
          	
          		<option value="{$row.cat_id}">{'&nbsp;'|str_repeat:($row.deep*2)}{$row.cat_name}</option>
          	{/foreach}
          </select>
        </td>
      </tr>
      <tr>
        <td class="label">排序:</td>
        <td>
          <input type="text" name='sort_order'  value="0" size="15" />
        </td>
      </tr>
      <tr>
        <td class="label">分类描述:</td>
        <td>
          <textarea name='cat_dsp' rows="6" cols="48"></textarea>
        </td>
      </tr>
      </table>
      <div class="button-div">
        <input type="submit" value=" 确定 " />
        <input type="reset" value=" 重置 " />
      </div>
    <input type="hidden" name="act" value="insert" />
    <input type="hidden" name="old_cat_name" value="" />
    <input type="hidden" name="cat_id" value="" />
  </form>
</div>
<script type="text/javascript" src="../js/utils.js"></script>
{include file=$smarty.const.TEMPLATE_VIEW_PATH|cat:'footer.html' }
</body>
</html>