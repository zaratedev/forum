<?php

namespace App;

use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['IsSubscribedTo'];

    public static function boot()
    {
      parent::boot();

      static::addGlobalScope('replyCount', function($builder) {
        $builder->withCount('replies');
      });

      static::deleting(function ($thread) {
        $thread->replies->each(function($reply) {
           $reply->delete();
         });
      });
    }

    public function path() {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies() {
        return $this->hasMany(Reply::class);
            //->withCount('favorites')
            //->with('owner');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addReply($reply) {
      $reply = $this->replies()->create($reply);

      event(new ThreadHasNewReply($this, $reply));
      // prepare notifications for all subscribers
     /* $this->subscriptions
          ->where('user_id', '!=' , $reply->user_id)
          ->each
          ->notify($reply);*/

      return $reply;
    }

    public function channel() {
        return $this->belongsTo(Channel::class);
    }

    public function scopeFilter($query, $filters) {
        return $filters->apply($query);
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()->where('user_id', $userId ?: auth()->id())->delete();
    }
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }
}
