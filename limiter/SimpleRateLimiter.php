<?php

class SimpleRateLimiter
{
    /**
     * @var Redis
     */
    private $redis;

    public function __construct($redis)
    {
        $this->redis = $redis;
    }

    public function isActionAllowed($userId, $actionKey, $period, $maxCount)
    {
        $key     = "hits:" . $userId . ":" . $actionKey;
        $nowTime = time();
        $this->redis->multi();
        $this->redis->zAdd($key, $nowTime, $nowTime);
        $this->redis->zRemRangeByScore($key, 0, $nowTime - $period);
        $count = $this->redis->zCard($key);
        $this->redis->expire($key, $period + 1);
        $this->redis->exec();
        return $count <= $maxCount;
    }
}