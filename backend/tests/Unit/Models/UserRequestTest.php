<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\UserRequest;
use PHPUnit\Framework\Attributes\Test;

class UserRequestTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_creates_a_user_request()
    {
        $user = User::factory()->create();

        $req = UserRequest::factory()->create([
            'user_id' => $user->id,
            'type' => 'DELETE_ACCOUNT'
        ]);

        $this->assertDatabaseHas('user_requests', [
            'user_id' => $user->id,
            'type'    => 'DELETE_ACCOUNT'
        ]);
    }

    #[Test]
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();

        $req = UserRequest::factory()->create([
            'user_id' => $user->id
        ]);

        $this->assertInstanceOf(User::class, $req->user);
        $this->assertEquals($user->id, $req->user->id);
    }

    #[Test]
    public function payload_is_cast_to_array()
    {
        $req = UserRequest::factory()->create([
            'payload' => ['model' => 'BMW']
        ]);

        $this->assertIsArray($req->payload);
        $this->assertEquals('BMW', $req->payload['model']);
    }

    #[Test]
    public function timestamps_are_carbon_instances()
    {
        $req = UserRequest::factory()->create();

        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $req->created_at);
        $this->assertInstanceOf(\Carbon\CarbonImmutable::class, $req->updated_at);
    }
}
