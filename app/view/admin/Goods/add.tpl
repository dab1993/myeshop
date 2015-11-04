
<!-- $Id: goods_info.htm 17126 2010-04-23 10:30:26Z liuhui $ -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ECSHOP 管理中心 - 添加新商品 </title>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="styles/general.css" rel="stylesheet" type="text/css" />
<link href="styles/main.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>

<h1>
<span class="action-span"><a href="goods.php?act=list">商品列表</a></span>
<span class="action-span1"><a href="index.php?act=main">ECSHOP 管理中心</a> </span><span id="search_id" class="action-span1"> - 添加新商品 </span>
<div style="clear:both"></div>
</h1>
<script type="text/javascript" src="../js/utils.js"></script><script type="text/javascript" src="js/selectzone.js"></script><script type="text/javascript" src="js/colorselector.js"></script><script type="text/javascript" src="../js/calendar.php?lang="></script>
<link href="../js/calendar/calendar.css" rel="stylesheet" type="text/css" />


<!-- start goods form -->
<div class="tab-div">
    <!-- tab bar -->
    <div id="tabbar-div">
      <p>
        <span class="tab-front" id="general-tab">通用信息</span><span
        class="tab-back" id="detail-tab">详细描述</span><span
        class="tab-back" id="mix-tab">其他信息</span><span
        class="tab-back" id="gallery-tab">商品相册</span>
      </p>
    </div>

    <!-- tab body -->
    <div id="tabbody-div">
      <form enctype="multipart/form-data" action="goods.php?act=insert" method="post" name="theForm" >
        <!-- 鏈€澶ф枃浠堕檺鍒 -->
        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
        
        <!-- 通用信息 -->
        <table width="90%" id="general-table" align="center">
          <tr>
            <td class="label">商品名称：</td>
            <td><input type="text" name="goods_name" value="" size="30" />
            <span class="require-field">*</span></td>
          </tr>
          <tr>
            <td class="label">
            <a href="javascript:showNotice('noticeGoodsSN');" title="点击此处查看提示信息"><img src="images/notice.gif" width="16" height="16" border="0" alt="点击此处查看提示信息"></a> 商品货号： </td>
            <td><input type="text" name="goods_sn" value="" size="20" onblur="checkGoodsSn(this.value,'0')" /><span id="goods_sn_notice"></span><br />
            <span class="notice-span" style="display:block"  id="noticeGoodsSN">如果您不输入商品货号，系统将自动生成一个唯一的货号。</span></td>
          </tr>
          <tr>
            <td class="label">商品分类：</td>
            <td><select name="cat_id" onchange="hideCatDiv()" ><option value="0">请选择...</option>
            	          <?php
          	foreach($this->data['category_list'] as $row)
          	{
          		$deep=str_repeat('&nbsp;',$row['deep']*4);
          		echo "<option value=\"{$row['cat_id']}\">{$deep}{$row['cat_name']}</option>";
          	}
          	?>
            </select><span class="require-field">*</span>            </td>
          </tr>
          <tr>
            <td class="label">本店售价：</td>
            <td><input type="text" name="shop_price" value="0" size="20" />
            <span class="require-field">*</span></td>
          </tr>
           
          <tr>
            <td class="label">市场售价：</td>
            <td><input type="text" name="market_price" value="0" size="20" />
            </td>
          </tr>
         
          <tr>
            <td class="label">上传商品图片：</td>
            <td>
              <input type="file" name="goods_image"/>
                              <!--<img src="images/no.gif" />
                            <br /><input type="text" size="40" value="商品图片外部URL" style="color:#aaa;" onfocus="if (this.value == '商品图片外部URL'){this.value='http://';this.style.color='#000';}" name="goods_img_url"/>-->
            </td>
          </tr>
        </table>

        <!-- 详细描述 -->
        <table width="90%" id="detail-table" style="display:none">
          <tr>
            <td><textarea name="goods_desc" style="width:100%; height:320px;" rows="1" cols="1"></textarea></td>
          </tr>
        </table>

        <!-- 其他信息 -->
        <table width="90%" id="mix-table" style="display:none" align="center">
                    
                              <tr>
            <td class="label"> 商品库存数量：</td>
            <td><input type="text" name="goods_number" value="1" size="20" /><br />
            </td>
          </tr>
          <tr>
            <td class="label">加入推荐：</td>
            <td><input type="checkbox" name="is_best" value="1"  />精品 <input type="checkbox" name="is_new" value="2"  />新品 <input type="checkbox" name="is_hot" value="4"  />热销</td>
          </tr>
          <tr id="alone_sale_1">
            <td class="label" id="alone_sale_2">上架：</td>
            <td id="alone_sale_3"><input type="checkbox" name="is_on_sale" value="1" checked="checked" /> 打勾表示允许销售，否则不允许销售。</td>
          </tr>
        </table>

        <!-- 商品相册 -->
        <!--<table width="90%" id="gallery-table" style="display:none" align="center">
          
          <tr>
            <td>
                          </td>
          </tr>
          <tr><td>&nbsp;</td></tr>
          
          <tr>
            <td>
              <a href="javascript:;" onclick="addImg(this)">[+]</a>
              图片描述 <input type="text" name="img_desc[]" size="20" />
              上传文件 <input type="file" name="img_url[]" />
              <input type="text" size="40" value="或者输入外部图片链接地址" style="color:#aaa;" onfocus="if (this.value == '或者输入外部图片链接地址'){this.value='http://';this.style.color='#000';}" name="img_file[]"/>
            </td>
          </tr>
        </table>-->

        <div class="button-div">
          <input type="hidden" name="goods_id" value="0" />
          <input type="submit" value=" 确定 " class="button" />
          <input type="reset" value=" 重置 " class="button" />
        </div>
        <input type="hidden" name="act" value="insert" />
      </form>
    </div>
</div>
<script type="text/javascript" src="js/tab.js"></script>
<?php require TEMPLATE_VIEW_PATH.'footer.html'; ?>
</script>
</body>
</html>