<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class FavoritesReplyTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function guests_can_not_favorite_anything() {
        $this->post('/replies/1/favorites')
            ->assertRedirect('/login');
    }


    /** @test */
    function at_authenticate_user_can_favorite_any_reply()
    {
        $this->singIn();

        $reply = create('App\Reply');

        $this->post('/replies/'. $reply->id .'/favorites');

        $this->assertCount(1, $reply->favorites);
    }

    /** @test */
    function an_authenticated_user_may_only_favorite_a_reply_once()
    {
        $this->singIn();

        $reply = create('App\Reply');
        try {
            $this->post('/replies/'. $reply->id .'/favorites');
            $this->post('/replies/'. $reply->id .'/favorites');
        } catch (\Exception $e) {
            $this->fail('Did not except to insert the same record set twice');
        }
        $this->assertCount(1, $reply->favorites);
    }
}
