<?php

/**
 * Class Bitmap
 * 只能存放 0 和正整数
 */
class Bitmap
{
    /**
     * @var int[]
     */
    private $list = [];

    /**
     * Bitmap constructor.
     * @param int $max [0, $max] 之间的值都可以存放(闭区间)
     */
    public function __construct(int $max)
    {
        $count = ceil(($max + 1) / 64);
        for ($i = 0; $i < $count; $i++) {
            $this->list[] = 0;
        }
    }

    public function setNums(array $nums)
    {
        foreach ($nums as $num) {
            $this->setNum($num);
        }
    }

    public function unsetNums(array $nums)
    {
        foreach ($nums as $num) {
            $this->unsetNum($num);
        }
    }

    public function setNum(int $num)
    {
        $shardNum = (int)($num / 64);
        $offset = $num % 64;
        $this->list[$shardNum] = $this->list[$shardNum] | (1 << $offset);
    }

    public function unsetNum(int $num)
    {
        $shardNum = (int)($num / 64);
        $offset = $num % 64;
        $this->list[$shardNum] = $this->list[$shardNum] & ~(1 << $offset);
    }

    /**
     * 顺序返回
     * @return Generator
     */
    public function getValues()
    {
        foreach ($this->list as $shardNum => $shard) {
            if ($shard === 0) {
                continue;
            }
            for ($i = 0; $i < 64; $i++) {
                if ($shard & (1 << $i)) {
                    yield $shardNum * 64 + $i;
                }
            }
        }
    }

    public function isSet(int $num)
    {
        $shardNum = (int)($num / 64);
        $offset = $num % 64;
        return (bool)$this->list[$shardNum] & (1 << $offset);
    }
}
