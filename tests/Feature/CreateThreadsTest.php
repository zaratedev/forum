<?php

namespace Tests\Feature;

use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function a_user_can_create_new_forum_thread() {
        $user = create('App\User', ['confirmed' => true]);
        $this->singIn($user);

        $thread = make('App\Thread');
        $response = $this->post('/threads', $thread->toArray());

        $this->get($response->headers->get('Location'))
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
        $user = make('App\User', ['confirmed' => true]);

        $this->singIn($user);
        $thread = make('App\Thread', ['title' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('title');
    }

    /** @test */
    function a_thread_requires_a_body()
    {
        $user = make('App\User', ['confirmed' => true]);

        $this->singIn($user);
        $thread = make('App\Thread', ['body' => null]);

        $this->post('/threads', $thread->toArray())
            ->assertSessionHasErrors('body');
    }

    /** @test */
    function a_thread_requires_a_channel()
    {
        $user = make('App\User', ['confirmed' => true]);

        $this->singIn($user);
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
    function authenticated_user_must_first_confirm_their_email_address_before_creating_threads()
    {
        $this->publishThread()->assertRedirect('/threads')->assertSessionHas('flash', 'You must first confirm your email address');
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

    function publishThread()
    {
        $this->withExceptionHandling()->singIn();

        $thread = make('App\Thread');

        return $this->post('/threads', $thread->toArray());
    }

    /** @test */
    function a_thread_requires_a_unique_slug()
    {
        $user = create('App\User', ['confirmed' => true]);
        $this->singIn($user);

        $thread = create('App\Thread', ['title' => 'Foo Title', 'slug' => 'foo-title']);
        $this->assertEquals($thread->fresh()->slug, 'foo-title');

        $this->post(route('threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-2')->exists());

        $this->post(route('threads'), $thread->toArray());
        $this->assertTrue(Thread::whereSlug('foo-title-3')->exists());
    }
}
