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
$json = [];
$page = 3; //TODO
$winnerCount = 4;//TODO

function xml_to_json($source) {  
    if(is_file($source)){             //传的是文件，还是xml的string的判断  
        $xml_array=simplexml_load_file($source);  
    }else{  
        $xml_array=simplexml_load_string($source);  
    }  
    $json = json_encode($xml_array);  //php5，以及以上，如果是更早版本，請下載JSON.php  
    return $json;  
} 
//var_dump(strtotime('2015-07-01T10:02:23+08:00'));exit;


for($i=1; $i<=$page; $i++) {
    $file = file_get_contents("http://wcf.open.cnblogs.com/blog/post/9789330/comments/{$i}/50");
    //$file = explode('HTTP/1.1', $file)[0];  //TODO
    $file = xml_to_json($file);
	$file = json_decode($file, true);
    $json = array_merge($file['entry'], $json);
}
//print_r(count($json));exit();
$arr = [];
$key = 'name';
$end = mktime(23, 59, 59, 10, 18, 2018);//TODO
foreach($json as $k => $v) {
    if (in_array($v['author'][$key], $arr) || strtotime($v['published']) > $end) {
        unset($json[$k]);
    } else {
        $arr[] = $v['author'][$key];
    }
}
$json = array_values($json);
//print_r($json);exit();
//print_r(count($json));exit();
$ids = [];
$length = count($json) - 1;
//根据实际情况
$scope = [
    [0, 34],
	[35, 69],
    [70, 104],
    [105, $length]
];
while (true) {
	$current = count($ids);
	$index = mt_rand($scope[$current][0], $scope[$current][1]);	//随机获取索引
    //$index = mt_rand(0, $length);
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
    $names[] = $json[$v]['author']['name'];
}
file_put_contents('index3_index.text', json_encode($ids));
file_put_contents('index3_data.text', json_encode($peoples));
$winner = implode('，', $names);
//print_r($names);
echo "<script> alert('恭喜：{$winner}') </script>";
print_r($ids);
print_r($peoples);
//$file = json_decode(file_get_contents('index3_data.text'));
//print_r($file);

