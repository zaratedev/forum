<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class SubscribeToThreadsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function a_user_can_subscribe_ti_threads()
    {
        $this->singIn();

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertCount(1, $thread->fresh()->subscriptions);

//        $thread->addReply([
//            'user_id' => auth()->id(),
//            'body' => 'Some reply here'
//        ]);
//
//        $this->assertCount(1, auth()->user()->notifications);
    }

    /** @test */
    function it_knows_if_the_authenticate_user_is_subscribed_to_it()
    {
        $this->singIn();

        $thread = create('App\Thread');

        $this->post($thread->path() . '/subscriptions');

        $this->assertTrue($thread->isSubscribedTo);
    }

    /** @test */
    function a_user_can_unsubscribe_from_ti_threads()
    {
        $this->singIn();
        $thread = create('App\Thread');
        $thread->subscribe();

        $this->delete($thread->path() . '/subscriptions');
        $this->assertCount(0, $thread->subscriptions);

    }
}