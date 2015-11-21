<?php

$url = 'http://eshop.cc/test1.php';

$data['flag[]']=1;
$data['flag[]']=1;
$data['flag[]']=1;

$pro = curl_init();
curl_setopt($pro, CURLOPT_URL, $url);
curl_setopt($pro, CURLOPT_POST, true);
//curl_setopt($pro, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($pro, CURLOPT_COOKIE, 'menuitems=1_1%2C2_1%2C3_1%2C4_1; Hm_lvt_86f43783acc56b0c8abb5bb039edc763=1447468152; lastCid=12; lastCid__ckMd5=32334e2dceed96e5; PHPSESSID=3i9hfpit8h8gvoho4mbptou1h7; DedeUserID=1; DedeUserID__ckMd5=21656f81551e2194; DedeLoginTime=1447951987; DedeLoginTime__ckMd5=e8b68eb16c46c0a4; dede_vote_2365=1; ENV_GOBACK_URL=%2Fadministrator%2Fcontent_list.php%3Fchannelid%3D1');
curl_setopt($pro, CURLOPT_REFERER, 'http://sx.cc/administrator/article_add.php?channelid=1&cid=0');
curl_setopt($pro, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
curl_setopt($pro, CURLOPT_POSTFIELDS, $data);

curl_exec($pro);

unset($pro);
