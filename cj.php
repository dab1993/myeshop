<?php

/**
 * 
 * 采集绝想日记网文章
 * @author 秦仙游 <dab1117@163.com>
 * @version 1.0
 * @package suibiwu.com
 */
/* 处理页面最大请求时间为无限 */
set_time_limit(0);
header('Content-Type:text/html;charset=utf8');
require_once("simple_html_dom.php");
require_once("./framework/tool/MySQLDBTool.class.php");
require_once("./framework/tool/ImageTool.class.php");

/* key为需要采集的id号 */
$arr = array();

$arr['24'] = 10196; //感悟 - 感悟 
$arr['21'] = 10197; //闲逸 - 闲逸
$GLOBALS['prefix'] = 'http://www.juexiang.com';

if (count($arr) > 0) {
    foreach ($arr as $key => $value) {
        echo "<h2 style='color:red;'>{$key}分类</h2>";

        $typeid = $key;
        /* 副栏目号 */
        $typeid2 = '';
        $base_url = 'http://www.juexiang.com/list/' . $value;
        for ($i = 6; $i <= 8; $i++) {
            $list_url = $base_url . '?p=' . $i;
            $items = getArcticleList($list_url);
            echo "<h3>第{$i}页</h3>";
            foreach ($items as $k => $v) {
                $id = $v['id'];
                console("获取到id号：$id");
                myFlush();
                sleep(1);
                $obj = getArticle('detail/' . $id);
                console("获取到文档信息：$id");
                $obj['typeid'] = $typeid;
                $obj['typeid2'] = $typeid2;
                sleep(0);
                $info = htmlToText(postToDede($obj)) . "\r\n";
                console("执行结果：$info");
                echo '<hr/>';
                myFlush();
            }
            /* 防盗链设置，虽然可能没有效果 */
            sleep(3);
        }
    }
}

/**
 * 格式化输出
 * @param type $msg
 */
function console($msg) {

    echo "<p style='line-height:20px; font-size:12px; line-height:20px;'>{$msg}</p>";
}

/* 刷新缓存 */

function myFlush() {
    ob_flush();
    flush();
}

/**
 * 获取文章详情
 * @param type $id
 * @return type
 */
function getArticle($id) {

    $obj = pickOne($id);
    $body_without_html = htmlToText($obj['body']);
    $obj['description'] = mb_substr($body_without_html, '0', 150, 'utf8');


    $obj['source'] = '绝想日记网';
    /* 设置文章属性 */
    if (!empty($obj['litpic'])) {
        $obj['flag'] = ',f,p';
    }
    if (rand(1, 3) == 2)
        $obj['flag'] = ',c';
    if (rand(1, 3) == 2)
        $obj['flag'] = ',h';
    if (rand(1, 3) == 2)
        $obj['flag'] = ',s';

    $obj['flag'] = ltrim($flag['flag'], ',');
    $obj['flag'] = explode(',', $flag);
    $obj['keywords'] = '随笔坞';
    $obj['qianbian'] = rand(0, 20);
    $obj['zhichi'] = rand(0, 300);
    $obj['zhaoma'] = rand(0, 70);
    $obj['gaoxiao'] = rand(0, 80);
    $obj['chedan'] = rand(0, 100);
    $obj['bujie'] = rand(0, 200);
    $obj['chijing'] = rand(0, 50);
    $obj['henbang'] = rand(0, 200);
    return $obj;
}

//var_dump($obj);
//echo saveToDatabase($obj) . "\r\n";

function postToDede($obj) {

    /* 构造表单数据 */
    $data = array(
        'channelid' => '1',
        'dopost' => 'save',
        'title' => $obj['title'],
        'shorttitle' => '',
        'redirecturl' => '',
        'tags' => '',
        'weight' => '1',
        'picname' => '',
        'litpic' => '',
        'source' => $obj['source'],
        'writer' => $obj['writer'],
        'typeid' => $obj['typeid'],
        'typeid2' => $obj['typeid2'],
        'keywords' => '',
        'autokey' => '1',
        'desciption' => '',
        'qianbian' => $obj['qianbian'],
        'zhichi' => $obj['zhichi'],
        'zhaoma' => $obj['zhaoma'],
        'gaoxiao' => $obj['gaoxiao'],
        'chedan' => $obj['chedan'],
        'bujie' => $obj['bujie'],
        'chijing' => $obj['chijing'],
        'henbang' => $obj['henbang'],
        'music' => $obj['music'],
        'dede_addonfields' => 'qianbian,int;zhichi,int;zhaoma,int;gaoxiao,int;chedan,int;bujie,int;chijing,int;henbang,int;music,text',
        'remote' => '1',
        'dellink' => '1',
        'autolitpic' => '1',
        'needwatermark' => '1',
        'sptype' => 'hand',
        'spsize' => 'voteid',
        'body' => $obj['body'],
        'voteid' => '',
        'notpost' => '0',
        'click' => $obj['views'],
        'sortup' => '0',
        'color' => '',
        'arcrank' => '',
        'money' => '0',
        'pubdate' => date('Y-m-d H:i:s', $obj['senddate']),
        'ishtml' => '0',
        'filename' => '',
        'templet' => '',
        'imageField.x' => '24',
        'imageField.x' => '14'
    );
    if (isset($obj['flag'])) {
        $index = 0;
        foreach ($obj['flag'] as $key => $value) {
            $data['flag[' . $index . ']'] = $value;
            $index++;
        }
    }
    $url = 'http://sx.cc/administrator/article_add.php';
    $pro = curl_init();
    curl_setopt($pro, CURLOPT_URL, $url);
    curl_setopt($pro, CURLOPT_POST, true);
    curl_setopt($pro, CURLOPT_RETURNTRANSFER, 1);
    /* cookie字符串，需替换成自己的 */
    curl_setopt($pro, CURLOPT_COOKIE, 'menuitems=1_1%2C2_1%2C3_1%2C4_1; Hm_lvt_86f43783acc56b0c8abb5bb039edc763=1447468152; lastCid=12; lastCid__ckMd5=32334e2dceed96e5; PHPSESSID=3i9hfpit8h8gvoho4mbptou1h7; DedeUserID=1; DedeUserID__ckMd5=21656f81551e2194; DedeLoginTime=1447951987; DedeLoginTime__ckMd5=e8b68eb16c46c0a4; dede_vote_2365=1; ENV_GOBACK_URL=%2Fadministrator%2Fcontent_list.php%3Fchannelid%3D1');
    curl_setopt($pro, CURLOPT_REFERER, 'http://sx.cc/administrator/article_add.php?channelid=1&cid=0');
    curl_setopt($pro, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
    curl_setopt($pro, CURLOPT_POSTFIELDS, $data);

    $result = curl_exec($pro);
    unset($pro);
    return $result;
}

/**
 * 保存数据库到数据库 不可用，最好的方式还是提交到dede
 * @param array $obj 文章对象
 */
function saveToDatabase($obj) {
    $option = array(
        'host' => 'localhost',
        'port' => '3306',
        'user' => 'root',
        'password' => 'sa123',
        'charset' => 'utf8',
        'database' => 'dedecmsv57utf8sp1',
        'prefix' => 'dede_'
    );
    $db = MySQLDBTool::getInstance($option);
    $sql = "select count(*) from dede_archives where title='{$obj['title']}'";
    $count = $db->executeScalar($sql);
    if ($count > 0)
        return false;
    $sql1 = "INSERT INTO dede_arctiny(typeid,typeid2,arcrank,channel,senddate,sortrank,`mid`) VALUES ('{$obj["typeid"]}','{$obj["typeid2"]}',0,1,'{$obj["senddate"]}','{$obj["sortrank"]}',1)";
    $id = $db->last_insert_id($sql1);
    if ($id > 0) {
        $sql2 = "INSERT INTO dede_archives(id,typeid,typeid2,sortrank,flag,ismake,channel,arcrank,click,money,title,shorttitle,color,writer,source,litpic,"
                . "pubdate,senddate,`mid`,keywords,lastpost,scores,goodpost,badpost,voteid,notpost,description,filename,dutyadmin,tackid,mtype,weight) VALUES"
                . "({$id},'{$obj['typeid']}','{$obj['typeid2']}','{$obj['sortrank']}','{$obj['flag']}','-1','1','0','{$obj['views']}','0','{$obj['title']}','','','{$obj['writer']}',"
                . "'{$obj['source']}','{$obj['litpic']}','{$obj['pubdate']}','{$obj['pubdate']}','1','{$obj['keywords']}','0','0','0','0','0','0','{$obj['description']}','','1','0','0','0')";
        $sql2_r = $db->exec($sql2);

        $sql3 = "INSERT INTO dede_addonarticle(aid,typeid,body,redirecturl,templet,userip,qianbian,zhichi,zhaoma,gaoxiao,chedan,bujie,chijing,henbang,music) VALUES "
                . "({$id},{$obj['typeid']},'{$obj['body']}','','','127.0.0.1','{$obj['qianbian']}','{$obj['zhichi']}','{$obj['zhaoma']}','{$obj['gaoxiao']}','{$obj['chedan']}','{$obj['bujie']}','{$obj['chijing']}','{$obj['henbang']}','{$obj['music']}')";
        $sql3_r = $db->exec($sql3);
        if ($sql3_r < 0 or $sql2_r < 0) {
            $db->exec('delete from dede_archives where id=' . $id);
            $db->exec('delete from dede_addonarticle where aid=' . $id);
            $db->exec('delete from dede_arctiny where id=' . $id);
            return false;
        }
        return true;
    } else {
        return false;
    }
}

/**
 * 取得没有html文档的字符串
 * @param type $html_str
 * @return type
 */
function htmlToText($html_str) {
    return preg_replace('/\s+/', '', preg_replace('/&nbsp;/s', '', preg_replace('/<.*>/sU', '', $html_str)));
}

/**
 *  dede中的sortrank计算方法增加天数 既然是提交，就不需要这个方法了
 *
 * @param     int  $ntime  当前时间
 * @param     int  $aday   增加天数
 * @return    int 计算后的时间
 */
function AddDay($ntime, $aday) {
    $dayst = 3600 * 24;
    $oktime = $ntime + ($aday * $dayst);
    return $oktime;
}

/**
 * 获取单个列表页全部的文章列表
 * @param int $url 列表页链接地址
 */
function getArcticleList($url) {
    $dom = new simple_html_dom;
    $dom->load_file($url);
    $i = 0;

    $items = $dom->find('.left .item .arttitle');
    foreach ($items as $k => $v) {
        $res[$i]['href'] = $GLOBALS['prefix'] . $v->children[0]->attr['href'];
        $res[$i]['title'] = $v->children[0]->innertext;
        preg_match('/(\d+)\.html/isU', $res[$i]['href'], $temp_1);
        $res[$i]['id'] = $temp_1[1];
        $i++;
    }

    /* 释放资源 */
    $dom->clear();
    unset($dom);
    return $res;
}

/**
 * 获取一篇文章信息绝想
 * @param int $id 日记id
 */
function pickOne($id) {
    $url = $GLOBALS['prefix'] . "/{$id}.html";
    echo $url;
    $dom = new simple_html_dom;
    $dom->load_file($url);

    $postHeader_title = $dom->find('.left h1[0]');
    $pubtime = $dom->find('.pubtime');
    $heart = $dom->find('.week a');
    $info = $dom->find('.author a');
    $content = $dom->find('.content');
    $views = $dom->find('.views b');

    $writer = $info[0]->innertext;
    $title = $postHeader_title[0]->innertext;
    $senddate = $pubdate = strtotime($pubtime[0]->innertext);
    $body = $content[0]->innertext;
    $views = $views[0]->innertext;

    /* 释放资源 */
    $dom->clear();
    unset($dom);
    $mp3 = preg_match_all('/\?mp3=(.+\.mp3).*autostart/Us', $body, $res);

    if ($mp3 and isset($res) and count($res) > 0) {
        $music = $res[1][0];
    }

    /* 去除超链接和开头的空白段落 */
    $body = preg_replace('/<a.*>(.*)<\/a>/isU', '$1', preg_replace('/<div.*>(.*)<\/div>/isU', '', preg_replace('/^<p>\s*<\/p>/', '', preg_replace('/\s*<style.+<\/style>/s', '', $body))));

    /* 本地化图片 */
    saveImages($body, $res_img);
    $obj = array('title' => $title, 'writer' => $writer, 'body' => $body, 'pubdate' => $pubdate, 'senddate' => $senddate, 'views' => $views);
    $obj['sortrank'] = AddDay($senddate, 0);
    /* 处理背景音乐 */
    $obj['music'] = isset($music) ? $music : '';
    return $obj;
}

/**
 * 生成缩略图并打水印
 * @param string $file 文件全名
 * @return string 缩略图名字
 */
function makeThumb($file) {
    $image_tool = new ImageTool($file);
    $new_name = $image_tool->makeThumb(300, 300);
    $image_tool->waterMark('./upload/cklogo.png');
    return $new_name;
}

/**
 * 本地化远程图片并返回图片列表
 * @param string $html_str 要存储图片的路径
 * @param string $predix 要查找图片的前缀
 */
function saveImages(& $html_str, & $result) {
    $reg = '/<img.+src\s*=\s*[\'"]\s*(.+\.(\w{3,5}))\s*[\'"].*>/iU';
//    $html_str = preg_replace_callback($reg, 'savePic', $html_str);
    $html_str = preg_replace_callback($reg, 'noHttp', $html_str);
    preg_match_all($reg, $html_str, $result);
}

/* 判断图片是否是http绝对路径开头 */

function noHttp($matches) {
    $image_url = $matches[1];
    if (strpos($image_url, '/') === false) {
        $image_url = $GLOBALS['prefix'] . $image_url;
    }
    return '<center><img src="' . $image_url . '" style="max-width:680px;"/></center>';
}

/**
 * 保存图片到本地
 * @param type $matches
 * @return type
 */
function savePic($matches) {
    $error_img = '/upload/yimo.png';
    $filename = uniqid() . '.' . $matches[2];
    if (!downloadPicture($matches[1], './uploads/cj/', $filename)) {
        $src = $error_img;
    } else {
        $src = 'http://eshop.cc/uploads/cj/' . $filename;
    }
    return "<img style=\"max-width:500px\" src=\"$src\"/>";
}

/**
 * 下载远程图片
 * @param string $image_url 图片保存地址
 * @param string $save_path 图片保存路径
 * @param string $filename 图片文件名
 * @return string 成功返回文件字节数，失败返回false
 */
function downloadPicture($image_url, $save_path, $filename = 'rand') {

    $image_url = (!strpos($image_url, 'http://')) ? $GLOBALS['prefix'] . $image_url : $image_url;

    if (substr($save_path, -1) == '/' || substr($save_path, -1) == '\\') {
        $save_path = rtrim($save_path, '/');
        $save_path = rtrim($save_path, '\\');
    }

    if (!is_dir($save_path)) {
        if (!mkdir($save_path, 0, true))
            die('创建目录失败');
    }
    if ($filename == 'rand') {
        $filename = uniqid();
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $image_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_COOKIE, 'juexiangssid=acnjkmock336p1ofl7sbgf2p97');
    curl_setopt($ch, CURLOPT_REFERER, 'http://www.juexiang.com/');
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
    $img = curl_exec($ch);
    curl_close($ch);
    unset($ch);
    return file_put_contents($save_path . DIRECTORY_SEPARATOR . $filename, $img);
}
