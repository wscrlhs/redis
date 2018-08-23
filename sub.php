<?php
//简单发布订阅
//以下是 sub.php 文件内容 cli下运行
//ini_set('default_socket_timeout', -1);
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$strChannel = 'Test_bihu_channel';

//订阅
echo "---- 订阅{$strChannel}这个频道，等待消息推送...----  <br/><br/>";
$redis->subscribe([$strChannel], 'callBackFun');
function callBackFun($redis, $channel, $msg)
{
    print_r([
        'redis'   => $redis,
        'channel' => $channel,
        'msg'     => $msg
    ]);
}

