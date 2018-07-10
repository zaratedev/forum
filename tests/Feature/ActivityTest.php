<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Activity;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    public function it_records_activity_when_a_thread_is_created()
    {
        $this->singIn();

        $thread = create('App\Thread');

        $this->assertDatabaseHas('activities', [
           'type' => 'created_thread',
            'user_id' => auth()->id(),
            'subject_id' => $thread->id,
            'subject_type' => 'App\Thread'
        ]);

        $activity = Activity::first();

        $this->assertEquals($activity->subject->id, $thread->id);
    }

    public function it_records_activity_when_a_reply_is_created()
    {
        $this->singIn();

        $reply = create('App\Reply');

        $this->assertEquals(2, Activity::count());

    }
}