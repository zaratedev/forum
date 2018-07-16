<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function mentioned_users_in_a_reply_are_notifed()
    {
        $user = create('App\User', ['name' => 'zaratedev']);

        $this->singIn($user);
        $cynthia = create('App\User', ['name' => 'cynthia']);

        $thread = create('App\Thread');

        $reply = make('App\Reply', [
            'body' => '@cynthia look at this. @erick view this.'
        ]);

        $this->post($thread->path().'/replies', $reply->toArray());

        $this->assertCount(1, $cynthia->notifications);
    }
}
