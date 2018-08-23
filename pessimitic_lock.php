<?php
//简单字符串悲观锁
//解释：悲观锁(Pessimistic Lock), 顾名思义，就是很悲观。
//每次去拿数据的时候都认为别人会修改，所以每次在拿数据的时候都会上锁。
//场景：如果项目中使用了缓存且对缓存设置了超时时间。
//当并发量比较大的时候，如果没有锁机制，那么缓存过期的瞬间，
//大量并发请求会穿透缓存直接查询数据库，造成雪崩效应。

/**
 * 获取锁
 * @param  String  $key    锁标识
 * @param  Int     $expire 锁过期时间
 * @return Boolean
 */
public function lock($key = '', $expire = 5) {
    $is_lock = $this->_redis->setnx($key, time()+$expire);
    //不能获取锁
    if(!$is_lock){
        //判断锁是否过期
        $lock_time = $this->_redis->get($key);
        //锁已过期，删除锁，重新获取
        if (time() > $lock_time) {
            unlock($key);
            $is_lock = $this->_redis->setnx($key, time() + $expire);
        }
    }
 
    return $is_lock? true : false;
}
 
/**
 * 释放锁
 * @param  String  $key 锁标识
 * @return Boolean
 */
public function unlock($key = ''){
    return $this->_redis->del($key);
}
 
// 定义锁标识
$key = 'Test_bihu_lock';
 
// 获取锁
$is_lock = lock($key, 10);
if ($is_lock) {
    echo 'get lock success<br>';
    echo 'do sth..<br>';
    sleep(5);
    echo 'success<br>';
    unlock($key);
} else { //获取锁失败
    echo 'request too frequently<br>';
}

