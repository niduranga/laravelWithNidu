<?php

namespace Tests\Unit;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\RespondWithTokenAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginActionTest extends TestCase
{
    use RefreshDatabase;

    private LoginAction $loginAction;

    protected function setUp(): void
    {
        parent::setUp();

        $respondWithTokenAction = new RespondWithTokenAction();
        $this->loginAction = new LoginAction($respondWithTokenAction);
    }

    /** @test */
    public function test_it_returns_token_data_on_successful_login()
    {
        $password = 'password123';

        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt($password),
        ]);

        $attributes = [
            'email' => 'test@example.com',
            'password' => $password,
        ];

        $result = $this->loginAction->handle($attributes);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('access_token', $result);
        $this->assertArrayHasKey('token_type', $result);
        $this->assertEquals('bearer', $result['token_type']);
    }

    /** @test */
    public function test_it_returns_false_on_failed_login()
    {
        User::factory()->create([
            'email' => 'wrong@example.com',
            'password' => bcrypt('correct_password'),
        ]);

        $attributes = [
            'email' => 'wrong@example.com',
            'password' => 'incorrect_password',
        ];

        $result = $this->loginAction->handle($attributes);

        $this->assertFalse($result);
    }
}
