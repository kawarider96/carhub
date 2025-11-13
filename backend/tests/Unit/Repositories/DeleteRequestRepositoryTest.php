<?php

namespace Tests\Unit\Repositories;

use App\Models\DeleteRequest;
use App\Models\User;
use App\Repositories\DeleteRequestRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteRequestRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected DeleteRequestRepository $repo;

    protected function setUp(): void
    {
        parent::setUp();
        $this->repo = new DeleteRequestRepository(new DeleteRequest);
    }

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

    public function test_pending_requests()
    {
        DeleteRequest::factory()->count(3)->create([
            'status' => 'pending'
        ]);

        DeleteRequest::factory()->create([
            'status' => 'approved'
        ]);

        $result = $this->repo->pending();

        $this->assertCount(3, $result);
    }

    public function test_by_user()
    {
        $user = User::factory()->create();

        $req = DeleteRequest::factory()->create([
            'user_id' => $user->id
        ]);

        $found = $this->repo->byUser($user->id);

        $this->assertEquals($req->id, $found->id);
    }
}
