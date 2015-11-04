<?php
class PageTool {
	public static function show($page = 1, $pagesize = 10, $total = 1,$url = '', $params = array()) {
		if (empty($url)) {
			$url = $_SERVER['REQUEST_URI'];

			$url = preg_replace("/&page=\d+/", "", $url);
		}
		$url_info = parse_url($url);


		$url .= !isset($url_info['query']) ? '?' : '&';

		foreach ($params as $key => $value) {
			$url .= "{$key}={$value}&";
		}

		$total_page = ceil($total / $pagesize);

		$url .= "page=";

		$next_url = $page == $total_page ? 'javascript:void;' : ($url . ($page + 1));
		$prev_url = $page == 1 ? 'javascript:void;' : ($url . ($page - 1));
		$last_url = $page == $total_page ? 'javascript:void;' : ($url . $total_page);
		$first_url = $page == 1 ? 'javascript:void;' : ($url . 1);
		$info = <<<HTML
			<div id="turn-page">
        总计  <span id="totalRecords">{$total}</span>
        个记录分为 <span id="totalPages">{$total_page}</span>
        页当前第 <span id="pageCurrent">{$page}</span>
        页，每页 <input type='text' size='3' id='pageSize' value="{$pagesize}" onblur="window.location.href='$url'+'1&pagesize='+this.value" />
        <span id="page-link">
          <a href="{$first_url}">第一页</a>
          <a href="{$prev_url}">上一页</a>
          <a href="{$next_url}">下一页</a>
          <a href="{$last_url}">最末页</a>
          <select id="gotoPage" onchange="window.location.href='$url'+this.value">        
HTML;
		$option='';
		for($i=1;$i<=$total_page;$i++)
		{
			$selected=$i==$page?'selected':'';
			$option.="<option {$selected} value='{$i}'>{$i}</option>";
		}
		return $info.$option.' </select></span></div>';
	}

}
