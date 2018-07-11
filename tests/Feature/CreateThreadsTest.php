<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function an_authenticated_user_can_create_new_forum_thread() {
        // signed in user
        $this->singIn();
        // When we hit the endpoint to create a new thread
        $thread = create('App\Thread');
        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page
        $this->get($thread->path())
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }

    /** @test */
    function guests_cannot_see_the_create_thread_page() {
        $this->get('/threads/create')
            ->assertRedirect('/login');
    }

    /** @test */
    function a_thread_requires_a_title()
    {
        $this->singIn();
        $thread = make('App\Thread', ['title' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $this->singIn();
        $thread = make('App\Thread', ['body' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_channel()
    {
        $this->singIn();
        $thread = make('App\Thread', ['channel_id' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('channel_id');
    }

    /** @test */
    function guests_cannot_delete_threads()
    {
        $thread = create('App\Thread');
        $response = $this->delete($thread->path());
        $response->assertRedirect('/login');

    }

    /** @test */
    function authorized_users_can_delete_threads()
    {
        $this->singIn();

        $thread = create('App\Thread', ['user_id' => auth()->id()]);
        $reply = create('App\Reply', ['thread_id' => $thread->id]);

        $response = $this->json('DELETE', $thread->path() );
        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
        $this->assertDatabaseMissing('replies', ['id' => $reply->id]);

        $this->assertDatabaseMissing('activities', [
          'subject_id' => $thread->id,
          'subject_type' => get_class($thread)
        ]);

        $this->assertDatabaseMissing('activities', [
          'subject_id' => $reply->id,
          'subject_type' => get_class($reply)
        ]);
    }

    /** @test */
    function unauthorized_users_may_not_delete_threads()
    {
        $this->withExceptionHandling();

        $thread = create('App\Thread');

        $this->delete($thread->path())->assertRedirect('/login');

        $this->singIn();

        $this->delete($thread->path())->assertStatus(403);
    }
}
