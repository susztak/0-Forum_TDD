<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
    use DatabaseMigrations;

    protected $thread;

    public function setUp()
    {
        parent::setUp();
        $this->thread = create('App\Thread');
    }

    /** @test */
    public function a_thread_can_make_a_string_path()
    {
        $thread = create('App\Thread');

        $this->assertEquals("/threads/{$thread->channel->slug}/{$thread->id}", $thread->path());
    }


    /** @test */
    public function a_thread_has_replies()
    {
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
    }

    /** @test */
    public function a_thread_has_a_creator()
    {

        $this->assertInstanceOf('App\User', $this->thread->creator);
    }

    /** @test */
    public function a_thread_can_add_a_reply()
    {
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);

        $this->assertCount(1, $this->thread->replies);
    }
    /** @test */
    public function a_thread_notifies_subs_if_a_reply_is_added()
    {
        Notification::fake();
        $this->signIn();
        $this->thread->subscribe();
        $this->thread->addReply([
            'body' => 'Foobar',
            'user_id' => 1
        ]);
        Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
     }
    

    /** @test */
    public function a_thread_belongs_to_a_channel()
    {
        $thread = create('App\Thread');
        $this->assertInstanceOf('App\Channel', $thread->channel);

    }

    /** @test */
    public function a_thread_can_be_subscribed_to()
    {
        $thread = create('App\Thread');


        $thread->subscribe($userId = 1);

        $this->assertEquals(
            1,
            $thread->subscriptions()->where('user_id', $userId)->count());
    }

    /** @test */
    public function a_thread_can_be_unsubscribed()
    {
        $thread = create('App\Thread');

        $thread->subscribe($userId = 1);
        $thread->unsubscribe($userId);

        $this->assertCount(0, $thread->subscriptions);
    }
    /** @test */
    public function it_knows_if_the_atuhenticated_user_is_subscribed()
    {
           $thread = create('App\Thread');

           $this->signIn();
           $this->assertFalse($thread->isSubscribedTo);
           $thread->subscribe();
           $this->assertTrue($thread->isSubscribedTo);
     }
     /** @test */
     public function a_thread_can_check_if_an_autchenticated_user_has_read_all_replies()
     {
            $this->signIn();
            $thread = create('App\Thread');
            $this->assertTrue($thread->hasUpdatesFor(auth()->user()));
            auth()->user()->read($thread);
            $this->assertFalse($thread->hasUpdatesFor(auth()->user()));
      }
     


}