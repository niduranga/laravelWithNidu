<?php

namespace Tests\Unit;

use App\Actions\Auth\RegisterAction;
use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Mockery\MockInterface;
use ReflectionClass;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_listener_is_queued_with_correct_delay_and_priority(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $event = new UserRegistered($user);

        event($event);

        Event::assertDispatched(UserRegistered::class, function ($event) {
            return true;
        });

        $listener = new SendWelcomeEmail();
        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, $listener);

        $reflection = new \ReflectionClass($listener);
        $queueAttr = $reflection->getAttributes(\Illuminate\Queue\Attributes\Queue::class)[0];
        $delayAttr = $reflection->getAttributes(\Illuminate\Queue\Attributes\Delay::class)[0];

        $this->assertEquals('high', $queueAttr->getArguments()[0]);
        $this->assertEquals(60, $delayAttr->getArguments()[0]);
    }

    public function test_it_sends_welcome_email(): void
    {
        Mail::fake();

        $user = User::factory()->make();
        $event = new UserRegistered($user);

        $listener = new SendWelcomeEmail();
        $listener->handle($event);

        Mail::assertSent(WelcomeMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    public function test_listener_is_queued_on_high_priority(): void
    {
        $listener = new SendWelcomeEmail();

        $this->assertInstanceOf(\Illuminate\Contracts\Queue\ShouldQueue::class, $listener);

        $reflection = new ReflectionClass($listener);
        $queueAttr = $reflection->getAttributes(\Illuminate\Queue\Attributes\Queue::class)[0];

        $this->assertEquals('high', $queueAttr->getArguments()[0]);
    }

    public function test_it_creates_user_and_dispatches_event(): void
    {
        Event::fake();

        /** @var UserRepository|MockInterface $repository */
        $repository = $this->mock(UserRepository::class);

        $userData = User::factory()->make();

        $repository->shouldReceive('create')
            ->once()
            ->with($userData)
            ->andReturn($userData);

        $action = new RegisterAction($repository);
        $result = $action->handle($userData);

        $this->assertInstanceOf(User::class, $result);

        Event::assertDispatched(UserRegistered::class, function ($event) use ($userData) {
            return $event->user === $userData;
        });
    }

}
