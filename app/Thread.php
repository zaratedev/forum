<?php

namespace App;

use App\Inspections\Spam;
use App\Events\ThreadHasNewReply;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\YourWereMentioned;

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

      static::created(function ($thread) {
        $thread->update(['slug' => $thread->title]);
        });
    }

    public function path() {
        return "/threads/{$this->channel->slug}/{$this->slug}";
    }

    public function replies() {
        return $this->hasMany(Reply::class);
            //->withCount('favorites')
            //->with('owner');
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $reply
     * @return Model
     */
    public function addReply($reply)
    {
      $reply = $this->replies()->create($reply);

      //event(new ThreadHasNewReply($this, $reply));
      // prepare notifications for all subscribers
      $this->subscriptions
          ->where('user_id', '!=' , $reply->user_id)
          ->each
          ->notify($reply);

        preg_match_all('/@([\w\-]+)/', $reply->body, $matches);

        foreach ($matches[1] as $name) {
            $user = User::whereName($name)->first();
            if ($user) {
                $user->notify(new YourWereMentioned($reply));
            }
        }

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

    public function hasUpdatesFor($user)
    {
      $key = $user->visitedThreadCacheKey($this);

      return $this->updated_at > cache($key);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function setSlugAttribute($value)
    {
        if (static::whereSlug($slug = str_slug($value))->exists()) {
            $slug = "{$slug}-{$this->id}";
        }
        $this->attributes['slug'] = $slug;
    }
}
