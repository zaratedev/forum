<?php 

namespace App;

use Illuminate\Support\Facades\Redis;

class Thrending
{
	public function get()
	{
		return array_map('json_decode', Redis::zrevrange($this->cacheKey(), 0, 4));
	}

	public function push($thread)
	{
		Redis::zincrby($this->cacheKey(), 1, json_encode([
            'title' => $thread->title,
            'path' => $thread->path()
        ]));
	}

	protected function cacheKey()
	{
		return 'trending_threads';
	}

	public function reset()
	{
		Redis::del($this->cacheKey());
	}
}