<?php
header("Content-Type:text/html;charset=utf-8");
$json = [];
$page = 3; //TODO
$winnerCount = 4;//TODO
for($i=1; $i<=$page; $i++) {
    $file = file_get_contents("http://bbs.xiuno.com/thread-2-{$i}.htm?ajax=1");
    $file = explode('HTTP/1.1', $file)[0];  //TODO
    $file = json_decode($file, true);
    $json = array_merge($file['message']['postlist'], $json);
}
$arr = [];
$key = 'uid';
$end = mktime(22, 0, 0, 1, 22, 2018);
foreach($json as $k => $v) {
    if (in_array($v[$key], $arr) || $v['create_date'] > $end) {
        unset($json[$k]);
    } else {
        $arr[] = $v[$key];
    }
}
$json = array_values($json);
//print_r($json);
$ids = [];
$length = count($json) - 1;
while (true) {
    $index = mt_rand(0, $length);
    if(!in_array($index, $ids)) {
        $ids[] = $index;
    }
    if(count($ids) == $winnerCount) {
        break;
    }
}
$peoples = [];
$names = [];
foreach($ids as $k => $v) {
    $peoples[] = $json[$v];
    $names[] = $json[$v]['username'];
}
file_put_contents('index2_index.text', json_encode($ids));
file_put_contents('index2_data.text', json_encode($peoples));
$winner = implode('，', $names);
//print_r($names);
echo "<script> alert('恭喜：{$winner}') </script>";
print_r($ids);
//$file = json_decode(file_get_contents('index2_data.text'));
//print_r($file);


