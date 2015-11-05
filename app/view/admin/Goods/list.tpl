<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

	<head>
		<title>ECSHOP 管理中心 - 商品列表 </title>
		<meta name="robots" content="noindex, nofollow">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link href="styles/general.css" rel="stylesheet" type="text/css" />
		<link href="styles/main.css" rel="stylesheet" type="text/css" />
	</head>

	<body>

		<h1>
<span class="action-span"><a href="goods.php?act=add">添加新商品</a></span>
<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id" class="action-span1"> - 商品列表 </span>
<div style="clear:both"></div>
</h1>
		<script type="text/javascript" src="../js/utils.js"></script>
		<!-- 商品列表 -->
		<form method="post" action="" name="listForm" onsubmit="return confirmSubmit(this)">
			<!-- start goods list -->
			<div class="list-div" id="listDiv">
				<table cellpadding="3" cellspacing="1">
					<tr>
						<th>
							<input onclick='listTable.selectAll(this, "checkboxes")' type="checkbox" />
							<a>编号</a><img src="images/sort_desc.gif" /> </th>

						<th>操作</th>
						<th><a>商品名称</a></th>
						<th><a>货号</a></th>
						<th><a>价格</a></th>
						<th><a>上架</a></th>
						<th><a>精品</a></th>
						<th><a>新品</a></th>
						<th><a>热销</a></th>
						<th><a>库存</a></th>
					</tr>
					{foreach $list as $row}
					<tr>
						<td>
							<input type="checkbox" name="checkboxes[]" value="{$row.goods_id}" />{$row.goods_id}</td>
						<td align="center">
							<a target="_blank" href={$smarty.const.UPLOAD_URL|cat:$row.image_ori}>{$row.image_thumb|url : '/20151101/goods_5635c2c7b86b5.png'}</a>
							<!--<a href="../goods.php?id=9" target="_blank" title="查看"><img src="images/icon_view.gif" width="16" height="16" border="0" /></a>
      <a href="goods.php?act=edit&goods_id=9&extension_code=" title="编辑"><img src="images/icon_edit.gif" width="16" height="16" border="0" /></a>
      
      <a href="javascript:;" title="回收站"><img src="images/icon_trash.gif" width="16" height="16" border="0" /></a>-->
						</td>
						<td class="first-cell" align="center" style=""><span>{$row.goods_name}</span></td>
						<td align="center"><span>{$row.goods_sn}</span></td>
						<td align="center"><span>{$row.shop_price}
    </span></td>
						<td align="center"><img src="images/{if $row.is_on_sale==1}yes{else}no{/if}.gif" /></td>
						<td align="center"><img src="images/{if $row.goods_status&1}yes{else}no{/if}.gif" /></td>
						<td align="center"><img src="images/{if $row.goods_status&2}yes{else}no{/if}.gif" /></td>
						<td align="center"><img src="images/{if $row.goods_status&4}yes{else}no{/if}.gif" /></td>
						<td align="center"><span>{$row.goods_number}</span></td>

					</tr>
					{/foreach}
				</table>
				<!-- end goods list -->

				<!-- 分页 -->
				<table id="page-table" cellspacing="0">
					<tr>
						<td align="right" nowrap="true">
							{$page_html}
						</td>
					</tr>
				</table>

			</div>

		</form>
		{include file=$smarty.const.TEMPLATE_VIEW_PATH|cat:'footer.html' }
	</body>

</html>