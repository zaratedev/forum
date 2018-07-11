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

    /** @test */
    function a_reply_requires_a_body()
    {
        $this->singIn();
        $thread = create('App\Thread');
        $reply = make('App\Reply', ['body' => null]);

        $this->post($thread->path().'/replies', $reply->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function unauthorized_users_cannot_delete_a_reply()
    {

      $this->withExceptionHandling();

      $reply = create('App\Reply');

      $this->delete("/replies/{$reply->id}")
        ->assertRedirect('login');

      $this->singIn()
        ->delete("/replies/{$reply->id}")
        ->assertStatus(403);
    }

    /** @test */
    function unauthorized_users_can_delete_a_reply()
    {

      $this->singIn();

      $reply = create('App\Reply', ['user_id' => auth()->id()]);

      $this->delete("/replies/{$reply->id}");

      $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    }
}
