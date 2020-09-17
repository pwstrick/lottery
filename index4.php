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
$json = file_get_contents('https://www.v2ex.com/api/replies/show.json?topic_id=499699');
$json = json_decode($json, true);
$arr = [];
$key = 'member_id';
$end = mktime(23, 59, 59, 10, 25, 2018);	//截至日期 2018.9.29 23:59:59
$winnerCount = 6;						//获奖数量
//去掉重复的或截至日期后的回帖
foreach($json as $k => $v) {
    if (in_array($v[$key], $arr) || $v['created'] > $end) {
        unset($json[$k]);
    } else {
        $arr[] = $v[$key];
    }
}
//将索引连续
$json = array_values($json);
$ids = [];	//中奖索引
$length = count($json) - 1;
$scope = [
    [0, 93],
    [94, 187],
    [188, 281],
    [282, 375],
    [376, 469],
    [470, $length]
];
while (true) {
    $current = count($ids);
    $index = mt_rand($scope[$current][0], $scope[$current][1]);	//随机获取索引
    if(!in_array($index, $ids)) {	//判断是否已在中奖索引中
        $ids[] = $index;
    }
    if(count($ids) == $winnerCount) {
        break;
    }
}
$peoples = [];
$names = [];
//获取用户具体信息
foreach($ids as $k => $v) {
    $peoples[] = $json[$v];
    $names[] = $json[$v]['member']['username'];
}
file_put_contents('index1_index.text', json_encode($ids));	//保存中奖索引
file_put_contents('index1_data.text', json_encode($peoples));//保存中奖人信息
$winner = implode('，', $names);
//弹出获奖名单 恭喜：linghutf，setsena，kissnicky，zhuawadao，Genezzzzzz，hws8033856
echo "<script> alert('恭喜：{$winner}') </script>";
print_r($ids);
//$file = json_decode(file_get_contents('index1_data.text'));
//print_r($file);
?>

