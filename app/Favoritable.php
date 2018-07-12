<?php

namespace App;

trait Favoritable {

  public static function bootFavoritable()
  {
    static::deleting( function($model) {
      $model->favorites->each(function($favorite) {
        $favorite->delete();
      });
    });
  }

  public function favorites() {
      return $this->morphMany(Favorite::class, 'favorited');
  }

  public function favorite() {
      if (! $this->favorites()->where(['user_id' => auth()->id()])->exists() )
          return $this->favorites()->create(['user_id' => auth()->id()]);
  }

  public function unfavorite()
  {
    $attributes = ['user_id' => auth()->id()];

    $this->favorites()->where($attributes)->get()->each(function($favorite) {
      $favorite->delete();
    });
  }

  public function isFavorited() {
      return !! $this->favorites->where('user_id', auth()->id())->count();
  }

  public function getIsFavoritedAttribute()
  {
    return $this->isFavorited();
  }

  public function getFavoritesCountAttribute()
  {
    return $this->favorites->count();
  }
}
