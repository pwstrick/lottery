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
$json = file_get_contents('https://www.v2ex.com/api/replies/show.json?topic_id=');
$json = json_decode($json, true);
$arr = [];
$key = 'member_id';
$end = mktime(23, 59, 59, 10, 6, 2018);	//截至日期 2018.10.6 23:59:59					
//去掉重复或截至日期后的回帖
foreach($json as $k => $v) {
    if (in_array($v[$key], $arr) || $v['created'] > $end) {
        unset($json[$k]);
    } else {
        $arr[] = $v[$key];
    }
}
print_r($arr);
$json = array_values($json);	//将索引连续
$indexs = [];					//中奖索引
$length = count($json);			//有效的参加人数
$scope = [ 0.01, 0.2, 0.4, 0.6, 0.8, 0.99];	//楼层概率
foreach($scope as $k => $v) {
    $indexs[] = round($length * $v - 1);	//四舍五入
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

