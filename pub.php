<?php
//简单发布订阅
//ini_set('default_socket_timeout', -1);
$redis = new Redis();
//连接
$redis->connect('127.0.0.1', 6379);
$strChannel = 'Test_bihu_channel';

//发布
$redis->publish($strChannel, "来自{$strChannel}频道的推送");
echo "---- {$strChannel} ---- 频道消息推送成功～ <br/>";
$redis->close();

