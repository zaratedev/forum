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
        $thread = make('App\Thread');
        $this->post('/threads', $thread->toArray());
        // Then, when we visit the thread page
        $this->get('/threads/'. $thread->id)
            ->assertSee($thread->title)
            ->assertSee($thread->body);
    }
}
