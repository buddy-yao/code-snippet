<?php

/**
 * 函数或方法的重试调用，要求被调用函数或方法在失败时会抛出异常
 * @param mixed $func 被调用的函数或方法 http://php.net/manual/zh/function.call-user-func.php
 * @param int $retry_interval 重试间隔, 单位秒, 默认为 0 秒
 * @param int $retry_count 重试总次数 默认为 3, 表示 $func 最多会被执行 3 次
 * @param mixed ...$args 被调用函数或方法的参数列表
 * @return mixed
 * @throws \Exception
 */
function retry(callable $func, int $retry_interval = 0, int $retry_count = 3, ...$args)
{
    try {
        return call_user_func($func, ...$args);
    } catch (\Exception $e) {
        if ($retry_count > 1) {
            sleep($retry_interval);
            return retry($func, $retry_interval, $retry_count - 1, ...$args);
        } else {
            throw $e;
        }
    }
}
