<!DOCTYPE html>
<html>
<head>
    <title>抽取幸运网友</title>
    <meta charset="utf-8"/>
</head>
<body style="text-align: center">
    <form method="post">
        <button type="submit" style="margin: 300px 0">抽取幸运网友</button>
    </form>
</body>
</html>
<?php
if($_SERVER['REQUEST_METHOD'] == 'GET') {
    exit;
}
header("Content-Type:text/html;charset=utf-8");
$json = file_get_contents('https://www.v2ex.com/api/replies/show.json?topic_id=513679');	//可配置
$json = json_decode($json, true);
$arr = [];
$key = 'member_id';
$end = mktime(23, 59, 59, 1, 9, 2019);	//截至日期 2019.1.9 23:59:59（可配置）			
//去掉重复、截至日期后的回帖和作者发的回复
foreach($json as $k => $v) {
    if (in_array($v[$key], $arr) || $v['created'] > $end || $v['member']['username'] == 'pwstrick') {
        unset($json[$k]);
    } else {
        $arr[] = $v[$key];
    }
}
$json = array_values($json);	//将索引连续
print_r($json);
print_r(count($json));
$indexs = [];					//中奖索引
$length = count($json);			//有效的参加人数
$scope = [ 0.01, 0.2, 0.4, 0.6, 0.8, 0.99];	//楼层概率
foreach($scope as $k => $v) {
	$index = round($length * $v);
	if($index >= 1)
		$index -= 1;
    $indexs[] = $index;	//四舍五入
}
$peoples = [];
$names = [];
//获取用户具体信息
foreach($indexs as $k => $v) {
    $peoples[] = $json[$v];
    $names[] = $json[$v]['member']['username'];
}
$winner = implode('，', $names);
//弹出获奖名单
echo "<script> alert('恭喜：{$winner}') </script>";
?>

