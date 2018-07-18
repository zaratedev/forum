<?php

namespace App;

use Illuminate\Support\Facades\Redis;

trait RecordsVisits {
    public function recordVisit()
    {
        Redis::incr("threads.{$this->id}.visits");
        return $this;
    }

    public function visits()
    {
        return Redis::get("threads.{$this->id}.visits") ?? 0;
    }

    public function resetVisits()
    {

        Redis::del("threads.{$this->id}.visits");
    }
}