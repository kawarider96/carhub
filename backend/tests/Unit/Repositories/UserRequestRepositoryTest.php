<?php

namespace Tests\Unit\Repositories;

use App\Models\UserRequest;
use App\Models\User;
use App\Repositories\UserRequestRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class UserRequestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected UserRequestRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new UserRequestRepository(new UserRequest);
    }

    #[Test]
    public function test_create_delete_request()
    {
        $user = User::factory()->create();

        $req = $this->repo->create([
            'user_id' => $user->id,
            'status' => 'pending'
        ]);

        $this->assertDatabaseHas('delete_requests', [
            'id' => $req->id,
            'status' => 'pending'
        ]);
    }

    #[Test]
    public function test_pending_requests()
    {
        UserRequest::factory()->count(3)->create([
            'status' => 'pending'
        ]);

        UserRequest::factory()->create([
            'status' => 'approved'
        ]);

        $result = $this->repo->pending();

        $this->assertCount(3, $result);
    }

    #[Test]
    public function test_by_user()
    {
        $user = User::factory()->create();

        $req = UserRequest::factory()->create([
            'user_id' => $user->id
        ]);

        $found = $this->repo->byUser($user->id);

        $this->assertEquals($req->id, $found->id);
    }
}
