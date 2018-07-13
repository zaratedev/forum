<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;


class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;
    public function setUp() {
        parent::setUp();
        $this->thread = factory('App\Thread')->create();
    }
    /** @test */
    public function a_user_can_view_all_threads()
    {
        $this->get('/threads')
            ->assertSee($this->thread->title);
    }

    /** @test */
    function a_user_ca_read_a_single_thread() {
        $this->get($this->thread->path())
            ->assertSee($this->thread->title);
    }

    /** @test */
    /*function a_user_can_read_replies_that_are_associated_whit_a_thread() {

        $reply = factory('App\Reply')->create(['thread_id' => $this->thread->id]);

        $this->get($this->thread->path())
            ->assertSee($reply->body);
    }*/

    /** @test */
    function a_user_can_filter_threads_according_to_channel()
    {
        $channel = create('App\Channel');
        $threadInChannel = create('App\Thread', ['channel_id' => $channel->id]);
        $threadNotChannel = create('App\Thread');

        $this->get('/threads/'. $channel->slug)
            ->assertSee($threadInChannel->title)
            ->assertDontSee($threadNotChannel->title);
    }

    /** @test */
    function a_user_can_filter_threads_by_username()
    {
        $this->singIn(create('App\User', ['name' => 'Jonathan']));
        $threadOne = create('App\Thread', ['user_id' => auth()->id()]);
        $threadTwo = create('App\Thread');

        $this->get('/threads?by=Jonathan')
            ->assertSee($threadOne->title)
            ->assertDontSee($threadTwo->title);
    }

    /** @test */
    function a_user_can_filter_thread_by_popular()
    {
      // Thread with 2 replies
      $threadOne = create('App\Thread');
      create('App\Reply', ['thread_id' => $threadOne->id], 2);

      // Thread with 3 replies
      $threadTwo = create('App\Thread');
      create('App\Reply', ['thread_id' => $threadTwo->id], 3);

      // Thread with 0 replies
      $threadThree = $this->thread;

      $response = $this->getJson('/threads?popular=1')->json();

      $this->assertEquals([3, 2, 0], array_column($response, 'replies_count'));
    }

    /** @test */
    function a_user_can_resquet_all_replies_for_given_thread()
    {
      $thread = create('App\Thread');
      create('App\Reply', ['thread_id' => $thread->id], 2);

      $response = $this->getJson($thread->path() . '/replies')->json();

      $this->assertCount(1, $response['data']);

    }
}
