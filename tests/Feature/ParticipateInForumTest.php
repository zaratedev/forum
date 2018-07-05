<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function an_authenticated_user_may_participate_in_forum_threads()
    {
        // Given we have a authenticate user
        $this->singIn();
        // And an existing thread
        $thread = create('App\Thread');
        // When the user adds a reply to the thread
        $reply = make('App\Reply');

        $this->post($thread->path().'/replies', $reply->toArray());
        // Then their reply should be visible on the page
        $this->get($thread->path())
            ->assertSee($reply->body);
    }
}
