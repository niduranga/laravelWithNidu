<?php

namespace Tests\Unit;

use App\Actions\Auth\Refresh;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RefreshActionTest extends TestCase
{
    use RefreshDatabase;

    private Refresh $refreshAction;

    protected function setUp(): void
    {
        parent::setUp();
        $this->refreshAction = new Refresh();
    }

    /** @test */
    public function test_it_refreshes_token_and_returns_json_response()
    {
        $user = User::factory()->create();
        $token = Auth::guard('api')->login($user);

        $this->withHeader('Authorization', 'Bearer ' . $token);

        $response = $this->refreshAction->refresh();

        $this->assertEquals(200, $response->getStatusCode());

        $data = $response->getData(true);

        $this->assertArrayHasKey('access_token', $data);
        $this->assertArrayHasKey('token_type', $data);
        $this->assertEquals('bearer', $data['token_type']);

        $this->assertNotEquals($token, $data['access_token']);
    }
}
